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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
            $table->foreignId('cashier_id')->constrained('users')->onDelete('restrict');
            $table->decimal('total_amount', 15, 2);
            $table->string('payment_method', 50)->default('cash');
            $table->timestamp('transaction_date');
            $table->timestamps();
            
            // Indexes
            $table->index('store_id');
            $table->index('cashier_id');
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
