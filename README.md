# FJU Smart Hub - 輔仁大學智慧校園管理平台

## Project Overview
- **Name**: FJU Smart Hub
- **Goal**: 整合 AI 智慧預審、資源調度、數位證書、活動管理的一站式校園解決方案
- **Tech Stack**: Hono + TypeScript + Tailwind CSS + Leaflet.js + evo-calendar.js + Chart.js + GSAP

## Design Specifications
- **Colors**: Deep Blue `#002B5B` (header/footer/title), Dark Gray `#333333` (sidebar), Background `#F4F6F8`, Accent Yellow `#FFB800` (buttons/popups/labels)
- **Font**: 微軟正黑體 (Microsoft JhengHei)
- **Border Radius**: 12-15px
- **Layout**: Double-layer header + sticky left sidebar + main content + footer

## Features

### Double-Layer Header
- **Top (White)**: Logo + "天主教輔仁大學" + green subtitle "學務處 課外活動指導組" | Home, 輔仁大學, ENGLISH, 網站地圖
- **Bottom (Deep Blue)**: 認識課指組, 學會．社團, 場地/器材借用, 重要訊息, 表單下載, 常見問題

### Sidebar (Dark Gray #333333)
- **主要功能**: Dashboard, 場地預約, 設備借用, 行事曆
- **社群與社團**: 社團資訊, 活動牆
- **AI 核心專區**: AI 資訊概覽, AI 導覽助理, 法規查詢 (RAG)
- **管理與報表**: 報修管理, 申訴記錄, 統計報表

### Interactive Campus Map (Leaflet.js)
- Centered on 淨心堂 with scroll-zoom and L.control.zoom
- 4 LayerGroups with toggle buttons:
  1. **教學行政區**: All campus buildings (17 markers) with college info
  2. **無障礙**: Ramps, elevators, accessible restrooms (9 markers)
  3. **生活**: Cafeterias (輔園/理園/心園), dormitories (宜聖/立言/格物)
  4. **交通**: MRT station, YouBike, parking, entrance gates
- Custom popups: 12px rounded, deep-blue header, building name + college + 黃色 "我要預約" button

### Calendar (evo-calendar.js)
- Requires login verification (pre-process check)
- Shows campus events with color-coded categories
- Royal Navy theme customized to FJU colors

### AI Chatbot (Fixed Bottom-Right)
- Shiba-Inu (柴犬) avatar "輔寶"
- Prompt: "今天想要問輔寶甚麼事呢?"
- Auto-reply with context-aware responses
- Yellow FAB button with pulse animation

### Footer (Deep Blue #002B5B)
- Left: 校址 (24205 新北市新莊區中正路510號)
- Center: 粉專連結 (Facebook, Instagram, LINE)
- Right: 櫃台資訊 / 維護信箱
- Copyright: "天主教輔仁大學 © 2014-2024 版權所有"

### Main Content Area
- **Carousel**: Recent activities with yellow "了解更多" button
- **Function Cards**: 場地預約 and 設備借用 with "了解更多" links
- **Quick Stats**: 待審核, 本月活動, 場地使用率, AI通過率
- **Charts**: 社團參與趨勢 (line) + 活動類型分布 (doughnut)

## All Routes
| Route | Page |
|---|---|
| `/` | Landing page with GSAP animations |
| `/login` | Login with Google OAuth + 2FA |
| `/dashboard?role=X` | Dashboard (admin/officer/professor/student/it) |
| `/module/venue-booking` | 場地預約 (3-stage scheduling) |
| `/module/equipment` | 設備借用 |
| `/module/calendar` | 行事曆 (evo-calendar) |
| `/module/club-info` | 社團資訊 |
| `/module/activity-wall` | 活動牆 |
| `/module/ai-overview` | AI 資訊概覽 |
| `/module/ai-guide` | AI 導覽助理 |
| `/module/rag-search` | 法規查詢 (RAG) |
| `/module/repair` | 報修管理 |
| `/module/appeal` | 申訴記錄 |
| `/module/reports` | 統計報表 |

## API Endpoints
| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/health` | Health check |
| GET/POST | `/api/users` | User CRUD |
| GET/POST | `/api/clubs` | Club management |
| GET/POST | `/api/venues` | Venue management |
| GET/POST | `/api/activities` | Activity management |
| GET/POST | `/api/equipment` | Equipment tracking |
| GET/POST | `/api/reservations` | 3-stage reservation system |
| POST | `/api/ai/pre-review` | AI RAG pre-review |
| POST | `/api/ai/generate-proposal` | AI proposal generation |
| GET/POST | `/api/credits/:userId` | Credit system |
| GET | `/api/notifications/:userId` | Notifications |
| POST | `/api/certificates/generate` | Certificate generation |
| GET | `/api/dashboard/stats/:role` | Dashboard statistics |
| GET/POST | `/api/conflicts` | Conflict negotiation |

## Deployment
- **Platform**: Cloudflare Pages
- **Status**: ✅ Active (Development)
- **Last Updated**: 2026-04-05
