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
        Schema::create('time_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained('users')->onDelete('cascade'); // Foreign key to users (volunteers)
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade'); // Foreign key to events
            $table->timestamp('checkin_time')->nullable(); // Check-in time
            $table->timestamp('checkout_time')->nullable(); // Check-out time
            $table->unsignedBigInteger('hours_logged')->nullable(); // Total hours logged
            $table->boolean('break_included')->default(false); // Whether a break is included in the time logged
            $table->unsignedBigInteger('break_duration_minutes')->default(0); // Duration of the break (in minutes)
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetrackings');
    }
};
