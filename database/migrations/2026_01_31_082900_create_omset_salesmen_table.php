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
        Schema::create('omset_salesman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesman_id')->constrained('users', 'id');
            $table->date('omset_date');
            $table->decimal('omset_value', 15,2);
            $table->timestamps();
            // $table->unique(['salesman_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omset_salesmen');
    }
};
