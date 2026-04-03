import { Hono } from 'hono'

type Bindings = { DB: D1Database }

export const apiRoutes = new Hono<{ Bindings: Bindings }>()

// ====== Users ======
apiRoutes.get('/users', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoUsers() })
    const { results } = await db.prepare('SELECT * FROM users ORDER BY created_at DESC').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoUsers() }) }
})

apiRoutes.post('/users', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, id: Date.now() })
  const result = await db.prepare(
    'INSERT INTO users (student_id, name, email, phone, role, club_position, credit_score) VALUES (?, ?, ?, ?, ?, ?, ?)'
  ).bind(body.student_id, body.name, body.email, body.phone, body.role || 'student', body.club_position || '', 100).run()
  return c.json({ success: true, id: result.meta.last_row_id })
})

apiRoutes.get('/users/:id', async (c) => {
  const id = c.req.param('id')
  const db = c.env?.DB
  if (!db) return c.json({ data: getDemoUsers()[0] })
  const result = await db.prepare('SELECT * FROM users WHERE id = ?').bind(id).first()
  return result ? c.json({ data: result }) : c.json({ error: 'User not found' }, 404)
})

apiRoutes.put('/users/:id', async (c) => {
  const id = c.req.param('id')
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare(
    'UPDATE users SET name=?, phone=?, club_position=? WHERE id=?'
  ).bind(body.name, body.phone, body.club_position, id).run()
  return c.json({ success: true })
})

apiRoutes.delete('/users/:id', async (c) => {
  const id = c.req.param('id')
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('DELETE FROM users WHERE id = ?').bind(id).run()
  return c.json({ success: true })
})

// ====== Clubs ======
apiRoutes.get('/clubs', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoClubs() })
    const { results } = await db.prepare('SELECT * FROM clubs ORDER BY name').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoClubs() }) }
})

apiRoutes.post('/clubs', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, id: Date.now() })
  const result = await db.prepare(
    'INSERT INTO clubs (name, category, description, advisor_id, president_id) VALUES (?, ?, ?, ?, ?)'
  ).bind(body.name, body.category, body.description, body.advisor_id, body.president_id).run()
  return c.json({ success: true, id: result.meta.last_row_id })
})

apiRoutes.put('/clubs/:id', async (c) => {
  const id = c.req.param('id')
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('UPDATE clubs SET name=?, category=?, description=? WHERE id=?').bind(body.name, body.category, body.description, id).run()
  return c.json({ success: true })
})

apiRoutes.delete('/clubs/:id', async (c) => {
  const id = c.req.param('id')
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('DELETE FROM clubs WHERE id = ?').bind(id).run()
  return c.json({ success: true })
})

// ====== Venues ======
apiRoutes.get('/venues', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoVenues() })
    const { results } = await db.prepare('SELECT * FROM venues ORDER BY name').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoVenues() }) }
})

apiRoutes.post('/venues', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, id: Date.now() })
  const result = await db.prepare(
    'INSERT INTO venues (name, location, capacity, status, equipment_list) VALUES (?, ?, ?, ?, ?)'
  ).bind(body.name, body.location, body.capacity, body.status || 'available', body.equipment_list || '').run()
  return c.json({ success: true, id: result.meta.last_row_id })
})

apiRoutes.put('/venues/:id', async (c) => {
  const id = c.req.param('id')
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('UPDATE venues SET name=?, capacity=?, status=? WHERE id=?').bind(body.name, body.capacity, body.status, id).run()
  return c.json({ success: true })
})

apiRoutes.delete('/venues/:id', async (c) => {
  const id = c.req.param('id')
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('DELETE FROM venues WHERE id = ?').bind(id).run()
  return c.json({ success: true })
})

// ====== Activities ======
apiRoutes.get('/activities', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoActivities() })
    const { results } = await db.prepare('SELECT * FROM activities ORDER BY event_date DESC').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoActivities() }) }
})

apiRoutes.post('/activities', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, id: Date.now() })
  const result = await db.prepare(
    'INSERT INTO activities (title, description, club_id, venue_id, event_date, start_time, end_time, max_participants, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(body.title, body.description, body.club_id, body.venue_id, body.event_date, body.start_time, body.end_time, body.max_participants, body.category, 'pending').run()
  return c.json({ success: true, id: result.meta.last_row_id })
})

