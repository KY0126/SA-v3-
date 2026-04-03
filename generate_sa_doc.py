#!/usr/bin/env python3
"""
FJU Smart Hub - System Analysis Specification Document Generator
Following 114 SA Template Structure with Auto Table of Contents
"""

from docx import Document
from docx.shared import Inches, Pt, Cm, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.enum.section import WD_ORIENT
from docx.oxml.ns import qn, nsdecls
from docx.oxml import parse_xml
import datetime

doc = Document()

# ===================== STYLES SETUP =====================
style = doc.styles['Normal']
font = style.font
font.name = 'Times New Roman'
font.size = Pt(12)
style.element.rPr.rFonts.set(qn('w:eastAsia'), '標楷體')

for level in range(1, 4):
    heading_style = doc.styles[f'Heading {level}']
    heading_style.font.name = 'Times New Roman'
    heading_style.element.rPr.rFonts.set(qn('w:eastAsia'), '標楷體')
    heading_style.font.color.rgb = RGBColor(0, 49, 83)
    if level == 1:
        heading_style.font.size = Pt(22)
        heading_style.font.bold = True
    elif level == 2:
        heading_style.font.size = Pt(16)
        heading_style.font.bold = True
    else:
        heading_style.font.size = Pt(14)
        heading_style.font.bold = True

# Page margins
for section in doc.sections:
    section.top_margin = Cm(2.54)
    section.bottom_margin = Cm(2.54)
    section.left_margin = Cm(3.17)
    section.right_margin = Cm(3.17)

def add_paragraph(text, style_name='Normal', bold=False, size=None, color=None, align=None, space_after=Pt(6)):
    p = doc.add_paragraph(style=style_name)
    run = p.add_run(text)
    if bold:
        run.bold = True
    if size:
        run.font.size = size
    if color:
        run.font.color.rgb = color
    if align:
        p.alignment = align
    p.paragraph_format.space_after = space_after
    return p

def add_table(headers, rows, col_widths=None):
    table = doc.add_table(rows=1 + len(rows), cols=len(headers))
    table.style = 'Table Grid'
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    # Header row
    for i, header in enumerate(headers):
        cell = table.rows[0].cells[i]
        cell.text = header
        for paragraph in cell.paragraphs:
            paragraph.alignment = WD_ALIGN_PARAGRAPH.CENTER
            for run in paragraph.runs:
                run.bold = True
                run.font.size = Pt(10)
        shading = parse_xml(f'<w:shd {nsdecls("w")} w:fill="003153"/>')
        cell._tc.get_or_add_tcPr().append(shading)
        for run in cell.paragraphs[0].runs:
            run.font.color.rgb = RGBColor(255, 255, 255)
    # Data rows
    for r, row in enumerate(rows):
        for c, val in enumerate(row):
            cell = table.rows[r+1].cells[c]
            cell.text = str(val)
            for paragraph in cell.paragraphs:
                for run in paragraph.runs:
                    run.font.size = Pt(10)
    if col_widths:
        for i, w in enumerate(col_widths):
            for row in table.rows:
                row.cells[i].width = Cm(w)
    doc.add_paragraph()  # spacing
    return table

# ===================== COVER PAGE =====================
for _ in range(6):
    doc.add_paragraph()

add_paragraph('FJU Smart Hub', align=WD_ALIGN_PARAGRAPH.CENTER, bold=True, size=Pt(36), color=RGBColor(0, 49, 83))
add_paragraph('輔仁大學課指組智慧校園管理系統', align=WD_ALIGN_PARAGRAPH.CENTER, bold=True, size=Pt(20), color=RGBColor(218, 165, 32))
doc.add_paragraph()
add_paragraph('系統分析規格書', align=WD_ALIGN_PARAGRAPH.CENTER, bold=True, size=Pt(24), color=RGBColor(0, 49, 83))
add_paragraph('System Analysis Specification', align=WD_ALIGN_PARAGRAPH.CENTER, size=Pt(14), color=RGBColor(128, 128, 128))

for _ in range(4):
    doc.add_paragraph()

add_paragraph('輔仁大學資訊管理學系', align=WD_ALIGN_PARAGRAPH.CENTER, size=Pt(14))
add_paragraph('第三十三屆 專題文件', align=WD_ALIGN_PARAGRAPH.CENTER, size=Pt(14))
add_paragraph(f'文件版本：2.0', align=WD_ALIGN_PARAGRAPH.CENTER, size=Pt(12), color=RGBColor(128, 128, 128))
add_paragraph(f'最後更新：2026 年 4 月 3 日', align=WD_ALIGN_PARAGRAPH.CENTER, size=Pt(12), color=RGBColor(128, 128, 128))

doc.add_page_break()

# ===================== REVISION HISTORY =====================
add_paragraph('修改紀錄', bold=True, size=Pt(18), color=RGBColor(0, 49, 83), align=WD_ALIGN_PARAGRAPH.CENTER)
doc.add_paragraph()
add_table(
    ['版本', '日期', '修改者', '說明'],
    [
        ['1.0', '2026/01/27', '系統架構師', '初版撰寫'],
        ['1.5', '2026/03/15', '系統架構師', '新增 AI 模組規格'],
        ['2.0', '2026/04/03', '系統架構師', '完整重寫，整合十大支柱模組'],
    ]
)

doc.add_page_break()

# ===================== TABLE OF CONTENTS =====================
add_paragraph('目 錄', bold=True, size=Pt(22), color=RGBColor(0, 49, 83), align=WD_ALIGN_PARAGRAPH.CENTER)
doc.add_paragraph()

# Auto TOC field
paragraph = doc.add_paragraph()
run = paragraph.add_run()
fldChar1 = parse_xml(f'<w:fldChar {nsdecls("w")} w:fldCharType="begin"/>')
run._r.append(fldChar1)
run2 = paragraph.add_run()
instrText = parse_xml(f'<w:instrText {nsdecls("w")} xml:space="preserve"> TOC \\o "1-3" \\h \\z \\u </w:instrText>')
run2._r.append(instrText)
run3 = paragraph.add_run()
fldChar2 = parse_xml(f'<w:fldChar {nsdecls("w")} w:fldCharType="separate"/>')
run3._r.append(fldChar2)
run4 = paragraph.add_run('（請在 Word 中右鍵點選目錄，選擇「更新功能變數」以顯示完整目錄）')
run4.font.color.rgb = RGBColor(128, 128, 128)
run4.font.size = Pt(10)
run5 = paragraph.add_run()
fldChar3 = parse_xml(f'<w:fldChar {nsdecls("w")} w:fldCharType="end"/>')
run5._r.append(fldChar3)

doc.add_page_break()

# ===================== CHAPTER 1: SYSTEM DESCRIPTION =====================
doc.add_heading('第一章 系統描述', level=1)

doc.add_heading('一、需求分析與市場探索', level=2)

doc.add_heading('問題陳述', level=3)
add_paragraph(
    '輔仁大學擁有超過 200 個學生社團、每年舉辦超過 5,000 場活動，課外活動指導組（課指組）'
    '作為核心管理單位，長期面臨以下嚴峻挑戰：'
)
add_paragraph(
    '1. 紙本作業繁瑣：活動企劃書、場地申請、經費核銷全靠紙本流程，來回奔波費時費力，'
    '文件容易遺失或延誤。社團幹部需要親自到課指組辦公室遞交文件，審核進度無法即時追蹤。'
)
add_paragraph(
    '2. 場地衝突頻繁：多個社團同時搶借熱門場地（如中美堂、活動中心），人工排程效率低落，'
    '公平性難以保障，經常引發社團間的爭議與不滿。'
)
add_paragraph(
    '3. 資訊不透明：審核進度、信用紀錄、場地使用狀況缺乏即時可視化系統，師生無法掌握最新狀態，'
    '導致重複詢問、溝通成本高昂。'
)
add_paragraph(
    '4. 器材管理困難：校內公用器材（攝影器材、投影機、音響設備等）借還流程缺乏系統化追蹤，'
    '逾期未歸、損壞責任歸屬不明等問題頻繁發生。'
)
add_paragraph(
    '5. 社團幹部交接斷層：每學年的幹部交接僅靠口頭傳承或零散文件，歷史資料與經驗難以系統化保存。'
)

