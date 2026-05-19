<?php

namespace Database\Seeders\Workflow;

use App\Models\ActivityApplication;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder {
    public function run(): void {
        $admin = User::where('student_id', 'A001')->first();

        $rows = [
            [
                'student_id' => '410012345',
                'club_name' => '攝影社',
                'venue_name' => '進修部地下室教室 ES002',
                'unit_code' => '063',
                'reservation_date' => '2026-05-28',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'priority_level' => 2,
                'status' => 'negotiating',
                'stage' => 'negotiation',
                'serial_no' => 'VB-2026-00001',
                'vb_status' => 'conflicted',
                'activity_serial_no' => 'AA-2026-00001',
                'purpose' => '攝影社成果展示準備與場勘',
            ],
            [
                'student_id' => '410012347',
                'club_name' => '健言社',
                'venue_name' => '進修部地下室教室 ES002',
                'unit_code' => '001',
                'reservation_date' => '2026-05-28',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'priority_level' => 1,
                'status' => 'negotiating',
                'stage' => 'negotiation',
                'serial_no' => 'VB-2026-00002',
                'vb_status' => 'conflicted',
                'activity_serial_no' => 'AA-2026-00003',
                'purpose' => '校際辯論邀請賽正式賽程',
            ],
            [
                'student_id' => '410012346',
                'club_name' => '民謠吉他社',
                'venue_name' => '焯炤館地下室旋律廣場 EZ012',
                'unit_code' => '076',
                'reservation_date' => '2026-05-29',
                'start_time' => '14:00',
                'end_time' => '17:00',
                'priority_level' => 2,
                'status' => 'confirmed',
                'stage' => 'approved',
                'serial_no' => 'VB-2026-00003',
                'vb_status' => 'approved',
                'activity_serial_no' => 'AA-2026-00002',
                'purpose' => '民謠吉他社期末成果展',
            ],
            [
                'student_id' => '410012348',
                'club_name' => '同舟共濟服務社',
                'venue_name' => '進修部地下室演講廳 ES001',
                'unit_code' => '034',
                'reservation_date' => '2026-06-02',
                'start_time' => '10:00',
                'end_time' => '12:00',
                'priority_level' => 2,
                'status' => 'confirmed',
                'stage' => 'approved',
                'serial_no' => 'VB-2026-00004',
                'vb_status' => 'approved',
                'activity_serial_no' => 'AA-2026-00001',
                'purpose' => '社區服務行前說明會',
            ],
            [
                'student_id' => '410012345',
                'club_name' => '攝影社',
                'venue_name' => '進修部地下室演講廳 ES001',
                'unit_code' => '063',
                'reservation_date' => '2026-06-02',
                'start_time' => '10:00',
                'end_time' => '12:00',
                'priority_level' => 3,
                'status' => 'negotiating',
                'stage' => 'negotiation',
                'serial_no' => 'VB-2026-00005',
                'vb_status' => 'conflicted',
                'activity_serial_no' => 'AA-2026-00001',
                'purpose' => '攝影展彩排',
            ],
            [
                'student_id' => '410012346',
                'club_name' => '民謠吉他社',
                'venue_name' => '焯炤館地下室演講廳 EZ003',
                'unit_code' => '076',
                'reservation_date' => '2026-06-05',
                'start_time' => '18:00',
                'end_time' => '21:00',
                'priority_level' => 2,
                'status' => 'negotiating',
                'stage' => 'negotiation',
                'serial_no' => 'VB-2026-00006',
                'vb_status' => 'conflicted',
                'activity_serial_no' => 'AA-2026-00002',
                'purpose' => '成果展總彩排',
            ],
            [
                'student_id' => '410012347',
                'club_name' => '健言社',
                'venue_name' => '焯炤館地下室演講廳 EZ003',
                'unit_code' => '001',
                'reservation_date' => '2026-06-05',
                'start_time' => '18:00',
                'end_time' => '21:00',
                'priority_level' => 1,
                'status' => 'negotiating',
                'stage' => 'negotiation',
                'serial_no' => 'VB-2026-00007',
                'vb_status' => 'conflicted',
                'activity_serial_no' => 'AA-2026-00003',
                'purpose' => '辯論決賽夜間場',
            ],
        ];

        foreach ($rows as $row) {
            $user = User::where('student_id', $row['student_id'])->first();
            $venue = Venue::where('name', $row['venue_name'])->first();
            $application = ActivityApplication::where('serial_no', $row['activity_serial_no'])->first();

            if (!$user || !$venue) {
                continue;
            }

            Reservation::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'venue_id' => $venue->id,
                    'reservation_date' => $row['reservation_date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                ],
                [
                    'unit_code' => $row['unit_code'],
                    'activity_id' => null,
                    'user_name' => $user->name,
                    'club_name' => $row['club_name'],
                    'venue_name' => $venue->name,
                    'priority_level' => $row['priority_level'],
                    'status' => $row['status'],
                    'stage' => $row['stage'],
                    'preference_order' => 1,
                    'purpose' => $row['purpose'],
                ]
            );

            DB::table('venue_bookings')->updateOrInsert(
                ['serial_no' => $row['serial_no']],
                [
                    'venue_id' => $venue->id,
                    'applicant_id' => $user->id,
                    'unit_code' => $row['unit_code'],
                    'activity_application_id' => $application?->id,
                    'booking_date' => $row['reservation_date'],
                    'start_time' => $row['start_time'],
                    'end_time' => $row['end_time'],
                    'expected_participants' => $application?->expected_participants ?? 0,
                    'purpose' => $row['purpose'],
                    'status' => $row['vb_status'],
                    'reviewed_by' => $row['vb_status'] === 'approved' ? $admin?->id : null,
                    'reviewed_at' => $row['vb_status'] === 'approved' ? now()->subDays(3) : null,
                    'reject_reason' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
