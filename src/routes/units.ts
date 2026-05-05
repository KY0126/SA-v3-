import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const unitRoutes = new Hono<{ Bindings: Bindings }>()

unitRoutes.get('/', async (c) => {
  const db = c.env.DB
  const { results } = await db.prepare(
    'SELECT U.*, CU.USR_NAME as CONTACT_NAME, UVP.UVP_POINT FROM Unit U LEFT JOIN User CU ON U.UNIT_CONTACT_USR = CU.USR_ID LEFT JOIN UnitViolationPoint UVP ON U.UNIT_ID = UVP.UVP_UNIT_ID WHERE U.UNIT_ACTIVE = 1 ORDER BY U.UNIT_NAME'
  ).all()
  return c.json({ data: results })
})

unitRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const unit = await db.prepare('SELECT U.*, CU.USR_NAME as CONTACT_NAME FROM Unit U LEFT JOIN User CU ON U.UNIT_CONTACT_USR = CU.USR_ID WHERE U.UNIT_ID = ?').bind(c.req.param('id')).first()
  if (!unit) return c.json({ error: '單位不存在' }, 404)
  const { results: members } = await db.prepare('SELECT UM.*, USR.USR_NAME, USR.USR_EMAIL FROM UnitMember UM LEFT JOIN User USR ON UM.UM_USR_ID = USR.USR_ID WHERE UM.UM_UNIT_ID = ? AND UM.UM_ACTIVE = 1').bind(c.req.param('id')).all()
  return c.json({ data: unit, members })
})

unitRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare('INSERT INTO Unit (UNIT_NAME, UNIT_TYPE, UNIT_CONTACT_USR) VALUES (?, ?, ?)').bind(b.name, b.type, b.contactUserId).run()
  await db.prepare('INSERT INTO UnitViolationPoint (UVP_UNIT_ID, UVP_POINT) VALUES (?, 0)').bind(result.meta.last_row_id).run()
  return c.json({ success: true, id: result.meta.last_row_id }, 201)
})

unitRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare('UPDATE Unit SET UNIT_NAME=?, UNIT_TYPE=?, UNIT_CONTACT_USR=? WHERE UNIT_ID=?').bind(b.name, b.type, b.contactUserId, id).run()
  return c.json({ success: true })
})

unitRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('UPDATE Unit SET UNIT_ACTIVE = 0 WHERE UNIT_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

// Members
unitRoutes.post('/:id/members', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare('INSERT OR IGNORE INTO UnitMember (UM_UNIT_ID, UM_USR_ID) VALUES (?, ?)').bind(id, b.userId).run()
  return c.json({ success: true }, 201)
})

unitRoutes.delete('/:id/members/:userId', async (c) => {
  const db = c.env.DB
  await db.prepare('UPDATE UnitMember SET UM_ACTIVE = 0 WHERE UM_UNIT_ID = ? AND UM_USR_ID = ?').bind(c.req.param('id'), c.req.param('userId')).run()
  return c.json({ success: true })
})

export { unitRoutes }
