<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('tour_packages', function (Blueprint $table) {
        $table->softDeletes(); // Ye 'deleted_at' column add kar dega
    });
}

public function down()
{
    Schema::table('tour_packages', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
