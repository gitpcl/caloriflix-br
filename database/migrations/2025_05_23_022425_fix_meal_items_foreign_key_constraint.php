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
        Schema::table('meal_items', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['food_id']);
            
            // Add the correct foreign key constraint pointing to 'foods' table
            $table->foreign('food_id')->references('id')->on('foods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meal_items', function (Blueprint $table) {
            // Drop the corrected foreign key
            $table->dropForeign(['food_id']);
            
            // Restore the original (incorrect) foreign key
            $table->foreign('food_id')->references('id')->on('food')->onDelete('cascade');
        });
    }
};
