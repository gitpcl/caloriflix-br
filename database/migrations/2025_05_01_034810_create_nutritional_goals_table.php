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
        Schema::create('nutritional_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('protein')->nullable()->comment('Protein in grams');
            $table->integer('carbs')->nullable()->comment('Carbohydrates in grams');
            $table->integer('fat')->nullable()->comment('Fat in grams');
            $table->integer('fiber')->nullable()->comment('Fiber in grams');
            $table->integer('calories')->nullable()->comment('Calories in kcal');
            $table->integer('water')->nullable()->comment('Water in ml');
            $table->string('objective')->nullable()->comment('Weight loss, maintenance, or gain');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutritional_goals');
    }
};
