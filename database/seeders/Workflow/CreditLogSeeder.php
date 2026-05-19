<?php

namespace Database\Seeders\Workflow;

use App\Models\CreditLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreditLogSeeder extends Seeder {
    public function run(): void {
        $student = User::where('student_id', '410012345')->first();
        if (!$student) {
            return;
        }

        $rows = [
            ['action' => 'add', 'points' => 5, 'reason' => '按時歸還器材', 'reference_type' => 'equipment_borrowings', 'reference_id' => 1],
            ['action' => 'deduct', 'points' => -8, 'reason' => '場地協調未於期限內確認', 'reference_type' => 'conflicts', 'reference_id' => 1],
            ['action' => 'add', 'points' => 10, 'reason' => '活動申請資料完整且一次核准', 'reference_type' => 'activity_applications', 'reference_id' => 1],
        ];

        foreach ($rows as $row) {
            CreditLog::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'reason' => $row['reason'],
                ],
                [
                    'action' => $row['action'],
                    'points' => $row['points'],
                    'reference_type' => $row['reference_type'],
                    'reference_id' => $row['reference_id'],
                ]
            );
        }
    }
}
