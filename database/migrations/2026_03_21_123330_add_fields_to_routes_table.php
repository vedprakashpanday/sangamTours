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
    Schema::table('routes', function (Blueprint $table) {
        // Agar column nahi hai toh add ho jayega
        if (!Schema::hasColumn('routes', 'distance')) {
            $table->string('distance')->nullable()->after('to_city_id');
        }
        if (!Schema::hasColumn('routes', 'duration')) {
            $table->string('duration')->nullable()->after('distance');
        }
    });
}

public function down()
{
    Schema::table('routes', function (Blueprint $table) {
        $table->dropColumn(['distance', 'duration']);
    });
}
};
