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
        Schema::table('vehicles', function (Blueprint $table) {
            // Naye columns add kar rahe hain
            $table->string('model_name')->nullable()->after('seat_type'); // e.g., "Swift Dzire", "Innova Crysta"
            $table->string('luggage_allowed')->nullable()->after('model_name'); // e.g., "2 Bags", "4 Bags"
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['model_name', 'luggage_allowed']);
        });
    }
};
