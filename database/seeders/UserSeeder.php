<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminId   = DB::table('roles')->where('name', 'admin')->value('id');
        $staffId   = DB::table('roles')->where('name', 'staff')->value('id');
        $managerId = DB::table('roles')->where('name', 'manager')->value('id');

        $users = [
            [
                'role_id'    => $adminId,
                'name'       => 'Admin User',
                'email'      => 'admin@inventory.test',
                'password'   => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id'    => $staffId,
                'name'       => 'Staff User',
                'email'      => 'staff@inventory.test',
                'password'   => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id'    => $managerId,
                'name'       => 'Manager User',
                'email'      => 'manager@inventory.test',
                'password'   => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore($user);
        }
    }
}
