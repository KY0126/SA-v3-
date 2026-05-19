<?php

namespace Database\Seeders\Workflow;

use App\Models\Repair;
use Illuminate\Database\Seeder;

class RepairSeeder extends Seeder {
    public function run(): void {
        Repair::updateOrCreate(['code' => 'RP-001'], ['target' => 'B2 單槍投影機', 'description' => '燈泡更換', 'status' => 'processing', 'assignee' => '維修組 王先生']);
        Repair::updateOrCreate(['code' => 'RP-002'], ['target' => '進修部地下室教室 ES003 冷氣', 'description' => '冷氣不涼', 'status' => 'pending', 'assignee' => null]);
        Repair::updateOrCreate(['code' => 'RP-003'], ['target' => '焯炤館地下室旋律廣場 EZ012 音響', 'description' => '左聲道音質異常', 'status' => 'completed', 'assignee' => '維修組 李先生']);
    }
}
