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
        Schema::create('types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the type (e.g., Admin, Volunteer, Fundraiser)
            $table->string('slug')->unique(); // Unique identifier (e.g., admin, volunteer, event)
            $table->string('category')->nullable(); // Category of the type (e.g., User Type, Event Type)
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types');
    }
};
