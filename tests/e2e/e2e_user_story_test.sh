#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════════════
# FJU Smart Hub — User Story E2E 測試
# 版本: 1.0.0
# 說明: 依據 SA 規格書 21 張資料表、63 條 API、五大角色撰寫
#       按 User Story 情境逐步驗證完整業務流程
# ═══════════════════════════════════════════════════════════════════
set +e  # Don't exit on error — we track failures ourselves

BASE="http://localhost:3000"
PASS=0; FAIL=0; WARN=0; ERRORS=()
TOTAL_STORIES=0

# ─── Helpers ───────────────────────────────────────────────────────
C_GREEN="\033[32m"; C_RED="\033[31m"; C_YELLOW="\033[33m"
C_CYAN="\033[36m"; C_BOLD="\033[1m"; C_RESET="\033[0m"

log_header() { echo -e "\n${C_BOLD}${C_CYAN}══════════════════════════════════════════════════${C_RESET}"; echo -e "${C_BOLD}${C_CYAN}  $1${C_RESET}"; echo -e "${C_BOLD}${C_CYAN}══════════════════════════════════════════════════${C_RESET}"; }
log_story()  { echo -e "\n${C_BOLD}📖 User Story: $1${C_RESET}"; ((TOTAL_STORIES++)); }
log_step()   { echo -e "  ${C_CYAN}▶ $1${C_RESET}"; }

assert_status() {
    local desc="$1" method="$2" url="$3" expected="$4"
    shift 4
    local actual
    actual=$(curl -s -o /dev/null -w "%{http_code}" -X "$method" "$url" "$@" 2>/dev/null)
    if [ "$actual" == "$expected" ]; then
        echo -e "    ${C_GREEN}✅ PASS${C_RESET} [$method $url] → $actual | $desc"
        ((PASS++))
    else
        echo -e "    ${C_RED}❌ FAIL${C_RESET} [$method $url] → $actual (expected $expected) | $desc"
        ((FAIL++))
        ERRORS+=("FAIL|$desc|$method $url|expected=$expected got=$actual")
    fi
}

assert_json_field() {
    local desc="$1" response="$2" field="$3" expected="$4"
    local actual
    actual=$(echo "$response" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d$field)" 2>/dev/null || echo "__MISSING__")
    if [ "$actual" == "$expected" ]; then
        echo -e "    ${C_GREEN}✅ PASS${C_RESET} field $field == '$expected' | $desc"
        ((PASS++))
    else
        echo -e "    ${C_RED}❌ FAIL${C_RESET} field $field == '$actual' (expected '$expected') | $desc"
        ((FAIL++))
        ERRORS+=("FAIL|$desc|field $field|expected=$expected got=$actual")
    fi
}

assert_json_exists() {
    local desc="$1" response="$2" field="$3"
    local actual
    actual=$(echo "$response" | python3 -c "import sys,json; d=json.load(sys.stdin); v=d$field; print('EXISTS' if v is not None else 'NONE')" 2>/dev/null || echo "__MISSING__")
    if [ "$actual" == "EXISTS" ]; then
        echo -e "    ${C_GREEN}✅ PASS${C_RESET} field $field exists | $desc"
        ((PASS++))
    else
        echo -e "    ${C_RED}❌ FAIL${C_RESET} field $field missing | $desc"
        ((FAIL++))
        ERRORS+=("FAIL|$desc|field $field|missing")
    fi
}

assert_json_gt() {
    local desc="$1" response="$2" field="$3" min="$4"
    local actual
    actual=$(echo "$response" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d$field)" 2>/dev/null || echo "0")
    if python3 -c "exit(0 if float('$actual') > float('$min') else 1)" 2>/dev/null || [ "$actual" -gt "$min" ] 2>/dev/null; then
        echo -e "    ${C_GREEN}✅ PASS${C_RESET} $field ($actual) > $min | $desc"
        ((PASS++))
    else
        echo -e "    ${C_RED}❌ FAIL${C_RESET} $field ($actual) not > $min | $desc"
        ((FAIL++))
        ERRORS+=("FAIL|$desc|$field=$actual|expected > $min")
    fi
}

assert_json_contains() {
    local desc="$1" response="$2" substring="$3"
    # Decode Unicode escapes for CJK character matching (separators without spaces to preserve grep patterns)
    local decoded
    decoded=$(echo "$response" | python3 -c "import sys,json; print(json.dumps(json.loads(sys.stdin.read()), ensure_ascii=False, separators=(',',':')))" 2>/dev/null || echo "$response")
    if echo "$decoded" | grep -q "$substring"; then
        echo -e "    ${C_GREEN}✅ PASS${C_RESET} response contains '$substring' | $desc"
        ((PASS++))
    else
        echo -e "    ${C_RED}❌ FAIL${C_RESET} response missing '$substring' | $desc"
        ((FAIL++))
        ERRORS+=("FAIL|$desc|missing substring|$substring")
    fi
}

assert_page_title() {
    local desc="$1" url="$2" expected_substr="$3"
    local body
    body=$(curl -s "$url" 2>/dev/null)
    if echo "$body" | grep -qi "$expected_substr"; then
        echo -e "    ${C_GREEN}✅ PASS${C_RESET} page contains '$expected_substr' | $desc"
        ((PASS++))
    else
        echo -e "    ${C_RED}❌ FAIL${C_RESET} page missing '$expected_substr' | $desc"
        ((FAIL++))
        ERRORS+=("FAIL|$desc|page $url|missing '$expected_substr'")
    fi
}

# ═══════════════════════════════════════════════════════════════════
# US-00  系統基礎健康檢查
# ═══════════════════════════════════════════════════════════════════
log_header "US-00  系統基礎設施驗證"

log_story "作為 IT 管理員，我希望確認系統所有服務正常運行"
log_step "T-00-01: Health endpoint 回傳 ok"
RESP=$(curl -s "$BASE/api/health")
assert_json_field "health status" "$RESP" "['status']" "ok"
assert_json_contains "framework 版本" "$RESP" "Laravel"
assert_json_field "DB connected" "$RESP" "['services']['database']" "connected"
assert_json_field "AI ready" "$RESP" "['services']['ai']" "ready"
assert_json_field "Cache active" "$RESP" "['services']['cache']" "active"
assert_json_field "WAF enabled" "$RESP" "['services']['waf']" "enabled"

