<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = DB::table('categories')->where('name', 'Electronics')->value('id');
        $furniture   = DB::table('categories')->where('name', 'Furniture')->value('id');
        $stationery  = DB::table('categories')->where('name', 'Stationery')->value('id');
        $networking  = DB::table('categories')->where('name', 'Networking')->value('id');
        $tools       = DB::table('categories')->where('name', 'Tools')->value('id');

        $products = [
            ['category_id' => $electronics, 'code' => 'PRD-0001', 'name' => 'Laptop Dell Latitude',     'stock' => 10, 'location' => 'Storage A1', 'condition' => 'good'],
            ['category_id' => $electronics, 'code' => 'PRD-0002', 'name' => 'Monitor LG 24"',           'stock' => 8,  'location' => 'Storage A2', 'condition' => 'good'],
            ['category_id' => $electronics, 'code' => 'PRD-0003', 'name' => 'Wireless Mouse Logitech',  'stock' => 20, 'location' => 'Storage A3', 'condition' => 'good'],
            ['category_id' => $electronics, 'code' => 'PRD-0004', 'name' => 'Mechanical Keyboard',      'stock' => 15, 'location' => 'Storage A3', 'condition' => 'good'],
            ['category_id' => $electronics, 'code' => 'PRD-0005', 'name' => 'Webcam Logitech C920',     'stock' => 0,  'location' => 'Storage A4', 'condition' => 'damaged'],
            ['category_id' => $furniture,   'code' => 'PRD-0006', 'name' => 'Office Chair Ergonomic',   'stock' => 5,  'location' => 'Warehouse B1', 'condition' => 'good'],
            ['category_id' => $furniture,   'code' => 'PRD-0007', 'name' => 'Standing Desk 160cm',      'stock' => 3,  'location' => 'Warehouse B2', 'condition' => 'good'],
            ['category_id' => $stationery,  'code' => 'PRD-0008', 'name' => 'Whiteboard Marker Set',    'stock' => 50, 'location' => 'Storage C1', 'condition' => 'good'],
            ['category_id' => $stationery,  'code' => 'PRD-0009', 'name' => 'A4 Paper Ream (500 sheets)','stock' => 100,'location' => 'Storage C1', 'condition' => 'good'],
            ['category_id' => $networking,  'code' => 'PRD-0010', 'name' => 'Cat6 Ethernet Cable 10m',  'stock' => 30, 'location' => 'Storage D1', 'condition' => 'good'],
            ['category_id' => $networking,  'code' => 'PRD-0011', 'name' => 'Cisco 8-Port Switch',      'stock' => 4,  'location' => 'Storage D2', 'condition' => 'good'],
            ['category_id' => $tools,       'code' => 'PRD-0012', 'name' => 'Screwdriver Set (12 pcs)', 'stock' => 7,  'location' => 'Storage E1', 'condition' => 'good'],
        ];

        foreach ($products as $product) {
            DB::table('products')->insertOrIgnore([
                'category_id' => $product['category_id'],
                'code'        => $product['code'],
                'name'        => $product['name'],
                'stock'       => $product['stock'],
                'location'    => $product['location'],
                'condition'   => $product['condition'],
                'image'       => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}
