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
        Schema::create('center_rider_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equestrian_center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rider_profile_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('pending')->index();
            $table->boolean('is_initial_validation')->default(false);
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->unique(['equestrian_center_id', 'rider_profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center_rider_memberships');
    }
};
