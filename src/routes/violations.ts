import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const violationRoutes = new Hono<{ Bindings: Bindings }>()

violationRoutes.get('/', async (c) => {
  const db = c.env.DB
  const userId = c.req.query('userId')
  const unitId = c.req.query('unitId')
  let sql = 'SELECT VPL.*, U.USR_NAME, UN.UNIT_NAME, A.USR_NAME as ADMIN_NAME FROM ViolationPointLog VPL LEFT JOIN User U ON VPL.VPL_USR_ID = U.USR_ID LEFT JOIN Unit UN ON VPL.VPL_UNIT_ID = UN.UNIT_ID LEFT JOIN User A ON VPL.VPL_ADMIN_ID = A.USR_ID WHERE 1=1'
  const params: any[] = []
  if (userId) { sql += ' AND VPL.VPL_USR_ID = ?'; params.push(userId) }
  if (unitId) { sql += ' AND VPL.VPL_UNIT_ID = ?'; params.push(unitId) }
  sql += ' ORDER BY VPL.VPL_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

violationRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare(
    'INSERT INTO ViolationPointLog (VPL_TARGET_TYPE, VPL_USR_ID, VPL_UNIT_ID, VPL_DELTA, VPL_REASON, VPL_SOURCE, VPL_ADMIN_ID, VPL_REF_ID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(b.targetType, b.userId || null, b.unitId || null, b.delta, b.reason, b.source || 0, b.adminId || null, b.refId || null).run()
  // Update UnitViolationPoint if target is unit
  if (b.targetType === 1 && b.unitId) {
    await db.prepare('UPDATE UnitViolationPoint SET UVP_POINT = UVP_POINT + ? WHERE UVP_UNIT_ID = ?').bind(b.delta, b.unitId).run()
    const uvp = await db.prepare('SELECT UVP_POINT FROM UnitViolationPoint WHERE UVP_UNIT_ID = ?').bind(b.unitId).first() as any
    if (uvp && uvp.UVP_POINT >= 10) {
      await db.prepare('UPDATE UnitViolationPoint SET UVP_SUSPENDED = 1 WHERE UVP_UNIT_ID = ?').bind(b.unitId).run()
    }
  }
  return c.json({ success: true, id: result.meta.last_row_id }, 201)
})

// Unit violation points summary
violationRoutes.get('/unit-points', async (c) => {
  const db = c.env.DB
  const { results } = await db.prepare(
    'SELECT UVP.*, U.UNIT_NAME FROM UnitViolationPoint UVP LEFT JOIN Unit U ON UVP.UVP_UNIT_ID = U.UNIT_ID ORDER BY UVP.UVP_POINT DESC'
  ).all()
  return c.json({ data: results })
})

export { violationRoutes }
