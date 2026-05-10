<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_applications', function (Blueprint $table) {
            $table->string('unit_code', 20)->nullable()->after('club_id');
        });

        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->string('unit_code', 20)->nullable()->after('applicant_id');
        });

        Schema::table('equipment_loans', function (Blueprint $table) {
            $table->string('unit_code', 20)->nullable()->after('borrower_id');
        });
    }

    public function down(): void
    {
        Schema::table('activity_applications', function (Blueprint $table) {
            $table->dropColumn('unit_code');
        });
        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->dropColumn('unit_code');
        });
        Schema::table('equipment_loans', function (Blueprint $table) {
            $table->dropColumn('unit_code');
        });
    }
};
