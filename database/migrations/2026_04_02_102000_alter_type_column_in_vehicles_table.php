<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        // Enum ko Varchar mein badalne ke liye hum DB::statement use kar rahe hain, 
        // kyunki Laravel mein Enum ko change karna kabhi-kabhi error deta hai.
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN type VARCHAR(255) NOT NULL");
    }

    public function down(): void
    {
        // Agar rollback karna pade toh wapas enum
        DB::statement("ALTER TABLE vehicles MODIFY COLUMN type ENUM('Flight', 'Bus', 'Train') NOT NULL");
    }
};
