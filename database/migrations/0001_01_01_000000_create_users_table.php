<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('type')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Additional fields
            $table->string('phone')->nullable(); // Phone number
            $table->string('status')->nullable(); // Phone number
            $table->date('date_of_birth')->nullable(); // Date of birth
            $table->text('address')->nullable(); // Address (JSON format)
            $table->string('availability', 50)->default('Anytime'); // Availability
            $table->string('skills')->nullable(); // Additional skills
            $table->string('preferred_roles')->nullable(); // Preferred roles
            $table->boolean('is_active')->default(true); // Is the user active
            $table->string('profile_picture')->nullable(); // Profile picture URL
            $table->string('languages')->nullable(); // Languages spoken
            $table->string('emergency_contact_name')->nullable(); // Emergency contact name
            $table->string('emergency_contact_phone')->nullable(); // Emergency contact phone
            $table->text('motivation')->nullable(); // Why they want to volunteer
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index()->constrained('users')->onDelete('cascade'); // Foreign key constraint
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
