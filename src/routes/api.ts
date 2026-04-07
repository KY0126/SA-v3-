import { Hono } from 'hono'
import { clubs114, associations114, allClubsAndAssociations, getClubStats } from '../data/clubs'

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

// ====== Clubs (114學年度完整資料) ======
apiRoutes.get('/clubs', async (c) => {
  const type = c.req.query('type') // 'club', 'association', or undefined for all
  const category = c.req.query('category')
  const search = c.req.query('search')?.toLowerCase()
  
  let data = type === 'club' ? clubs114 
           : type === 'association' ? associations114 
           : allClubsAndAssociations

  if (category && category !== 'all') {
    data = data.filter(c => c.category === category || c.categoryLabel === category)
  }
  if (search) {
    data = data.filter(c => 
      c.name.toLowerCase().includes(search) || 
      c.description.toLowerCase().includes(search) ||
      c.categoryLabel.toLowerCase().includes(search)
    )
  }
  return c.json({ data, total: data.length })
})

apiRoutes.get('/clubs/stats', (c) => {
  return c.json(getClubStats())
})

apiRoutes.get('/clubs/:id', (c) => {
  const id = parseInt(c.req.param('id'))
  const club = allClubsAndAssociations.find(c => c.id === id)
  return club ? c.json({ data: club }) : c.json({ error: 'Club not found' }, 404)
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

// ====== Venues (Complete with booking functionality) ======
apiRoutes.get('/venues', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoVenues() })
    const { results } = await db.prepare('SELECT * FROM venues ORDER BY name').all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoVenues() }) }
})

apiRoutes.get('/venues/:id', async (c) => {
  const id = parseInt(c.req.param('id'))
  const venues = getDemoVenues()
  const venue = venues.find(v => v.id === id)
  return venue ? c.json({ data: venue }) : c.json({ error: 'Venue not found' }, 404)
})

