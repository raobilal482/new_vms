<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\User;

class UserPolicy
{
    public const PERMISSIONS = [
        ['name' => 'user.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'user.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'user.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'user.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'user.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'user.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'user.force-delete', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('user.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('user.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->hasPermissionTo('user.force-delete');
    }
}