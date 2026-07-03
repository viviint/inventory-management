<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'staff', 'manager'];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore([
                'name'       => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
