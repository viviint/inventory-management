<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics',     'description' => 'Electronic devices and components'],
            ['name' => 'Furniture',       'description' => 'Office and workspace furniture'],
            ['name' => 'Stationery',      'description' => 'Paper, pens, and office supplies'],
            ['name' => 'Networking',      'description' => 'Cables, switches, routers, and networking equipment'],
            ['name' => 'Tools',           'description' => 'Hand tools and power tools'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insertOrIgnore([
                'name'        => $category['name'],
                'description' => $category['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