log_step "T-00-02: 社團數 ≥ 100 (SA 規格: 102 社團)"
assert_json_gt "社團總數 > 100" "$RESP" "['stats']['total_clubs']" 100

log_step "T-00-03: 使用者數 ≥ 5 (SA 規格: 7 使用者, 5 角色)"
assert_json_gt "使用者總數 > 4" "$RESP" "['stats']['total_users']" 4

# ═══════════════════════════════════════════════════════════════════
# US-01  頁面路由完整性 (21 頁面)
# ═══════════════════════════════════════════════════════════════════
log_header "US-01  頁面路由完整性 (SA 規格 §4.1: 21 頁面)"

log_story "作為使用者，我希望所有頁面都能正常訪問"
PAGES=(
    "/|FJU Smart Hub|首頁 Landing"
    "/login|登入|登入頁"
    "/dashboard|Dashboard|儀表板"
    "/campus-map|校園分區地圖|互動校園地圖"
    "/module/venue-booking|場地預約|場地活化大數據"
    "/module/equipment|器材|器材盤點追蹤"
    "/module/calendar|行事曆|行事曆管理"
    "/module/club-info|社團|社團資訊"
    "/module/activity-wall|活動|動態活動牆"
    "/module/ai-overview|AI|AI 總覽"
    "/module/ai-guide|AI|AI 導覽"
    "/module/rag-search|RAG|RAG 法規搜尋"
    "/module/rag-rental|租借|RAG 租借"
    "/module/repair|報修|修繕追蹤"
    "/module/appeal|申訴|申訴管理"
    "/module/reports|報表|統計報表"
    "/module/e-portfolio|Portfolio|職能 E-Portfolio"
    "/module/certificate|證書|幹部證書"
    "/module/time-capsule|膠囊|時光膠囊"
    "/module/2fa|2FA|雙因子驗證"
)
PAGE_COUNT=0
for entry in "${PAGES[@]}"; do
    IFS='|' read -r path keyword desc <<< "$entry"
    assert_status "頁面 $desc ($path)" "GET" "$BASE$path" "200"
    assert_page_title "頁面內容含 '$keyword'" "$BASE$path" "$keyword"
    ((PAGE_COUNT++))
done
log_step "已驗證 $PAGE_COUNT 個頁面路由"

# ═══════════════════════════════════════════════════════════════════
# US-02  學生社團瀏覽流程
# ═══════════════════════════════════════════════════════════════════
log_header "US-02  學生社團瀏覽與篩選"

log_story "作為學生，我想瀏覽所有社團，並按類型/分類篩選"
log_step "T-02-01: GET /api/clubs 取得完整社團列表"
RESP=$(curl -s "$BASE/api/clubs")
assert_json_gt "社團數量 > 100" "$RESP" "['total']" 100
assert_json_exists "data 陣列存在" "$RESP" "['data']"

log_step "T-02-02: 依 type=club 篩選"
RESP=$(curl -s "$BASE/api/clubs?type=club")
assert_json_contains "回傳含 club" "$RESP" "\"type\":\"club\""

log_step "T-02-03: 依 type=association 篩選"
RESP=$(curl -s "$BASE/api/clubs?type=association")
assert_json_contains "回傳含 association" "$RESP" "\"type\":\"association\""

log_step "T-02-04: 關鍵字搜尋"
RESP=$(curl -s "$BASE/api/clubs?search=%E6%94%9D%E5%BD%B1")
assert_json_contains "搜尋攝影社" "$RESP" "攝影"

log_step "T-02-05: 社團統計 API"
RESP=$(curl -s "$BASE/api/clubs/stats")
assert_json_exists "totalClubs 存在" "$RESP" "['totalClubs']"
assert_json_exists "totalAssociations 存在" "$RESP" "['totalAssociations']"
assert_json_exists "byCategory 存在" "$RESP" "['byCategory']"
assert_json_exists "byType 存在" "$RESP" "['byType']"

# ═══════════════════════════════════════════════════════════════════
# US-03  使用者 CRUD 完整生命週期
# ═══════════════════════════════════════════════════════════════════
log_header "US-03  使用者管理 CRUD（SA §3.4.1 users 表）"

log_story "作為管理員，我需要完整的使用者 CRUD 管理"
log_step "T-03-01: 建立新使用者"
RESP=$(curl -s -X POST "$BASE/api/users" \
    -H "Content-Type: application/json" \
    -d '{"student_id":"E2E001","name":"E2E測試生","email":"e2e@fju.edu.tw","phone":"0912-345-678","role":"student"}')
assert_json_field "建立成功" "$RESP" "['success']" "True"
assert_json_exists "回傳 id" "$RESP" "['id']"
USER_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "999")
echo -e "    ${C_CYAN}    → 新使用者 ID: $USER_ID${C_RESET}"

log_step "T-03-02: 查詢使用者"
RESP=$(curl -s "$BASE/api/users/$USER_ID")
assert_json_field "姓名正確" "$RESP" "['data']['name']" "E2E測試生"
assert_json_field "角色為 student" "$RESP" "['data']['role']" "student"
assert_json_field "信用積分預設 100" "$RESP" "['data']['credit_score']" "100"
assert_json_field "語言預設 zh-TW" "$RESP" "['data']['language']" "zh-TW"

log_step "T-03-03: 更新使用者"
RESP=$(curl -s -X PUT "$BASE/api/users/$USER_ID" \
    -H "Content-Type: application/json" \
    -d '{"name":"E2E更新生","phone":"0999-888-777","role":"officer"}')
assert_json_field "更新成功" "$RESP" "['success']" "True"
assert_json_field "姓名已更新" "$RESP" "['data']['name']" "E2E更新生"
assert_json_field "角色已更新" "$RESP" "['data']['role']" "officer"

log_step "T-03-04: 驗證五大角色 (SA §1.3)"
for ROLE in admin officer professor student it; do
    RESP=$(curl -s -X POST "$BASE/api/users" \
        -H "Content-Type: application/json" \
        -d "{\"student_id\":\"ROLE_$ROLE\",\"name\":\"Role $ROLE\",\"email\":\"$ROLE@fju.edu.tw\",\"role\":\"$ROLE\"}")
    assert_json_field "角色 $ROLE 建立成功" "$RESP" "['success']" "True"
    ROLE_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")
    # Clean up
    curl -s -X DELETE "$BASE/api/users/$ROLE_ID" > /dev/null 2>&1
