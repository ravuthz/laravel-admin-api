<?php

namespace Tests\Unit;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserRolePermissionTest extends TestCase
{
    public function test_save_user_role_permission(): void
    {
        // Create roles
        $readerRole = Role::saveByName('read only');
        $writerRole = Role::saveByName('write only');
        $auditorRole = Role::saveByName('audit only');

        // Create permissions
        $readerPermissions = Permission::saveResource('roles', ['index', 'show']);
        $writerPermissions = Permission::saveResource('roles', ['store', 'update', 'destroy']);
        $auditorPermissions = Permission::saveResource('roles', ['store', 'update']);

        // Assign permissions to roles
        $readerRole->permissions()->sync($readerPermissions->pluck('id'));
        $writerRole->permissions()->sync($writerPermissions->pluck('id'));
        $auditorRole->permissions()->sync($auditorPermissions->pluck('id'));

        // Create users
        $reader = User::saveByName('reader');
        $writer = User::saveByName('writer');
        $auditor = User::saveByName('auditor');

        // Assign roles to users
        $reader->roles()->sync($readerRole);
        $writer->roles()->sync($writerRole);
        $auditor->roles()->sync($auditorRole);

        // Check users, roles and permissions are exists in database
        $this->assertTrue(User::whereIn('id', [ $reader->id, $writer->id ])->exists());
        $this->assertTrue(Role::whereIn('id', [ $readerRole->id, $writerRole->id ])->exists());
        $this->assertTrue(Permission::whereIn('id',
            [
                ...$readerPermissions->pluck('id')->toArray(),
                ...$writerPermissions->pluck('id')->toArray(),
                ...$auditorPermissions->pluck('id')->toArray()
            ]
        )->exists());

        $this->assertTrue(
            DB::table('role_permissions')
                ->where('role_id', $readerRole->id)
                ->whereIn('permission_id', $readerPermissions->pluck('id'))
            ->exists(),
            'All Read-Only permissions to assigned to role'
        );

        $this->assertTrue(
            DB::table('role_permissions')
                ->where('role_id', $writerRole->id)
                ->whereIn('permission_id', $writerPermissions->pluck('id'))
                ->exists(),
            'All Write-Only permissions to assigned to role'
        );

    }
}
