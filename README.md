# FJU Smart Hub v3.0 - Laravel + MySQL

## Project Overview
- **Name**: FJU Smart Hub (輔仁大學智慧校園管理平台)
- **Version**: 3.0.0
- **Backend**: PHP 8.2 / Laravel 12
- **Database**: MySQL 8.0 (SQLite for dev)
- **Frontend**: Vue 3 + Tailwind CSS (CDN) + Blade Templates
- **Architecture**: MVC with RESTful API

## Live Demo
- **URL**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai
- **GitHub**: https://github.com/KY0126/SA-v3-

## Features (21 Pages, 38+ API Endpoints)

### Pages
| Route | Title | Description |
|-------|-------|-------------|
| `/` | Landing Page | Hero, features, 10 modules, testimonials |
| `/login` | Login | Google OAuth + credentials + role demo |
| `/dashboard` | Dashboard | Carousel, map, charts, stats |
| `/campus-map` | Campus Map | GeoJSON + LayerGroups + search |
| `/module/venue-booking` | Venue Booking | 3-stage scheduling + CRUD |
| `/module/equipment` | Equipment | Borrow/return flow + CRUD |
| `/module/calendar` | Calendar | Evo-calendar + event CRUD |
| `/module/club-info` | Club Info | 114 clubs + search + CRUD |
| `/module/activity-wall` | Activity Wall | Filter + register + CRUD |
| `/module/ai-overview` | AI Overview | Module status |
| `/module/ai-guide` | AI Proposal | Generate proposals via AI |
| `/module/rag-search` | RAG Search | AI pre-review simulation |
| `/module/rag-rental` | Rental RAG | Rental workflow |
| `/module/repair` | Repair | CRUD with tracking |
| `/module/appeal` | Appeal | CRUD + AI summary |
| `/module/reports` | Reports | Charts + statistics |
| `/module/e-portfolio` | E-Portfolio | Module ready |
| `/module/certificate` | Certificate | Auto-generate with signature |
| `/module/time-capsule` | Time Capsule | Module ready |
| `/module/2fa` | 2FA | Module ready |

### API Endpoints (Full CRUD)
- `GET/POST /api/users` - User CRUD
- `GET/POST /api/clubs` - Club CRUD with filter/search
- `GET/POST /api/venues` - Venue CRUD + schedule
- `GET/POST /api/activities` - Activity CRUD + registration
- `GET/POST /api/equipment` - Equipment CRUD + borrow/return
- `GET/POST /api/reservations` - 3-stage reservation system
- `GET/POST /api/conflicts` - Conflict + negotiation
- `GET/POST /api/repairs` - Repair CRUD
- `GET/POST /api/appeals` - Appeal CRUD + AI summary
- `GET/POST /api/calendar/events` - Calendar CRUD
- `GET /api/credits/{userId}` - Credit system
- `POST /api/ai/pre-review` - AI RAG review
- `POST /api/ai/generate-proposal` - AI proposal generator
- `POST /api/certificates/generate` - Certificate generator
- `GET /api/health` - Health check

## Database (21 Tables)
users, clubs, club_members, venues, activities, activity_registrations,
equipment, equipment_borrowings, reservations, conflicts, credit_logs,
notification_logs, certificates, portfolio_entries, competency_scores,
ai_review_logs, appeals, repairs, calendar_events, time_capsules, outbox

## UI/UX Specs
- **60-30-10 Color Rule**: Main #FFFFFF, Secondary #003153, Accent #DAA520/#FDB913
- **Font**: Microsoft JhengHei (微軟正黑體)
- **Border Radius**: 12px / 15px
- **Double-layer Header** (white + deep blue)
- **Dark Gray Sidebar** (#333333)
- **Sticky AI Chatbot Widget** (輔寶 🐕)
- **Glassmorphism**: blur(15px)

## Quick Start
```bash
# Install
composer install

# Database
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# Run
php artisan serve --host=0.0.0.0 --port=3000
```

## For MySQL Production
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fju_smart_hub
DB_USERNAME=root
DB_PASSWORD=your_password
```

## Tech Stack
- PHP 8.2 + Laravel 12
- MySQL 8.0 / SQLite (dev)
- Tailwind CSS (CDN)
- Chart.js, Leaflet.js, GSAP, evo-calendar
- FontAwesome 6

## 5 User Roles
- **admin** (課指組/處室)
- **officer** (社團幹部)
- **professor** (指導教授)
- **student** (一般學生)
- **it** (資訊中心)

## Last Updated
2026-04-11 | v3.0.0 Laravel Rewrite
