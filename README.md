# FJU Smart Hub - 輔仁大學課指組智慧校園管理系統

## Project Overview
- **Name**: FJU Smart Hub
- **Goal**: 打造整合 AI 智慧預審、三階段資源調度、數位證書、大數據可視化的一站式校園課外活動管理平台
- **Tech Stack**: Hono + TypeScript + Cloudflare Pages (Demo) / PHP 8.3 Laravel + Vue 3 + MySQL 8.0 (Production)
- **AI Engine**: Dify AI + Pinecone RAG + GPT-4

## URLs
- **Demo**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai
- **API Health**: /api/health

## Features (已完成)

### 前端頁面
- [x] **動態 Landing Page** - GSAP 動畫、Hero Image 輪播、60-30-10 配色法則、痛點分析、功能特色、十大模組展示、技術架構、使用評價、最新公告
- [x] **登入頁面** - Glassmorphism 效果、Google OAuth UI、2FA 驗證碼、Cloudflare Turnstile、五國語言切換、角色快速體驗
- [x] **五角色儀表板** (Bento Grid Layout)
  - 課指組：趨勢折線、圓餅圖、Gauge、堆疊長條、SDGs 雷達、審核漏斗
  - 社團幹部：出勤長條、留存折線、滿意度圓餅、經費水滴
  - 指導教授：績效雷達、活動熱力、蛛網圖、風險燈號
  - 一般學生：履歷摘要、職能雷達、AI 推薦活動
  - 資訊中心：負載熱圖、API 延遲、WAF 日誌、R2 使用率

### 十大支柱模組
1. [x] **職能 E-Portfolio** - 個人資訊、職能標籤、活動經歷、雷達圖、PDF 匯出
2. [x] **AI 自動企劃生成器** - Dify Workflow、RAG 法規檢索、企劃書預覽
3. [x] **幹部證書自動化** - 即時預覽、數位簽章、QR Code 驗證
4. [x] **器材盤點與追蹤** - 器材列表、狀態管理、LINE/SMS 到期提醒
5. [x] **AI 智慧預審** - 文件上傳、RAG 結果、Reasoning JSON、風險標記
6. [x] **場地活化大數據** - 熱力圖、排行榜、三階段資源調度視覺化
7. [x] **AI 申訴摘要生成器** - 案件列表、WebSocket AI 摘要、仲裁功能
8. [x] **動態活動牆** - 關鍵字搜尋、標籤篩選、活動卡片、報名
9. [x] **數位時光膠囊** - R2 文件上傳、GPS 浮水印、歷史封裝紀錄
10. [x] **全方位 2FA** - TOTP/SMS 設定、QR Code、驗證記錄

### 後端 API
- [x] Users CRUD (`/api/users`)
- [x] Clubs CRUD (`/api/clubs`)
- [x] Venues CRUD (`/api/venues`)
- [x] Activities CRUD (`/api/activities`)
- [x] Equipment CRUD (`/api/equipment`)
- [x] Reservations (`/api/reservations`)
- [x] AI Pre-review (`/api/ai/pre-review`)
- [x] AI Proposal Generator (`/api/ai/generate-proposal`)
- [x] Credit System (`/api/credits/:userId`, `/api/credits/deduct`)
- [x] Notifications (`/api/notifications/:userId`)
- [x] Certificates (`/api/certificates/generate`)
- [x] Conflicts/Negotiation (`/api/conflicts`, `/api/conflicts/negotiate`)
- [x] Dashboard Stats (`/api/dashboard/stats/:role`)
- [x] Health Check (`/api/health`)

### 資料庫 Schema
- 19 張資料表完整設計（含索引、外鍵、CHECK 約束）
- users, clubs, club_members, venues, activities, activity_registrations
- equipment, equipment_borrowings, reservations, conflicts
- credit_logs, notification_logs, certificates, portfolio_entries
- competency_scores, ai_review_logs, appeals, time_capsules, outbox_table

### 系統分析規格書 (SA Document)
- [x] **Word 文件** - `FJU_Smart_Hub_SA_Specification.docx`
- [x] 自動目錄 (TOC)
- [x] 第一章：系統描述（問題陳述、利害關係人、需求蒐集、競爭者分析、市場定位）
- [x] 第二章：軟體需求規格（6 大 Epic、30+ 使用者故事、使用者故事卡）
- [x] 第三章：軟體設計規格（19 張資料表 Schema、E-R 關聯、介面設計、API 定義、5 核心流程圖）
- [x] 第四章：系統專題實作檢討（SWOT、問題解決、心得、展望）
- [x] 附錄（Sprint 1/2 進度報告、參考資料）
- [x] 5 種核心 Mermaid 流程圖

## Data Architecture
- **Database**: MySQL 8.0 (InnoDB) / Cloudflare D1 (SQLite, local dev)
- **Cache**: Redis (2FA, Task Timer, Pub/Sub)
- **Object Storage**: Cloudflare R2 (File uploads, GPS watermark)
- **Vector DB**: Pinecone (RAG knowledge base)
- **AI Engine**: Dify (Workflow + RAG)

## API Entry Points
| Method | Path | Description |
|--------|------|-------------|
| GET | `/` | Landing Page |
| GET | `/login` | Login Page |
| GET | `/dashboard?role=admin\|officer\|professor\|student\|it` | Role Dashboard |
| GET | `/module/:name` | Module Page |
| GET | `/api/health` | Health Check |
| GET | `/api/users` | User List |
| POST | `/api/ai/pre-review` | AI Pre-review |
| POST | `/api/ai/generate-proposal` | AI Proposal |
| GET | `/api/dashboard/stats/:role` | Dashboard Stats |

## Deployment
- **Platform**: Cloudflare Pages (Demo)
- **Status**: ✅ Active
- **Last Updated**: 2026-04-03
