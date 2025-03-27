<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public const PERMISSIONS = [
        ['name' => 'task.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'task.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'task.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'task.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'task.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'task.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'task.force-delete', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('task.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('task.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $user->hasPermissionTo('task.force-delete');
    }
}