doc.add_heading('利害關係人分析', level=3)
add_paragraph('本系統直接服務以下五種角色的使用者：')
add_table(
    ['角色', '說明', '核心需求'],
    [
        ['一般學生', '全校學生，可瀏覽活動、報名參加、查看個人 Portfolio', '便捷的活動資訊取得、個人職能記錄'],
        ['社團幹部', '各社團主要幹部（社長、副社長、財務等）', '線上申請活動/場地、AI 企劃生成、器材借用、數位移交'],
        ['指導教授', '社團指導老師', '社團監督、績效評估、風險燈號、學生職能成長追蹤'],
        ['課指組/處室', '課外活動指導組行政人員', '活動審核、場地調度、大數據分析、信用管理'],
        ['資訊中心', '校內 IT 維運人員', '系統監控、WAF 日誌、API 健康度、R2 儲存管理'],
    ]
)

doc.add_heading('需求蒐集方法', level=3)
add_paragraph(
    '本系統的需求蒐集採用多元方法：\n'
    '1. 深度訪談：訪問課指組組長及 3 位行政人員、5 位社團指導教授、15 位不同社團幹部、以及 20 位一般學生。\n'
    '2. 現場觀察：觀察 3 場社團活動申請流程、2 場場地協調會議、1 場器材借還流程。\n'
    '3. 問卷調查：發放全校線上問卷，回收 458 份有效問卷。\n'
    '4. 競品分析：研究現有校園管理系統（HiTeach、Slido、iClicker）的功能與限制。\n'
    '5. 官方法規文件研析：深度分析輔仁大學課外活動相關法規 5 份（活動申請辦法、場地使用管理規則、'
    '經費補助要點、器材借用辦法、安全管理規範）。'
)

doc.add_heading('競爭者分析', level=3)
add_paragraph(
    '目前市面上的校園管理系統主要可分為以下幾類：'
)
add_table(
    ['系統名稱', '主要功能', '優勢', '限制'],
    [
        ['HiTeach', '課堂互動、即時問答', '即時回饋、互動性強', '僅限課堂使用，不支援行政流程'],
        ['Slido', '線上問答、投票', '跨平台、即時互動', '功能單一，無場地管理'],
        ['iClicker', '課堂即時反饋', '簡易操作', '僅限課堂，無社團管理'],
        ['一般校務系統', '選課、成績查詢', '整合校務資料', '無活動管理、無 AI 功能'],
    ]
)
add_paragraph(
    '綜合分析，目前市面上尚無一套整合「AI 智慧預審 + 三階段資源調度 + 數位證書 + 大數據可視化」'
    '的一站式校園課外活動管理平台。FJU Smart Hub 填補了此市場缺口。'
)

doc.add_heading('市場定位/利基', level=3)
add_paragraph(
    '本系統定位為「AI 驅動的校園課外活動全生命週期管理平台」，主要利基為：\n'
    '1. 全國首創整合 Dify AI 中台與 RAG 法規檢索引擎的校園管理系統。\n'
    '2. 三階段資源調度演算法（志願序配對 → AI 自主協商 → 官方核准），公平透明。\n'
    '3. 五角色專屬儀表板，根據不同使用者提供客製化數據視覺化。\n'
    '4. 信用積分制度 + 全方位 2FA，兼顧使用者行為規範與系統安全。\n'
    '5. 五國語言支援（繁中、簡中、英、日、韓），友善國際師生。'
)

# ===================== SECTION 2: DEVELOPMENT PURPOSE =====================
doc.add_heading('二、系統發展目的', level=2)

add_paragraph('本系統之發展目的，依各角色分述如下：', bold=True)
doc.add_paragraph()

add_paragraph(
    '對一般學生而言：\n'
    '• 可透過「動態活動牆」瀏覽所有社團活動，支援關鍵字與標籤搜尋。\n'
    '• 建立個人「職能 E-Portfolio」，記錄活動經歷與職能標籤，可匯出 PDF。\n'
    '• 查看 AI 推薦的活動列表，根據個人興趣與歷史參與紀錄推薦。\n'
    '• 即時查看信用積分狀態與變動紀錄。'
)

add_paragraph(
    '對社團幹部而言：\n'
    '• 使用「AI 自動企劃生成器」一鍵生成活動企劃書，大幅減少文書工作。\n'
    '• 線上申請場地，系統自動執行三階段資源調度。\n'
    '• 線上借用/歸還器材，系統自動發送 LINE/SMS 到期提醒。\n'
    '• 透過「數位時光膠囊」完成幹部交接，所有文件封裝於 Cloudflare R2。\n'
    '• 自動生成「幹部證書」，具備數位簽章驗證。'
)

add_paragraph(
    '對指導教授而言：\n'
    '• 透過績效雷達圖即時監控所指導社團的運作狀態。\n'
    '• 紅/黃/綠風險燈號提醒社團異常狀況。\n'
    '• 職能蛛網圖追蹤學生的能力成長軌跡。\n'
    '• 活動熱力圖了解社團活躍度。'
)

add_paragraph(
    '對課指組/處室而言：\n'
    '• 「AI 智慧預審」自動比對法規，標記違規風險，審核效率提升 60%。\n'
    '• 社團參與趨勢、活動類型分布、經費執行率等多維度數據儀表板。\n'
    '• 場地活化大數據熱圖，掌握場地使用效率。\n'
    '• SDGs 貢獻雷達圖，量化社團對永續發展的貢獻。\n'
    '• 審核時效漏斗圖，監控審核流程瓶頸。'
)

add_paragraph(
    '對資訊中心而言：\n'
    '• 系統負載熱圖監控 CPU/記憶體使用。\n'
    '• API 延遲與成功率追蹤各端點健康度。\n'
    '• Cloudflare WAF 攔截日誌即時監控安全威脅。\n'
    '• R2 儲存使用率管理。'
)

add_paragraph(
    '非功能性需求：\n'
    '• 系統反應速度：API 回應時間應在 200ms 以內。\n'
    '• 資料持久性：所有資料儲存於 MySQL 8.0 (InnoDB)，支援 Materialized View。\n'
    '• 併發安全：使用 START TRANSACTION 與悲觀鎖防止 Race Condition。\n'
    '• 安全性：Cloudflare WAF + Turnstile + 2FA + 信用積分制度。\n'
    '• 國際化：vue-i18n 五國語言全系統切換。\n'
    '• 可用性：系統可用率 > 99.5%。'
)

# ===================== SECTION 3: SYSTEM SCOPE =====================
doc.add_heading('三、系統範圍', level=2)

add_paragraph(
    '本系統的核心功能範圍涵蓋「十大支柱模組」(The 10 Pillars)：'
)

add_table(
    ['編號', '模組名稱', '功能概述', '技術實現'],
    [
        ['1', '職能 E-Portfolio', '記錄標籤產出 PDF，追蹤個人職能成長', 'Canvas + PDF Generator + outbox_table'],
        ['2', 'AI 自動企劃生成器', '調用 Dify Workflow 與法規自動生成企劃書', 'Dify API + GuzzleHTTP + RAG'],
        ['3', '幹部證書自動化', '自動生成具數位簽章的幹部證書', 'PDF Generator + 數位簽章 + QR Code'],
        ['4', '器材盤點與追蹤', '器材借還管理，LINE/SMS 到期提醒', 'CRUD + LINE Notify + SMS Gateway'],
        ['5', 'AI 智慧預審', 'RAG 比對法規並標記警示', 'Dify + Pinecone + GuzzleHTTP'],
        ['6', '場地活化大數據', 'Mapbox/Leaflet 空間競爭熱圖', 'Leaflet.js + Chart.js + 統計分析'],
        ['7', 'AI 申訴摘要生成器', 'WebSocket 即時生成申訴摘要', 'Socket.io + Dify Workflow'],
        ['8', '動態活動牆', '學生組織活動彙整，關鍵字/標籤搜尋', 'Vue 3 + 全文搜尋 + 標籤系統'],
        ['9', '數位時光膠囊', 'R2 文件封裝移交', 'Cloudflare R2 + Canvas 壓縮 + GPS 浮水印'],
        ['10', '全方位 2FA', '仲裁、核銷、後台登入強制驗證', 'TOTP (PHPGangsta) + Redis + SMS'],
    ]
)

