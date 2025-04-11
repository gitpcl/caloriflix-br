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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Evaluation features
            $table->boolean('glycemic_index_enabled')->default(false);
            $table->boolean('cholesterol_enabled')->default(false);
            $table->boolean('keto_diet_enabled')->default(false);
            $table->boolean('paleo_diet_enabled')->default(false);
            $table->boolean('low_fodmap_enabled')->default(false);
            $table->boolean('low_carb_enabled')->default(false);
            $table->boolean('meal_plan_evaluation_enabled')->default(false);
            
            // Preference settings
            $table->string('time_zone')->default('UTC-3');
            $table->boolean('silent_mode_enabled')->default(false);
            $table->string('language')->default('Português');
            $table->boolean('prioritize_taco_enabled')->default(false);
            $table->boolean('daily_log_enabled')->default(true);
            $table->boolean('photo_with_macros_enabled')->default(false);
            $table->boolean('auto_fasting_enabled')->default(true);
            $table->boolean('detailed_foods_enabled')->default(false);
            $table->boolean('show_dashboard_enabled')->default(false);
            $table->boolean('advanced_food_analysis_enabled')->default(false);
            $table->boolean('group_water_enabled')->default(false);
            
            // Section expanded states (not typically stored in DB, but could be)
            $table->json('expanded_sections')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
