<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* delete */
        Schema::disableForeignKeyConstraints();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();
        /* end delete */

        $userPermissions = [];

        $adminPerissions = [
            'admin access settings'
        ];
        $permissions = array_merge($userPermissions, $adminPerissions);

        foreach ($permissions as $permission) Permission::create(['name' => $permission]);

        $roles = [];
        $roles['user'] = Role::create(['name' => 'user'])->syncPermissions($userPermissions);
        $roles['admin'] = Role::create(['name' => 'admin'])->syncPermissions($adminPerissions);
    }
}
