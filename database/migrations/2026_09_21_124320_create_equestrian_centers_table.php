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
        Schema::create('equestrian_centers', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name');
            $table->string('slug')->unique();
            $table->string('fiscal_code')->nullable()->unique();
            $table->string('registration_number')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('county');
            $table->string('locality');
            $table->string('address');
            $table->string('affiliation_status')->default('pending')->index();
            $table->timestamp('affiliated_at')->nullable();
            $table->timestamp('affiliation_expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equestrian_centers');
    }
};
