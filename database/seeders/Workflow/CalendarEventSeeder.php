<?php

namespace Database\Seeders\Workflow;

use App\Models\CalendarEvent;
use Illuminate\Database\Seeder;

class CalendarEventSeeder extends Seeder {
    public function run(): void {
        $calendarEvents = [
            ['社團評鑑會議', '2026-04-05', 'meeting', '#003153', '地點：焯炤館地下室中型會議室 EZ008 / 14:00-16:00', '焯炤館地下室中型會議室 EZ008'],
            ['金乐獎初審', '2026-04-08', 'competition', '#DAA520', '地點：焯炤館地下室演講廳 EZ003 / 10:00-12:00', '焯炤館地下室演講廳 EZ003'],
            ['桌上遊戲社同樂會', '2026-04-10', 'study', '#008000', '地點：焯炤館地下室中型會議室 EZ008 / 14:00-16:00', '焯炤館地下室中型會議室 EZ008'],
            ['民謠吉他社成果展', '2026-04-15', 'performance', '#003153', '地點：焯炤館地下室旋律廣場 EZ012 / 14:00-17:00', '焯炤館地下室旋律廣場 EZ012'],
            ['熱舞社街舞展演', '2026-04-18', 'performance', '#FF0000', '地點：焯炤館地下室演講廳 EZ003 / 18:00-21:00', '焯炤館地下室演講廳 EZ003'],
            ['攝影社春季外拍', '2026-04-20', 'outdoor', '#DAA520', '地點：校園各處 / 09:00-17:00', '校園各處'],
            ['健言社辯論比賽', '2026-04-22', 'competition', '#003153', '地點：焯炤館地下室旋律廣場 EZ012 / 09:00-17:00', '焯炤館地下室旋律廣場 EZ012'],
            ['程式設計工作坊', '2026-04-25', 'workshop', '#008000', '地點：進修部地下室教室 ES002 / 13:00-17:00', '進修部地下室教室 ES002'],
            ['同舟共濟服務社淨灘', '2026-05-01', 'service', '#008000', '地點：白沙灣 / 08:00-16:00', '白沙灣'],
            ['跑步社校園路跑', '2026-05-05', 'sports', '#FF0000', '地點：真善美聖廣場 / 07:00-12:00', '真善美聖廣場'],
        ];

        foreach ($calendarEvents as $event) {
            CalendarEvent::updateOrCreate([
                'title' => $event[0],
                'date' => $event[1],
            ], [
                'type' => $event[2],
                'color' => $event[3],
                'description' => $event[4],
                'venue' => $event[5],
            ]);
        }
    }
}
