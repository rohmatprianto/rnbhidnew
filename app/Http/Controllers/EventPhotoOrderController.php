<?php

namespace App\Http\Controllers;

use App\Models\EventPhoto;
use App\Models\EventPhotoOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventPhotoOrderController extends Controller
{

    public function index()
{
    $eventPhotos = EventPhoto::query()
        ->latest()
        ->get();

    return view('eventphoto.index', compact('eventPhotos'));
}

public function create(EventPhoto $eventPhoto)
{

    return view('eventphoto.formpesan', compact('eventPhoto'));
}

    public function store(Request $request, EventPhoto $eventPhoto): RedirectResponse
    {
        // optional: block jika event closed
        if (($eventPhoto->status ?? '') !== 'open') {
            return back()->with('error', 'Event tidak tersedia untuk pemesanan.')->withInput();
        }

        $data = $request->validate([
            'guardian_name'     => ['required','string','max:120'],
            'email'             => ['required','email','max:190'],
            'phone'             => ['required','string','max:30'],
            'rider_full_name'   => ['required','string','max:120'],
            'rider_nickname'    => ['required','string','max:80'],
            'category'          => ['required','string','max:80'],
            'plate_number'      => ['nullable','string','max:50'],
            'batch'             => ['nullable','string','max:50'],
            'instagram'         => ['nullable','string','max:80'],
        ]);

        $data['event_photo_id'] = $eventPhoto->id;

        // opsional kalau order harus login:
        // $data['user_id'] = auth()->id();

        EventPhotoOrder::create($data);

        return redirect()
            ->route('eventphoto.index')
            ->with('success', 'Order berhasil dibuat.');
    }
}
