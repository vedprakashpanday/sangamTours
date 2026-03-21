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
    Schema::table('offers', function (Blueprint $table) {
        $table->softDeletes(); // 🔥 Database mein deleted_at column banayega
    });
}

public function down()
{
    Schema::table('offers', function (Blueprint $table) {
        $table->dropSoftDeletes();
    });
}
};
