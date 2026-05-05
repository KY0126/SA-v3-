import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const venueCoordinationRoutes = new Hono<{ Bindings: Bindings }>()

// GET /api/venue-coordination - 場協大會登記列表
venueCoordinationRoutes.get('/', async (c) => {
  const db = c.env.DB
  const semester = c.req.query('semester')
  const status = c.req.query('status')
  let sql = `SELECT VC.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME
    FROM VenueCoordination VC
    LEFT JOIN User U ON VC.VC_USR_ID = U.USR_ID
    LEFT JOIN Unit UN ON VC.VC_UNIT_ID = UN.UNIT_ID
    LEFT JOIN Facility F ON VC.VC_FAC_ID = F.FAC_ID WHERE 1=1`
  const params: any[] = []
  if (semester) { sql += ' AND VC.VC_SEMESTER = ?'; params.push(semester) }
  if (status !== undefined) { sql += ' AND VC.VC_STATUS = ?'; params.push(Number(status)) }
  sql += ' ORDER BY VC.VC_DAY_OF_WEEK, VC.VC_TIME_START'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// GET /api/venue-coordination/:id
venueCoordinationRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const vc = await db.prepare(`SELECT VC.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME
    FROM VenueCoordination VC LEFT JOIN User U ON VC.VC_USR_ID = U.USR_ID
    LEFT JOIN Unit UN ON VC.VC_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VC.VC_FAC_ID = F.FAC_ID
    WHERE VC.VC_ID = ?`).bind(c.req.param('id')).first()
  if (!vc) return c.json({ error: '登記不存在' }, 404)
  return c.json({ data: vc })
})

// POST /api/venue-coordination - 新增場協大會登記
venueCoordinationRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  if (!b.unitId || !b.facId || b.dayOfWeek === undefined || !b.timeStart || !b.timeEnd || !b.semester) {
    return c.json({ success: false, message: '請填寫必要欄位' }, 400)
  }
  // 檢查同場地同時段衝突
  const conflict = await db.prepare(
    `SELECT VC_ID FROM VenueCoordination WHERE VC_FAC_ID = ? AND VC_DAY_OF_WEEK = ? AND VC_SEMESTER = ? AND VC_STATUS IN (0, 1)
     AND VC_TIME_START < ? AND VC_TIME_END > ?`
  ).bind(b.facId, b.dayOfWeek, b.semester, b.timeEnd, b.timeStart).first()
  if (conflict) return c.json({ success: false, message: '此時段已有其他單位登記' }, 409)
  const result = await db.prepare(
    'INSERT INTO VenueCoordination (VC_USR_ID, VC_UNIT_ID, VC_FAC_ID, VC_DAY_OF_WEEK, VC_TIME_START, VC_TIME_END, VC_PURPOSE, VC_SEMESTER, VC_STATUS) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0)'
  ).bind(b.userId, b.unitId, b.facId, b.dayOfWeek, b.timeStart, b.timeEnd, b.purpose || null, b.semester).run()
  return c.json({ success: true, id: result.meta.last_row_id, message: '場協大會登記已送出' }, 201)
})

// PATCH /api/venue-coordination/:id/approve
venueCoordinationRoutes.patch('/:id/approve', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE VenueCoordination SET VC_STATUS = 1, VC_ADMIN_NOTE = ? WHERE VC_ID = ?")
    .bind(b.note || null, id).run()
  return c.json({ success: true, message: '登記已核准' })
})

// PATCH /api/venue-coordination/:id/reject
venueCoordinationRoutes.patch('/:id/reject', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE VenueCoordination SET VC_STATUS = 2, VC_ADMIN_NOTE = ? WHERE VC_ID = ?")
    .bind(b.note || '未符合條件', id).run()
  return c.json({ success: true, message: '登記已駁回' })
})

// DELETE /api/venue-coordination/:id
venueCoordinationRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('DELETE FROM VenueCoordination WHERE VC_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

export { venueCoordinationRoutes }
