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
        Schema::create('grade_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rider_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained()->restrictOnDelete();
            $table->foreignId('equestrian_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('professional_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('riding_activity_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('awarded_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['rider_profile_id', 'grade_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_awards');
    }
};
