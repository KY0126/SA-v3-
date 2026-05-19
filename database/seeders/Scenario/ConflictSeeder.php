<?php

namespace Database\Seeders\Scenario;

use App\Models\Conflict;
use Illuminate\Database\Seeder;

class ConflictSeeder extends Seeder {
    public function run(): void {
        Conflict::create(['party_a' => '攝影社', 'party_b' => '健言社', 'venue_name' => '進修部地下室教室 ES002', 'conflict_date' => '2026-04-25', 'time_slot' => '13:00-17:00', 'status' => 'negotiating', 'stage' => 'ai_intervention', 'elapsed_minutes' => 4]);
    }
}
