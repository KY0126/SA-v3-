import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const statsRoutes = new Hono<{ Bindings: Bindings }>()

statsRoutes.get('/dashboard', async (c) => {
  const db = c.env.DB
  const period = c.req.query('period') || new Date().toISOString().slice(0, 7)
  const totalFacilities = await db.prepare('SELECT COUNT(*) as cnt FROM Facility WHERE FAC_ACTIVE=1').first() as any
  const totalEquipment = await db.prepare('SELECT COUNT(*) as cnt FROM Equipment WHERE EQ_ACTIVE=1').first() as any
  const totalUsers = await db.prepare('SELECT COUNT(*) as cnt FROM User').first() as any
  const totalUnits = await db.prepare('SELECT COUNT(*) as cnt FROM Unit WHERE UNIT_ACTIVE=1').first() as any
  const pendingBookings = await db.prepare('SELECT COUNT(*) as cnt FROM VenueBooking WHERE VB_STATUS=0').first() as any
  const approvedBookings = await db.prepare('SELECT COUNT(*) as cnt FROM VenueBooking WHERE VB_STATUS=1').first() as any
  const pendingActivities = await db.prepare('SELECT COUNT(*) as cnt FROM ActivityApplication WHERE AA_STATUS=0').first() as any
  const activeLoans = await db.prepare('SELECT COUNT(*) as cnt FROM EquipmentLoan WHERE EL_STATUS IN (0,1,2,3)').first() as any
  const openRepairs = await db.prepare('SELECT COUNT(*) as cnt FROM RepairRequest WHERE RR_STATUS IN (0,1)').first() as any
  const pendingAppeals = await db.prepare('SELECT COUNT(*) as cnt FROM AppealCase WHERE AC_STATUS=0').first() as any
  const { results: statsSummary } = await db.prepare('SELECT SS.*, F.FAC_NAME FROM StatsSummary SS LEFT JOIN Facility F ON SS.SS_FAC_ID = F.FAC_ID WHERE SS.SS_PERIOD = ?').bind(period).all()
  return c.json({
    period,
    totalFacilities: totalFacilities?.cnt || 0,
    totalEquipment: totalEquipment?.cnt || 0,
    totalUsers: totalUsers?.cnt || 0,
    totalUnits: totalUnits?.cnt || 0,
    pendingBookings: pendingBookings?.cnt || 0,
    approvedBookings: approvedBookings?.cnt || 0,
    pendingActivities: pendingActivities?.cnt || 0,
    activeLoans: activeLoans?.cnt || 0,
    openRepairs: openRepairs?.cnt || 0,
    pendingAppeals: pendingAppeals?.cnt || 0,
    facilitySummary: statsSummary
  })
})

statsRoutes.get('/facility-usage', async (c) => {
  const db = c.env.DB
  const { results } = await db.prepare(
    `SELECT F.FAC_NAME, F.FAC_CAPACITY, COUNT(VB.VB_ID) as booking_count,
     COALESCE(SUM(ROUND((julianday(VB.VB_END_DATETIME) - julianday(VB.VB_START_DATETIME)) * 24, 2)), 0) as total_hours
     FROM Facility F LEFT JOIN VenueBooking VB ON F.FAC_ID = VB.VB_FAC_ID AND VB.VB_STATUS IN (1, 4)
     WHERE F.FAC_ACTIVE = 1 GROUP BY F.FAC_ID ORDER BY booking_count DESC`
  ).all()
  return c.json({ data: results })
})

statsRoutes.get('/equipment-usage', async (c) => {
  const db = c.env.DB
  const { results } = await db.prepare(
    `SELECT E.EQ_NAME, E.EQ_TOTAL, E.EQ_AVAILABLE, COUNT(ELD.ELD_ID) as loan_count,
     COALESCE(SUM(ELD.ELD_QUANTITY), 0) as total_borrowed
     FROM Equipment E LEFT JOIN EquipmentLoanDetail ELD ON E.EQ_ID = ELD.ELD_EQ_ID
     WHERE E.EQ_ACTIVE = 1 GROUP BY E.EQ_ID ORDER BY loan_count DESC`
  ).all()
  return c.json({ data: results })
})

export { statsRoutes }
