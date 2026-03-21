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
        // Agar status column nahi hai toh add karein
        if (!Schema::hasColumn('routes', 'status')) {
            $table->tinyInteger('status')->default(1)->after('duration')->comment('1: Active, 0: Inactive');
        }
    });
}

public function down()
{
    Schema::table('routes', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
