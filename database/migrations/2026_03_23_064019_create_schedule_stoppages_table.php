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
       Schema::create('schedule_stoppages', function (Blueprint $table) {
    $table->id();
    $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
    $table->foreignId('location_id')->constrained(); // Kaun sa city/stop hai
    $table->time('arrival_time')->nullable();
    $table->time('departure_time')->nullable();
    $table->integer('stop_order'); // 0 for Start, 1, 2 for Stoppages, 99 for Destination
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_stoppages');
    }
};
