<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_applications', function (Blueprint $table) {
            $table->string('responsible_person', 100)->nullable()->after('unit_code');
            $table->string('unit_name', 100)->nullable()->after('responsible_person');
            $table->string('department', 100)->nullable()->after('unit_name');
            $table->string('contact_phone', 50)->nullable()->after('department');
            $table->integer('staff_count')->default(0)->after('expected_participants');
        });
    }

    public function down(): void
    {
        Schema::table('activity_applications', function (Blueprint $table) {
            $table->dropColumn(['responsible_person', 'department', 'contact_phone', 'staff_count']);
        });
    }
};