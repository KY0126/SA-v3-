#!/usr/bin/env python3
"""
FJU Smart Hub - 系統開發規格書 (SA Document) Generator
Based on 114 SA Template format with automatic Table of Contents
Covers: Front-end, Back-end, DB integration, all core modules
"""

from docx import Document
from docx.shared import Inches, Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml.ns import qn
from docx.oxml import OxmlElement
import datetime

BLUE = RGBColor(0, 49, 83)      # #003153
GOLD = RGBColor(218, 165, 32)   # #DAA520
GREEN = RGBColor(0, 128, 0)
RED = RGBColor(255, 0, 0)
GRAY = RGBColor(128, 128, 128)
WHITE = RGBColor(255, 255, 255)
BLACK = RGBColor(0, 0, 0)

def add_toc(doc):
    """Add a Table of Contents field that auto-updates in Word"""
    paragraph = doc.add_paragraph()
    run = paragraph.add_run()
    fldChar = OxmlElement('w:fldChar')
    fldChar.set(qn('w:fldCharType'), 'begin')
    run._r.append(fldChar)
    run2 = paragraph.add_run()
    instrText = OxmlElement('w:instrText')
    instrText.set(qn('xml:space'), 'preserve')
    instrText.text = ' TOC \\o "1-3" \\h \\z \\u '
    run2._r.append(instrText)
    run3 = paragraph.add_run()
    fldChar2 = OxmlElement('w:fldChar')
    fldChar2.set(qn('w:fldCharType'), 'separate')
    run3._r.append(fldChar2)
    run4 = paragraph.add_run('（請在 Word 中按右鍵 > 更新功能變數 以產生目錄）')
    run4.font.color.rgb = GRAY
    run4.font.italic = True
    run5 = paragraph.add_run()
    fldChar3 = OxmlElement('w:fldChar')
    fldChar3.set(qn('w:fldCharType'), 'end')
    run5._r.append(fldChar3)

def set_cell_shading(cell, color):
    shading = OxmlElement('w:shd')
    shading.set(qn('w:fill'), color)
    shading.set(qn('w:val'), 'clear')
    cell._tc.get_or_add_tcPr().append(shading)

