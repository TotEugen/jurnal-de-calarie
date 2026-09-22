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
        Schema::create('riding_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equestrian_center_id')->constrained()->restrictOnDelete();
            $table->foreignId('professional_profile_id')->constrained()->restrictOnDelete();
            $table->dateTime('performed_at');
            $table->unsignedSmallInteger('duration_minutes');
            $table->string('activity_type');
            $table->json('competencies')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('recorded')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riding_activities');
    }
};
