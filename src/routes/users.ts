import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const userRoutes = new Hono<{ Bindings: Bindings }>()

userRoutes.get('/', async (c) => {
  const db = c.env.DB
  const role = c.req.query('role')
  let sql = 'SELECT USR_ID, USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_IS_ADMIN, USR_SUSPENDED, USR_EXPIRE_DATE, USR_AVATAR, USR_CREATED_AT FROM User WHERE 1=1'
  const params: any[] = []
  if (role) { sql += ' AND USR_ROLE = ?'; params.push(role) }
  sql += ' ORDER BY USR_ID'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

userRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const user = await db.prepare('SELECT USR_ID, USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_IS_ADMIN, USR_SUSPENDED, USR_EXPIRE_DATE, USR_AVATAR, USR_CREATED_AT FROM User WHERE USR_ID = ?').bind(c.req.param('id')).first()
  if (!user) return c.json({ error: '使用者不存在' }, 404)
  const { results: units } = await db.prepare('SELECT UM.*, U.UNIT_NAME FROM UnitMember UM LEFT JOIN Unit U ON UM.UM_UNIT_ID = U.UNIT_ID WHERE UM.UM_USR_ID = ? AND UM.UM_ACTIVE = 1').bind(c.req.param('id')).all()
  const { results: certs } = await db.prepare('SELECT EC.*, ECT.ECT_NAME FROM EquipmentCert EC LEFT JOIN EquipmentCertType ECT ON EC.EC_TYPE_ID = ECT.ECT_ID WHERE EC.EC_USR_ID = ? AND EC.EC_STATUS = 0').bind(c.req.param('id')).all()
  return c.json({ data: user, units, certs })
})

userRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare('UPDATE User SET USR_NAME=?, USR_PHONE=?, USR_ROLE=?, USR_AVATAR=? WHERE USR_ID=?')
    .bind(b.name, b.phone || null, b.role, b.avatar || null, id).run()
  return c.json({ success: true })
})

userRoutes.patch('/:id/suspend', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  await db.prepare('UPDATE User SET USR_SUSPENDED = 1 WHERE USR_ID = ?').bind(id).run()
  return c.json({ success: true, message: '帳號已停權' })
})

userRoutes.patch('/:id/unsuspend', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  await db.prepare('UPDATE User SET USR_SUSPENDED = 0 WHERE USR_ID = ?').bind(id).run()
  return c.json({ success: true, message: '帳號已解除停權' })
})

userRoutes.patch('/:id/toggle-admin', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const user = await db.prepare('SELECT USR_IS_ADMIN FROM User WHERE USR_ID = ?').bind(id).first() as any
  const newVal = user?.USR_IS_ADMIN === 1 ? 0 : 1
  await db.prepare('UPDATE User SET USR_IS_ADMIN = ? WHERE USR_ID = ?').bind(newVal, id).run()
  return c.json({ success: true, isAdmin: newVal })
})

// GET /api/users/:id/profile - E-Portfolio data
userRoutes.get('/:id/profile', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const user = await db.prepare('SELECT USR_ID, USR_EMAIL, USR_NAME, USR_ROLE, USR_AVATAR, USR_CREATED_AT FROM User WHERE USR_ID = ?').bind(id).first()
  const { results: bookings } = await db.prepare('SELECT VB.*, F.FAC_NAME FROM VenueBooking VB LEFT JOIN Facility F ON VB.VB_FAC_ID = F.FAC_ID WHERE VB.VB_USR_ID = ? ORDER BY VB.VB_CREATED_AT DESC LIMIT 10').bind(id).all()
  const { results: loans } = await db.prepare('SELECT EL.* FROM EquipmentLoan EL WHERE EL.EL_USR_ID = ? ORDER BY EL.EL_CREATED_AT DESC LIMIT 10').bind(id).all()
  const { results: appeals } = await db.prepare('SELECT * FROM AppealCase WHERE AC_USR_ID = ? ORDER BY AC_CREATED_AT DESC LIMIT 5').bind(id).all()
  const { results: repairs } = await db.prepare('SELECT RR.*, F.FAC_NAME FROM RepairRequest RR LEFT JOIN Facility F ON RR.RR_FAC_ID = F.FAC_ID WHERE RR.RR_USR_ID = ? ORDER BY RR.RR_CREATED_AT DESC LIMIT 5').bind(id).all()
  const { results: violations } = await db.prepare('SELECT * FROM ViolationPointLog WHERE VPL_USR_ID = ? ORDER BY VPL_CREATED_AT DESC LIMIT 10').bind(id).all()
  return c.json({ user, bookings, loans, appeals, repairs, violations })
})

// PATCH /api/users/:id/avatar - 更新大頭貼 (Prompt7 指令9E)
userRoutes.patch('/:id/avatar', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  if (!b.avatar) return c.json({ success: false, message: '請選擇大頭貼' }, 400)
  await db.prepare('UPDATE User SET USR_AVATAR = ? WHERE USR_ID = ?').bind(b.avatar, id).run()
  return c.json({ success: true, avatar: b.avatar, message: '大頭貼已更新' })
})

export { userRoutes }