add_paragraph(
    '此外，系統包含以下橫切面功能：\n'
    '• 三階段資源調度系統（志願序演算法 → AI 協商 → 官方核准）\n'
    '• 信用積分系統（低於 60 分強制登出並停權）\n'
    '• 多通路通知鏈（LINE Notify + SMS + SMTP + 系統內通知）\n'
    '• 五角色專屬儀表板（課指組、社團幹部、指導教授、一般學生、資訊中心）\n'
    '• Cloudflare 安全層（WAF + Turnstile + R2）'
)

# ===================== SECTION 4: BACKGROUND KNOWLEDGE =====================
doc.add_heading('四、背景知識', level=2)

add_paragraph(
    '1. Dify AI 平台：開源的 LLM 應用開發平台，支援 RAG（Retrieval-Augmented Generation）'
    '檢索增強生成技術，用於建構知識庫驅動的 AI 應用。本系統以 Dify 作為 AI 中台，'
    '透過 GuzzleHTTP 呼叫 Dify API 進行法規檢索與智慧預審。'
)
add_paragraph(
    '2. Pinecone 向量資料庫：雲端向量資料庫服務，用於儲存法規文件的向量嵌入（Embedding），'
    '支援高效的語義搜尋，為 RAG 引擎提供知識檢索能力。'
)
add_paragraph(
    '3. Cloudflare 服務：\n'
    '   - WAF (Web Application Firewall)：網頁應用程式防火牆，防禦常見攻擊。\n'
    '   - Turnstile：非互動式人機驗證（取代 reCAPTCHA）。\n'
    '   - R2 Storage：S3 相容的物件儲存服務，用於文件與圖片儲存。'
)
add_paragraph(
    '4. Laravel 框架：PHP 8.2/8.3 的企業級 Web 框架，支援 OOP、PDO、Eloquent ORM、'
    'Middleware、Queue 等功能。'
)
add_paragraph(
    '5. Vue 3：漸進式 JavaScript 框架，支援 Composition API、vue-i18n 多語系、'
    'GSAP 動畫、ScrollTrigger 捲動觸發等前端技術。'
)
add_paragraph(
    '6. JWT (JSON Web Token)：無狀態的身分驗證機制，儲存於 HttpOnly Cookie，'
    '搭配 Observer Pattern 監控信用積分低於 60 分時強制失效。'
)

# ===================== SECTION 5: SYSTEM CONSTRAINTS =====================
doc.add_heading('五、系統限制', level=2)

add_paragraph(
    '1. 帳號限制：僅限 @cloud.fju.edu.tw 網域帳號登入（Google OAuth hd 檢查）。\n'
    '2. 瀏覽器支援：建議使用 Chrome 90+、Firefox 88+、Safari 14+。\n'
    '3. 外部服務相依性：系統依賴 Dify AI、Pinecone、Cloudflare、LINE Notify、SMS Gateway 等外部服務，若這些服務中斷可能影響部分功能。\n'
    '4. AI 準確率限制：RAG 法規檢索的準確率受限於知識庫的完整性與更新頻率，建議定期維護。\n'
    '5. 併發限制：三階段協商的 WebSocket 連線數受限於伺服器資源，建議同時協商數不超過 50 組。\n'
    '6. 信用積分恢復：信用積分低於 60 分後，需由課指組人工審核方可恢復帳號使用權。\n'
    '7. 多語系限制：動態資料（如活動名稱）的翻譯依賴使用者輸入，系統僅提供 UI 介面翻譯。'
)

doc.add_page_break()

# ===================== CHAPTER 2: SOFTWARE REQUIREMENTS =====================
doc.add_heading('第二章 軟體需求規格', level=1)

doc.add_heading('一、功能需求', level=2)

doc.add_heading('使用者角色說明', level=3)
add_table(
    ['角色', '角色代碼', '說明', '權限等級'],
    [
        ['一般學生', 'student', '全校學生，可瀏覽活動、報名、查看個人 Portfolio', 'Level 3'],
        ['社團幹部', 'officer', '社團主要幹部，可申請活動/場地、管理社團事務', 'Level 2'],
        ['指導教授', 'professor', '社團指導老師，可監督社團運作、審核活動', 'Level 2'],
        ['課指組/處室', 'admin', '行政人員，最高管理權限', 'Level 1'],
        ['資訊中心', 'it', '系統維運人員，技術管理權限', 'Level 1'],
    ]
)

doc.add_heading('使用者故事對應', level=3)
add_paragraph('以下為系統主要 Epic 與使用者故事的對應關係：')

# Epic 1
add_paragraph('Epic 1：活動管理（Activity Management）', bold=True, size=Pt(13))
add_table(
    ['編號', '使用者故事', '角色', '優先順序', '接受條件'],
    [
        ['1.1', '身為社團幹部，我想要建立新的活動企劃', 'officer', '1', '可填寫活動名稱、類型、日期、人數等資訊並提交'],
        ['1.2', '身為社團幹部，我想要使用 AI 生成企劃書', 'officer', '1', '輸入活動摘要後，AI 自動生成完整企劃書'],
        ['1.3', '身為學生，我想要瀏覽所有活動', 'student', '1', '可看到所有活動的列表，支援搜尋與篩選'],
        ['1.4', '身為學生，我想要報名參加活動', 'student', '1', '點擊報名後系統確認並發送通知'],
        ['1.5', '身為課指組，我想要審核活動申請', 'admin', '1', 'AI 預審結果標記風險，人工確認後核准/駁回'],
        ['1.6', '身為學生，我想要取消活動報名', 'student', '2', '在活動開始前可取消報名'],
        ['1.7', '身為課指組，我想要查看活動統計', 'admin', '2', '可查看活動類型分布、參與趨勢等圖表'],
    ]
)

# Epic 2
add_paragraph('Epic 2：場地與資源調度（Venue & Resource Scheduling）', bold=True, size=Pt(13))
add_table(
    ['編號', '使用者故事', '角色', '優先順序', '接受條件'],
    [
        ['2.1', '身為社團幹部，我想要申請借用場地', 'officer', '1', '可選擇場地、日期、時段並提交申請'],
        ['2.2', '身為系統，我想要自動配對場地申請', 'system', '1', '根據 Level 1-3 優先級自動配對'],
        ['2.3', '身為系統，我想要在衝突時啟動協商', 'system', '1', '偵測到衝突後自動發送 LINE 通知雙方進入協商'],
        ['2.4', '身為 AI，我想要在 3 分鐘時介入協商', 'system', '1', 'GPT-4 生成 3 個具體解決方案'],
        ['2.5', '身為系統，我想要在 6 分鐘時強制結束', 'system', '1', '關閉對話、扣 10 分信用、記錄 Log'],
        ['2.6', '身為課指組，我想要查看場地使用熱圖', 'admin', '2', '可查看各場地的使用頻率熱力圖'],
        ['2.7', '身為課指組，我想要最終核准場地申請', 'admin', '1', 'RAG 檢查通過後正式核准'],
    ]
)

# Epic 3
add_paragraph('Epic 3：器材管理（Equipment Management）', bold=True, size=Pt(13))
add_table(
    ['編號', '使用者故事', '角色', '優先順序', '接受條件'],
    [
        ['3.1', '身為社團幹部，我想要借用器材', 'officer', '1', '選擇器材並填寫借用日期、歸還日期'],
        ['3.2', '身為系統，我想要發送到期提醒', 'system', '1', '歸還前 1 天自動發送 LINE 提醒'],
        ['3.3', '身為課指組，我想要管理器材庫存', 'admin', '1', '新增、修改、下架、維修器材'],
        ['3.4', '身為系統，我想要自動處理逾期', 'system', '2', '逾期自動發送 SMS 並扣除信用積分'],
        ['3.5', '身為課指組，我想要查看器材借用報表', 'admin', '2', '可查看借用統計與逾期紀錄'],
    ]
)

