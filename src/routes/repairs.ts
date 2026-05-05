import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const repairRoutes = new Hono<{ Bindings: Bindings }>()

repairRoutes.get('/', async (c) => {
  const db = c.env.DB
  const status = c.req.query('status')
  let sql = 'SELECT RR.*, U.USR_NAME, F.FAC_NAME, A.USR_NAME as ADMIN_NAME FROM RepairRequest RR LEFT JOIN User U ON RR.RR_USR_ID = U.USR_ID LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID LEFT JOIN User A ON RR.RR_ADMIN_ID = A.USR_ID WHERE 1=1'
  const params: any[] = []
  if (status !== undefined) { sql += ' AND RR.RR_STATUS = ?'; params.push(Number(status)) }
  sql += ' ORDER BY RR.RR_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

repairRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const rr = await db.prepare('SELECT RR.*, U.USR_NAME, F.FAC_NAME FROM RepairRequest RR LEFT JOIN User U ON RR.RR_USR_ID = U.USR_ID LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID WHERE RR.RR_ID = ?').bind(c.req.param('id')).first()
  if (!rr) return c.json({ error: '報修不存在' }, 404)
  const { results: photos } = await db.prepare('SELECT * FROM RepairRequestPhoto WHERE RRP_RR_ID = ?').bind(c.req.param('id')).all()
  return c.json({ data: rr, photos })
})

repairRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare('INSERT INTO RepairRequest (RR_FAC_ID, RR_USR_ID, RR_DESCRIPTION) VALUES (?, ?, ?)').bind(b.facId, b.userId, b.description).run()
  return c.json({ success: true, id: result.meta.last_row_id, message: '報修已提交' }, 201)
})

repairRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE RepairRequest SET RR_STATUS=?, RR_ADMIN_ID=?, RR_ADMIN_NOTE=?, RR_RESOLVED_AT=CASE WHEN ?=2 THEN datetime('now') ELSE RR_RESOLVED_AT END WHERE RR_ID=?")
    .bind(b.status, b.adminId || 1, b.adminNote || null, b.status, id).run()
  return c.json({ success: true })
})

repairRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('DELETE FROM RepairRequestPhoto WHERE RRP_RR_ID = ?').bind(c.req.param('id')).run()
  await db.prepare('DELETE FROM RepairRequest WHERE RR_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

export { repairRoutes }
