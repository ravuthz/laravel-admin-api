<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keys = ['index', 'show', 'store', 'update', 'destroy'];

        $roleUser = Role::saveByName('user');
        $roleAdmin = Role::saveByName('admin');
        $roleEditor = Role::saveByName('editor');

        $userPermissions = Permission::saveResource('users', $keys);
        $rolePermissions = Permission::saveResource('roles', $keys);
        $permPermissions = Permission::saveResource('permissions', $keys);

        $allPermissions = $userPermissions->merge($rolePermissions)->merge($permPermissions);

        $allEditorPerms = $allPermissions->filter(function ($p) {
            return str($p->code)->endsWith(['.index', '.show', '.update']);
        });

        $roleUser->permissions()->sync($userPermissions->pluck('id'));
        $roleAdmin->permissions()->sync($allPermissions->pluck('id'));
        $roleEditor->permissions()->sync($allEditorPerms->pluck('id'));

        $user = User::saveByName('user');
        $admin = User::saveByName('admin');
        $editor = User::saveByName('editor');

        $user->roles()->sync($roleUser);
        $admin->roles()->sync($roleAdmin);
        $editor->roles()->sync($roleEditor);

    }
}