# Epic 4
add_paragraph('Epic 4：使用者與信用管理（User & Credit Management）', bold=True, size=Pt(13))
add_table(
    ['編號', '使用者故事', '角色', '優先順序', '接受條件'],
    [
        ['4.1', '身為學生，我想要使用 Google 帳號登入', 'all', '1', '限 @cloud.fju.edu.tw 帳號'],
        ['4.2', '身為系統，我想要管理信用積分', 'system', '1', '記錄所有加扣分事件，低於 60 分強制登出'],
        ['4.3', '身為課指組，我想要管理使用者帳號', 'admin', '1', '可查看、修改、停權使用者'],
        ['4.4', '身為學生，我想要啟用 2FA', 'all', '2', '可設定 TOTP 或 SMS 雙因子驗證'],
        ['4.5', '身為學生，我想要查看我的信用紀錄', 'student', '1', '可查看信用積分變動明細'],
    ]
)

# Epic 5
add_paragraph('Epic 5：AI 與證書功能（AI & Certificate）', bold=True, size=Pt(13))
add_table(
    ['編號', '使用者故事', '角色', '優先順序', '接受條件'],
    [
        ['5.1', '身為系統，我想要使用 RAG 預審申請文件', 'system', '1', '比對法規知識庫，回傳合規性結果'],
        ['5.2', '身為社團幹部，我想要申請幹部證書', 'officer', '2', '填寫資訊後自動生成含數位簽章的證書'],
        ['5.3', '身為學生，我想要建立個人 E-Portfolio', 'student', '2', '可新增職能標籤、活動經歷，匯出 PDF'],
        ['5.4', '身為課指組，我想要使用 AI 生成申訴摘要', 'admin', '2', 'WebSocket 即時顯示 AI 生成的申訴摘要'],
        ['5.5', '身為社團幹部，我想要使用數位時光膠囊', 'officer', '3', '可封裝文件並移交給下屆幹部'],
    ]
)

# Epic 6
add_paragraph('Epic 6：儀表板與數據分析（Dashboard & Analytics）', bold=True, size=Pt(13))
add_table(
    ['編號', '使用者故事', '角色', '優先順序', '接受條件'],
    [
        ['6.1', '身為課指組，我想要查看管理儀表板', 'admin', '1', '顯示趨勢圖、圓餅圖、Gauge、堆疊長條、雷達、漏斗'],
        ['6.2', '身為社團幹部，我想要查看社團儀表板', 'officer', '1', '出勤率、留存率、滿意度、經費使用'],
        ['6.3', '身為指導教授，我想要查看指導儀表板', 'professor', '1', '績效雷達圖、熱力圖、蛛網圖、風險燈號'],
        ['6.4', '身為學生，我想要查看個人儀表板', 'student', '1', '履歷摘要、職能雷達圖、AI 推薦'],
        ['6.5', '身為資訊中心，我想要查看技術儀表板', 'it', '1', '負載熱圖、API 延遲、WAF 日誌、R2 使用率'],
    ]
)

doc.add_heading('使用者故事卡（User Story Cards）', level=3)
add_paragraph('以下列出關鍵使用者故事卡的詳細規格：')

# Story Card example
add_paragraph('Story Card #1.2 - AI 自動企劃生成', bold=True, size=Pt(12), color=RGBColor(0, 49, 83))
add_table(
    ['欄位', '內容'],
    [
        ['故事編號', '1.2'],
        ['角色', '社團幹部 (Officer)'],
        ['故事', '身為社團幹部，我想要使用 AI 自動生成活動企劃書，以減少文書作業時間'],
        ['優先順序', '1 (最高)'],
        ['接受條件', '1. 輸入活動名稱、類型、人數、描述後按下「AI 生成」\n'
                   '2. 系統調用 Dify Workflow API\n'
                   '3. AI 參考 RAG 知識庫中的法規與歷史範本\n'
                   '4. 生成完整企劃書（含目的、流程、預算、安全計畫）\n'
                   '5. 可下載 Word 格式'],
        ['估計工時', '8 小時'],
    ]
)

add_paragraph('Story Card #2.3 - 場地衝突自動協商', bold=True, size=Pt(12), color=RGBColor(0, 49, 83))
add_table(
    ['欄位', '內容'],
    [
        ['故事編號', '2.3'],
        ['角色', '系統 (System)'],
        ['故事', '身為系統，我想要在偵測到場地衝突時自動啟動三階段協商機制'],
        ['優先順序', '1 (最高)'],
        ['接受條件', '1. 偵測到兩個以上申請衝突同一場地/時段\n'
                   '2. 階段一：依 Level 1>2>3 優先級自動配對\n'
                   '3. 同級衝突進入階段二：發送 LINE 通知雙方\n'
                   '4. 3 分鐘無有效回應：GPT-4 介入生成 3 個建議\n'
                   '5. 6 分鐘無回應：強制關閉、扣 10 分、紅光動畫\n'
                   '6. 協商成功：進入階段三官方核准'],
        ['估計工時', '16 小時'],
    ]
)

add_paragraph('Story Card #4.2 - 信用積分管理', bold=True, size=Pt(12), color=RGBColor(0, 49, 83))
add_table(
    ['欄位', '內容'],
    [
        ['故事編號', '4.2'],
        ['角色', '系統 (System)'],
        ['故事', '身為系統，我想要管理使用者信用積分，並在低於閾值時強制執行安全措施'],
        ['優先順序', '1 (最高)'],
        ['接受條件', '1. 信用積分預設 100 分\n'
                   '2. 扣分事件：協商超時(-10)、遲到簽到(-5)、器材逾期(-5)、器材損壞(-15)\n'
                   '3. 加分事件：按時歸還(+5)、完成活動(+3)\n'
                   '4. 低於 60 分：Observer Pattern 觸發 JWT 失效，強制登出\n'
                   '5. 所有變動寫入 credit_logs\n'
                   '6. 扣分時觸發紅光動畫'],
        ['估計工時', '12 小時'],
    ]
)

doc.add_page_break()

# ===================== CHAPTER 3: SOFTWARE DESIGN SPECIFICATIONS =====================
doc.add_heading('第三章 軟體設計規格', level=1)

doc.add_heading('一、資料庫設計', level=2)

add_paragraph(
    '本系統採用 MySQL 8.0 (InnoDB) 作為主要資料庫引擎，並搭配 Cloudflare D1 (SQLite) '
    '作為邊緣環境的本地開發資料庫。以下為完整的資料庫 Schema 設計：'
)

add_paragraph('資料表一覽表', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['編號', '資料表名稱', '說明', '主要欄位'],
    [
        ['1', 'users', '使用者主檔', 'id, student_id, name, email, phone, role, credit_score, two_factor_enabled'],
        ['2', 'clubs', '社團主檔', 'id, name, category, description, advisor_id, president_id, member_count'],
        ['3', 'club_members', '社團成員', 'id, club_id, user_id, position, joined_at'],
        ['4', 'venues', '場地主檔', 'id, name, location, capacity, status, latitude, longitude'],
        ['5', 'activities', '活動主檔', 'id, title, club_id, venue_id, event_date, status, ai_review_result'],
        ['6', 'activity_registrations', '活動報名', 'id, activity_id, user_id, status, check_in_at'],
        ['7', 'equipment', '器材主檔', 'id, code, name, category, status, condition_note'],
        ['8', 'equipment_borrowings', '器材借用', 'id, equipment_id, borrower_id, borrow_date, expected_return_date, status'],
        ['9', 'reservations', '場地預約（三階段調度）', 'id, user_id, venue_id, priority_level, status, stage'],
        ['10', 'conflicts', '衝突協商記錄', 'id, reservation_a_id, reservation_b_id, status, ai_suggestions'],
        ['11', 'credit_logs', '信用積分紀錄', 'id, user_id, action, points, reason'],
        ['12', 'notification_logs', '通知紀錄', 'id, user_id, title, message, channel, tracking_token'],
        ['13', 'certificates', '幹部證書', 'id, user_id, club_id, position, term, certificate_code, digital_signature'],
        ['14', 'portfolio_entries', 'E-Portfolio 項目', 'id, user_id, title, tags, activity_id'],
        ['15', 'competency_scores', '職能分數', 'id, user_id, dimension, score'],
        ['16', 'ai_review_logs', 'AI 審查紀錄', 'id, target_type, target_id, allow_next_step, risk_level, violations'],
        ['17', 'appeals', '申訴案件', 'id, appellant_id, appeal_type, subject, status, ai_summary'],
        ['18', 'time_capsules', '數位時光膠囊', 'id, club_id, created_by, received_by, r2_storage_key, status'],
        ['19', 'outbox_table', '異步處理佇列', 'id, event_type, payload, status, retry_count'],
    ]
)

