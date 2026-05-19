<?php

namespace Database\Seeders\Demo;

use App\Models\CalendarEvent;
use App\Models\NotificationLog;
use App\Models\PortfolioEntry;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder {
    public function run(): void {
        $demoUsers = [
            [
                'student_id' => 'D0001',
                'name' => 'Demo 管理者',
                'email' => 'demo.admin@cloud.fju.edu.tw',
                'role' => 'admin',
                'club_position' => 'Demo 系統管理',
            ],
            [
                'student_id' => 'D0002',
                'name' => 'Demo 社團幹部',
                'email' => 'demo.officer@cloud.fju.edu.tw',
                'role' => 'officer',
                'club_position' => 'Demo 社團總召',
            ],
            [
                'student_id' => 'D0003',
                'name' => 'Demo 學生',
                'email' => 'demo.student@cloud.fju.edu.tw',
                'role' => 'student',
                'club_position' => 'Demo 社員',
            ],
        ];

        foreach ($demoUsers as $demoUser) {
            User::updateOrCreate(
                ['student_id' => $demoUser['student_id']],
                $demoUser + [
                    'credit_score' => 95,
                    'password' => Hash::make('Password123!'),
                    'language' => 'zh-TW',
                    'is_active' => true,
                ]
            );
        }

        $demoStudent = User::where('student_id', 'D0003')->first();
        if (!$demoStudent) {
            return;
        }

        NotificationLog::updateOrCreate(
            ['tracking_token' => 'seed:demo:welcome'],
            [
                'user_id' => $demoStudent->id,
                'title' => 'Demo 儀表板歡迎訊息',
                'message' => '這是展示環境，包含借用、衝突協調與仲裁完整流程資料。',
                'channel' => 'system',
                'read' => false,
            ]
        );

        $portfolioRows = [
            ['title' => 'Demo 活動企劃書', 'category' => 'activity'],
            ['title' => 'Demo 場地協調紀錄', 'category' => 'coordination'],
            ['title' => 'Demo 仲裁結案報告', 'category' => 'arbitration'],
        ];

        foreach ($portfolioRows as $row) {
            PortfolioEntry::updateOrCreate(
                [
                    'user_id' => $demoStudent->id,
                    'title' => $row['title'],
                ],
                [
                    'description' => 'Seeder 產生之展示用資料。',
                    'category' => $row['category'],
                    'tags' => 'demo,seed',
                ]
            );
        }

        CalendarEvent::updateOrCreate(
            [
                'title' => 'Demo Dashboard 巡覽',
                'date' => '2026-06-01',
            ],
            [
                'type' => 'demo',
                'color' => '#1F6FEB',
                'description' => '展示完整流程：借用 -> 通知 -> 協調 -> 仲裁。',
                'venue' => '課指組二樓204會議室',
            ]
        );
    }
}
