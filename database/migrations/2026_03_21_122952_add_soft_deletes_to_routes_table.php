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
        // 🔥 Ye line table mein 'deleted_at' column add karegi
        $table->softDeletes(); 
    });
}

public function down()
{
    Schema::table('routes', function (Blueprint $table) {
        // Rollback ke liye column wapas hatane ka logic
        $table->dropSoftDeletes();
    });
}
};
