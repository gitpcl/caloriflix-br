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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('quantity', 8, 2)->default(1);
            $table->string('unit')->nullable(); // gramas, ml, etc
            $table->decimal('protein', 8, 2)->nullable(); // in grams
            $table->decimal('fat', 8, 2)->nullable(); // in grams (gordura)
            $table->decimal('carbohydrate', 8, 2)->nullable(); // in grams (carboidrato)
            $table->decimal('fiber', 8, 2)->nullable(); // in grams (fibras)
            $table->decimal('calories', 8, 2)->nullable(); // in kcal
            $table->string('barcode')->nullable(); // código de barras (optional)
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
