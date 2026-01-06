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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('level', ['pusat', 'cabang', 'retail']);
            $table->foreignId('parent_id')->nullable()->constrained('stores')->onDelete('set null');
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('level');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
