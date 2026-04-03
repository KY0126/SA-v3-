-- FJU Smart Hub - Initial Database Schema
-- MySQL 8.0 (InnoDB) compatible, adapted for Cloudflare D1

-- ====== Users ======
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  student_id TEXT UNIQUE NOT NULL,
  name TEXT NOT NULL,
  email TEXT UNIQUE NOT NULL,
  phone TEXT,
  role TEXT NOT NULL DEFAULT 'student' CHECK(role IN ('student', 'officer', 'professor', 'admin', 'it')),
  club_position TEXT DEFAULT '',
  credit_score INTEGER NOT NULL DEFAULT 100,
  google_oauth_id TEXT,
  two_factor_enabled INTEGER DEFAULT 0,
  two_factor_secret TEXT,
  avatar_url TEXT,
  language TEXT DEFAULT 'zh-TW',
  is_active INTEGER DEFAULT 1,
  last_login_at TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_users_student_id ON users(student_id);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);
CREATE INDEX IF NOT EXISTS idx_users_credit_score ON users(credit_score);

-- ====== Clubs ======
CREATE TABLE IF NOT EXISTS clubs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT UNIQUE NOT NULL,
  category TEXT NOT NULL CHECK(category IN ('academic', 'service', 'recreation', 'sports', 'arts', 'general')),
  description TEXT,
  advisor_id INTEGER,
  president_id INTEGER,
  member_count INTEGER DEFAULT 0,
  established_year INTEGER,
  is_active INTEGER DEFAULT 1,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (advisor_id) REFERENCES users(id),
  FOREIGN KEY (president_id) REFERENCES users(id)
);

CREATE INDEX IF NOT EXISTS idx_clubs_category ON clubs(category);

