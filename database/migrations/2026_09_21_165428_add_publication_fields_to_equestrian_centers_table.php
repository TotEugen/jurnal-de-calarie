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
        Schema::table('equestrian_centers', function (Blueprint $table) {
            $table->boolean('is_public')->default(false)->index();
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equestrian_centers', function (Blueprint $table) {
            $table->dropColumn(['is_public', 'published_at']);
        });
    }
};
