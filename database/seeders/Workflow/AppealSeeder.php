<?php

namespace Database\Seeders\Workflow;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Database\Seeder;

class AppealSeeder extends Seeder {
    public function run(): void {
        $admin = User::where('student_id', 'A001')->first();
        $student = User::where('student_id', '410012345')->first();

        $rows = [
            [
                'code' => 'AP-2026-042',
                'appeal_type' => '場地衝突仲裁',
                'subject' => '攝影社與健言社 ES002 衝突進入仲裁',
                'description' => '私下協調無共識，申請課指組仲裁。',
                'status' => 'processing',
            ],
            [
                'code' => 'AP-2026-038',
                'appeal_type' => '器材損壞',
                'subject' => 'B4 DV 攝影機歸還時外殼受損',
                'description' => '借用人主張既有瑕疵，申請覆核。',
                'status' => 'pending',
            ],
            [
                'code' => 'AP-2026-035',
                'appeal_type' => '信用積分',
                'subject' => '協調超時扣分爭議',
                'description' => '學生提出補件，請求調整扣分。',
                'status' => 'resolved',
            ],
        ];

        foreach ($rows as $row) {
            Appeal::updateOrCreate(
                ['code' => $row['code']],
                [
                    'appellant_id' => $student?->id,
                    'appeal_type' => $row['appeal_type'],
                    'subject' => $row['subject'],
                    'description' => $row['description'],
                    'status' => $row['status'],
                    'assigned_to' => $admin?->id,
                    'ai_summary' => 'Seeder 建立：供展示申請、協調與仲裁流轉。',
                    'resolution' => $row['status'] === 'resolved' ? '改以書面提醒，不扣點。' : null,
                ]
            );
        }
    }
}
