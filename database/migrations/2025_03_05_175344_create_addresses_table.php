<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Addresses table which is unused in this project. Please see design.
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users
            $table->string('street');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->default('Canada'); // Default to Canada
            $table->string('postal_code')->nullable(); // For Canada
            $table->string('zip_code')->nullable(); // For US
            $table->string('phone')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