apiRoutes.get('/venues/:id/schedule', (c) => {
  const id = parseInt(c.req.param('id'))
  const date = c.req.query('date') || new Date().toISOString().split('T')[0]
  // Return time slot availability for a venue
  const slots = generateTimeSlots(id, date)
  return c.json({ venue_id: id, date, slots })
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

// ====== Activities (with filtering & pagination) ======
apiRoutes.get('/activities', async (c) => {
  const category = c.req.query('category')
  const status = c.req.query('status')
  const search = c.req.query('search')?.toLowerCase()
  let data = getDemoActivities()
  if (category && category !== 'all') data = data.filter(a => a.category === category)
  if (status) data = data.filter(a => a.status === status)
  if (search) data = data.filter(a => a.title.toLowerCase().includes(search) || (a.organizer || '').toLowerCase().includes(search))
  return c.json({ data, total: data.length })
})

apiRoutes.get('/activities/:id', (c) => {
  const id = parseInt(c.req.param('id'))
  const activity = getDemoActivities().find(a => a.id === id)
  return activity ? c.json({ data: activity }) : c.json({ error: 'Activity not found' }, 404)
})

apiRoutes.post('/activities', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) {
    return c.json({ 
      success: true, id: Date.now(), 
      message: '活動已建立，等待 AI 預審',
      ai_review: { status: 'pending', estimated_time: '30秒' }
    })
  }
  const result = await db.prepare(
    'INSERT INTO activities (title, description, club_id, venue_id, event_date, start_time, end_time, max_participants, category, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(body.title, body.description, body.club_id, body.venue_id, body.event_date, body.start_time, body.end_time, body.max_participants, body.category, 'pending').run()
  return c.json({ success: true, id: result.meta.last_row_id })
})

apiRoutes.post('/activities/:id/register', (c) => {
  const id = c.req.param('id')
  return c.json({ success: true, message: '報名成功', activity_id: id, registration_id: Date.now() })
})

apiRoutes.post('/activities/:id/cancel-registration', (c) => {
  const id = c.req.param('id')
  return c.json({ success: true, message: '已取消報名', activity_id: id })
})

// ====== Equipment (with borrow/return flow) ======
apiRoutes.get('/equipment', async (c) => {
  const status = c.req.query('status')
  const category = c.req.query('category')
  let data = getDemoEquipment()
  if (status) data = data.filter(e => e.status === status)
  if (category) data = data.filter(e => e.category === category)
  return c.json({ data, total: data.length })
})

apiRoutes.get('/equipment/:id', (c) => {
  const id = parseInt(c.req.param('id'))
  const eq = getDemoEquipment().find(e => e.id === id)
  return eq ? c.json({ data: eq }) : c.json({ error: 'Equipment not found' }, 404)
})

apiRoutes.post('/equipment/borrow', (c) => {
  return c.json({ 
    success: true, 
    borrow_id: Date.now(),
    message: '借用申請已送出，LINE 通知已發送',
    return_date: new Date(Date.now() + 7 * 86400000).toISOString().split('T')[0],
    reminder: { line: true, sms: false }
  })
})

apiRoutes.post('/equipment/return', (c) => {
  return c.json({ 
    success: true, 
    message: '歸還完成，信用積分 +5',
    credit_change: { points: 5, reason: '按時歸還器材', new_score: 90 }
  })
})

apiRoutes.post('/equipment/remind', (c) => {
  return c.json({
    success: true,
    message: '提醒已透過 LINE Notify 發送',
    channels: ['line', 'system']
  })
})

// ====== Reservations (3-stage scheduling - fully interactive) ======
apiRoutes.get('/reservations', async (c) => {
  const stage = c.req.query('stage')
  const status = c.req.query('status')
  let data = getDemoReservations()
  if (stage) data = data.filter(r => r.stage === stage)
  if (status) data = data.filter(r => r.status === status)
  return c.json({ data, total: data.length })
})

apiRoutes.post('/reservations', async (c) => {
  const body = await c.req.json()
  // Simulate 3-stage scheduling
  const priority = body.priority_level || 3
  const conflictCheck = Math.random() > 0.7 // 30% chance of conflict for demo
  
  if (conflictCheck) {
    return c.json({ 
      success: false,
      stage: 'negotiation',
      message: '偵測到時段衝突，進入第二階段協商',
      conflict: {
        conflicting_party: '吉他社',
        time_slot: `${body.start_time} - ${body.end_time}`,
        venue: body.venue_name || '中美堂',
        negotiation_timer: 180 // 3 minutes
      }
    })
  }
  
  return c.json({ 
    success: true, 
    id: Date.now(),
    stage: priority === 1 ? 'approved' : 'algorithm',
    message: priority === 1 ? '校方優先，直接通過' : '志願序配對完成，等待審核',
    priority_level: priority,
    estimated_approval: '2-3 個工作天'
  })
})

apiRoutes.post('/reservations/:id/negotiate', (c) => {
  return c.json({
    success: true,
    suggestions: [
      { id: 1, description: '甲方改至 SF 134 教室', confidence: 0.85 },
      { id: 2, description: '時段分割：14:00-15:30 / 15:30-17:00', confidence: 0.78 },
      { id: 3, description: '乙方延後至隔天同時段', confidence: 0.72 }
    ],
    ai_reasoning: '根據歷史使用記錄與雙方彈性分數，建議方案一最可行。',
    timeout_warning: '已超過3分鐘，AI建議已介入',
    timer_remaining: 180
  })
})

apiRoutes.post('/reservations/:id/accept-suggestion', (c) => {
  return c.json({
    success: true,
    message: '協商方案已接受，進入第三階段官方審核',
    stage: 'approval',
    next_step: 'RAG 法規比對 + Gatekeeping'
  })
})

// ====== AI Pre-review (RAG simulation - enhanced) ======
apiRoutes.post('/ai/pre-review', async (c) => {
  const body = await c.req.json()
  const participants = body.participants || 0
  const result = {
    allow_next_step: participants <= 80,
    risk_level: participants > 80 ? 'High' : participants > 50 ? 'Medium' : 'Low',
    violations: [] as string[],
    references: [] as string[],
    suggestions: [] as string[],
    reasoning: '',
    confidence: 0.95,
    reviewed_regulations: [
      '活動申請辦法 v2.1',
      '場地使用管理規則 v3.0',
      '安全管理規範 v1.8'
    ]
  }

  if (participants > 80) {
    result.violations = ['crowd_overload', 'safety_plan_required']
    result.references = ['場地使用管理規則 第5條', '安全管理規範 第3條']
    result.suggestions = ['調整人數至80人以下', '或申請更大場地（中美堂/體育館）', '提交安全計畫書']
    result.reasoning = `AI 預審完成：申請 ${participants} 人，超過場地容量限制。需另附安全計畫書。`
  } else if (participants > 50) {
    result.references = ['場地使用管理規則 第3條']
    result.suggestions = ['建議提前一週申請', '準備活動保險文件']
    result.reasoning = `AI 預審完成：申請 ${participants} 人，符合規定但建議注意安全。`
  } else {
    result.reasoning = `AI 預審完成：申請 ${participants} 人，完全符合場地容量規定。`
  }

  return c.json(result)
})

// ====== AI Proposal Generator (enhanced) ======
apiRoutes.post('/ai/generate-proposal', async (c) => {
  const body = await c.req.json()
  const participants = body.participants || 50
  const budget = participants * 300
  
  const proposal = {
    title: `${body.title || '活動'}企劃書`,
    sections: {
      purpose: `一、活動目的：${body.description || '提升社團凝聚力，培養同學各方面能力'}`,
      time: `二、活動時間：${body.date || '待定'}`,
      venue: `三、活動地點：${body.venue || '待定'}`,
      participants: `四、預計參加人數：${participants}人`,
      flow: [
        '五、活動流程：',
        '  1. 開場 & 破冰活動 (30分鐘)',
        '  2. 主題活動進行 (90分鐘)',
        '  3. 分組討論 & 實作 (60分鐘)',
        '  4. 成果發表 & 回饋 (30分鐘)',
        '  5. 閉幕 & 大合照 (10分鐘)'
      ],
      budget: `六、預算概估：NT$${budget.toLocaleString()}`,
      budget_breakdown: [
        { item: '場地布置', amount: Math.round(budget * 0.2) },
        { item: '餐飲茶點', amount: Math.round(budget * 0.35) },
        { item: '文具材料', amount: Math.round(budget * 0.15) },
        { item: '保險費用', amount: Math.round(budget * 0.1) },
        { item: '雜支', amount: Math.round(budget * 0.1) },
        { item: '預備金', amount: Math.round(budget * 0.1) },
      ],
      risk: '七、風險評估：依據 RAG 法規檢索，本活動符合《活動申請辦法》規定。',
      sdg: '八、SDGs 對應：SDG 4 (優質教育)、SDG 17 (夥伴關係)'
    },
    ai_review: {
      allow_next_step: true,
      risk_level: participants > 50 ? 'Medium' : 'Low',
      violations: [],
      confidence: 0.92
    },
    generated_at: new Date().toISOString()
  }
  return c.json(proposal)
})

// ====== Credit System (enhanced with state machine) ======
apiRoutes.get('/credits/:userId', async (c) => {
  const userId = c.req.param('userId')
  try {
    const db = c.env?.DB
    if (!db) return c.json({ score: 85, status: 'normal', logs: getDemoCreditLogs(), threshold: 60 })
    const user = await db.prepare('SELECT credit_score FROM users WHERE id = ?').bind(userId).first()
    const { results: logs } = await db.prepare('SELECT * FROM credit_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 20').bind(userId).all()
    const score = (user as any)?.credit_score || 100
    return c.json({ 
      score, 
      status: score < 60 ? 'danger' : score < 75 ? 'warning' : 'normal',
      logs,
      threshold: 60,
      force_logout: score < 60
    })
  } catch { return c.json({ score: 85, status: 'normal', logs: getDemoCreditLogs(), threshold: 60 }) }
})

apiRoutes.post('/credits/deduct', async (c) => {
  const body = await c.req.json()
  const db = c.env?.DB
  if (!db) {
    const newScore = 85 - (body.points || 10)
    return c.json({ 
      success: true, 
      new_score: newScore, 
      force_logout: newScore < 60,
      event: newScore < 60 ? 'INVALIDATE_JWT' : null,
      message: newScore < 60 ? '信用積分低於60，帳號已被強制登出' : `已扣除 ${body.points} 分`
    })
  }
  await db.prepare('UPDATE users SET credit_score = credit_score - ? WHERE id = ?').bind(body.points, body.user_id).run()
  await db.prepare('INSERT INTO credit_logs (user_id, action, points, reason) VALUES (?, ?, ?, ?)').bind(body.user_id, 'deduct', -body.points, body.reason).run()
  const user = await db.prepare('SELECT credit_score FROM users WHERE id = ?').bind(body.user_id).first() as any
  const forceLogout = (user?.credit_score || 0) < 60
  return c.json({ 
    success: true, 
    new_score: user?.credit_score, 
    force_logout: forceLogout,
    event: forceLogout ? 'INVALIDATE_JWT' : null
  })
})

// ====== Calendar Events ======
apiRoutes.get('/calendar/events', (c) => {
  const month = c.req.query('month')
  const year = c.req.query('year')
  return c.json({ data: getDemoCalendarEvents() })
})

apiRoutes.post('/calendar/events', (c) => {
  return c.json({ success: true, id: Date.now(), message: '行事曆事件已建立' })
})

// ====== Notifications ======
apiRoutes.get('/notifications/:userId', async (c) => {
  try {
    const db = c.env?.DB
    if (!db) return c.json({ data: getDemoNotifications(), unread_count: 2 })
    const userId = c.req.param('userId')
    const { results } = await db.prepare('SELECT * FROM notification_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 20').bind(userId).all()
    return c.json({ data: results })
  } catch { return c.json({ data: getDemoNotifications(), unread_count: 2 }) }
})

apiRoutes.post('/notifications/:id/read', (c) => {
  return c.json({ success: true, message: '已標記為已讀' })
})

// ====== Certificates ======
apiRoutes.post('/certificates/generate', async (c) => {
  const body = await c.req.json()
  return c.json({
    certificate_id: `FJU-CERT-2026-${String(Date.now()).slice(-6)}`,
    name: body.name || '王大明',
    club: body.club || '攝影社',
    position: body.position || '副社長',
    term: body.term || '114學年度第一學期',
    digital_signature: `SHA256:${Math.random().toString(36).substring(2, 15)}`,
    verification_url: `https://fju-smart-hub.pages.dev/verify/FJU-CERT-2026-${String(Date.now()).slice(-6)}`,
    generated_at: new Date().toISOString()
  })
})

// ====== Dashboard Stats (role-specific) ======
apiRoutes.get('/dashboard/stats/:role', async (c) => {
  const role = c.req.param('role')
  const stats: Record<string, object> = {
    admin: { 
      pending_reviews: 5, monthly_activities: 28, venue_usage: 87, ai_pass_rate: 94, 
      total_clubs: allClubsAndAssociations.length, total_students: 5000,
      sdg_data: { SDG4: 85, SDG5: 60, SDG10: 75, SDG11: 90, SDG16: 55, SDG17: 70 },
      funnel_data: { submitted: 120, ai_reviewed: 110, approved: 95, completed: 82 },
      trend_data: [1200, 1350, 1500, 1420, 1380, 1550, 1680]
    },
    officer: { 
      pending_tasks: 3, member_count: 45, budget_used: 65, next_event_days: 12,
      retention_data: [100, 95, 88, 82, 78, 75, 73],
      satisfaction_data: { very_satisfied: 45, satisfied: 35, neutral: 12, unsatisfied: 8 },
      attendance_data: [42, 38, 45, 40, 43, 41, 44]
    },
    professor: { 
      supervised_clubs: 3, risk_alerts: 1, review_pending: 2,
      performance_data: { leadership: 82, creativity: 75, teamwork: 88, communication: 70, problem_solving: 85 },
      growth_data: [65, 70, 72, 78, 82, 85],
      risk_indicators: { high: 0, medium: 1, low: 2 }
    },
    student: { 
      activities_joined: 8, officer_roles: 3, credit_score: 85, competency_level: 'A',
      competency_data: { leadership: 80, creativity: 70, teamwork: 85, communication: 75, digital: 90 },
      portfolio_count: 12, certificates: 3
    },
    it: { 
      cpu_usage: 45, memory_usage: 62, api_success_rate: 99.5, waf_blocks_today: 23,
      load_data: [32, 45, 55, 62, 58, 45, 38, 42, 55, 65, 52, 40],
      api_latency: [120, 95, 88, 105, 92, 78, 85, 110, 95, 82, 90, 88],
      r2_usage: { documents: 45, images: 30, certificates: 15, backups: 10 }
    },
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
  const elapsedMinutes = body.elapsed_minutes || 0
  const response: any = {
    suggestions: [
      { id: 1, description: `${body.party_a_name || '甲方'}改至其他場地`, confidence: 0.85 },
      { id: 2, description: '時段分割，各使用一半時間', confidence: 0.78 },
      { id: 3, description: `${body.party_b_name || '乙方'}延後至隔天`, confidence: 0.72 }
    ],
    ai_reasoning: 'Based on historical usage patterns and both parties\' flexibility scores...',
  }
  
  if (elapsedMinutes >= 6) {
    response.forced_close = true
    response.credit_deduction = { points: 10, reason: '協商超過6分鐘，強制關閉' }
    response.message = '⚠️ 協商已超過6分鐘限制，系統強制關閉並扣除雙方各10信用積分'
  } else if (elapsedMinutes >= 3) {
    response.timeout_warning = '已超過3分鐘，AI建議已介入'
    response.ai_intervention = true
  }
  
  return c.json(response)
})

// ====== Repair Requests ======
apiRoutes.get('/repairs', (c) => {
  return c.json({ data: getDemoRepairs() })
})

apiRoutes.post('/repairs', (c) => {
  return c.json({ 
    success: true, id: Date.now(), 
    message: '報修單已建立',
    tracking_code: `RP-${String(Date.now()).slice(-3)}`
  })
})

// ====== Appeals ======
apiRoutes.get('/appeals', (c) => {
  return c.json({ data: getDemoAppeals() })
})

apiRoutes.post('/appeals', (c) => {
  return c.json({ 
    success: true, id: Date.now(), 
    message: '申訴已提交，AI 摘要生成中...',
    estimated_response: '24小時內'
  })
})

apiRoutes.post('/appeals/:id/ai-summary', (c) => {
  return c.json({
    summary: '案件摘要：攝影社（Level 2）與吉他社（Level 2）同時申請中美堂 14:00-17:00 時段。',
    suggestions: [
      '方案一：攝影社改至 SF 134',
      '方案二：時段分割各使用一半',
      '方案三：吉他社延至隔天'
    ],
    sentiment: 'neutral',
    urgency: 'medium'
  })
})

// ====== i18n Language Packs ======
apiRoutes.get('/i18n/:lang', (c) => {
  const lang = c.req.param('lang')
  return c.json(getLanguagePack(lang))
})

// ====== Health Check ======
apiRoutes.get('/health', (c) => c.json({
  status: 'ok',
  version: '2.1.0',
  timestamp: new Date().toISOString(),
  services: { database: 'connected', ai: 'ready', cache: 'active', waf: 'enabled' },
  stats: {
    total_clubs: allClubsAndAssociations.length,
    total_club_categories: Object.keys(getClubStats().byCategory).length
  }
}))

// ==================== DEMO DATA ====================

function getDemoUsers() {
  return [
    { id: 1, student_id: '410012345', name: '王大明', email: '410012345@cloud.fju.edu.tw', phone: '0912-345-678', role: 'student', club_position: '攝影社 副社長', credit_score: 85 },
    { id: 2, student_id: '410012346', name: '陳小美', email: '410012346@cloud.fju.edu.tw', phone: '0923-456-789', role: 'officer', club_position: '吉他社 社長', credit_score: 92 },
    { id: 3, student_id: 'T001', name: '林教授', email: 'lin@cloud.fju.edu.tw', phone: '(02)2905-2001', role: 'professor', club_position: '攝影社 指導教授', credit_score: 100 },
    { id: 4, student_id: 'A001', name: '張組長', email: 'zhang@cloud.fju.edu.tw', phone: '(02)2905-2002', role: 'admin', club_position: '課指組 組長', credit_score: 100 },
    { id: 5, student_id: 'IT001', name: '李工程師', email: 'li@cloud.fju.edu.tw', phone: '(02)2905-3001', role: 'it', club_position: '資訊中心 工程師', credit_score: 100 },
  ]
}

function getDemoVenues() {
  return [
    { id: 1, name: '中美堂', location: '校園中心', capacity: 500, status: 'available', equipment_list: '音響、投影機、舞台燈光', latitude: 25.0356, longitude: 121.4300 },
    { id: 2, name: '活動中心', location: '學生活動中心', capacity: 200, status: 'available', equipment_list: '音響、投影機', latitude: 25.0348, longitude: 121.4305 },
    { id: 3, name: 'SF 134', location: '理工大樓', capacity: 80, status: 'available', equipment_list: '投影機、白板', latitude: 25.0365, longitude: 121.4345 },
    { id: 4, name: '草地廣場', location: '校園東側', capacity: 300, status: 'available', equipment_list: '電源箱', latitude: 25.0355, longitude: 121.4330 },
    { id: 5, name: '體育館', location: '體育場區', capacity: 800, status: 'available', equipment_list: '計分板、音響', latitude: 25.0340, longitude: 121.4320 },
    { id: 6, name: '淨心堂', location: '校園中心', capacity: 400, status: 'available', equipment_list: '管風琴、音響', latitude: 25.0363, longitude: 121.4318 },
    { id: 7, name: '會議室 A', location: '行政大樓 3F', capacity: 30, status: 'available', equipment_list: '投影機、視訊設備', latitude: 25.0358, longitude: 121.4310 },
    { id: 8, name: '會議室 B', location: '行政大樓 5F', capacity: 20, status: 'maintenance', equipment_list: '投影機', latitude: 25.0358, longitude: 121.4312 },
    { id: 9, name: '百鍊廳', location: '體育場區', capacity: 100, status: 'available', equipment_list: '鏡面牆、音響', latitude: 25.0345, longitude: 121.4325 },
    { id: 10, name: '焯炤館 多功能教室', location: '圖書館 B1', capacity: 60, status: 'available', equipment_list: '投影機、白板', latitude: 25.0352, longitude: 121.4335 },
  ]
}

function generateTimeSlots(venueId: number, date: string) {
  const slots = []
  for (let h = 8; h <= 21; h++) {
    const start = `${String(h).padStart(2, '0')}:00`
    const end = `${String(h + 1).padStart(2, '0')}:00`
    const rand = Math.random()
    slots.push({
      start_time: start,
      end_time: end,
      status: rand > 0.7 ? 'reserved' : rand > 0.5 ? 'pending' : 'available',
      reserved_by: rand > 0.7 ? ['吉他社', '攝影社', '資訊社'][Math.floor(Math.random() * 3)] : null
    })
  }
  return slots
}

function getDemoActivities() {
  return [
    { id: 1, title: '攝影社春季外拍', club: '攝影社', club_id: 47, venue: '校園各處', venue_id: 0, event_date: '2026-04-20', start_time: '09:00', end_time: '17:00', category: 'arts', status: 'approved', max_participants: 50, current_participants: 35, organizer: '王大明' },
    { id: 2, title: '吉他社期末成果展', club: '吉他社', club_id: 15, venue: '中美堂', venue_id: 1, event_date: '2026-04-15', start_time: '14:00', end_time: '17:00', category: 'recreation', status: 'pending', max_participants: 200, current_participants: 0, organizer: '陳小美' },
    { id: 3, title: '程式設計工作坊', club: '電腦資訊研究社', club_id: 1, venue: 'SF 134', venue_id: 3, event_date: '2026-04-25', start_time: '13:00', end_time: '17:00', category: 'academic', status: 'approved', max_participants: 40, current_participants: 28, organizer: '李同學' },
    { id: 4, title: '環保淨灘活動', club: '環保社', club_id: 56, venue: '白沙灣', venue_id: 0, event_date: '2026-05-01', start_time: '08:00', end_time: '16:00', category: 'service', status: 'approved', max_participants: 80, current_participants: 45, organizer: '張同學' },
    { id: 5, title: '校園路跑挑戰', club: '田徑社', club_id: 36, venue: '操場', venue_id: 5, event_date: '2026-05-05', start_time: '07:00', end_time: '12:00', category: 'sports', status: 'pending', max_participants: 150, current_participants: 0, organizer: '劉同學' },
    { id: 6, title: '日語讀書會', club: '日本語文研究社', club_id: 5, venue: '焯炤館', venue_id: 10, event_date: '2026-04-10', start_time: '14:00', end_time: '16:00', category: 'academic', status: 'approved', max_participants: 25, current_participants: 18, organizer: '林同學' },
    { id: 7, title: '熱舞社街舞展演', club: '熱門舞蹈社', club_id: 51, venue: '百鍊廳', venue_id: 9, event_date: '2026-04-18', start_time: '18:00', end_time: '21:00', category: 'arts', status: 'approved', max_participants: 100, current_participants: 72, organizer: '陳同學' },
    { id: 8, title: '辯論比賽 - 校際邀請賽', club: '辯論社', club_id: 12, venue: '活動中心', venue_id: 2, event_date: '2026-04-22', start_time: '09:00', end_time: '17:00', category: 'academic', status: 'approved', max_participants: 60, current_participants: 48, organizer: '黃同學' },
  ]
}

function getDemoEquipment() {
  return [
    { id: 1, code: 'EQ-001', name: 'Canon EOS R6 Mark II', category: '攝影', status: 'borrowed', condition: '良好', borrower: '陳同學', borrow_date: '2026-03-28', return_date: '2026-04-05' },
    { id: 2, code: 'EQ-002', name: 'Sony A7III', category: '攝影', status: 'available', condition: '良好', borrower: null, borrow_date: null, return_date: null },
    { id: 3, code: 'EQ-003', name: '投影機 EPSON EB-X51', category: '視聽', status: 'maintenance', condition: '燈泡更換中', borrower: null, borrow_date: null, return_date: null },
    { id: 4, code: 'EQ-004', name: '無線麥克風組 (Shure)', category: '音響', status: 'available', condition: '良好', borrower: null, borrow_date: null, return_date: null },
    { id: 5, code: 'EQ-005', name: 'JBL Eon One Compact 音箱', category: '音響', status: 'available', condition: '良好', borrower: null, borrow_date: null, return_date: null },
    { id: 6, code: 'EQ-006', name: 'DJI Ronin SC 穩定器', category: '攝影', status: 'borrowed', condition: '良好', borrower: '王大明', borrow_date: '2026-04-01', return_date: '2026-04-08' },
    { id: 7, code: 'EQ-007', name: '白板架 (含白板筆組)', category: '文具', status: 'available', condition: '良好', borrower: null, borrow_date: null, return_date: null },
    { id: 8, code: 'EQ-008', name: 'MacBook Pro 16" (投影用)', category: '電腦', status: 'available', condition: '良好', borrower: null, borrow_date: null, return_date: null },
  ]
}

function getDemoReservations() {
  return [
    { id: 1, user: '陳小美', club: '吉他社', venue: '中美堂', venue_id: 1, date: '2026-04-15', start_time: '14:00', end_time: '17:00', priority_level: 2, status: 'confirmed', stage: 'approved' },
    { id: 2, user: '王大明', club: '攝影社', venue: 'SF 134', venue_id: 3, date: '2026-04-20', start_time: '09:00', end_time: '12:00', priority_level: 3, status: 'pending', stage: 'algorithm' },
    { id: 3, user: '李同學', club: '資訊社', venue: 'SF 134', venue_id: 3, date: '2026-04-25', start_time: '13:00', end_time: '17:00', priority_level: 3, status: 'confirmed', stage: 'approved' },
    { id: 4, user: '張同學', club: '環保社', venue: '活動中心', venue_id: 2, date: '2026-05-01', start_time: '10:00', end_time: '16:00', priority_level: 2, status: 'negotiating', stage: 'negotiation' },
  ]
}

function getDemoCreditLogs() {
  return [
    { id: 1, action: 'deduct', points: -10, reason: '協商超時未回應', created_at: '2026-03-15' },
    { id: 2, action: 'add', points: 5, reason: '按時歸還器材', created_at: '2026-03-10' },
    { id: 3, action: 'deduct', points: -5, reason: '遲到簽到', created_at: '2026-02-28' },
    { id: 4, action: 'add', points: 10, reason: '完成社團評鑑資料', created_at: '2026-02-20' },
    { id: 5, action: 'deduct', points: -3, reason: '活動未到場', created_at: '2026-02-15' },
  ]
}

function getDemoCalendarEvents() {
  return [
    { id: 1, title: '社團評鑑會議', date: '2026-04-05', type: 'meeting', color: '#002B5B', description: '地點：活動中心 / 14:00-16:00', venue: '活動中心' },
    { id: 2, title: '金乐獎初審', date: '2026-04-08', type: 'competition', color: '#FFB800', description: '地點：中美堂 / 10:00-12:00', venue: '中美堂' },
    { id: 3, title: '日語讀書會', date: '2026-04-10', type: 'study', color: '#008000', description: '地點：焯炤館 / 14:00-16:00', venue: '焯炤館' },
    { id: 4, title: '吉他社成果展', date: '2026-04-15', type: 'performance', color: '#002B5B', description: '地點：中美堂 / 14:00-17:00', venue: '中美堂' },
    { id: 5, title: '熱舞社街舞展演', date: '2026-04-18', type: 'performance', color: '#FF0000', description: '地點：百鍊廳 / 18:00-21:00', venue: '百鍊廳' },
    { id: 6, title: '攝影社春季外拍', date: '2026-04-20', type: 'outdoor', color: '#FFB800', description: '地點：校園各處 / 09:00-17:00', venue: '校園' },
    { id: 7, title: '辯論比賽', date: '2026-04-22', type: 'competition', color: '#002B5B', description: '地點：活動中心 / 09:00-17:00', venue: '活動中心' },
    { id: 8, title: '程式設計工作坊', date: '2026-04-25', type: 'workshop', color: '#008000', description: '地點：SF 134 / 13:00-17:00', venue: 'SF 134' },
    { id: 9, title: '環保社淨灘', date: '2026-05-01', type: 'service', color: '#008000', description: '地點：白沙灣 / 08:00-16:00', venue: '白沙灣' },
    { id: 10, title: '校園路跑', date: '2026-05-05', type: 'sports', color: '#FF0000', description: '地點：操場 / 07:00-12:00', venue: '操場' },
  ]
}

function getDemoNotifications() {
  return [
    { id: 1, title: '場地申請已核准', message: '您的中美堂場地申請（04/15 14:00-17:00）已通過審核', channel: 'system', read: false, created_at: '2026-04-03T10:30:00Z' },
    { id: 2, title: '器材歸還提醒', message: 'Canon EOS R6 Mark II 借用期限將於明天到期，請準時歸還', channel: 'line', read: false, created_at: '2026-04-04T09:00:00Z' },
    { id: 3, title: '信用積分變更', message: '因按時歸還器材 +5 分，目前積分 85 分', channel: 'system', read: true, created_at: '2026-03-28T14:20:00Z' },
    { id: 4, title: '活動報名確認', message: '您已成功報名「攝影社春季外拍」(04/20)', channel: 'system', read: true, created_at: '2026-03-25T11:00:00Z' },
    { id: 5, title: 'AI 預審通過', message: '您提交的「程式設計工作坊」活動企劃已通過 AI 預審', channel: 'system', read: true, created_at: '2026-03-20T16:45:00Z' },
  ]
}

function getDemoConflicts() {
  return [
    { id: 1, party_a: '攝影社', party_b: '吉他社', venue: '中美堂', date: '2026-04-15', time_slot: '14:00-17:00', status: 'negotiating', stage: 'ai_intervention', elapsed_minutes: 4 },
  ]
}

function getDemoRepairs() {
  return [
    { id: 1, code: 'RP-001', target: '投影機 EPSON EB-X51', description: '燈泡更換', status: 'processing', submitted_at: '2026-04-01', assignee: '維修組 王先生' },
    { id: 2, code: 'RP-002', target: 'SF 134 冷氣', description: '冷氣不涼', status: 'pending', submitted_at: '2026-04-03', assignee: null },
    { id: 3, code: 'RP-003', target: '中美堂音響', description: '左聲道音質異常', status: 'completed', submitted_at: '2026-03-28', assignee: '維修組 李先生' },
  ]
}

function getDemoAppeals() {
  return [
    { id: 1, code: 'AP-042', type: '場地衝突', subject: '攝影社與吉他社中美堂使用時段衝突', status: 'pending', created_at: '2026-04-03' },
    { id: 2, code: 'AP-038', type: '器材損壞', subject: '借用投影機歸還時發現損壞', status: 'processing', created_at: '2026-03-30' },
    { id: 3, code: 'AP-035', type: '信用積分', subject: '因系統故障導致簽到失敗扣分', status: 'resolved', created_at: '2026-03-25' },
  ]
}

function getLanguagePack(lang: string) {
  const packs: Record<string, Record<string, string>> = {
    'zh-TW': {
      dashboard: 'Dashboard', venue_booking: '場地預約', equipment: '設備借用',
      calendar: '行事曆', club_info: '社團資訊', activity_wall: '活動牆',
      ai_overview: 'AI 資訊概覽', ai_guide: 'AI 導覽助理', rag_search: '法規查詢',
      repair: '報修管理', appeal: '申訴記錄', reports: '統計報表',
      login: '登入', logout: '登出', search: '搜尋', hello: '你好',
      credit_score: '信用積分', notification: '通知',
    },
    'zh-CN': {
      dashboard: '仪表板', venue_booking: '场地预约', equipment: '设备借用',
      calendar: '日历', club_info: '社团信息', activity_wall: '活动墙',
      ai_overview: 'AI 信息概览', ai_guide: 'AI 导览助手', rag_search: '法规查询',
      repair: '报修管理', appeal: '申诉记录', reports: '统计报表',
      login: '登录', logout: '退出', search: '搜索', hello: '你好',
      credit_score: '信用积分', notification: '通知',
    },
    'en': {
      dashboard: 'Dashboard', venue_booking: 'Venue Booking', equipment: 'Equipment',
      calendar: 'Calendar', club_info: 'Club Info', activity_wall: 'Activity Wall',
      ai_overview: 'AI Overview', ai_guide: 'AI Guide', rag_search: 'RAG Search',
      repair: 'Repair', appeal: 'Appeal', reports: 'Reports',
      login: 'Login', logout: 'Logout', search: 'Search', hello: 'Hello',
      credit_score: 'Credit Score', notification: 'Notifications',
    },
    'ja': {
      dashboard: 'ダッシュボード', venue_booking: '会場予約', equipment: '機器貸出',
      calendar: 'カレンダー', club_info: 'サークル情報', activity_wall: 'アクティビティウォール',
      ai_overview: 'AI 概要', ai_guide: 'AI ガイド', rag_search: '規則検索',
      repair: '修理管理', appeal: '申立記録', reports: '統計レポート',
      login: 'ログイン', logout: 'ログアウト', search: '検索', hello: 'こんにちは',
      credit_score: 'クレジットスコア', notification: '通知',
    },
    'ko': {
      dashboard: '대시보드', venue_booking: '장소 예약', equipment: '장비 대여',
      calendar: '캘린더', club_info: '동아리 정보', activity_wall: '활동 게시판',
      ai_overview: 'AI 개요', ai_guide: 'AI 가이드', rag_search: '규정 검색',
      repair: '수리 관리', appeal: '이의 제기', reports: '통계 보고서',
      login: '로그인', logout: '로그아웃', search: '검색', hello: '안녕하세요',
      credit_score: '신용 점수', notification: '알림',
    }
  }
  return packs[lang] || packs['zh-TW']
}
