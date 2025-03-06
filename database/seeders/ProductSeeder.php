<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Generating 30 products
        for ($i = 0; $i < 30; $i++) {
            DB::table('products')->insert([
                'name' => ucfirst($faker->word) . ' Motorcycle', // fake name
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 5000, 20000), // Generate price between 5000 and 20000
                'image_url' => 'assets/images/' . rand(1, 4) . '.jpg', // Picks a random image from 1 to 5, I have 5 images saved in the folder
                'stock' => $faker->numberBetween(1, 10), // generates stock between 1 and 10
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
