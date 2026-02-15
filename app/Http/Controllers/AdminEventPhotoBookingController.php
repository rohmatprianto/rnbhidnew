<?php

namespace App\Http\Controllers;

use App\Models\EventPhoto;
use App\Models\EventPhotoOrder;
use Illuminate\Http\Request;

class AdminEventPhotoBookingController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $events = EventPhoto::query()
            ->withCount('orders') // relasi: EventPhoto hasMany EventPhotoOrder
            ->when($q, function ($s) use ($q) {
                $s->where('title', 'like', "%{$q}%")->orWhere('location', 'like', "%{$q}%");
            })
            ->latest('event_date')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('eventphoto.admin-bookings.index', [
            'q' => $q,
            'events' => $events,
        ]);
    }

    public function show(Request $request, EventPhoto $eventPhoto)
    {
        $q = trim($request->input('q', ''));

        // ambil semua order (tidak paginate, karena mau group)
        $ordersQuery = $eventPhoto
            ->orders()
            ->when($q !== '', function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('guardian_name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%")
                        ->orWhere('rider_full_name', 'like', "%{$q}%")
                        ->orWhere('rider_nickname', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%")
                        ->orWhere('instagram', 'like', "%{$q}%");
                });
            })
            ->latest('id');

        $orders = $ordersQuery->get();

        // master categories dari category_notes event (selalu tampil walau kosong)
        $categories = collect(preg_split('/\s*;\s*/', trim($eventPhoto->category_notes ?? '')))
            ->map(fn($x) => trim($x))
            ->filter()
            ->values();

        // kalau event belum punya category_notes, fallback: unik dari orders
        if ($categories->isEmpty()) {
            $categories = $orders->pluck('category')->filter()->unique()->values();
        }

        // group orders by category
        $ordersByCategory = $orders->groupBy(fn($o) => $o->category ?: 'Uncategorized');

        // pastikan semua category ada key-nya walaupun kosong
        foreach ($categories as $cat) {
            $ordersByCategory->put($cat, $ordersByCategory->get($cat, collect()));
        }

        // optional: mau tampilkan Uncategorized juga selalu
        if (!$ordersByCategory->has('Uncategorized')) {
            $ordersByCategory->put('Uncategorized', collect());
        }

        $chartLabels = $categories->values()->all();

        $chartCounts = $categories->map(fn($cat) => $ordersByCategory->get($cat, collect())->count())->values()->all();

        return view('eventphoto.admin-bookings.show', [
            'eventPhoto' => $eventPhoto,
            'ordersByCategory' => $ordersByCategory,
            'categories' => $categories, // dipakai loop FE
            'q' => $q,
            'chartLabels' => $chartLabels,
            'chartCounts' => $chartCounts,
        ]);
    }

    public function editOrder(EventPhotoOrder $order)
{
    // optional: kalau mau pastikan admin only (sudah di route middleware)
    return view('eventphoto.admin-bookings.edit-order', [
        'order' => $order,
        'eventPhoto' => $order->eventPhoto, // relasi belongsTo sudah ada
    ]);
}

public function updateOrder(Request $request, EventPhotoOrder $order)
{
    $data = $request->validate([
        'guardian_name'     => ['required','string','max:120'],
        'email'             => ['required','email','max:120'],
        'phone'             => ['required','string','max:30'],
        'rider_full_name'   => ['required','string','max:120'],
        'rider_nickname'    => ['required','string','max:120'],
        'category'          => ['required','string','max:120'],
        'plate_no'          => ['nullable','string','max:50'],
        'batch'             => ['nullable','string','max:50'],
        'instagram'         => ['nullable','string','max:120'],
    ]);

    $order->update($data);

    return redirect()
        ->route('eventphoto.bookings.show', $order->event_photo_id)
        ->with('success', 'Order berhasil diupdate.');
}

public function destroyOrder(EventPhotoOrder $order)
{
    $eventId = $order->event_photo_id;
    $order->delete();

    return redirect()
        ->route('eventphoto.bookings.show', $eventId)
        ->with('success', 'Order berhasil dihapus.');
}

}
