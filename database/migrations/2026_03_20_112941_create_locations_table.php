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
       Schema::create('locations', function (Blueprint $table) {
        $table->id();
        $table->string('country_name');
        $table->string('state_name');
        $table->string('city_location'); // Jo user input karega (e.g. Shimla)
        $table->string('country_id')->nullable(); // API wala ID agar chahiye
        $table->string('state_id')->nullable();   // API wala ID agar chahiye
        $table->boolean('status')->default(1);
        $table->timestamps();
    });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
