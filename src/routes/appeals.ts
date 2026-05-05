import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const appealRoutes = new Hono<{ Bindings: Bindings }>()

appealRoutes.get('/', async (c) => {
  const db = c.env.DB
  const status = c.req.query('status')
  let sql = 'SELECT AC.*, U.USR_NAME, A.USR_NAME as ADMIN_NAME FROM AppealCase AC LEFT JOIN User U ON AC.AC_USR_ID = U.USR_ID LEFT JOIN User A ON AC.AC_ADMIN_ID = A.USR_ID WHERE 1=1'
  const params: any[] = []
  if (status !== undefined) { sql += ' AND AC.AC_STATUS = ?'; params.push(Number(status)) }
  sql += ' ORDER BY AC.AC_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

appealRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const ac = await db.prepare('SELECT AC.*, U.USR_NAME FROM AppealCase AC LEFT JOIN User U ON AC.AC_USR_ID = U.USR_ID WHERE AC.AC_ID = ?').bind(c.req.param('id')).first()
  if (!ac) return c.json({ error: '申訴不存在' }, 404)
  return c.json({ data: ac })
})

appealRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare('INSERT INTO AppealCase (AC_USR_ID, AC_TYPE, AC_REF_LOG_ID, AC_REASON, AC_EVIDENCE) VALUES (?, ?, ?, ?, ?)')
    .bind(b.userId, b.type, b.refLogId || null, b.reason, b.evidence || null).run()
  return c.json({ success: true, id: result.meta.last_row_id, message: '申訴已提交' }, 201)
})

appealRoutes.patch('/:id/approve', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE AppealCase SET AC_STATUS=1, AC_ADMIN_ID=?, AC_ADMIN_NOTE=?, AC_REVIEWED_AT=datetime('now') WHERE AC_ID=?")
    .bind(b.adminId || 1, b.note || null, id).run()
  return c.json({ success: true, message: '申訴已核准' })
})

appealRoutes.patch('/:id/reject', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare("UPDATE AppealCase SET AC_STATUS=2, AC_ADMIN_ID=?, AC_ADMIN_NOTE=?, AC_REVIEWED_AT=datetime('now') WHERE AC_ID=?")
    .bind(b.adminId || 1, b.note || '未符合申訴條件', id).run()
  return c.json({ success: true, message: '申訴已駁回' })
})

appealRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('DELETE FROM AppealCase WHERE AC_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

export { appealRoutes }
