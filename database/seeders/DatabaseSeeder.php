<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Appeal;
use App\Models\CalendarEvent;
use App\Models\Club;
use App\Models\Conflict;
use App\Models\CreditLog;
use App\Models\Equipment;
use App\Models\NotificationLog;
use App\Models\Repair;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Venue;
use Database\Seeders\Core\UserSeeder;
use Database\Seeders\Demo\DemoSeeder;
use Database\Seeders\Workflow\EquipmentBorrowingSeeder;
use Database\Seeders\Domain\ClubSeeder;
use Database\Seeders\Domain\EquipmentSeeder;
use Database\Seeders\Domain\VenueSeeder;
use Database\Seeders\Scenario\ConflictSeeder;
use Database\Seeders\Workflow\ActivitySeeder;
use Database\Seeders\Workflow\AppealSeeder;
use Database\Seeders\Workflow\CalendarEventSeeder;
use Database\Seeders\Workflow\CreditLogSeeder;
use Database\Seeders\Workflow\NotificationLogSeeder;
use Database\Seeders\Workflow\RepairSeeder;
use Database\Seeders\Workflow\ReservationSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // ====== Users ======
        $users = [
            ['student_id' => '410012345', 'name' => '王大明', 'email' => '410012345@cloud.fju.edu.tw', 'phone' => '0912-345-678', 'role' => 'student', 'club_position' => '攝影社 副社長', 'credit_score' => 85],
            ['student_id' => '410012346', 'name' => '陳小美', 'email' => '410012346@cloud.fju.edu.tw', 'phone' => '0923-456-789', 'role' => 'officer', 'club_position' => '民謠吉他社 社長', 'credit_score' => 92],
            ['student_id' => 'T001', 'name' => '林教授', 'email' => 'lin@cloud.fju.edu.tw', 'phone' => '(02)2905-2001', 'role' => 'professor', 'club_position' => '攝影社 指導教授', 'credit_score' => 100],
            ['student_id' => 'A001', 'name' => '張組長', 'email' => 'zhang@cloud.fju.edu.tw', 'phone' => '(02)2905-2002', 'role' => 'admin', 'club_position' => '課指組 組長', 'credit_score' => 100],
            ['student_id' => 'IT001', 'name' => '李工程師', 'email' => 'li@cloud.fju.edu.tw', 'phone' => '(02)2905-3001', 'role' => 'it', 'club_position' => '資訊中心 工程師', 'credit_score' => 100],
            ['student_id' => '410012347', 'name' => '李同學', 'email' => '410012347@cloud.fju.edu.tw', 'phone' => '0934-567-890', 'role' => 'student', 'club_position' => '健言社 社員', 'credit_score' => 90],
            ['student_id' => '410012348', 'name' => '張同學', 'email' => '410012348@cloud.fju.edu.tw', 'phone' => '0945-678-901', 'role' => 'student', 'club_position' => '同舟共濟服務社 社長', 'credit_score' => 88],
        ];
        foreach ($users as $u) {
            User::create($u);
        }

        // ====== Clubs (114學年度社團一覽表，共82個) ======
        // IDs 1-19: (A)學術性社團
        // IDs 20-33: (B)休閒聯誼性社團
        // IDs 34-41: (C)服務性社團
        // IDs 42-61: (D)體能性社團
        // IDs 62-73: (E)藝術性社團
        // IDs 74-82: (F)音樂性社團
        $clubsData = [
            // (A) 學術性社團 (19個)
            ['健言社', 'academic', '學術性', 'club', 30],
            ['大千社', 'academic', '學術性', 'club', 25],
            ['天文社', 'academic', '學術性', 'club', 28],
            ['中華醫藥研習社', 'academic', '學術性', 'club', 22],
            ['國際經濟商管學生會', 'academic', '學術性', 'club', 45],
            ['占星塔羅社', 'academic', '學術性', 'club', 35],
            ['信望愛社', 'academic', '學術性', 'club', 30],
            ['淨仁社', 'academic', '學術性', 'club', 25],
            ['學園團契社', 'academic', '學術性', 'club', 40],
            ['禪學社', 'academic', '學術性', 'club', 20],
            ['聖經研究社', 'academic', '學術性', 'club', 18],
            ['教育學程學會', 'academic', '學術性', 'club', 30],
            ['福智青年社', 'academic', '學術性', 'club', 35],
            ['性別研究社', 'academic', '學術性', 'club', 28],
            ['永續影響力大使社', 'academic', '學術性', 'club', 32],
            ['創新創業社', 'academic', '學術性', 'club', 45],
            ['租稅研究社', 'academic', '學術性', 'club', 20],
            ['光鹽社', 'academic', '學術性', 'club', 22],
            ['金融投資研究社', 'academic', '學術性', 'club', 38],
            // (B) 休閒聯誼性社團 (14個)
            ['僑生聯誼會', 'recreation', '休閒聯誼性', 'club', 50],
            ['高中校友聯合總會', 'recreation', '休閒聯誼性', 'club', 60],
            ['轉學生聯誼會', 'recreation', '休閒聯誼性', 'club', 40],
            ['野營社', 'recreation', '休閒聯誼性', 'club', 35],
            ['魔術社', 'recreation', '休閒聯誼性', 'club', 25],
            ['棋藝社', 'recreation', '休閒聯誼性', 'club', 28],
            ['飲料調製社', 'recreation', '休閒聯誼性', 'club', 32],
            ['努瑪社', 'recreation', '休閒聯誼性', 'club', 20],
            ['國際菁英學生會', 'recreation', '休閒聯誼性', 'club', 45],
            ['桌上遊戲社', 'recreation', '休閒聯誼性', 'club', 55],
            ['電子競技社', 'recreation', '休閒聯誼性', 'club', 60],
            ['二輪社', 'recreation', '休閒聯誼性', 'club', 30],
            ['咖啡研究社', 'recreation', '休閒聯誼性', 'club', 25],
            ['韓國流行文化研究社', 'recreation', '休閒聯誼性', 'club', 38],
            // (C) 服務性社團 (8個)
            ['同舟共濟服務社', 'service', '服務性', 'club', 35],
            ['醒新愛愛服務社', 'service', '服務性', 'club', 30],
            ['急救康輔社', 'service', '服務性', 'club', 28],
            ['崇德志工服務社', 'service', '服務性', 'club', 32],
            ['基層文化服務社', 'service', '服務性', 'club', 25],
            ['慈濟青年社', 'service', '服務性', 'club', 40],
            ['繪本服務學習社', 'service', '服務性', 'club', 22],
            ['勵德青少年服務社', 'service', '服務性', 'club', 20],
            // (D) 體能性社團 (20個)
            ['登山社', 'sports', '體能性', 'club', 38],
            ['國術社', 'sports', '體能性', 'club', 25],
            ['跆拳道社', 'sports', '體能性', 'club', 30],
            ['柔道社', 'sports', '體能性', 'club', 22],
            ['劍道社', 'sports', '體能性', 'club', 20],
            ['擊劍社', 'sports', '體能性', 'club', 18],
            ['羽球社', 'sports', '體能性', 'club', 45],
            ['桌球社', 'sports', '體能性', 'club', 40],
            ['網球社', 'sports', '體能性', 'club', 35],
            ['射箭社', 'sports', '體能性', 'club', 25],
            ['同心救生社', 'sports', '體能性', 'club', 28],
            ['空手道社', 'sports', '體能性', 'club', 22],
            ['黑輪社', 'sports', '體能性', 'club', 30],
            ['合氣道社', 'sports', '體能性', 'club', 20],
            ['歐洲劍術社', 'sports', '體能性', 'club', 18],
            ['撞球社', 'sports', '體能性', 'club', 25],
            ['Kali武術社', 'sports', '體能性', 'club', 15],
            ['自由潛水社', 'sports', '體能性', 'club', 20],
            ['跑步社', 'sports', '體能性', 'club', 35],
            ['袋棍球社', 'sports', '體能性', 'club', 22],
            // (E) 藝術性社團 (12個)
            ['書法社', 'arts', '藝術性', 'club', 22],
            ['攝影社', 'arts', '藝術性', 'club', 45],
            ['熱舞社', 'arts', '藝術性', 'club', 60],
            ['戲劇社', 'arts', '藝術性', 'club', 35],
            ['國際標準舞蹈社', 'arts', '藝術性', 'club', 25],
            ['廣播演藝社', 'arts', '藝術性', 'club', 28],
            ['動漫電玩研習社', 'arts', '藝術性', 'club', 55],
            ['影片創作社', 'arts', '藝術性', 'club', 30],
            ['弓道社', 'arts', '藝術性', 'club', 22],
            ['光火藝術社', 'arts', '藝術性', 'club', 18],
            ['民俗體育社', 'arts', '藝術性', 'club', 20],
            ['生活花藝設計社', 'arts', '藝術性', 'club', 25],
            // (F) 音樂性社團 (9個)
            ['國樂社', 'music', '音樂性', 'club', 35],
            ['管弦樂社', 'music', '音樂性', 'club', 40],
            ['民謠吉他社', 'music', '音樂性', 'club', 45],
            ['搖滾音樂研究社', 'music', '音樂性', 'club', 30],
            ['鋼琴社', 'music', '音樂性', 'club', 28],
            ['數位音樂創作研習社', 'music', '音樂性', 'club', 25],
            ['烏克麗麗社', 'music', '音樂性', 'club', 32],
            ['嘻哈文化社', 'music', '音樂性', 'club', 38],
            ['爵士鋼琴社', 'music', '音樂性', 'club', 22],
        ];
        foreach ($clubsData as $c) {
            Club::create(['name' => $c[0], 'category' => $c[1], 'category_label' => $c[2], 'type' => $c[3], 'description' => $c[0] . ' - 輔仁大學114學年度', 'member_count' => $c[4], 'is_active' => true]);
        }

        // ====== Associations (114學年度學生自治組織，共84個 + 學生會/議會/法庭) ======
        // format: [name, category, category_label, type, member_count, unit_code]
        $associationsData = [
            // 學生會/學生議會/學生法庭
            ['學生會', 'student_gov', '學生自治', 'association', 0, '001'],
            ['學生議會', 'student_gov', '學生自治', 'association', 0, '002'],
            ['學生法庭', 'student_gov', '學生自治', 'association', 0, '-'],
            // 文學院
            ['文學院代會', 'liberal_arts', '院代會', 'association', 0, '112'],
            ['中文系學會', 'liberal_arts', '系學會', 'association', 120, '008'],
            ['歷史系學會', 'liberal_arts', '系學會', 'association', 85, '009'],
            ['哲學系學會', 'liberal_arts', '系學會', 'association', 70, '010'],
            ['社創學程學會', 'liberal_arts', '學程學會', 'association', 30, '301'],
            // 藝術院
            ['藝術院代會', 'arts_college', '院代會', 'association', 0, '113'],
            ['音樂系學會', 'arts_college', '系學會', 'association', 45, '015'],
            ['應美系學會', 'arts_college', '系學會', 'association', 50, '016'],
            ['景觀系學會', 'arts_college', '系學會', 'association', 40, '105'],
            // 傳播院
            ['傳播院代會', 'communications', '院代會', 'association', 0, '110'],
            ['影傳系學會', 'communications', '系學會', 'association', 75, '130'],
            ['新傳系學會', 'communications', '系學會', 'association', 85, '133'],
            ['廣告系學會', 'communications', '系學會', 'association', 80, '134'],
            // 教運院
            ['教運院代會', 'education', '院代會', 'association', 0, '108'],
            ['圖資系學會', 'education', '系學會', 'association', 60, '011'],
            ['體育系學會', 'education', '系學會', 'association', 55, '014'],
            ['領科學程學會', 'education', '學程學會', 'association', 30, '181'],
            // 理工院
            ['理工院代會', 'science', '院代會', 'association', 0, '004'],
            ['數學系學會', 'science', '系學會', 'association', 65, '017'],
            ['物理系學會', 'science', '系學會', 'association', 55, '018'],
            ['化學系學會', 'science', '系學會', 'association', 50, '019'],
            ['生科系學會', 'science', '系學會', 'association', 60, '020'],
            ['電機系學會', 'science', '系學會', 'association', 90, '024'],
            ['資工系學會', 'science', '系學會', 'association', 105, '025'],
            ['醫資學程學會', 'science', '學程學會', 'association', 40, '182'],
            ['資安學程學會', 'science', '學程學會', 'association', 35, '197'],
            // 外語院
            ['外語院代會', 'languages', '院代會', 'association', 0, '005'],
            ['英文系學會', 'languages', '系學會', 'association', 110, '026'],
            ['德語系學會', 'languages', '系學會', 'association', 35, '027'],
            ['法文系學會', 'languages', '系學會', 'association', 30, '028'],
            ['西文系學會', 'languages', '系學會', 'association', 40, '029'],
            ['日文系學會', 'languages', '系學會', 'association', 65, '030'],
            ['義文系學會', 'languages', '系學會', 'association', 25, '040'],
            ['科創學程學會', 'languages', '學程學會', 'association', 30, '302'],
            // 民生院
            ['民生院代會', 'livelihood', '院代會', 'association', 0, '120'],
            ['兒家系學會', 'livelihood', '系學會', 'association', 60, '012'],
            ['餐旅系學會', 'livelihood', '系學會', 'association', 90, '021'],
            ['食科系學會', 'livelihood', '系學會', 'association', 70, '022'],
            ['營養系學會', 'livelihood', '系學會', 'association', 80, '145'],
            // 織品院
            ['織品院代會', 'textiles', '院代會', 'association', 0, '187'],
            ['織品系學會', 'textiles', '系學會', 'association', 65, '023'],
            // 法律院
            ['法律院代會', 'law', '院代會', 'association', 0, '007'],
            ['法律系學會', 'law', '系學會', 'association', 130, '034'],
            ['財法系學會', 'law', '系學會', 'association', 80, '111'],
            ['學士後法律系學會', 'law', '系學會', 'association', 50, '152'],
            // 管院
            ['管院代會', 'management', '院代會', 'association', 0, '006'],
            ['企管系學會', 'management', '系學會', 'association', 150, '035'],
            ['會計系學會', 'management', '系學會', 'association', 120, '036'],
            ['統資系學會', 'management', '系學會', 'association', 80, '037'],
            ['金企系學會', 'management', '系學會', 'association', 110, '038'],
            ['資管系學會', 'management', '系學會', 'association', 100, '039'],
            // 社科院
            ['社科院代會', 'social_science', '院代會', 'association', 0, '115'],
            ['社會學會', 'social_science', '系學會', 'association', 70, '031'],
            ['社工系學會', 'social_science', '系學會', 'association', 95, '032'],
            ['經濟系學會', 'social_science', '系學會', 'association', 90, '033'],
            ['宗教系學會', 'social_science', '系學會', 'association', 50, '121'],
            ['心理系學會', 'social_science', '系學會', 'association', 90, '013'],
            ['天學學程學會', 'social_science', '學程學會', 'association', 25, '176'],
            // 醫學院
            ['醫學院代會', 'medicine', '院代會', 'association', 0, '114'],
            ['公衛系學會', 'medicine', '系學會', 'association', 80, '103'],
            ['護理系學會', 'medicine', '系學會', 'association', 125, '104'],
            ['臨心系學會', 'medicine', '系學會', 'association', 75, '122'],
            ['醫學系學會', 'medicine', '系學會', 'association', 140, '102'],
            ['職治系學會', 'medicine', '系學會', 'association', 60, '135'],
            ['呼治系學會', 'medicine', '系學會', 'association', 45, '154'],
            // 進修部
            ['進-學代會', 'evening_div', '院代會', 'association', 0, '202'],
            ['進-中文系學會', 'evening_div', '系學會', 'association', 40, '203'],
            ['進-歷史系學會', 'evening_div', '系學會', 'association', 35, '204'],
            ['進-大傳學程學會', 'evening_div', '學程學會', 'association', 30, '206'],
            ['進-運資學程學會', 'evening_div', '學程學會', 'association', 25, '207'],
            ['進-英文系學會', 'evening_div', '系學會', 'association', 35, '208'],
            ['進-日文系學會', 'evening_div', '系學會', 'association', 30, '209'],
            ['進-法律系學會', 'evening_div', '系學會', 'association', 40, '211'],
            ['進-經濟系學會', 'evening_div', '系學會', 'association', 35, '212'],
            ['進-餐旅系學會', 'evening_div', '系學會', 'association', 45, '225'],
            ['進-應美系學會', 'evening_div', '系學會', 'association', 30, '226'],
            ['進-文創學程學會', 'evening_div', '學程學會', 'association', 25, '228'],
            ['進-運管學程學會', 'evening_div', '學程學會', 'association', 28, '230'],
            ['進-商管學程學會', 'evening_div', '學程學會', 'association', 35, '231'],
            ['進-軟創學程學會', 'evening_div', '學程學會', 'association', 22, '232'],
            ['進-長照學程學會', 'evening_div', '學程學會', 'association', 20, '233'],
            ['進-文服學程學會', 'evening_div', '學程學會', 'association', 18, '234'],
            ['進-室設學程學會', 'evening_div', '學程學會', 'association', 22, '236'],
            ['進-服飾學程學會', 'evening_div', '學程學會', 'association', 20, '237'],
        ];
        foreach ($associationsData as $a) {
            Club::create(['name' => $a[0], 'category' => $a[1], 'category_label' => $a[2], 'type' => $a[3], 'description' => '代號：' . $a[5] . ' - 輔仁大學114學年度', 'member_count' => $a[4], 'is_active' => true]);
        }

        // ====== Venues (課指組管轄場地) ======
        // IDs: 1-3 戶外空間, 4-9 進修部, 10-12 文開樓, 13-19 焯炤館, 20-22 仁愛學苑, 23 課指組
        $venues = [
            // 戶外空間
            ['真善美聖廣場', '戶外空間', 0, 'available', '戶外電源箱（可填寫攤位數量）', 25.0356, 121.4300],
            ['校門口左側擺攤區AB', '戶外空間', 0, 'available', '戶外電源（可填寫攤位數量）', 25.0350, 121.4295],
            ['校門口左側擺攤區CD', '戶外空間', 0, 'available', '戶外電源（可填寫攤位數量）', 25.0349, 121.4295],
            // 進修部
            ['進修部地下室演講廳 ES001', '進修部地下室', 200, 'available', '音響、投影機、麥克風', 25.0355, 121.4310],
            ['進修部地下室教室 ES002', '進修部地下室', 40, 'available', '投影機、白板', 25.0354, 121.4310],
            ['進修部地下室教室 ES003', '進修部地下室', 40, 'available', '投影機、白板', 25.0354, 121.4311],
            ['進修部地下室教室 ES004', '進修部地下室', 40, 'available', '投影機、白板', 25.0354, 121.4312],
            ['進修部地下室教室 ES005', '進修部地下室', 40, 'available', '投影機、白板', 25.0354, 121.4313],
            ['進修部地下室教室 ES006', '進修部地下室', 40, 'available', '投影機、白板', 25.0354, 121.4314],
            // 文開樓
            ['文開樓地下室舞蹈空間（左側）', '文開樓地下室', 50, 'available', '鏡面牆、音響', 25.0360, 121.4320],
            ['文開樓地下室舞蹈空間（中間）', '文開樓地下室', 50, 'available', '鏡面牆、音響', 25.0360, 121.4321],
            ['文開樓地下室舞蹈空間（右側軟墊區）', '文開樓地下室', 50, 'available', '軟墊、鏡面牆', 25.0360, 121.4322],
            // 焯炤館
            ['焯炤館地下室演講廳 EZ003', '焯炤館地下室', 160, 'available', '音響、投影機、麥克風', 25.0352, 121.4335],
            ['焯炤館地下室旋律廣場 EZ012', '焯炤館地下室', 100, 'available', '音響、舞台', 25.0352, 121.4336],
            ['焯炤館地下室視聽會議室 EZ015', '焯炤館地下室', 40, 'available', '投影機、視訊設備', 25.0352, 121.4337],
            ['焯炤館地下室中型會議室 EZ008', '焯炤館地下室', 20, 'available', '投影機、白板', 25.0352, 121.4338],
            ['焯炤館地下室鏡鏡屋 EZ016', '焯炤館地下室', 40, 'available', '鏡面牆、音響', 25.0352, 121.4339],
            ['焯炤館地下室夢幻電影城 EZ004', '焯炤館地下室', 50, 'available', '投影機、音響', 25.0352, 121.4340],
            ['焯炤館四樓康樂教室 EZ408', '焯炤館四樓', 100, 'available', '音響、投影機', 25.0353, 121.4335],
            // 仁愛學苑
            ['仁愛學苑公共空間（一樓半）', '仁愛學苑', 30, 'available', '基本設備', 25.0345, 121.4325],
            ['仁愛學苑公共空間（二樓半）', '仁愛學苑', 30, 'available', '基本設備', 25.0345, 121.4326],
            ['仁愛學苑公共空間（三樓半）', '仁愛學苑', 30, 'available', '基本設備', 25.0345, 121.4327],
            // 其他
            ['課指組二樓204會議室', '課外活動指導組', 15, 'available', '投影機、白板', 25.0358, 121.4315],
        ];
        foreach ($venues as $v) {
            Venue::create(['name' => $v[0], 'location' => $v[1], 'capacity' => $v[2], 'status' => $v[3], 'equipment_list' => $v[4], 'latitude' => $v[5], 'longitude' => $v[6]]);
        }

        // ====== Activities ======
        // Club IDs (1-indexed): 攝影社=63, 熱舞社=64, 民謠吉他社=76, 健言社=1, 同舟共濟服務社=34, 跑步社=60, 桌上遊戲社=29
        // Venue IDs: 真善美聖廣場=1, ES001=4, ES002=5, EZ003=13, EZ012=14, EZ008=16
        $activities = [
            ['攝影社春季外拍', '攝影技巧實作與外拍練習', 63, null, '王大明', '2026-04-20', '09:00', '17:00', 50, 35, 'arts', 'approved'],
            ['民謠吉他社期末成果展', '吉他社年度成果展演', 76, 14, '陳小美', '2026-04-15', '14:00', '17:00', 100, 0, 'music', 'pending'],
            ['程式設計工作坊', 'Python 與 AI 基礎工作坊', 1, 5, '李同學', '2026-04-25', '13:00', '17:00', 40, 28, 'academic', 'approved'],
            ['同舟共濟服務社淨灘活動', '白沙灣淨灘志工服務', 34, null, '張同學', '2026-05-01', '08:00', '16:00', 80, 45, 'service', 'approved'],
            ['跑步社校園路跑挑戰', '5K 路跑挑戰賽', 60, 1, '劉同學', '2026-05-05', '07:00', '12:00', 150, 0, 'sports', 'pending'],
            ['桌上遊戲社同樂會', '桌遊同樂活動', 29, 16, '林同學', '2026-04-10', '14:00', '16:00', 20, 18, 'recreation', 'approved'],
            ['熱舞社街舞展演', '街舞年度展演', 64, 13, '陳同學', '2026-04-18', '18:00', '21:00', 160, 72, 'arts', 'approved'],
            ['健言社校際辯論邀請賽', '校際辯論邀請賽', 1, 14, '黃同學', '2026-04-22', '09:00', '17:00', 100, 48, 'academic', 'approved'],
        ];
        foreach ($activities as $a) {
            Activity::create(['title' => $a[0], 'description' => $a[1], 'club_id' => $a[2], 'venue_id' => $a[3], 'organizer_name' => $a[4], 'event_date' => $a[5], 'start_time' => $a[6], 'end_time' => $a[7], 'max_participants' => $a[8], 'current_participants' => $a[9], 'category' => $a[10], 'status' => $a[11]]);
        }

        // ====== Equipment (課指組器材一覽表 115/02/01) ======
        $equipment = [
            // 擴音設備
            ['A1', '喊話器（充電電池*1）', '擴音設備', '限借1件，共12件'],
            ['A2', '有線麥克風', '擴音設備', '限借2件，共9件'],
            ['A3', '短麥克風架', '擴音設備', '限借1件，共2件'],
            ['A4', '長麥克風架', '擴音設備', '限借2件，共10件'],
            ['A5', 'MIPRO擴音器 MA-100SB（無線麥克風*1）', '擴音設備', '限借1件，共10件'],
            ['A6', 'MIPRO擴音器 MA-708（無線麥克風*2）', '擴音設備', '限借1件，共10件'],
            ['A7', 'YAMAHA擴音器 600BT（喇叭*2、無線麥克風*2）', '擴音設備', '限借1件，共1件'],
            ['A8', '金嗓卡拉ok（無線麥克風*2）', '擴音設備', '限借1件，共1件'],
            ['A9', '戶外高級音響 MA-808（喇叭*2、無線麥克風*4）', '擴音設備', '限借1件，共1件'],
            ['A10', '電鋼琴', '擴音設備', '限借1件，共1件'],
            // 影音設備
            ['B1', '投影布幕（長150*寬210*高240cm）', '影音設備', '限借1件，共3件'],
            ['B2', '單槍投影機', '影音設備', '限借1件，共5件'],
            ['B3', '數位相機', '影音設備', '限借1件，共3件'],
            ['B4', 'DV攝影機', '影音設備', '限借1件，共3件'],
            ['B5', 'DV腳架', '影音設備', '限借1件，共6件'],
            // 場地佈置設備
            ['C1', 'A字看板 木（長110*寬80cm）／鋁（長89*寬64cm）', '場地佈置設備', '限借2件，共29件'],
            ['C2', '珍珠椅', '場地佈置設備', '限借40件，共200件'],
            ['C3', '折疊鐵椅', '場地佈置設備', '限借10件，共27件'],
            ['C4', '折疊長桌（長180*寬70*高75cm）', '場地佈置設備', '限借4件，共40件'],
            ['C5', '司令帳（沙袋*2）（開-長300*寬300*高345cm）', '場地佈置設備', '限借4件，共40件'],
            ['C6', 'TRUSS帆布立架組（300*200cm長方形）', '場地佈置設備', '限借1件，共2件'],
            ['C7', '交通警示錐', '場地佈置設備', '限借20件，共85件'],
            ['C8', '交通警示橫桿（長200cm）', '場地佈置設備', '限借15件，共80件'],
            ['C9', '插旗組（旗桿、旗帽）', '場地佈置設備', '限借20件，共130件'],
            ['C10', '旗座', '場地佈置設備', '限借10件，共25件'],
            // 燈光設備
            ['D1', '地燈 黃光／白光', '燈光設備', '限借2件，共16件'],
            ['D2', '地燈架', '燈光設備', '限借2件，共10件'],
            ['D3', '七彩旋轉燈', '燈光設備', '限借1件，共3件'],
            ['D4', '追蹤燈組', '燈光設備', '限借1件，共1件'],
            // 其他設備
            ['E1', '延長線捲', '其他設備', '限借2件，共10件'],
            ['E2', '無線電對講機', '其他設備', '限借4件，共16件'],
            ['E3', '茶桶40L', '其他設備', '限借1件，共5件'],
            ['E4', '睡袋', '其他設備', '共84件'],
        ];
        foreach ($equipment as $e) {
            Equipment::create(['code' => $e[0], 'name' => $e[1], 'category' => $e[2], 'status' => 'available', 'condition_note' => $e[3]]);
        }

        // ====== Reservations ======
        Reservation::create(['user_id' => 2, 'venue_id' => 14, 'user_name' => '陳小美', 'club_name' => '民謠吉他社', 'venue_name' => '焯炤館地下室旋律廣場 EZ012', 'reservation_date' => '2026-04-15', 'start_time' => '14:00', 'end_time' => '17:00', 'priority_level' => 2, 'status' => 'confirmed', 'stage' => 'approved']);
        Reservation::create(['user_id' => 1, 'venue_id' => 5, 'user_name' => '王大明', 'club_name' => '攝影社', 'venue_name' => '進修部地下室教室 ES002', 'reservation_date' => '2026-04-20', 'start_time' => '09:00', 'end_time' => '12:00', 'priority_level' => 3, 'status' => 'pending', 'stage' => 'algorithm']);
        Reservation::create(['user_id' => 6, 'venue_id' => 5, 'user_name' => '李同學', 'club_name' => '健言社', 'venue_name' => '進修部地下室教室 ES002', 'reservation_date' => '2026-04-25', 'start_time' => '13:00', 'end_time' => '17:00', 'priority_level' => 3, 'status' => 'confirmed', 'stage' => 'approved']);
        Reservation::create(['user_id' => 7, 'venue_id' => 4, 'user_name' => '張同學', 'club_name' => '同舟共濟服務社', 'venue_name' => '進修部地下室演講廳 ES001', 'reservation_date' => '2026-05-01', 'start_time' => '10:00', 'end_time' => '16:00', 'priority_level' => 2, 'status' => 'negotiating', 'stage' => 'negotiation']);

        // ====== Conflicts ======
        Conflict::create(['party_a' => '攝影社', 'party_b' => '健言社', 'venue_name' => '進修部地下室教室 ES002', 'conflict_date' => '2026-04-25', 'time_slot' => '13:00-17:00', 'status' => 'negotiating', 'stage' => 'ai_intervention', 'elapsed_minutes' => 4]);

        // ====== Credit Logs ======
        CreditLog::create(['user_id' => 1, 'action' => 'deduct', 'points' => -10, 'reason' => '協商超時未回應']);
        CreditLog::create(['user_id' => 1, 'action' => 'add', 'points' => 5, 'reason' => '按時歸還器材']);
        CreditLog::create(['user_id' => 1, 'action' => 'deduct', 'points' => -5, 'reason' => '遲到簽到']);
        CreditLog::create(['user_id' => 1, 'action' => 'add', 'points' => 10, 'reason' => '完成社團評鑑資料']);
        CreditLog::create(['user_id' => 1, 'action' => 'deduct', 'points' => -3, 'reason' => '活動未到場']);

        // ====== Notifications ======
        NotificationLog::create(['user_id' => 1, 'title' => '場地申請已核准', 'message' => '您的焯炤館地下室旋律廣場 EZ012 場地申請（04/15 14:00-17:00）已通過審核', 'channel' => 'system', 'read' => false]);
        NotificationLog::create(['user_id' => 1, 'title' => '器材歸還提醒', 'message' => 'B3 數位相機 借用期限將於明天到期，請準時歸還', 'channel' => 'line', 'read' => false]);
        NotificationLog::create(['user_id' => 1, 'title' => '信用積分變更', 'message' => '因按時歸還器材 +5 分，目前積分 85 分', 'channel' => 'system', 'read' => true]);
        NotificationLog::create(['user_id' => 1, 'title' => '活動報名確認', 'message' => '您已成功報名「攝影社春季外拍」(04/20)', 'channel' => 'system', 'read' => true]);
        NotificationLog::create(['user_id' => 1, 'title' => 'AI 預審通過', 'message' => '您提交的「程式設計工作坊」活動企劃已通過 AI 預審', 'channel' => 'system', 'read' => true]);

        // ====== Calendar Events ======
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
        foreach ($calendarEvents as $e) {
            CalendarEvent::create(['title' => $e[0], 'date' => $e[1], 'type' => $e[2], 'color' => $e[3], 'description' => $e[4], 'venue' => $e[5]]);
        }

        // ====== Repairs ======
        Repair::create(['code' => 'RP-001', 'target' => 'B2 單槍投影機', 'description' => '燈泡更換', 'status' => 'processing', 'assignee' => '維修組 王先生']);
        Repair::create(['code' => 'RP-002', 'target' => '進修部地下室教室 ES003 冷氣', 'description' => '冷氣不涼', 'status' => 'pending', 'assignee' => null]);
        Repair::create(['code' => 'RP-003', 'target' => '焯炤館地下室旋律廣場 EZ012 音響', 'description' => '左聲道音質異常', 'status' => 'completed', 'assignee' => '維修組 李先生']);

        // ====== Appeals ======
        Appeal::create(['code' => 'AP-042', 'appeal_type' => '場地衝突', 'subject' => '攝影社與健言社進修部教室 ES002 使用時段衝突', 'status' => 'pending']);
        Appeal::create(['code' => 'AP-038', 'appeal_type' => '器材損壞', 'subject' => '借用B4 DV攝影機歸還時發現損壞', 'status' => 'processing']);
        Appeal::create(['code' => 'AP-035', 'appeal_type' => '信用積分', 'subject' => '因系統故障導致簽到失敗扣分', 'status' => 'resolved']);
    }
}
