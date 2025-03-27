<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\TimeTracking;
use App\Models\User;

class TimeTrackingPolicy
{
    public const PERMISSIONS = [
        ['name' => 'time-tracking.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'time-tracking.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'time-tracking.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'time-tracking.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'time-tracking.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'time-tracking.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'time-tracking.force-delete', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('time-tracking.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeTracking $timeTracking): bool
    {
        return $user->hasPermissionTo('time-tracking.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('time-tracking.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeTracking $timeTracking): bool
    {
        return $user->hasPermissionTo('time-tracking.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeTracking $timeTracking): bool
    {
        return $user->hasPermissionTo('time-tracking.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TimeTracking $timeTracking): bool
    {
        return $user->hasPermissionTo('time-tracking.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TimeTracking $timeTracking): bool
    {
        return $user->hasPermissionTo('time-tracking.force-delete');
    }
}