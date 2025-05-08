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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('weight', 5, 1)->nullable()->comment('Weight in kg');
            $table->integer('height')->nullable()->comment('Height in cm');
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->string('activity_level')->nullable();
            $table->integer('basal_metabolic_rate')->nullable();
            $table->boolean('use_basal_metabolic_rate')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
