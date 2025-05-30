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
        Schema::table('reminders', function (Blueprint $table) {
            $table->string('title')->nullable()->after('name');
            $table->string('type')->nullable()->after('reminder_type');
            $table->time('time')->nullable()->after('type');
            $table->json('days')->nullable()->after('time');
            $table->string('repeat_type')->nullable()->after('days');
            $table->boolean('is_active')->default(true)->after('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['title', 'type', 'time', 'days', 'repeat_type', 'is_active']);
        });
    }
};
