<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('giver_id')->constrained('users')->onDelete('cascade'); // Who gave the feedback
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade'); // User receiving feedback (null for event/task)
            $table->foreignId('event_id')->nullable()->constrained('events')->onDelete('cascade'); // Event feedback (null if not event-related)
            $table->foreignId('task_id')->nullable()->constrained('tasks')->onDelete('cascade'); // Task feedback (null if not task-related)
            $table->text('comment'); // Feedback text
            $table->unsignedTinyInteger('rating')->nullable(); // Rating (e.g., 1-5)
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('feedback');
    }
};
