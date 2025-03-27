<?php

namespace Database\Seeders;

use App\Policies\EventPolicy;
use App\Policies\FeedbackPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\TaskPolicy;
use App\Policies\TimeTrackingPolicy;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Permission; // Use Spatie's Permission model
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // List of all policy classes with PERMISSIONS constants
        $policyClasses = [
            EventPolicy::class,
            FeedbackPolicy::class,
            PermissionPolicy::class,
            RolePolicy::class,
            TaskPolicy::class,
            TimeTrackingPolicy::class,
            UserPolicy::class,
        ];

        // Collect all permissions from the policies
        $permissions = [];
        foreach ($policyClasses as $policyClass) {
            if (defined("$policyClass::PERMISSIONS")) {
                foreach ($policyClass::PERMISSIONS as $permission) {
                    $permissions[] = [
                        'name' => $permission['name'],
                        'guard_name' => 'web', // Adjust if using a different guard
                    ];
                }
            }
        }

        // Seed the permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
            ]);
        }
    }
}