done

log_step "T-03-05: 刪除使用者"
assert_status "刪除使用者" "DELETE" "$BASE/api/users/$USER_ID" "200"

log_step "T-03-06: 確認已刪除"
assert_status "已刪除的使用者回傳 404" "GET" "$BASE/api/users/$USER_ID" "404"

# ═══════════════════════════════════════════════════════════════════
# US-04  場地預約三階段完整流程
# ═══════════════════════════════════════════════════════════════════
log_header "US-04  場地預約三階段調度（SA §5.2）"

log_story "作為社團幹部，我需要預約場地，系統需支援三階段調度"
log_step "T-04-01: 瀏覽場地列表"
RESP=$(curl -s "$BASE/api/venues")
assert_json_exists "場地 data 存在" "$RESP" "['data']"

log_step "T-04-02: 查看場地時段表"
RESP=$(curl -s "$BASE/api/venues/1/schedule?date=2026-06-01")
assert_json_exists "slots 陣列存在" "$RESP" "['slots']"
assert_json_contains "時段含 available" "$RESP" "available"

E2E_DATE_1="2099-01-$(printf '%02d' $((RANDOM % 28 + 1)))"
E2E_DATE_2="2099-02-$(printf '%02d' $((RANDOM % 28 + 1)))"
log_step "T-04-03: 第一階段 — Level 1 校方優先直接通過"
RESP=$(curl -s -X POST "$BASE/api/reservations" \
    -H "Content-Type: application/json" \
    -d "{\"user_id\":1,\"venue_id\":1,\"user_name\":\"校方\",\"club_name\":\"校方活動\",\"venue_name\":\"中美堂\",\"reservation_date\":\"$E2E_DATE_1\",\"start_time\":\"09:00\",\"end_time\":\"11:00\",\"priority_level\":1,\"purpose\":\"校慶\"}")
assert_json_field "Level 1 成功" "$RESP" "['success']" "True"
assert_json_field "直接 approved" "$RESP" "['stage']" "approved"
assert_json_contains "校方優先訊息" "$RESP" "校方優先"
RES_ID_1=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-04-04: 第一階段 — Level 3 一般預約進入 algorithm"
RESP=$(curl -s -X POST "$BASE/api/reservations" \
    -H "Content-Type: application/json" \
    -d "{\"user_id\":2,\"venue_id\":2,\"user_name\":\"王小明\",\"club_name\":\"攝影社\",\"venue_name\":\"文華樓\",\"reservation_date\":\"$E2E_DATE_2\",\"start_time\":\"14:00\",\"end_time\":\"16:00\",\"priority_level\":3,\"purpose\":\"社團活動\"}")
assert_json_field "Level 3 成功" "$RESP" "['success']" "True"
assert_json_field "進入 algorithm" "$RESP" "['stage']" "algorithm"
assert_json_contains "等待審核訊息" "$RESP" "等待審核"
RES_ID_2=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-04-05: 第二階段 — 衝突偵測 (同場地同時段)"
RESP=$(curl -s -X POST "$BASE/api/reservations" \
    -H "Content-Type: application/json" \
    -d "{\"user_id\":3,\"venue_id\":2,\"user_name\":\"李小華\",\"club_name\":\"吉他社\",\"venue_name\":\"文華樓\",\"reservation_date\":\"$E2E_DATE_2\",\"start_time\":\"14:00\",\"end_time\":\"16:00\",\"priority_level\":2,\"purpose\":\"練團\"}")
assert_json_field "偵測衝突" "$RESP" "['success']" "False"
assert_json_field "進入 negotiation" "$RESP" "['stage']" "negotiation"
assert_json_exists "衝突資訊存在" "$RESP" "['conflict']"
assert_json_contains "衝突含計時器" "$RESP" "negotiation_timer"

log_step "T-04-06: 第二階段 — AI 協商建議"
RESP=$(curl -s -X POST "$BASE/api/reservations/$RES_ID_2/negotiate")
assert_json_exists "suggestions 存在" "$RESP" "['suggestions']"
assert_json_contains "含信心分數" "$RESP" "confidence"
assert_json_contains "含 AI 推理" "$RESP" "ai_reasoning"
assert_json_contains "含超時警告" "$RESP" "timeout_warning"

log_step "T-04-07: 第二階段 → 第三階段 — 接受方案進入 approval"
RESP=$(curl -s -X POST "$BASE/api/reservations/$RES_ID_2/accept-suggestion")
assert_json_field "接受成功" "$RESP" "['success']" "True"
assert_json_field "進入 approval" "$RESP" "['stage']" "approval"
assert_json_contains "RAG 法規比對" "$RESP" "RAG"

