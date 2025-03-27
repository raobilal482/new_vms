<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\Permission; // Your custom Permission model
use App\Models\User;

class PermissionPolicy
{
    public const PERMISSIONS = [
        ['name' => 'permission.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'permission.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'permission.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'permission.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'permission.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'permission.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'permission.force-delete', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('permission.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('permission.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return $user->hasPermissionTo('permission.force-delete');
    }
}