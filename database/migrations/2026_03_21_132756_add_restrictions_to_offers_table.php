<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::table('offers', function (Blueprint $table) {
        // Kis category par lagega: All, Flight, Bus, Train, Tour Package
        $table->string('apply_to')->default('All')->after('offer_code');
        
        // Agar kisi specific Flight ya Package par lagana hai toh uska ID
        $table->unsignedBigInteger('content_id')->nullable()->after('apply_to');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            //
        });
    }
};
