import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const laborRoutes = new Hono<{ Bindings: Bindings }>()

laborRoutes.get('/', async (c) => {
  const db = c.env.DB
  const userId = c.req.query('userId')
  let sql = 'SELECT LSA.*, U.USR_NAME FROM LaborServiceApplication LSA LEFT JOIN User U ON LSA.LSA_USR_ID = U.USR_ID WHERE 1=1'
  const params: any[] = []
  if (userId) { sql += ' AND LSA.LSA_USR_ID = ?'; params.push(userId) }
  sql += ' ORDER BY LSA.LSA_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

laborRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare(
    'INSERT INTO LaborServiceApplication (LSA_USR_ID, LSA_SERVICE_TYPE, LSA_SERVICE_DATE, LSA_HOURS, LSA_POINTS_TO_DEDUCT) VALUES (?, ?, ?, ?, ?)'
  ).bind(b.userId, b.serviceType, b.serviceDate, b.hours, b.pointsToDeduct).run()
  return c.json({ success: true, id: result.meta.last_row_id, message: '勞動服務銷點申請已送出' }, 201)
})

laborRoutes.patch('/:id/approve', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  const lsa = await db.prepare('SELECT * FROM LaborServiceApplication WHERE LSA_ID = ?').bind(id).first() as any
  if (!lsa) return c.json({ error: '申請不存在' }, 404)
  await db.prepare("UPDATE LaborServiceApplication SET LSA_STATUS=1, LSA_ADMIN_ID=?, LSA_ADMIN_NOTE=?, LSA_REVIEWED_AT=datetime('now') WHERE LSA_ID=?")
    .bind(b.adminId || 1, b.note || null, id).run()
  // Create violation point log for deduction
  await db.prepare(
    'INSERT INTO ViolationPointLog (VPL_TARGET_TYPE, VPL_USR_ID, VPL_DELTA, VPL_REASON, VPL_SOURCE, VPL_ADMIN_ID, VPL_REF_ID) VALUES (0, ?, ?, ?, 3, ?, ?)'
  ).bind(lsa.LSA_USR_ID, -lsa.LSA_POINTS_TO_DEDUCT, '勞動服務銷點', b.adminId || 1, id).run()
  return c.json({ success: true, message: '勞動服務銷點已核准' })
})

laborRoutes.patch('/:id/reject', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE LaborServiceApplication SET LSA_STATUS=2, LSA_ADMIN_ID=?, LSA_ADMIN_NOTE=?, LSA_REVIEWED_AT=datetime('now') WHERE LSA_ID=?")
    .bind(b.adminId || 1, b.note || '未符合條件', id).run()
  return c.json({ success: true, message: '勞動服務銷點已駁回' })
})

export { laborRoutes }
