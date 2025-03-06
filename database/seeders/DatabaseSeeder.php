<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeding 1 user, products and 1 tax row for testing
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            TaxesSeeder::class
        ]);
    }
}
