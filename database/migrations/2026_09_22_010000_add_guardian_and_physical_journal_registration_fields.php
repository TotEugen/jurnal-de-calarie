<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->boolean('had_physical_journal')->default(false)->after('data_processing_consent_at');
        });

        Schema::table('guardian_relationships', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('rider_profile_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->unsignedTinyInteger('age')->nullable()->after('last_name');
            $table->string('phone', 30)->nullable()->after('age');
            $table->string('email')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->dropColumn('had_physical_journal');
        });

        Schema::table('guardian_relationships', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name', 'age', 'phone', 'email']);
        });
    }
};
