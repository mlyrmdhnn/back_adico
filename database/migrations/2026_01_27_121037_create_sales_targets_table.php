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
        Schema::create('sales_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesman_id')->constrained('users', 'id');
            $table->enum('period_type', ['daily', 'monthly']);
            $table->date('period_start');
            $table->decimal('target_value', 15, 2);
            $table->enum('status', ['active', 'revised']);
            $table->foreignId('created_by')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_targets');
    }
};
