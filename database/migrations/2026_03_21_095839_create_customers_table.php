<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('customers', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('phone')->unique();
        $table->text('address')->nullable();
        
        // KYC Details
        $table->string('pan_number')->nullable();
        $table->string('aadhar_number')->nullable();
        
        // Image Paths
        $table->string('customer_image')->nullable();
        $table->string('pan_front')->nullable();
        $table->string('pan_back')->nullable();
        $table->string('aadhar_front')->nullable();
        $table->string('aadhar_back')->nullable();

        // Status: 1:Active, 2:Blocked, 3:Restricted
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
        Schema::dropIfExists('customers');
    }
};
