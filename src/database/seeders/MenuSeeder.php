<?php

namespace Database\Seeders;

use App\Models\Auth\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MenuSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* delete */
        Schema::disableForeignKeyConstraints();
        DB::table('menu')->truncate();
        DB::table('menu_permissions')->truncate();
        Schema::enableForeignKeyConstraints();
        /* end delete */

        // create menu
        DB::table('menu')->insert([
            // Admin
            [
                'id'                => 1,
                'title'             => 'Dashboard',
                'icon'              => 'far fa-home',
                'linkable_id'       => null,
                'linkable_type'     => null,
                'link'              => '/',
                'dropdownOnly'      => false,
                'internal'          => true,
                'all_permissions'   => true,
                'active'            => true,
                'parent_id'         => null,
                'pos'               => 1,
                'type'              => 'Admin',
                'created_at'        => now(),
                'updated_at'        => now()
            ],
            [
                'id'                => 2,
                'title'             => 'Settings',
                'icon'              => 'far fa-gear-complex',
                'linkable_id'       => null,
                'linkable_type'     => null,
                'link'              => '/admin/settings',
                'dropdownOnly'      => false,
                'internal'          => true,
                'all_permissions'   => true,
                'active'            => true,
                'parent_id'         => null,
                'pos'               => 2,
                'type'              => 'Admin',
                'created_at'        => now(),
                'updated_at'        => now()
            ]
        ]);

        // Menu Permissions
        $permissions = DB::table('permissions')->pluck('id', 'name')->toArray();

        DB::table('menu_permissions')->insert([
            // Admin
            ['menu_id' => 2, 'permission_id' => $permissions['admin access settings']], // Settings
        ]);
    }
}
