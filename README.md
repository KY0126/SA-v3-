# FJU Smart Hub - 輔仁大學智慧校園管理平台

## Project Overview
- **Name**: FJU Smart Hub
- **Goal**: 整合 AI 智慧預審、資源調度、數位證書、活動管理的一站式校園解決方案
- **Version**: 2.1.0
- **適用學年度**: 114 學年度

## URLs
- **Demo**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai
- **GitHub**: https://github.com/KY0126/SA-v3-

## Tech Stack
- **Backend**: Hono Framework (TypeScript) on Cloudflare Workers
- **Frontend**: Vue 3 concepts + Tailwind CSS (CDN) + Vanilla JS
- **Map**: Leaflet.js (GeoJSON campus map + LayerGroups)
- **Calendar**: evo-calendar.js (Royal Navy theme)
- **Charts**: Chart.js (line, doughnut, bar, radar)
- **Animation**: GSAP + ScrollTrigger
- **Database**: Cloudflare D1 (SQLite)
- **AI**: Dify + GPT-4 + Pinecone (simulated)
- **Build**: Vite + @hono/vite-cloudflare-pages
- **Deploy**: Cloudflare Pages

## Features (已完成)

### Core Modules (17 Pages)
1. **Landing Page** (`/`) - Hero section, features, modules, testimonials
2. **Login** (`/login`) - Google OAuth, Turnstile, 5-role quick demo
3. **Dashboard** (`/dashboard`) - Role-specific charts, carousel, campus map
4. **Campus Map** (`/campus-map`) - GeoJSON polygon map with college colors
5. **場地預約** (`/module/venue-booking`) - 3-stage scheduling, booking modal
6. **設備借用** (`/module/equipment`) - Borrow/return flow, LINE reminders
7. **行事曆** (`/module/calendar`) - evo-calendar with API events
8. **社團資訊** (`/module/club-info`) - 117 clubs/associations, search/filter
9. **活動牆** (`/module/activity-wall`) - Activity browse/register
10. **AI 資訊概覽** (`/module/ai-overview`) - AI engine overview
11. **AI 導覽助理** (`/module/ai-guide`) - AI proposal generator
12. **法規查詢** (`/module/rag-search`) - RAG regulation query
13. **報修管理** (`/module/repair`) - Repair request submission
14. **申訴記錄** (`/module/appeal`) - Appeal with AI summary
15. **統計報表** (`/module/reports`) - SDG radar, charts
16. **E-Portfolio** (`/module/e-portfolio`) - Competency radar
17. **幹部證書** (`/module/certificate`) - Digital certificate generation

### API Endpoints (36+)
- CRUD: users, clubs, venues, activities, equipment, reservations
- AI: pre-review, proposal-generator, appeal-summary
- System: credits, notifications, calendar, certificates, conflicts, repairs, appeals
- i18n: 5 language packs (zh-TW, zh-CN, EN, JA, KO)
- Health: /api/health

### Data
- **114學年度社團**: 71 個 (學藝/康樂/體育/藝文/服務/宗教/自治)
- **114學年度學會**: 46 個 (12 學院系學會)
- **總計**: 117 個社團/學會組織, 4,482+ 社員
- **場地**: 10 個可借用場地
- **設備**: 8 件可借用設備

### Design Specs
- Colors: #002B5B (deep blue), #333333 (dark gray), #F4F6F8 (bg), #FFB800 (accent yellow)
- Font: Microsoft JhengHei (微軟正黑體)
- Rounded corners: 12-15px
- Double-layer header, sticky sidebar, AI chatbot FAB

### Interactive Features
- All forms submit to real API endpoints
- Equipment borrow modal with return date
- Venue booking with conflict detection
- Activity registration with capacity tracking
- AI proposal generator with budget breakdown
- RAG query with suggested questions
- Repair submission with tracking codes
- Appeal AI summary with sentiment analysis
- Credit score state machine (< 60 = force logout)

## SA Specification Document
- **File**: `FJU_Smart_Hub_SA_Specification.docx`
- **Content**: 11 chapters with auto-TOC
  1. 專案概述 (背景/目標/範圍/名詞)
  2. 系統架構 (技術棧/部署)
  3. 功能需求規格 (角色/模組/三階段調度)
  4. 資料庫設計 (18 tables, ER diagram)
  5. API 介面規格 (36+ endpoints)
  6. UI/UX 設計 (色彩/佈局/地圖)
  7. 核心流程圖 (5 Mermaid diagrams)
  8. 社團與學會資料
  9. 安全設計 (OAuth/JWT/2FA/WAF)
  10. 測試計畫
  11. 附錄 (頁面一覽/變更紀錄)

## Database Schema (18 tables)
users, clubs, club_members, venues, activities, activity_registrations, equipment, equipment_borrowings, reservations, conflicts, credit_logs, notification_logs, certificates, portfolio_entries, competency_scores, ai_review_logs, appeals, time_capsules

## Quick Start
```bash
npm install
npm run build
npx wrangler pages dev dist --ip 0.0.0.0 --port 3000
```

## Deployment
```bash
npm run build
npx wrangler pages deploy dist --project-name fju-smart-hub
```

---
天主教輔仁大學 © 2014-2024 版權所有 | Powered by Hono + Vue 3 + Dify AI
