import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const conflictRoutes = new Hono<{ Bindings: Bindings }>()

conflictRoutes.get('/', async (c) => {
  const db = c.env.DB
  const status = c.req.query('status')
  let sql = `SELECT CN.*, 
    VBA.VB_START_DATETIME as A_START, VBA.VB_END_DATETIME as A_END, VBA.VB_PURPOSE as A_PURPOSE,
    VBB.VB_START_DATETIME as B_START, VBB.VB_END_DATETIME as B_END, VBB.VB_PURPOSE as B_PURPOSE,
    F.FAC_NAME, UA.USR_NAME as A_USER, UB.USR_NAME as B_USER, UNA.UNIT_NAME as A_UNIT, UNB.UNIT_NAME as B_UNIT
    FROM ConflictNegotiation CN
    LEFT JOIN VenueBooking VBA ON CN.CN_VB_ID_A = VBA.VB_ID LEFT JOIN VenueBooking VBB ON CN.CN_VB_ID_B = VBB.VB_ID
    LEFT JOIN Facility F ON VBA.VB_FAC_ID = F.FAC_ID
    LEFT JOIN User UA ON VBA.VB_USR_ID = UA.USR_ID LEFT JOIN User UB ON VBB.VB_USR_ID = UB.USR_ID
    LEFT JOIN Unit UNA ON VBA.VB_UNIT_ID = UNA.UNIT_ID LEFT JOIN Unit UNB ON VBB.VB_UNIT_ID = UNB.UNIT_ID
    WHERE 1=1`
  const params: any[] = []
  if (status !== undefined) { sql += ' AND CN.CN_STATUS = ?'; params.push(Number(status)) }
  sql += ' ORDER BY CN.CN_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

conflictRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const cn = await db.prepare(`SELECT CN.*, 
    VBA.VB_START_DATETIME as A_START, VBA.VB_END_DATETIME as A_END, VBA.VB_PURPOSE as A_PURPOSE, VBA.VB_FAC_ID,
    VBB.VB_START_DATETIME as B_START, VBB.VB_END_DATETIME as B_END, VBB.VB_PURPOSE as B_PURPOSE,
    F.FAC_NAME, UA.USR_NAME as A_USER, UB.USR_NAME as B_USER, UNA.UNIT_NAME as A_UNIT, UNB.UNIT_NAME as B_UNIT
    FROM ConflictNegotiation CN
    LEFT JOIN VenueBooking VBA ON CN.CN_VB_ID_A = VBA.VB_ID LEFT JOIN VenueBooking VBB ON CN.CN_VB_ID_B = VBB.VB_ID
    LEFT JOIN Facility F ON VBA.VB_FAC_ID = F.FAC_ID
    LEFT JOIN User UA ON VBA.VB_USR_ID = UA.USR_ID LEFT JOIN User UB ON VBB.VB_USR_ID = UB.USR_ID
    LEFT JOIN Unit UNA ON VBA.VB_UNIT_ID = UNA.UNIT_ID LEFT JOIN Unit UNB ON VBB.VB_UNIT_ID = UNB.UNIT_ID
    WHERE CN.CN_ID = ?`).bind(id).first()
  if (!cn) return c.json({ error: '衝突不存在' }, 404)
  const { results: messages } = await db.prepare('SELECT * FROM CoordinationMessage WHERE CM_CN_ID = ? ORDER BY CM_SENT_AT ASC').bind(id).all()
  return c.json({ data: cn, messages })
})

conflictRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare("INSERT INTO ConflictNegotiation (CN_VB_ID_A, CN_VB_ID_B, CN_OPENED_AT) VALUES (?, ?, datetime('now'))").bind(b.bookingIdA, b.bookingIdB).run()
  return c.json({ success: true, id: result.meta.last_row_id }, 201)
})

conflictRoutes.post('/:id/messages', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare('INSERT INTO CoordinationMessage (CM_CN_ID, CM_SENDER_ROLE, CM_CONTENT) VALUES (?, ?, ?)').bind(id, b.senderRole, b.content).run()
  await db.prepare('UPDATE ConflictNegotiation SET CN_STATUS = 1 WHERE CN_ID = ? AND CN_STATUS = 0').bind(id).run()
  const { results: messages } = await db.prepare('SELECT * FROM CoordinationMessage WHERE CM_CN_ID = ? ORDER BY CM_SENT_AT ASC').bind(id).all()
  return c.json({ success: true, messages })
})

conflictRoutes.patch('/:id/resolve', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 2, CN_RESOLVED_BY = ?, CN_CLOSED_AT = datetime('now'), CN_DELETE_AT = datetime('now', '+30 days') WHERE CN_ID = ?").bind(b.resolvedBy || 1, id).run()
  return c.json({ success: true, message: '協調已解決' })
})

conflictRoutes.patch('/:id/fail', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  await db.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 3, CN_CLOSED_AT = datetime('now') WHERE CN_ID = ?").bind(id).run()
  return c.json({ success: true, message: '協調失敗' })
})

conflictRoutes.patch('/:id/timeout', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  await db.prepare("UPDATE ConflictNegotiation SET CN_STATUS = 4, CN_CLOSED_AT = datetime('now') WHERE CN_ID = ?").bind(id).run()
  return c.json({ success: true, message: '協調超時關閉' })
})

export { conflictRoutes }
