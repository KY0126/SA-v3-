import { Hono } from 'hono'
import { cors } from 'hono/cors'
import { authRoutes } from './routes/auth'
import { facilityRoutes } from './routes/facilities'
import { equipmentRoutes } from './routes/equipment'
import { activityRoutes } from './routes/activities'
import { venueBookingRoutes } from './routes/venueBookings'
import { repairRoutes } from './routes/repairs'
import { appealRoutes } from './routes/appeals'
import { announcementRoutes } from './routes/announcements'
import { statsRoutes } from './routes/stats'
import { unitRoutes } from './routes/units'
import { violationRoutes } from './routes/violations'
import { conflictRoutes } from './routes/conflicts'
import { aiRoutes } from './routes/ai'
import { faqRoutes } from './routes/faq'
import { userRoutes } from './routes/users'
import { laborRoutes } from './routes/labor'
import { venueCoordinationRoutes } from './routes/venueCoordination'
import { renderPage } from './pages'

type Bindings = { DB: D1Database }
const app = new Hono<{ Bindings: Bindings }>()

app.use('/api/*', cors())

// API Routes
app.route('/api/auth', authRoutes)
app.route('/api/facilities', facilityRoutes)
app.route('/api/equipment', equipmentRoutes)
app.route('/api/activities', activityRoutes)
app.route('/api/venue-bookings', venueBookingRoutes)
app.route('/api/repairs', repairRoutes)
app.route('/api/appeals', appealRoutes)
app.route('/api/announcements', announcementRoutes)
app.route('/api/stats', statsRoutes)
app.route('/api/units', unitRoutes)
app.route('/api/violations', violationRoutes)
app.route('/api/conflicts', conflictRoutes)
app.route('/api/ai', aiRoutes)
app.route('/api/faq', faqRoutes)
app.route('/api/users', userRoutes)
app.route('/api/labor', laborRoutes)
app.route('/api/venue-coordination', venueCoordinationRoutes)

// Health check
app.get('/api/health', (c) => c.json({ status: 'ok', version: '3.2.0', framework: 'Hono + Cloudflare Pages', tables: 27 }))

// Frontend Pages - SPA
app.get('*', (c) => {
  const html = renderPage()
  return c.html(html)
})

export default app
