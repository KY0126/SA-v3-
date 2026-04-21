# FJU Smart Hub v3.0

## 專案概述
- **名稱**: FJU Smart Hub — 輔仁大學智慧校園管理平台
- **技術堆疊**: PHP 8.2 + Laravel 12 + MySQL 8.0 (MariaDB) + Tailwind CSS + Chart.js + Leaflet.js + GSAP + Dify AI
- **目標**: 整合 AI 智慧預審、三階段資源調度、數位證書、活動管理的一站式校園解決方案

## URLs
- **GitHub**: https://github.com/KY0126/SA-v3-
- **版本**: v3.0.0

## 已完成功能 (21 個模組頁面 + 30+ API)

### 前台模組
| 模組 | 路徑 | 說明 |
|------|------|------|
| Landing Page | `/` | GSAP 動畫、痛點→解方、柴犬吉祥物、計數器、快訊輪播 |
| 登入頁面 | `/login` | Google OAuth (hd 檢查)、Glassmorphism、5 角色快速切換 |
| Dashboard | `/dashboard?role=XXX` | 5 角色專屬儀表板 (從 API 動態載入) |
| 校園地圖 | `/campus-map` | Leaflet.js 互動地圖、4 圖層、20+ 建築 |

### 核心業務模組
| 模組 | 路徑 | 說明 |
|------|------|------|
| 場地預約 | `/module/venue-booking` | 三階段資源調度、衝突偵測、L1-L3 優先權 |
| 設備借用 | `/module/equipment` | CRUD、借還流程、狀態篩選 |
| 行事曆 | `/module/calendar` | EvoCalendar、事件 CRUD |
| 社團資訊 | `/module/club-info` | 102 社團、8 分類、搜尋 |
| 活動牆 | `/module/activity-wall` | CRUD、報名、篩選 |
| 衝突協調中心 | `/module/conflict-coordination` | 即時對話、郵件、3/6分鐘計時器、紅光動畫、雙方確認→重導行事曆 |

### AI 智慧模組
| 模組 | 路徑 | 說明 |
|------|------|------|
| AI 資訊概覽 | `/module/ai-overview` | AI 系統總覽 |
| AI 導覽助理 | `/module/ai-guide` | AI 企劃生成器 |
| 法規查詢 (RAG) | `/module/rag-search` | Dify RAG 知識庫、8 份法規索引、Gatekeeping 阻斷機制、AI 預審 |

### 管理與安全模組
| 模組 | 路徑 | 說明 |
|------|------|------|
| 報修管理 | `/module/repair` | CRUD、追蹤碼 |
| 申訴記錄 | `/module/appeal` | CRUD、AI 摘要 |
| 統計報表 | `/module/reports` | **5 大核心流程圖** (SVG)、權限矩陣、功能清單、評分對照 |
| 用戶管理 | `/module/user-management` | 使用者 CRUD (姓名/學號/Outlook/手機/角色) |

### 個人與證書模組
| 模組 | 路徑 | 說明 |
|------|------|------|
| 職能 E-Portfolio | `/module/e-portfolio` | 歷程記錄、雷達圖、Steppers、SDGs 對應 |
| 數位證書 | `/module/certificate` | 自動生成、SHA256 簽章 |
| 時光膠囊 | `/module/time-capsule` | R2 文件封裝移交 |
| 2FA 驗證 | `/module/2fa` | TOTP/SMS 雙因子 |

## 5 大核心流程圖 (在 `/module/reports` 頁面)
1. **三階段資源調度狀態圖** — 志願序→協商→審核完整流程
2. **系統全域架構圖** — Dify AI 中台 + Cloudflare 邊緣防護 + Laravel API + 多通路通知
3. **多角色全端功能泳道圖** — 5 角色 × 6 階段 (登入→搜尋→預約→AI預審→審核→簽到)
4. **Dify AI 智慧預審與 RAG 流程圖** — GuzzleHTTP → Dify API → Pinecone RAG → Reasoning JSON
5. **安全驗證與身分生命週期圖** — Google OAuth → 2FA → JWT → 觀察者模式 → <60 強制失效

## 角色專屬儀表板 (5 角色 × 各自圖表)
- **Admin (課指組)**: 折線圖、圓餅圖、Gauge、堆疊長條、雷達圖、漏斗圖 (6 圖表)
- **Officer (社團幹部)**: 簽到長條、留存折線、滿意度圓餅、經費水滴圖、傳承檢查表 (5 圖表)
- **Professor (指導教授)**: 績效雷達圖、職能成長折線、紅黃綠燈風險指標 (4 圖表)
- **Student (一般學生)**: 職能雷達圖、AI 推薦清單、Steppers、社團評價牆 (4 圖表)
- **IT (資訊中心)**: 負載長條、API 延遲折線、WAF 日誌、R2 圓餅圖 (4 圖表)

## 權限矩陣
5 角色 × 16 功能模組 完整 RBAC 矩陣

## 評分標準對應
| 項目 | 權重 | 對應功能 |
|------|------|----------|
| 創新性 | 20% | 三階段調度、AI 預審、衝突協調中心、Gatekeeping、Workers AI 意圖過濾 |
| 實用性 | 50% | 場地預約全流程、設備借用、活動牆、社團管理、行事曆、報修、申訴、信用積分、通知、證書、用戶管理 |
| 技術完善度 | 10% | Laravel 12 + MySQL + 30+ API + E2E 測試 + JWT + 2FA + 觀察者模式 |
| UI/UX 友善度 | 10% | 60-30-10 配色、Tailwind CSS、GSAP 動畫、Leaflet 地圖、Chart.js、柴犬 AI FAB |
| 內容豐富度 | 10% | 102 社團、10 場地、20+ 建築、5 語言、5 流程圖、SDGs 對應 |

## 資料架構
- **MySQL 8.0 (MariaDB)**: 21 張表 (users, clubs, venues, activities, reservations, conflicts, etc.)
- **Redis**: 計時器 + 2FA OTP 暫存
- **Dify AI**: RAG 知識庫 (8 份法規) + Workflow (預審/企劃/摘要)

## API 接口 (30+)
- Users CRUD, Clubs CRUD, Venues CRUD, Activities CRUD, Equipment CRUD
- Reservations (negotiate, accept-suggestion)
- Conflicts (chat, send-email, confirm, negotiate)
- Credits (show, deduct)
- Notifications (index, read)
- Certificates (generate)
- Calendar Events CRUD
- Repairs CRUD, Appeals CRUD (ai-summary)
- Dashboard stats (per role)
- AI (pre-review, generate-proposal)
- Gatekeeping (dependency check)
- i18n (zh-TW, en, ja, ko, zh-CN)
- Health check

## 部署方式
```bash
# 安裝依賴
composer install && npm install

# 環境設定
cp .env.example .env
php artisan key:generate

# 資料庫
php artisan migrate:fresh --seed

# 啟動
php artisan serve --port=3000
```

## 最後更新
- **日期**: 2026-04-21
- **版本**: v3.0.0
- **狀態**: ✅ Active
