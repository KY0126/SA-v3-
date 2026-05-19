<?php

namespace Database\Seeders\Scenario;

use App\Models\Conflict;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ConflictSeeder extends Seeder {
    public function run(): void {
        $date = '2026-04-25';
        $start = '13:00';
        $end = '17:00';

        $resA = Reservation::where('club_name', '攝影社')
            ->where('reservation_date', $date)
            ->where('start_time', '<=', $start)
            ->where('end_time', '>=', $end)
            ->first();

        $resB = Reservation::where('club_name', '健言社')
            ->where('reservation_date', $date)
            ->where('start_time', '<=', $start)
            ->where('end_time', '>=', $end)
            ->first();

        Conflict::create([
            'reservation_a_id' => $resA?->id,
            'reservation_b_id' => $resB?->id,
            'party_a' => '攝影社',
            'party_b' => '健言社',
            'venue_name' => '進修部地下室教室 ES002',
            'conflict_date' => $date,
            'time_slot' => '13:00-17:00',
            'status' => 'negotiating',
            'stage' => 'ai_intervention',
            'elapsed_minutes' => 4,
        ]);
    }
}
