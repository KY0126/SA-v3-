import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const equipmentRoutes = new Hono<{ Bindings: Bindings }>()

// GET /api/equipment
equipmentRoutes.get('/', async (c) => {
  const db = c.env.DB
  const q = c.req.query('q') || ''
  let sql = 'SELECT E.*, ECT.ECT_NAME as CERT_NAME FROM Equipment E LEFT JOIN EquipmentCertType ECT ON E.EQ_CERT_TYPE_ID = ECT.ECT_ID WHERE E.EQ_ACTIVE = 1'
  const params: string[] = []
  if (q) { sql += ' AND E.EQ_NAME LIKE ?'; params.push(`%${q}%`) }
  sql += ' ORDER BY E.EQ_NAME'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// Cert types — MUST be before /:id to avoid route conflict
equipmentRoutes.get('/cert-types', async (c) => {
  const db = c.env.DB
  const { results } = await db.prepare('SELECT * FROM EquipmentCertType').all()
  return c.json({ data: results })
})

// GET /api/equipment/:id
equipmentRoutes.get('/:id', async (c) => {
  const db = c.env.DB
  const eq = await db.prepare('SELECT E.*, ECT.ECT_NAME as CERT_NAME FROM Equipment E LEFT JOIN EquipmentCertType ECT ON E.EQ_CERT_TYPE_ID = ECT.ECT_ID WHERE E.EQ_ID = ?').bind(c.req.param('id')).first()
  if (!eq) return c.json({ error: '器材不存在' }, 404)
  return c.json({ data: eq })
})

// POST /api/equipment
equipmentRoutes.post('/', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const result = await db.prepare(
    'INSERT INTO Equipment (EQ_NAME, EQ_TOTAL, EQ_AVAILABLE, EQ_MAX_PER_LOAN, EQ_CERT_TYPE_ID, EQ_DESC, EQ_PHYSICAL_CODE) VALUES (?, ?, ?, ?, ?, ?, ?)'
  ).bind(b.name, b.total, b.total, b.maxPerLoan || 1, b.certTypeId || null, b.desc || null, b.physicalCode || null).run()
  return c.json({ success: true, id: result.meta.last_row_id }, 201)
})

// PUT /api/equipment/:id
equipmentRoutes.put('/:id', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const b = await c.req.json()
  await db.prepare('UPDATE Equipment SET EQ_NAME=?, EQ_TOTAL=?, EQ_AVAILABLE=?, EQ_MAX_PER_LOAN=?, EQ_DESC=? WHERE EQ_ID=?')
    .bind(b.name, b.total, b.available, b.maxPerLoan, b.desc || null, id).run()
  return c.json({ success: true })
})

// DELETE /api/equipment/:id
equipmentRoutes.delete('/:id', async (c) => {
  const db = c.env.DB
  await db.prepare('UPDATE Equipment SET EQ_ACTIVE = 0 WHERE EQ_ID = ?').bind(c.req.param('id')).run()
  return c.json({ success: true })
})

// POST /api/equipment/loans - Create equipment loan
equipmentRoutes.post('/loans', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  // Validate activity application exists
  const aa = await db.prepare('SELECT AA_ID FROM ActivityApplication WHERE AA_ID = ? AND AA_STATUS = 1').bind(b.activityId).first()
  if (!aa) return c.json({ success: false, message: '需先取得已核准的活動申請' }, 400)
  // Create loan record
  const loanResult = await db.prepare(
    'INSERT INTO EquipmentLoan (EL_AA_ID, EL_UNIT_ID, EL_USR_ID, EL_BORROW_START, EL_RETURN_DUE, EL_USE_LOCATION, EL_PURPOSE, EL_LOAN_TYPE) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
  ).bind(b.activityId, b.unitId, b.userId, b.borrowStart, b.returnDue, b.useLocation, b.purpose, b.loanType || 0).run()
  const loanId = loanResult.meta.last_row_id
  // Create loan details
  if (b.items && Array.isArray(b.items)) {
    for (const item of b.items) {
      const eq = await db.prepare('SELECT EQ_AVAILABLE, EQ_MAX_PER_LOAN, EQ_CERT_TYPE_ID FROM Equipment WHERE EQ_ID = ? AND EQ_ACTIVE = 1').bind(item.equipmentId).first() as any
      if (!eq) continue
      if (item.quantity > eq.EQ_MAX_PER_LOAN) return c.json({ success: false, message: `${item.equipmentId} 超過單次借用上限 ${eq.EQ_MAX_PER_LOAN}` }, 400)
      if (item.quantity > eq.EQ_AVAILABLE) return c.json({ success: false, message: `庫存不足` }, 400)
      // Check cert requirement
      if (eq.EQ_CERT_TYPE_ID) {
        const cert = await db.prepare('SELECT EC_ID FROM EquipmentCert WHERE EC_USR_ID = ? AND EC_TYPE_ID = ? AND EC_STATUS = 0').bind(b.userId, eq.EQ_CERT_TYPE_ID).first()
        if (!cert) return c.json({ success: false, message: '需先取得對應器材操作證' }, 400)
      }
      await db.prepare('INSERT INTO EquipmentLoanDetail (ELD_EL_ID, ELD_EQ_ID, ELD_QUANTITY) VALUES (?, ?, ?)').bind(loanId, item.equipmentId, item.quantity).run()
      await db.prepare('UPDATE Equipment SET EQ_AVAILABLE = EQ_AVAILABLE - ? WHERE EQ_ID = ?').bind(item.quantity, item.equipmentId).run()
    }
  }
  return c.json({ success: true, loanId, message: '器材借用申請已送出' }, 201)
})

// GET /api/equipment/loans
equipmentRoutes.get('/loans/list', async (c) => {
  const db = c.env.DB
  const userId = c.req.query('userId')
  let sql = 'SELECT EL.*, U.USR_NAME, UN.UNIT_NAME FROM EquipmentLoan EL LEFT JOIN User U ON EL.EL_USR_ID = U.USR_ID LEFT JOIN Unit UN ON EL.EL_UNIT_ID = UN.UNIT_ID'
  const params: any[] = []
  if (userId) { sql += ' WHERE EL.EL_USR_ID = ?'; params.push(userId) }
  sql += ' ORDER BY EL.EL_CREATED_AT DESC'
  const { results } = await db.prepare(sql).bind(...params).all()
  return c.json({ data: results })
})

// GET /api/equipment/loans/:id/details
equipmentRoutes.get('/loans/:id/details', async (c) => {
  const db = c.env.DB
  const id = c.req.param('id')
  const { results } = await db.prepare(
    'SELECT ELD.*, EQ.EQ_NAME FROM EquipmentLoanDetail ELD LEFT JOIN Equipment EQ ON ELD.ELD_EQ_ID = EQ.EQ_ID WHERE ELD.ELD_EL_ID = ?'
  ).bind(id).all()
  return c.json({ data: results })
})

export { equipmentRoutes }
