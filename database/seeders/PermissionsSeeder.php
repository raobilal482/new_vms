<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
            $permissions = [
                'create event',
                'edit event',
                'delete event',
                'view event',
                'approve event',

                'create task',
                'edit task',
                'delete task',
                'view task',

                'create time tracking',
                'edit time tracking',
                'delete time tracking',
                'view time tracking',

                'create role',
                'edit role',
                'delete role',
                'view role',

                'create permission',
                'edit permission',
                'delete permission',
                'view permission',

                'create user',
                'edit user',
                'delete user',
                'view user',
            ];

            foreach ($permissions as $permission) {
                Permission::firstOrCreate(['name' => $permission,'guard_name' => 'web']);
            }
        }
    }

