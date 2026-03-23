<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('schedules', function (Blueprint $table) {
        // In teeno columns ko nullable kar rahe hain kyunki data ab Stoppages table mein hai
        $table->time('departure_time')->nullable()->change();
        $table->time('arrival_time')->nullable()->change();
        $table->foreignId('route_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('schedules', function (Blueprint $table) {
        $table->time('departure_time')->nullable(false)->change();
        $table->time('arrival_time')->nullable(false)->change();
        $table->foreignId('route_id')->nullable(false)->change();
    });
}
};
