<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Event title
            $table->text('description')->nullable(); // Event description
            $table->string('location'); // Event location
            $table->timestamp('start_time'); // Event start time
            $table->timestamp('end_time'); // Event end time
            $table->unsignedInteger('max_volunteers'); // Maximum number of volunteers
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin ID (foreign key to Users table)
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null'); // Manager ID (foreign key to Users table)
            $table->foreignId('volunteer_id')->nullable()->constrained('users')->onDelete('set null'); // Manager ID (foreign key to Users table)
            $table->string('type')->default('General'); // Type of event (e.g., General, Fundraiser, Workshop)
            $table->boolean('is_virtual')->default(false); // Whether the event is virtual
            $table->string('platform_link')->nullable(); // Virtual platform link (if applicable)
            $table->text('requirements')->nullable(); // Event-specific requirements
            $table->string('contact_email')->nullable(); // Contact email for the event
            $table->string('contact_phone')->nullable(); // Contact phone for the event
            $table->string('status')->default('Upcoming'); // Event status
            $table->unsignedBigInteger('duration')->nullable(); // Event duration in minutes
            $table->text('tags')->nullable(); // Event tags (e.g., for categorization)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('events');
    }
};
