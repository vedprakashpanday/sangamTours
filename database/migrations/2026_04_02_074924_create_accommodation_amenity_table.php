<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('accommodation_amenity', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('accommodation_id');
        $table->unsignedBigInteger('amenity_id');

        // Foreign keys (Agar hotel delete ho toh ye link bhi delete ho jaye)
        $table->foreign('accommodation_id')->references('id')->on('accommodations')->onDelete('cascade');
        $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_amenity');
    }
};
