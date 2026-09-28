<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->string('physical_journal_issuing_center')->nullable()->after('had_physical_journal');
            $table->string('physical_journal_series', 100)->nullable()->after('physical_journal_issuing_center');
            $table->string('physical_journal_rider_code', 100)->nullable()->after('physical_journal_series');
        });
    }

    public function down(): void
    {
        Schema::table('rider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'physical_journal_issuing_center',
                'physical_journal_series',
                'physical_journal_rider_code',
            ]);
        });
    }
};
