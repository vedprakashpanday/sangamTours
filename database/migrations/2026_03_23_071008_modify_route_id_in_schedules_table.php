<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('schedules', function (Blueprint $table) {
        // route_id ko nullable banayein taaki ye optional ho jaye
        $table->foreignId('route_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->foreignId('route_id')->nullable(false)->change();
    });
}
};
