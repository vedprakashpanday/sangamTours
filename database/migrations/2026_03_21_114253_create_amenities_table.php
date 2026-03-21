<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up() {
    Schema::create('amenities', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // e.g., WiFi, AC, Meal, Water
        $table->string('icon')->nullable(); // FontAwesome or BoxIcons class
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
