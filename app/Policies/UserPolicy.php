<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // ADMIN only: lihat list user (index), create, store, dst
    public function viewAny(User $authUser): bool
    {
        return (bool) ($authUser->is_admin ?? false);
    }

    // SELF + ADMIN: lihat detail user
    public function view(User $authUser, User $user): bool
    {
        return (bool) ($authUser->is_admin ?? false) || $authUser->id === $user->id;
    }

    // SELF + ADMIN: edit/update user
    public function update(User $authUser, User $user): bool
    {
        return (bool) ($authUser->is_admin ?? false) || $authUser->id === $user->id;
    }

    // ADMIN only: hapus user
    public function delete(User $authUser, User $user): bool
    {
        return (bool) ($authUser->is_admin ?? false);
    }
}
