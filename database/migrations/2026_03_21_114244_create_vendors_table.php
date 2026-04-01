<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up() {
    Schema::create('vendors', function (Blueprint $table) {
        $table->id();
        $table->string('name');
       
        $table->string('phone')->unique();
        $table->string('email')->nullable();
        $table->boolean('is_api')->default(0); // 1: API Integrated, 0: Manual
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
        Schema::dropIfExists('vendors');
    }
};
