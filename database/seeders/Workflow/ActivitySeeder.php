<?php

namespace Database\Seeders\Workflow;

use App\Models\Activity;
use App\Models\ActivityApplication;
use App\Models\Club;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder {
    public function run(): void {
        $admin = User::where('student_id', 'A001')->first();

        $applicationRows = [
            [
                'serial_no' => 'AA-2026-00001',
                'applicant_student_id' => '410012345',
                'club_name' => '攝影社',
                'activity_name' => '攝影社春季外拍',
                'unit_code' => '063',
                'unit_name' => '攝影社',
                'department' => '課外活動指導組',
                'responsible_person' => '王大明',
                'contact_phone' => '0912-345-678',
                'purpose' => '攝影技巧實作與外拍練習',
                'event_date' => '2026-05-24',
                'start_time' => '09:00',
                'end_time' => '16:00',
                'venue_description' => '校園戶外空間',
                'expected_participants' => 45,
                'staff_count' => 8,
                'budget_requested' => 12000,
                'status' => 'approved',
            ],
            [
                'serial_no' => 'AA-2026-00002',
                'applicant_student_id' => '410012346',
                'club_name' => '民謠吉他社',
                'activity_name' => '民謠吉他社期末成果展',
                'unit_code' => '076',
                'unit_name' => '民謠吉他社',
                'department' => '課外活動指導組',
                'responsible_person' => '陳小美',
                'contact_phone' => '0923-456-789',
                'purpose' => '學期成果展演與社團招生',
                'event_date' => '2026-05-28',
                'start_time' => '14:00',
                'end_time' => '17:00',
                'venue_description' => '焯炤館地下室旋律廣場 EZ012',
                'expected_participants' => 120,
                'staff_count' => 12,
                'budget_requested' => 25000,
                'status' => 'submitted',
            ],
            [
                'serial_no' => 'AA-2026-00003',
                'applicant_student_id' => '410012347',
                'club_name' => '健言社',
                'activity_name' => '校際辯論邀請賽',
                'unit_code' => '001',
                'unit_name' => '健言社',
                'department' => '課外活動指導組',
                'responsible_person' => '李同學',
                'contact_phone' => '0934-567-890',
                'purpose' => '跨校辯論交流',
                'event_date' => '2026-05-28',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'venue_description' => '進修部地下室教室 ES002',
                'expected_participants' => 60,
                'staff_count' => 10,
                'budget_requested' => 18000,
                'status' => 'approved',
            ],
        ];

        foreach ($applicationRows as $row) {
            $applicant = User::where('student_id', $row['applicant_student_id'])->first();
            $club = Club::where('name', $row['club_name'])->where('type', 'club')->first();

            if (!$applicant) {
                continue;
            }

            ActivityApplication::updateOrCreate(
                ['serial_no' => $row['serial_no']],
                [
                    'applicant_id' => $applicant->id,
                    'club_id' => $club?->id,
                    'unit_code' => $row['unit_code'],
                    'responsible_person' => $row['responsible_person'],
                    'unit_name' => $row['unit_name'],
                    'department' => $row['department'],
                    'contact_phone' => $row['contact_phone'],
                    'activity_name' => $row['activity_name'],
                    'purpose' => $row['purpose'],
                    'event_date' => $row['event_date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'venue_description' => $row['venue_description'],
                    'expected_participants' => $row['expected_participants'],
                    'staff_count' => $row['staff_count'],
                    'budget_requested' => $row['budget_requested'],
                    'status' => $row['status'],
                    'reviewed_by' => $row['status'] === 'approved' ? $admin?->id : null,
                    'reviewed_at' => $row['status'] === 'approved' ? now()->subDays(5) : null,
                ]
            );
        }

        $activityRows = [
            ['title' => '攝影社春季外拍', 'club_name' => '攝影社', 'venue_name' => '真善美聖廣場', 'organizer' => '王大明', 'event_date' => '2026-05-24', 'start_time' => '09:00', 'end_time' => '16:00', 'max' => 60, 'current' => 42, 'category' => 'arts', 'status' => 'approved'],
            ['title' => '民謠吉他社期末成果展', 'club_name' => '民謠吉他社', 'venue_name' => '焯炤館地下室旋律廣場 EZ012', 'organizer' => '陳小美', 'event_date' => '2026-05-28', 'start_time' => '14:00', 'end_time' => '17:00', 'max' => 120, 'current' => 0, 'category' => 'music', 'status' => 'pending'],
            ['title' => '校際辯論邀請賽', 'club_name' => '健言社', 'venue_name' => '進修部地下室教室 ES002', 'organizer' => '李同學', 'event_date' => '2026-05-28', 'start_time' => '13:00', 'end_time' => '17:00', 'max' => 80, 'current' => 60, 'category' => 'academic', 'status' => 'approved'],
        ];

        foreach ($activityRows as $row) {
            $club = Club::where('name', $row['club_name'])->where('type', 'club')->first();
            $venue = Venue::where('name', $row['venue_name'])->first();

            Activity::updateOrCreate(
                ['title' => $row['title'], 'event_date' => $row['event_date']],
                [
                    'description' => $row['title'] . '（Seeder 建立流程資料）',
                    'club_id' => $club?->id,
                    'venue_id' => $venue?->id,
                    'organizer_name' => $row['organizer'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'max_participants' => $row['max'],
                    'current_participants' => $row['current'],
                    'category' => $row['category'],
                    'status' => $row['status'],
                ]
            );
        }
    }
}