-- ====== Club Members ======
CREATE TABLE IF NOT EXISTS club_members (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  club_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  position TEXT DEFAULT 'member',
  joined_at TEXT DEFAULT (datetime('now')),
  left_at TEXT,
  FOREIGN KEY (club_id) REFERENCES clubs(id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  UNIQUE(club_id, user_id)
);

-- ====== Venues ======
CREATE TABLE IF NOT EXISTS venues (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  location TEXT,
  capacity INTEGER NOT NULL,
  status TEXT DEFAULT 'available' CHECK(status IN ('available', 'maintenance', 'reserved', 'closed')),
  equipment_list TEXT DEFAULT '',
  floor_plan_url TEXT,
  latitude REAL,
  longitude REAL,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_venues_status ON venues(status);

-- ====== Activities ======
CREATE TABLE IF NOT EXISTS activities (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  title TEXT NOT NULL,
  description TEXT,
  club_id INTEGER,
  venue_id INTEGER,
  organizer_id INTEGER,
  event_date TEXT NOT NULL,
  start_time TEXT,
  end_time TEXT,
  max_participants INTEGER DEFAULT 0,
  current_participants INTEGER DEFAULT 0,
  category TEXT CHECK(category IN ('academic', 'service', 'recreation', 'sports', 'arts', 'general')),
  status TEXT DEFAULT 'draft' CHECK(status IN ('draft', 'pending', 'ai_reviewing', 'approved', 'rejected', 'cancelled', 'completed')),
  approval_number TEXT,
  ai_review_result TEXT,
  budget_amount REAL DEFAULT 0,
  budget_used REAL DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (club_id) REFERENCES clubs(id),
  FOREIGN KEY (venue_id) REFERENCES venues(id),
  FOREIGN KEY (organizer_id) REFERENCES users(id)
);

CREATE INDEX IF NOT EXISTS idx_activities_club ON activities(club_id);
CREATE INDEX IF NOT EXISTS idx_activities_date ON activities(event_date);
CREATE INDEX IF NOT EXISTS idx_activities_status ON activities(status);

-- ====== Activity Registrations ======
CREATE TABLE IF NOT EXISTS activity_registrations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  activity_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,
  status TEXT DEFAULT 'registered' CHECK(status IN ('registered', 'attended', 'absent', 'cancelled')),
  check_in_at TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (activity_id) REFERENCES activities(id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  UNIQUE(activity_id, user_id)
);

-- ====== Equipment ======
CREATE TABLE IF NOT EXISTS equipment (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  code TEXT UNIQUE NOT NULL,
  name TEXT NOT NULL,
  category TEXT,
  status TEXT DEFAULT 'available' CHECK(status IN ('available', 'borrowed', 'maintenance', 'lost', 'retired')),
  condition_note TEXT,
  purchase_date TEXT,
  purchase_cost REAL,
  image_url TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now'))
);

CREATE INDEX IF NOT EXISTS idx_equipment_status ON equipment(status);
CREATE INDEX IF NOT EXISTS idx_equipment_code ON equipment(code);

-- ====== Equipment Borrowings ======
CREATE TABLE IF NOT EXISTS equipment_borrowings (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  equipment_id INTEGER NOT NULL,
  borrower_id INTEGER NOT NULL,
  borrow_date TEXT NOT NULL,
  expected_return_date TEXT NOT NULL,
  actual_return_date TEXT,
  status TEXT DEFAULT 'active' CHECK(status IN ('active', 'returned', 'overdue', 'lost')),
  condition_on_return TEXT,
  reminder_sent INTEGER DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (equipment_id) REFERENCES equipment(id),
  FOREIGN KEY (borrower_id) REFERENCES users(id)
);

CREATE INDEX IF NOT EXISTS idx_borrowings_status ON equipment_borrowings(status);

-- ====== Reservations (3-Stage Scheduling) ======
CREATE TABLE IF NOT EXISTS reservations (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  venue_id INTEGER NOT NULL,
  activity_id INTEGER,
  reservation_date TEXT NOT NULL,
  start_time TEXT NOT NULL,
  end_time TEXT NOT NULL,
  priority_level INTEGER DEFAULT 3 CHECK(priority_level IN (1, 2, 3)),
  status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'confirmed', 'conflicted', 'negotiating', 'approved', 'rejected', 'cancelled')),
  stage TEXT DEFAULT 'algorithm' CHECK(stage IN ('algorithm', 'negotiation', 'approval', 'completed')),
  preference_order TEXT,
  ai_review_result TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (venue_id) REFERENCES venues(id),
  FOREIGN KEY (activity_id) REFERENCES activities(id)
);

CREATE INDEX IF NOT EXISTS idx_reservations_date ON reservations(reservation_date);
CREATE INDEX IF NOT EXISTS idx_reservations_venue ON reservations(venue_id);
CREATE INDEX IF NOT EXISTS idx_reservations_status ON reservations(status);

-- ====== Conflicts (Negotiation) ======
CREATE TABLE IF NOT EXISTS conflicts (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  reservation_a_id INTEGER NOT NULL,
  reservation_b_id INTEGER NOT NULL,
  venue_id INTEGER NOT NULL,
  conflict_date TEXT NOT NULL,
  time_slot TEXT NOT NULL,
  status TEXT DEFAULT 'open' CHECK(status IN ('open', 'negotiating', 'ai_intervention', 'resolved', 'forced_close')),
  negotiation_start_at TEXT,
  ai_intervention_at TEXT,
  resolution TEXT,
  ai_suggestions TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (reservation_a_id) REFERENCES reservations(id),
  FOREIGN KEY (reservation_b_id) REFERENCES reservations(id),
  FOREIGN KEY (venue_id) REFERENCES venues(id)
);

-- ====== Credit Logs ======
CREATE TABLE IF NOT EXISTS credit_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  action TEXT NOT NULL CHECK(action IN ('add', 'deduct')),
  points INTEGER NOT NULL,
  reason TEXT NOT NULL,
  reference_type TEXT,
  reference_id INTEGER,
  created_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX IF NOT EXISTS idx_credit_logs_user ON credit_logs(user_id);

