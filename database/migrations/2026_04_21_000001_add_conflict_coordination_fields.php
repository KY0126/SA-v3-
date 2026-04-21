<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add coordination fields to conflicts table
        Schema::table('conflicts', function (Blueprint $table) {
            $table->text('chat_messages')->nullable()->after('ai_suggestion');
            $table->boolean('party_a_confirmed')->default(false)->after('resolution');
            $table->boolean('party_b_confirmed')->default(false)->after('party_a_confirmed');
            $table->timestamp('party_a_confirmed_at')->nullable()->after('party_b_confirmed');
            $table->timestamp('party_b_confirmed_at')->nullable()->after('party_a_confirmed_at');
            $table->boolean('email_sent_a')->default(false)->after('party_b_confirmed_at');
            $table->boolean('email_sent_b')->default(false)->after('email_sent_a');
            $table->string('resolution_type', 50)->nullable()->after('email_sent_b');
        });
    }

    public function down(): void
    {
        Schema::table('conflicts', function (Blueprint $table) {
            $table->dropColumn([
                'chat_messages','party_a_confirmed','party_b_confirmed',
                'party_a_confirmed_at','party_b_confirmed_at',
                'email_sent_a','email_sent_b','resolution_type'
            ]);
        });
    }
};
