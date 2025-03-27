<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public const PERMISSIONS = [
        ['name' => 'feedback.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'feedback.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'feedback.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'feedback.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'feedback.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'feedback.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'feedback.force-delete', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('feedback.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('feedback.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('feedback.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('feedback.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('feedback.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('feedback.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Feedback $feedback): bool
    {
        return $user->hasPermissionTo('feedback.force-delete');
    }
}