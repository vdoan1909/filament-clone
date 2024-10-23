<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role = Role::create(
            [
                'name' => 'Admin',
                'slug' => 'admin',
            ]
        );

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password')
        ]);

        DB::table('role_users')->insert([
            'role_id' => $role->id,
            'user_id' => $user->id,
            'created_at' => now(),
            'updated_at' => now(),  
        ]);
    }
}
