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
        Schema::create('tour_packages', function (Blueprint $table) {
    $table->id(); // Primary Key
    $table->string('package_id')->unique(); // Aapki demanded Varchar ID (e.g. PKG-123)
    $table->string('title');
    $table->decimal('price', 10, 2);
    $table->decimal('discount_price', 10, 2)->nullable();
    $table->text('details')->nullable();
    $table->foreignId('location_id')->constrained('locations'); // Common location table se link
    $table->boolean('status')->default(1); // 1 = Active, 0 = Inactive
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_packages');
    }
};
