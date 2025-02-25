<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permission; // Use your custom Permission model
use Illuminate\Auth\Access\Response;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view permission');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $user->can('view permission');
    }

    public function create(User $user): bool
    {
        return $user->can('create permission');
    }

    public function update(User $user, Permission $permission): bool
    {
        return $user->can('edit permission');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return $user->can('delete permission');
    }

    public function restore(User $user, Permission $permission): bool
    {
        return $user->can('edit permission');
    }

    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->can('delete permission');
    }
}