-- ====== Notification Logs ======
CREATE TABLE IF NOT EXISTS notification_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  title TEXT NOT NULL,
  message TEXT NOT NULL,
  channel TEXT DEFAULT 'system' CHECK(channel IN ('system', 'line', 'sms', 'email')),
  read INTEGER DEFAULT 0,
  tracking_token TEXT UNIQUE,
  read_at TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX IF NOT EXISTS idx_notifications_user ON notification_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_notifications_read ON notification_logs(read);

-- ====== Certificates ======
CREATE TABLE IF NOT EXISTS certificates (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  club_id INTEGER NOT NULL,
  position TEXT NOT NULL,
  term TEXT NOT NULL,
  certificate_code TEXT UNIQUE NOT NULL,
  digital_signature TEXT,
  issued_at TEXT DEFAULT (datetime('now')),
  pdf_url TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (club_id) REFERENCES clubs(id)
);

-- ====== E-Portfolio Entries ======
CREATE TABLE IF NOT EXISTS portfolio_entries (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  title TEXT NOT NULL,
  description TEXT,
  category TEXT,
  tags TEXT,
  activity_id INTEGER,
  evidence_url TEXT,
  created_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (activity_id) REFERENCES activities(id)
);

CREATE INDEX IF NOT EXISTS idx_portfolio_user ON portfolio_entries(user_id);

-- ====== Competency Scores ======
CREATE TABLE IF NOT EXISTS competency_scores (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL,
  dimension TEXT NOT NULL,
  score INTEGER NOT NULL DEFAULT 0,
  updated_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (user_id) REFERENCES users(id),
  UNIQUE(user_id, dimension)
);

-- ====== AI Review Logs ======
CREATE TABLE IF NOT EXISTS ai_review_logs (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  target_type TEXT NOT NULL,
  target_id INTEGER NOT NULL,
  reviewer_type TEXT DEFAULT 'dify_rag',
  input_data TEXT,
  output_data TEXT,
  allow_next_step INTEGER,
  risk_level TEXT,
  violations TEXT,
  references_cited TEXT,
  created_at TEXT DEFAULT (datetime('now'))
);

-- ====== Appeals ======
CREATE TABLE IF NOT EXISTS appeals (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  appellant_id INTEGER NOT NULL,
  appeal_type TEXT NOT NULL,
  subject TEXT NOT NULL,
  description TEXT NOT NULL,
  status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'processing', 'resolved', 'rejected')),
  ai_summary TEXT,
  resolution TEXT,
  assigned_to INTEGER,
  created_at TEXT DEFAULT (datetime('now')),
  updated_at TEXT DEFAULT (datetime('now')),
  FOREIGN KEY (appellant_id) REFERENCES users(id),
  FOREIGN KEY (assigned_to) REFERENCES users(id)
);

-- ====== Time Capsules (R2 Document Handover) ======
CREATE TABLE IF NOT EXISTS time_capsules (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  club_id INTEGER NOT NULL,
  created_by INTEGER NOT NULL,
  received_by INTEGER,
  term TEXT NOT NULL,
  file_manifest TEXT,
  r2_storage_key TEXT,
  gps_watermark TEXT,
  status TEXT DEFAULT 'sealed' CHECK(status IN ('sealed', 'transferred', 'received')),
  sealed_at TEXT DEFAULT (datetime('now')),
  transferred_at TEXT,
  received_at TEXT,
  FOREIGN KEY (club_id) REFERENCES clubs(id),
  FOREIGN KEY (created_by) REFERENCES users(id),
  FOREIGN KEY (received_by) REFERENCES users(id)
);

-- ====== Outbox Table (Async Processing) ======
CREATE TABLE IF NOT EXISTS outbox_table (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  event_type TEXT NOT NULL,
  payload TEXT NOT NULL,
  status TEXT DEFAULT 'pending' CHECK(status IN ('pending', 'processing', 'completed', 'failed')),
  retry_count INTEGER DEFAULT 0,
  created_at TEXT DEFAULT (datetime('now')),
  processed_at TEXT
);

CREATE INDEX IF NOT EXISTS idx_outbox_status ON outbox_table(status);
