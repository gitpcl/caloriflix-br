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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('reminder_type')->default('intervalo de tempo'); // 'intervalo de tempo' or other types
            $table->integer('interval_hours')->default(0);
            $table->integer('interval_minutes')->default(0);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('buttons_enabled')->default(false);
            $table->boolean('auto_command_enabled')->default(false);
            $table->string('auto_command')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};