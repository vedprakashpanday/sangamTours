<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('vendors', function (Blueprint $table) {
        // Email ke baad image column add kar rahe hain
        $table->string('image')->nullable()->after('email');
    });
}

public function down(): void
{
    Schema::table('vendors', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}
};
