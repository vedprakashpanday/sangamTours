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
       Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained();
        $table->foreignId('route_id')->constrained();
        
        // Timing
        $table->time('departure_time');
        $table->time('arrival_time');
        
        // Availability Logic
        // 1. Specific Date (e.g. sirf 23/03/2026 ko)
        $table->date('specific_date')->nullable(); 
        
        // 2. Recurring (e.g. Daily, ya sirf Mon, Tue)
        $table->string('days_of_week')->nullable(); // Store as JSON or comma separated: ["Mon", "Wed"]
        
        $table->decimal('fare_override', 10, 2)->nullable(); // Agar us din price alag ho
        $table->tinyInteger('status')->default(1);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
