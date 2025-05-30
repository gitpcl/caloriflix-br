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
        Schema::table('recipes', function (Blueprint $table) {
            $table->decimal('protein', 8, 2)->nullable()->after('servings');
            $table->decimal('fat', 8, 2)->nullable()->after('protein');
            $table->decimal('carbohydrate', 8, 2)->nullable()->after('fat');
            $table->decimal('fiber', 8, 2)->nullable()->after('carbohydrate');
            $table->integer('calories')->nullable()->after('fiber');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recipes', function (Blueprint $table) {
            $table->dropColumn(['protein', 'fat', 'carbohydrate', 'fiber', 'calories']);
        });
    }
};
