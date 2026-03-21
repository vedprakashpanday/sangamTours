<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->string('booking_no')->unique(); // e.g., ST-2026-001
        $table->foreignId('customer_id')->constrained()->onDelete('cascade');
        $table->enum('service_type', ['Flight', 'Bus', 'Train', 'Tour Package']);
        
        // Dynamic IDs (Jo service select hogi uska ID yahan aayega)
        $table->unsignedBigInteger('vehicle_id')->nullable(); // Flight/Bus/Train ID
        $table->unsignedBigInteger('route_id')->nullable();   // Route ID
        $table->unsignedBigInteger('package_id')->nullable(); // Tour Package ID
        
        $table->date('travel_date');
        $table->integer('pax_count')->default(1); // Kitne log hain
        
        // Pricing Logic
        $table->decimal('total_amount', 12, 2);
        $table->decimal('paid_amount', 12, 2)->default(0);
        $table->decimal('due_amount', 12, 2)->default(0);
        
        // Statuses
        $table->string('payment_status')->default('Pending'); // Paid, Partial, Pending
        $table->string('booking_status')->default('Upcoming'); // Upcoming, Completed, Cancelled
        
        $table->text('internal_note')->nullable(); // Admin ke liye
        $table->softDeletes();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