def add_styled_table(doc, headers, rows, col_widths=None):
    table = doc.add_table(rows=1 + len(rows), cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.style = 'Table Grid'
    # Header row
    for i, h in enumerate(headers):
        cell = table.rows[0].cells[i]
        cell.text = h
        set_cell_shading(cell, '003153')
        for p in cell.paragraphs:
            p.alignment = WD_ALIGN_PARAGRAPH.CENTER
            for r in p.runs:
                r.font.color.rgb = WHITE
                r.font.bold = True
                r.font.size = Pt(9)
    # Data rows
    for ri, row in enumerate(rows):
        for ci, val in enumerate(row):
            cell = table.rows[ri + 1].cells[ci]
            cell.text = str(val)
            for p in cell.paragraphs:
                for r in p.runs:
                    r.font.size = Pt(9)
            if ri % 2 == 1:
                set_cell_shading(cell, 'F4F6F8')
    if col_widths:
        for i, w in enumerate(col_widths):
            for row in table.rows:
                row.cells[i].width = Cm(w)
    return table

def h(doc, level, text):
    """Add heading with consistent styling"""
    heading = doc.add_heading(text, level=level)
    for run in heading.runs:
        run.font.color.rgb = BLUE
    return heading

def p(doc, text, bold=False, italic=False, size=10.5):
    """Add paragraph"""
    para = doc.add_paragraph(text)
    for run in para.runs:
        run.font.size = Pt(size)
        run.font.bold = bold
        run.font.italic = italic
    return para

def bullet(doc, text, level=0):
    para = doc.add_paragraph(text, style='List Bullet')
    para.paragraph_format.left_indent = Cm(1.5 + level * 0.75)
    for run in para.runs:
        run.font.size = Pt(10)
    return para

def generate():
    doc = Document()
    
    # Page setup
    section = doc.sections[0]
    section.page_height = Cm(29.7)
    section.page_width = Cm(21)
    section.top_margin = Cm(2.54)
    section.bottom_margin = Cm(2.54)
    section.left_margin = Cm(3.17)
    section.right_margin = Cm(3.17)
    
    # ===== COVER PAGE =====
    for _ in range(4):
        doc.add_paragraph()
    
    cover_title = doc.add_paragraph()
    cover_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = cover_title.add_run('天主教輔仁大學')
    run.font.size = Pt(22)
    run.font.color.rgb = BLUE
    run.font.bold = True
    
    cover_sub = doc.add_paragraph()
    cover_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = cover_sub.add_run('學務處 課外活動指導組')
    run.font.size = Pt(14)
    run.font.color.rgb = GOLD
    run.font.bold = True
    
    doc.add_paragraph()
    
    main_title = doc.add_paragraph()
    main_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = main_title.add_run('FJU Smart Hub')
    run.font.size = Pt(36)
    run.font.color.rgb = BLUE
    run.font.bold = True
    
    sub_title = doc.add_paragraph()
    sub_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = sub_title.add_run('輔仁大學智慧校園管理平台')
    run.font.size = Pt(16)
    run.font.color.rgb = BLUE
    
    doc.add_paragraph()
    
    spec_title = doc.add_paragraph()
    spec_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = spec_title.add_run('系統分析規格書 (SA Specification)')
    run.font.size = Pt(18)
    run.font.color.rgb = BLUE
    run.font.bold = True
    
    doc.add_paragraph()
    
    ver_info = doc.add_paragraph()
    ver_info.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = ver_info.add_run(f'114 學年度 · 版本 2.1.0 · {datetime.date.today().isoformat()}')
    run.font.size = Pt(11)
    run.font.color.rgb = GRAY
    
    doc.add_page_break()
    
    # ===== VERSION HISTORY =====
    h(doc, 1, '版本修訂紀錄')
    add_styled_table(doc,
        ['版本', '日期', '修訂者', '修訂內容'],
        [
            ['1.0.0', '2026-03-01', '開發團隊', '初版系統規格書'],
            ['1.5.0', '2026-03-15', '開發團隊', '新增 10 大核心模組完整規格'],
            ['2.0.0', '2026-04-01', '開發團隊', '重構前端 UI/UX、整合 Leaflet.js 校園地圖'],
            ['2.1.0', '2026-04-08', '開發團隊', '配色修正為 60-30-10 法則、新增 4 LayerGroups、租借流程 RAG'],
        ]
    )
    
    doc.add_page_break()
    
    # ===== TABLE OF CONTENTS =====
    h(doc, 1, '目錄')
    add_toc(doc)
    doc.add_page_break()
    
    # ===== 1. SYSTEM OVERVIEW =====
    h(doc, 1, '1. 系統概述')
    
    h(doc, 2, '1.1 專案背景')
    p(doc, '輔仁大學課外活動指導組（課指組）長期面臨紙本流程繁瑣、場地/器材管理不透明、社團活動審核耗時等痛點。FJU Smart Hub 旨在透過 AI 與數位化轉型，建構一站式智慧校園管理平台，涵蓋場地預約、器材借用、活動管理、AI 預審、數位證書等功能。')
    
    h(doc, 2, '1.2 專案目標')
    for goal in [
        '建構整合前後端的全棧 Web 應用，取代現行紙本流程',
        '導入 AI (Dify RAG) 智慧預審，將審核時間從 3 天縮短至 30 秒',
        '實現三階段資源調度（志願序配對 → AI 協商 → 官方核准）',
        '提供 Leaflet.js 無障礙互動校園地圖（4 LayerGroups）',
        '整合 Google OAuth + 2FA (TOTP/SMS) 全方位安全機制',
        '五國語言支援（繁中、簡中、英文、日文、韓文）',
    ]:
        bullet(doc, goal)
    
    h(doc, 2, '1.3 適用對象（五大角色）')
    add_styled_table(doc,
        ['角色', '代碼', '權限說明'],
        [
            ['課指組/處室', 'admin', '最高管理權限，審核場地/活動，管理所有社團'],
            ['社團幹部', 'officer', '社團管理、活動申請、器材借用、場地預約'],
            ['指導教授', 'professor', '社團指導、活動預審、風險監控'],
            ['一般學生', 'student', '活動報名、個人 E-Portfolio、社團資訊瀏覽'],
            ['資訊中心', 'it', '系統監控、WAF 設定、資料庫管理'],
        ]
    )
    
    h(doc, 2, '1.4 系統範圍')
    p(doc, '本系統涵蓋以下十大核心模組（The 10 Pillars）：')
    modules = [
        ('職能 E-Portfolio', '學生個人職能紀錄、雷達圖分析、PDF 履歷匯出'),
        ('AI 企劃生成器', '調用 Dify Workflow 自動生成活動企劃書'),
        ('幹部證書自動化', '數位簽章證書產生、QR Code 驗證'),
        ('器材盤點追蹤', 'LINE/SMS 到期提醒、信用積分扣抵'),
        ('AI 智慧預審', 'RAG 法規比對、合規風險標記'),
        ('場地活化大數據', '使用率熱力圖、三階段資源調度'),
        ('AI 申訴摘要', 'WebSocket 即時生成申訴摘要與建議'),
        ('動態活動牆', '關鍵字/標籤搜尋、即時報名'),
        ('數位時光膠囊', 'R2 檔案封裝、社團移交文件'),
        ('全方位 2FA', 'TOTP + SMS 雙因子驗證'),
    ]
    add_styled_table(doc,
        ['#', '模組名稱', '功能概述'],
        [[str(i+1), m[0], m[1]] for i, m in enumerate(modules)]
    )
    
    doc.add_page_break()
    
    # ===== 2. TECHNOLOGY STACK =====
    h(doc, 1, '2. 技術架構')
    
    h(doc, 2, '2.1 技術選型')
    add_styled_table(doc,
        ['層級', '技術', '版本', '用途'],
        [
            ['前端框架', 'Vue 3', '^3.4', '響應式 UI、元件化架構'],
            ['前端樣式', 'Tailwind CSS', '^3.4', '60-30-10 配色、rounded 12-15px'],
            ['前端動畫', 'GSAP + ScrollTrigger', '^3.12', '頁面過場、卡片淡入'],
            ['前端地圖', 'Leaflet.js', '^1.9.4', '互動校園地圖、GeoJSON 分區'],
            ['前端行事曆', 'evo-calendar.js', '^1.1.3', '智慧行事曆面板'],
            ['前端圖表', 'Chart.js', '^4.4', '統計圖表、雷達圖'],
            ['後端框架', 'PHP 8.2/8.3 (Laravel)', '^11', 'RESTful API、MVC 架構'],
            ['資料庫', 'MySQL 8.0 (InnoDB)', '^8.0', '關聯式資料庫、事務控制'],
            ['快取/排程', 'Redis', '^7', '2FA OTP、協商計時器'],
            ['AI 中台', 'Dify AI', '-', 'RAG 法規檢索、Workflow 企劃生成'],
            ['向量資料庫', 'Pinecone', '-', '法規文件向量索引'],
            ['CDN/安全', 'Cloudflare', '-', 'WAF、Turnstile、R2 Storage'],
            ['認證', 'Google OAuth 2.0', '-', '@cloud.fju.edu.tw 域名限制'],
            ['2FA', 'PHPGangsta + Redis', '-', 'TOTP/SMS 雙因子驗證'],
            ['通知', 'LINE Notify / SMS Gateway', '-', '場地/器材到期推送'],
            ['即時通訊', 'Socket.io', '-', 'AI 申訴摘要即時推送'],
        ]
    )
    
    h(doc, 2, '2.2 系統架構圖')
    p(doc, '系統採用前後端分離架構，前端 Vue 3 SPA 透過 RESTful API 與 Laravel 後端溝通：')
    p(doc, '''
[使用者] ← HTTPS/Cloudflare WAF →
  [Vue 3 SPA + Tailwind CSS + Leaflet.js]
    ↕ REST API (JSON)
  [Laravel 11 (PHP 8.3)]
    ├── Google OAuth (hd=cloud.fju.edu.tw)
    ├── JWT (HttpOnly Cookie) + 2FA (TOTP/SMS)
    ├── Dify AI (RAG/Workflow via GuzzleHTTP)
    ├── Redis (OTP, 協商 Timer)
    ├── MySQL 8.0 (InnoDB)
    └── Cloudflare R2 (時光膠囊/證書 PDF)
''', size=9)
    
    h(doc, 2, '2.3 60-30-10 配色規範')
    add_styled_table(doc,
        ['比例', '名稱', '色碼', '用途'],
        [
            ['60%', 'Angel White (主色)', '#FFFFFF', '背景、卡片、內容區'],
            ['30%', 'Virgin Mary Blue (輔色)', '#003153', '標題、按鈕、Header、Sidebar、Footer'],
            ['10%', 'Vatican Gold (強調色)', '#DAA520', '按鈕 hover、active 標記、重要標籤'],
            ['10%', 'School Flag Gold', '#FDB913', '按鈕 hover 亮色、警示輔助色'],
            ['輔助', '正常狀態', '#008000', '正常、可用、通過'],
            ['輔助', '異常/維護', '#FF0000', '維護中、逾期、衝突'],
        ]
    )
    
    h(doc, 2, '2.4 UI/UX 規範')
    for spec in [
        '字型：Microsoft JhengHei (微軟正黑體)，備用 system-ui, sans-serif',
        '圓角：12px (fju) / 15px (fju-lg) 統一圓角',
        '佈局：Bento Grid 內部頁面、glassmorphism 登入框',
        'Header：雙層 — 上白 (校名+連結) / 下深藍 (#003153，導覽+搜尋)',
        'Sidebar：深灰 #333333，四大區塊 (主要功能/社群社團/AI核心/管理報表)',
        'Footer：深藍背景，校址、粉專連結、櫃台資訊、版權',
        'AI 聊天：固定右下角 FAB (柴犬 🐕 輔寶)，毛玻璃面板',
        '行事曆面板：毛玻璃效果 (blur 15px, rgba 255,255,255,0.85)，佔 40% 寬度',
    ]:
        bullet(doc, spec)
    
    doc.add_page_break()
    
    # ===== 3. DATABASE DESIGN =====
    h(doc, 1, '3. 資料庫設計')
    
    h(doc, 2, '3.1 ER 模型概要')
    p(doc, '本系統使用 MySQL 8.0 (InnoDB) 作為主資料庫，共 16 張資料表，支援外鍵約束、索引優化、事務控制。')
    
    tables_info = [
        ('users', '使用者', 'id, student_id, name, email, phone, role, credit_score, google_oauth_id, two_factor_enabled, ...'),
        ('clubs', '社團', 'id, name, category, description, advisor_id, president_id, member_count, ...'),
        ('club_members', '社團成員', 'id, club_id, user_id, position, joined_at'),
        ('venues', '場地', 'id, name, location, capacity, status, equipment_list, latitude, longitude'),
        ('activities', '活動', 'id, title, description, club_id, venue_id, event_date, category, status, ai_review_result, ...'),
        ('activity_registrations', '活動報名', 'id, activity_id, user_id, status, check_in_at'),
        ('equipment', '器材', 'id, code, name, category, status, condition_note'),
        ('equipment_borrowings', '器材借用', 'id, equipment_id, borrower_id, borrow_date, expected_return_date, status'),
        ('reservations', '場地預約', 'id, user_id, venue_id, reservation_date, priority_level, status, stage'),
        ('conflicts', '衝突協商', 'id, reservation_a_id, reservation_b_id, venue_id, status, ai_suggestions'),
        ('credit_logs', '信用紀錄', 'id, user_id, action, points, reason'),
        ('notification_logs', '通知紀錄', 'id, user_id, title, message, channel, read'),
        ('certificates', '證書', 'id, user_id, club_id, position, term, certificate_code, digital_signature'),
        ('portfolio_entries', 'E-Portfolio', 'id, user_id, title, description, category, tags'),
        ('competency_scores', '職能分數', 'id, user_id, dimension, score'),
        ('ai_review_logs', 'AI 審核記錄', 'id, target_type, target_id, risk_level, violations, references_cited'),
        ('appeals', '申訴', 'id, appellant_id, appeal_type, subject, status, ai_summary'),
        ('time_capsules', '時光膠囊', 'id, club_id, created_by, term, r2_storage_key, status'),
        ('outbox_table', '異步事件', 'id, event_type, payload, status, retry_count'),
    ]
    
    add_styled_table(doc,
        ['#', '資料表', '中文名', '主要欄位'],
        [[str(i+1), t[0], t[1], t[2]] for i, t in enumerate(tables_info)]
    )
    
    h(doc, 2, '3.2 關鍵約束與索引')
    for constraint in [
        'users.role：CHECK IN (student, officer, professor, admin, it)',
        'reservations.priority_level：CHECK IN (1, 2, 3)，Level 1 為校方最高優先',
        'reservations.stage：CHECK IN (algorithm, negotiation, approval, completed)',
        'credit_logs.action：CHECK IN (add, deduct)，低於 60 分觸發 JWT 失效',
        'venues.status：CHECK IN (available, maintenance, reserved, closed)',
        '索引：idx_users_email, idx_activities_date, idx_reservations_venue, idx_credit_logs_user',
        '唯一約束：users.student_id, users.email, equipment.code, certificates.certificate_code',
    ]:
        bullet(doc, constraint)
    
    h(doc, 2, '3.3 事務控制')
    p(doc, '場地預約採用 Pessimistic Locking (SELECT ... FOR UPDATE) 確保並行預約時無 Race Condition。信用積分扣抵使用 START TRANSACTION 確保一致性。')
    
    doc.add_page_break()
    
    # ===== 4. FRONTEND ARCHITECTURE =====
    h(doc, 1, '4. 前端架構')
    
    h(doc, 2, '4.1 頁面結構')
    add_styled_table(doc,
        ['路由', '頁面名稱', '說明'],
        [
            ['/', '首頁 (Landing)', 'Hero 輪播、功能介紹、十大模組、推薦、公告'],
            ['/login', '登入', 'Google OAuth + 帳密、Turnstile、角色快速切換'],
            ['/dashboard', '儀表板', '角色專屬統計、輪播、功能卡片、地圖速覽'],
            ['/campus-map', '互動校園地圖', 'Leaflet.js + GeoJSON、4 LayerGroups、行事曆面板'],
            ['/module/venue-booking', '場地預約', '三階段調度、預約表單、使用率圖表'],
            ['/module/equipment', '設備借用', '器材清單、借還流程、LINE 提醒設定'],
            ['/module/calendar', '行事曆', 'evo-calendar + API 事件、近期活動列表'],
            ['/module/club-info', '社團資訊', '114 年度完整社團/學會資料、分類篩選'],
            ['/module/activity-wall', '活動牆', '活動卡片、報名功能、進度條'],
            ['/module/ai-overview', 'AI 資訊概覽', '6 大 AI 功能入口、統計指標'],
            ['/module/ai-guide', 'AI 導覽助理', 'AI 企劃書生成表單、RAG 法規參考'],
            ['/module/rag-search', '法規 RAG 查詢', '智慧法規問答、知識庫文件列表'],
            ['/module/rag-rental', '租借流程 RAG', '租借流程問答、場地/器材借用步驟'],
            ['/module/repair', '報修管理', '報修單提交、追蹤碼、處理狀態'],
            ['/module/appeal', '申訴記錄', '申訴提交、AI 摘要面板、仲裁按鈕'],
            ['/module/reports', '統計報表', '趨勢圖、類型分布、場地排行、SDG 雷達圖'],
        ]
    )
    
    h(doc, 2, '4.2 共用元件 (Layout System)')
    for comp in [
        'layout()：全局 HTML 骨架，Tailwind config、CDN 引用、全局 CSS',
        'appShell()：登入後頁面框架 — 雙層 Header + Sidebar + Main + Footer + Chatbot',
        'doubleHeader()：上白 (校名/連結/角色切換) + 下深藍 (導覽/搜尋/通知/用戶)',
        'getSidebarMenus()：四大區塊導覽，active 黃色標記，信用儀表板',
        'footerBar()：深藍背景，校址、社群連結、聯絡資訊、版權聲明',
        'chatbotWidget()：柴犬 🐕 輔寶 AI 助理 FAB + 對話面板',
    ]:
        bullet(doc, comp)
    
    h(doc, 2, '4.3 互動校園地圖（Leaflet.js）')
    p(doc, '地圖以淨心堂 (25.0359, 121.4323) 為中心，使用 OpenStreetMap 底圖 + GeoJSON 建築分區疊加。')
    
    add_styled_table(doc,
        ['LayerGroup', '圖示', '內容', '標記數'],
        [
            ['教學行政區', '🏛️', '17 棟教學/行政大樓，學院分色編碼', '17'],
            ['無障礙設施', '♿', '坡道 (4)、無障礙電梯 (3)、無障礙廁所 (2)', '9'],
            ['生活機能', '🍽️', '餐廳 (3)、便利商店 (1)、郵局 (1)、宿舍區 (1)', '6'],
            ['交通設施', '🚇', '捷運站 (1)、YouBike (2)、停車場 (2)、接駁車 (1)、通風口 (1)', '7'],
        ]
    )
    
    p(doc, '建築 Popup 包含：學院分色標頭、樓層數、狀態（綠/紅）、「我要預約」按鈕、「定位」按鈕。')
    p(doc, '行事曆面板：毛玻璃效果 (backdrop-filter: blur(15px))，佔 40% 寬度從右側滑入 (GSAP)，整合 evo-calendar 與場地預約狀態。')
    
    doc.add_page_break()
    
    # ===== 5. BACKEND ARCHITECTURE =====
    h(doc, 1, '5. 後端架構')
    
    h(doc, 2, '5.1 API 端點清單')
    apis = [
        ('GET', '/api/users', '取得使用者列表'),
        ('POST', '/api/users', '新增使用者'),
        ('GET', '/api/users/:id', '取得使用者詳情'),
        ('PUT', '/api/users/:id', '更新使用者資料'),
        ('DELETE', '/api/users/:id', '刪除使用者'),
        ('GET', '/api/clubs', '取得社團列表（含篩選 type/category/search）'),
        ('GET', '/api/clubs/stats', '取得社團統計資料'),
        ('GET', '/api/clubs/:id', '取得社團詳情'),
        ('POST/PUT/DELETE', '/api/clubs', '社團 CRUD'),
        ('GET', '/api/venues', '取得場地列表'),
        ('GET', '/api/venues/:id/schedule', '取得場地時段表'),
        ('POST/PUT/DELETE', '/api/venues', '場地 CRUD'),
        ('GET', '/api/activities', '取得活動列表（含篩選 category/status/search）'),
        ('POST', '/api/activities', '新增活動（觸發 AI 預審）'),
        ('POST', '/api/activities/:id/register', '活動報名'),
        ('GET', '/api/equipment', '取得器材列表'),
        ('POST', '/api/equipment/borrow', '借用器材（LINE 通知）'),
        ('POST', '/api/equipment/return', '歸還器材（+5 信用分）'),
        ('POST', '/api/equipment/remind', '發送 LINE 提醒'),
        ('GET', '/api/reservations', '取得預約列表'),
        ('POST', '/api/reservations', '新增預約（三階段調度）'),
        ('POST', '/api/reservations/:id/negotiate', 'AI 協商建議'),
        ('POST', '/api/reservations/:id/accept-suggestion', '接受協商方案'),
        ('POST', '/api/ai/pre-review', 'AI 預審（RAG）'),
        ('POST', '/api/ai/generate-proposal', 'AI 企劃書生成'),
        ('GET', '/api/credits/:userId', '取得信用積分與紀錄'),
        ('POST', '/api/credits/deduct', '扣除信用積分'),
        ('GET', '/api/calendar/events', '取得行事曆事件'),
        ('GET', '/api/notifications/:userId', '取得通知列表'),
        ('POST', '/api/certificates/generate', '生成數位證書'),
        ('GET', '/api/dashboard/stats/:role', '角色專屬儀表板統計'),
        ('POST', '/api/conflicts/negotiate', '衝突協商 (3/6 min)'),
        ('GET', '/api/repairs', '報修列表'),
        ('POST', '/api/repairs', '新增報修'),
        ('GET', '/api/appeals', '申訴列表'),
        ('POST', '/api/appeals/:id/ai-summary', 'AI 申訴摘要'),
        ('GET', '/api/i18n/:lang', '語系翻譯包'),
        ('GET', '/api/health', '系統健康檢查'),
    ]
    add_styled_table(doc,
        ['方法', '路徑', '說明'],
        [[a[0], a[1], a[2]] for a in apis],
        col_widths=[3, 6, 6]
    )
    
    h(doc, 2, '5.2 三階段資源調度')
    p(doc, '場地預約核心邏輯分三個階段：')
    for stage in [
        '第一階段 - 志願序配對（Algorithm）：依 Level 1/2/3 優先權自動配對，無衝突直接進入核准',
        '第二階段 - 自主協商（Negotiation）：偵測衝突後啟動 3/6 分鐘計時器。3 分鐘 GPT-4 介入建議，6 分鐘強制關閉並扣雙方各 10 信用分',
        '第三階段 - 官方核准（Approval）：RAG 法規比對 + Gatekeeping，產出 PDF + TOTP QR 驗證碼',
    ]:
        bullet(doc, stage)
    
    h(doc, 2, '5.3 AI 整合（Dify + RAG）')
    for ai_feat in [
        'AI 預審：Dify RAG Engine 比對 5+ 法規文件（Pinecone 向量 DB），返回 risk_level / violations / references / suggestions',
        'AI 企劃生成：Dify Workflow 參考法規模板，產出完整企劃書含預算/流程/SDG 對應',
        'AI 協商監控：GPT-4 分析雙方歷史使用紀錄，3 分鐘後自動提供替代方案',
        'AI 申訴摘要：WebSocket 即時推送案件分析，含情緒分析/緊急度/建議方案',
        'AI 導覽 (輔寶)：Shiba-Inu 柴犬化身 FAB，支援法規查詢/場地導覽/流程引導',
    ]:
        bullet(doc, ai_feat)
    
    h(doc, 2, '5.4 安全機制')
    add_styled_table(doc,
        ['機制', '技術', '說明'],
        [
            ['認證', 'Google OAuth 2.0', '限制 @cloud.fju.edu.tw 域名 (hd 參數)'],
            ['2FA', 'TOTP (PHPGangsta) + SMS', '仲裁、核銷、後台登入必須 2FA'],
            ['Session', 'JWT (HttpOnly Cookie)', '信用積分 <60 → Observer 自動撤銷 JWT'],
            ['前端保護', 'Cloudflare Turnstile', '非互動式 CAPTCHA，登入頁必備'],
            ['WAF', 'Cloudflare WAF', 'IP 信譽、Rate Limiting、SQL Injection 防護'],
            ['檔案儲存', 'Cloudflare R2', 'GPS 浮水印、加密存取'],
            ['信用制度', '積分狀態機', '起始 100 分，逾期 -2/天，低於 60 強制登出'],
        ]
    )
    
    doc.add_page_break()
    
    # ===== 6. MODULE SPECIFICATIONS =====
    h(doc, 1, '6. 核心模組規格')
    
    module_specs = [
        {
            'title': '6.1 職能 E-Portfolio',
            'desc': '記錄學生參與社團活動的職能成長歷程，提供雷達圖分析與 PDF 履歷匯出。',
            'features': [
                '個人資料表：姓名、學號、Email、社團職位',
                '職能雷達圖（Chart.js radar）：領導力、創意思維、團隊合作、溝通能力、數位能力',
                '活動紀錄列表：自動從 activity_registrations 彙總',
                '技能標籤（Tag-based）：可自訂/自動建議',
                'PDF 匯出：透過 outbox_table 異步產生',
            ],
            'db': ['portfolio_entries', 'competency_scores', 'activity_registrations'],
            'api': ['GET /api/users/:id', 'GET /api/portfolio/:userId'],
        },
        {
            'title': '6.2 AI 企劃生成器',
            'desc': '調用 Dify Workflow 參考校內法規，自動生成完整活動企劃書。',
            'features': [
                '表單輸入：活動名稱、類型、人數、日期、描述',
                'Dify Workflow 調用：GuzzleHTTP → Dify API',
                '生成內容：目的/時間/地點/流程/預算明細/風險評估/SDG 對應',
                'RAG 法規參考列表：5+ 法規文件即時檢索',
                'AI 預審結果：risk_level + confidence 指標',
            ],
            'db': ['ai_review_logs'],
            'api': ['POST /api/ai/generate-proposal', 'POST /api/ai/pre-review'],
        },
        {
            'title': '6.3 幹部證書自動化',
            'desc': '自動產生社團幹部數位證書，含數位簽章與 QR Code 驗證。',
            'features': [
                '證書表單：社團名稱、幹部姓名、職位、任期',
                '即時預覽：校內官方格式、金邊裝飾',
                '數位簽章：SHA256 雜湊 + 唯一驗證碼',
                'QR Code：線上驗證連結',
                'PDF 下載：Server-side 產生',
            ],
            'db': ['certificates'],
            'api': ['POST /api/certificates/generate'],
        },
        {
            'title': '6.4 器材盤點追蹤',
            'desc': '完整器材生命週期管理，含借還流程、LINE/SMS 到期提醒、信用積分連動。',
            'features': [
                '器材清單：編號/名稱/類別/狀態/借用人/歸還日',
                '借用流程：線上申請 → 審核 → 領取 → LINE 提醒 → 歸還',
                '篩選器：全部/可借用/已借出/維修中',
                'LINE Notify：歸還前 1 天自動推送',
                '信用積分：準時歸還 +5，逾期 -2/天',
            ],
            'db': ['equipment', 'equipment_borrowings', 'credit_logs'],
            'api': ['GET /api/equipment', 'POST /api/equipment/borrow', 'POST /api/equipment/return', 'POST /api/equipment/remind'],
        },
        {
            'title': '6.5 AI 智慧預審 (RAG)',
            'desc': 'Dify RAG Engine 自動比對校內法規，標記合規風險，提供修改建議。',
            'features': [
                '智慧法規問答：自然語言輸入',
                'Pinecone 向量 DB：5+ 法規文件已索引',
                '結構化回覆：引用條文、信心指數、建議列表',
                '常用問題快捷按鈕',
                '租借流程 RAG：場地/器材借用步驟查詢',
            ],
            'db': ['ai_review_logs'],
            'api': ['POST /api/ai/pre-review', 'GET /api/i18n/:lang'],
        },
        {
            'title': '6.6 場地活化大數據',
            'desc': '場地使用率熱力圖、周轉排行榜，搭配三階段資源調度系統。',
            'features': [
                '場地統計卡：總數/可用/使用率/維護中',
                '使用熱力圖（Chart.js bar）：時段使用密度',
                '場地周轉排行（Chart.js horizontal bar）',
                '預約表單 Modal：場地/日期/時段/優先等級/用途',
                '三階段調度視覺化：志願序 → 協商 → 核准',
            ],
            'db': ['venues', 'reservations', 'conflicts'],
            'api': ['GET /api/venues', 'POST /api/reservations', 'POST /api/reservations/:id/negotiate'],
        },
        {
            'title': '6.7 AI 申訴摘要',
            'desc': 'WebSocket 即時生成申訴案件摘要，含情緒分析與建議方案。',
            'features': [
                '申訴案件列表：待處理/處理中/已結案',
                '新增申訴表單：主題/描述',
                'AI 摘要面板：案件摘要/建議列表/情緒分析/緊急度',
                '操作按鈕：採納方案/進入仲裁',
                'WebSocket 即時推送（模擬）',
            ],
            'db': ['appeals'],
            'api': ['GET /api/appeals', 'POST /api/appeals', 'POST /api/appeals/:id/ai-summary'],
        },
        {
            'title': '6.8 動態活動牆',
            'desc': '卡片式活動展示，支援分類篩選、關鍵字搜尋、即時報名。',
            'features': [
                '活動卡片：漸層背景/類型標籤/日期/場地/報名進度條',
                '分類篩選：全部/學術/服務/康樂/體育/藝文',
                '關鍵字搜尋：即時過濾',
                '報名功能：POST /api/activities/:id/register',
                '額滿標記：進度條 100% 顯示紅色',
            ],
            'db': ['activities', 'activity_registrations'],
            'api': ['GET /api/activities', 'POST /api/activities/:id/register'],
        },
        {
            'title': '6.9 數位時光膠囊',
            'desc': '社團移交文件封裝至 Cloudflare R2，確保跨屆資產安全傳承。',
            'features': [
                '檔案上傳：R2 Storage 加密存放',
                '文件清單：檔名/大小/上傳日期',
                '封裝狀態：已封裝/已移交/已接收',
                '解封驗證：現任社長 + 前任社長雙重驗證',
                'GPS 浮水印：上傳時記錄地理資訊',
            ],
            'db': ['time_capsules'],
            'api': ['POST /api/capsules', 'POST /api/capsules/:id/transfer'],
        },
        {
            'title': '6.10 全方位 2FA',
            'desc': 'TOTP + SMS 雙因子驗證，強制用於仲裁、核銷、後台登入。',
            'features': [
                'TOTP 切換：Google Authenticator / Authy',
                'SMS 切換：手機驗證碼',
                'LINE Notify 綁定：場地/器材通知',
                '必要 2FA 動作：仲裁操作、經費核銷、後台管理登入',
                'QR Code 顯示：TOTP 設定掃描',
            ],
            'db': ['users (two_factor_enabled, two_factor_secret)'],
            'api': ['POST /api/auth/2fa/enable', 'POST /api/auth/2fa/verify'],
        },
    ]
    
    for mod in module_specs:
        h(doc, 2, mod['title'])
        p(doc, mod['desc'])
        p(doc, '功能需求：', bold=True)
        for feat in mod['features']:
            bullet(doc, feat)
        p(doc, f'相關資料表：{", ".join(mod["db"])}', italic=True, size=9)
        p(doc, f'API 端點：{", ".join(mod["api"])}', italic=True, size=9)
    
    doc.add_page_break()
    
    # ===== 7. CAMPUS MAP =====
    h(doc, 1, '7. 無障礙校園地圖規格')
    
    h(doc, 2, '7.1 技術實現')
    for spec in [
        'Leaflet.js v1.9.4 + OpenStreetMap 底圖',
        'GeoJSON 建築分區：campus.geojson (120KB+)，學院分色多邊形',
        '座標系統：WGS84 經緯度，地圖中心 (25.0360, 121.4320) - 淨心堂',
        '淨心堂圓形高亮：L.circle 半徑 50m，金色虛線',
        '兩點仿射變換：中美堂 (A) + 第二圓環 (B) 錨點校正',
    ]:
        bullet(doc, spec)
    
    h(doc, 2, '7.2 四大 LayerGroup')
    add_styled_table(doc,
        ['LayerGroup', '標記數', '標記類型', '顏色', '內容'],
        [
            ['教學行政區', '17', 'GeoJSON Polygon', '#003153 學院分色', '17 棟教學行政大樓'],
            ['無障礙設施', '9', 'DivIcon Marker', '#008000 (坡道/電梯)', '4 坡道 + 3 電梯 + 2 廁所'],
            ['生活機能', '6', 'DivIcon Marker', '#FF6B35', '3 餐廳 + 1 便利商店 + 1 郵局 + 1 宿舍'],
            ['交通設施', '7', 'DivIcon Marker', '#6C5CE7', '1 捷運 + 2 YouBike + 2 停車場 + 1 接駁 + 1 通風口'],
        ]
    )
    
    h(doc, 2, '7.3 Popup 資訊卡')
    for spec in [
        '標頭：學院色彩背景 + 圖示 + 建築名稱 + 英文名',
        '內容：學院歸屬、代碼、樓層數、狀態（綠：可用 / 紅：維護中）',
        '按鈕：「我要預約」(導向 /module/venue-booking) + 「定位」(flyTo zoom 19)',
        'CSS：rounded 12px, box-shadow, 最大寬度 320px',
    ]:
        bullet(doc, spec)
    
    doc.add_page_break()
    
    # ===== 8. SIDEBAR & NAVIGATION =====
    h(doc, 1, '8. 導覽結構')
    
    h(doc, 2, '8.1 Sidebar 四大區塊')
    add_styled_table(doc,
        ['區塊', '項目', '圖示'],
        [
            ['主要功能', 'Dashboard', 'fas fa-tachometer-alt'],
            ['主要功能', '校園分區地圖', 'fas fa-map-marked-alt'],
            ['主要功能', '場地預約', 'fas fa-map-marker-alt'],
            ['主要功能', '設備借用', 'fas fa-boxes-stacked'],
            ['主要功能', '行事曆', 'fas fa-calendar-alt'],
            ['社群與社團', '社團資訊', 'fas fa-users'],
            ['社群與社團', '活動牆', 'fas fa-newspaper'],
            ['AI 核心專區', 'AI 資訊概覽', 'fas fa-brain'],
            ['AI 核心專區', 'AI 導覽助理', 'fas fa-robot'],
            ['AI 核心專區', '法規 RAG 查詢', 'fas fa-gavel'],
            ['AI 核心專區', '租借流程 RAG', 'fas fa-file-contract'],
            ['管理與報表', '報修管理', 'fas fa-wrench'],
            ['管理與報表', '申訴記錄', 'fas fa-comments'],
            ['管理與報表', '統計報表', 'fas fa-chart-bar'],
        ]
    )
    
    h(doc, 2, '8.2 信用積分儀表板')
    p(doc, 'Sidebar 頂部顯示信用積分：起始 100 分、進度條、閾值 60 分警告。低於 60 分觸發 JWT 自動失效（Observer Pattern）。')
    
    doc.add_page_break()
    
    # ===== 9. TESTING & DEPLOYMENT =====
    h(doc, 1, '9. 部署與測試')
    
    h(doc, 2, '9.1 Demo 環境')
    add_styled_table(doc,
        ['項目', '說明'],
        [
            ['Demo URL', 'https://3000-<sandbox>.sandbox.novita.ai'],
            ['GitHub', 'https://github.com/<repo>'],
            ['Cloudflare Pages', 'https://<project>.pages.dev'],
            ['PM2 Process', 'fju-smart-hub (wrangler pages dev)'],
            ['Build Tool', 'Vite 6 + @hono/vite-cloudflare-pages'],
        ]
    )
    
    h(doc, 2, '9.2 測試涵蓋')
    for test in [
        '16 頁面路由完整性驗證（curl 測試）',
        '38+ RESTful API 端點回應驗證',
        'CORS 中間件（/api/* 路徑）',
        '角色切換（admin/officer/professor/student/it）',
        '5 國語系切換（zh-TW/zh-CN/en/ja/ko）',
        '地圖 GeoJSON 載入、4 LayerGroup 切換、建築搜尋/篩選',
        '行事曆面板 GSAP 滑入/滑出動畫',
    ]:
        bullet(doc, test)
    
    doc.add_page_break()
    
    # ===== 10. APPENDIX =====
    h(doc, 1, '10. 附錄')
    
    h(doc, 2, '10.1 社團完整資料 (114 學年度)')
    p(doc, '系統內建 114 學年度完整社團資料：社團 67 個 + 學會 50 個 = 共 117 個組織。包含名稱、分類、描述、成員數等完整資訊。')
    
    h(doc, 2, '10.2 參考法規文件（RAG 知識庫）')
    for reg in [
        '活動申請辦法 v2.1',
        '場地使用管理規則 v3.0',
        '經費補助要點 v1.5',
        '器材借用辦法 v2.0',
        '安全管理規範 v1.8',
    ]:
        bullet(doc, reg)
    
    h(doc, 2, '10.3 專案文件清單')
    add_styled_table(doc,
        ['檔案', '路徑', '說明'],
        [
            ['index.tsx', 'src/index.tsx', '主路由 (Hono)'],
            ['layout.ts', 'src/pages/layout.ts', '全局 Layout + 共用元件'],
            ['landing.ts', 'src/pages/landing.ts', '首頁'],
            ['login.ts', 'src/pages/login.ts', '登入頁'],
            ['dashboard.ts', 'src/pages/dashboard.ts', '儀表板'],
            ['campus-map.ts', 'src/pages/campus-map.ts', '互動校園地圖 (Leaflet.js)'],
            ['modules.ts', 'src/pages/modules.ts', '12 個功能模組頁面'],
            ['api.ts', 'src/routes/api.ts', '38+ RESTful API 端點'],
            ['clubs.ts', 'src/data/clubs.ts', '114 年度社團完整資料'],
            ['campus.geojson', 'public/static/campus.geojson', 'GeoJSON 校園建築資料'],
            ['0001_initial_schema.sql', 'migrations/', '資料庫初始 Schema (19 表)'],
        ]
    )
    
    # Save
    filepath = '/home/user/webapp/FJU_Smart_Hub_SA_Specification.docx'
    doc.save(filepath)
    print(f'✅ SA 規格書已生成：{filepath}')
    print(f'   檔案大小：{round(__import__("os").path.getsize(filepath)/1024)}KB')

if __name__ == '__main__':
    generate()
