<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\{User, Club, Venue, Activity, Equipment, EquipmentBorrowing, Reservation, Conflict, CreditLog, NotificationLog, CalendarEvent, Repair, Appeal};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ====== Users ======
        $users = [
            ['student_id' => '410012345', 'name' => '王大明', 'email' => '410012345@cloud.fju.edu.tw', 'phone' => '0912-345-678', 'role' => 'student', 'club_position' => '攝影社 副社長', 'credit_score' => 85],
            ['student_id' => '410012346', 'name' => '陳小美', 'email' => '410012346@cloud.fju.edu.tw', 'phone' => '0923-456-789', 'role' => 'officer', 'club_position' => '吉他社 社長', 'credit_score' => 92],
            ['student_id' => 'T001', 'name' => '林教授', 'email' => 'lin@cloud.fju.edu.tw', 'phone' => '(02)2905-2001', 'role' => 'professor', 'club_position' => '攝影社 指導教授', 'credit_score' => 100],
            ['student_id' => 'A001', 'name' => '張組長', 'email' => 'zhang@cloud.fju.edu.tw', 'phone' => '(02)2905-2002', 'role' => 'admin', 'club_position' => '課指組 組長', 'credit_score' => 100],
            ['student_id' => 'IT001', 'name' => '李工程師', 'email' => 'li@cloud.fju.edu.tw', 'phone' => '(02)2905-3001', 'role' => 'it', 'club_position' => '資訊中心 工程師', 'credit_score' => 100],
            ['student_id' => '410012347', 'name' => '李同學', 'email' => '410012347@cloud.fju.edu.tw', 'phone' => '0934-567-890', 'role' => 'student', 'club_position' => '電腦資訊研究社 社員', 'credit_score' => 90],
            ['student_id' => '410012348', 'name' => '張同學', 'email' => '410012348@cloud.fju.edu.tw', 'phone' => '0945-678-901', 'role' => 'student', 'club_position' => '環保社 社長', 'credit_score' => 88],
        ];
        foreach ($users as $u) { User::create($u); }

        // ====== Clubs (114 academic year full list) ======
        $clubsData = [
            // 學藝性社團
            ['電腦資訊研究社','academic','學藝','club',58],['天文社','academic','學藝','club',32],['法學社','academic','學藝','club',28],['英語會話社','academic','學藝','club',45],['日本語文研究社','academic','學藝','club',38],['韓國語文研究社','academic','學藝','club',42],['德語研究社','academic','學藝','club',22],['法語研究社','academic','學藝','club',20],['西班牙語文研究社','academic','學藝','club',25],['創業投資研究社','academic','學藝','club',35],['生命倫理研究社','academic','學藝','club',18],['辯論社','academic','學藝','club',30],['手語社','academic','學藝','club',26],['桌上遊戲社','academic','學藝','club',50],
            // 康樂性社團
            ['吉他社','recreation','康樂','club',55],['熱門音樂社','recreation','康樂','club',48],['管樂社','recreation','康樂','club',40],['弦樂社','recreation','康樂','club',30],['國樂社','recreation','康樂','club',35],['合唱團','recreation','康樂','club',42],['口琴社','recreation','康樂','club',22],['烏克麗麗社','recreation','康樂','club',28],['動漫研究社','recreation','康樂','club',65],['魔術社','recreation','康樂','club',24],['電影研究社','recreation','康樂','club',38],['嘻哈文化研究社','recreation','康樂','club',32],
            // 體育性社團
            ['籃球社','sports','體育','club',60],['排球社','sports','體育','club',45],['桌球社','sports','體育','club',35],['羽球社','sports','體育','club',42],['網球社','sports','體育','club',28],['棒壘球社','sports','體育','club',38],['足球社','sports','體育','club',40],['游泳社','sports','體育','club',25],['田徑社','sports','體育','club',30],['跆拳道社','sports','體育','club',22],['劍道社','sports','體育','club',18],['空手道社','sports','體育','club',15],['柔道社','sports','體育','club',12],['射箭社','sports','體育','club',20],['飛盤社','sports','體育','club',28],['滑板社','sports','體育','club',35],['登山社','sports','體育','club',32],['自行車社','sports','體育','club',22],['健身社','sports','體育','club',50],
            // 藝術性社團
            ['攝影社','arts','藝術','club',45],['書法社','arts','藝術','club',20],['漫畫研究社','arts','藝術','club',35],['戲劇社','arts','藝術','club',32],['熱門舞蹈社','arts','藝術','club',55],['國標舞社','arts','藝術','club',18],['現代舞社','arts','藝術','club',25],['街舞社','arts','藝術','club',40],['美術社','arts','藝術','club',22],
            // 服務性社團
            ['慈青社','service','服務','club',38],['環保社','service','服務','club',30],['手工藝服務社','service','服務','club',20],['國際志工社','service','服務','club',35],['紅十字社','service','服務','club',25],['社區服務社','service','服務','club',22],
            // 宗教性社團
            ['基督生活團','religious','宗教','club',28],['真善美社','religious','宗教','club',22],['佛學社','religious','宗教','club',15],
            // 聯誼性社團
            ['僑生聯誼會','social','聯誼','club',40],['陸生聯誼會','social','聯誼','club',35],['原住民文化研究社','social','聯誼','club',25],
            // 學會
            ['中文系學會','academic','學會','association',120],['英文系學會','academic','學會','association',110],['歷史系學會','academic','學會','association',85],['哲學系學會','academic','學會','association',70],['法律系學會','academic','學會','association',130],['企管系學會','academic','學會','association',150],['會計系學會','academic','學會','association',120],['資管系學會','academic','學會','association',100],['統計系學會','academic','學會','association',80],['金融系學會','academic','學會','association',110],['經濟系學會','academic','學會','association',95],['數學系學會','academic','學會','association',65],['物理系學會','academic','學會','association',55],['化學系學會','academic','學會','association',50],['生科系學會','academic','學會','association',60],['電機系學會','academic','學會','association',90],['資工系學會','academic','學會','association',105],['醫學系學會','academic','學會','association',140],['護理系學會','academic','學會','association',125],['公衛系學會','academic','學會','association',80],['臨心系學會','academic','學會','association',75],['社工系學會','academic','學會','association',95],['社會系學會','academic','學會','association',70],['心理系學會','academic','學會','association',90],['新傳系學會','academic','學會','association',85],['廣告系學會','academic','學會','association',80],['影傳系學會','academic','學會','association',75],['織品系學會','academic','學會','association',65],['食科系學會','academic','學會','association',70],['營養系學會','academic','學會','association',80],['兒家系學會','academic','學會','association',60],['餐旅系學會','academic','學會','association',90],['體育系學會','academic','學會','association',55],['音樂系學會','academic','學會','association',45],['應美系學會','academic','學會','association',50],['景觀系學會','academic','學會','association',40],
        ];
        $id = 1;
        foreach ($clubsData as $c) {
            Club::create(['name' => $c[0], 'category' => $c[1], 'category_label' => $c[2], 'type' => $c[3], 'description' => $c[0] . ' - 輔仁大學114學年度', 'member_count' => $c[4], 'is_active' => true]);
            $id++;
        }

        // ====== Venues ======
        $venues = [
            ['中美堂','校園中心',500,'available','音響、投影機、舞台燈光',25.0356,121.4300],
            ['活動中心','學生活動中心',200,'available','音響、投影機',25.0348,121.4305],
            ['SF 134','理工大樓',80,'available','投影機、白板',25.0365,121.4345],
            ['草地廣場','校園東側',300,'available','電源箱',25.0355,121.4330],
            ['體育館','體育場區',800,'available','計分板、音響',25.0340,121.4320],
            ['淨心堂','校園中心',400,'available','管風琴、音響',25.0363,121.4318],
            ['會議室 A','行政大樓 3F',30,'available','投影機、視訊設備',25.0358,121.4310],
            ['會議室 B','行政大樓 5F',20,'maintenance','投影機',25.0358,121.4312],
            ['百鍊廳','體育場區',100,'available','鏡面牆、音響',25.0345,121.4325],
            ['焯炤館 多功能教室','圖書館 B1',60,'available','投影機、白板',25.0352,121.4335],
        ];
        foreach ($venues as $v) {
            Venue::create(['name'=>$v[0],'location'=>$v[1],'capacity'=>$v[2],'status'=>$v[3],'equipment_list'=>$v[4],'latitude'=>$v[5],'longitude'=>$v[6]]);
        }

        // ====== Activities ======
        $activities = [
            ['攝影社春季外拍','攝影技巧實作與外拍練習',47,null,'王大明','2026-04-20','09:00','17:00',50,35,'arts','approved'],
            ['吉他社期末成果展','吉他社年度成果展演',15,1,'陳小美','2026-04-15','14:00','17:00',200,0,'recreation','pending'],
            ['程式設計工作坊','Python 與 AI 基礎工作坊',1,3,'李同學','2026-04-25','13:00','17:00',40,28,'academic','approved'],
            ['環保淨灘活動','白沙灣淨灘志工服務',57,null,'張同學','2026-05-01','08:00','16:00',80,45,'service','approved'],
            ['校園路跑挑戰','5K 路跑挑戰賽',36,5,'劉同學','2026-05-05','07:00','12:00',150,0,'sports','pending'],
            ['日語讀書會','日語能力考試準備',5,10,'林同學','2026-04-10','14:00','16:00',25,18,'academic','approved'],
            ['熱舞社街舞展演','街舞年度展演',51,9,'陳同學','2026-04-18','18:00','21:00',100,72,'arts','approved'],
            ['辯論比賽 - 校際邀請賽','校際辯論邀請賽',12,2,'黃同學','2026-04-22','09:00','17:00',60,48,'academic','approved'],
        ];
        foreach ($activities as $a) {
            Activity::create(['title'=>$a[0],'description'=>$a[1],'club_id'=>$a[2],'venue_id'=>$a[3],'organizer_name'=>$a[4],'event_date'=>$a[5],'start_time'=>$a[6],'end_time'=>$a[7],'max_participants'=>$a[8],'current_participants'=>$a[9],'category'=>$a[10],'status'=>$a[11]]);
        }

        // ====== Equipment ======
        $equipment = [
            ['EQ-001','Canon EOS R6 Mark II','攝影','borrowed','良好'],
            ['EQ-002','Sony A7III','攝影','available','良好'],
            ['EQ-003','投影機 EPSON EB-X51','視聽','maintenance','燈泡更換中'],
            ['EQ-004','無線麥克風組 (Shure)','音響','available','良好'],
            ['EQ-005','JBL Eon One Compact 音箱','音響','available','良好'],
            ['EQ-006','DJI Ronin SC 穩定器','攝影','borrowed','良好'],
            ['EQ-007','白板架 (含白板筆組)','文具','available','良好'],
            ['EQ-008','MacBook Pro 16" (投影用)','電腦','available','良好'],
        ];
        foreach ($equipment as $e) {
            $eq = Equipment::create(['code'=>$e[0],'name'=>$e[1],'category'=>$e[2],'status'=>$e[3],'condition_note'=>$e[4]]);
            if ($e[3] === 'borrowed') {
                EquipmentBorrowing::create(['equipment_id'=>$eq->id,'borrower_id'=>$eq->id===1?2:1,'borrower_name'=>$eq->id===1?'陳同學':'王大明','borrow_date'=>'2026-03-28','expected_return_date'=>'2026-04-05','status'=>'active']);
            }
        }

        // ====== Reservations ======
        Reservation::create(['user_id'=>2,'venue_id'=>1,'user_name'=>'陳小美','club_name'=>'吉他社','venue_name'=>'中美堂','reservation_date'=>'2026-04-15','start_time'=>'14:00','end_time'=>'17:00','priority_level'=>2,'status'=>'confirmed','stage'=>'approved']);
        Reservation::create(['user_id'=>1,'venue_id'=>3,'user_name'=>'王大明','club_name'=>'攝影社','venue_name'=>'SF 134','reservation_date'=>'2026-04-20','start_time'=>'09:00','end_time'=>'12:00','priority_level'=>3,'status'=>'pending','stage'=>'algorithm']);
        Reservation::create(['user_id'=>6,'venue_id'=>3,'user_name'=>'李同學','club_name'=>'資訊社','venue_name'=>'SF 134','reservation_date'=>'2026-04-25','start_time'=>'13:00','end_time'=>'17:00','priority_level'=>3,'status'=>'confirmed','stage'=>'approved']);
        Reservation::create(['user_id'=>7,'venue_id'=>2,'user_name'=>'張同學','club_name'=>'環保社','venue_name'=>'活動中心','reservation_date'=>'2026-05-01','start_time'=>'10:00','end_time'=>'16:00','priority_level'=>2,'status'=>'negotiating','stage'=>'negotiation']);

        // ====== Conflicts ======
        Conflict::create(['party_a'=>'攝影社','party_b'=>'吉他社','venue_name'=>'中美堂','conflict_date'=>'2026-04-15','time_slot'=>'14:00-17:00','status'=>'negotiating','stage'=>'ai_intervention','elapsed_minutes'=>4]);

        // ====== Credit Logs ======
        CreditLog::create(['user_id'=>1,'action'=>'deduct','points'=>-10,'reason'=>'協商超時未回應']);
        CreditLog::create(['user_id'=>1,'action'=>'add','points'=>5,'reason'=>'按時歸還器材']);
        CreditLog::create(['user_id'=>1,'action'=>'deduct','points'=>-5,'reason'=>'遲到簽到']);
        CreditLog::create(['user_id'=>1,'action'=>'add','points'=>10,'reason'=>'完成社團評鑑資料']);
        CreditLog::create(['user_id'=>1,'action'=>'deduct','points'=>-3,'reason'=>'活動未到場']);

        // ====== Notifications ======
        NotificationLog::create(['user_id'=>1,'title'=>'場地申請已核准','message'=>'您的中美堂場地申請（04/15 14:00-17:00）已通過審核','channel'=>'system','read'=>false]);
        NotificationLog::create(['user_id'=>1,'title'=>'器材歸還提醒','message'=>'Canon EOS R6 Mark II 借用期限將於明天到期，請準時歸還','channel'=>'line','read'=>false]);
        NotificationLog::create(['user_id'=>1,'title'=>'信用積分變更','message'=>'因按時歸還器材 +5 分，目前積分 85 分','channel'=>'system','read'=>true]);
        NotificationLog::create(['user_id'=>1,'title'=>'活動報名確認','message'=>'您已成功報名「攝影社春季外拍」(04/20)','channel'=>'system','read'=>true]);
        NotificationLog::create(['user_id'=>1,'title'=>'AI 預審通過','message'=>'您提交的「程式設計工作坊」活動企劃已通過 AI 預審','channel'=>'system','read'=>true]);

        // ====== Calendar Events ======
        $calendarEvents = [
            ['社團評鑑會議','2026-04-05','meeting','#003153','地點：活動中心 / 14:00-16:00','活動中心'],
            ['金乐獎初審','2026-04-08','competition','#DAA520','地點：中美堂 / 10:00-12:00','中美堂'],
            ['日語讀書會','2026-04-10','study','#008000','地點：焯炤館 / 14:00-16:00','焯炤館'],
            ['吉他社成果展','2026-04-15','performance','#003153','地點：中美堂 / 14:00-17:00','中美堂'],
            ['熱舞社街舞展演','2026-04-18','performance','#FF0000','地點：百鍊廳 / 18:00-21:00','百鍊廳'],
            ['攝影社春季外拍','2026-04-20','outdoor','#DAA520','地點：校園各處 / 09:00-17:00','校園'],
            ['辯論比賽','2026-04-22','competition','#003153','地點：活動中心 / 09:00-17:00','活動中心'],
            ['程式設計工作坊','2026-04-25','workshop','#008000','地點：SF 134 / 13:00-17:00','SF 134'],
            ['環保社淨灘','2026-05-01','service','#008000','地點：白沙灣 / 08:00-16:00','白沙灣'],
            ['校園路跑','2026-05-05','sports','#FF0000','地點：操場 / 07:00-12:00','操場'],
        ];
        foreach ($calendarEvents as $e) { CalendarEvent::create(['title'=>$e[0],'date'=>$e[1],'type'=>$e[2],'color'=>$e[3],'description'=>$e[4],'venue'=>$e[5]]); }

        // ====== Repairs ======
        Repair::create(['code'=>'RP-001','target'=>'投影機 EPSON EB-X51','description'=>'燈泡更換','status'=>'processing','assignee'=>'維修組 王先生']);
        Repair::create(['code'=>'RP-002','target'=>'SF 134 冷氣','description'=>'冷氣不涼','status'=>'pending','assignee'=>null]);
        Repair::create(['code'=>'RP-003','target'=>'中美堂音響','description'=>'左聲道音質異常','status'=>'completed','assignee'=>'維修組 李先生']);

        // ====== Appeals ======
        Appeal::create(['code'=>'AP-042','appeal_type'=>'場地衝突','subject'=>'攝影社與吉他社中美堂使用時段衝突','status'=>'pending']);
        Appeal::create(['code'=>'AP-038','appeal_type'=>'器材損壞','subject'=>'借用投影機歸還時發現損壞','status'=>'processing']);
        Appeal::create(['code'=>'AP-035','appeal_type'=>'信用積分','subject'=>'因系統故障導致簽到失敗扣分','status'=>'resolved']);
    }
}
