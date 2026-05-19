<?php

namespace Database\Seeders\Workflow;

use App\Models\CreditLog;
use Illuminate\Database\Seeder;

class CreditLogSeeder extends Seeder {
    public function run(): void {
        CreditLog::create(['user_id' => 1, 'action' => 'deduct', 'points' => -10, 'reason' => '協商超時未回應']);
        CreditLog::create(['user_id' => 1, 'action' => 'add', 'points' => 5, 'reason' => '按時歸還器材']);
        CreditLog::create(['user_id' => 1, 'action' => 'deduct', 'points' => -5, 'reason' => '遲到簽到']);
        CreditLog::create(['user_id' => 1, 'action' => 'add', 'points' => 10, 'reason' => '完成社團評鑑資料']);
        CreditLog::create(['user_id' => 1, 'action' => 'deduct', 'points' => -3, 'reason' => '活動未到場']);
    }
}
