# FJU Smart Hub - 輔仁大學智慧校園管理平台

## Project Overview
- **Name**: FJU Smart Hub v2.1.0
- **Goal**: 整合 AI 智慧預審、三階段資源調度、數位證書、活動管理的一站式校園解決方案
- **Template**: 114 學年度 SA 規格書模板
- **Date**: 2026-04-08

## URLs
- **Demo**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai
- **GitHub**: https://github.com/KY0126/SA-v3-
- **SA 規格書**: `FJU_Smart_Hub_SA_Specification.docx` (Word 含自動目錄)

## Tech Stack
| Layer | Technology |
|-------|-----------|
| Frontend | Vue 3 + Tailwind CSS + GSAP + Chart.js |
| Map | Leaflet.js + GeoJSON + 4 LayerGroups |
| Calendar | evo-calendar.js (Royal Navy theme) |
| Backend | Hono (demo) / PHP 8.3 Laravel (production) |
| Database | MySQL 8.0 (InnoDB) / D1 SQLite (demo) |
| AI | Dify AI + Pinecone + GPT-4 |
| Security | Google OAuth + 2FA (TOTP/SMS) + Cloudflare WAF |
| Storage | Cloudflare R2 |

## 60-30-10 Color Palette
| Ratio | Name | Code |
|-------|------|------|
| 60% | Angel White (Main) | `#FFFFFF` |
| 30% | Virgin Mary Blue (Secondary) | `#003153` |
| 10% | Vatican Gold (Accent) | `#DAA520` / `#FDB913` |

## Pages (16 routes)
| Route | Page |
|-------|------|
| `/` | Landing (Hero, Features, 10 Modules, Testimonials) |
| `/login` | Login (Google OAuth, Turnstile, Role Quick-Switch) |
| `/dashboard` | Dashboard (Role-specific stats, Charts, Carousel) |
| `/campus-map` | Interactive Campus Map (Leaflet.js, 4 LayerGroups) |
| `/module/venue-booking` | Venue Booking (3-Stage Scheduling) |
| `/module/equipment` | Equipment Management (Borrow/Return) |
| `/module/calendar` | Calendar (evo-calendar + API events) |
| `/module/club-info` | Club Info (114 clubs data, search/filter) |
| `/module/activity-wall` | Activity Wall (Card grid, registration) |
| `/module/ai-overview` | AI Overview (6 AI features) |
| `/module/ai-guide` | AI Proposal Generator (Dify Workflow) |
| `/module/rag-search` | Regulation RAG Search |
| `/module/rag-rental` | Rental Process RAG |
| `/module/repair` | Repair Management |
| `/module/appeal` | Appeal Records (AI Summary) |
| `/module/reports` | Statistics Reports (Charts) |

## API Endpoints (38+)
- `GET/POST/PUT/DELETE /api/users` - User CRUD
- `GET /api/clubs` - Club list (114 data, filter/search)
- `GET /api/venues` - Venue list + schedule
- `GET /api/activities` - Activity list + registration
- `GET/POST /api/equipment` - Equipment borrow/return/remind
- `POST /api/reservations` - 3-stage reservation
- `POST /api/ai/pre-review` - AI pre-review (RAG)
- `POST /api/ai/generate-proposal` - AI proposal generator
- `GET /api/credits/:userId` - Credit system
- `GET /api/calendar/events` - Calendar events
- `POST /api/certificates/generate` - Certificate generation
- `GET /api/dashboard/stats/:role` - Role stats
- `POST /api/conflicts/negotiate` - AI negotiation (3/6 min)
- `GET /api/repairs` / `GET /api/appeals` - Repair & Appeal
- `GET /api/i18n/:lang` - i18n (5 languages)
- `GET /api/health` - Health check

## Campus Map Features
- **Center**: 淨心堂 (25.0359, 121.4323) with golden circle overlay
- **LayerGroups**:
  - 教學行政區 (17 buildings, college color-coded)
  - 無障礙設施 (9 markers: ramps, elevators, accessible toilets)
  - 生活機能 (6 markers: restaurants, convenience store, post office)
  - 交通設施 (7 markers: MRT, YouBike, parking, shuttle)
- **Popup**: Building info card with "我要預約" button
- **Calendar Panel**: Glassmorphism (blur 15px), 40% width, GSAP slide animation

## Database Schema (19 tables)
users, clubs, club_members, venues, activities, activity_registrations,
equipment, equipment_borrowings, reservations, conflicts, credit_logs,
notification_logs, certificates, portfolio_entries, competency_scores,
ai_review_logs, appeals, time_capsules, outbox_table

## 5 User Roles
- **admin** (課指組/處室) - Full management
- **officer** (社團幹部) - Club management
- **professor** (指導教授) - Supervision
- **student** (一般學生) - Activities & E-Portfolio
- **it** (資訊中心) - System monitoring

## Deployment
- **Platform**: Cloudflare Pages (pending API key)
- **Status**: Demo running on sandbox
- **Build**: Vite 6 + @hono/vite-cloudflare-pages
- **PM2**: fju-smart-hub process
- **Last Updated**: 2026-04-08
