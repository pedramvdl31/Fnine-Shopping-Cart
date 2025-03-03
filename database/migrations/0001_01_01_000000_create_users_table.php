<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable(); // Full name of the user
            $table->string('first_name')->nullable(); // First name for personalization
            $table->string('last_name')->nullable(); // Last name for personalization
            $table->string('slug')->unique()->nullable(); // Unique identifier for user profile (e.g., URL-friendly name)
            $table->string('email')->unique(); // Email address
            $table->timestamp('email_verified_at')->nullable(); // Verification timestamp
            $table->string('password'); // Encrypted password

            // OAuth-related fields
            $table->string('oauth_id')->nullable(); // OAuth provider ID (e.g., LinkedIn, Facebook)
            $table->string('oauth_type')->nullable(); // Type of OAuth provider (e.g., 'linkedin', 'facebook')
            $table->string('login_method')->nullable(); // Method used for login (e.g., 'linkedin', 'facebook')

            // Profile details
            $table->string('linkedin_avatar')->nullable(); // LinkedIn profile picture URL
            $table->string('facebook_id')->nullable()->unique(); // Facebook ID for linking accounts
            $table->text('facebook_avatar')->nullable(); // Facebook profile picture URL
            $table->string('profile_banner')->nullable(); // User's profile banner image

            // Additional metadata
            $table->string('order-testing')->nullable(); // Placeholder for testing or custom metadata
            $table->timestamp('last_activity')->nullable()->index(); // Index for last activity to optimize sorting and filtering
            $table->rememberToken(); // Token for "remember me" functionality
            $table->softDeletes(); // Adds the deleted_at column for soft deletes
            $table->timestamps(); // Created and updated timestamps
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Email as the primary key
            $table->string('token')->index(); // Index for token to optimize lookup
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Primary key for session ID
            $table->foreignId('user_id')->nullable()->index(); // Index for user_id to optimize queries related to users
            $table->string('ip_address', 45)->nullable()->index(); // Index for IP address (optional for analytics)
            $table->text('user_agent')->nullable(); // User agent (no index needed due to its size and rarity of filtering by it)
            $table->longText('payload');
            $table->integer('last_activity')->index(); // Index for last activity to optimize session cleanup
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

