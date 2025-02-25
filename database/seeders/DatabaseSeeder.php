<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsSeeder::class);
        $adminUser = User::firstOrCreate(
            ['email' => 'test@vms.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'type' => 'Admin',
            ]
        );

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);

        $adminUser->assignRole($adminRole);
    }
}
