import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const announcementRoutes = new Hono<{ Bindings: Bindings }>()

announcementRoutes.get('/', async (c) => {
  const db = c.env.DB
  const active = c.req.query('active')
  let sql = 'SELECT ANN.*, U.USR_NAME as ADMIN_NAME FROM Announcement ANN LEFT JOIN User U ON ANN.ANN_ADMIN_ID = U.USR_ID'
  if (active === '1') sql += " WHERE ANN.ANN_START_DATE <= date('now') AND ANN.ANN_END_DATE >= date('now')"
  sql += ' ORDER BY ANN.ANN_CREATED_AT DESC'
  const { results } = await db.prepare(sql).all()
  return c.json({ data: results })
})

announcementRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const ann = await db.prepare('SELECT ANN.*, U.USR_NAME as ADMIN_NAME FROM Announcement ANN LEFT JOIN User U ON ANN.ANN_ADMIN_ID = U.USR_ID WHERE ANN.ANN_ID = ?').bind(c.req.param('id')).first()
  if (!ann) return c.json({ error: '公告不存在' }, 404)
  return c.json({ data: ann })
})

announcementRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare('INSERT INTO Announcement (ANN_TITLE, ANN_CONTENT, ANN_START_DATE, ANN_END_DATE, ANN_ADMIN_ID) VALUES (?, ?, ?, ?, ?)')
    .bind(b.title, b.content, b.startDate, b.endDate, b.adminId || 1).run()
  return c.json({ success: true, id: result.meta.last_row_id, message: '公告已發布' }, 201)
})

announcementRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare('UPDATE Announcement SET ANN_TITLE=?, ANN_CONTENT=?, ANN_START_DATE=?, ANN_END_DATE=? WHERE ANN_ID=?')
    .bind(b.title, b.content, b.startDate, b.endDate, id).run()
  return c.json({ success: true })
})

announcementRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('DELETE FROM Announcement WHERE ANN_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

export { announcementRoutes }
