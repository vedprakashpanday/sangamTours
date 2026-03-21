<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up()
{
    Schema::table('accommodations', function (Blueprint $table) {
        // id ke baad accommodation_id column add karein
        $table->string('accommodation_id')->nullable()->after('id');
    });
}

public function down()
{
    Schema::table('accommodations', function (Blueprint $table) {
        $table->dropColumn('accommodation_id');
    });
}
};
