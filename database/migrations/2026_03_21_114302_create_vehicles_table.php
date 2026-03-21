<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up() {
    Schema::create('vehicles', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
        $table->enum('type', ['Flight', 'Bus', 'Train']);
        $table->string('vehicle_number'); // e.g., 6E-213 or BR-01-1234
        $table->integer('total_seats');
        $table->decimal('base_fare', 10, 2);
        $table->string('seat_type')->nullable(); // Economy, Business, Sleeper, Seater
        $table->tinyInteger('status')->default(1);
        $table->softDeletes();
        $table->timestamps();
    });

    // Pivot table for Amenities (Many-to-Many)
    Schema::create('amenity_vehicle', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
        $table->foreignId('amenity_id')->constrained()->onDelete('cascade');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
