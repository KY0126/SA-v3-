<?php

namespace Database\Seeders\Workflow;

use App\Models\Equipment;
use App\Models\EquipmentBorrowing;
use App\Models\EquipmentLoanDetail;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentBorrowingSeeder extends Seeder {
    public function run(): void {
        $admin = User::where('student_id', 'A001')->first();

        $rows = [
            [
                'loan_serial' => 'EL-2026-00001',
                'student_id' => '410012345',
                'equipment_code' => 'B3',
                'unit_code' => '063',
                'borrow_date' => '2026-05-25',
                'expected_return_date' => '2026-05-30',
                'actual_return_date' => null,
                'status' => 'active',
                'loan_status' => 'picked_up',
                'purpose' => '攝影社外拍器材使用',
                'reminder_sent' => true,
            ],
            [
                'loan_serial' => 'EL-2026-00002',
                'student_id' => '410012346',
                'equipment_code' => 'A6',
                'unit_code' => '076',
                'borrow_date' => '2026-05-24',
                'expected_return_date' => '2026-05-29',
                'actual_return_date' => '2026-05-29',
                'status' => 'returned',
                'loan_status' => 'returned',
                'purpose' => '成果展音響排練',
                'reminder_sent' => false,
            ],
            [
                'loan_serial' => 'EL-2026-00003',
                'student_id' => '410012347',
                'equipment_code' => 'B4',
                'unit_code' => '001',
                'borrow_date' => '2026-05-20',
                'expected_return_date' => '2026-05-26',
                'actual_return_date' => null,
                'status' => 'overdue',
                'loan_status' => 'overdue',
                'purpose' => '辯論隊影片記錄',
                'reminder_sent' => true,
            ],
        ];

        foreach ($rows as $row) {
            $equipment = Equipment::where('code', $row['equipment_code'])->first();
            $user = User::where('student_id', $row['student_id'])->first();

            if (!$equipment || !$user) {
                continue;
            }

            EquipmentBorrowing::updateOrCreate(
                [
                    'equipment_id' => $equipment->id,
                    'borrower_id' => $user->id,
                    'borrow_date' => $row['borrow_date'],
                ],
                [
                    'borrower_name' => $user->name,
                    'expected_return_date' => $row['expected_return_date'],
                    'actual_return_date' => $row['actual_return_date'],
                    'status' => $row['status'],
                    'return_condition' => $row['status'] === 'returned' ? '器材完整' : null,
                    'reminder_sent' => $row['reminder_sent'],
                ]
            );

            DB::table('equipment_loans')->updateOrInsert(
                ['serial_no' => $row['loan_serial']],
                [
                    'borrower_id' => $user->id,
                    'unit_code' => $row['unit_code'],
                    'activity_application_id' => null,
                    'borrow_date' => $row['borrow_date'],
                    'expected_return_date' => $row['expected_return_date'],
                    'actual_return_date' => $row['actual_return_date'],
                    'status' => $row['loan_status'],
                    'purpose' => $row['purpose'],
                    'reject_reason' => null,
                    'approved_by' => $admin?->id,
                    'approved_at' => now()->subDays(5),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $loanId = DB::table('equipment_loans')->where('serial_no', $row['loan_serial'])->value('id');
            if ($loanId) {
                EquipmentLoanDetail::updateOrCreate(
                    ['loan_id' => $loanId, 'equipment_id' => $equipment->id],
                    [
                        'quantity' => 1,
                        'status' => $row['loan_status'] === 'returned' ? 'returned' : 'picked_up',
                        'condition_on_return' => $row['loan_status'] === 'returned' ? '正常' : null,
                        'picked_up_at' => now()->subDays(5),
                        'returned_at' => $row['loan_status'] === 'returned' ? now()->subDays(1) : null,
                    ]
                );
            }

            $equipment->update([
                'status' => in_array($row['status'], ['active', 'overdue'], true) ? 'borrowed' : 'available',
            ]);
        }
    }
}
