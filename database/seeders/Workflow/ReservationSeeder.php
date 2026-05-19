<?php

namespace Database\Seeders\Workflow;

use App\Models\Reservation;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder {
    public function run(): void {
        Reservation::create(['user_id' => 2, 'venue_id' => 14, 'user_name' => '陳小美', 'club_name' => '民謠吉他社', 'venue_name' => '焯炤館地下室旋律廣場 EZ012', 'reservation_date' => '2026-04-15', 'start_time' => '14:00', 'end_time' => '17:00', 'priority_level' => 2, 'status' => 'confirmed', 'stage' => 'approved']);
        Reservation::create(['user_id' => 1, 'venue_id' => 5, 'user_name' => '王大明', 'club_name' => '攝影社', 'venue_name' => '進修部地下室教室 ES002', 'reservation_date' => '2026-04-20', 'start_time' => '09:00', 'end_time' => '12:00', 'priority_level' => 3, 'status' => 'pending', 'stage' => 'algorithm']);

        // Additional reservation for 攝影社 to create the conflict recorded in ConflictSeeder
        Reservation::create(['user_id' => 1, 'venue_id' => 5, 'user_name' => '王大明', 'club_name' => '攝影社', 'venue_name' => '進修部地下室教室 ES002', 'reservation_date' => '2026-04-25', 'start_time' => '13:00', 'end_time' => '17:00', 'priority_level' => 3, 'status' => 'confirmed', 'stage' => 'approved']);
        Reservation::create(['user_id' => 6, 'venue_id' => 5, 'user_name' => '李同學', 'club_name' => '健言社', 'venue_name' => '進修部地下室教室 ES002', 'reservation_date' => '2026-04-25', 'start_time' => '13:00', 'end_time' => '17:00', 'priority_level' => 3, 'status' => 'confirmed', 'stage' => 'approved']);
        Reservation::create(['user_id' => 7, 'venue_id' => 4, 'user_name' => '張同學', 'club_name' => '同舟共濟服務社', 'venue_name' => '進修部地下室演講廳 ES001', 'reservation_date' => '2026-05-01', 'start_time' => '10:00', 'end_time' => '16:00', 'priority_level' => 2, 'status' => 'negotiating', 'stage' => 'negotiation']);
    }
}
