# FJU Smart Hub 3.0 — 輔仁大學智慧校園管理平台

## 🚀 專案概覽
- **名稱**: FJU Smart Hub
- **版本**: 3.0.0
- **目標**: 整合 AI 智慧預審、三階段資源調度、衝突協調、數位證書、活動管理的一站式校園解決方案
- **技術棧**: Laravel 12 + PHP 8.2 + MySQL (MariaDB) + Tailwind CSS + Leaflet.js + Chart.js

## 🔗 連結
- **Web Demo**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai
- **GitHub**: https://github.com/KY0126/SA-v3-
- **Dashboard**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai/dashboard
- **衝突協調中心**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai/module/conflict-coordination
- **系統流程圖/報表**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai/module/reports

## 📊 E2E 測試
- **User Stories**: 23 個
- **總測試數**: 244 checks
- **通過率**: 100.0% ✅

## 🗃️ 資料庫
- **引擎**: MariaDB 10.11 (MySQL 相容)
- **資料表**: 21 張 + conflict 擴充欄位
- **種子數據**: 102 社團、10 場地、8 活動、7 使用者、10 行事曆事件

## 🏗️ 系統架構（三階段資源調度）

```
[使用者提交預約] → [志願序配對 L1→L3]
    │
    ├── 無衝突 → 直接通過
    │
    └── 有衝突 → [衝突協調中心]
                    ├── 📧 郵件通知
                    ├── 💬 即時對話
                    ├── 🤖 AI 協商建議
                    └── ✅ 雙方確認 → [RAG 法規比對] → 核准
```

## ✅ 已完成功能 (16 大模組)

### 核心業務
| 模組 | 功能 | API |
|------|------|-----|
| 場地預約 | 三階段調度、衝突偵測、優先權配對 | `/api/reservations` |
| 衝突協調中心 ⭐ | 即時對話、郵件通知、雙方確認按鈕、AI建議 | `/api/conflicts/*` |
| 設備借用 | 借還流程、狀態篩選、到期提醒 | `/api/equipment` |
| 活動牆 | CRUD、報名、篩選、AI預審 | `/api/activities` |
| 社團資訊 | 102社團、8分類、搜尋、統計 | `/api/clubs` |
| 行事曆 | EvoCalendar、6種事件類型 | `/api/calendar/events` |

### AI 智慧模組
| 模組 | 功能 | API |
|------|------|-----|
| AI 預審 | 法規比對、風險分級、建議 | `/api/ai/pre-review` |
| AI 企劃生成 | 自動生成企劃書、預算、SDGs對應 | `/api/ai/generate-proposal` |
| AI 申訴摘要 | 案件摘要、建議方案、情緒分析 | `/api/appeals/{id}/ai-summary` |
| AI 協商建議 | 信心指數、超時規則 | `/api/conflicts/negotiate` |

### 管理與安全
| 模組 | 功能 | API |
|------|------|-----|
| 報修管理 | 追蹤碼、狀態流轉 | `/api/repairs` |
| 申訴記錄 | 4種類型、AI摘要 | `/api/appeals` |
| 信用積分 | 扣分機制、<60強制登出 | `/api/credits/{userId}` |
| 通知系統 | 系統/郵件/LINE多管道 | `/api/notifications/{userId}` |
| 數位證書 | 自動生成、數位簽章 | `/api/certificates/generate` |
| 多語言 i18n | 繁中/簡中/英/日/韓 | `/api/i18n/{lang}` |

## 👥 角色權限矩陣

| 功能 | Admin | Officer | Professor | Student | IT |
|------|-------|---------|-----------|---------|-----|
| Dashboard | ✅全域 | ✅社團 | ✅指導 | ✅個人 | ✅系統 |
| 場地預約/衝突協調 | ✅核准 | ✅申請 | ✅審閱 | ✅申請 | 🔧維護 |
| 設備借用 | ✅管理 | ✅借還 | 👁️瀏覽 | ✅借還 | 🔧維護 |
| 活動牆 | ✅管理 | ✅CRUD | ✅審閱 | ✅報名 | 🔧維護 |
| AI 預審 | ✅管理 | ✅使用 | ✅審閱 | 👁️瀏覽 | 🔧維護 |

## 📋 評分對照

| 評分項目 | 權重 | 對應功能 |
|---------|------|---------|
| 創新性 | 20% | 三階段調度系統、AI預審、衝突協調中心（Chat+Email+Confirm）、無障礙地圖 |
| 實用性 | 50% | 場地預約、設備借用、活動牆、社團管理、行事曆、報修、申訴、信用積分 |
| 技術完善度 | 10% | Laravel 12 + MySQL + RESTful API (30+端點) + E2E測試 (244 tests) |
| UI/UX 友善度 | 10% | Tailwind CSS + GSAP動畫 + Leaflet地圖 + Chart.js + 5角色切換 |
| 內容豐富度 | 10% | 102社團 + 10場地 + 5國語言 + SDGs對應 + 20+建築標示 |

## 🔧 部署說明

```bash
# 安裝依賴
composer install

# 環境設定
cp .env.example .env
# 設定 DB_CONNECTION=mysql, DB_DATABASE=fju_smart_hub

# 資料庫遷移
php artisan migrate:fresh --seed

# 啟動服務
php artisan serve --port=3000
```

## 📅 最後更新
- **日期**: 2026-04-21
- **版本**: 3.0.0
- **狀態**: ✅ Active
