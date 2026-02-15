<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\EventPhoto;

// class EventphotoController extends Controller
// {

// public function indexlist(Request $request)
//     {
//         $q = $request->string('q')->toString();

//         $events = EventPhoto::query()
//             ->when($q, fn($s) => $s->where('title', 'like', "%{$q}%")
//                 ->orWhere('location', 'like', "%{$q}%")
//             )
//             ->latest('event_date')
//             ->latest('id')
//             ->paginate(10)
//             ->withQueryString();

//         return view('eventphoto.adminindex', [
//             'title' => 'Event Photo',
//             'events' => $events,
//             'q' => $q,
//         ]);
//     }

namespace App\Http\Controllers;

use App\Models\EventPhoto;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class EventphotoController extends Controller
{
    public function indexlist(Request $request)
    {
        $q = $request->string('q')->toString();

        $events = EventPhoto::query()
            ->when($q, function ($s) use ($q) {
                $s->where(function ($w) use ($q) {
                    $w->where('title', 'like', "%{$q}%")
                      ->orWhere('location', 'like', "%{$q}%");
                });
            })
            ->latest('event_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('eventphoto.adminindex', [
            'title'  => 'Event Photo',
            'events' => $events,
            'q'      => $q,
        ]);
    }

    public function show(EventPhoto $event)
{
    return view('eventphoto.showevent', [
        'title' => 'Event Photo Detail',
        'event' => $event,
    ]);
}

    public function create()
    {
        return view('eventphoto.eventcreate', [
            'title' => 'Add Event Photo',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'location'    => ['nullable','string','max:255'],
            'event_date'  => ['required','date'],
            'status'      => ['required','in:open,closed,draft'],
            'price_notes' => ['nullable','string'], // simpan "a; b; c"
            'cover'       => ['nullable','image','max:2048'], // 2MB
        ]);

        if ($request->hasFile('cover')) {
            $dir = public_path('images/assets/eventcover');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);

            $file = $request->file('cover');
            $filename = 'ev_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $data['cover_path'] = 'images/assets/eventcover/' . $filename;
        }

        EventPhoto::create($data);

        return redirect()
            ->route('event.eventphoto.index')
            ->with('success', 'Event berhasil dibuat.');
    }

    public function edit(EventPhoto $event)
    {
        return view('eventphoto.eventedit', [
            'title' => 'Edit Event Photo',
            'event' => $event,
        ]);
    }

    public function update(Request $request, EventPhoto $event): RedirectResponse
    {
        $data = $request->validate([
            'title'       => ['required','string','max:255'],
            'location'    => ['nullable','string','max:255'],
            'event_date'  => ['required','date'],
            'status'      => ['required','in:open,closed,draft'],
            'price_notes' => ['nullable','string'],
            'cover'       => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('cover')) {
            $dir = public_path('images/assets/eventcover');
            if (!File::exists($dir)) File::makeDirectory($dir, 0755, true);

            // hapus cover lama jika ada
            if (!empty($event->cover_path)) {
                $old = public_path($event->cover_path);
                if (File::exists($old)) File::delete($old);
            }

            $file = $request->file('cover');
            $filename = 'ev_' . $event->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $data['cover_path'] = 'images/assets/eventcover/' . $filename;
        }

        $event->update($data);

        return redirect()
            ->route('event.eventphoto.show', $event)
            ->with('success', 'Event berhasil diupdate.');
    }

    public function destroy(EventPhoto $event): RedirectResponse
    {
        // hapus cover file (optional)
        if (!empty($event->cover_path)) {
            $old = public_path($event->cover_path);
            if (File::exists($old)) File::delete($old);
        }

        $event->delete();

        return redirect()
            ->route('event.eventphoto.index')
            ->with('success', 'Event berhasil dihapus.');
    }

    public function updateStatus(Request $request, EventPhoto $event): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required','in:open,closed,draft'],
        ]);

        $event->update($data);

        return back()->with('success', 'Status event diperbarui.');
    }
}