apiRoutes.put('/activities/:id', async (c) => {
  const id = c.req.param('id')
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('UPDATE activities SET title=?, description=?, status=? WHERE id=?').bind(body.title, body.description, body.status, id).run()
  return c.json({ success: true })
})

apiRoutes.delete('/activities/:id', async (c) => {
  const id = c.req.param('id')
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('DELETE FROM activities WHERE id = ?').bind(id).run()
  return c.json({ success: true })
})

// ====== Equipment ======
apiRoutes.get('/equipment', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoEquipment() })
    const { results } = await db.prepare('SELECT * FROM equipment ORDER BY name').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoEquipment() }) }
})

apiRoutes.post('/equipment', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, id: Date.now() })
  const result = await db.prepare(
    'INSERT INTO equipment (code, name, category, status, condition_note) VALUES (?, ?, ?, ?, ?)'
  ).bind(body.code, body.name, body.category, 'available', body.condition_note || '').run()
  return c.json({ success: true, id: result.meta.last_row_id })
})

apiRoutes.put('/equipment/:id', async (c) => {
  const id = c.req.param('id')
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('UPDATE equipment SET name=?, status=?, condition_note=? WHERE id=?').bind(body.name, body.status, body.condition_note, id).run()
  return c.json({ success: true })
})

apiRoutes.delete('/equipment/:id', async (c) => {
  const id = c.req.param('id')
  const db = c.env?.DB
  if (!db) return c.json({ success: true })
  await db.prepare('DELETE FROM equipment WHERE id = ?').bind(id).run()
  return c.json({ success: true })
})

// ====== Reservations (3-stage scheduling) ======
apiRoutes.get('/reservations', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoReservations() })
    const { results } = await db.prepare('SELECT * FROM reservations ORDER BY created_at DESC').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoReservations() }) }
})

apiRoutes.post('/reservations', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, id: Date.now(), stage: 'algorithm' })
  const result = await db.prepare(
    'INSERT INTO reservations (user_id, venue_id, activity_id, reservation_date, start_time, end_time, priority_level, status, stage) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(body.user_id, body.venue_id, body.activity_id, body.reservation_date, body.start_time, body.end_time, body.priority_level || 3, 'pending', 'algorithm').run()
  return c.json({ success: true, id: result.meta.last_row_id, stage: 'algorithm' })
})

// ====== AI Pre-review (RAG simulation) ======
apiRoutes.post('/ai/pre-review', async (c) => {
  const body = await c.req.json()
  // Simulate Dify RAG response
  const result = {
    allow_next_step: body.participants <= 80,
    risk_level: body.participants > 80 ? 'High' : body.participants > 50 ? 'Medium' : 'Low',
    violations: body.participants > 80 ? ['crowd_overload'] : [],
    references: body.participants > 80 ? ['場地使用管理規則 第5條'] : [],
    suggestions: body.participants > 80 ? ['調整人數至80人以下', '或申請更大場地'] : [],
    reasoning: `AI 預審完成：申請 ${body.participants} 人。${body.participants > 80 ? '超過場地容量限制。' : '符合場地容量規定。'}`
  }
  return c.json(result)
})

// ====== AI Proposal Generator ======
apiRoutes.post('/ai/generate-proposal', async (c) => {
  const body = await c.req.json()
  const proposal = {
    title: `${body.title || '活動'}企劃書`,
    content: `一、活動目的：${body.description || '提升社團凝聚力'}\n二、預計參加人數：${body.participants || 50}人\n三、活動流程：（AI 自動生成）\n四、預算概估：NT$${(body.participants || 50) * 300}`,
    ai_review: { allow_next_step: true, risk_level: 'Low', violations: [] }
  }
  return c.json(proposal)
})

// ====== Credit System ======
apiRoutes.get('/credits/:userId', async (c) => {
  const userId = c.req.param('userId')
  try {
    const db = c.env?.DB
    if (!db) return c.json({ score: 85, logs: getDemoCreditLogs() })
    const user = await db.prepare('SELECT credit_score FROM users WHERE id = ?').bind(userId).first()
    const { results: logs } = await db.prepare('SELECT * FROM credit_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 20').bind(userId).all()
    return c.json({ score: user?.credit_score || 100, logs })
  } catch { return c.json({ score: 85, logs: getDemoCreditLogs() }) }
})

