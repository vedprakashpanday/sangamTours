<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/xxxx_create_routes_table.php
public function up() {
    Schema::create('routes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('from_city_id')->constrained('locations')->onDelete('cascade');
        $table->foreignId('to_city_id')->constrained('locations')->onDelete('cascade');
        $table->string('distance')->nullable(); // e.g., 500 KM
        $table->string('duration')->nullable(); // e.g., 02h 30m
        $table->tinyInteger('status')->default(1);
        $table->softDeletes();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
