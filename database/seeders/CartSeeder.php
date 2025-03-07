<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;
use App\Models\Product;

class CartSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $userId = 1;

        // Fetch some random products from the database
        $products = Product::inRandomOrder()->limit(5)->get();
        foreach ($products as $product) {
            DB::table('carts')->insert([
                'user_id'    => $userId,
                'product_id' => $product->id,
                'quantity'   => $faker->numberBetween(1, 10),
                'price'      => $product->price,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
