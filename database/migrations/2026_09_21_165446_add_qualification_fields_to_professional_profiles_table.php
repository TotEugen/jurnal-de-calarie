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
        Schema::table('professional_profiles', function (Blueprint $table) {
            $table->string('qualification_grade')->nullable();
            $table->string('qualification_identifier')->nullable();
            $table->string('passport_number')->nullable();
            $table->date('qualification_obtained_at')->nullable();
            $table->string('issuing_authority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professional_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'qualification_grade', 'qualification_identifier', 'passport_number',
                'qualification_obtained_at', 'issuing_authority',
            ]);
        });
    }
};
