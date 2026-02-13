<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UpdateUserPhotoRequest;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('pages.auth.settings.editprofile', [
            'user' => $request->user(),
        ]);
    }

    public function photo(Request $request): View
    {
        return view('pages.auth.settings.photo', [
            'user' => $request->user(),
        ]);
    }
    
    public function show(Request $request): View
    {
        return view('pages.auth.settings.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'phone'  => ['nullable','string','max:30'],
            'sosmed' => ['nullable','string','max:255'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return to_route('settings.profile.edit')->with('status', __('Profile updated successfully'));
    }

public function updatePhoto(UpdateUserPhotoRequest $request): RedirectResponse
{
    $user = $request->user();

    // pastikan policy update mengizinkan SELF + ADMIN (untuk my account)
    $this->authorize('update', $user);

    $dir = public_path('images/assets/userphoto');
    if (!File::exists($dir)) {
        File::makeDirectory($dir, 0755, true);
    }

    $default = 'images/assets/userphoto/default.png';

    // delete old photo (skip default)
    if (!empty($user->photo_path) && $user->photo_path !== $default) {
        $oldPath = public_path($user->photo_path);
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }
    }

    $file = $request->file('photo_path');
    $filename = 'u_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $file->move($dir, $filename);

    $user->update([
        'photo_path' => 'images/assets/userphoto/' . $filename,
    ]);

    return back()->with('success', 'Photo updated.');
}

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('home');
    }
}
