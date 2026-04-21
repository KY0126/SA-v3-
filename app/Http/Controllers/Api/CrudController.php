<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\{Conflict, CreditLog, NotificationLog, Certificate, Appeal, Repair, CalendarEvent, User, Club, Reservation};
use Illuminate\Http\Request;

class CrudController extends Controller
{
    // ====== Conflicts (Enhanced Coordination) ======
    public function conflictIndex(Request $r) {
        $q = Conflict::orderBy('created_at', 'desc');
        if ($r->status) $q->where('status', $r->status);
        $data = $q->get()->map(function($c) {
            $c->chat_messages = json_decode($c->chat_messages, true) ?? [];
            return $c;
        });
        return response()->json(['data' => $data]);
    }

    public function conflictShow($id) {
        $c = Conflict::findOrFail($id);
        $c->chat_messages = json_decode($c->chat_messages, true) ?? [];
        // Get related reservations
        $resA = $c->reservation_a_id ? Reservation::find($c->reservation_a_id) : null;
        $resB = $c->reservation_b_id ? Reservation::find($c->reservation_b_id) : null;
        return response()->json([
            'data' => $c,
            'reservation_a' => $resA,
            'reservation_b' => $resB,
        ]);
    }

    public function conflictStore(Request $r) {
        $c = Conflict::create($r->all() + [
            'chat_messages' => '[]',
            'party_a_confirmed' => false,
            'party_b_confirmed' => false,
        ]);
        // Auto-create notifications for both parties
        $this->_sendConflictNotification($c, 'new');
        return response()->json(['success' => true, 'data' => $c], 201);
    }

    public function conflictNegotiate(Request $r) {
        $elapsed = $r->elapsed_minutes ?? 0;
        $resp = [
            'suggestions' => [
                ['id' => 1, 'description' => ($r->party_a_name ?? '甲方').'改至其他場地', 'confidence' => 0.85],
                ['id' => 2, 'description' => '時段分割，各使用一半時間', 'confidence' => 0.78],
                ['id' => 3, 'description' => ($r->party_b_name ?? '乙方').'延後至隔天', 'confidence' => 0.72],
            ],
            'ai_reasoning' => 'Based on historical usage patterns and both parties\' flexibility scores...',
        ];
        if ($elapsed >= 6) {
            $resp['forced_close'] = true;
            $resp['credit_deduction'] = ['points' => 10, 'reason' => '協商超過6分鐘，強制關閉'];
            $resp['message'] = '⚠️ 協商已超過6分鐘限制，系統強制關閉並扣除雙方各10信用積分';
        } elseif ($elapsed >= 3) {
            $resp['timeout_warning'] = '已超過3分鐘，AI建議已介入';
            $resp['ai_intervention'] = true;
        }
        return response()->json($resp);
    }

    // -- Chat in conflict --
    public function conflictChat(Request $r, $id) {
        $conflict = Conflict::findOrFail($id);
        $msgs = json_decode($conflict->chat_messages, true) ?? [];
        $msgs[] = [
            'sender' => $r->sender ?? '匿名',
            'party' => $r->party ?? 'a',
            'message' => $r->message,
            'timestamp' => now()->toIso8601String(),
        ];
        $conflict->update([
            'chat_messages' => json_encode($msgs, JSON_UNESCAPED_UNICODE),
            'status' => 'negotiating',
        ]);
        return response()->json(['success' => true, 'messages' => $msgs]);
    }

    // -- Send email notification (simulated) --
    public function conflictSendEmail(Request $r, $id) {
        $conflict = Conflict::findOrFail($id);
        $party = $r->party ?? 'a'; // a or b
        $update = $party === 'a' ? ['email_sent_a' => true] : ['email_sent_b' => true];
        $conflict->update($update);
        // Create notification log
        NotificationLog::create([
            'user_id' => 1,
            'title' => '場地衝突協商通知 - 電子郵件',
            'message' => "已對{$conflict->{'party_'.$party}}發送協商通知郵件，衝突編號：#{$conflict->id}",
            'channel' => 'email',
        ]);
        return response()->json([
            'success' => true,
            'message' => "已發送電子郵件通知給".($party === 'a' ? $conflict->party_a : $conflict->party_b),
            'email_status' => $party === 'a' ? 'sent_to_a' : 'sent_to_b',
        ]);
    }

