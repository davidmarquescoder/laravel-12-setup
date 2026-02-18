<?php

use App\Enums\RoleEnum;
use App\Enums\PermissionEnum;
use App\Enums\RolePermissionsEnum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (RoleEnum::values() as $role) {
            Role::create(['name' => $role]);
        }

        foreach (PermissionEnum::values() as $permission) {
            Permission::create(['name' => $permission]);
        }

        $developerRole = Role::where('name', RoleEnum::DEVELOPER->value)->first();
        $developerRole->givePermissionTo(RolePermissionsEnum::DEVELOPER->permissions());

        $adminRole = Role::where('name', RoleEnum::ADMIN->value)->first();
        $adminRole->givePermissionTo(RolePermissionsEnum::ADMIN->permissions());

        $staffRole = Role::where('name', RoleEnum::STAFF->value)->first();
        $staffRole->givePermissionTo(RolePermissionsEnum::STAFF->permissions());
    }

    public function down(): void
    {
        //
    }
};
