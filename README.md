# FJU Smart Hub v3.0 - Laravel + MySQL

## Project Overview
- **Name**: FJU Smart Hub (輔仁大學智慧校園管理平台)
- **Version**: 3.0.0
- **Backend**: PHP 8.2 / Laravel 12.56.0
- **Database**: MySQL 8.0 (SQLite for dev/demo)
- **Frontend**: Tailwind CSS (CDN) + Blade Templates + Chart.js + Leaflet.js + GSAP + evo-calendar
- **Architecture**: MVC with RESTful API (Full CRUD for all entities)

## URLs
- **Live Demo**: https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai
- **GitHub**: https://github.com/KY0126/SA-v3-

## Completed Features

### 21 Frontend Pages
| Route | Title | Description |
|-------|-------|-------------|
| `/` | Landing Page | Hero slider, features, 10 modules, testimonials, news ticker |
| `/login` | Login | Google OAuth + credentials + 5 role demo quick-links |
| `/dashboard?role=X` | Dashboard | Carousel, Leaflet map, Chart.js charts, role-aware stats |
| `/campus-map?role=X` | Campus Map | GeoJSON buildings + LayerGroups + college filter + search |
| `/module/venue-booking` | Venue Booking | 3-stage scheduling + CRUD + conflict detection |
| `/module/equipment` | Equipment | Borrow/return flow + CRUD + LINE notify |
| `/module/calendar` | Calendar | Evo-calendar + event CRUD |
| `/module/club-info` | Club Info | 102 clubs (114 academic year) + search + filter + CRUD |
| `/module/activity-wall` | Activity Wall | Filter + register + CRUD |
| `/module/ai-overview` | AI Overview | Module status dashboard |
| `/module/ai-guide` | AI Proposal | Generate proposals via AI |
| `/module/rag-search` | RAG Search | AI pre-review simulation |
| `/module/rag-rental` | Rental RAG | Rental regulation workflow |
| `/module/repair` | Repair | Full CRUD with tracking codes |
| `/module/appeal` | Appeal | Full CRUD + AI summary |
| `/module/reports` | Reports | Charts + statistics dashboard |
| `/module/e-portfolio` | E-Portfolio | Student competency portfolio |
| `/module/certificate` | Certificate | Auto-generate with digital signature |
| `/module/time-capsule` | Time Capsule | Digital time capsule module |
| `/module/2fa` | 2FA | Two-factor authentication module |

### 38+ API Endpoints (Full CRUD)
| Resource | Endpoints | Description |
|----------|-----------|-------------|
| **Users** | `GET/POST/PUT/DELETE /api/users` | User management CRUD |
| **Clubs** | `GET/POST/PUT/DELETE /api/clubs` | 102 clubs + filter/search + stats |
| **Venues** | `GET/POST/PUT/DELETE /api/venues` | Venue CRUD + schedule/availability |
| **Activities** | `GET/POST/PUT/DELETE /api/activities` | Activity CRUD + registration |
| **Equipment** | `GET/POST/PUT/DELETE /api/equipment` | Equipment CRUD + borrow/return |
| **Reservations** | `GET/POST/PUT/DELETE /api/reservations` | 3-stage reservation + conflict |
| **Conflicts** | `GET/POST /api/conflicts` | Conflict + AI negotiation |
| **Repairs** | `GET/POST/PUT/DELETE /api/repairs` | Repair CRUD with tracking |
| **Appeals** | `GET/POST/PUT/DELETE /api/appeals` | Appeal CRUD + AI summary |
| **Calendar** | `GET/POST/PUT/DELETE /api/calendar/events` | Calendar event CRUD |
| **Credits** | `GET /api/credits/{userId}` | Credit score system |
| **Notifications** | `GET /api/notifications/{userId}` | Notification system |
| **Dashboard** | `GET /api/dashboard/stats/{role}` | Role-specific dashboard stats |
| **AI Pre-review** | `POST /api/ai/pre-review` | AI RAG regulation review |
| **AI Proposal** | `POST /api/ai/generate-proposal` | AI proposal generator |
| **Certificates** | `POST /api/certificates/generate` | Digital certificate generation |
| **i18n** | `GET /api/i18n/{lang}` | 5-language i18n packs |
| **Health** | `GET /api/health` | System health check |

