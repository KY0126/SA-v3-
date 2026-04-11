<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique();
            $table->string('name', 100);
            $table->string('email', 191)->unique();
            $table->string('phone', 30)->nullable();
            $table->enum('role', ['admin', 'officer', 'professor', 'student', 'it'])->default('student');
            $table->string('club_position', 100)->nullable();
            $table->integer('credit_score')->default(100);
            $table->string('google_oauth_id')->nullable();
            $table->string('google_avatar_url')->nullable();
            $table->boolean('two_factor_enabled')->default(false);
            $table->string('two_factor_secret')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('language', 10)->default('zh-TW');
            $table->boolean('is_active')->default(true);
            $table->string('password')->default('');
            $table->timestamps();
            $table->index('role');
            $table->index('credit_score');
        });

        // 2. Clubs
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('category', 50);
            $table->string('category_label', 50)->nullable();
            $table->enum('type', ['club', 'association'])->default('club');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('advisor_id')->nullable();
            $table->unsignedBigInteger('president_id')->nullable();
            $table->integer('member_count')->default(0);
            $table->string('established_year', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('category');
            $table->index('type');
        });

        // 3. Club Members
        Schema::create('club_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('user_id');
            $table->string('position', 50)->default('member');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->unique(['club_id', 'user_id']);
            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Venues
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('location', 200)->nullable();
            $table->integer('capacity')->default(0);
            $table->enum('status', ['available', 'occupied', 'maintenance', 'reserved'])->default('available');
            $table->text('equipment_list')->nullable();
            $table->string('floor_plan_url')->nullable();
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();
            $table->timestamps();
            $table->index('status');
        });

        // 5. Activities
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('club_id')->nullable();
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->string('organizer_name', 100)->nullable();
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_participants')->default(0);
            $table->integer('current_participants')->default(0);
            $table->string('category', 50)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->string('approval_number')->nullable();
            $table->text('ai_review_result')->nullable();
            $table->decimal('budget_requested', 12, 2)->default(0);
            $table->decimal('budget_approved', 12, 2)->default(0);
            $table->timestamps();
            $table->index('club_id');
            $table->index('event_date');
            $table->index('status');
        });

        // 6. Activity Registrations
        Schema::create('activity_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['registered', 'attended', 'cancelled'])->default('registered');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamps();
            $table->unique(['activity_id', 'user_id']);
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 7. Equipment
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 150);
            $table->string('category', 50);
            $table->enum('status', ['available', 'borrowed', 'maintenance', 'retired'])->default('available');
            $table->string('condition_note')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index('code');
        });

        // 8. Equipment Borrowings
        Schema::create('equipment_borrowings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('borrower_id');
            $table->string('borrower_name', 100)->nullable();
            $table->date('borrow_date');
            $table->date('expected_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['active', 'returned', 'overdue', 'lost'])->default('active');
            $table->text('return_condition')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
            $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('cascade');
            $table->foreign('borrower_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 9. Reservations
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('venue_id');
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('club_name', 100)->nullable();
            $table->string('venue_name', 100)->nullable();
            $table->date('reservation_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('priority_level')->default(3);
            $table->enum('status', ['pending', 'confirmed', 'negotiating', 'rejected', 'cancelled'])->default('pending');
            $table->enum('stage', ['algorithm', 'negotiation', 'approval', 'approved', 'rejected'])->default('algorithm');
            $table->integer('preference_order')->default(1);
            $table->text('ai_review_result')->nullable();
            $table->text('purpose')->nullable();
            $table->timestamps();
            $table->index('reservation_date');
            $table->index('venue_id');
            $table->index('status');
        });

        // 10. Conflicts
        Schema::create('conflicts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_a_id')->nullable();
            $table->unsignedBigInteger('reservation_b_id')->nullable();
            $table->unsignedBigInteger('venue_id')->nullable();
            $table->string('party_a', 100)->nullable();
            $table->string('party_b', 100)->nullable();
            $table->string('venue_name', 100)->nullable();
            $table->date('conflict_date');
            $table->string('time_slot', 50);
            $table->enum('status', ['pending', 'negotiating', 'resolved', 'escalated'])->default('pending');
            $table->enum('stage', ['initial', 'ai_intervention', 'forced_close'])->default('initial');
            $table->integer('elapsed_minutes')->default(0);
            $table->text('negotiation_log')->nullable();
            $table->text('ai_suggestion')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamps();
        });

        // 11. Credit Logs
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('action', ['add', 'deduct']);
            $table->integer('points');
            $table->string('reason', 200);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 12. Notification Logs
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 200);
            $table->text('message');
            $table->enum('channel', ['system', 'email', 'line', 'sms'])->default('system');
            $table->boolean('read')->default(false);
            $table->string('tracking_token')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('read');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 13. Certificates
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('club_id')->nullable();
            $table->string('name', 100);
            $table->string('club_name', 100)->nullable();
            $table->string('position', 50);
            $table->string('term', 50);
            $table->string('certificate_code', 50)->unique();
            $table->string('digital_signature')->nullable();
            $table->string('verification_url')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->string('pdf_url')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 14. Portfolio Entries
        Schema::create('portfolio_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('category', 50)->nullable();
            $table->string('tags')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->string('evidence_url')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 15. Competency Scores
        Schema::create('competency_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('dimension', 50);
            $table->integer('score')->default(0);
            $table->timestamp('updated_at')->nullable();
            $table->unique(['user_id', 'dimension']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 16. AI Review Logs
        Schema::create('ai_review_logs', function (Blueprint $table) {
            $table->id();
            $table->string('target_type', 50);
            $table->unsignedBigInteger('target_id');
            $table->string('reviewer_type', 50)->default('dify_rag');
            $table->text('input_data')->nullable();
            $table->text('output_data')->nullable();
            $table->boolean('allow_next_step')->default(true);
            $table->string('risk_level', 20)->default('Low');
            $table->text('violations')->nullable();
            $table->text('references')->nullable();
            $table->timestamps();
        });

        // 17. Appeals
        Schema::create('appeals', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->unsignedBigInteger('appellant_id')->nullable();
            $table->string('appeal_type', 50);
            $table->string('subject', 300);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'processing', 'resolved', 'rejected'])->default('pending');
            $table->text('ai_summary')->nullable();
            $table->text('resolution')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();
        });

        // 18. Repairs
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('target', 200);
            $table->text('description');
            $table->enum('status', ['pending', 'processing', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->string('assignee', 100)->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->timestamps();
        });

        // 19. Calendar Events
        Schema::create('calendar_events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->date('date');
            $table->string('type', 50)->nullable();
            $table->string('color', 20)->default('#003153');
            $table->text('description')->nullable();
            $table->string('venue', 100)->nullable();
            $table->timestamps();
        });

        // 20. Time Capsules
        Schema::create('time_capsules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('term', 50);
            $table->text('file_manifest')->nullable();
            $table->string('r2_storage_key')->nullable();
            $table->string('gps_watermark')->nullable();
            $table->enum('status', ['sealed', 'transferred', 'received'])->default('sealed');
            $table->timestamp('sealed_at')->nullable();
            $table->timestamp('transfer_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });

        // 21. Outbox (Event Sourcing)
        Schema::create('outbox', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 100);
            $table->text('payload');
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->integer('retry_count')->default(0);
            $table->timestamps();
            $table->index('status');
        });
    }

    public function down(): void
    {
        $tables = [
            'outbox', 'time_capsules', 'calendar_events', 'repairs', 'appeals',
            'ai_review_logs', 'competency_scores', 'portfolio_entries', 'certificates',
            'notification_logs', 'credit_logs', 'conflicts', 'reservations',
            'equipment_borrowings', 'equipment', 'activity_registrations', 'activities',
            'venues', 'club_members', 'clubs', 'users'
        ];
        foreach ($tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
