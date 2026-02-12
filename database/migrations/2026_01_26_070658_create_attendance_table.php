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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salesman_id')->constrained('users', 'id');
            $table->date('date');
            $table->dateTime('check_in_at')->nullable();
            $table->foreignId('attendance_type_id')->constrained('attendance_type', 'id');
            $table->string('note')->nullable();
            $table->timestamps();

            $table->unique(['salesman_id', 'date']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