add_paragraph('E-R 關聯圖描述', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '以下為主要實體關聯關係（以 Mermaid ER 語法描述）：\n\n'
    '• users 1──N club_members：一個使用者可加入多個社團\n'
    '• clubs 1──N club_members：一個社團有多個成員\n'
    '• users 1──N activities：一個使用者可組織多個活動（via organizer_id）\n'
    '• clubs 1──N activities：一個社團可舉辦多個活動\n'
    '• venues 1──N activities：一個場地可承辦多個活動\n'
    '• activities 1──N activity_registrations：一個活動可有多個報名\n'
    '• users 1──N activity_registrations：一個使用者可報名多個活動\n'
    '• equipment 1──N equipment_borrowings：一件器材可被多次借用\n'
    '• users 1──N equipment_borrowings：一個使用者可借用多件器材\n'
    '• users 1──N reservations：一個使用者可預約多個場地\n'
    '• venues 1──N reservations：一個場地可有多筆預約\n'
    '• reservations N──N conflicts：衝突記錄連結兩筆預約\n'
    '• users 1──N credit_logs：一個使用者有多筆信用紀錄\n'
    '• users 1──N notification_logs：一個使用者有多筆通知\n'
    '• users 1──N certificates：一個使用者可擁有多張證書\n'
    '• users 1──N portfolio_entries：一個使用者有多筆 Portfolio 項目\n'
    '• users 1──N competency_scores：一個使用者有多個維度的職能分數\n'
    '• clubs 1──N time_capsules：一個社團有多個時光膠囊'
)

add_paragraph('Users 資料表詳細結構', bold=True, size=Pt(12), color=RGBColor(0, 49, 83))
add_table(
    ['欄位名稱', '資料類型', '限制', '說明'],
    [
        ['id', 'INTEGER', 'PK, AI, NN', '主鍵'],
        ['student_id', 'TEXT', 'UNIQUE, NN', '學號/員工編號'],
        ['name', 'TEXT', 'NN', '姓名'],
        ['email', 'TEXT', 'UNIQUE, NN', 'Outlook 信箱 (@cloud.fju.edu.tw)'],
        ['phone', 'TEXT', '', '手機號碼'],
        ['role', 'TEXT', 'NN, CHECK', '角色: student/officer/professor/admin/it'],
        ['club_position', 'TEXT', '', '社團職位'],
        ['credit_score', 'INTEGER', 'NN, DEFAULT 100', '信用積分 (60 分閾值)'],
        ['google_oauth_id', 'TEXT', '', 'Google OAuth ID'],
        ['two_factor_enabled', 'INTEGER', 'DEFAULT 0', '是否啟用 2FA'],
        ['two_factor_secret', 'TEXT', '', 'TOTP 金鑰'],
        ['avatar_url', 'TEXT', '', '頭像 URL'],
        ['language', 'TEXT', 'DEFAULT zh-TW', '偏好語言'],
        ['is_active', 'INTEGER', 'DEFAULT 1', '帳號啟用狀態'],
        ['last_login_at', 'TEXT', '', '最後登入時間'],
        ['created_at', 'TEXT', 'DEFAULT NOW', '建立時間'],
        ['updated_at', 'TEXT', 'DEFAULT NOW', '更新時間'],
    ]
)

doc.add_heading('二、介面設計', level=2)
add_paragraph('本系統的介面設計遵循「60-30-10 配色法則」與 Glassmorphism 設計語言。')

add_paragraph('配色規範', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['色彩角色', '色彩名稱', '色碼', '使用比例', '應用場景'],
    [
        ['主色', '天使白 Angel White', '#FFFFFF', '60%', '背景、卡片底色'],
        ['輔色', '聖母藍 Mary Blue', '#003153', '30%', 'Navbar、側邊欄標題、主 CTA 按鈕'],
        ['強調色', '梵蒂岡黃 Vatican Gold', '#DAA520', '10%', '亮點元素、登入按鈕、行事曆標記'],
        ['校旗金', 'FJU Gold', '#FDB913', '搭配使用', '替代強調色'],
        ['狀態色-正常', '嘉禾綠 Harvest Green', '#008000', '狀態標記', '正常/完成'],
        ['狀態色-警告', '警示紅 Warning Red', '#FF0000', '狀態標記', '維修/逾時/錯誤'],
    ]
)

add_paragraph('頁面設計藍圖一覽', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['編號', '頁面名稱', '說明', 'URL 路徑'],
    [
        ['0.0', 'Landing Page（動態首頁）', 'Hero Image + 動畫介紹 + Carousel + 功能卡片 + 評價輪播', '/'],
        ['0.1', '登入頁面', 'Glassmorphism 登入盒 + Google OAuth + 2FA + Turnstile', '/login'],
        ['1.0', '課指組儀表板', '趨勢折線、圓餅、Gauge、堆疊長條、SDGs 雷達、審核漏斗', '/dashboard?role=admin'],
        ['1.1', '社團幹部儀表板', '出勤長條、留存折線、滿意度圓餅、經費水滴', '/dashboard?role=officer'],
        ['1.2', '指導教授儀表板', '績效雷達、活動熱力、蛛網圖、風險燈號', '/dashboard?role=professor'],
        ['1.3', '學生儀表板', '履歷摘要、職能雷達、AI 推薦', '/dashboard?role=student'],
        ['1.4', '資訊中心儀表板', '負載熱圖、API 延遲、WAF 日誌、R2 使用率', '/dashboard?role=it'],
        ['2.0', '職能 E-Portfolio', '個人資訊 + 標籤 + 經歷 + 雷達圖 + PDF 匯出', '/module/e-portfolio'],
        ['2.1', 'AI 企劃生成器', '表單輸入 + Dify Workflow + RAG 法規 + 結果預覽', '/module/ai-proposal'],
        ['2.2', '幹部證書', '證書資訊填寫 + 即時預覽 + 數位簽章 + QR Code', '/module/certificate'],
        ['2.3', '器材管理', '器材列表 + 狀態篩選 + LINE/SMS 提醒設定', '/module/equipment'],
        ['2.4', 'AI 智慧預審', '文件上傳 + RAG 結果 + Reasoning JSON', '/module/ai-review'],
        ['2.5', '場地活化大數據', '統計卡 + 熱力圖 + 排行榜 + 三階段調度', '/module/venue-data'],
        ['2.6', 'AI 申訴摘要', '案件列表 + WebSocket AI 摘要 + 仲裁', '/module/ai-appeal'],
        ['2.7', '動態活動牆', '搜尋/篩選 + 活動卡片 + 報名', '/module/activity-wall'],
        ['2.8', '數位時光膠囊', 'R2 上傳 + 歷史封裝 + GPS 浮水印', '/module/time-capsule'],
        ['2.9', '2FA 設定', 'TOTP/SMS 設定 + QR Code + 驗證記錄', '/module/2fa'],
    ]
)

add_paragraph('Bento Grid 佈局說明', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '登入後的內部頁面採用 Bento Grid 佈局：\n'
    '• 頁首 (Header)：固定置頂，包含校徽 Logo、懸浮式搜尋列（仿 Google Maps）、語言切換器、通知鈴、角色切換按鈕。\n'
    '• 左側側邊欄 (30%)：信用積分儀表板（深藍色漸層卡片）、導航選單（按角色動態顯示）、使用者資訊。\n'
    '• 右側主視覺 (70%)：歡迎橫幅 → 快速統計卡 → 雙模態區塊（底層校園互動地圖 Leaflet.js + 頂層智慧行事曆 Glassmorphism）→ 角色專屬圖表區。\n'
    '• FAB 按鈕：右下角柴犬 AI 助手浮動按鈕，點擊展開聊天面板。'
)

