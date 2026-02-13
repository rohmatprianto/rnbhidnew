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

    public function view(User $authUser, User $user): bool
{
    return (bool) ($authUser->is_admin ?? false);
}

public function update(User $authUser, User $user): bool
{
    return (bool) ($authUser->is_admin ?? false) || $authUser->id === $user->id;
}


    // ADMIN only: hapus user
    public function delete(User $authUser, User $user): bool
    {
        return (bool) ($authUser->is_admin ?? false);
    }
    
    public function restore(User $auth, User $user): bool
{
    return (bool) ($auth->is_admin ?? false);
}

public function updateStatus(User $auth, User $user): bool
{
    return (bool) ($auth->is_admin ?? false);
}



}
