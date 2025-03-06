<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Taxes table with a flag to enable and disable it
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->decimal('gst', 5, 2)->default(5.00);
            $table->decimal('qst', 5, 2)->default(9.9);
            $table->boolean('is_active')->default(true); // Flag to enable/disable tax
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};