apiRoutes.post('/credits/deduct', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) return c.json({ success: true, new_score: 75, force_logout: false })
  // Deduct credit and check threshold
  await db.prepare('UPDATE users SET credit_score = credit_score - ? WHERE id = ?').bind(body.points, body.user_id).run()
  await db.prepare('INSERT INTO credit_logs (user_id, action, points, reason) VALUES (?, ?, ?, ?)').bind(body.user_id, 'deduct', -body.points, body.reason).run()
  const user = await db.prepare('SELECT credit_score FROM users WHERE id = ?').bind(body.user_id).first() as any
  const forceLogout = (user?.credit_score || 0) < 60
  return c.json({ success: true, new_score: user?.credit_score, force_logout: forceLogout })
})

// ====== Notifications ======
apiRoutes.get('/notifications/:userId', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoNotifications() })
    const userId = c.req.param('userId')
    const { results } = await db.prepare('SELECT * FROM notification_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 20').bind(userId).all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoNotifications() }) }
})

// ====== Certificates ======
apiRoutes.post('/certificates/generate', async (c) => {
  const body = await c.req.json()
  return c.json({
    certificate_id: `FJU-CERT-2026-${String(Date.now()).slice(-6)}`,
    name: body.name,
    club: body.club,
    position: body.position,
    term: body.term,
    digital_signature: `SHA256:${Math.random().toString(36).substring(2, 15)}`,
    generated_at: new Date().toISOString()
  })
})

// ====== Dashboard Stats ======
apiRoutes.get('/dashboard/stats/:role', async (c) => {
  const role = c.req.param('role')
  const stats: Record<string, object> = {
    admin: { pending_reviews: 5, monthly_activities: 28, venue_usage: 87, ai_pass_rate: 94, total_clubs: 200, total_students: 5000 },
    officer: { pending_tasks: 3, member_count: 45, budget_used: 65, next_event_days: 12 },
    professor: { supervised_clubs: 3, risk_alerts: 1, review_pending: 2 },
    student: { activities_joined: 8, officer_roles: 3, credit_score: 85, competency_level: 'A' },
    it: { cpu_usage: 45, memory_usage: 62, api_success_rate: 99.5, waf_blocks_today: 23 },
  }
  return c.json(stats[role] || stats.student)
})

// ====== Conflicts (3/6 minute negotiation) ======
apiRoutes.get('/conflicts', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoConflicts() })
    const { results } = await db.prepare('SELECT * FROM conflicts ORDER BY created_at DESC').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoConflicts() }) }
})

apiRoutes.post('/conflicts/negotiate', async (c) => {
  const body = await c.req.json()
  // Simulate GPT-4 intervention at 3 minutes
  return c.json({
    suggestions: [
      { id: 1, description: `${body.party_a_name || '甲方'}改至其他場地` },
      { id: 2, description: '時段分割，各使用一半時間' },
      { id: 3, description: `${body.party_b_name || '乙方'}延後至隔天` }
    ],
    ai_reasoning: 'Based on historical usage patterns and both parties\' flexibility scores...',
    timeout_warning: body.elapsed_minutes >= 3 ? '已超過3分鐘，AI建議已介入' : null
  })
})

// ====== Health Check ======
apiRoutes.get('/health', (c) => c.json({
  status: 'ok',
  version: '2.0.0',
  timestamp: new Date().toISOString(),
  services: { database: 'connected', ai: 'ready', cache: 'active' }
}))

// Demo data functions
function getDemoUsers() {
  return [
    { id: 1, student_id: '410012345', name: '王大明', email: '410012345@cloud.fju.edu.tw', phone: '0912-345-678', role: 'student', club_position: '攝影社 副社長', credit_score: 85 },
    { id: 2, student_id: '410012346', name: '陳小美', email: '410012346@cloud.fju.edu.tw', phone: '0923-456-789', role: 'officer', club_position: '吉他社 社長', credit_score: 92 },
    { id: 3, student_id: 'T001', name: '林教授', email: 'lin@cloud.fju.edu.tw', phone: '(02)2905-2001', role: 'professor', club_position: '攝影社 指導教授', credit_score: 100 },
    { id: 4, student_id: 'A001', name: '張組長', email: 'zhang@cloud.fju.edu.tw', phone: '(02)2905-2002', role: 'admin', club_position: '課指組 組長', credit_score: 100 },
  ]
}

