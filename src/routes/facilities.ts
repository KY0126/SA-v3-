import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const facilityRoutes = new Hono<{ Bindings: Bindings }>()

// GET /api/facilities
facilityRoutes.get('/', async (c) => {
  const db = c.env.DB
  const q = c.req.query('q') || ''
  let sql = 'SELECT * FROM Facility WHERE FAC_ACTIVE = 1'
  const params: string[] = []
  if (q) { sql += ' AND (FAC_NAME LIKE ? OR FAC_BUILDING LIKE ?)'; params.push(`%${q}%`, `%${q}%`) }
  sql += ' ORDER BY FAC_NAME'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// GET /api/facilities/:id
facilityRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const fac = await db.prepare('SELECT * FROM Facility WHERE FAC_ID = ?').bind(id).first()
  if (!fac) return c.json({ error: '場地不存在' }, 404)
  return c.json({ data: fac })
})

// GET /api/facilities/:id/calendar
facilityRoutes.get('/:id/calendar', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const start = c.req.query('start') || ''
  const end = c.req.query('end') || ''
  let sql = 'SELECT VB.*, U.USR_NAME, UN.UNIT_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID WHERE VB.VB_FAC_ID = ? AND VB.VB_STATUS IN (0, 1)'
  const params: any[] = [id]
  if (start) { sql += ' AND VB.VB_END_DATETIME >= ?'; params.push(start) }
  if (end) { sql += ' AND VB.VB_START_DATETIME <= ?'; params.push(end) }
  sql += ' ORDER BY VB.VB_START_DATETIME'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// POST /api/facilities
facilityRoutes.post('/', async (c) => {
  const db = c.env.DB
  const body = await c.req.json()
  const result = await db.prepare(
    'INSERT INTO Facility (FAC_NAME, FAC_TYPE, FAC_BUILDING, FAC_FLOOR, FAC_CAPACITY, FAC_GIS_LAT, FAC_GIS_LNG, FAC_STATUS, FAC_DESC) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(body.name, body.type || 0, body.building, body.floor || 1, body.capacity, body.lat || null, body.lng || null, body.status || 0, body.desc || null).run()
  return c.json({ success: true, id: result.meta.last_row_id }, 201)
})

// PUT /api/facilities/:id
facilityRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const body = await c.req.json()
  await db.prepare(
    'UPDATE Facility SET FAC_NAME=?, FAC_TYPE=?, FAC_BUILDING=?, FAC_FLOOR=?, FAC_CAPACITY=?, FAC_STATUS=?, FAC_DESC=? WHERE FAC_ID=?'
  ).bind(body.name, body.type, body.building, body.floor, body.capacity, body.status, body.desc || null, id).run()
  return c.json({ success: true })
})

// DELETE /api/facilities/:id
facilityRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  await db.prepare('UPDATE Facility SET FAC_ACTIVE = 0 WHERE FAC_ID = ?').bind(id).run()
  return c.json({ success: true })
})

// Maintenance logs
facilityRoutes.get('/:id/maintenance', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const { results } = await db.prepare('SELECT FML.*, U.USR_NAME FROM FacilityMaintenanceLog FML LEFT JOIN User U ON FML.FML_ADMIN_ID = U.USR_ID WHERE FML.FML_FAC_ID = ? ORDER BY FML.FML_CREATED_AT DESC').bind(id).all()
  return c.json({ data: results })
})

facilityRoutes.post('/:id/maintenance', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const body = await c.req.json()
  const result = await db.prepare(
    'INSERT INTO FacilityMaintenanceLog (FML_FAC_ID, FML_START_DATE, FML_END_DATE, FML_NOTE, FML_ADMIN_ID) VALUES (?, ?, ?, ?, ?)'
  ).bind(id, body.startDate, body.endDate || null, body.note || null, body.adminId || 1).run()
  await db.prepare('UPDATE Facility SET FAC_STATUS = 1 WHERE FAC_ID = ?').bind(id).run()
  return c.json({ success: true, id: result.meta.last_row_id }, 201)
})

export { facilityRoutes }
