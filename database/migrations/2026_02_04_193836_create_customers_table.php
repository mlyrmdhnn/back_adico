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
        Schema::create('customer', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->index();
            $table->string('store_name');
            $table->string('phone')->nullable();
            $table->string('npwp')->nullable();
            $table->text('address');
            $table->string('pic')->nullable();
            $table->string('re')->nullable();
            $table->foreignId('salesman_id')->nullable()->constrained('users', 'id');
            $table->date('created_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
