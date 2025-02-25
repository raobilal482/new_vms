<?php

namespace App\Policies;

use App\Models\TimeTracking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TimeTrackingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view time tracking');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TimeTracking $timeTracking): bool
    {
        return $user->can('view time tracking');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create time tracking');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TimeTracking $timeTracking): bool
    {
        return $user->can('edit time tracking');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TimeTracking $timeTracking): bool
    {
        return $user->can('delete time tracking');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TimeTracking $timeTracking): bool
    {
        return $user->can('edit time tracking'); // Assuming restore falls under edit permission
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TimeTracking $timeTracking): bool
    {
        return $user->can('delete time tracking'); // Assuming force delete falls under delete permission
    }
}
