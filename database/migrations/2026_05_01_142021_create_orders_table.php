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
        Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');           // customer
    $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
    $table->foreignId('rider_id')->nullable()->constrained('users')->nullOnDelete(); // assigned rider
    
    // State machine: placed → confirmed → preparing → ready_for_pickup → picked_up → delivered / cancelled
    $table->enum('status', [
        'placed', 'confirmed', 'preparing', 'ready_for_pickup',
        'picked_up', 'delivered', 'cancelled'
    ])->default('placed');
    
    // Pricing
    $table->decimal('subtotal', 10, 2);
    $table->decimal('delivery_fee', 8, 2)->default(0);
    $table->decimal('surge_multiplier', 4, 2)->default(1.00);
    $table->decimal('tax', 8, 2)->default(0);
    $table->decimal('total', 10, 2);
    
    // Delivery info
    $table->text('delivery_address');
    $table->decimal('delivery_lat', 10, 7)->nullable();
    $table->decimal('delivery_lng', 10, 7)->nullable();
    $table->text('special_instructions')->nullable();
    
    // Timing
    $table->timestamp('confirmed_at')->nullable();
    $table->timestamp('preparing_at')->nullable();
    $table->timestamp('ready_at')->nullable();
    $table->timestamp('picked_up_at')->nullable();
    $table->timestamp('delivered_at')->nullable();
    $table->timestamp('cancelled_at')->nullable();
    $table->string('cancellation_reason')->nullable();
    
    $table->timestamps();
    $table->softDeletes();
    
    // Indexes for performance
    $table->index('status');
    $table->index(['user_id', 'status']);
    $table->index(['restaurant_id', 'status']);
    $table->index(['rider_id', 'status']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
