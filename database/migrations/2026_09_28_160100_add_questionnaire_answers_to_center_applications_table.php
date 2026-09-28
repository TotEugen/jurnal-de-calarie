<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('center_applications', function (Blueprint $table) {
            $table->json('questionnaire_answers')->nullable()->after('applicant_notes');
        });
    }

    public function down(): void
    {
        Schema::table('center_applications', function (Blueprint $table) {
            $table->dropColumn('questionnaire_answers');
        });
    }
};
