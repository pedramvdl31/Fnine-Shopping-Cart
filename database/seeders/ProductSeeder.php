<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Racing Motorcycle A',
                'description' => 'Description for Motorcycle A.',
                'price' => 8999.99,
                'image_url' => 'assets/images/1.jpg',
                'stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sport Motorcycle B',
                'description' => 'Description for Motorcycle B.',
                'price' => 12999,
                'image_url' => 'assets/images/2.jpg',
                'stock' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adventure Motorcycle C',
                'description' => 'Description for Motorcycle C.',
                'price' => 15999.99,
                'image_url' => 'assets/images/3.jpg',
                'stock' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
