<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // venue_bookings: add 'returned' status for admin 退件
        DB::statement("ALTER TABLE venue_bookings MODIFY COLUMN status ENUM('pending','approved','rejected','cancelled','conflicted','returned') NOT NULL DEFAULT 'pending'");

        // venue_bookings: add unit_code (validated on submit but wasn't stored)
        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->string('unit_code', 20)->nullable()->after('applicant_id');
        });

        // equipment_loans: add reject_reason + unit_code columns
        Schema::table('equipment_loans', function (Blueprint $table) {
            $table->string('unit_code', 20)->nullable()->after('borrower_id');
            $table->text('reject_reason')->nullable()->after('purpose');
        });

        // equipment_loans: add 'returned_review' status for admin 退件
        // (distinct from 'returned' which means physical item return by borrower)
        DB::statement("ALTER TABLE equipment_loans MODIFY COLUMN status ENUM('pending','approved','picked_up','returned','overdue','rejected','returned_review') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE venue_bookings MODIFY COLUMN status ENUM('pending','approved','rejected','cancelled','conflicted') NOT NULL DEFAULT 'pending'");

        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->dropColumn('unit_code');
        });

        Schema::table('equipment_loans', function (Blueprint $table) {
            $table->dropColumn(['unit_code', 'reject_reason']);
        });

        DB::statement("ALTER TABLE equipment_loans MODIFY COLUMN status ENUM('pending','approved','picked_up','returned','overdue','rejected') NOT NULL DEFAULT 'pending'");
    }
};
