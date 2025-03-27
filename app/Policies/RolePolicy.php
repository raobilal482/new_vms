<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\Role; // Your custom Role model or Spatie's Role
use App\Models\User;

class RolePolicy
{
    public const PERMISSIONS = [
        ['name' => 'role.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'role.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'role.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'role.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'role.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'role.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'role.force-delete', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('role.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('role.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->hasPermissionTo('role.force-delete');
    }
}