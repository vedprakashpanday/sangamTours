<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('common_images', function (Blueprint $table) {
    $table->id();
    $table->string('filename'); // e.g. "manali_trip_01.jpg"
    $table->string('image_type'); // e.g. "main", "gallery", "thumbnail"
    $table->unsignedBigInteger('imageable_id'); // ID of the Package/User/Post
    $table->string('imageable_type'); // Model Name
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('common_images');
    }
};
