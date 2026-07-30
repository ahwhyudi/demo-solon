<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 🔁 Reset cache permission (penting kalau pakai Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Role
        $role = Role::updateOrCreate([
            'name' => 'super admin'
        ]);

        // 2. Create Permissions
        $allPermission = getAllPermission();
        $permissions = [];

        foreach ($allPermission as $group) {
            foreach ($group as $perm) {
                $permission = Permission::updateOrCreate([
                    'name' => $perm
                ]);

                $permissions[] = $permission->name;
            }
        }

        // 3. Assign semua permission ke role (ANTI DUPLIKAT)
        $role->syncPermissions($permissions);

        // 4. Create / Update Users
        $users = [
            [
                'name' => 'super admin',
                'email' => 'superadmin@supernotaris.com',
                'password' => 'TeknikHijau1',
            ]
        ];

        foreach ($users as $u) {
            $user = User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                ]
            );

            // 5. Assign role ke user
            $user->assignRole($role);
        }
    }
}
