<?php

namespace Database\Seeders\Workflow;

use App\Models\Appeal;
use Illuminate\Database\Seeder;

class AppealSeeder extends Seeder {
    public function run(): void {
        Appeal::create(['code' => 'AP-042', 'appeal_type' => '場地衝突', 'subject' => '攝影社與健言社進修部教室 ES002 使用時段衝突', 'status' => 'pending']);
        Appeal::create(['code' => 'AP-038', 'appeal_type' => '器材損壞', 'subject' => '借用B4 DV攝影機歸還時發現損壞', 'status' => 'processing']);
        Appeal::create(['code' => 'AP-035', 'appeal_type' => '信用積分', 'subject' => '因系統故障導致簽到失敗扣分', 'status' => 'resolved']);
    }
}
