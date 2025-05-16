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
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('notes')->nullable();
            $table->integer('mood')->nullable(); // Can be used for tracking mood (1-5 scale)
            $table->integer('water')->default(0); // Track water intake in ml
            $table->integer('sleep')->nullable(); // Sleep duration in minutes
            $table->timestamps();
            
            // Unique constraint to prevent multiple entries for the same day
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diaries');
    }
};
