<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('taxes')->insert([
            'gst' => 5.00,
            'qst' => 9.975,
            'is_active' => true, // Tax is the active by default
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
