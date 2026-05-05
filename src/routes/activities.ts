import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const activityRoutes = new Hono<{ Bindings: Bindings }>()

// GET /api/activities
activityRoutes.get('/', async (c) => {
  const db = c.env.DB
  const status = c.req.query('status')
  const unitId = c.req.query('unitId')
  let sql = 'SELECT AA.*, U.USR_NAME, UN.UNIT_NAME FROM ActivityApplication AA LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID WHERE 1=1'
  const params: any[] = []
  if (status !== undefined) { sql += ' AND AA.AA_STATUS = ?'; params.push(Number(status)) }
  if (unitId) { sql += ' AND AA.AA_UNIT_ID = ?'; params.push(unitId) }
  sql += ' ORDER BY AA.AA_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// GET /api/activities/:id
activityRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const aa = await db.prepare('SELECT AA.*, U.USR_NAME, UN.UNIT_NAME FROM ActivityApplication AA LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID WHERE AA.AA_ID = ?').bind(c.req.param('id')).first()
  if (!aa) return c.json({ error: '活動申請不存在' }, 404)
  return c.json({ data: aa })
})

// POST /api/activities
activityRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const count = await db.prepare('SELECT COUNT(*) as cnt FROM ActivityApplication').first() as any
  const serialNo = 'AA' + new Date().getFullYear() + String(count.cnt + 1).padStart(6, '0')
  const result = await db.prepare(
    `INSERT INTO ActivityApplication (AA_SERIAL_NO, AA_USR_ID, AA_UNIT_ID, AA_ACTIVITY_NAME, AA_START_DATETIME, AA_END_DATETIME, AA_HEADCOUNT, AA_DESCRIPTION, AA_CONTACT_NAME, AA_CONTACT_PHONE, AA_CONTACT_EMAIL, AA_THEMES, AA_HAS_ALCOHOL, AA_HAS_FIRE, AA_HAS_BOOTH) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`
  ).bind(serialNo, b.userId, b.unitId, b.activityName, b.startDatetime, b.endDatetime, b.headcount, b.description || null, b.contactName || null, b.contactPhone || null, b.contactEmail || null, b.themes || null, b.hasAlcohol || 0, b.hasFire || 0, b.hasBooth || 0).run()
  return c.json({ success: true, id: result.meta.last_row_id, serialNo, message: '活動申請已送出' }, 201)
})

// PUT /api/activities/:id
activityRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare(
    'UPDATE ActivityApplication SET AA_ACTIVITY_NAME=?, AA_START_DATETIME=?, AA_END_DATETIME=?, AA_HEADCOUNT=?, AA_DESCRIPTION=? WHERE AA_ID=?'
  ).bind(b.activityName, b.startDatetime, b.endDatetime, b.headcount, b.description || null, id).run()
  return c.json({ success: true })
})

// PATCH /api/activities/:id/approve
activityRoutes.patch('/:id/approve', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE ActivityApplication SET AA_STATUS = 1, AA_ADMIN_ID = ?, AA_ADMIN_NOTE = ?, AA_REVIEWED_AT = datetime('now') WHERE AA_ID = ?")
    .bind(b.adminId || 1, b.note || null, id).run()
  return c.json({ success: true, message: '活動已核准' })
})

// PATCH /api/activities/:id/reject
activityRoutes.patch('/:id/reject', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE ActivityApplication SET AA_STATUS = 2, AA_ADMIN_ID = ?, AA_ADMIN_NOTE = ?, AA_REVIEWED_AT = datetime('now') WHERE AA_ID = ?")
    .bind(b.adminId || 1, b.note || '未符合規定', id).run()
  return c.json({ success: true, message: '活動已駁回' })
})

// PATCH /api/activities/:id/cancel
activityRoutes.patch('/:id/cancel', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  await db.prepare('UPDATE ActivityApplication SET AA_STATUS = 5 WHERE AA_ID = ?').bind(id).run()
  return c.json({ success: true, message: '活動已取消' })
})

// DELETE /api/activities/:id
activityRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('DELETE FROM ActivityApplication WHERE AA_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

export { activityRoutes }
