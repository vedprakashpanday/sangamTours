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
       Schema::create('package_stays', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tour_package_id')->constrained('tour_packages')->onDelete('cascade');
    $table->integer('days');
    $table->integer('nights');
    $table->string('place_description');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_stays');
    }
};
