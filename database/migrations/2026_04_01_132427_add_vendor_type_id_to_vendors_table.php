<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('vendors', function (Blueprint $table) {
        // 1. Pehle column add karein (without foreign key)
        $table->unsignedBigInteger('vendor_type_id')->nullable()->after('name');
    });

    // 2. Purane data ko kisi valid ID se update karein (Temporary fix taaki migrate ho sake)
    // Maan lijiye 'vendor_types' table mein ID 1 exist karti hai
    $firstType = DB::table('vendor_types')->first();
    if ($firstType) {
        DB::table('vendors')->update(['vendor_type_id' => $firstType->id]);
    }

    Schema::table('vendors', function (Blueprint $table) {
        // 3. Ab foreign key constraint lagayein
        $table->foreign('vendor_type_id')->references('id')->on('vendor_types')->onDelete('cascade');
        
        // 4. Purana column drop karein agar exists karta hai
        if (Schema::hasColumn('vendors', 'type')) {
            $table->dropColumn('type');
        }
    });
}

public function down(): void
{
    Schema::table('vendors', function (Blueprint $table) {
        $table->dropForeign(['vendor_type_id']);
        $table->dropColumn('vendor_type_id');
        $table->string('type')->nullable(); // Rollback ke liye wapas 'type' add kar rahe hain
    });
}
   
};
