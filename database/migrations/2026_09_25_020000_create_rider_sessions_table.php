<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rider_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equestrian_center_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('session_number');
            $table->date('session_date');
            $table->time('session_time');
            $table->string('center_name');
            $table->unsignedSmallInteger('duration_minutes');
            $table->json('activity_types');
            $table->string('other_activity')->nullable();
            $table->string('horse_name');
            $table->text('learned_today');
            $table->text('key_takeaway');
            $table->text('next_experience')->nullable();
            $table->string('instructor_name');
            $table->unsignedTinyInteger('rating');
            $table->string('status')->default('pending_monitor')->index();
            $table->timestamp('submitted_at');
            $table->foreignId('monitor_confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('monitor_confirmed_at')->nullable();
            $table->foreignId('center_confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('center_confirmed_at')->nullable();
            $table->timestamps();
            $table->unique(['rider_profile_id', 'session_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rider_sessions');
    }
};
