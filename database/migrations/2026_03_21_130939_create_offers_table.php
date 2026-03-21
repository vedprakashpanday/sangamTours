<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_create_offers_table.php
public function up() {
    Schema::create('offers', function (Blueprint $table) {
        $table->id();
        $table->string('offer_name'); // e.g., Summer Bonanza
        $table->string('offer_code')->unique(); // e.g., SUMMER10
        $table->enum('discount_type', ['Fixed', 'Percentage']); 
        $table->decimal('discount_value', 10, 2); // 10 (for 10%) or 500 (for ₹500)
        $table->decimal('min_booking_amount', 10, 2)->default(0);
        $table->date('valid_until');
        $table->tinyInteger('status')->default(1);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
