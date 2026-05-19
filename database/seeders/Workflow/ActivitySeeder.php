<?php

namespace Database\Seeders\Workflow;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder {
    public function run(): void {
        $activities = [
            ['攝影社春季外拍', '攝影技巧實作與外拍練習', 63, null, '王大明', '2026-04-20', '09:00', '17:00', 50, 35, 'arts', 'approved'],
            ['民謠吉他社期末成果展', '吉他社年度成果展演', 76, 14, '陳小美', '2026-04-15', '14:00', '17:00', 100, 0, 'music', 'pending'],
            ['程式設計工作坊', 'Python 與 AI 基礎工作坊', 1, 5, '李同學', '2026-04-25', '13:00', '17:00', 40, 28, 'academic', 'approved'],
            ['同舟共濟服務社淨灘活動', '白沙灣淨灘志工服務', 34, null, '張同學', '2026-05-01', '08:00', '16:00', 80, 45, 'service', 'approved'],
            ['跑步社校園路跑挑戰', '5K 路跑挑戰賽', 60, 1, '劉同學', '2026-05-05', '07:00', '12:00', 150, 0, 'sports', 'pending'],
            ['桌上遊戲社同樂會', '桌遊同樂活動', 29, 16, '林同學', '2026-04-10', '14:00', '16:00', 20, 18, 'recreation', 'approved'],
            ['熱舞社街舞展演', '街舞年度展演', 64, 13, '陳同學', '2026-04-18', '18:00', '21:00', 160, 72, 'arts', 'approved'],
            ['健言社校際辯論邀請賽', '校際辯論邀請賽', 1, 14, '黃同學', '2026-04-22', '09:00', '17:00', 100, 48, 'academic', 'approved'],
        ];

        foreach ($activities as $activity) {
            Activity::create([
                'title' => $activity[0],
                'description' => $activity[1],
                'club_id' => $activity[2],
                'venue_id' => $activity[3],
                'organizer_name' => $activity[4],
                'event_date' => $activity[5],
                'start_time' => $activity[6],
                'end_time' => $activity[7],
                'max_participants' => $activity[8],
                'current_participants' => $activity[9],
                'category' => $activity[10],
                'status' => $activity[11],
            ]);
        }
    }
}