log_step "T-04-08: 預約 CRUD — 更新"
RESP=$(curl -s -X PUT "$BASE/api/reservations/$RES_ID_2" \
    -H "Content-Type: application/json" \
    -d '{"status":"confirmed","stage":"approved"}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

log_step "T-04-09: 預約 CRUD — 刪除"
assert_status "刪除預約 1" "DELETE" "$BASE/api/reservations/$RES_ID_1" "200"
assert_status "刪除預約 2" "DELETE" "$BASE/api/reservations/$RES_ID_2" "200"

# ═══════════════════════════════════════════════════════════════════
# US-05  器材借還完整流程
# ═══════════════════════════════════════════════════════════════════
log_header "US-05  器材借還流程（SA §6.4 器材盤點追蹤）"

log_story "作為學生，我需要借用器材並在期限內歸還"
log_step "T-05-01: 瀏覽器材列表（附借用者資訊）"
RESP=$(curl -s "$BASE/api/equipment")
assert_json_exists "器材 data" "$RESP" "['data']"
assert_json_gt "器材總數 > 0" "$RESP" "['total']" 0

log_step "T-05-02: 新增器材"
RESP=$(curl -s -X POST "$BASE/api/equipment" \
    -H "Content-Type: application/json" \
    -d '{"code":"E2E-001","name":"E2E測試相機","category":"攝影","status":"available","condition_note":"全新"}')
assert_json_field "建立成功" "$RESP" "['success']" "True"
EQ_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-05-03: 借用器材"
RESP=$(curl -s -X POST "$BASE/api/equipment/borrow" \
    -H "Content-Type: application/json" \
    -d "{\"equipment_id\":$EQ_ID,\"borrower_id\":1,\"borrower_name\":\"E2E測試生\"}")
assert_json_field "借用成功" "$RESP" "['success']" "True"
assert_json_exists "歸還日期" "$RESP" "['return_date']"
assert_json_contains "LINE 通知" "$RESP" "LINE"

log_step "T-05-04: 確認器材狀態改為 borrowed"
RESP=$(curl -s "$BASE/api/equipment/$EQ_ID")
assert_json_field "狀態已改 borrowed" "$RESP" "['data']['status']" "borrowed"

log_step "T-05-05: 歸還器材"
RESP=$(curl -s -X POST "$BASE/api/equipment/return" \
    -H "Content-Type: application/json" \
    -d "{\"equipment_id\":$EQ_ID,\"condition\":\"良好\"}")
assert_json_field "歸還成功" "$RESP" "['success']" "True"
assert_json_contains "信用積分 +5" "$RESP" "+5"
assert_json_exists "credit_change" "$RESP" "['credit_change']"

log_step "T-05-06: 確認器材狀態恢復 available"
RESP=$(curl -s "$BASE/api/equipment/$EQ_ID")
assert_json_field "狀態恢復 available" "$RESP" "['data']['status']" "available"

log_step "T-05-07: 發送到期提醒"
RESP=$(curl -s -X POST "$BASE/api/equipment/remind" \
    -H "Content-Type: application/json" -d '{}')
assert_json_field "提醒成功" "$RESP" "['success']" "True"
assert_json_contains "LINE Notify" "$RESP" "LINE"

log_step "T-05-08: 刪除測試器材"
assert_status "刪除器材" "DELETE" "$BASE/api/equipment/$EQ_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-06  活動管理＋報名完整流程
# ═══════════════════════════════════════════════════════════════════
log_header "US-06  活動管理與報名（SA §6.8 動態活動牆）"

log_story "作為社團幹部，我要建立活動；作為學生，我要報名活動"
log_step "T-06-01: 建立活動"
RESP=$(curl -s -X POST "$BASE/api/activities" \
    -H "Content-Type: application/json" \
    -d '{"title":"E2E測試活動","description":"E2E 自動化測試用活動","club_id":1,"venue_id":1,"organizer_name":"E2E","event_date":"2026-08-01","start_time":"10:00","end_time":"12:00","max_participants":50,"category":"學術"}')
assert_json_field "建立成功" "$RESP" "['success']" "True"
assert_json_contains "AI 預審狀態" "$RESP" "ai_review"
ACT_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-06-02: 活動預設狀態為 pending"
RESP=$(curl -s "$BASE/api/activities/$ACT_ID")
assert_json_field "狀態為 pending" "$RESP" "['data']['status']" "pending"
assert_json_field "current_participants 為 0" "$RESP" "['data']['current_participants']" "0"

log_step "T-06-03: 學生報名活動"
RESP=$(curl -s -X POST "$BASE/api/activities/$ACT_ID/register" \
    -H "Content-Type: application/json" -d '{"user_id":1}')
assert_json_field "報名成功" "$RESP" "['success']" "True"
assert_json_contains "報名成功訊息" "$RESP" "報名成功"

log_step "T-06-04: 確認 current_participants 增加"
RESP=$(curl -s "$BASE/api/activities/$ACT_ID")
assert_json_field "參與者 +1" "$RESP" "['data']['current_participants']" "1"

log_step "T-06-05: 取消報名"
RESP=$(curl -s -X POST "$BASE/api/activities/$ACT_ID/cancel-registration" \
    -H "Content-Type: application/json" -d '{"user_id":1}')
assert_json_field "取消成功" "$RESP" "['success']" "True"

log_step "T-06-06: 確認 current_participants 減少"
RESP=$(curl -s "$BASE/api/activities/$ACT_ID")
assert_json_field "參與者 回到 0" "$RESP" "['data']['current_participants']" "0"

log_step "T-06-07: 活動篩選 — 依類別"
RESP=$(curl -s "$BASE/api/activities?category=%E5%AD%B8%E8%A1%93")
assert_json_contains "篩選含學術" "$RESP" "學術"

log_step "T-06-08: 活動更新 & 刪除"
RESP=$(curl -s -X PUT "$BASE/api/activities/$ACT_ID" \
    -H "Content-Type: application/json" -d '{"status":"approved"}')
assert_json_field "審核通過" "$RESP" "['success']" "True"
assert_status "刪除活動" "DELETE" "$BASE/api/activities/$ACT_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-07  AI 智慧預審（SA §6.5 RAG）
# ═══════════════════════════════════════════════════════════════════
log_header "US-07  AI 智慧預審（SA §5.3 + §6.5）"

log_story "作為課指組幹事，我要使用 AI 預審活動申請"
log_step "T-07-01: Low 風險 (≤50 人)"
RESP=$(curl -s -X POST "$BASE/api/ai/pre-review" \
    -H "Content-Type: application/json" -d '{"participants":30}')
assert_json_field "允許下一步" "$RESP" "['allow_next_step']" "True"
assert_json_field "Low 風險" "$RESP" "['risk_level']" "Low"
assert_json_exists "信心指數" "$RESP" "['confidence']"
assert_json_exists "法規參照" "$RESP" "['reviewed_regulations']"

log_step "T-07-02: Medium 風險 (51-80 人)"
RESP=$(curl -s -X POST "$BASE/api/ai/pre-review" \
    -H "Content-Type: application/json" -d '{"participants":65}')
assert_json_field "允許下一步" "$RESP" "['allow_next_step']" "True"
assert_json_field "Medium 風險" "$RESP" "['risk_level']" "Medium"

log_step "T-07-03: High 風險 (>80 人)"
RESP=$(curl -s -X POST "$BASE/api/ai/pre-review" \
    -H "Content-Type: application/json" -d '{"participants":120}')
assert_json_field "不允許下一步" "$RESP" "['allow_next_step']" "False"
assert_json_field "High 風險" "$RESP" "['risk_level']" "High"
assert_json_exists "violations 存在" "$RESP" "['violations']"
assert_json_contains "含建議" "$RESP" "suggestions"
assert_json_contains "含法規參照" "$RESP" "references"

# ═══════════════════════════════════════════════════════════════════
# US-08  AI 企劃生成器（SA §6.2）
# ═══════════════════════════════════════════════════════════════════
log_header "US-08  AI 企劃生成器（SA §6.2）"

log_story "作為社團幹部，我要用 AI 自動生成活動企劃書"
RESP=$(curl -s -X POST "$BASE/api/ai/generate-proposal" \
    -H "Content-Type: application/json" \
    -d '{"title":"社團成果展","description":"年度社團成果展覽","date":"2026-06-15","venue":"中美堂","participants":80}')
assert_json_contains "標題含企劃書" "$RESP" "企劃書"
assert_json_exists "sections 存在" "$RESP" "['sections']"
assert_json_contains "含預算" "$RESP" "budget"
assert_json_contains "含風險評估" "$RESP" "risk"
assert_json_contains "含 SDGs" "$RESP" "SDG"
assert_json_exists "AI review 結果" "$RESP" "['ai_review']"
assert_json_contains "含預算明細" "$RESP" "budget_breakdown"

# ═══════════════════════════════════════════════════════════════════
# US-09  信用積分制度（SA §8.2）
# ═══════════════════════════════════════════════════════════════════
log_header "US-09  信用積分制度（SA §8.2 信用積分儀表板）"

log_story "作為系統，信用積分需正確追蹤加扣並在 <60 分時強制登出"
log_step "T-09-01: 查詢積分"
RESP=$(curl -s "$BASE/api/credits/1")
assert_json_exists "score 存在" "$RESP" "['score']"
assert_json_exists "status 存在" "$RESP" "['status']"
assert_json_field "threshold 為 60" "$RESP" "['threshold']" "60"

log_step "T-09-02: 扣除積分"
RESP=$(curl -s -X POST "$BASE/api/credits/deduct" \
    -H "Content-Type: application/json" \
    -d '{"user_id":1,"points":5,"reason":"E2E測試扣分"}')
assert_json_field "扣分成功" "$RESP" "['success']" "True"
assert_json_exists "new_score" "$RESP" "['new_score']"

log_step "T-09-03: 驗證 force_logout 欄位存在 (低分觸發 JWT 失效)"
assert_json_exists "force_logout 欄位" "$RESP" "['force_logout']"

# ═══════════════════════════════════════════════════════════════════
# US-10  通知管理（SA §3.4.12）
# ═══════════════════════════════════════════════════════════════════
log_header "US-10  通知管理（四管道: system/email/line/sms）"

log_story "作為使用者，我希望收到系統通知並能標記已讀"
RESP=$(curl -s "$BASE/api/notifications/1")
assert_json_exists "通知 data" "$RESP" "['data']"
assert_json_exists "unread_count" "$RESP" "['unread_count']"

# ═══════════════════════════════════════════════════════════════════
# US-11  幹部證書自動化（SA §6.3）
# ═══════════════════════════════════════════════════════════════════
log_header "US-11  幹部證書自動化（SA §6.3）"

log_story "作為課指組幹事，我要為社團幹部產生數位證書"
RESP=$(curl -s -X POST "$BASE/api/certificates/generate" \
    -H "Content-Type: application/json" \
    -d '{"user_id":1,"club_id":1,"name":"E2E證書測試","club":"攝影社","position":"社長","term":"114學年度"}')
assert_json_contains "含 FJU-CERT" "$RESP" "FJU-CERT"
assert_json_exists "certificate_code" "$RESP" "['certificate_code']"
assert_json_exists "digital_signature" "$RESP" "['digital_signature']"
assert_json_exists "verification_url" "$RESP" "['verification_url']"

# ═══════════════════════════════════════════════════════════════════
# US-12  行事曆 CRUD（SA §3.4.19）
# ═══════════════════════════════════════════════════════════════════
log_header "US-12  行事曆管理 CRUD"

log_story "作為管理員，我要管理校園行事曆事件"
log_step "T-12-01: 建立事件"
RESP=$(curl -s -X POST "$BASE/api/calendar/events" \
    -H "Content-Type: application/json" \
    -d '{"title":"E2E測試事件","date":"2026-09-01","type":"event","color":"#FF5733","description":"自動化測試","venue":"中美堂"}')
assert_json_field "建立成功" "$RESP" "['success']" "True"
CAL_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-12-02: 查詢事件"
RESP=$(curl -s "$BASE/api/calendar/events")
assert_json_exists "事件列表" "$RESP" "['data']"

log_step "T-12-03: 更新事件"
RESP=$(curl -s -X PUT "$BASE/api/calendar/events/$CAL_ID" \
    -H "Content-Type: application/json" \
    -d '{"title":"E2E更新事件","color":"#003153"}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

log_step "T-12-04: 刪除事件"
assert_status "刪除事件" "DELETE" "$BASE/api/calendar/events/$CAL_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-13  修繕追蹤 CRUD（SA §3.4.18）
# ═══════════════════════════════════════════════════════════════════
log_header "US-13  修繕追蹤 CRUD（SA §3.4.18 repairs）"

log_story "作為使用者，我要報修設備並追蹤維修進度"
log_step "T-13-01: 提交報修"
RESP=$(curl -s -X POST "$BASE/api/repairs" \
    -H "Content-Type: application/json" \
    -d '{"target":"E2E測試投影機","description":"無法開機","assignee":"IT部門"}')
assert_json_field "報修成功" "$RESP" "['success']" "True"
assert_json_contains "含追蹤碼 RP-" "$RESP" "RP-"
REP_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-13-02: 更新修繕狀態 pending → processing"
RESP=$(curl -s -X PUT "$BASE/api/repairs/$REP_ID" \
    -H "Content-Type: application/json" -d '{"status":"processing"}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

log_step "T-13-03: 更新修繕狀態 processing → in_progress"
RESP=$(curl -s -X PUT "$BASE/api/repairs/$REP_ID" \
    -H "Content-Type: application/json" -d '{"status":"in_progress"}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

log_step "T-13-04: 更新修繕狀態 in_progress → completed"
RESP=$(curl -s -X PUT "$BASE/api/repairs/$REP_ID" \
    -H "Content-Type: application/json" -d '{"status":"completed"}')
assert_json_field "完工成功" "$RESP" "['success']" "True"

log_step "T-13-05: 刪除修繕"
assert_status "刪除修繕" "DELETE" "$BASE/api/repairs/$REP_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-14  申訴管理 + AI 摘要（SA §6.7）
# ═══════════════════════════════════════════════════════════════════
log_header "US-14  申訴管理與 AI 摘要（SA §6.7）"

log_story "作為學生，我要提交申訴；系統需 AI 自動生成摘要"
log_step "T-14-01: 提交申訴"
RESP=$(curl -s -X POST "$BASE/api/appeals" \
    -H "Content-Type: application/json" \
    -d '{"appeal_type":"venue","subject":"E2E場地衝突申訴","description":"我社場地被佔用","appellant_id":1}')
assert_json_field "提交成功" "$RESP" "['success']" "True"
assert_json_contains "含編碼 AP-" "$RESP" "AP-"
assert_json_contains "預估回應時間" "$RESP" "24"
APL_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-14-02: AI 摘要生成"
RESP=$(curl -s -X POST "$BASE/api/appeals/$APL_ID/ai-summary")
assert_json_exists "summary 存在" "$RESP" "['summary']"
assert_json_exists "suggestions 存在" "$RESP" "['suggestions']"
assert_json_exists "sentiment 存在" "$RESP" "['sentiment']"
assert_json_exists "urgency 存在" "$RESP" "['urgency']"

log_step "T-14-03: 更新申訴狀態"
RESP=$(curl -s -X PUT "$BASE/api/appeals/$APL_ID" \
    -H "Content-Type: application/json" \
    -d '{"status":"processing","assigned_to":2}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

log_step "T-14-04: 結案"
RESP=$(curl -s -X PUT "$BASE/api/appeals/$APL_ID" \
    -H "Content-Type: application/json" \
    -d '{"status":"resolved","resolution":"已協調場地更換"}')
assert_json_field "結案成功" "$RESP" "['success']" "True"

log_step "T-14-05: 刪除申訴"
assert_status "刪除申訴" "DELETE" "$BASE/api/appeals/$APL_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-15  衝突協商完整流程（SA §5.2 三階段）
# ═══════════════════════════════════════════════════════════════════
log_header "US-15  衝突協商計時器機制（SA §5.2）"

log_story "作為系統，衝突協商需在 3/6 分鐘觸發 AI 介入/強制關閉"
log_step "T-15-01: < 3 分鐘 — 正常建議"
RESP=$(curl -s -X POST "$BASE/api/conflicts/negotiate" \
    -H "Content-Type: application/json" \
    -d '{"elapsed_minutes":1,"party_a_name":"攝影社","party_b_name":"吉他社"}')
assert_json_exists "suggestions 存在" "$RESP" "['suggestions']"
RESP_STR=$(echo "$RESP")
if echo "$RESP_STR" | grep -q "forced_close"; then
    echo -e "    ${C_RED}❌ FAIL${C_RESET} < 3 min 不應有 forced_close"
    ((FAIL++))
    ERRORS+=("FAIL|< 3min 不應有 forced_close|conflicts/negotiate|elapsed=1")
else
    echo -e "    ${C_GREEN}✅ PASS${C_RESET} < 3 min 無 forced_close"
    ((PASS++))
fi

log_step "T-15-02: ≥ 3 分鐘 — AI 自動介入"
RESP=$(curl -s -X POST "$BASE/api/conflicts/negotiate" \
    -H "Content-Type: application/json" \
    -d '{"elapsed_minutes":4,"party_a_name":"攝影社","party_b_name":"吉他社"}')
assert_json_contains "AI 介入" "$RESP" "ai_intervention"
assert_json_contains "超時警告" "$RESP" "timeout_warning"

log_step "T-15-03: ≥ 6 分鐘 — 強制關閉 + 扣雙方各 10 分"
RESP=$(curl -s -X POST "$BASE/api/conflicts/negotiate" \
    -H "Content-Type: application/json" \
    -d '{"elapsed_minutes":7,"party_a_name":"攝影社","party_b_name":"吉他社"}')
assert_json_contains "強制關閉" "$RESP" "forced_close"
assert_json_contains "扣 10 分" "$RESP" "10"

# ═══════════════════════════════════════════════════════════════════
# US-16  Dashboard 五大角色統計（SA §1.3）
# ═══════════════════════════════════════════════════════════════════
log_header "US-16  Dashboard 五大角色統計"

log_story "每個角色需要看到專屬的 Dashboard 統計資料"
for ROLE in admin officer professor student it; do
    log_step "T-16: 角色 $ROLE Dashboard"
    RESP=$(curl -s "$BASE/api/dashboard/stats/$ROLE")
    case $ROLE in
        admin)
            assert_json_exists "pending_reviews" "$RESP" "['pending_reviews']"
            assert_json_exists "total_clubs" "$RESP" "['total_clubs']"
            assert_json_exists "sdg_data" "$RESP" "['sdg_data']"
            assert_json_exists "funnel_data" "$RESP" "['funnel_data']"
            ;;
        officer)
            assert_json_exists "pending_tasks" "$RESP" "['pending_tasks']"
            assert_json_exists "member_count" "$RESP" "['member_count']"
            assert_json_exists "retention_data" "$RESP" "['retention_data']"
            ;;
        professor)
            assert_json_exists "supervised_clubs" "$RESP" "['supervised_clubs']"
            assert_json_exists "risk_alerts" "$RESP" "['risk_alerts']"
            assert_json_exists "performance_data" "$RESP" "['performance_data']"
            ;;
        student)
            assert_json_exists "credit_score" "$RESP" "['credit_score']"
            assert_json_exists "competency_data" "$RESP" "['competency_data']"
            assert_json_exists "portfolio_count" "$RESP" "['portfolio_count']"
            ;;
        it)
            assert_json_exists "cpu_usage" "$RESP" "['cpu_usage']"
            assert_json_exists "api_success_rate" "$RESP" "['api_success_rate']"
            assert_json_exists "waf_blocks_today" "$RESP" "['waf_blocks_today']"
            assert_json_exists "r2_usage" "$RESP" "['r2_usage']"
            ;;
    esac
