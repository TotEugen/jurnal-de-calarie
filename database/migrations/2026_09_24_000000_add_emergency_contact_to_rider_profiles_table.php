<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->string('emergency_contact_name')->nullable()->after('contact_email');
            $table->string('emergency_contact_phone', 30)->nullable()->after('emergency_contact_name');
        });
    }

    public function down(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->dropColumn(['emergency_contact_name', 'emergency_contact_phone']);
        });
    }
};
