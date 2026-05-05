import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const venueBookingRoutes = new Hono<{ Bindings: Bindings }>()

// GET /api/venue-bookings
venueBookingRoutes.get('/', async (c) => {
  const db = c.env.DB
  const status = c.req.query('status')
  const userId = c.req.query('userId')
  const facId = c.req.query('facId')
  let sql = 'SELECT VB.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE 1=1'
  const params: any[] = []
  if (status !== undefined) { sql += ' AND VB.VB_STATUS = ?'; params.push(Number(status)) }
  if (userId) { sql += ' AND VB.VB_USR_ID = ?'; params.push(userId) }
  if (facId) { sql += ' AND VB.VB_FAC_ID = ?'; params.push(facId) }
  sql += ' ORDER BY VB.VB_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// GET /api/venue-bookings/pending
venueBookingRoutes.get('/pending', async (c) => {
  const db = c.env.DB
  const { results } = await db.prepare(
    'SELECT VB.*, U.USR_NAME, UN.UNIT_NAME, F.FAC_NAME FROM VenueBooking VB LEFT JOIN User U ON VB.VB_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VB.VB_UNIT_ID = UN.UNIT_ID LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE VB.VB_STATUS = 0 ORDER BY VB.VB_CREATED_AT ASC'
  ).all()
  return c.json({ data: results })
})

// POST /api/venue-bookings
venueBookingRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  // Pessimistic lock: check conflicts
  const conflicts = await db.prepare(
    'SELECT VB_ID, VB_START_DATETIME, VB_END_DATETIME FROM VenueBooking WHERE VB_FAC_ID = ? AND VB_STATUS IN (0, 1) AND VB_START_DATETIME < ? AND VB_END_DATETIME > ?'
  ).bind(b.facId, b.endDatetime, b.startDatetime).all()
  if (conflicts.results && conflicts.results.length > 0) {
    return c.json({ success: false, message: '此時段已有預約，建議進入衝突協調', conflictBookings: conflicts.results }, 409)
  }
  const result = await db.prepare(
    'INSERT INTO VenueBooking (VB_AA_ID, VB_FAC_ID, VB_UNIT_ID, VB_USR_ID, VB_START_DATETIME, VB_END_DATETIME, VB_PURPOSE, VB_HEADCOUNT, VB_BOOKING_TYPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(b.activityId, b.facId, b.unitId, b.userId, b.startDatetime, b.endDatetime, b.purpose, b.headcount, b.bookingType || 0).run()
  return c.json({ success: true, id: result.meta.last_row_id, message: '場地預約已送出，等待審核' }, 201)
})

// PATCH /api/venue-bookings/:id/approve
venueBookingRoutes.patch('/:id/approve', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE VenueBooking SET VB_STATUS = 1, VB_ADMIN_ID = ? WHERE VB_ID = ?").bind(b.adminId || 1, id).run()
  return c.json({ success: true, message: '場地預約已核准' })
})

// PATCH /api/venue-bookings/:id/reject
venueBookingRoutes.patch('/:id/reject', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE VenueBooking SET VB_STATUS = 2, VB_ADMIN_ID = ?, VB_REJECT_REASON = ? WHERE VB_ID = ?").bind(b.adminId || 1, b.reason || '', id).run()
  return c.json({ success: true, message: '場地預約已拒絕' })
})

// PATCH /api/venue-bookings/:id/cancel
venueBookingRoutes.patch('/:id/cancel', async (c) => {
  const db = c.env.DB
  await db.prepare("UPDATE VenueBooking SET VB_STATUS = 3 WHERE VB_ID = ?").bind(c.req.param('id')).run()
  return c.json({ success: true, message: '場地預約已取消' })
})

// PATCH /api/venue-bookings/:id/return
venueBookingRoutes.patch('/:id/return', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  const status = b.abnormal ? 5 : 4
  await db.prepare("UPDATE VenueBooking SET VB_STATUS = ?, VB_RETURN_AT = datetime('now'), VB_RETURN_NOTE = ? WHERE VB_ID = ?").bind(status, b.note || null, id).run()
  return c.json({ success: true, message: '場地已歸還' })
})

// DELETE /api/venue-bookings/:id
venueBookingRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('DELETE FROM VenueBooking WHERE VB_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

export { venueBookingRoutes }
