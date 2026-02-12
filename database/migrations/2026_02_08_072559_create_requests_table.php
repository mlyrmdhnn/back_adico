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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('uuid');
            $table->foreignId('salesman_id')->constrained('users', 'id');
            $table->foreignId('store_id')->constrained('stores', 'id');
            $table->foreignId('manager_id')->nullable()->constrained('users', 'id');
            $table->foreignId('supervisor_id')->nullable()->constrained('users', 'id');
            $table->foreignId('payment_method_id')->constrained('payment_method', 'id');
            $table->string('status1');
            $table->string('status2');
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
