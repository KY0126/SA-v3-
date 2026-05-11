<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Activity Applications — add cancellation review fields
        Schema::table('activity_applications', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('reject_reason');
            $table->enum('cancellation_status', ['pending', 'approved', 'rejected'])->nullable()->after('cancellation_reason');
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_status');
            $table->unsignedBigInteger('cancellation_reviewed_by')->nullable()->after('cancellation_requested_at');
            $table->timestamp('cancellation_reviewed_at')->nullable()->after('cancellation_reviewed_by');
        });

        // Equipment Loans — add cancellation review fields
        Schema::table('equipment_loans', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('status');
            $table->enum('cancellation_status', ['pending', 'approved', 'rejected'])->nullable()->after('cancellation_reason');
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_status');
            $table->unsignedBigInteger('cancellation_reviewed_by')->nullable()->after('cancellation_requested_at');
            $table->timestamp('cancellation_reviewed_at')->nullable()->after('cancellation_reviewed_by');
        });

        // Venue Bookings — add cancellation review fields
        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->text('cancellation_reason')->nullable()->after('reject_reason');
            $table->enum('cancellation_status', ['pending', 'approved', 'rejected'])->nullable()->after('cancellation_reason');
            $table->timestamp('cancellation_requested_at')->nullable()->after('cancellation_status');
            $table->unsignedBigInteger('cancellation_reviewed_by')->nullable()->after('cancellation_requested_at');
            $table->timestamp('cancellation_reviewed_at')->nullable()->after('cancellation_reviewed_by');
        });
    }

    public function down(): void {
        Schema::table('activity_applications', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason',
                'cancellation_status',
                'cancellation_requested_at',
                'cancellation_reviewed_by',
                'cancellation_reviewed_at',
            ]);
        });

        Schema::table('equipment_loans', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason',
                'cancellation_status',
                'cancellation_requested_at',
                'cancellation_reviewed_by',
                'cancellation_reviewed_at',
            ]);
        });

        Schema::table('venue_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cancellation_reason',
                'cancellation_status',
                'cancellation_requested_at',
                'cancellation_reviewed_by',
                'cancellation_reviewed_at',
            ]);
        });
    }
};