done

# ═══════════════════════════════════════════════════════════════════
# US-17  多語系 i18n（SA: 5 國語系）
# ═══════════════════════════════════════════════════════════════════
log_header "US-17  多語系 i18n（5 國語系）"

log_story "作為使用者，我希望切換語系後 UI 文字相應改變"
for LANG in zh-TW en ja ko zh-CN; do
    RESP=$(curl -s "$BASE/api/i18n/$LANG")
    assert_json_exists "$LANG dashboard" "$RESP" "['dashboard']"
    assert_json_exists "$LANG venue_booking" "$RESP" "['venue_booking']"
    assert_json_exists "$LANG credit_score" "$RESP" "['credit_score']"
done

# ═══════════════════════════════════════════════════════════════════
# US-18  場地 CRUD + 時段查詢
# ═══════════════════════════════════════════════════════════════════
log_header "US-18  場地管理 CRUD + 時段查詢"

log_story "作為管理員，我需要管理場地並查看排程"
log_step "T-18-01: 建立場地"
RESP=$(curl -s -X POST "$BASE/api/venues" \
    -H "Content-Type: application/json" \
    -d '{"name":"E2E測試場地","location":"測試樓101","capacity":100,"status":"available","latitude":25.0360,"longitude":121.4320}')
assert_json_field "建立成功" "$RESP" "['success']" "True"
VEN_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