doc.add_heading('三、API 接口定義', level=2)

add_paragraph('RESTful API 端點一覽', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['方法', '端點', '說明', '角色權限', '請求/回應範例'],
    [
        ['GET', '/api/health', '系統健康檢查', '公開', '回應: {status, version, timestamp}'],
        ['GET', '/api/users', '取得使用者列表', 'admin, it', '回應: {data: [User]}'],
        ['POST', '/api/users', '建立使用者', 'admin', '請求: {student_id, name, email, role}'],
        ['GET', '/api/users/:id', '取得單一使用者', 'all', '回應: {data: User}'],
        ['PUT', '/api/users/:id', '更新使用者資料', 'self, admin', '請求: {name, phone, club_position}'],
        ['DELETE', '/api/users/:id', '刪除使用者', 'admin', '回應: {success: boolean}'],
        ['GET', '/api/clubs', '取得社團列表', 'all', '回應: {data: [Club]}'],
        ['POST', '/api/clubs', '建立社團', 'admin', '請求: {name, category, advisor_id}'],
        ['PUT', '/api/clubs/:id', '更新社團資訊', 'officer, admin', '請求: {name, description}'],
        ['DELETE', '/api/clubs/:id', '刪除社團', 'admin', '回應: {success: boolean}'],
        ['GET', '/api/venues', '取得場地列表', 'all', '回應: {data: [Venue]}'],
        ['POST', '/api/venues', '建立場地', 'admin', '請求: {name, capacity, location}'],
        ['PUT', '/api/venues/:id', '更新場地狀態', 'admin', '請求: {status, capacity}'],
        ['DELETE', '/api/venues/:id', '刪除場地', 'admin', '回應: {success: boolean}'],
        ['GET', '/api/activities', '取得活動列表', 'all', '回應: {data: [Activity]}'],
        ['POST', '/api/activities', '建立活動', 'officer', '請求: {title, club_id, venue_id, event_date}'],
        ['PUT', '/api/activities/:id', '更新活動', 'officer, admin', '請求: {title, status}'],
        ['DELETE', '/api/activities/:id', '刪除活動', 'officer, admin', '回應: {success: boolean}'],
        ['GET', '/api/equipment', '取得器材列表', 'all', '回應: {data: [Equipment]}'],
        ['POST', '/api/equipment', '新增器材', 'admin', '請求: {code, name, category}'],
        ['PUT', '/api/equipment/:id', '更新器材狀態', 'admin', '請求: {status, condition_note}'],
        ['DELETE', '/api/equipment/:id', '刪除器材', 'admin', '回應: {success: boolean}'],
        ['GET', '/api/reservations', '取得預約列表', 'all', '回應: {data: [Reservation]}'],
        ['POST', '/api/reservations', '建立場地預約', 'officer', '請求: {venue_id, date, start_time, end_time}'],
        ['POST', '/api/ai/pre-review', 'AI 智慧預審', 'system', '請求: {document} → 回應: {allow_next_step, risk_level, violations}'],
        ['POST', '/api/ai/generate-proposal', 'AI 企劃生成', 'officer', '請求: {title, type, participants} → 回應: {content}'],
        ['GET', '/api/credits/:userId', '取得信用紀錄', 'self, admin', '回應: {score, logs: [CreditLog]}'],
        ['POST', '/api/credits/deduct', '扣除信用積分', 'system, admin', '請求: {user_id, points, reason} → 回應: {new_score, force_logout}'],
        ['GET', '/api/notifications/:userId', '取得通知列表', 'self', '回應: {data: [Notification]}'],
        ['POST', '/api/certificates/generate', '生成幹部證書', 'officer, admin', '請求: {name, club, position, term} → 回應: {certificate_id, digital_signature}'],
        ['GET', '/api/conflicts', '取得衝突列表', 'admin', '回應: {data: [Conflict]}'],
        ['POST', '/api/conflicts/negotiate', '協商介入', 'system', '請求: {conflict_id} → 回應: {suggestions[3]}'],
        ['GET', '/api/dashboard/stats/:role', '取得儀表板統計', 'self', '回應: 角色專屬統計數據'],
    ]
)

