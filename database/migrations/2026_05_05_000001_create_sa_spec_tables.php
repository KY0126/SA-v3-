<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Equipment cert types (e.g. 攀岩證、操音證)
        Schema::create('equipment_cert_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->integer('validity_months')->default(12);
            $table->timestamps();
        });

        // User certifications
        Schema::create('equipment_certs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cert_type_id');
            $table->date('issued_at');
            $table->date('expires_at');
            $table->enum('status', ['valid', 'expired', 'revoked'])->default('valid');
            $table->timestamps();
            $table->unique(['user_id', 'cert_type_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('cert_type_id')->references('id')->on('equipment_cert_types')->onDelete('cascade');
        });

        // Add cert_type_id to equipment (nullable — not all equipment require a cert)
        Schema::table('equipment', function (Blueprint $table) {
            $table->unsignedBigInteger('cert_type_id')->nullable()->after('category');
        });

        // Activity applications (prerequisite for venue booking & equipment loan)
        Schema::create('activity_applications', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no', 30)->unique();   // AA-2026-00001
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('club_id')->nullable();
            $table->string('activity_name', 200);
            $table->text('purpose')->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('venue_description', 200)->nullable();
            $table->integer('expected_participants')->default(0);
            $table->decimal('budget_requested', 12, 2)->default(0);
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected', 'returned'])
                  ->default('draft');
            $table->text('reject_reason')->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('applicant_id');
            $table->index('event_date');
        });

        // Equipment loan master record (multi-item)
        Schema::create('equipment_loans', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no', 30)->unique();   // EL-2026-00001
            $table->unsignedBigInteger('borrower_id');
            $table->unsignedBigInteger('activity_application_id')->nullable();
            $table->date('borrow_date');
            $table->date('expected_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'picked_up', 'returned', 'overdue', 'rejected'])
                  ->default('pending');
            $table->text('purpose')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->index('borrower_id');
            $table->index('status');
        });

        // Equipment loan detail (one row per equipment item)
        Schema::create('equipment_loan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->unsignedBigInteger('equipment_id');
            $table->integer('quantity')->default(1);
            $table->enum('status', ['pending', 'picked_up', 'returned', 'lost'])->default('pending');
            $table->string('condition_on_return')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('equipment_loans')->onDelete('cascade');
            $table->foreign('equipment_id')->references('id')->on('equipment');
        });

        // Venue bookings (replaces reservations for SA-spec flow)
        Schema::create('venue_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no', 30)->unique();   // VB-2026-00001
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('applicant_id');
            $table->unsignedBigInteger('activity_application_id')->nullable();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('expected_participants')->default(0);
            $table->text('purpose')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled', 'conflicted'])
                  ->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            // Composite index used by pessimistic-lock conflict query
            $table->index(['venue_id', 'status', 'booking_date', 'start_time', 'end_time'], 'vb_conflict_idx');
            $table->index('applicant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue_bookings');
        Schema::dropIfExists('equipment_loan_details');
        Schema::dropIfExists('equipment_loans');
        Schema::dropIfExists('activity_applications');
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn('cert_type_id');
        });
        Schema::dropIfExists('equipment_certs');
        Schema::dropIfExists('equipment_cert_types');
    }
};
