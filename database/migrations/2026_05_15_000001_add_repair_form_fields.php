<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('repairs', function (Blueprint $table) {
            $table->string('email', 120)->nullable()->after('code');
            $table->boolean('consent')->default(false)->after('email');
            $table->string('reporter_name', 100)->nullable()->after('consent');
            $table->string('reporter_identifier', 50)->nullable()->after('reporter_name');
            $table->string('phone', 50)->nullable()->after('reporter_identifier');
            $table->string('unit', 120)->nullable()->after('phone');
            $table->string('category', 50)->nullable()->after('unit');
            $table->string('category_other', 100)->nullable()->after('category');
            $table->string('repair_item', 200)->nullable()->after('category_other');
            $table->text('damage_description')->nullable()->after('repair_item');
            $table->text('location')->nullable()->after('damage_description');
            $table->text('attachment_paths')->nullable()->after('location');
            $table->text('other_notes')->nullable()->after('attachment_paths');
        });
    }

    public function down(): void {
        Schema::table('repairs', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'consent',
                'reporter_name',
                'reporter_identifier',
                'phone',
                'unit',
                'category',
                'category_other',
                'repair_item',
                'damage_description',
                'location',
                'attachment_paths',
                'other_notes',
            ]);
        });
    }
};
