<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Update status column to be a string to support all OrderStatus enum values
            $table->string('status')->default(OrderStatus::PaymentPending->value)->change();
            
            // Add payment_status if missing
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->default(PaymentStatus::Pending->value)->after('status');
            }

            // Ensure rider_id exists (it should, but just in case)
            if (!Schema::hasColumn('orders', 'rider_id')) {
                $table->foreignId('rider_id')->nullable()->after('restaurant_id')->constrained('users')->nullOnDelete();
            }

            // Indexes for rider discovery
            $table->index(['status', 'rider_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status', 'rider_id']);
            $table->dropColumn('payment_status');
        });
    }
};
