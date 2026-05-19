<?php

namespace Database\Seeders\Workflow;

use App\Models\EquipmentBorrowing;
use App\Models\Reservation;
use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationLogSeeder extends Seeder {
    public function run(): void {
        $messages = [];

        $approvedReservation = Reservation::where('club_name', '民謠吉他社')
            ->where('status', 'confirmed')
            ->where('reservation_date', '2026-05-29')
            ->first();

        if ($approvedReservation) {
            $messages[] = [
                'user_id' => $approvedReservation->user_id,
                'title' => '場地申請已核准',
                'message' => '您申請的 ' . $approvedReservation->venue_name . '（' . $approvedReservation->reservation_date . ' ' . $approvedReservation->start_time . '-' . $approvedReservation->end_time . '）已核准。',
                'channel' => 'system',
                'tracking_token' => 'seed:reservation-approved:' . $approvedReservation->id,
                'read' => false,
            ];
        }

        $borrowings = EquipmentBorrowing::with('equipment')
            ->whereIn('status', ['active', 'overdue'])
            ->get();

        foreach ($borrowings as $borrowing) {
            $messages[] = [
                'user_id' => $borrowing->borrower_id,
                'title' => $borrowing->status === 'overdue' ? '器材逾期警示' : '器材到期提醒',
                'message' => ($borrowing->equipment?->code ?? '未知器材')
                    . ' ' . ($borrowing->equipment?->name ?? '')
                    . ' 應歸還日為 ' . $borrowing->expected_return_date . '，請盡速處理。',
                'channel' => 'line',
                'tracking_token' => 'seed:borrowing-reminder:' . $borrowing->id,
                'read' => false,
            ];
        }

        $conflictParty = User::where('student_id', '410012347')->first();
        if ($conflictParty) {
            $messages[] = [
                'user_id' => $conflictParty->id,
                'title' => '衝突協調進行中',
                'message' => '您有一筆場地衝突進入私下協調流程，請於 24 小時內回覆。',
                'channel' => 'system',
                'tracking_token' => 'seed:conflict-negotiation:410012347',
                'read' => false,
            ];
        }

        foreach ($messages as $message) {
            NotificationLog::updateOrCreate(
                ['tracking_token' => $message['tracking_token']],
                $message
            );
        }
    }
}
