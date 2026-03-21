<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('accommodations', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('hotel_type'); // e.g., 3 Star, 5 Star, Resort
        $table->unsignedBigInteger('location_id'); // Link to locations table
        $table->decimal('price_per_night', 10, 2);
        $table->text('description')->nullable();
        $table->tinyInteger('status')->default(1); // 1: Active, 0: Inactive
        $table->softDeletes(); // Trash system ke liye
        $table->timestamps();

        $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodations');
    }
};
