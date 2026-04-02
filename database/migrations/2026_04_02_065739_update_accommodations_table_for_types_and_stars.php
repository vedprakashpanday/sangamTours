<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
{
    Schema::table('accommodations', function (Blueprint $table) {
        // 1. Category ke liye Foreign Key (Hotel, Resort, etc.)
        if (!Schema::hasColumn('accommodations', 'accommodation_type_id')) {
            $table->unsignedBigInteger('accommodation_type_id')->after('name')->nullable();
            $table->foreign('accommodation_type_id')->references('id')->on('accommodation_types')->onDelete('set null');
        }

        // 2. Star Rating ke liye (5 Star, 4 Star, etc.)
        if (!Schema::hasColumn('accommodations', 'star_rating')) {
            $table->string('star_rating')->nullable()->after('accommodation_type_id');
        }

        // Purana 'hotel_type' agar hai toh use hata sakte hain
        if (Schema::hasColumn('accommodations', 'hotel_type')) {
            $table->dropColumn('hotel_type');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accommodations', function (Blueprint $table) {
            //
        });
    }
};