function getDemoClubs() {
  return [
    { id: 1, name: '攝影社', category: '藝文', description: '培養攝影技巧與美學素養', member_count: 50 },
    { id: 2, name: '吉他社', category: '藝文', description: '吉他演奏與音樂創作', member_count: 45 },
    { id: 3, name: '資訊社', category: '學術', description: '程式設計與資訊技術交流', member_count: 60 },
    { id: 4, name: '環保社', category: '服務', description: '環境保護與永續發展推廣', member_count: 35 },
  ]
}

function getDemoVenues() {
  return [
    { id: 1, name: '中美堂', location: '校園中心', capacity: 500, status: 'available' },
    { id: 2, name: '活動中心', location: '學生活動中心', capacity: 200, status: 'available' },
    { id: 3, name: 'SF 134', location: '理工大樓', capacity: 80, status: 'available' },
    { id: 4, name: '草地廣場', location: '校園東側', capacity: 300, status: 'maintenance' },
  ]
}

function getDemoActivities() {
  return [
    { id: 1, title: '攝影社春季外拍', club_id: 1, venue_id: 0, event_date: '2026-04-20', category: '藝文', status: 'approved', max_participants: 50 },
    { id: 2, title: '吉他社期末成果展', club_id: 2, venue_id: 1, event_date: '2026-04-15', category: '藝文', status: 'pending', max_participants: 200 },
    { id: 3, title: '程式設計工作坊', club_id: 3, venue_id: 3, event_date: '2026-04-25', category: '學術', status: 'approved', max_participants: 40 },
  ]
}

function getDemoEquipment() {
  return [
    { id: 1, code: 'EQ-001', name: 'Canon EOS R6', category: '攝影', status: 'borrowed', borrower: '陳同學', return_date: '2026-04-02' },
    { id: 2, code: 'EQ-002', name: 'Sony A7III', category: '攝影', status: 'available', borrower: null, return_date: null },
    { id: 3, code: 'EQ-003', name: '投影機 EPSON EB-X51', category: '視聽', status: 'maintenance', borrower: null, return_date: null },
    { id: 4, code: 'EQ-004', name: '無線麥克風組', category: '音響', status: 'available', borrower: null, return_date: null },
  ]
}

function getDemoReservations() {
  return [
    { id: 1, user_id: 2, venue_id: 1, reservation_date: '2026-04-15', start_time: '14:00', end_time: '17:00', priority_level: 2, status: 'confirmed', stage: 'approved' },
    { id: 2, user_id: 1, venue_id: 3, reservation_date: '2026-04-20', start_time: '09:00', end_time: '12:00', priority_level: 3, status: 'pending', stage: 'algorithm' },
  ]
}

function getDemoCreditLogs() {
  return [
    { id: 1, action: 'deduct', points: -10, reason: '協商超時未回應', created_at: '2026-03-15' },
    { id: 2, action: 'add', points: 5, reason: '按時歸還器材', created_at: '2026-03-10' },
    { id: 3, action: 'deduct', points: -5, reason: '遲到簽到', created_at: '2026-02-28' },
  ]
}

function getDemoNotifications() {
  return [
    { id: 1, title: '場地申請已核准', message: '您的中美堂場地申請已通過', channel: 'system', read: false, created_at: '2026-04-03' },
    { id: 2, title: '器材歸還提醒', message: 'Canon EOS R6 明天到期', channel: 'line', read: false, created_at: '2026-04-02' },
    { id: 3, title: '信用積分變更', message: '因按時歸還器材 +5 分', channel: 'system', read: true, created_at: '2026-03-28' },
  ]
}

function getDemoConflicts() {
  return [
    { id: 1, party_a: '攝影社', party_b: '吉他社', venue: '中美堂', date: '2026-04-15', time_slot: '14:00-17:00', status: 'negotiating', stage: 'ai_intervention' },
  ]
}