log_step "T-18-02: 查詢場地"
RESP=$(curl -s "$BASE/api/venues/$VEN_ID")
assert_json_field "名稱正確" "$RESP" "['data']['name']" "E2E測試場地"
assert_json_field "容量正確" "$RESP" "['data']['capacity']" "100"

log_step "T-18-03: 場地時段表 (14 時段 08:00-22:00)"
RESP=$(curl -s "$BASE/api/venues/$VEN_ID/schedule?date=2026-10-01")
SLOT_COUNT=$(echo "$RESP" | python3 -c "import sys,json; print(len(json.load(sys.stdin)['slots']))" 2>/dev/null || echo "0")
if [ "$SLOT_COUNT" -eq 14 ]; then
    echo -e "    ${C_GREEN}✅ PASS${C_RESET} 時段數量 = 14 | 08:00-22:00"
    ((PASS++))
else
    echo -e "    ${C_RED}❌ FAIL${C_RESET} 時段數量 = $SLOT_COUNT (expected 14)"
    ((FAIL++))
    ERRORS+=("FAIL|時段數量|schedule|expected=14 got=$SLOT_COUNT")
fi

log_step "T-18-04: 更新場地"
RESP=$(curl -s -X PUT "$BASE/api/venues/$VEN_ID" \
    -H "Content-Type: application/json" -d '{"capacity":200}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

log_step "T-18-05: 刪除場地"
assert_status "刪除場地" "DELETE" "$BASE/api/venues/$VEN_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-19  社團 CRUD
# ═══════════════════════════════════════════════════════════════════
log_header "US-19  社團管理 CRUD（SA §3.4.2）"

log_story "作為管理員，我需要完整管理社團資料"
RESP=$(curl -s -X POST "$BASE/api/clubs" \
    -H "Content-Type: application/json" \
    -d '{"name":"E2E測試社","category":"academic","category_label":"學術","type":"club","description":"E2E 自動化測試社團","member_count":25}')
assert_json_field "建立成功" "$RESP" "['success']" "True"
CLUB_ID=$(echo "$RESP" | python3 -c "import sys,json; print(json.load(sys.stdin)['id'])" 2>/dev/null || echo "0")

RESP=$(curl -s -X PUT "$BASE/api/clubs/$CLUB_ID" \
    -H "Content-Type: application/json" -d '{"member_count":30}')
assert_json_field "更新成功" "$RESP" "['success']" "True"

assert_status "刪除社團" "DELETE" "$BASE/api/clubs/$CLUB_ID" "200"

# ═══════════════════════════════════════════════════════════════════
# US-20  跨實體資料一致性驗證
# ═══════════════════════════════════════════════════════════════════
log_header "US-20  跨實體資料一致性驗證"

log_story "系統資料需跨表一致，不能有孤兒記錄"
log_step "T-20-01: 驗證 users 列表結構 (SA §3.4.1)"
RESP=$(curl -s "$BASE/api/users")
FIRST_USER=$(echo "$RESP" | python3 -c "
import sys,json
d = json.load(sys.stdin)['data']
u = d[0] if d else {}
fields = ['id','student_id','name','email','role','credit_score','language','is_active','created_at','updated_at']
missing = [f for f in fields if f not in u]
print('OK' if not missing else 'MISSING:' + ','.join(missing))
" 2>/dev/null || echo "ERROR")
if [ "$FIRST_USER" == "OK" ]; then
    echo -e "    ${C_GREEN}✅ PASS${C_RESET} users 包含所有必要欄位"
    ((PASS++))
else
    echo -e "    ${C_RED}❌ FAIL${C_RESET} users 缺少欄位: $FIRST_USER"
    ((FAIL++))
    ERRORS+=("FAIL|users 欄位不完整|api/users|$FIRST_USER")
fi

log_step "T-20-02: 驗證 clubs 列表結構 (SA §3.4.2)"
RESP=$(curl -s "$BASE/api/clubs")
FIRST_CLUB=$(echo "$RESP" | python3 -c "
import sys,json
d = json.load(sys.stdin)['data']
c = d[0] if d else {}
fields = ['id','name','category','type','description','member_count','is_active']
missing = [f for f in fields if f not in c]
print('OK' if not missing else 'MISSING:' + ','.join(missing))
" 2>/dev/null || echo "ERROR")
if [ "$FIRST_CLUB" == "OK" ]; then
    echo -e "    ${C_GREEN}✅ PASS${C_RESET} clubs 包含所有必要欄位"
    ((PASS++))
else
    echo -e "    ${C_RED}❌ FAIL${C_RESET} clubs 缺少欄位: $FIRST_CLUB"
    ((FAIL++))
    ERRORS+=("FAIL|clubs 欄位不完整|api/clubs|$FIRST_CLUB")
fi

log_step "T-20-03: 驗證 equipment 列表結構 (SA §3.4.7)"
RESP=$(curl -s "$BASE/api/equipment")
FIRST_EQ=$(echo "$RESP" | python3 -c "
import sys,json
d = json.load(sys.stdin)['data']
e = d[0] if d else {}
fields = ['id','code','name','category','status']
missing = [f for f in fields if f not in e]
print('OK' if not missing else 'MISSING:' + ','.join(missing))
" 2>/dev/null || echo "ERROR")
if [ "$FIRST_EQ" == "OK" ]; then
    echo -e "    ${C_GREEN}✅ PASS${C_RESET} equipment 包含所有必要欄位"
    ((PASS++))
else
    echo -e "    ${C_RED}❌ FAIL${C_RESET} equipment 缺少欄位: $FIRST_EQ"
    ((FAIL++))
    ERRORS+=("FAIL|equipment 欄位不完整|api/equipment|$FIRST_EQ")
fi

log_step "T-20-04: 驗證 SA 規格 users.role ENUM 值"
RESP=$(curl -s "$BASE/api/users")
ROLES_CHECK=$(echo "$RESP" | python3 -c "
import sys,json
d = json.load(sys.stdin)['data']
roles = set(u['role'] for u in d)
expected = {'admin','officer','professor','student','it'}
missing = expected - roles
extra = roles - expected
if missing:
    print('MISSING_ROLES:' + ','.join(missing))
elif extra:
    print('EXTRA_ROLES:' + ','.join(extra))
else:
    print('OK')
" 2>/dev/null || echo "ERROR")
if [[ "$ROLES_CHECK" == "OK" ]]; then
    echo -e "    ${C_GREEN}✅ PASS${C_RESET} 所有 5 種角色都存在"
    ((PASS++))
elif [[ "$ROLES_CHECK" == MISSING_ROLES* ]]; then
    echo -e "    ${C_YELLOW}⚠️ WARN${C_RESET} Seeder 缺少角色: $ROLES_CHECK (非程式錯誤)"
    ((WARN++))
    ERRORS+=("WARN|Seeder 未涵蓋所有角色|api/users|$ROLES_CHECK")
else
    echo -e "    ${C_RED}❌ FAIL${C_RESET} 角色驗證: $ROLES_CHECK"
    ((FAIL++))
    ERRORS+=("FAIL|角色驗證|api/users|$ROLES_CHECK")
fi

# ═══════════════════════════════════════════════════════════════════
# US-21  SA 規格書 vs 實際 API 回應結構比對
# ═══════════════════════════════════════════════════════════════════
log_header "US-21  SA 規格書 vs 實際 API 結構比對"

log_story "確認 API 回應結構與 SA 規格書定義一致"

log_step "T-21-01: reservations 回傳含 SA §3.4.9 所有狀態欄位"
RESP=$(curl -s "$BASE/api/reservations")
assert_json_exists "reservations data" "$RESP" "['data']"

log_step "T-21-02: conflicts 回傳結構"
RESP=$(curl -s "$BASE/api/conflicts")
assert_json_exists "conflicts data" "$RESP" "['data']"

log_step "T-21-03: repairs 回傳結構"
RESP=$(curl -s "$BASE/api/repairs")
assert_json_exists "repairs data" "$RESP" "['data']"

log_step "T-21-04: appeals 回傳結構"
RESP=$(curl -s "$BASE/api/appeals")
assert_json_exists "appeals data" "$RESP" "['data']"

log_step "T-21-05: venues schedule 含 venue_id + date + slots"
RESP=$(curl -s "$BASE/api/venues/1/schedule?date=2026-01-01")
assert_json_exists "venue_id" "$RESP" "['venue_id']"
assert_json_exists "date" "$RESP" "['date']"
assert_json_exists "slots" "$RESP" "['slots']"

# ═══════════════════════════════════════════════════════════════════
#  FINAL REPORT
# ═══════════════════════════════════════════════════════════════════
echo ""
echo -e "${C_BOLD}╔══════════════════════════════════════════════════════════════╗${C_RESET}"
echo -e "${C_BOLD}║              E2E USER STORY 測試總結報告                    ║${C_RESET}"
echo -e "${C_BOLD}╠══════════════════════════════════════════════════════════════╣${C_RESET}"
echo -e "${C_BOLD}║  User Stories 測試:  ${TOTAL_STORIES} 個                                    ║${C_RESET}"
echo -e "${C_BOLD}║  ${C_GREEN}✅ PASS:  ${PASS}${C_RESET}${C_BOLD}                                              ║${C_RESET}"
echo -e "${C_BOLD}║  ${C_RED}❌ FAIL:  ${FAIL}${C_RESET}${C_BOLD}                                               ║${C_RESET}"
echo -e "${C_BOLD}║  ${C_YELLOW}⚠️  WARN:  ${WARN}${C_RESET}${C_BOLD}                                              ║${C_RESET}"
TOTAL=$((PASS + FAIL))
if [ $TOTAL -gt 0 ]; then
    RATE=$(python3 -c "print(round($PASS * 100 / $TOTAL, 1))" 2>/dev/null || echo "0")
else
    RATE="0"
fi
echo -e "${C_BOLD}║  通過率:  ${RATE}%                                          ║${C_RESET}"
echo -e "${C_BOLD}╚══════════════════════════════════════════════════════════════╝${C_RESET}"

if [ ${#ERRORS[@]} -gt 0 ]; then
    echo ""
    echo -e "${C_BOLD}${C_RED}══════════ 錯誤明細 ══════════${C_RESET}"
    for err in "${ERRORS[@]}"; do
        IFS='|' read -r level desc endpoint detail <<< "$err"
        if [ "$level" == "FAIL" ]; then
            echo -e "  ${C_RED}❌ [FAIL]${C_RESET} $desc"
            echo -e "      端點: $endpoint"
            echo -e "      詳情: $detail"
        else
            echo -e "  ${C_YELLOW}⚠️ [WARN]${C_RESET} $desc"
            echo -e "      端點: $endpoint"
            echo -e "      詳情: $detail"
        fi
    done
fi

echo ""
# Exit with error if any failures
if [ $FAIL -gt 0 ]; then
    echo -e "${C_RED}E2E 測試發現 $FAIL 個失敗項目，需要修正。${C_RESET}"
    exit 1
else
    echo -e "${C_GREEN}🎉 所有 E2E 測試通過！${C_RESET}"
    exit 0
fi
