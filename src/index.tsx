import { Hono } from 'hono'
import { cors } from 'hono/cors'
import { landing } from './pages/landing'
import { login } from './pages/login'
import { dashboard } from './pages/dashboard'
import { campusMapPage } from './pages/campus-map'
import { modulePages } from './pages/modules'
import { apiRoutes } from './routes/api'

type Bindings = {
  DB: D1Database
}

const app = new Hono<{ Bindings: Bindings }>()

// CORS
app.use('/api/*', cors())

// API Routes
app.route('/api', apiRoutes)

// Static
app.get('/favicon.svg', async (c) => {
  return new Response('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><text y=".9em" font-size="90">🏫</text></svg>', {
    headers: { 'Content-Type': 'image/svg+xml' }
  })
})
app.get('/favicon.ico', (c) => c.redirect('/favicon.svg'))

// Pages
app.get('/', (c) => c.html(landing()))
app.get('/login', (c) => c.html(login()))
app.get('/dashboard', (c) => c.html(dashboard(c.req.query('role') || 'student')))
app.get('/campus-map', (c) => c.html(campusMapPage(c.req.query('role') || 'student')))
app.get('/module/:name', (c) => {
  const name = c.req.param('name')
  const role = c.req.query('role') || 'student'
  const page = modulePages(name, role)
  if (!page) return c.text('Module not found', 404)
  return c.html(page)
})

export default app
