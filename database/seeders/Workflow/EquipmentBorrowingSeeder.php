<?php

namespace Database\Seeders\Workflow;

use App\Models\Equipment;
use App\Models\EquipmentBorrowing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EquipmentBorrowingSeeder extends Seeder {
    public function run(): void {
        // Create a sample borrowing for equipment code B3 so notifications referencing B3 make sense.
        $equipment = Equipment::where('code', 'B3')->first();
        $borrower = User::first();
        if (!$equipment || !$borrower) {
            return;
        }

        $borrowDate = Carbon::today()->subDays(1)->toDateString();
        $expectedReturn = Carbon::today()->addDay()->toDateString();

        EquipmentBorrowing::create([
            'equipment_id' => $equipment->id,
            'borrower_id' => $borrower->id,
            'borrower_name' => $borrower->name ?? null,
            'borrow_date' => $borrowDate,
            'expected_return_date' => $expectedReturn,
            'status' => 'active',
            'reminder_sent' => false,
        ]);

        // mark equipment as borrowed so status aligns with borrowing
        $equipment->update(['status' => 'borrowed']);
    }
}
