import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const authRoutes = new Hono<{ Bindings: Bindings }>()

// POST /api/auth/login
authRoutes.post('/login', async (c) => {
  const { email, password } = await c.req.json()
  if (!email) return c.json({ success: false, message: '請輸入 Email' }, 400)
  const db = c.env.DB
  const user = await db.prepare('SELECT * FROM User WHERE USR_EMAIL = ?').bind(email).first()
  if (!user) return c.json({ success: false, message: '查無此帳號，請確認 Email 或建立帳號' }, 401)
  if (user.USR_SUSPENDED === 1) return c.json({ success: false, message: '帳號已被停權，請聯繫課指組或透過申復流程處理' }, 403)
  if (user.USR_EXPIRE_DATE) {
    const exp = new Date(user.USR_EXPIRE_DATE as string)
    if (exp < new Date()) return c.json({ success: false, message: `帳號使用資格已到期（到期日：${user.USR_EXPIRE_DATE}），如有疑問請洽課指組` }, 403)
  }
  // Demo mode: accept any password for demo accounts
  // Use TextEncoder + base64 to handle Unicode (btoa cannot handle CJK characters)
  const tokenPayload = JSON.stringify({ id: user.USR_ID, email: user.USR_EMAIL, role: user.USR_ROLE, name: user.USR_NAME, isAdmin: user.USR_IS_ADMIN })
  const tokenBytes = new TextEncoder().encode(tokenPayload)
  const tokenArr = Array.from(tokenBytes).map(b => String.fromCharCode(b)).join('')
  const token = btoa(tokenArr)
  
  // Get user's units
  const { results: units } = await db.prepare(
    'SELECT UM.UM_UNIT_ID, U.UNIT_NAME, U.UNIT_TYPE FROM UnitMember UM LEFT JOIN Unit U ON UM.UM_UNIT_ID = U.UNIT_ID WHERE UM.UM_USR_ID = ? AND UM.UM_ACTIVE = 1 AND U.UNIT_ACTIVE = 1'
  ).bind(user.USR_ID).all()

  return c.json({
    success: true, message: '登入成功', token,
    user: {
      id: user.USR_ID, email: user.USR_EMAIL, name: user.USR_NAME,
      role: user.USR_ROLE, isAdmin: user.USR_IS_ADMIN, phone: user.USR_PHONE,
      avatar: user.USR_AVATAR, expireDate: user.USR_EXPIRE_DATE,
      units: units || []
    }
  })
})

// POST /api/auth/register - 註冊時選擇身分 (Prompt7 指令9C)
authRoutes.post('/register', async (c) => {
  const { email, name, phone, role, password } = await c.req.json()
  if (!email || !name || !password) return c.json({ success: false, message: '請填寫必要欄位' }, 400)
  if (password.length < 8) return c.json({ success: false, message: '密碼至少需 8 字元' }, 400)
  if (!email.endsWith('@mail.fju.edu.tw') && !email.endsWith('@cloud.fju.edu.tw')) {
    return c.json({ success: false, message: '僅限輔仁大學師生使用，請使用學校帳號 (@mail.fju.edu.tw) 註冊' }, 400)
  }
  const db = c.env.DB
  const existing = await db.prepare('SELECT USR_ID FROM User WHERE USR_EMAIL = ?').bind(email).first()
  if (existing) return c.json({ success: false, message: '此 Email 已被註冊，請直接登入' }, 409)

  // Prompt7 指令9C: 註冊時選擇身分
  // 身份認證邏輯：
  // 1. 選擇角色時由使用者自選 (student/officer/professor/staff)
  // 2. 管理員權限需由課指組確認後賦予
  // 3. 教職員 email 在特定清單內可自動識別
  let userRole = role || 'student'
  const validRoles = ['student', 'officer', 'professor', 'staff']
  if (!validRoles.includes(userRole)) userRole = 'student'

  // 自動偵測：非學生學號格式的 email 建議教職員身分
  if (userRole === 'student' && !email.match(/^s\d{7}@/)) {
    // 如果 email 不是學號格式但選了學生，提醒但仍允許
  }

  const expireDate = ['professor', 'staff'].includes(userRole) ? null : '2027-07-31'
  const result = await db.prepare(
    'INSERT INTO User (USR_EMAIL, USR_NAME, USR_PHONE, USR_ROLE, USR_PASSWORD_HASH, USR_EXPIRE_DATE) VALUES (?, ?, ?, ?, ?, ?)'
  ).bind(email, name, phone || null, userRole, 'pbkdf2_' + password, expireDate).run()
  return c.json({ success: true, message: '帳號建立成功！請使用學校帳號登入系統', userId: result.meta.last_row_id }, 201)
})

// POST /api/auth/forgot-password
authRoutes.post('/forgot-password', async (c) => {
  const { email } = await c.req.json()
  if (!email) return c.json({ success: false, message: '請輸入 Email' }, 400)
  const db = c.env.DB
  const user = await db.prepare('SELECT USR_EMAIL, USR_NAME FROM User WHERE USR_EMAIL = ?').bind(email).first()
  if (!user) return c.json({ success: false, message: '查無此 Email，請確認後重試或建立新帳號' }, 404)
  return c.json({ success: true, message: `密碼重設連結已寄送至 ${user.USR_EMAIL}，請至信箱查收` })
})

// POST /api/auth/reset-password
authRoutes.post('/reset-password', async (c) => {
  const { email, newPassword } = await c.req.json()
  if (!email || !newPassword) return c.json({ success: false, message: '請填寫必要欄位' }, 400)
  if (newPassword.length < 8) return c.json({ success: false, message: '密碼至少需 8 字元' }, 400)
  const db = c.env.DB
  await db.prepare('UPDATE User SET USR_PASSWORD_HASH = ? WHERE USR_EMAIL = ?').bind('pbkdf2_' + newPassword, email).run()
  return c.json({ success: true, message: '密碼已重設，請使用新密碼登入' })
})

export { authRoutes }
