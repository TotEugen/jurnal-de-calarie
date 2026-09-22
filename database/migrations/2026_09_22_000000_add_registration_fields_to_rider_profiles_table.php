<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('birth_date');
            $table->foreignId('self_reported_grade_id')->nullable()->after('phone')->constrained('grades')->nullOnDelete();
            $table->timestamp('data_processing_consent_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('self_reported_grade_id');
            $table->dropColumn(['phone', 'data_processing_consent_at']);
        });
    }
};