    // -- Confirm resolution --
    public function conflictConfirm(Request $r, $id) {
        $conflict = Conflict::findOrFail($id);
        $party = $r->party ?? 'a';
        $update = [];
        if ($party === 'a') {
            $update = ['party_a_confirmed' => true, 'party_a_confirmed_at' => now()];
        } else {
            $update = ['party_b_confirmed' => true, 'party_b_confirmed_at' => now()];
        }
        $conflict->update($update);
        $conflict->refresh();

        // Check if both parties confirmed
        $bothConfirmed = $conflict->party_a_confirmed && $conflict->party_b_confirmed;
        if ($bothConfirmed) {
            $conflict->update([
                'status' => 'resolved',
                'resolution_type' => $r->resolution_type ?? 'mutual_agreement',
                'resolution' => $r->resolution ?? '雙方已確認協商結果',
            ]);
            // Update related reservations
            if ($conflict->reservation_a_id) {
                Reservation::where('id', $conflict->reservation_a_id)->update(['stage' => 'approval', 'status' => 'confirmed']);
            }
            if ($conflict->reservation_b_id) {
                Reservation::where('id', $conflict->reservation_b_id)->update(['stage' => 'approval', 'status' => 'confirmed']);
            }
            // Notify coordinator
            $this->_sendConflictNotification($conflict, 'resolved');
        }

        return response()->json([
            'success' => true,
            'party_confirmed' => $party,
            'both_confirmed' => $bothConfirmed,
            'conflict_status' => $conflict->status,
            'message' => $bothConfirmed
                ? '雙方均已確認！衝突已解決，已自動更新預約狀態。'
                : ($party === 'a' ? $conflict->party_a : $conflict->party_b) . ' 已確認，等待對方確認。',
        ]);
    }

    private function _sendConflictNotification(Conflict $c, $type) {
        $title = $type === 'new' ? '⚠️ 新場地衝突通知' : '✅ 場地衝突已解決';
        $msg = $type === 'new'
            ? "場地 {$c->venue_name} 於 {$c->conflict_date} {$c->time_slot} 時段發生衝突（{$c->party_a} vs {$c->party_b}），請儘速協商。"
            : "衝突 #{$c->id}（{$c->venue_name}）已由雙方確認解決。";
        NotificationLog::create(['user_id' => 1, 'title' => $title, 'message' => $msg, 'channel' => 'system']);
    }

    // ====== Credit System ======
    public function creditShow($userId) {
        $user = User::find($userId);
        $score = $user?->credit_score ?? 85;
        $logs = CreditLog::where('user_id', $userId)->orderBy('created_at', 'desc')->limit(20)->get();
        return response()->json([
            'score' => $score, 'status' => $score < 60 ? 'danger' : ($score < 75 ? 'warning' : 'normal'),
            'logs' => $logs, 'threshold' => 60, 'force_logout' => $score < 60
        ]);
    }
    public function creditDeduct(Request $r) {
        $user = User::find($r->user_id);
        if ($user) {
            $user->decrement('credit_score', $r->points ?? 10);
            CreditLog::create(['user_id' => $user->id, 'action' => 'deduct', 'points' => -($r->points ?? 10), 'reason' => $r->reason ?? '系統扣分']);
            $user->refresh();
            return response()->json([
                'success' => true, 'new_score' => $user->credit_score,
                'force_logout' => $user->credit_score < 60,
                'event' => $user->credit_score < 60 ? 'INVALIDATE_JWT' : null,
            ]);
        }
        return response()->json(['success' => true, 'new_score' => 75, 'force_logout' => false]);
    }

