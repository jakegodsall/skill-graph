<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* delete */
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();
        /* end delete */

        // Create a dev user
        User::create([
                'name' => 'Dev User',
                'email' => 'user@dev.com',
                'password' => Hash::make('password'),
            ])->assignRole('user');

        // Create an admin user
        User::create([
                'name' => 'Admin User',
                'email' => 'admin@dev.com',
                'password' => Hash::make('password'),
            ])->assignRole('admin');
    }
}
