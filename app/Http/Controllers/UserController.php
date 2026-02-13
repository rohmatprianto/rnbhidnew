<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserPhotoRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class UserController extends Controller
{
    /**
     * GET /users  (admin only)
     */
    public function index(Request $request): View
{
    $this->authorize('viewAny', User::class);

    $tab = $request->query('tab', 'users'); // users | reviewusers | deleteusers
    $q   = trim((string) $request->query('q', ''));

    // Counts untuk badge (tanpa search)
    $counts = [
        'users'       => User::query()->whereNull('deleted_at')->where('status', 'aktif')->count(),
        'reviewusers' => User::query()->whereNull('deleted_at')->where('status', 'review')->count(),
        'deleteusers' => User::onlyTrashed()->count(),
    ];

    // Base query per tab
    $query = User::query()->latest();

    if ($tab === 'deleteusers') {
        $query->onlyTrashed();
    } elseif ($tab === 'reviewusers') {
        $query->where('status', 'review');
    } else {
        // default tab users
        $query->where('status', 'aktif');
    }

    // Search filter (untuk semua tab termasuk deleted)
    if ($q !== '') {
        $query->where(function ($sub) use ($q) {
            $sub->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhere('sosmed', 'like', "%{$q}%");
        });
    }

    $users = $query->paginate(10)->withQueryString();

    // Jika request AJAX (fetch Alpine), return partial table saja
    if ($request->ajax()) {
        return view('users.partials.tableuser', compact('users', 'tab'));
    }

    return view('users.index', compact('users', 'tab', 'q', 'counts'));
}

    /**
     * GET /users/create  (admin only)
     */
    public function create(): View
    {
        $this->authorize('viewAny', User::class);

        return view('users.create');
    }

    /**
     * POST /users  (admin only)
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('viewAny', User::class);

        $data = $request->validated();

        if ($request->hasFile('photo')) {
    $dir = public_path('images/assets/userphoto');
    if (!File::exists($dir)) {
        File::makeDirectory($dir, 0755, true);
    }

    $file = $request->file('photo');
    $filename = 'u_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $file->move($dir, $filename);

    // simpan path relatif untuk dipakai di asset()
    $data['photo'] = 'images/assets/userphoto/' . $filename;
}

        // Password wajib di StoreUserRequest, jadi aman.
        $data['password'] = Hash::make($data['password']);

        // Jika Anda mengizinkan admin set is_admin via form, StoreUserRequest harus memvalidasi 'is_admin'
        // Jika tidak, hapus 'is_admin' dari request rules.
        $user = User::create($data);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'User created.');
    }

    /**
     * GET /users/{user}  (self + admin)
     */
    public function show(User $user): View
    {
        $this->authorize('view', $user);

        return view('users.show', compact('user'));
    }

    /**
     * GET /users/{user}/edit  (self + admin)
     */
    public function edit(User $user): View
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * PUT/PATCH /users/{user}  (self + admin)
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
{
    $this->authorize('update', $user);

    $data = $request->validated();

    // extra hardening (walaupun sudah prohibited + fillable)
    unset($data['is_admin'], $data['email_verified_at'], $data['remember_token']);

    // jika password kosong/null, jangan overwrite
    if (empty($data['password'])) {
        unset($data['password']);
    }

    if ($request->hasFile('photo')) {
    $dir = public_path('images/assets/userphoto');
    if (!File::exists($dir)) {
        File::makeDirectory($dir, 0755, true);
    }

    // hapus foto lama (jika ada)
    if (!empty($user->photo)) {
        $oldPath = public_path($user->photo);
        if (File::exists($oldPath)) {
            File::delete($oldPath);
        }
    }

    $file = $request->file('photo');
    $filename = 'u_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

    $file->move($dir, $filename);

    $data['photo'] = 'images/assets/userphoto/' . $filename;
}

    $user->update($data);

    return redirect()
        ->route('users.show', $user)
        ->with('success', 'User updated.');
}

// public function updatePhoto(UpdateUserPhotoRequest $request, User $user): RedirectResponse
public function updatePhoto(UpdateUserPhotoRequest $request, User $user)
{
    $this->authorize('update', $user);

    $dir = public_path('images/assets/userphoto');
    if (!File::exists($dir)) {
        File::makeDirectory($dir, 0755, true);
    }

    // delete old photo (skip default)
    $default = 'images/assets/userphoto/default.png';
    if (!empty($user->photo) && $user->photo !== $default) {
        $oldPath = public_path($user->photo);
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

    // return redirect()
    //     ->route('users.edit', $user)
    //     ->with('success', 'Photo updated.');
}


    /**
     * DELETE /users/{user}  (admin only)
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);

        // admin tidak boleh hapus dirinya sendiri
    if (Auth::id() === $user->id) {
        return back()->with('error', 'Cannot delete your own account.');
    }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted.');
    }

    // Restore user yang sudah di soft delete (admin only)
    public function restore(int $id): RedirectResponse
{
    $user = User::onlyTrashed()->findOrFail($id);

    $this->authorize('restore', $user);

    $user->restore();

    return redirect()
        ->route('users.index', ['trashed' => 1])
        ->with('success', 'User restored.');
}

// Update status user (admin only)
public function updateStatus(Request $request, User $user): RedirectResponse
{
    $this->authorize('updateStatus', $user);

    $data = $request->validate([
        'status' => ['required', Rule::in(['aktif','review','reject'])],
    ]);

    // update status langsung via forceFill (karena status tidak ada di fillable)
    $user->forceFill(['status' => $data['status']])->save();

    return back()->with('success', 'Status updated.');
}

}