## Database Schema (21 Tables)
| Table | Key Fields | Description |
|-------|-----------|-------------|
| `users` | student_id, name, email, role, credit_score | 5 roles: admin, officer, professor, student, it |
| `clubs` | name, category, type, member_count | 102 clubs (114 academic year) |
| `club_members` | club_id, user_id, position | Club membership |
| `venues` | name, location, capacity, status, lat/lng | 10 campus venues |
| `activities` | title, club_id, venue_id, event_date, status | Activity management |
| `activity_registrations` | activity_id, user_id, status | Event registration |
| `equipment` | code, name, category, status | Equipment inventory |
| `equipment_borrowings` | equipment_id, borrower_id, dates | Borrow/return tracking |
| `reservations` | venue_id, user_id, date, time, stage | 3-stage reservation |
| `conflicts` | party_a, party_b, venue, status | Venue conflict management |
| `credit_logs` | user_id, action, points, reason | Credit score history |
| `notification_logs` | user_id, title, message, channel | Multi-channel notifications |
| `certificates` | user_id, certificate_code, signature | Digital certificates |
| `portfolio_entries` | user_id, title, category | E-Portfolio entries |
| `competency_scores` | user_id, dimension, score | Competency tracking |
| `ai_review_logs` | target_type, target_id, risk_level | AI review audit trail |
| `appeals` | code, appeal_type, status, ai_summary | Appeal management |
| `repairs` | code, target, status, assignee | Repair tracking |
| `calendar_events` | title, date, type, venue | Calendar events |
| `time_capsules` | club_id, term, r2_storage_key | Digital time capsules |
| `outbox` | event_type, payload, status | Event sourcing outbox |

## UI/UX Design Specifications
- **60-30-10 Color Rule**: Main `#FFFFFF`, Secondary `#003153` (FJU Blue), Accent `#DAA520`/`#FDB913` (Gold)
- **Font**: Microsoft JhengHei (微軟正黑體)
- **Border Radius**: 12px / 15px
- **Double-layer Header**: White top bar + Deep blue nav bar
- **Dark Gray Sidebar**: `#333333` with credit score dashboard + system status
- **Sticky AI Chatbot Widget**: Bottom-right floating chatbot (輔寶)
- **Glassmorphism**: `blur(15px)` frosted-glass effect
- **GSAP Animations**: Scroll-triggered entrance animations, hero slider
- **Chart.js**: Line charts, doughnut charts, radar charts
- **Leaflet.js**: Interactive campus map with building GeoJSON

## 5 User Roles
| Role | Label | Capabilities |
|------|-------|-------------|
| `admin` | 課指組/處室 | Full system management, approval, reports |
| `officer` | 社團幹部 | Club management, event creation, budget |
| `professor` | 指導教授 | Club supervision, risk alerts, performance |
| `student` | 一般學生 | Activity participation, portfolio, certificates |
| `it` | 資訊中心 | System monitoring, API metrics, WAF stats |

## Quick Start
```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Database (SQLite dev mode)
touch database/database.sqlite
php artisan migrate:fresh --seed

# Run development server
php artisan serve --host=0.0.0.0 --port=3000
```

## MySQL Production Setup
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fju_smart_hub
DB_USERNAME=root
DB_PASSWORD=your_password
```
```bash
php artisan migrate:fresh --seed
```

## Tech Stack
| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.2 + Laravel 12.56.0 |
| Database | MySQL 8.0 / SQLite (dev) |
| Frontend | Tailwind CSS (CDN) + Blade Templates |
| Charts | Chart.js 4.4 |
| Maps | Leaflet.js 1.9.4 |
| Animations | GSAP 3.12.5 |
| Calendar | evo-calendar 1.1.3 |
| Icons | FontAwesome 6.4.0 |
| jQuery | 3.7.1 (evo-calendar dependency) |

## Deployment Notes
- **Cloudflare Pages**: Not applicable (Laravel requires PHP runtime)
- **Recommended Hosting**: Laravel Forge, DigitalOcean App Platform, AWS EC2, Railway, Render
- **Sandbox Demo**: PM2 + php artisan serve on port 3000

## Project Structure
```
webapp/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/                    # RESTful API controllers
│   │   │   ├── UserController.php
│   │   │   ├── ClubController.php
│   │   │   ├── VenueController.php
│   │   │   ├── ActivityController.php
│   │   │   ├── EquipmentController.php
│   │   │   ├── ReservationController.php
│   │   │   └── CrudController.php   # Shared CRUD (conflicts, repairs, etc.)
│   │   └── PageController.php       # Web page routes
│   └── Models/                      # 18 Eloquent models
├── database/
│   ├── migrations/                  # Schema: 21 tables
│   └── seeders/                     # 102 clubs + demo data
├── resources/views/
│   ├── layouts/                     # app.blade.php, shell.blade.php
│   ├── pages/                       # Landing, login, dashboard, campus-map
│   │   ├── modules/                 # 16 module blade views
│   │   └── partials/                # Dashboard content partial
│   └── partials/                    # Chatbot, footer
├── routes/
│   ├── web.php                      # Page routes
│   └── api.php                      # 38+ API endpoints
├── public/
│   ├── static/                      # CSS, JS, images, campus.geojson
│   └── favicon.svg
├── ecosystem.config.cjs             # PM2 config
└── .env                             # Environment config
```

## Last Updated
2026-04-11 | v3.0.0 - Complete Laravel rewrite with full CRUD, 21 pages, 38+ APIs, 102 clubs
