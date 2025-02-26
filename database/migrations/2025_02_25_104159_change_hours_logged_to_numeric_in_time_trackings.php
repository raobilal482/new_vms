<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('time_trackings', function (Blueprint $table) {
            // Change hours_logged from bigint to numeric(10,2)
            
        });
    }

    public function down(): void
    {
        Schema::table('time_trackings', function (Blueprint $table) {
            // Revert back to bigint if needed
            $table->bigInteger('hours_logged')->nullable()->change();
        });
    }
};