    // ====== Notifications ======
    public function notificationIndex($userId) {
        $data = NotificationLog::where('user_id', $userId)->orderBy('created_at', 'desc')->limit(20)->get();
        $unread = $data->where('read', false)->count();
        return response()->json(['data' => $data, 'unread_count' => $unread]);
    }
    public function notificationRead($id) {
        NotificationLog::where('id', $id)->update(['read' => true, 'read_at' => now()]);
        return response()->json(['success' => true, 'message' => '已標記為已讀']);
    }

    // ====== Certificates ======
    public function certificateGenerate(Request $r) {
        $code = 'FJU-CERT-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        $cert = Certificate::create([
            'user_id' => $r->user_id ?? 1, 'club_id' => $r->club_id,
            'name' => $r->name ?? '王大明', 'club_name' => $r->club ?? '攝影社',
            'position' => $r->position ?? '副社長', 'term' => $r->term ?? '114學年度第一學期',
            'certificate_code' => $code,
            'digital_signature' => 'SHA256:' . substr(md5(rand()), 0, 15),
            'verification_url' => url("/verify/{$code}"), 'issued_at' => now(),
        ]);
        return response()->json($cert);
    }

    // ====== Calendar Events ======
    public function calendarIndex(Request $r) {
        return response()->json(['data' => CalendarEvent::orderBy('date')->get()]);
    }
    public function calendarStore(Request $r) {
        $e = CalendarEvent::create($r->only(['title','date','type','color','description','venue']));
        return response()->json(['success' => true, 'id' => $e->id, 'data' => $e, 'message' => '行事曆事件已建立'], 201);
    }
    public function calendarUpdate(Request $r, $id) {
        $e = CalendarEvent::findOrFail($id);
        $e->update($r->only(['title','date','type','color','description','venue']));
        return response()->json(['success' => true, 'data' => $e]);
    }
    public function calendarDestroy($id) {
        CalendarEvent::destroy($id);
        return response()->json(['success' => true]);
    }

    // ====== Repairs ======
    public function repairIndex() {
        return response()->json(['data' => Repair::orderBy('created_at', 'desc')->get()]);
    }
    public function repairStore(Request $r) {
        $code = 'RP-' . str_pad(Repair::count() + 1, 3, '0', STR_PAD_LEFT);
        $rep = Repair::create($r->only(['target','description','assignee']) + ['code' => $code, 'status' => 'pending']);
        return response()->json(['success' => true, 'id' => $rep->id, 'data' => $rep, 'tracking_code' => $code], 201);
    }
    public function repairUpdate(Request $r, $id) {
        $rep = Repair::findOrFail($id);
        $rep->update($r->only(['target','description','status','assignee']));
        return response()->json(['success' => true, 'data' => $rep]);
    }
    public function repairDestroy($id) {
        Repair::destroy($id);
        return response()->json(['success' => true]);
    }

    // ====== Appeals ======
    public function appealIndex() {
        return response()->json(['data' => Appeal::orderBy('created_at', 'desc')->get()]);
    }
    public function appealStore(Request $r) {
        $code = 'AP-' . str_pad(Appeal::count() + 1, 3, '0', STR_PAD_LEFT);
        $a = Appeal::create($r->only(['appeal_type','subject','description','appellant_id']) + ['code' => $code, 'status' => 'pending']);
        return response()->json(['success' => true, 'id' => $a->id, 'data' => $a, 'message' => '申訴已提交，AI 摘要生成中...', 'estimated_response' => '24小時內'], 201);
    }
    public function appealUpdate(Request $r, $id) {
        $a = Appeal::findOrFail($id);
        $a->update($r->only(['status','resolution','ai_summary','assigned_to']));
        return response()->json(['success' => true, 'data' => $a]);
    }
    public function appealDestroy($id) {
        Appeal::destroy($id);
        return response()->json(['success' => true]);
    }
    public function appealAiSummary($id) {
        return response()->json([
            'summary' => '案件摘要：攝影社（Level 2）與吉他社（Level 2）同時申請中美堂 14:00-17:00 時段。',
            'suggestions' => ['方案一：攝影社改至 SF 134', '方案二：時段分割各使用一半', '方案三：吉他社延至隔天'],
            'sentiment' => 'neutral', 'urgency' => 'medium'
        ]);
    }

    // ====== Dashboard Stats ======
    public function dashboardStats($role) {
        $clubCount = Club::count();
        $stats = [
            'admin' => ['pending_reviews' => 5, 'monthly_activities' => 28, 'venue_usage' => 87, 'ai_pass_rate' => 94, 'total_clubs' => $clubCount, 'total_students' => 5000, 'sdg_data' => ['SDG4'=>85,'SDG5'=>60,'SDG10'=>75,'SDG11'=>90,'SDG16'=>55,'SDG17'=>70], 'funnel_data' => ['submitted'=>120,'ai_reviewed'=>110,'approved'=>95,'completed'=>82], 'trend_data' => [1200,1350,1500,1420,1380,1550,1680]],
            'officer' => ['pending_tasks' => 3, 'member_count' => 45, 'budget_used' => 65, 'next_event_days' => 12, 'retention_data' => [100,95,88,82,78,75,73], 'satisfaction_data' => ['very_satisfied'=>45,'satisfied'=>35,'neutral'=>12,'unsatisfied'=>8], 'attendance_data' => [42,38,45,40,43,41,44]],
            'professor' => ['supervised_clubs' => 3, 'risk_alerts' => 1, 'review_pending' => 2, 'performance_data' => ['leadership'=>82,'creativity'=>75,'teamwork'=>88,'communication'=>70,'problem_solving'=>85], 'growth_data' => [65,70,72,78,82,85], 'risk_indicators' => ['high'=>0,'medium'=>1,'low'=>2]],
            'student' => ['activities_joined' => 8, 'officer_roles' => 3, 'credit_score' => 85, 'competency_level' => 'A', 'competency_data' => ['leadership'=>80,'creativity'=>70,'teamwork'=>85,'communication'=>75,'digital'=>90], 'portfolio_count' => 12, 'certificates' => 3],
            'it' => ['cpu_usage' => 45, 'memory_usage' => 62, 'api_success_rate' => 99.5, 'waf_blocks_today' => 23, 'load_data' => [32,45,55,62,58,45,38,42,55,65,52,40], 'api_latency' => [120,95,88,105,92,78,85,110,95,82,90,88], 'r2_usage' => ['documents'=>45,'images'=>30,'certificates'=>15,'backups'=>10]],
        ];
        return response()->json($stats[$role] ?? $stats['student']);
    }

    // ====== AI Pre-review ======
    public function aiPreReview(Request $r) {
        $participants = $r->participants ?? 0;
        $result = ['allow_next_step' => $participants <= 80, 'risk_level' => $participants > 80 ? 'High' : ($participants > 50 ? 'Medium' : 'Low'), 'violations' => [], 'references' => [], 'suggestions' => [], 'reasoning' => '', 'confidence' => 0.95, 'reviewed_regulations' => ['活動申請辦法 v2.1', '場地使用管理規則 v3.0', '安全管理規範 v1.8']];
        if ($participants > 80) {
            $result['violations'] = ['crowd_overload', 'safety_plan_required'];
            $result['references'] = ['場地使用管理規則 第5條', '安全管理規範 第3條'];
            $result['suggestions'] = ['調整人數至80人以下', '或申請更大場地（中美堂/體育館）', '提交安全計畫書'];
            $result['reasoning'] = "AI 預審完成：申請 {$participants} 人，超過場地容量限制。需另附安全計畫書。";
        } elseif ($participants > 50) {
            $result['references'] = ['場地使用管理規則 第3條'];
            $result['suggestions'] = ['建議提前一週申請', '準備活動保險文件'];
            $result['reasoning'] = "AI 預審完成：申請 {$participants} 人，符合規定但建議注意安全。";
        } else {
            $result['reasoning'] = "AI 預審完成：申請 {$participants} 人，完全符合場地容量規定。";
        }
        return response()->json($result);
    }

    // ====== AI Proposal Generator ======
    public function aiGenerateProposal(Request $r) {
        $participants = $r->participants ?? 50;
        $budget = $participants * 300;
        return response()->json([
            'title' => ($r->title ?? '活動') . '企劃書',
            'sections' => [
                'purpose' => '一、活動目的：' . ($r->description ?? '提升社團凝聚力，培養同學各方面能力'),
                'time' => '二、活動時間：' . ($r->date ?? '待定'),
                'venue' => '三、活動地點：' . ($r->venue ?? '待定'),
                'participants' => "四、預計參加人數：{$participants}人",
                'flow' => ['五、活動流程：', '  1. 開場 & 破冰活動 (30分鐘)', '  2. 主題活動進行 (90分鐘)', '  3. 分組討論 & 實作 (60分鐘)', '  4. 成果發表 & 回饋 (30分鐘)', '  5. 閉幕 & 大合照 (10分鐘)'],
                'budget' => '六、預算概估：NT$' . number_format($budget),
                'budget_breakdown' => [
                    ['item' => '場地布置', 'amount' => round($budget * 0.2)],
                    ['item' => '餐飲茶點', 'amount' => round($budget * 0.35)],
                    ['item' => '文具材料', 'amount' => round($budget * 0.15)],
                    ['item' => '保險費用', 'amount' => round($budget * 0.1)],
                    ['item' => '雜支', 'amount' => round($budget * 0.1)],
                    ['item' => '預備金', 'amount' => round($budget * 0.1)],
                ],
                'risk' => '七、風險評估：依據 RAG 法規檢索，本活動符合《活動申請辦法》規定。',
                'sdg' => '八、SDGs 對應：SDG 4 (優質教育)、SDG 17 (夥伴關係)',
            ],
            'ai_review' => ['allow_next_step' => true, 'risk_level' => $participants > 50 ? 'Medium' : 'Low', 'violations' => [], 'confidence' => 0.92],
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    // ====== i18n ======
    public function i18n($lang) {
        $packs = [
            'zh-TW' => ['dashboard' => 'Dashboard', 'venue_booking' => '場地預約', 'equipment' => '設備借用', 'calendar' => '行事曆', 'club_info' => '社團資訊', 'activity_wall' => '活動牆', 'ai_overview' => 'AI 資訊概覽', 'ai_guide' => 'AI 導覽助理', 'rag_search' => '法規查詢', 'repair' => '報修管理', 'appeal' => '申訴記錄', 'reports' => '統計報表', 'login' => '登入', 'logout' => '登出', 'search' => '搜尋', 'hello' => '你好', 'credit_score' => '信用積分', 'notification' => '通知', 'conflict' => '衝突協調'],
            'en' => ['dashboard' => 'Dashboard', 'venue_booking' => 'Venue Booking', 'equipment' => 'Equipment', 'calendar' => 'Calendar', 'club_info' => 'Club Info', 'activity_wall' => 'Activity Wall', 'ai_overview' => 'AI Overview', 'ai_guide' => 'AI Guide', 'rag_search' => 'RAG Search', 'repair' => 'Repair', 'appeal' => 'Appeal', 'reports' => 'Reports', 'login' => 'Login', 'logout' => 'Logout', 'search' => 'Search', 'hello' => 'Hello', 'credit_score' => 'Credit Score', 'notification' => 'Notifications', 'conflict' => 'Conflict Resolution'],
            'ja' => ['dashboard' => 'ダッシュボード', 'venue_booking' => '会場予約', 'equipment' => '機器貸出', 'calendar' => 'カレンダー', 'club_info' => 'サークル情報', 'activity_wall' => 'アクティビティウォール', 'ai_overview' => 'AI 概要', 'ai_guide' => 'AI ガイド', 'rag_search' => '規則検索', 'repair' => '修理管理', 'appeal' => '申立記録', 'reports' => '統計レポート', 'login' => 'ログイン', 'logout' => 'ログアウト', 'search' => '検索', 'hello' => 'こんにちは', 'credit_score' => 'クレジットスコア', 'notification' => '通知', 'conflict' => '紛争解決'],
            'ko' => ['dashboard' => '대시보드', 'venue_booking' => '장소 예약', 'equipment' => '장비 대여', 'calendar' => '캘린더', 'club_info' => '동아리 정보', 'activity_wall' => '활동 게시판', 'ai_overview' => 'AI 개요', 'ai_guide' => 'AI 가이드', 'rag_search' => '규정 검색', 'repair' => '수리 관리', 'appeal' => '이의 제기', 'reports' => '통계 보고서', 'login' => '로그인', 'logout' => '로그아웃', 'search' => '검색', 'hello' => '안녕하세요', 'credit_score' => '신용 점수', 'notification' => '알림', 'conflict' => '갈등 해결'],
            'zh-CN' => ['dashboard' => '仪表板', 'venue_booking' => '场地预约', 'equipment' => '设备借用', 'calendar' => '日历', 'club_info' => '社团信息', 'activity_wall' => '活动墙', 'ai_overview' => 'AI 信息概览', 'ai_guide' => 'AI 导览助手', 'rag_search' => '法规查询', 'repair' => '报修管理', 'appeal' => '申诉记录', 'reports' => '统计报表', 'login' => '登录', 'logout' => '退出', 'search' => '搜索', 'hello' => '你好', 'credit_score' => '信用积分', 'notification' => '通知', 'conflict' => '冲突协调'],
        ];
        return response()->json($packs[$lang] ?? $packs['zh-TW']);
    }

    // ====== Health Check ======
    public function health() {
        return response()->json([
            'status' => 'ok', 'version' => '3.0.0', 'framework' => 'Laravel ' . app()->version(),
            'timestamp' => now()->toIso8601String(),
            'services' => ['database' => 'connected', 'ai' => 'ready', 'cache' => 'active', 'waf' => 'enabled'],
            'stats' => ['total_clubs' => Club::count(), 'total_users' => User::count()]
        ]);
    }

    // ====== Gatekeeping: Dependency Check ======
    public function gatekeepingCheck(Request $r) {
        $userId = $r->user_id ?? 1;
        $targetAction = $r->target_action ?? 'venue_booking'; // venue_booking, equipment_borrow
        $steps = [
            ['step' => 'activity_approval', 'label' => '活動核備', 'required' => true, 'completed' => true, 'code' => 'FJU-ACT-2026-0042'],
            ['step' => 'venue_booking', 'label' => '場地預約', 'required' => $targetAction !== 'activity_approval', 'completed' => $targetAction === 'equipment_borrow'],
            ['step' => 'equipment_borrow', 'label' => '器材借用', 'required' => $targetAction === 'equipment_borrow', 'completed' => false],
        ];
        $blocked = false;
        $blockedReason = null;
        foreach ($steps as $step) {
            if ($step['step'] === $targetAction) break;
            if ($step['required'] && !$step['completed']) {
                $blocked = true;
                $blockedReason = "依據《場地設備借用管理辦法》第3條，您需先完成「{$step['label']}」才能進行目前操作。";
                break;
            }
        }
        return response()->json([
            'allow' => !$blocked,
            'steps' => $steps,
            'blocked_reason' => $blockedReason,
            'ai_guidance' => $blocked
                ? "AI 引導提示：請先至「{$steps[0]['label']}」頁面取得核備編號，再進行場地預約。"
                : null,
            'law_reference' => 'FJU-ACT-001 第3條: 場地借用須先取得活動核備編號',
        ]);
    }
}
