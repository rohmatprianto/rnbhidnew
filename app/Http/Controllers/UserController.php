<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * GET /users  (admin only)
     */
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
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

        $user->update($request->validated());

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'User updated.');
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
}
