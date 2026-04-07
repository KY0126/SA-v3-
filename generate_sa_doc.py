#!/usr/bin/env python3
"""
FJU Smart Hub - 系統開發規格書 (SA Document) Generator
Based on 114 SA Template format with automatic Table of Contents
Output: Word (.docx) with proper headings for TOC generation
"""

from docx import Document
from docx.shared import Inches, Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.enum.section import WD_ORIENT
from docx.oxml.ns import qn
from docx.oxml import OxmlElement
import datetime

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
    
    run4 = paragraph.add_run('（請在 Word 中按右鍵 > 更新功能變數以產生目錄）')
    run4.font.color.rgb = RGBColor(128, 128, 128)
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

def add_table_row(table, cells, bold=False, bg_color=None):
    row = table.add_row()
    for i, text in enumerate(cells):
        cell = row.cells[i]
        p = cell.paragraphs[0]
        run = p.add_run(str(text))
        run.font.size = Pt(10)
        if bold:
            run.bold = True
            run.font.color.rgb = RGBColor(255, 255, 255)
        if bg_color:
            set_cell_shading(cell, bg_color)
    return row

def generate_sa_document():
    doc = Document()
    
    # Page setup
    for section in doc.sections:
        section.top_margin = Cm(2.54)
        section.bottom_margin = Cm(2.54)
        section.left_margin = Cm(2.54)
        section.right_margin = Cm(2.54)
    
    style = doc.styles['Normal']
    style.font.name = 'Microsoft JhengHei'
    style.font.size = Pt(12)
    style._element.rPr.rFonts.set(qn('w:eastAsia'), 'Microsoft JhengHei')
    
    for level in range(1, 4):
        heading_style = doc.styles[f'Heading {level}']
        heading_style.font.name = 'Microsoft JhengHei'
        heading_style._element.rPr.rFonts.set(qn('w:eastAsia'), 'Microsoft JhengHei')
        heading_style.font.color.rgb = RGBColor(0, 43, 91)
    
    # ==================== COVER PAGE ====================
    for _ in range(6):
        doc.add_paragraph()
    
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run('天主教輔仁大學')
    run.font.size = Pt(22)
    run.bold = True
    run.font.color.rgb = RGBColor(0, 43, 91)
    
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run('學務處 課外活動指導組')
    run.font.size = Pt(16)
    run.font.color.rgb = RGBColor(0, 128, 0)
    
    doc.add_paragraph()
    
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run('FJU Smart Hub')
    run.font.size = Pt(36)
    run.bold = True
    run.font.color.rgb = RGBColor(255, 184, 0)
    
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run('智慧校園管理平台')
    run.font.size = Pt(20)
    run.font.color.rgb = RGBColor(0, 43, 91)
    
    doc.add_paragraph()
    
    p = doc.add_paragraph()
    p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    run = p.add_run('系統開發規格書 (SA Specification)')
    run.font.size = Pt(18)
    run.bold = True
    run.font.color.rgb = RGBColor(0, 43, 91)
    
    doc.add_paragraph()
    doc.add_paragraph()
    
    info_items = [
        ('文件版本', '2.1.0'),
        ('專案名稱', 'FJU Smart Hub 智慧校園管理平台'),
        ('文件日期', datetime.date.today().strftime('%Y年%m月%d日')),
        ('適用學年度', '114 學年度'),
        ('開發單位', '資訊中心 / 課外活動指導組'),
        ('技術架構', 'Hono + Vue 3 + Cloudflare D1 + Dify AI'),
    ]
    for label, value in info_items:
        p = doc.add_paragraph()
        p.alignment = WD_ALIGN_PARAGRAPH.CENTER
        run = p.add_run(f'{label}：{value}')
        run.font.size = Pt(11)
        run.font.color.rgb = RGBColor(51, 51, 51)
    
    doc.add_page_break()
    
    # ==================== TABLE OF CONTENTS ====================
    doc.add_heading('目錄', level=1)
    add_toc(doc)
    doc.add_page_break()
    
    # ==================== CHAPTER 1: 專案概述 ====================
    doc.add_heading('第一章 專案概述', level=1)
    
    doc.add_heading('1.1 專案背景', level=2)
    doc.add_paragraph('天主教輔仁大學課外活動指導組（以下簡稱課指組）負責管理全校 200+ 學生社團、5000+ 年度活動場次，以及 50+ 可借用場地與設備。現有流程仰賴紙本與人工作業，存在效率低落、資訊不透明、資源衝突頻繁等痛點。')
    doc.add_paragraph('FJU Smart Hub 智慧校園管理平台旨在整合 AI 智慧預審、資源調度、數位證書、活動管理等功能，打造一站式校園解決方案，提升行政效率與師生滿意度。')
    
    doc.add_heading('1.2 專案目標', level=2)
    goals = [
        '建構具備 AI 預審機制的線上活動/場地/設備管理系統',
        '實現三階段資源調度（志願序演算法 → AI 協商 → 官方審核）',
        '整合 114 學年度 71 個社團及 46 個學會完整資料',
        '提供 5 種角色（課指組、社團幹部、指導教授、一般學生、資訊中心）差異化操作介面',
        '支援繁中/簡中/英/日/韓 五國語言',
        '導入信用積分制度與 2FA 雙因子驗證',
        '建立 Leaflet.js 無障礙校園互動地圖',
    ]
    for g in goals:
        doc.add_paragraph(g, style='List Bullet')
    
    doc.add_heading('1.3 適用範圍', level=2)
    doc.add_paragraph('本系統適用於天主教輔仁大學全校師生，包含但不限於：課外活動指導組行政人員、社團幹部、指導教授、一般學生、資訊中心人員。')
    
    doc.add_heading('1.4 名詞定義', level=2)
    table = doc.add_table(rows=1, cols=2)
    table.style = 'Table Grid'
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    hdr = table.rows[0].cells
    hdr[0].text = '名詞'
    hdr[1].text = '定義'
    for cell in hdr:
        set_cell_shading(cell, '002B5B')
        for p in cell.paragraphs:
            for r in p.runs:
                r.font.color.rgb = RGBColor(255, 255, 255)
                r.bold = True
    
    terms = [
        ('FJU Smart Hub', '輔仁大學智慧校園管理平台'),
        ('RAG', 'Retrieval-Augmented Generation，檢索增強生成'),
        ('Dify', '開源 AI 應用開發平台，用於建構 RAG 工作流'),
        ('三階段調度', '志願序配對 → 自主協商 → 官方核准'),
        ('信用積分', '用戶行為積分系統，低於 60 分強制登出'),
        ('LayerGroup', 'Leaflet.js 圖層群組，用於地圖資料分層顯示'),
        ('TOTP', 'Time-based One-Time Password，基於時間的一次性密碼'),
    ]
    for term, defn in terms:
        add_table_row(table, [term, defn])
    
    doc.add_page_break()
    
    # ==================== CHAPTER 2: 系統架構 ====================
    doc.add_heading('第二章 系統架構', level=1)
    
    doc.add_heading('2.1 整體架構圖', level=2)
    doc.add_paragraph('本系統採用前後端分離架構，以 Cloudflare Workers 為部署基礎：')
    doc.add_paragraph('''
┌──────────────────────────────────────────────────────────────┐
│                    Cloudflare Edge Network                     │
│  ┌─────────┐  ┌─────────────┐  ┌──────────┐  ┌───────────┐  │
│  │   WAF   │→│ Cloudflare   │→│  Workers  │→│ D1 Database│  │
│  │ Turnstile│  │   Pages     │  │  (Hono)  │  │  (SQLite) │  │
│  └─────────┘  └─────────────┘  └──────────┘  └───────────┘  │
│                      │                │             │          │
│              ┌───────┴──────┐   ┌─────┴─────┐  ┌──┴───────┐  │
│              │  Vue 3 SPA   │   │  Dify AI  │  │ R2 Storage│  │
│              │  Tailwind CSS│   │  GPT-4    │  │ (Files)   │  │
│              │  Leaflet.js  │   │  Pinecone │  │           │  │
│              └──────────────┘   └───────────┘  └──────────┘  │
└──────────────────────────────────────────────────────────────┘
    ''')
    
    doc.add_heading('2.2 技術架構', level=2)
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['層級', '技術', '說明']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    stack = [
        ('前端', 'Vue 3 + Tailwind CSS', '響應式 UI，CDN 載入'),
        ('前端', 'Leaflet.js', '互動校園地圖，GeoJSON 分區'),
        ('前端', 'evo-calendar.js', '智慧行事曆元件'),
        ('前端', 'Chart.js', '數據視覺化圖表'),
        ('前端', 'GSAP', '動畫效果引擎'),
        ('後端', 'Hono Framework', '輕量化 Edge Runtime Web 框架'),
        ('後端', 'TypeScript', '型別安全開發語言'),
        ('資料庫', 'Cloudflare D1', 'SQLite 分散式資料庫'),
        ('儲存', 'Cloudflare R2', 'S3 相容物件儲存'),
        ('快取', 'Cloudflare KV', '全球分散式鍵值存儲'),
        ('AI', 'Dify + GPT-4', 'RAG 工作流 + 企劃生成'),
        ('AI', 'Pinecone', '向量資料庫，法規索引'),
        ('安全', 'Cloudflare WAF', 'Web 應用防火牆'),
        ('安全', 'Turnstile', '人機驗證（替代 reCAPTCHA）'),
        ('通訊', 'LINE Notify / SMS', '推播通知與提醒'),
        ('通訊', 'Microsoft Graph API', 'Outlook 郵件整合'),
    ]
    for s in stack:
        add_table_row(table, s)
    
    doc.add_heading('2.3 部署架構', level=2)
    doc.add_paragraph('• 前端：Cloudflare Pages（自動 CI/CD 從 GitHub main 分支部署）')
    doc.add_paragraph('• 後端：Cloudflare Workers（Hono 框架，全球 Edge 節點）')
    doc.add_paragraph('• 資料庫：Cloudflare D1（SQLite，--local 開發模式）')
    doc.add_paragraph('• 版本控制：GitHub Repository（KY0126/SA-v3-）')
    
    doc.add_page_break()
    
    # ==================== CHAPTER 3: 功能需求 ====================
    doc.add_heading('第三章 功能需求規格', level=1)
    
    doc.add_heading('3.1 角色定義與權限', level=2)
    table = doc.add_table(rows=1, cols=4)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['角色', '代號', '主要權限', '特殊功能']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    roles = [
        ('課指組/處室', 'admin', '全系統管理、活動審核、報表匯出', 'SDGs 雷達圖、Funnel 漏斗圖'),
        ('社團幹部', 'officer', '社團管理、活動企劃、器材借用', '留存率曲線、滿意度圓餅'),
        ('指導教授', 'professor', '社團督導、評鑑、預算審核', '風險燈號、成長蛛網圖'),
        ('一般學生', 'student', '報名活動、借用器材、查詢資訊', '職能雷達圖、E-Portfolio'),
        ('資訊中心', 'it', '系統監控、權限管理、維護', 'CPU 熱圖、API 延遲、WAF 統計'),
    ]
    for r in roles:
        add_table_row(table, r)
    
    doc.add_heading('3.2 十大支柱模組', level=2)
    modules = [
        ('職能 E-Portfolio', '學生電子履歷，記錄社團經歷、職能標籤，支援 PDF 匯出'),
        ('AI 企劃生成器', '調用 Dify Workflow，參考法規自動生成活動企劃書與預算'),
        ('幹部證書自動化', '自動產生幹部證書，含數位簽章驗證碼，支援 PDF 下載'),
        ('器材盤點追蹤', '設備借用/歸還管理，LINE/SMS 到期提醒，自動信用扣分'),
        ('AI 智慧預審', 'RAG 引擎比對校內法規，標記合規風險，即時回饋'),
        ('場地活化大數據', '場地使用率分析、熱力圖、周轉率排行，Leaflet 地圖整合'),
        ('AI 申訴摘要', 'WebSocket 即時生成案件摘要與 AI 調解建議'),
        ('動態活動牆', '活動瀏覽/搜尋/報名，支援類別篩選與剩餘名額顯示'),
        ('數位時光膠囊', '社團移交文件封裝至 R2，雙重驗證解封'),
        ('全方位 2FA', 'TOTP + SMS 雙因子驗證，Google Authenticator 整合'),
    ]
    for i, (name, desc) in enumerate(modules, 1):
        doc.add_heading(f'3.2.{i} {name}', level=3)
        doc.add_paragraph(desc)
    
    doc.add_heading('3.3 三階段資源調度系統', level=2)
    doc.add_paragraph('場地預約採用三階段調度演算法：')
    
    doc.add_heading('3.3.1 第一階段：志願序配對', level=3)
    doc.add_paragraph('• Level 1：校方/處室（最高優先權）')
    doc.add_paragraph('• Level 2：社團/自治組織')
    doc.add_paragraph('• Level 3：一般社團/小組')
    doc.add_paragraph('系統以 Llama-3 進行意圖過濾，自動匹配最優場地配置。')
    
    doc.add_heading('3.3.2 第二階段：自主協商', level=3)
    doc.add_paragraph('• 衝突偵測後進入 Redis 計時協商')
    doc.add_paragraph('• 3 分鐘：GPT-4 介入提供 3 個替代方案')
    doc.add_paragraph('• 6 分鐘：強制關閉，雙方各扣 10 信用積分')
    doc.add_paragraph('• 支援 LINE 邀請私下調解')
    
    doc.add_heading('3.3.3 第三階段：官方核准', level=3)
    doc.add_paragraph('• RAG 法規比對，確認合規性')
    doc.add_paragraph('• 產出核准 PDF + TOTP QR Code')
    doc.add_paragraph('• 活動核准號 → 場地解鎖')
    doc.add_paragraph('• Deep Site Indexing of fju.edu.tw 相關連結')
    
    doc.add_page_break()
    
    # ==================== CHAPTER 4: 資料庫設計 ====================
    doc.add_heading('第四章 資料庫設計', level=1)
    
    doc.add_heading('4.1 ER Diagram 概述', level=2)
    doc.add_paragraph('本系統共包含 18 張資料表，採用 Cloudflare D1 (SQLite) 作為主要資料庫：')
    
    tables_info = [
        ('users', '使用者', 'student_id, name, email, phone, role, credit_score, google_oauth_id, two_factor_enabled, language'),
        ('clubs', '社團', 'name, category, description, advisor_id, president_id, member_count, established_year'),
        ('club_members', '社團成員', 'club_id, user_id, position, joined_at'),
        ('venues', '場地', 'name, location, capacity, status, equipment_list, latitude, longitude'),
        ('activities', '活動', 'title, description, club_id, venue_id, event_date, max_participants, status, ai_review_result'),
        ('activity_registrations', '活動報名', 'activity_id, user_id, status, check_in_at'),
        ('equipment', '設備', 'code, name, category, status, condition_note, purchase_date'),
        ('equipment_borrowings', '設備借用', 'equipment_id, borrower_id, borrow_date, expected_return_date, status'),
        ('reservations', '場地預約', 'user_id, venue_id, reservation_date, start_time, end_time, priority_level, stage'),
        ('conflicts', '衝突協商', 'reservation_a_id, reservation_b_id, venue_id, status, ai_suggestions'),
        ('credit_logs', '信用紀錄', 'user_id, action, points, reason'),
        ('notification_logs', '通知紀錄', 'user_id, title, message, channel, read'),
        ('certificates', '證書', 'user_id, club_id, position, term, certificate_code, digital_signature'),
        ('portfolio_entries', 'E-Portfolio', 'user_id, title, description, category, tags'),
        ('competency_scores', '職能分數', 'user_id, dimension, score'),
        ('ai_review_logs', 'AI 預審紀錄', 'target_type, target_id, risk_level, violations'),
        ('appeals', '申訴', 'appellant_id, appeal_type, subject, status, ai_summary'),
        ('time_capsules', '時光膠囊', 'club_id, created_by, term, r2_storage_key, status'),
    ]
    
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['資料表', '說明', '主要欄位']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    for t in tables_info:
        add_table_row(table, t)
    
    doc.add_page_break()
    
    # ==================== CHAPTER 5: API 規格 ====================
    doc.add_heading('第五章 API 介面規格', level=1)
    
    doc.add_heading('5.1 RESTful API 端點一覽', level=2)
    
    api_endpoints = [
        ('GET', '/api/users', '取得使用者列表'),
        ('POST', '/api/users', '新增使用者'),
        ('GET', '/api/users/:id', '取得特定使用者'),
        ('PUT', '/api/users/:id', '更新使用者'),
        ('DELETE', '/api/users/:id', '刪除使用者'),
        ('GET', '/api/clubs', '取得社團列表（支援 type/category/search 篩選）'),
        ('GET', '/api/clubs/stats', '取得社團統計數據'),
        ('GET', '/api/clubs/:id', '取得特定社團'),
        ('GET', '/api/venues', '取得場地列表'),
        ('GET', '/api/venues/:id/schedule', '取得場地時段排程'),
        ('GET', '/api/activities', '取得活動列表（支援 category/status/search 篩選）'),
        ('POST', '/api/activities', '新增活動'),
        ('POST', '/api/activities/:id/register', '報名活動'),
        ('GET', '/api/equipment', '取得設備列表'),
        ('POST', '/api/equipment/borrow', '借用設備'),
        ('POST', '/api/equipment/return', '歸還設備'),
        ('POST', '/api/equipment/remind', '發送歸還提醒'),
        ('GET', '/api/reservations', '取得預約列表'),
        ('POST', '/api/reservations', '新增場地預約'),
        ('POST', '/api/reservations/:id/negotiate', 'AI 協商建議'),
        ('POST', '/api/ai/pre-review', 'AI 預審'),
        ('POST', '/api/ai/generate-proposal', 'AI 企劃生成'),
        ('GET', '/api/credits/:userId', '查詢信用積分'),
        ('POST', '/api/credits/deduct', '扣除信用積分'),
        ('GET', '/api/calendar/events', '取得行事曆事件'),
        ('GET', '/api/notifications/:userId', '取得通知'),
        ('POST', '/api/certificates/generate', '生成證書'),
        ('GET', '/api/dashboard/stats/:role', '角色儀表板數據'),
        ('GET', '/api/conflicts', '取得衝突列表'),
        ('POST', '/api/conflicts/negotiate', '衝突協商'),
        ('GET', '/api/repairs', '取得報修列表'),
        ('POST', '/api/repairs', '新增報修'),
        ('GET', '/api/appeals', '取得申訴列表'),
        ('POST', '/api/appeals', '新增申訴'),
        ('GET', '/api/i18n/:lang', '取得語言包'),
        ('GET', '/api/health', '健康檢查'),
    ]
    
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['方法', '端點', '說明']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    for ep in api_endpoints:
        add_table_row(table, ep)
    
    doc.add_heading('5.2 AI Pre-review API 範例', level=2)
    doc.add_paragraph('Request:')
    doc.add_paragraph('POST /api/ai/pre-review\nContent-Type: application/json\n{\n  "activity_title": "攝影社春季外拍",\n  "participants": 50,\n  "venue_id": 3\n}')
    doc.add_paragraph('Response:')
    doc.add_paragraph('{\n  "allow_next_step": true,\n  "risk_level": "Low",\n  "violations": [],\n  "references": [],\n  "confidence": 0.95,\n  "reviewed_regulations": ["活動申請辦法 v2.1", "場地使用管理規則 v3.0"]\n}')
    
    doc.add_page_break()
    
    # ==================== CHAPTER 6: UI/UX 設計 ====================
    doc.add_heading('第六章 使用者介面設計', level=1)
    
    doc.add_heading('6.1 設計規範', level=2)
    doc.add_paragraph('• 圓角：12-15px (rounded-fju / rounded-fju-lg)')
    doc.add_paragraph('• 字型：微軟正黑體 (Microsoft JhengHei)')
    doc.add_paragraph('• 60-30-10 色彩比例規則')
    
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['用途', '色碼', '說明']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    colors = [
        ('主色（深藍）', '#002B5B', 'Header/Footer/標題'),
        ('輔色（深灰）', '#333333', '側邊欄'),
        ('背景', '#F4F6F8', '主要內容背景'),
        ('強調色（黃）', '#FFB800', '按鈕/彈窗/標籤'),
        ('成功（綠）', '#008000', '狀態正常'),
        ('錯誤（紅）', '#FF0000', '警告/錯誤'),
        ('金色', '#DAA520', '登入/信用條'),
    ]
    for c in colors:
        add_table_row(table, c)
    
    doc.add_heading('6.2 頁面架構', level=2)
    doc.add_paragraph('• 雙層 Header：上層白底（Logo + 校名 + 連結），下層深藍（導覽列 + 搜尋）')
    doc.add_paragraph('• Sticky 左側邊欄 (30%)：深灰底，含信用積分儀表、4 大選單群組、系統狀態')
    doc.add_paragraph('• 主內容區 (70%)：Leaflet 校園地圖 + 行事曆面板 + 資料表格')
    doc.add_paragraph('• Footer：深藍底，校址 + 社群連結 + 聯絡資訊')
    doc.add_paragraph('• AI 聊天機器人：固定右下角，柴犬輔寶頭像，黃色 FAB 按鈕')
    
    doc.add_heading('6.3 互動校園地圖', level=2)
    doc.add_paragraph('• 以淨心堂為中心 (25.0363, 121.4318)，使用 L.tileLayer + GeoJSON 分區')
    doc.add_paragraph('• 四個可切換 LayerGroup：教學行政區、無障礙設施、生活設施、交通設施')
    doc.add_paragraph('• Popup 格式：12px 圓角，深藍 header，顯示建築名稱/學院/無障礙清單/容量')
    doc.add_paragraph('• 黃色「我要預約」按鈕直接跳轉場地預約頁面')
    doc.add_paragraph('• 支援 flyTo 動態縮放與高亮效果')
    
    doc.add_page_break()
    
    # ==================== CHAPTER 7: 流程圖 ====================
    doc.add_heading('第七章 核心流程圖', level=1)
    
    doc.add_heading('7.1 全域系統架構流程 (Mermaid)', level=2)
    doc.add_paragraph('''graph TB
    User[使用者] --> CloudflareWAF[Cloudflare WAF/Turnstile]
    CloudflareWAF --> CloudflarePages[Cloudflare Pages]
    CloudflarePages --> HonoWorker[Hono Worker API]
    HonoWorker --> D1DB[(D1 Database)]
    HonoWorker --> DifyAI[Dify AI Platform]
    HonoWorker --> R2Storage[(R2 Storage)]
    DifyAI --> GPT4[GPT-4 Model]
    DifyAI --> Pinecone[(Pinecone Vector DB)]
    HonoWorker --> LINENotify[LINE Notify]
    HonoWorker --> SMS[SMS Gateway]
    HonoWorker --> SMTP[SMTP Server]''')
    
    doc.add_heading('7.2 三階段資源調度流程', level=2)
    doc.add_paragraph('''graph LR
    A[使用者提交預約] --> B{志願序配對}
    B -->|無衝突| C[自動通過 → 第三階段]
    B -->|有衝突| D[進入協商]
    D --> E{3分鐘}
    E -->|未解決| F[GPT-4 介入建議]
    F --> G{6分鐘}
    G -->|未解決| H[強制關閉 + 扣10分]
    G -->|已解決| I[協商成功]
    E -->|已解決| I
    C --> J[RAG 法規比對]
    I --> J
    J --> K{合規?}
    K -->|是| L[核准 + PDF + QR Code]
    K -->|否| M[退回修改]''')
    
    doc.add_heading('7.3 角色泳道流程（登入至簽到）', level=2)
    doc.add_paragraph('''graph TD
    subgraph 登入流程
        Login[Google OAuth @cloud.fju.edu.tw] --> Auth[JWT 驗證]
        Auth --> TwoFA[2FA 雙因子驗證]
        TwoFA --> CreditCheck{信用積分 >= 60?}
        CreditCheck -->|是| Dashboard[進入 Dashboard]
        CreditCheck -->|否| ForceLogout[強制登出 INVALIDATE_JWT]
    end
    subgraph 操作流程
        Dashboard --> SelectModule[選擇功能模組]
        SelectModule --> AIReview[AI 預審檢查]
        AIReview --> Process[執行操作]
        Process --> Notify[推播通知 LINE/SMS]
    end''')
    
    doc.add_heading('7.4 Dify RAG 工作流', level=2)
    doc.add_paragraph('''graph LR
    Query[使用者查詢] --> GuzzleHTTP[GuzzleHTTP Request]
    GuzzleHTTP --> DifyAPI[Dify API Endpoint]
    DifyAPI --> Embedding[文本嵌入]
    Embedding --> PineconeSearch[Pinecone 向量搜尋]
    PineconeSearch --> TopK[Top-K 相關法規]
    TopK --> GPT4Gen[GPT-4 生成回覆]
    GPT4Gen --> Response[回傳結構化結果]''')
    
    doc.add_heading('7.5 安全身分生命週期', level=2)
    doc.add_paragraph('''graph TB
    Register[使用者註冊 @cloud.fju.edu.tw] --> GoogleOAuth[Google OAuth 驗證]
    GoogleOAuth --> SetupTwoFA[設定 2FA TOTP/SMS]
    SetupTwoFA --> IssueJWT[核發 JWT HttpOnly Cookie]
    IssueJWT --> Active[活躍狀態]
    Active --> Monitor[信用積分監控]
    Monitor -->|< 60 分| Invalidate[INVALIDATE_JWT 強制登出]
    Monitor -->|>= 60 分| Active
    Active --> Logout[使用者登出]
    Active --> SessionExpiry[Session 過期]''')
    
    doc.add_page_break()
    
    # ==================== CHAPTER 8: 社團/學會資料 ====================
    doc.add_heading('第八章 114 學年度社團與學會資料', level=1)
    
    doc.add_heading('8.1 社團統計', level=2)
    doc.add_paragraph(f'• 社團總數：71 個')
    doc.add_paragraph(f'• 學會總數：46 個')
    doc.add_paragraph(f'• 合計：117 個社團/學會組織')
    doc.add_paragraph(f'• 總社員數：約 4,482 人')
    
    doc.add_heading('8.2 社團分類一覽', level=2)
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['類別', '數量', '代表社團']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    categories = [
        ('學藝性', '14', '電腦資訊研究社、辯論社、英語會話社'),
        ('康樂性', '12', '吉他社、合唱團、動漫研究社'),
        ('體育性', '20', '籃球社、排球社、登山社'),
        ('藝文性', '9', '攝影社、話劇社、熱門舞蹈社'),
        ('服務性', '8', '環保社、國際志工社、紅十字青年服務隊'),
        ('宗教性', '3', '天主教大專同學會、佛學社'),
        ('自治性', '5', '學生會、學生議會、畢聯會'),
    ]
    for c in categories:
        add_table_row(table, c)
    
    doc.add_heading('8.3 學會分類一覽', level=2)
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['學院', '學會數', '學會名稱']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    faculties = [
        ('文學院', '3', '中國文學系學會、歷史學系學會、哲學系學會'),
        ('醫學院', '6', '醫學系學會、護理學系學會、公共衛生學系學會等'),
        ('理工學院', '6', '數學系學會、資訊工程學系學會、電機工程學系學會等'),
        ('外語學院', '6', '英國語文學系學會、日本語文學系學會等'),
        ('管理學院', '5', '企業管理學系學會、會計學系學會、資訊管理學系學會等'),
        ('民生學院', '4', '兒童與家庭學系學會、餐旅管理學系學會等'),
        ('社會科學院', '4', '社會學系學會、社會工作學系學會、經濟學系學會等'),
        ('傳播學院', '3', '大眾傳播學系學會、新聞傳播學系學會、廣告傳播學系學會'),
        ('藝術學院', '3', '音樂學系學會、應用美術學系學會、景觀設計學系學會'),
        ('教育學院', '3', '體育學系學會、圖書資訊學系學會等'),
        ('法律學院', '2', '法律學系學會、財經法律學系學會'),
        ('織品學院', '1', '織品服裝學系學會'),
    ]
    for f in faculties:
        add_table_row(table, f)
    
    doc.add_page_break()
    
    # ==================== CHAPTER 9: 安全設計 ====================
    doc.add_heading('第九章 安全設計', level=1)
    
    doc.add_heading('9.1 認證機制', level=2)
    doc.add_paragraph('• Google OAuth 2.0（僅限 @cloud.fju.edu.tw 帳號）')
    doc.add_paragraph('• JWT Token（HttpOnly Cookie，防 XSS）')
    doc.add_paragraph('• TOTP 雙因子驗證（Google Authenticator / Authy）')
    doc.add_paragraph('• SMS 備援驗證')
    
    doc.add_heading('9.2 防護措施', level=2)
    doc.add_paragraph('• Cloudflare WAF（Skip Rules for @cloud.fju.edu.tw）')
    doc.add_paragraph('• Cloudflare Turnstile（人機驗證）')
    doc.add_paragraph('• R2 儲存壓縮 + GPS 浮水印')
    doc.add_paragraph('• 信用積分制度：低於 60 分觸發 INVALIDATE_JWT')
    
    doc.add_heading('9.3 信用積分狀態機', level=2)
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['事件', '積分變動', '觸發動作']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    events = [
        ('按時歸還器材', '+5', '系統通知'),
        ('完成社團評鑑', '+10', '系統通知'),
        ('遲到簽到', '-5', '扣分通知'),
        ('協商超時未回應', '-10', 'AI 介入 + 扣分'),
        ('積分低於 60', '-', '強制登出 (INVALIDATE_JWT)'),
    ]
    for e in events:
        add_table_row(table, e)
    
    doc.add_page_break()
    
    # ==================== CHAPTER 10: 測試計畫 ====================
    doc.add_heading('第十章 測試計畫', level=1)
    
    doc.add_heading('10.1 測試項目', level=2)
    table = doc.add_table(rows=1, cols=4)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['測試類型', '工具', '範圍', '通過標準']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    tests = [
        ('單元測試', 'Vitest', 'API 路由、資料處理', '覆蓋率 > 80%'),
        ('整合測試', 'Playwright', '頁面導航、表單提交', '全部通過'),
        ('效能測試', 'Lighthouse', '載入速度、LCP', '分數 > 90'),
        ('安全測試', 'OWASP ZAP', 'SQL 注入、XSS', '無高風險漏洞'),
        ('無障礙測試', 'axe-core', 'WCAG 2.1 AA', '無嚴重違規'),
    ]
    for t in tests:
        add_table_row(table, t)
    
    doc.add_heading('10.2 驗收標準', level=2)
    doc.add_paragraph('• 所有 17 個頁面路由可正常存取')
    doc.add_paragraph('• 所有 36 個 API 端點回傳正確資料')
    doc.add_paragraph('• 五種角色皆可登入並操作對應功能')
    doc.add_paragraph('• 互動地圖 4 個 LayerGroup 皆可切換')
    doc.add_paragraph('• AI 企劃生成器、RAG 法規查詢、預審功能正常')
    doc.add_paragraph('• 場地預約三階段流程完整可走')
    doc.add_paragraph('• 信用積分扣分/加分邏輯正確')
    
    doc.add_page_break()
    
    # ==================== CHAPTER 11: 附錄 ====================
    doc.add_heading('第十一章 附錄', level=1)
    
    doc.add_heading('11.1 系統頁面一覽', level=2)
    pages = [
        ('/', '首頁 Landing Page', '公開'),
        ('/login', '登入頁面', '公開'),
        ('/dashboard', 'Dashboard 儀表板', '需登入'),
        ('/campus-map', '互動校園分區地圖', '需登入'),
        ('/module/venue-booking', '場地預約', '需登入'),
        ('/module/equipment', '設備借用', '需登入'),
        ('/module/calendar', '行事曆', '需登入'),
        ('/module/club-info', '社團資訊', '需登入'),
        ('/module/activity-wall', '活動牆', '需登入'),
        ('/module/ai-overview', 'AI 資訊概覽', '需登入'),
        ('/module/ai-guide', 'AI 導覽助理', '需登入'),
        ('/module/rag-search', '法規查詢 (RAG)', '需登入'),
        ('/module/repair', '報修管理', '需登入'),
        ('/module/appeal', '申訴記錄', '需登入'),
        ('/module/reports', '統計報表', '需登入'),
        ('/module/e-portfolio', '職能 E-Portfolio', '需登入'),
        ('/module/certificate', '幹部證書', '需登入'),
    ]
    table = doc.add_table(rows=1, cols=3)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['路由', '頁面名稱', '權限']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    for p in pages:
        add_table_row(table, p)
    
    doc.add_heading('11.2 變更紀錄', level=2)
    table = doc.add_table(rows=1, cols=4)
    table.style = 'Table Grid'
    hdr = table.rows[0].cells
    for i, h in enumerate(['版本', '日期', '作者', '變更內容']):
        hdr[i].text = h
        set_cell_shading(hdr[i], '002B5B')
        for r in hdr[i].paragraphs[0].runs:
            r.font.color.rgb = RGBColor(255, 255, 255)
            r.bold = True
    
    changes = [
        ('1.0.0', '2026-04-03', '開發團隊', '初始版本，建立基礎架構'),
        ('2.0.0', '2026-04-05', '開發團隊', '完成全部模組、互動地圖、雙層 Header'),
        ('2.1.0', '2026-04-07', '開發團隊', '114 學年度社團/學會資料整合、所有功能互動化、SA 文件產出'),
    ]
    for c in changes:
        add_table_row(table, c)
    
    # Save
    output_path = '/home/user/webapp/FJU_Smart_Hub_SA_Specification.docx'
    doc.save(output_path)
    print(f'SA 規格書已生成：{output_path}')
    return output_path

if __name__ == '__main__':
    generate_sa_document()
