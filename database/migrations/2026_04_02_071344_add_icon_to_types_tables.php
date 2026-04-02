<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('accommodation_types', function (Blueprint $table) {
        $table->string('icon')->nullable()->after('name');
    });
    Schema::table('vendor_types', function (Blueprint $table) {
        $table->string('icon')->nullable()->after('name');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('types_tables', function (Blueprint $table) {
            //
        });
    }
};