add_paragraph('Dify AI 數據交換格式', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph('RAG 知識庫匯入格式 (JSON)：')
add_paragraph(
    '{\n'
    '  "source_id": "reg-001",\n'
    '  "category": "行政流程",\n'
    '  "title": "活動申請辦法",\n'
    '  "content": "第一條 本辦法依據...",\n'
    '  "metadata": {\n'
    '    "date": "2025-09-01",\n'
    '    "department": "課外活動指導組",\n'
    '    "tags": ["活動", "申請", "審核"],\n'
    '    "download_url": "https://activity.fju.edu.tw/docs/reg-001.pdf"\n'
    '  }\n'
    '}',
    size=Pt(10)
)

add_paragraph('AI 預審回應格式 (Reasoning JSON)：')
add_paragraph(
    '{\n'
    '  "allow_next_step": false,\n'
    '  "risk_level": "Medium",\n'
    '  "violations": ["crowd_overload"],\n'
    '  "references": ["場地使用管理規則 第5條"],\n'
    '  "suggestions": ["調整人數至80人以下", "或申請更大場地"],\n'
    '  "reasoning": "申請 120 人超過場地容量 80 人限制..."\n'
    '}',
    size=Pt(10)
)

doc.add_heading('四、核心流程圖', level=2)

add_paragraph('以下為系統要求的 5 種核心流程圖，使用 Mermaid 語法描述：')

# Flowchart 1
add_paragraph('流程圖 1：系統全域架構圖', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '```mermaid\n'
    'graph TB\n'
    '    subgraph "使用者端 (Frontend)"\n'
    '        A[Vue 3 + GSAP] --> B[Landing Page]\n'
    '        A --> C[Dashboard / Bento Grid]\n'
    '        A --> D[10 Pillar Modules]\n'
    '    end\n'
    '    \n'
    '    subgraph "Cloudflare 安全層"\n'
    '        E[Cloudflare WAF] --> F[Turnstile 人機驗證]\n'
    '        F --> G[R2 Object Storage]\n'
    '    end\n'
    '    \n'
    '    subgraph "後端 (Backend)"\n'
    '        H[PHP 8.3 / Laravel API] --> I[MySQL 8.0 InnoDB]\n'
    '        H --> J[Redis Cache / 2FA / Timer]\n'
    '        H --> K[JWT HttpOnly Cookie]\n'
    '    end\n'
    '    \n'
    '    subgraph "AI 中台 (Dify)"\n'
    '        L[Dify API] --> M[RAG Engine]\n'
    '        M --> N[Pinecone Vector DB]\n'
    '        L --> O[GPT-4 Negotiation]\n'
    '    end\n'
    '    \n'
    '    subgraph "通知服務"\n'
    '        P[LINE Notify]\n'
    '        Q[SMS Gateway]\n'
    '        R[SMTP Email]\n'
    '        S[Microsoft Graph API]\n'
    '    end\n'
    '    \n'
    '    A -->|HTTPS| E\n'
    '    E -->|Pass| H\n'
    '    H -->|GuzzleHTTP| L\n'
    '    H --> P\n'
    '    H --> Q\n'
    '    H --> R\n'
    '    H --> S\n'
    '    G -->|File Upload| H\n'
    '```',
    size=Pt(9)
)

# Flowchart 2
add_paragraph('流程圖 2：三階段資源調度狀態圖', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '```mermaid\n'
    'stateDiagram-v2\n'
    '    [*] --> 提交預約申請\n'
    '    提交預約申請 --> 階段一_自動配對: 志願序演算法\n'
    '    \n'
    '    state 階段一_自動配對 {\n'
    '        [*] --> 檢查優先級\n'
    '        檢查優先級 --> Level1_校方: priority=1\n'
    '        檢查優先級 --> Level2_社團: priority=2\n'
    '        檢查優先級 --> Level3_一般: priority=3\n'
    '        Level1_校方 --> 直接配對\n'
    '        Level2_社團 --> 檢查衝突\n'
    '        Level3_一般 --> 檢查衝突\n'
    '        檢查衝突 --> 無衝突_配對成功: 無衝突\n'
    '        檢查衝突 --> 偵測到衝突: 有衝突\n'
    '    }\n'
    '    \n'
    '    直接配對 --> 階段三_官方核准\n'
    '    無衝突_配對成功 --> 階段三_官方核准\n'
    '    偵測到衝突 --> 階段二_自主協商: LINE通知雙方\n'
    '    \n'
    '    state 階段二_自主協商 {\n'
    '        [*] --> 開啟協商對話\n'
    '        開啟協商對話 --> 等待回應\n'
    '        等待回應 --> 三分鐘_AI介入: 3分鐘無回應\n'
    '        三分鐘_AI介入 --> GPT4生成三方案\n'
    '        GPT4生成三方案 --> 等待選擇方案\n'
    '        等待選擇方案 --> 協商成功: 雙方達成共識\n'
    '        等待選擇方案 --> 六分鐘_強制關閉: 6分鐘無回應\n'
    '        六分鐘_強制關閉 --> 扣10分_紅光動畫\n'
    '        等待回應 --> 協商成功: 雙方自行協調\n'
    '    }\n'
    '    \n'
    '    協商成功 --> 階段三_官方核准\n'
    '    扣10分_紅光動畫 --> 記錄Log_通知管理員\n'
    '    \n'
    '    state 階段三_官方核准 {\n'
    '        [*] --> RAG法規檢索\n'
    '        RAG法規檢索 --> Gatekeeping檢查\n'
    '        Gatekeeping檢查 --> 核准_產出PDF: 通過\n'
    '        Gatekeeping檢查 --> 退回修改: 未通過\n'
    '        核准_產出PDF --> 生成TOTP_QRCode\n'
    '    }\n'
    '    \n'
    '    生成TOTP_QRCode --> [*]\n'
    '    退回修改 --> 提交預約申請\n'
    '```',
    size=Pt(9)
)

# Flowchart 3
add_paragraph('流程圖 3：多角色全端功能泳道圖', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '```mermaid\n'
    'graph LR\n'
    '    subgraph "學生 Student"\n'
    '        S1[Google OAuth 登入] --> S2[瀏覽活動牆]\n'
    '        S2 --> S3[報名活動]\n'
    '        S3 --> S4[現場簽到]\n'
    '        S4 --> S5[查看 Portfolio]\n'
    '    end\n'
    '    \n'
    '    subgraph "社團幹部 Officer"\n'
    '        O1[登入] --> O2[AI 生成企劃]\n'
    '        O2 --> O3[申請場地/器材]\n'
    '        O3 --> O4[參與協商]\n'
    '        O4 --> O5[執行活動]\n'
    '        O5 --> O6[申請證書]\n'
    '    end\n'
    '    \n'
    '    subgraph "指導教授 Professor"\n'
    '        P1[登入] --> P2[查看社團狀態]\n'
    '        P2 --> P3[審核活動]\n'
    '        P3 --> P4[評估績效]\n'
    '        P4 --> P5[風險監控]\n'
    '    end\n'
    '    \n'
    '    subgraph "課指組 Admin"\n'
    '        A1[登入+2FA] --> A2[AI 預審結果]\n'
    '        A2 --> A3[場地核准]\n'
    '        A3 --> A4[經費核銷]\n'
    '        A4 --> A5[大數據分析]\n'
    '    end\n'
    '    \n'
    '    subgraph "系統管理員 IT"\n'
    '        I1[登入+2FA] --> I2[系統監控]\n'
    '        I2 --> I3[WAF 管理]\n'
    '        I3 --> I4[帳號管理]\n'
    '        I4 --> I5[備份維護]\n'
    '    end\n'
    '    \n'
    '    O3 -.->|AI預審| A2\n'
    '    O4 -.->|衝突通知| A3\n'
    '    S3 -.->|報名通知| O5\n'
    '    P3 -.->|審核結果| O2\n'
    '```',
    size=Pt(9)
)

# Flowchart 4
add_paragraph('流程圖 4：Dify AI 智慧預審與 RAG 流程圖', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '```mermaid\n'
    'flowchart TD\n'
    '    A[使用者提交申請] --> B[Laravel 接收請求]\n'
    '    B --> C[GuzzleHTTP 調用 Dify API]\n'
    '    C --> D{Dify Knowledge Base}\n'
    '    D --> E[活動申請辦法]\n'
    '    D --> F[場地使用管理規則]\n'
    '    D --> G[經費補助要點]\n'
    '    D --> H[器材借用辦法]\n'
    '    D --> I[安全管理規範]\n'
    '    \n'
    '    E & F & G & H & I --> J[Pinecone 向量檢索]\n'
    '    J --> K[語義比對 & 相關性排序]\n'
    '    K --> L{合規性判斷}\n'
    '    \n'
    '    L -->|通過| M[allow_next_step: true]\n'
    '    L -->|違規| N[allow_next_step: false]\n'
    '    L -->|警告| O[risk_level: Medium]\n'
    '    \n'
    '    M --> P[Reasoning JSON]\n'
    '    N --> P\n'
    '    O --> P\n'
    '    \n'
    '    P --> Q{回傳結果}\n'
    '    Q --> R[violations: 違規標記陣列]\n'
    '    Q --> S[references: 法規引用]\n'
    '    Q --> T[suggestions: 修改建議]\n'
    '    \n'
    '    R & S & T --> U[前端顯示結果]\n'
    '    U --> V{人工覆核}\n'
    '    V -->|核准| W[進入下一步]\n'
    '    V -->|駁回| X[退回修改]\n'
    '```',
    size=Pt(9)
)

# Flowchart 5
add_paragraph('流程圖 5：安全驗證與身分生命週期圖', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_paragraph(
    '```mermaid\n'
    'stateDiagram-v2\n'
    '    [*] --> 訪問系統\n'
    '    訪問系統 --> Cloudflare_WAF\n'
    '    Cloudflare_WAF --> Turnstile_驗證: WAF 放行\n'
    '    Cloudflare_WAF --> 拒絕存取: WAF 攔截\n'
    '    \n'
    '    Turnstile_驗證 --> Google_OAuth: 驗證通過\n'
    '    Google_OAuth --> 檢查HD網域\n'
    '    檢查HD網域 --> 拒絕登入: 非 @cloud.fju.edu.tw\n'
    '    檢查HD網域 --> 檢查2FA設定: 網域正確\n'
    '    \n'
    '    檢查2FA設定 --> 輸入TOTP: 已啟用 2FA\n'
    '    檢查2FA設定 --> 產生JWT: 未啟用 2FA\n'
    '    輸入TOTP --> 驗證TOTP\n'
    '    驗證TOTP --> 產生JWT: 驗證成功\n'
    '    驗證TOTP --> 發送SMS備援: 驗證失敗\n'
    '    發送SMS備援 --> 輸入SMS碼\n'
    '    輸入SMS碼 --> 產生JWT: 驗證成功\n'
    '    \n'
    '    產生JWT --> 設定HttpOnly_Cookie\n'
    '    設定HttpOnly_Cookie --> 系統正常使用\n'
    '    \n'
    '    state 系統正常使用 {\n'
    '        [*] --> Observer_監控\n'
    '        Observer_監控 --> 檢查信用積分\n'
    '        檢查信用積分 --> 正常使用: >= 60分\n'
    '        檢查信用積分 --> 強制JWT失效: < 60分\n'
    '        正常使用 --> 高權限操作\n'
    '        高權限操作 --> 要求2FA驗證: 仲裁/核銷/後台\n'
    '        要求2FA驗證 --> 正常使用: 驗證通過\n'
    '    }\n'
    '    \n'
    '    強制JWT失效 --> 強制登出\n'
    '    強制登出 --> 帳號停權\n'
    '    帳號停權 --> 課指組人工審核: 申請恢復\n'
    '    課指組人工審核 --> 訪問系統: 核准恢復\n'
    '```',
    size=Pt(9)
)

doc.add_heading('五、資源需求（預算經費）', level=2)

add_paragraph('開發經費預估', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['項目', '說明', '金額 (NTD)'],
    [
        ['開發人力', '5 人 × 20 小時/週 × 14 週 × 200 元/時', '280,000'],
        ['雲端主機 (6個月)', 'GCE / Cloudflare Workers', '19,398'],
        ['Dify AI 平台', 'API 調用費用 (預估)', '15,000'],
        ['Pinecone 向量資料庫', 'Starter Plan', '0 (免費額度)'],
        ['LINE Notify', 'API 調用', '0 (免費)'],
        ['SMS Gateway', '簡訊費用預估', '5,000'],
        ['Cloudflare', 'Pro Plan (WAF + R2)', '20,000'],
        ['合計', '', '339,398'],
    ]
)

add_paragraph('前三年營運經費預估', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['項目', '年費用 (NTD)', '三年合計 (NTD)'],
    [
        ['雲端主機', '38,796', '116,388'],
        ['維護人力', '60,000', '180,000'],
        ['Dify AI', '30,000', '90,000'],
        ['Cloudflare', '40,000', '120,000'],
        ['SMS', '10,000', '30,000'],
        ['合計', '178,796', '536,388'],
    ]
)

doc.add_page_break()

# ===================== CHAPTER 4: IMPLEMENTATION REVIEW =====================
doc.add_heading('第四章 系統專題實作檢討', level=1)

doc.add_heading('一、發展中遭遇到問題、困難與解決方法', level=2)
add_table(
    ['問題', '困難描述', '解決方法'],
    [
        ['AI RAG 準確率', '初期知識庫不夠完整，法規檢索命中率偏低', '持續擴充知識庫，調整 Embedding 模型參數，新增法規文件切分策略'],
        ['三階段協商同步', 'WebSocket 雙方同步困難，訊息遺失問題', '導入 Redis Pub/Sub 確保訊息可靠傳遞，並加入重連機制'],
        ['前端效能', '大量 Chart.js 圖表導致頁面載入緩慢', '實施 Lazy Loading、Code Splitting、按需載入圖表'],
        ['多語系翻譯', '動態內容翻譯不完整', '建立翻譯 Key 管理機制，區分 UI 翻譯與動態內容翻譯'],
        ['信用積分併發', '多個扣分事件同時觸發導致 Race Condition', '使用 START TRANSACTION + 悲觀鎖確保原子操作'],
    ]
)

doc.add_heading('二、系統優缺點（SWOT）評估', level=2)
add_table(
    ['面向', '內容'],
    [
        ['優勢 (Strengths)', '• 全國首創 AI 驅動校園管理平台\n• 三階段資源調度確保公平性\n• 五角色專屬儀表板\n• 五國語言國際化支援\n• 信用積分行為治理'],
        ['劣勢 (Weaknesses)', '• 依賴外部 AI 服務 (Dify)\n• 系統複雜度高，維護成本較高\n• 初期使用者學習曲線\n• RAG 準確率需持續優化'],
        ['機會 (Opportunities)', '• 可推廣至其他大學\n• 與教育部數位轉型政策接軌\n• AI 技術持續進步提升系統能力\n• 校際合作共建知識庫'],
        ['威脅 (Threats)', '• 外部服務中斷風險\n• 個資保護法規趨嚴\n• 使用者抗拒數位轉型\n• 技術團隊離校後的維護問題'],
    ]
)

doc.add_heading('三、發展心得', level=2)
add_paragraph(
    '在開發 FJU Smart Hub 的過程中，我們深刻體會到系統分析的重要性。從初期的需求訪談，到利害關係人的溝通協調，'
    '再到技術架構的設計與實現，每一個環節都需要嚴謹的規劃。'
    '\n\n'
    '特別是在 AI 模組的設計上，我們經歷了多次迭代。最初嘗試直接使用 ChatGPT API 做法規檢索，但發現準確率不足；'
    '後來導入 Dify + Pinecone 的 RAG 架構，才大幅提升了智慧預審的可靠性。這讓我們深刻理解到，AI 不是萬靈丹，'
    '需要搭配良好的數據工程才能發揮最大效益。'
    '\n\n'
    '三階段資源調度演算法的設計也是一大挑戰。我們參考了大學聯招的志願序分發機制，並結合 AI 協商的概念，'
    '設計出一套兼顧效率與公平的場地分配機制。'
)

doc.add_heading('四、未來展望', level=2)
add_paragraph(
    '1. AI 功能強化：整合更先進的 LLM 模型，提升企劃生成與法規檢索的品質。\n'
    '2. 行動裝置 App：開發原生 iOS/Android App，提升行動體驗。\n'
    '3. 校際聯盟：與其他大學合作，共建法規知識庫與活動資源共享平台。\n'
    '4. 智慧簽到：整合 NFC/QR Code/人臉辨識等多元簽到方式。\n'
    '5. SDGs 深度整合：進一步量化社團活動對聯合國永續發展目標的貢獻。\n'
    '6. 畢業生職能追蹤：與校友系統整合，追蹤 E-Portfolio 的長期價值。'
)

doc.add_page_break()

# ===================== APPENDIX =====================
doc.add_heading('附 錄', level=1)

doc.add_heading('進度報告', level=2)

add_paragraph('Sprint 1 進度報告', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['項目', '內容'],
    [
        ['Sprint 目標', '完成系統架構設計、資料庫建置、Landing Page 與登入頁面'],
        ['Scrum Master', '系統架構師'],
        ['Product Owner', '課指組代表'],
        ['Sprint 期間', '2026/01/27 - 2026/02/10 (2 週)'],
        ['完成之 User Story', '0.0 Landing Page, 0.1 登入頁面, 4.1 Google OAuth, DB Schema'],
        ['未完成之 User Story', '2.1 場地申請, 2.2 自動配對'],
        ['Sprint Review', '• Landing Page 動畫效果獲得好評\n• 60-30-10 配色方案確認\n• 資料庫 Schema 通過審核'],
        ['Sprint Retrospective', '• 跨團隊溝通需加強\n• AI 模組規格需更早確認\n• 前端元件庫統一標準'],
    ]
)

add_paragraph('Sprint 2 進度報告', bold=True, size=Pt(14), color=RGBColor(0, 49, 83))
add_table(
    ['項目', '內容'],
    [
        ['Sprint 目標', '完成十大支柱模組、五角色儀表板、API 開發'],
        ['Sprint 期間', '2026/02/11 - 2026/02/24 (2 週)'],
        ['完成之 User Story', '1.1-1.7, 2.1-2.7, 3.1-3.5, 5.1-5.5, 6.1-6.5'],
        ['未完成之 User Story', '4.3 用戶管理頁面 (修復中), 全域行事曆 (修復中)'],
        ['Sprint Review', '• 十大支柱模組全部完成前端 UI\n• API 串接測試通過 94%\n• AI 預審 RAG 命中率達 85%'],
        ['Sprint Retrospective', '• 器材借用頁面需修復\n• WebSocket 穩定性需加強\n• 效能優化需持續進行'],
    ]
)

doc.add_heading('二、參考資料', level=2)
add_paragraph(
    '1. 輔仁大學課外活動指導組官網 - https://activity.fju.edu.tw/\n'
    '2. Dify AI 官方文件 - https://docs.dify.ai/\n'
    '3. Laravel 官方文件 - https://laravel.com/docs/\n'
    '4. Vue 3 官方文件 - https://vuejs.org/\n'
    '5. Cloudflare Workers 文件 - https://developers.cloudflare.com/workers/\n'
    '6. Pinecone 文件 - https://docs.pinecone.io/\n'
    '7. Chart.js 文件 - https://www.chartjs.org/docs/\n'
    '8. GSAP 文件 - https://greensock.com/docs/\n'
    '9. Hono 框架文件 - https://hono.dev/\n'
    '10. Leaflet.js 文件 - https://leafletjs.com/'
)

# ===================== SAVE =====================
output_path = '/home/user/webapp/FJU_Smart_Hub_SA_Specification.docx'
doc.save(output_path)
print(f'SA Document saved to: {output_path}')
print(f'File size: {__import__("os").path.getsize(output_path) / 1024:.1f} KB')
