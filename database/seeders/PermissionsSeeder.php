<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
            $permissions = [
                // Event Permissions
                'create event',
                'edit event',
                'delete event',
                'view event',

                // Task Permissions
                'create task',
                'edit task',
                'delete task',
                'view task',

                // Time Tracking Permissions
                'create time tracking',
                'edit time tracking',
                'delete time tracking',
                'view time tracking',

                // Role & Permission Management
                'create role',
                'edit role',
                'delete role',
                'view role',

                'create permission',
                'edit permission',
                'delete permission',
                'view permission',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission]);
            }
        }
    }

