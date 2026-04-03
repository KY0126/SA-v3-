import { Hono } from 'hono'
import { cors } from 'hono/cors'
import { landing } from './pages/landing'
import { login } from './pages/login'
import { dashboard } from './pages/dashboard'
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

// Pages
app.get('/', (c) => c.html(landing()))
app.get('/login', (c) => c.html(login()))
app.get('/dashboard', (c) => c.html(dashboard(c.req.query('role') || 'student')))
app.get('/module/:name', (c) => {
  const name = c.req.param('name')
  const page = modulePages(name)
  if (!page) return c.text('Module not found', 404)
  return c.html(page)
})

export default app
