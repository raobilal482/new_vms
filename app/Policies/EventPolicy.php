<?php

namespace App\Policies;

use App\Enums\PermissionTypeEnum;
use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public const PERMISSIONS = [
        ['name' => 'event.view-any', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.view', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.create', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.edit', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.restore', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.force-delete', 'type' => PermissionTypeEnum::WEB],
        ['name' => 'event.whilelist-action', 'type' => PermissionTypeEnum::WEB],
    ];

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('event.view-any');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('event.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.edit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->hasPermissionTo('event.force-delete');
    }

    public function whitelistAction(User $user): bool
    {
        return $user->hasPermissionTo('event.whilelist-action');
    }
}
