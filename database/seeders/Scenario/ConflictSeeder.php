<?php

namespace Database\Seeders\Scenario;

use App\Models\Conflict;
use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ConflictSeeder extends Seeder {
    public function run(): void {
        $scenarios = [
            [
                'a' => ['club' => '攝影社', 'date' => '2026-05-28', 'start' => '13:00', 'end' => '17:00'],
                'b' => ['club' => '健言社', 'date' => '2026-05-28', 'start' => '13:00', 'end' => '17:00'],
                'venue_name' => '進修部地下室教室 ES002',
                'status' => 'escalated',
                'stage' => 'ai_intervention',
                'resolution_type' => 'arbitration',
                'resolution' => '送交課指組仲裁，暫定健言社使用 13:00-17:00，攝影社改期。',
                'confirmed_a' => false,
                'confirmed_b' => false,
                'elapsed' => 180,
            ],
            [
                'a' => ['club' => '同舟共濟服務社', 'date' => '2026-06-02', 'start' => '10:00', 'end' => '12:00'],
                'b' => ['club' => '攝影社', 'date' => '2026-06-02', 'start' => '10:00', 'end' => '12:00'],
                'venue_name' => '進修部地下室演講廳 ES001',
                'status' => 'resolved',
                'stage' => 'ai_intervention',
                'resolution_type' => 'private_coordination',
                'resolution' => '攝影社改為 12:00-14:00，雙方確認協調完成。',
                'confirmed_a' => true,
                'confirmed_b' => true,
                'elapsed' => 48,
            ],
            [
                'a' => ['club' => '民謠吉他社', 'date' => '2026-06-05', 'start' => '18:00', 'end' => '21:00'],
                'b' => ['club' => '健言社', 'date' => '2026-06-05', 'start' => '18:00', 'end' => '21:00'],
                'venue_name' => '焯炤館地下室演講廳 EZ003',
                'status' => 'negotiating',
                'stage' => 'initial',
                'resolution_type' => 'in_progress',
                'resolution' => '協調中，尚待雙方提出替代時段。',
                'confirmed_a' => false,
                'confirmed_b' => false,
                'elapsed' => 20,
            ],
        ];

        foreach ($scenarios as $scenario) {
            $reservationA = Reservation::where('club_name', $scenario['a']['club'])
                ->where('reservation_date', $scenario['a']['date'])
                ->where('start_time', $scenario['a']['start'])
                ->where('end_time', $scenario['a']['end'])
                ->first();

            $reservationB = Reservation::where('club_name', $scenario['b']['club'])
                ->where('reservation_date', $scenario['b']['date'])
                ->where('start_time', $scenario['b']['start'])
                ->where('end_time', $scenario['b']['end'])
                ->first();

            Conflict::updateOrCreate(
                [
                    'reservation_a_id' => $reservationA?->id,
                    'reservation_b_id' => $reservationB?->id,
                ],
                [
                    'venue_id' => $reservationA?->venue_id,
                    'party_a' => $scenario['a']['club'],
                    'party_b' => $scenario['b']['club'],
                    'venue_name' => $scenario['venue_name'],
                    'conflict_date' => $scenario['a']['date'],
                    'time_slot' => $scenario['a']['start'] . '-' . $scenario['a']['end'],
                    'status' => $scenario['status'],
                    'stage' => $scenario['stage'],
                    'elapsed_minutes' => $scenario['elapsed'],
                    'ai_suggestion' => '建議優先協調替代時段，若未達共識交由課指組。',
                    'chat_messages' => json_encode([
                        ['from' => $scenario['a']['club'], 'message' => '提出原定時段需求。'],
                        ['from' => $scenario['b']['club'], 'message' => '提出活動不可調整因素。'],
                        ['from' => '系統', 'message' => '產生 AI 協調建議。'],
                    ], JSON_UNESCAPED_UNICODE),
                    'party_a_confirmed' => $scenario['confirmed_a'],
                    'party_b_confirmed' => $scenario['confirmed_b'],
                    'party_a_confirmed_at' => $scenario['confirmed_a'] ? now()->subDays(2) : null,
                    'party_b_confirmed_at' => $scenario['confirmed_b'] ? now()->subDays(2) : null,
                    'email_sent_a' => true,
                    'email_sent_b' => true,
                    'resolution_type' => $scenario['resolution_type'],
                    'negotiation_log' => 'Seeder 建立的協調流程資料。',
                    'resolution' => $scenario['resolution'],
                ]
            );
        }
    }
}
