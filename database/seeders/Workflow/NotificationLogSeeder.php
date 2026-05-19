<?php

namespace Database\Seeders\Workflow;

use App\Models\NotificationLog;
use Illuminate\Database\Seeder;

class NotificationLogSeeder extends Seeder {
    public function run(): void {
        NotificationLog::create(['user_id' => 1, 'title' => '場地申請已核准', 'message' => '您的焯炤館地下室旋律廣場 EZ012 場地申請（04/15 14:00-17:00）已通過審核', 'channel' => 'system', 'read' => false]);
        NotificationLog::create(['user_id' => 1, 'title' => '器材歸還提醒', 'message' => 'B3 數位相機 借用期限將於明天到期，請準時歸還', 'channel' => 'line', 'read' => false]);
        NotificationLog::create(['user_id' => 1, 'title' => '信用積分變更', 'message' => '因按時歸還器材 +5 分，目前積分 85 分', 'channel' => 'system', 'read' => true]);
        NotificationLog::create(['user_id' => 1, 'title' => '活動報名確認', 'message' => '您已成功報名「攝影社春季外拍」(04/20)', 'channel' => 'system', 'read' => true]);
        NotificationLog::create(['user_id' => 1, 'title' => 'AI 預審通過', 'message' => '您提交的「程式設計工作坊」活動企劃已通過 AI 預審', 'channel' => 'system', 'read' => true]);
    }
}
