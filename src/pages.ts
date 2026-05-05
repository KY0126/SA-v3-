export function renderPage(): string {
  return `<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>輔仁大學課指組 - 器材與場地預約平台</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        fju: { blue: '#003366', 'blue-light': '#004488', yellow: '#FFD700', green: '#28a745', red: '#dc3545', bg: '#f8f9fa' }
      },
      borderRadius: { 'fju': '8px', 'fju-lg': '12px' }
    }
  }
}
</script>
<style>
.btn-primary { @apply bg-fju-blue text-white px-4 py-2 rounded-fju hover:bg-fju-blue-light transition-all font-medium; }
.btn-yellow { @apply bg-fju-yellow text-fju-blue px-4 py-2 rounded-fju hover:brightness-110 transition-all font-bold; }
.btn-danger { @apply bg-fju-red text-white px-4 py-2 rounded-fju hover:brightness-110 transition-all font-medium; }
.btn-success { @apply bg-fju-green text-white px-4 py-2 rounded-fju hover:brightness-110 transition-all font-medium; }
.card { @apply bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100; }
.stat-card { @apply bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center transition-transform hover:scale-105; }
.sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-fju text-sm transition-all cursor-pointer; }
.sidebar-link:hover { @apply bg-fju-blue/10 text-fju-blue; }
.sidebar-link.active { @apply bg-fju-blue text-white; }
.modal-overlay { @apply fixed inset-0 bg-black/50 z-50 flex items-center justify-center; }
.modal-content { @apply bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto; }
.table-header { @apply bg-gray-50 text-left text-xs text-gray-500 uppercase; }
.table-cell { @apply p-3 text-sm; }
.status-pending { @apply px-2 py-1 rounded-fju bg-yellow-100 text-yellow-700 text-xs font-medium; }
.status-approved { @apply px-2 py-1 rounded-fju bg-green-100 text-green-700 text-xs font-medium; }
.status-rejected { @apply px-2 py-1 rounded-fju bg-red-100 text-red-700 text-xs font-medium; }
.status-info { @apply px-2 py-1 rounded-fju bg-blue-100 text-blue-700 text-xs font-medium; }
.fade-in { animation: fadeIn 0.3s ease-in; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
.input-field { @apply w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm focus:ring-2 focus:ring-fju-blue/20 focus:border-fju-blue outline-none; }
.toast { position:fixed;top:20px;right:20px;z-index:99;padding:12px 20px;border-radius:8px;color:#fff;font-size:14px;animation:slideIn .3s ease; }
@keyframes slideIn { from{transform:translateX(100%);opacity:0}to{transform:translateX(0);opacity:1} }
</style>
</head>
<body class="bg-fju-bg min-h-screen">

<!-- ========== Avatar Selection Modal (Prompt7 9E) ========== -->
<div id="avatar-modal" class="hidden fixed inset-0 bg-black/60 z-[60] flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
    <h3 class="font-bold text-fju-blue text-lg mb-2 text-center"><i class="fas fa-user-circle mr-2 text-fju-yellow"></i>選擇你的大頭貼</h3>
    <p class="text-xs text-gray-400 text-center mb-4">進入系統前請先選擇一個大頭貼</p>
    <div class="grid grid-cols-4 gap-3" id="avatar-grid"></div>
    <button onclick="confirmAvatar()" id="avatar-confirm-btn" disabled class="w-full btn-yellow py-3 mt-4 disabled:opacity-50 disabled:cursor-not-allowed">確認選擇</button>
  </div>
</div>

<!-- ========== Login Page (Prompt7 9B: tab切換) ========== -->
<div id="login-page" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-fju-blue via-fju-blue-light to-fju-blue p-4">
  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <div class="w-20 h-20 bg-fju-yellow rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg">
        <i class="fas fa-university text-fju-blue text-3xl"></i>
      </div>
      <h1 class="text-2xl font-bold text-white">輔仁大學</h1>
      <p class="text-fju-yellow text-sm mt-1">課指組器材與場地預約平台 v3.2</p>
    </div>
    <div class="flex bg-white/10 rounded-fju p-1 mb-4">
      <button onclick="switchAuthTab('login')" id="tab-login" class="flex-1 py-2 text-sm rounded-fju bg-white text-fju-blue font-bold transition-all">登入</button>
      <button onclick="switchAuthTab('register')" id="tab-register" class="flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all">建立帳號</button>
      <button onclick="switchAuthTab('forgot')" id="tab-forgot" class="flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all">忘記密碼</button>
    </div>
    <!-- Login Form -->
    <div id="form-login" class="card fade-in">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-sign-in-alt mr-2"></i>帳號登入</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">學校 Email</label><input id="login-email" type="email" placeholder="example@mail.fju.edu.tw" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">密碼</label><input id="login-password" type="password" placeholder="請輸入密碼" class="input-field" onkeypress="if(event.key==='Enter')doLogin()"></div>
        <button onclick="doLogin()" class="w-full btn-yellow py-3"><i class="fas fa-sign-in-alt mr-2"></i>登入</button>
        <div id="login-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100 flex gap-2">
        <button onclick="switchAuthTab('forgot')" class="flex-1 text-xs px-3 py-2 rounded-fju bg-gray-100 text-gray-600 hover:bg-gray-200"><i class="fas fa-key mr-1"></i>忘記密碼</button>
        <button onclick="switchAuthTab('register')" class="flex-1 text-xs px-3 py-2 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20"><i class="fas fa-user-plus mr-1"></i>建立帳號</button>
      </div>
      <div class="mt-4 pt-4 border-t border-gray-100">
        <p class="text-xs text-gray-400 mb-2">快速 Demo 登入：</p>
        <div class="grid grid-cols-3 gap-2">
          <button onclick="quickLogin('admin01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20">管理員</button>
          <button onclick="quickLogin('s1100001@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-yellow/20 text-fju-blue hover:bg-fju-yellow/30">幹部A(王)</button>
          <button onclick="quickLogin('s1100002@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-orange-100 text-orange-700 hover:bg-orange-200">幹部B(李)</button>
          <button onclick="quickLogin('s1100003@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-fju-green/10 text-fju-green hover:bg-fju-green/20">學生</button>
          <button onclick="quickLogin('prof01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-purple-100 text-purple-700 hover:bg-purple-200">教授</button>
          <button onclick="quickLogin('staff01@mail.fju.edu.tw')" class="text-xs px-2 py-1.5 rounded-fju bg-teal-100 text-teal-700 hover:bg-teal-200">職員</button>
        </div>
        <p class="text-xs text-gray-400 mt-2">* 借用者A(王小明)與借用者B(李小花)模擬預約衝突場景</p>
      </div>
    </div>
    <!-- Register Form (Prompt7 9C: 註冊時選身分) -->
    <div id="form-register" class="card fade-in hidden">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-user-plus mr-2"></i>建立帳號</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">姓名 *</label><input id="reg-name" type="text" placeholder="王小明" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">學校 Email *</label><input id="reg-email" type="email" placeholder="s1234567@mail.fju.edu.tw" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">手機號碼</label><input id="reg-phone" type="tel" placeholder="0912345678" class="input-field"></div>
        <div><label class="text-xs text-gray-500 block mb-1">身份角色 *</label>
          <select id="reg-role" class="input-field">
            <option value="student">學生</option>
            <option value="officer">社團幹部</option>
            <option value="professor">教授</option>
            <option value="staff">行政職員</option>
          </select>
          <p class="text-xs text-gray-400 mt-1">* 社團幹部身分需經課指組確認後生效</p>
        </div>
        <div><label class="text-xs text-gray-500 block mb-1">密碼 * (至少8字元)</label><input id="reg-password" type="password" placeholder="請設定密碼" class="input-field"></div>
        <button onclick="doRegister()" class="w-full btn-primary py-3"><i class="fas fa-user-plus mr-2"></i>建立帳號</button>
        <div id="reg-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-3 text-center"><button onclick="switchAuthTab('login')" class="text-xs text-fju-blue hover:underline">已有帳號？返回登入</button></div>
    </div>
    <!-- Forgot Password Form -->
    <div id="form-forgot" class="card fade-in hidden">
      <h2 class="text-lg font-bold text-fju-blue mb-4"><i class="fas fa-key mr-2"></i>忘記密碼</h2>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-500 block mb-1">學校 Email</label><input id="forgot-email" type="email" placeholder="example@mail.fju.edu.tw" class="input-field"></div>
        <button onclick="doForgotPassword()" class="w-full btn-primary py-3"><i class="fas fa-envelope mr-2"></i>寄送重設連結</button>
        <div id="forgot-msg" class="hidden text-sm p-3 rounded-fju"></div>
      </div>
      <div class="mt-3 text-center"><button onclick="switchAuthTab('login')" class="text-xs text-fju-blue hover:underline">返回登入</button></div>
    </div>
  </div>
</div>

<!-- ========== Main App ========== -->
<div id="app-page" class="hidden min-h-screen flex">
  <aside id="sidebar" class="w-64 bg-white border-r border-gray-100 min-h-screen flex flex-col shadow-sm fixed left-0 top-0 bottom-0 z-40 transition-transform">
    <div class="p-4 border-b border-gray-100">
      <div class="flex items-center gap-3 cursor-pointer" onclick="navigateTo('profile')">
        <div id="user-avatar" class="w-10 h-10 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue font-bold shadow text-lg overflow-hidden"></div>
        <div>
          <div id="user-name-display" class="text-sm font-bold text-fju-blue"></div>
          <div id="user-role-display" class="text-xs text-gray-400"></div>
        </div>
      </div>
    </div>
    <nav class="flex-1 p-3 space-y-1 overflow-y-auto" id="sidebar-nav"></nav>
    <div class="p-3 border-t border-gray-100">
      <button onclick="doLogout()" class="sidebar-link w-full text-red-500 hover:bg-red-50"><i class="fas fa-sign-out-alt w-5"></i><span>登出</span></button>
    </div>
  </aside>
  <main class="flex-1 ml-64">
    <header class="bg-white border-b border-gray-100 px-6 py-3 flex items-center justify-between sticky top-0 z-30">
      <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-fju-blue"><i class="fas fa-bars text-lg"></i></button>
        <h2 id="page-title" class="text-lg font-bold text-fju-blue"></h2>
      </div>
      <div class="flex items-center gap-3">
        <span id="role-badge" class="px-3 py-1 rounded-fju text-xs font-bold"></span>
      </div>
    </header>
    <div id="page-content" class="p-6 fade-in"></div>
  </main>
</div>

<script>
// ========== State ==========
let currentUser = null;
let currentPage = 'dashboard';
let selectedAvatar = null;
const API = '/api';
const roleNames = { admin:'課指組管理員', officer:'社團幹部', professor:'教授', student:'學生', staff:'行政職員' };
const badgeColors = { admin:'bg-fju-blue text-white', officer:'bg-fju-yellow text-fju-blue', professor:'bg-purple-100 text-purple-700', student:'bg-fju-green/10 text-fju-green', staff:'bg-teal-100 text-teal-700' };
const avatarEmojis = ['😊','😎','🤓','🧑‍💻','👩‍🎓','👨‍🏫','🦊','🐱','🐶','🦁','🐼','🐨','🦉','🐬','🌟','🎯'];

// ========== Toast ==========
function toast(msg, type) {
  const bg = type==='error'?'#dc3545':type==='success'?'#28a745':'#003366';
  const el = document.createElement('div');
  el.className='toast'; el.style.background=bg; el.textContent=msg;
  document.body.appendChild(el);
  setTimeout(()=>el.remove(),3000);
}

// ========== Auth ==========
function switchAuthTab(tab) {
  ['login','register','forgot'].forEach(t => {
    document.getElementById('form-'+t).classList.toggle('hidden', t !== tab);
    const btn = document.getElementById('tab-'+t);
    if(btn) btn.className = t === tab
      ? 'flex-1 py-2 text-sm rounded-fju bg-white text-fju-blue font-bold transition-all'
      : 'flex-1 py-2 text-sm rounded-fju text-white/70 hover:text-white transition-all';
  });
}

async function doLogin() {
  const email = document.getElementById('login-email').value;
  const password = document.getElementById('login-password').value;
  if (!email) return showMsg('login-msg', '請輸入 Email', 'error');
  try {
    const res = await fetch(API+'/auth/login', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email, password}) });
    const data = await res.json();
    if (data.success) {
      currentUser = data.user;
      localStorage.setItem('fjuUser', JSON.stringify(data.user));
      localStorage.setItem('fjuToken', data.token);
      if (!currentUser.avatar) { showAvatarSelection(); }
      else { enterApp(); }
    } else { showMsg('login-msg', data.message, 'error'); }
  } catch(e) { showMsg('login-msg', '連線錯誤', 'error'); }
}

function quickLogin(email) { document.getElementById('login-email').value = email; document.getElementById('login-password').value = 'demo123'; doLogin(); }

async function doRegister() {
  const name = document.getElementById('reg-name').value;
  const email = document.getElementById('reg-email').value;
  const phone = document.getElementById('reg-phone').value;
  const role = document.getElementById('reg-role').value;
  const password = document.getElementById('reg-password').value;
  if (!name || !email || !password) return showMsg('reg-msg', '請填寫必要欄位', 'error');
  if (password.length < 8) return showMsg('reg-msg', '密碼至少需 8 字元', 'error');
  try {
    const res = await fetch(API+'/auth/register', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({name, email, phone, role, password}) });
    const data = await res.json();
    showMsg('reg-msg', data.message, data.success ? 'success' : 'error');
    if (data.success) setTimeout(() => switchAuthTab('login'), 1500);
  } catch(e) { showMsg('reg-msg', '連線錯誤', 'error'); }
}

async function doForgotPassword() {
  const email = document.getElementById('forgot-email').value;
  if (!email) return showMsg('forgot-msg', '請輸入 Email', 'error');
  try {
    const res = await fetch(API+'/auth/forgot-password', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email}) });
    const data = await res.json();
    showMsg('forgot-msg', data.message, data.success ? 'success' : 'error');
  } catch(e) { showMsg('forgot-msg', '連線錯誤', 'error'); }
}

function doLogout() { localStorage.removeItem('fjuUser'); localStorage.removeItem('fjuToken'); currentUser = null; document.getElementById('app-page').classList.add('hidden'); document.getElementById('login-page').classList.remove('hidden'); }
function showMsg(id, msg, type) { const el = document.getElementById(id); if(!el) return; el.classList.remove('hidden'); el.className = 'text-sm p-3 rounded-fju ' + (type === 'error' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'); el.textContent = msg; setTimeout(() => el.classList.add('hidden'), 5000); }

// ========== Avatar Selection (Prompt7 9E) ==========
function showAvatarSelection() {
  selectedAvatar = null;
  const grid = document.getElementById('avatar-grid');
  grid.innerHTML = avatarEmojis.map((e, i) =>
    '<div class="w-16 h-16 flex items-center justify-center text-3xl rounded-fju-lg border-2 border-gray-200 cursor-pointer hover:border-fju-yellow hover:bg-fju-yellow/10 transition-all" data-idx="'+i+'" onclick="selectAvatar(this,\\''+e+'\\')">'+e+'</div>'
  ).join('');
  document.getElementById('avatar-modal').classList.remove('hidden');
  document.getElementById('avatar-confirm-btn').disabled = true;
}
function selectAvatar(el, emoji) {
  selectedAvatar = emoji;
  document.querySelectorAll('#avatar-grid > div').forEach(d => { d.classList.remove('border-fju-yellow','bg-fju-yellow/10','ring-2','ring-fju-yellow'); d.classList.add('border-gray-200'); });
  el.classList.remove('border-gray-200'); el.classList.add('border-fju-yellow','bg-fju-yellow/10','ring-2','ring-fju-yellow');
  document.getElementById('avatar-confirm-btn').disabled = false;
}
async function confirmAvatar() {
  if (!selectedAvatar || !currentUser) return;
  currentUser.avatar = selectedAvatar;
  localStorage.setItem('fjuUser', JSON.stringify(currentUser));
  try { await fetch(API+'/users/'+currentUser.id+'/avatar', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({avatar:selectedAvatar}) }); } catch(e) {}
  document.getElementById('avatar-modal').classList.add('hidden');
  enterApp();
}

// ========== App Entry ==========
function enterApp() {
  document.getElementById('login-page').classList.add('hidden');
  document.getElementById('app-page').classList.remove('hidden');
  const u = currentUser;
  document.getElementById('user-name-display').textContent = u.name;
  document.getElementById('user-role-display').textContent = roleNames[u.role] || u.role;
  const avatarEl = document.getElementById('user-avatar');
  if (u.avatar) { avatarEl.textContent = u.avatar; avatarEl.style.fontSize = '1.5rem'; }
  else { avatarEl.textContent = u.name ? u.name[0] : '?'; }
  const badge = document.getElementById('role-badge');
  badge.className = 'px-3 py-1 rounded-fju text-xs font-bold ' + (badgeColors[u.role] || badgeColors.student);
  badge.textContent = roleNames[u.role] || u.role;
  buildSidebar();
  navigateTo('dashboard');
}

// Prompt7 7A: 移除活動牆; sidebar依角色顯示不同功能
function buildSidebar() {
  const role = currentUser?.role || 'student';
  const isAdmin = currentUser?.isAdmin === 1;
  const items = [
    { id:'dashboard', icon:'fa-tachometer-alt', label:'儀表板', roles:['admin','officer','professor','student','staff'] },
    { id:'activities', icon:'fa-calendar-check', label:'活動申請', roles:['admin','officer','professor','staff'] },
    { id:'venues', icon:'fa-map-marker-alt', label:'場地預約', roles:['admin','officer','professor','student','staff'] },
    { id:'equipment', icon:'fa-tools', label:'器材借用', roles:['admin','officer','professor','staff'] },
    { id:'coordination', icon:'fa-users-rectangle', label:'場協大會', roles:['admin','officer'] },
    { id:'conflicts', icon:'fa-handshake', label:'衝突協調', roles:['admin','officer'] },
    { id:'repairs', icon:'fa-wrench', label:'報修管理', roles:['admin','officer','student','staff'] },
    { id:'appeals', icon:'fa-gavel', label:'申訴管理', roles:['admin','officer','student'] },
    { id:'announcements', icon:'fa-bullhorn', label:'公告資訊', roles:['admin','officer','professor','student','staff'] },
    { id:'faq', icon:'fa-robot', label:'FAQ / AI 助理', roles:['admin','officer','professor','student','staff'] },
    { id:'stats', icon:'fa-chart-bar', label:'統計報表', roles:['admin'] },
    { id:'users', icon:'fa-users-cog', label:'使用者管理', roles:['admin'] },
    { id:'violations', icon:'fa-exclamation-triangle', label:'違規記點', roles:['admin','officer','student'] },
    { id:'labor', icon:'fa-hands-helping', label:'勞動服務銷點', roles:['admin','officer','student'] },
    { id:'units', icon:'fa-building', label:'單位管理', roles:['admin','officer'] },
    { id:'profile', icon:'fa-user-circle', label:'個人中心', roles:['admin','officer','professor','student','staff'] },
  ];
  const nav = document.getElementById('sidebar-nav');
  nav.innerHTML = items.filter(i => i.roles.includes(role) || isAdmin).map(i =>
    '<div class="sidebar-link" data-page="'+i.id+'" onclick="navigateTo(\\''+i.id+'\\')"><i class="fas '+i.icon+' w-5 text-center"></i><span>'+i.label+'</span></div>'
  ).join('');
}

// ========== Navigation ==========
function navigateTo(page) {
  currentPage = page;
  document.querySelectorAll('.sidebar-link').forEach(el => el.classList.toggle('active', el.dataset.page === page));
  const titles = { dashboard:'儀表板', activities:'活動申請管理', venues:'場地預約管理', equipment:'器材借用管理', coordination:'場協大會', conflicts:'衝突協調', repairs:'報修管理', appeals:'申訴管理', announcements:'公告資訊', faq:'FAQ / AI 助理', stats:'統計報表', users:'使用者管理', violations:'違規記點管理', labor:'勞動服務銷點', units:'單位管理', profile:'個人中心' };
  document.getElementById('page-title').textContent = titles[page] || page;
  document.getElementById('page-content').innerHTML = '<div class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2 text-sm">載入中...</p></div>';
  const loaders = { dashboard:loadDashboard, activities:loadActivities, venues:loadVenues, equipment:loadEquipment, coordination:loadCoordination, conflicts:loadConflicts, repairs:loadRepairs, appeals:loadAppeals, announcements:loadAnnouncements, faq:loadFaq, stats:loadStats, users:loadUsers, violations:loadViolations, labor:loadLabor, units:loadUnits, profile:loadProfile };
  if (loaders[page]) loaders[page]();
}
function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); }

// ========== Utility ==========
function statCard(icon, label, value, color) {
  const colors = { blue:'text-fju-blue', green:'text-fju-green', yellow:'text-fju-yellow', red:'text-fju-red', purple:'text-purple-600', teal:'text-teal-600' };
  return '<div class="stat-card"><div class="text-2xl font-black '+(colors[color]||'text-fju-blue')+'">'+((value!=null)?value:0)+'</div><div class="text-xs text-gray-400 mt-1"><i class="fas '+icon+' mr-1"></i>'+label+'</div></div>';
}
function closeModal() { document.querySelector('.modal-overlay')?.remove(); }
function filterTable(id, q) { document.querySelectorAll('#'+id+' tbody tr').forEach(r => { r.style.display = r.textContent.toLowerCase().includes(q.toLowerCase()) ? '' : 'none'; }); }

// ========== Dashboard ==========
async function loadDashboard() {
  const [dashRes, annRes] = await Promise.all([fetch(API+'/stats/dashboard').then(r=>r.json()).catch(()=>({})), fetch(API+'/announcements?active=1').then(r=>r.json()).catch(()=>({data:[]}))]);
  const d = dashRes; const anns = (annRes.data || []).slice(0,3);
  const role = currentUser?.role || 'student'; const isAdmin = currentUser?.isAdmin === 1;
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="card bg-gradient-to-r from-fju-blue to-fju-blue-light text-white"><div class="flex items-center justify-between"><div><h3 class="text-lg font-bold">歡迎回來，'+(currentUser?.name||'')+'</h3><p class="text-sm text-white/70 mt-1">'+roleNames[role]+' | '+(currentUser?.units?.length ? '所屬單位：'+currentUser.units.map(u=>u.UNIT_NAME).join('、') : '尚未加入任何單位')+'</p></div><div class="text-5xl">'+(currentUser?.avatar||'🏫')+'</div></div></div>';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">';
  h += statCard('fa-building','場地總數',d.totalFacilities,'blue');
  h += statCard('fa-tools','器材項目',d.totalEquipment,'green');
  h += statCard('fa-users','使用者',d.totalUsers,'purple');
  h += statCard('fa-clock','待審預約',d.pendingBookings,'yellow');
  h += statCard('fa-wrench','待修件數',d.openRepairs,'red');
  h += '</div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-bolt mr-2 text-fju-yellow"></i>快速操作</h3><div class="flex flex-wrap gap-2">';
  if (['officer','professor','staff','admin'].includes(role) || isAdmin) {
    h += '<button onclick="navigateTo(\\'activities\\')" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>新增活動申請</button>';
    h += '<button onclick="navigateTo(\\'venues\\')" class="btn-yellow text-xs"><i class="fas fa-calendar-plus mr-1"></i>預約場地</button>';
    h += '<button onclick="navigateTo(\\'equipment\\')" class="btn-primary text-xs"><i class="fas fa-hand-holding mr-1"></i>借用器材</button>';
  }
  h += '<button onclick="navigateTo(\\'repairs\\')" class="text-xs px-3 py-2 rounded-fju bg-fju-red/10 text-fju-red hover:bg-fju-red/20"><i class="fas fa-wrench mr-1"></i>報修</button>';
  h += '<button onclick="navigateTo(\\'faq\\')" class="text-xs px-3 py-2 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20"><i class="fas fa-robot mr-1"></i>AI 助理</button>';
  h += '</div></div>';
  if (anns.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-bullhorn mr-2 text-fju-yellow"></i>最新公告</h3><div class="space-y-2">';
    anns.forEach(a => { h += '<div class="p-3 rounded-fju bg-fju-bg border border-gray-100 hover:border-fju-yellow/30 cursor-pointer" onclick="navigateTo(\\'announcements\\')"><div class="flex items-center justify-between"><span class="font-medium text-sm text-fju-blue">'+(a.ANN_TITLE||'')+'</span><span class="text-xs text-gray-400">'+(a.ANN_START_DATE||'')+'</span></div><p class="text-xs text-gray-500 mt-1 line-clamp-1">'+(a.ANN_CONTENT||'')+'</p></div>'; });
    h += '</div></div>';
  }
  if (isAdmin || role === 'admin') {
    h += '<div class="grid md:grid-cols-2 gap-4"><div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-clipboard-list mr-2 text-fju-yellow"></i>待審核項目</h3><div class="space-y-2">';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'venues\\')"><span class="text-sm text-gray-600">待審核預約</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingBookings||0)+'</span></div>';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'activities\\')"><span class="text-sm text-gray-600">待審核活動</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingActivities||0)+'</span></div>';
    h += '<div class="flex items-center justify-between p-2 rounded-fju hover:bg-fju-bg cursor-pointer" onclick="navigateTo(\\'appeals\\')"><span class="text-sm text-gray-600">待審核申訴</span><span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs font-bold">'+(d.pendingAppeals||0)+'</span></div>';
    h += '</div></div>';
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>本月概覽</h3><div class="space-y-2">';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">已核准預約</span><span class="font-bold text-fju-green">'+(d.approvedBookings||0)+'</span></div>';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">進行中借用</span><span class="font-bold text-fju-blue">'+(d.activeLoans||0)+'</span></div>';
    h += '<div class="flex justify-between text-sm"><span class="text-gray-500">進行中報修</span><span class="font-bold text-fju-red">'+(d.openRepairs||0)+'</span></div>';
    h += '</div></div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}

// ========== Activities (Epic 3) ==========
async function loadActivities() {
  const res = await fetch(API+'/activities').then(r=>r.json()).catch(()=>({data:[]}));
  const items = res.data || [];
  const sL = { 0:'待審核', 1:'已核准', 2:'已拒絕', 5:'已取消' };
  const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected', 5:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-file-alt','總申請',items.length,'blue')+statCard('fa-check-circle','已核准',items.filter(i=>i.AA_STATUS===1).length,'green')+statCard('fa-clock','待審核',items.filter(i=>i.AA_STATUS===0).length,'yellow')+statCard('fa-times-circle','已拒絕',items.filter(i=>i.AA_STATUS===2).length,'red')+'</div>';
  h += '<div class="flex items-center justify-between flex-wrap gap-2"><input type="text" placeholder="搜尋活動..." class="input-field w-64" oninput="filterTable(\\'act-table\\',this.value)">';
  if (['officer','professor','staff','admin'].includes(currentUser?.role) || isAdmin) h += '<button onclick="showAddActivityForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增活動申請</button>';
  h += '</div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm" id="act-table"><thead class="table-header"><tr><th class="table-cell">流水號</th><th class="table-cell">活動名稱</th><th class="table-cell">申請人/單位</th><th class="table-cell">時間</th><th class="table-cell">人數</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">暫無活動申請</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs font-mono text-fju-blue">'+(i.AA_SERIAL_NO||'')+'</td><td class="table-cell font-medium">'+(i.AA_ACTIVITY_NAME||'')+'</td><td class="table-cell text-xs text-gray-500">'+(i.USR_NAME||'')+' / '+(i.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(i.AA_START_DATETIME||'').slice(0,16)+'</td><td class="table-cell">'+i.AA_HEADCOUNT+'</td><td class="table-cell"><span class="'+(sC[i.AA_STATUS]||'')+'">'+((sL[i.AA_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.AA_STATUS === 1) h += '<button onclick="generatePDF('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1" title="產生PDF"><i class="fas fa-file-pdf"></i></button>';
    if (i.AA_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red mr-1">拒絕</button>';
    }
    if (i.AA_STATUS === 0) h += '<button onclick="cancelActivity('+i.AA_ID+')" class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-500">取消</button>';
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAddActivityForm() {
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">預設單位</option>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-check mr-2 text-fju-yellow"></i>新增活動申請</h3><div class="space-y-3"><input id="aa-name" placeholder="活動名稱 *" class="input-field"><select id="aa-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始</label><input id="aa-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">結束</label><input id="aa-end" type="datetime-local" class="input-field"></div></div><input id="aa-headcount" type="number" placeholder="預計人數 *" class="input-field"><textarea id="aa-desc" placeholder="活動說明" rows="2" class="input-field"></textarea><div class="flex gap-4 text-sm"><label><input type="checkbox" id="aa-alcohol"> 含酒精</label><label><input type="checkbox" id="aa-fire"> 含明火</label><label><input type="checkbox" id="aa-booth"> 含攤位</label></div><button onclick="submitActivity()" class="w-full btn-yellow py-2.5">送出申請</button></div></div></div>');
}
async function submitActivity() {
  const body = { userId:currentUser?.id||1, unitId:parseInt(document.getElementById('aa-unit').value), activityName:document.getElementById('aa-name').value, startDatetime:document.getElementById('aa-start').value, endDatetime:document.getElementById('aa-end').value, headcount:parseInt(document.getElementById('aa-headcount').value)||20, description:document.getElementById('aa-desc').value, hasAlcohol:document.getElementById('aa-alcohol').checked?1:0, hasFire:document.getElementById('aa-fire').checked?1:0, hasBooth:document.getElementById('aa-booth').checked?1:0 };
  if (!body.activityName) return alert('請填寫活動名稱');
  const res = await fetch(API+'/activities', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('活動申請已送出','success'); closeModal(); loadActivities(); }
  else { alert(data.message || '申請失敗'); }
}
async function approveActivity(id) { await fetch(API+'/activities/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('活動已核准','success'); loadActivities(); }
async function rejectActivity(id) { const note = prompt('拒絕原因：'); if(!note) return; await fetch(API+'/activities/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,note}) }); loadActivities(); }
async function cancelActivity(id) { if(!confirm('確定取消此活動？')) return; await fetch(API+'/activities/'+id+'/cancel', { method:'PATCH' }); loadActivities(); }
async function generatePDF(id) {
  try {
    const res = await fetch(API+'/ai/generate-pdf', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({activityId:id}) });
    const data = await res.json();
    if (data.success) {
      const p = data.pdfData;
      const w = window.open('','_blank');
      w.document.write('<html><head><title>活動申請書 - '+p.serialNo+'</title><style>body{font-family:sans-serif;padding:40px;max-width:800px;margin:0 auto}h1{text-align:center;border-bottom:3px double #003366;padding-bottom:10px;color:#003366}table{width:100%;border-collapse:collapse;margin:20px 0}td,th{border:1px solid #ccc;padding:8px 12px;text-align:left;font-size:14px}th{background:#f5f5f5;width:120px}.footer{margin-top:40px;text-align:center;font-size:12px;color:#999}.header{text-align:center;margin-bottom:20px}.stamp{display:inline-block;border:2px solid '+(p.status==='已核准'?'green':'red')+';color:'+(p.status==='已核准'?'green':'red')+';padding:5px 15px;border-radius:4px;font-weight:bold;font-size:18px;transform:rotate(-5deg)}</style></head><body>');
      w.document.write('<div class="header"><h1>輔仁大學課外活動指導組<br>活動申請書</h1></div>');
      w.document.write('<table><tr><th>流水編號</th><td>'+p.serialNo+'</td><th>審核狀態</th><td><span class="stamp">'+p.status+'</span></td></tr>');
      w.document.write('<tr><th>活動名稱</th><td colspan="3">'+p.activityName+'</td></tr>');
      w.document.write('<tr><th>主辦單位</th><td>'+p.unitName+'</td><th>申請人</th><td>'+p.applicantName+'</td></tr>');
      w.document.write('<tr><th>活動時間</th><td colspan="3">'+p.startDatetime+' ~ '+p.endDatetime+'</td></tr>');
      w.document.write('<tr><th>預計人數</th><td>'+p.headcount+' 人</td><th>聯絡人</th><td>'+(p.contactName||p.applicantName)+' '+(p.contactPhone||'')+'</td></tr>');
      w.document.write('<tr><th>活動說明</th><td colspan="3">'+(p.description||'')+'</td></tr>');
      w.document.write('<tr><th>特殊事項</th><td colspan="3">'+(p.hasAlcohol?'✅ 含酒精 ':'')+(p.hasFire?'✅ 含明火 ':'')+(p.hasBooth?'✅ 含攤位 ':'')+(!p.hasAlcohol&&!p.hasFire&&!p.hasBooth?'無':'')+'</td></tr>');
      if (p.adminNote) w.document.write('<tr><th>審核備註</th><td colspan="3">'+p.adminNote+'</td></tr>');
      w.document.write('</table>');
      w.document.write('<div class="footer"><p>本申請書由系統自動產生 | 產生時間：'+new Date(p.generatedAt).toLocaleString('zh-TW')+'</p><p>輔仁大學學生事務處課外活動指導組</p></div>');
      w.document.write('</body></html>');
      w.document.close();
    } else { alert(data.message); }
  } catch(e) { alert('PDF 產生失敗'); }
}

// ========== Venues (Epic 4) ==========
async function loadVenues() {
  const [facRes, bkRes] = await Promise.all([fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/venue-bookings').then(r=>r.json()).catch(()=>({data:[]}))])
  const venues = facRes.data || []; const bookings = bkRes.data || [];
  const sL = { 0:'可預約', 1:'維修中' }; const sC = { 0:'status-approved', 1:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-building','場地總數',venues.length,'blue')+statCard('fa-check-circle','可預約',venues.filter(v=>v.FAC_STATUS===0).length,'green')+statCard('fa-tools','維修中',venues.filter(v=>v.FAC_STATUS===1).length,'red')+statCard('fa-calendar-check','預約數',bookings.length,'yellow')+'</div>';
  h += '<div class="flex items-center justify-between flex-wrap gap-2"><input type="text" placeholder="搜尋場地..." class="input-field w-64" oninput="filterTable(\\'venue-table\\',this.value)">';
  if (isAdmin) h += '<button onclick="showAddVenueForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增場地</button>';
  h += '</div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm" id="venue-table"><thead class="table-header"><tr><th class="table-cell">場地名稱</th><th class="table-cell">大樓/樓層</th><th class="table-cell">類型</th><th class="table-cell">容量</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  const typeL = { 0:'場地', 1:'教室', 2:'運動場館', 3:'會議室' };
  venues.forEach(v => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(v.FAC_NAME||'')+'</td><td class="table-cell text-gray-500">'+(v.FAC_BUILDING||'')+' '+(v.FAC_FLOOR||'')+'F</td><td class="table-cell text-xs">'+(typeL[v.FAC_TYPE]||'')+'</td><td class="table-cell">'+(v.FAC_CAPACITY||'')+' 人</td><td class="table-cell"><span class="'+(sC[v.FAC_STATUS]||'')+'">'+((sL[v.FAC_STATUS])||'')+'</span></td><td class="table-cell">';
    if (v.FAC_STATUS === 0) h += '<button onclick="showBookingForm('+v.FAC_ID+',\\''+v.FAC_NAME+'\\')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1">預約</button>';
    h += '<button onclick="showCalendar('+v.FAC_ID+',\\''+v.FAC_NAME+'\\')" class="text-xs px-2 py-1 rounded bg-purple-100 text-purple-700">行事曆</button>';
    h += '</td></tr>';
  });
  h += '</tbody></table></div>';
  // 預約紀錄
  if (bookings.length > 0) {
    const bkSL = { 0:'待審核', 1:'已核准', 2:'已拒絕', 3:'已取消', 4:'已歸還', 5:'歸還異常' };
    const bkSC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected', 3:'status-rejected', 4:'status-approved', 5:'status-rejected' };
    h += '<div class="card p-0 overflow-x-auto"><h3 class="p-4 font-bold text-fju-blue"><i class="fas fa-list mr-2 text-fju-yellow"></i>預約紀錄</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">場地</th><th class="table-cell">申請人/單位</th><th class="table-cell">時間</th><th class="table-cell">事由</th><th class="table-cell">狀態</th>'+(isAdmin?'<th class="table-cell">操作</th>':'')+'</tr></thead><tbody>';
    bookings.forEach(b => {
      h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-fju-blue font-medium">'+(b.FAC_NAME||'')+'</td><td class="table-cell text-xs">'+(b.USR_NAME||'')+' / '+(b.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(b.VB_START_DATETIME||'').slice(0,16)+'</td><td class="table-cell text-xs">'+(b.VB_PURPOSE||'')+'</td><td class="table-cell"><span class="'+(bkSC[b.VB_STATUS]||'')+'">'+((bkSL[b.VB_STATUS])||'')+'</span></td>';
      if (isAdmin) {
        h += '<td class="table-cell">';
        if (b.VB_STATUS === 0) { h += '<button onclick="approveBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button><button onclick="rejectBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">拒絕</button>'; }
        else if (b.VB_STATUS === 1) { h += '<button onclick="returnBooking('+b.VB_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue">歸還</button>'; }
        h += '</td>';
      }
      h += '</tr>';
    });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function approveBooking(id) { await fetch(API+'/venue-bookings/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('預約已核准','success'); loadVenues(); }
async function rejectBooking(id) { const r = prompt('拒絕原因：'); if(!r) return; await fetch(API+'/venue-bookings/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,reason:r}) }); loadVenues(); }
async function returnBooking(id) { await fetch(API+'/venue-bookings/'+id+'/return', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({abnormal:false}) }); loadVenues(); }
function showBookingForm(facId, facName) {
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('');
  if (!uOpts) uOpts = '<option value="1">預設單位</option>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>預約場地：'+facName+'</h3><div class="space-y-3"><select id="vb-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始</label><input id="vb-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">結束</label><input id="vb-end" type="datetime-local" class="input-field"></div></div><input id="vb-purpose" placeholder="使用事由 *" class="input-field"><input id="vb-headcount" type="number" placeholder="使用人數" class="input-field"><input type="hidden" id="vb-fac-id" value="'+facId+'"><button onclick="submitBooking()" class="w-full btn-yellow py-2.5">送出預約</button></div></div></div>');
}
async function submitBooking() {
  const body = { facId:parseInt(document.getElementById('vb-fac-id').value), unitId:parseInt(document.getElementById('vb-unit').value), userId:currentUser?.id||1, startDatetime:document.getElementById('vb-start').value, endDatetime:document.getElementById('vb-end').value, purpose:document.getElementById('vb-purpose').value, headcount:parseInt(document.getElementById('vb-headcount').value)||20, activityId:1, bookingType:0 };
  if (!body.purpose) return alert('請填寫使用事由');
  const res = await fetch(API+'/venue-bookings', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('場地預約已送出','success'); closeModal(); loadVenues(); }
  else { alert(data.message || '預約失敗，可能有時段衝突'); }
}
function showAddVenueForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-plus mr-2 text-fju-yellow"></i>新增場地</h3><div class="space-y-3"><input id="fac-name" placeholder="場地名稱 *" class="input-field"><input id="fac-building" placeholder="所在大樓 *" class="input-field"><input id="fac-floor" type="number" placeholder="樓層" class="input-field"><input id="fac-capacity" type="number" placeholder="容納人數" class="input-field"><select id="fac-type" class="input-field"><option value="0">場地</option><option value="1">教室</option><option value="2">運動場館</option><option value="3">會議室</option></select><textarea id="fac-desc" placeholder="場地描述" rows="2" class="input-field"></textarea><button onclick="submitFacility()" class="w-full btn-yellow py-2.5">新增</button></div></div></div>');
}
async function submitFacility() {
  await fetch(API+'/facilities', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ name:document.getElementById('fac-name').value, building:document.getElementById('fac-building').value, floor:parseInt(document.getElementById('fac-floor').value)||1, capacity:parseInt(document.getElementById('fac-capacity').value)||50, type:parseInt(document.getElementById('fac-type').value), desc:document.getElementById('fac-desc').value }) });
  closeModal(); loadVenues();
}
// 場地行事曆檢視 — 月曆格式 (Epic 4 行事曆視圖)
let calViewYear, calViewMonth;
async function showCalendar(facId, facName) {
  const now = new Date(); calViewYear = now.getFullYear(); calViewMonth = now.getMonth();
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content" style="max-width:720px"><h3 class="font-bold text-fju-blue text-lg mb-2"><i class="fas fa-calendar-alt mr-2 text-fju-yellow"></i>'+facName+' — 月曆</h3><div id="cal-grid"></div></div></div>');
  window._calFacId = facId; window._calFacName = facName;
  renderCalendar(facId);
}
async function renderCalendar(facId) {
  const y = calViewYear, m = calViewMonth;
  const first = new Date(y, m, 1); const last = new Date(y, m+1, 0);
  const startPad = first.getDay(); const daysInMonth = last.getDate();
  const startQ = y+'-'+String(m+1).padStart(2,'0')+'-01';
  const endQ = y+'-'+String(m+1).padStart(2,'0')+'-'+String(daysInMonth).padStart(2,'0');
  const res = await fetch(API+'/facilities/'+facId+'/calendar?start='+startQ+'&end='+endQ).then(r=>r.json()).catch(()=>({data:[]}));
  const events = res.data || [];
  const byDay = {};
  events.forEach(e => { const d = parseInt((e.VB_START_DATETIME||'').slice(8,10)); if(!byDay[d]) byDay[d]=[]; byDay[d].push(e); });
  const mNames = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
  let h = '<div class="flex items-center justify-between mb-3"><button onclick="calViewMonth--;if(calViewMonth<0){calViewMonth=11;calViewYear--;}renderCalendar(window._calFacId)" class="text-fju-blue hover:bg-fju-bg px-2 py-1 rounded"><i class="fas fa-chevron-left"></i></button><span class="font-bold text-fju-blue">'+y+' 年 '+mNames[m]+'</span><button onclick="calViewMonth++;if(calViewMonth>11){calViewMonth=0;calViewYear++;}renderCalendar(window._calFacId)" class="text-fju-blue hover:bg-fju-bg px-2 py-1 rounded"><i class="fas fa-chevron-right"></i></button></div>';
  h += '<div class="grid grid-cols-7 text-center text-xs font-bold text-gray-400 mb-1">';
  ['日','一','二','三','四','五','六'].forEach(d => { h += '<div class="py-1">'+d+'</div>'; });
  h += '</div><div class="grid grid-cols-7 gap-px bg-gray-200 border border-gray-200 rounded-fju overflow-hidden">';
  for (let i=0; i<startPad; i++) h += '<div class="bg-gray-50 min-h-[56px]"></div>';
  const today = new Date(); const isThisMonth = today.getFullYear()===y && today.getMonth()===m;
  for (let d=1; d<=daysInMonth; d++) {
    const isToday = isThisMonth && today.getDate()===d;
    const evts = byDay[d] || [];
    h += '<div class="bg-white min-h-[56px] p-1 relative '+(isToday?'ring-2 ring-fju-yellow ring-inset':'')+'">';
    h += '<div class="text-xs font-medium '+(isToday?'text-fju-blue font-bold':'text-gray-500')+'">'+d+'</div>';
    evts.slice(0,2).forEach(e => {
      const color = e.VB_STATUS===1?'bg-fju-green/20 text-fju-green border-fju-green/30':'bg-fju-yellow/20 text-fju-blue border-fju-yellow/30';
      h += '<div class="mt-0.5 text-[10px] leading-tight px-1 py-0.5 rounded border truncate '+color+'" title="'+(e.VB_PURPOSE||'')+' ('+(e.VB_START_DATETIME||'').slice(11,16)+'~'+(e.VB_END_DATETIME||'').slice(11,16)+')">'+(e.VB_PURPOSE||'').slice(0,6)+'</div>';
    });
    if (evts.length > 2) h += '<div class="text-[9px] text-gray-400 mt-0.5">+' + (evts.length - 2) + ' 更多</div>';
    h += '</div>';
  }
  const totalCells = startPad + daysInMonth; const remain = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
  for (let i=0; i<remain; i++) h += '<div class="bg-gray-50 min-h-[56px]"></div>';
  h += '</div>';
  h += '<div class="flex gap-3 mt-3 text-xs text-gray-400"><span><span class="inline-block w-3 h-3 rounded bg-fju-green/20 border border-fju-green/30 mr-1 align-middle"></span>已核准</span><span><span class="inline-block w-3 h-3 rounded bg-fju-yellow/20 border border-fju-yellow/30 mr-1 align-middle"></span>待審核</span></div>';
  document.getElementById('cal-grid').innerHTML = h;
}

// ========== Equipment (Epic 5) ==========
async function loadEquipment() {
  const [eqRes, loanRes] = await Promise.all([fetch(API+'/equipment').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/equipment/loans/list').then(r=>r.json()).catch(()=>({data:[]}))])
  const items = eqRes.data || []; const loans = loanRes.data || [];
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-4 gap-4">'+statCard('fa-box','器材項目',items.length,'blue')+statCard('fa-check','可借用',items.filter(i=>i.EQ_AVAILABLE>0).length,'green')+statCard('fa-certificate','需操作證',items.filter(i=>i.EQ_CERT_TYPE_ID).length,'yellow')+statCard('fa-boxes-stacked','總庫存',items.reduce((s,i)=>s+i.EQ_TOTAL,0),'purple')+'</div>';
  if (['officer','professor','staff','admin'].includes(currentUser?.role) || currentUser?.isAdmin===1) h += '<div class="flex justify-end"><button onclick="showLoanForm()" class="btn-yellow text-xs"><i class="fas fa-hand-holding mr-1"></i>借用器材</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">器材名稱</th><th class="table-cell">總數</th><th class="table-cell">可用</th><th class="table-cell">單次上限</th><th class="table-cell">需操作證</th><th class="table-cell">說明</th></tr></thead><tbody>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.EQ_NAME||'')+'</td><td class="table-cell">'+i.EQ_TOTAL+'</td><td class="table-cell">'+(i.EQ_AVAILABLE>0?'<span class="status-approved">'+i.EQ_AVAILABLE+'</span>':'<span class="status-rejected">0</span>')+'</td><td class="table-cell">'+i.EQ_MAX_PER_LOAN+'</td><td class="table-cell">'+(i.CERT_NAME?'<span class="px-2 py-0.5 rounded-fju bg-fju-yellow/20 text-fju-blue text-xs">'+i.CERT_NAME+'</span>':'<span class="text-gray-400">-</span>')+'</td><td class="table-cell text-xs text-gray-400">'+(i.EQ_DESC||'-')+'</td></tr>';
  });
  h += '</tbody></table></div>';
  if (loans.length > 0) {
    const elSL = { 0:'待領取', 1:'借用中', 2:'部分領取', 3:'部分歸還', 4:'已歸還', 5:'歸還異常' };
    h += '<div class="card p-0 overflow-x-auto"><h3 class="p-4 font-bold text-fju-blue"><i class="fas fa-history mr-2 text-fju-yellow"></i>借用紀錄</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">借用人</th><th class="table-cell">單位</th><th class="table-cell">用途</th><th class="table-cell">借用日</th><th class="table-cell">歸還期限</th><th class="table-cell">狀態</th></tr></thead><tbody>';
    loans.forEach(l => { h += '<tr class="border-t border-gray-50"><td class="table-cell">'+(l.USR_NAME||'')+'</td><td class="table-cell text-xs">'+(l.UNIT_NAME||'')+'</td><td class="table-cell text-xs">'+(l.EL_PURPOSE||'')+'</td><td class="table-cell text-xs">'+(l.EL_BORROW_START||'').slice(0,10)+'</td><td class="table-cell text-xs">'+(l.EL_RETURN_DUE||'').slice(0,10)+'</td><td class="table-cell text-xs">'+(elSL[l.EL_STATUS]||'')+'</td></tr>'; });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
  window._eqItems = items;
}
function showLoanForm() {
  const items = window._eqItems || [];
  const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">預設單位</option>';
  let eqHtml = items.filter(i=>i.EQ_AVAILABLE>0).map(i => '<label class="flex items-center gap-2 p-2 rounded-fju hover:bg-fju-bg"><input type="checkbox" class="eq-check" value="'+i.EQ_ID+'"><span class="text-sm">'+i.EQ_NAME+' (可用:'+i.EQ_AVAILABLE+')</span><input type="number" class="eq-qty w-16 px-2 py-1 border rounded text-xs" min="1" max="'+i.EQ_MAX_PER_LOAN+'" value="1"></label>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hand-holding mr-2 text-fju-yellow"></i>借用器材</h3><div class="space-y-3"><select id="el-unit" class="input-field">'+uOpts+'</select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">領取日期</label><input id="el-start" type="datetime-local" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">歸還期限</label><input id="el-due" type="datetime-local" class="input-field"></div></div><input id="el-location" placeholder="使用地點 *" class="input-field"><input id="el-purpose" placeholder="用途說明 *" class="input-field"><div class="border rounded-fju p-3 max-h-40 overflow-y-auto"><p class="text-xs text-gray-500 mb-2">選擇器材：</p>'+eqHtml+'</div><button onclick="submitLoan()" class="w-full btn-yellow py-2.5">送出借用申請</button></div></div></div>');
}
async function submitLoan() {
  const checks = document.querySelectorAll('.eq-check:checked');
  const items = [];
  checks.forEach(ck => { const row = ck.closest('label'); const qty = row.querySelector('.eq-qty'); items.push({ equipmentId: parseInt(ck.value), quantity: parseInt(qty.value)||1 }); });
  if (items.length === 0) return alert('請選擇至少一項器材');
  const body = { activityId:1, unitId:parseInt(document.getElementById('el-unit').value), userId:currentUser?.id||1, borrowStart:document.getElementById('el-start').value, returnDue:document.getElementById('el-due').value, useLocation:document.getElementById('el-location').value, purpose:document.getElementById('el-purpose').value, items };
  const res = await fetch(API+'/equipment/loans', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('器材借用已送出','success'); closeModal(); loadEquipment(); }
  else { alert(data.message || '借用失敗'); }
}

// ========== Coordination (場協大會 Epic 4.2) ==========
async function loadCoordination() {
  const res = await fetch(API+'/venue-coordination?semester=114-2').then(r=>r.json()).catch(()=>({data:[]}));
  const items = res.data || [];
  const dayNames = ['日','一','二','三','四','五','六'];
  const sL = { 0:'待審核', 1:'已核准', 2:'已駁回' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>場協大會說明</h3><div class="text-xs text-gray-600 space-y-1"><p>1. 學期初由課指組舉辦場協大會，各單位登記每週固定使用場地需求</p><p>2. 登記截止後，課指組統一審核並協調衝突</p><p>3. 核准後系統自動產生整學期的場地預約</p></div></div>';
  if (['officer','admin'].includes(currentUser?.role) || isAdmin) h += '<div class="flex justify-end"><button onclick="showCoordinationForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增登記</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">單位</th><th class="table-cell">場地</th><th class="table-cell">每週</th><th class="table-cell">時段</th><th class="table-cell">用途</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">暫無登記</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.UNIT_NAME||'')+'</td><td class="table-cell">'+(i.FAC_NAME||'')+'</td><td class="table-cell">週'+(dayNames[i.VC_DAY_OF_WEEK]||'')+'</td><td class="table-cell text-xs">'+(i.VC_TIME_START||'')+' ~ '+(i.VC_TIME_END||'')+'</td><td class="table-cell text-xs">'+(i.VC_PURPOSE||'')+'</td><td class="table-cell"><span class="'+(sC[i.VC_STATUS]||'')+'">'+((sL[i.VC_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.VC_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveCoord('+i.VC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectCoord('+i.VC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">駁回</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function approveCoord(id) { await fetch(API+'/venue-coordination/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({}) }); toast('登記已核准','success'); loadCoordination(); }
async function rejectCoord(id) { const note = prompt('駁回原因：'); if(!note) return; await fetch(API+'/venue-coordination/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({note}) }); loadCoordination(); }
async function showCoordinationForm() {
  const facRes = await fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]}));
  const facs = facRes.data || []; const units = currentUser?.units || [];
  let uOpts = units.map(u => '<option value="'+u.UM_UNIT_ID+'">'+u.UNIT_NAME+'</option>').join('') || '<option value="1">預設單位</option>';
  let fOpts = facs.filter(f=>f.FAC_STATUS===0).map(f => '<option value="'+f.FAC_ID+'">'+f.FAC_NAME+'</option>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-users-rectangle mr-2 text-fju-yellow"></i>場協大會登記</h3><div class="space-y-3"><select id="vc-unit" class="input-field">'+uOpts+'</select><select id="vc-fac" class="input-field">'+fOpts+'</select><select id="vc-day" class="input-field"><option value="1">週一</option><option value="2">週二</option><option value="3">週三</option><option value="4">週四</option><option value="5">週五</option></select><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始</label><input id="vc-start" type="time" class="input-field" value="14:00"></div><div><label class="text-xs text-gray-500 block mb-1">結束</label><input id="vc-end" type="time" class="input-field" value="17:00"></div></div><input id="vc-purpose" placeholder="使用事由" class="input-field"><button onclick="submitCoordination()" class="w-full btn-yellow py-2.5">送出登記</button></div></div></div>');
}
async function submitCoordination() {
  const body = { userId:currentUser?.id||1, unitId:parseInt(document.getElementById('vc-unit').value), facId:parseInt(document.getElementById('vc-fac').value), dayOfWeek:parseInt(document.getElementById('vc-day').value), timeStart:document.getElementById('vc-start').value, timeEnd:document.getElementById('vc-end').value, purpose:document.getElementById('vc-purpose').value, semester:'114-2' };
  const res = await fetch(API+'/venue-coordination', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  const data = await res.json();
  if (data.success) { toast('登記已送出','success'); closeModal(); loadCoordination(); }
  else { alert(data.message || '登記失敗'); }
}

// ========== Repairs (Epic 6) ==========
async function loadRepairs() {
  const [repRes, facRes] = await Promise.all([fetch(API+'/repairs').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/facilities').then(r=>r.json()).catch(()=>({data:[]}))])
  const items = repRes.data || []; window._facilities = facRes.data || [];
  const sL = { 0:'待處理', 1:'處理中', 2:'已完成' }; const sC = { 0:'status-pending', 1:'status-info', 2:'status-approved' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in"><div class="flex justify-end"><button onclick="showRepairForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>新增報修</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">設施</th><th class="table-cell">問題描述</th><th class="table-cell">申報人</th><th class="table-cell">狀態</th><th class="table-cell">時間</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">暫無報修</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell font-medium text-fju-blue">'+(i.FAC_NAME||'-')+'</td><td class="table-cell text-xs">'+(i.RR_DESCRIPTION||'')+'</td><td class="table-cell text-xs">'+(i.USR_NAME||'-')+'</td><td class="table-cell"><span class="'+(sC[i.RR_STATUS]||'')+'">'+((sL[i.RR_STATUS])||'')+'</span></td><td class="table-cell text-xs text-gray-400">'+(i.RR_CREATED_AT||'')+'</td><td class="table-cell">';
    if (i.RR_STATUS < 2 && isAdmin) {
      const ns = i.RR_STATUS === 0 ? 1 : 2;
      h += '<button onclick="updateRepairStatus('+i.RR_ID+','+ns+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue">'+(ns===1?'開始處理':'完成')+'</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showRepairForm() {
  const facs = window._facilities || [];
  let opts = facs.map(f => '<option value="'+f.FAC_ID+'">'+f.FAC_NAME+'</option>').join('');
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>新增報修</h3><div class="space-y-3"><select id="rr-fac" class="input-field"><option value="">選擇設施 *</option>'+opts+'</select><textarea id="rr-desc" placeholder="問題描述（至少10字）*" rows="3" class="input-field"></textarea><button onclick="submitRepair()" class="w-full btn-yellow py-2.5">送出報修</button></div></div></div>');
}
async function submitRepair() {
  const desc = document.getElementById('rr-desc').value;
  if (desc.length < 10) return alert('問題描述至少需要10個字');
  await fetch(API+'/repairs', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ facId:parseInt(document.getElementById('rr-fac').value), userId:currentUser?.id||1, description:desc }) });
  toast('報修已送出','success'); closeModal(); loadRepairs();
}
async function updateRepairStatus(id, status) { await fetch(API+'/repairs/'+id, { method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ status, adminId:currentUser?.id||1 }) }); loadRepairs(); }

// ========== Appeals (Epic 7) ==========
async function loadAppeals() {
  const res = await fetch(API+'/appeals').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const tL = { 0:'停權申復', 1:'違規記點申復', 2:'其他檢舉' }; const sL = { 0:'待審核', 1:'已核准', 2:'已駁回' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in"><div class="flex justify-end"><button onclick="showAppealForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>提交申訴</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">類型</th><th class="table-cell">申訴人</th><th class="table-cell">理由</th><th class="table-cell">狀態</th><th class="table-cell">時間</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">暫無申訴</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell"><span class="px-2 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(tL[i.AC_TYPE]||'')+'</span></td><td class="table-cell text-sm">'+(i.USR_NAME||'-')+'</td><td class="table-cell text-xs">'+(i.AC_REASON||'')+'</td><td class="table-cell"><span class="'+(sC[i.AC_STATUS]||'')+'">'+((sL[i.AC_STATUS])||'')+'</span></td><td class="table-cell text-xs text-gray-400">'+(i.AC_CREATED_AT||'')+'</td><td class="table-cell">';
    if (i.AC_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveAppeal('+i.AC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectAppeal('+i.AC_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">駁回</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAppealForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>提交申訴</h3><div class="space-y-3"><select id="ac-type" class="input-field"><option value="0">停權申復</option><option value="1">違規記點申復</option><option value="2">其他檢舉</option></select><textarea id="ac-reason" placeholder="申訴理由 *（請詳述事由）" rows="3" class="input-field"></textarea><input id="ac-evidence" placeholder="佐證資料（選填）" class="input-field"><button onclick="submitAppeal()" class="w-full btn-yellow py-2.5">送出申訴</button></div></div></div>');
}
async function submitAppeal() {
  const reason = document.getElementById('ac-reason').value;
  if (!reason) return alert('請填寫申訴理由');
  await fetch(API+'/appeals', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ userId:currentUser?.id||1, type:parseInt(document.getElementById('ac-type').value), reason, evidence:document.getElementById('ac-evidence').value }) });
  toast('申訴已送出','success'); closeModal(); loadAppeals();
}
async function approveAppeal(id) { await fetch(API+'/appeals/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('申訴已核准','success'); loadAppeals(); }
async function rejectAppeal(id) { const note = prompt('駁回原因：'); if(!note) return; await fetch(API+'/appeals/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1,note}) }); loadAppeals(); }

// ========== Announcements (Epic 8) ==========
async function loadAnnouncements() {
  const res = await fetch(API+'/announcements').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  if (isAdmin) h += '<div class="flex justify-end"><button onclick="showAnnouncementForm()" class="btn-yellow text-xs"><i class="fas fa-plus mr-1"></i>新增公告</button></div>';
  if (items.length === 0) h += '<div class="card text-center text-gray-400"><i class="fas fa-bullhorn text-2xl mb-2"></i><p>暫無公告</p></div>';
  items.forEach(a => {
    h += '<div class="card hover:border-fju-yellow/30 transition-all"><div class="flex items-start justify-between"><div class="flex-1"><h3 class="font-bold text-fju-blue">'+(a.ANN_TITLE||'')+'</h3><p class="text-sm text-gray-600 mt-2 whitespace-pre-line">'+(a.ANN_CONTENT||'')+'</p><div class="flex gap-3 mt-3 text-xs text-gray-400"><span><i class="fas fa-calendar mr-1"></i>'+(a.ANN_START_DATE||'')+' ~ '+(a.ANN_END_DATE||'')+'</span><span><i class="fas fa-user mr-1"></i>'+(a.ADMIN_NAME||'管理員')+'</span></div></div>';
    if (isAdmin) h += '<button onclick="deleteAnnouncement('+a.ANN_ID+')" class="text-gray-300 hover:text-fju-red ml-3"><i class="fas fa-trash"></i></button>';
    h += '</div></div>';
  });
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAnnouncementForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-bullhorn mr-2 text-fju-yellow"></i>新增公告</h3><div class="space-y-3"><input id="ann-title" placeholder="公告標題 *" class="input-field"><textarea id="ann-content" placeholder="公告內容 *" rows="4" class="input-field"></textarea><div class="grid grid-cols-2 gap-2"><div><label class="text-xs text-gray-500 block mb-1">開始日期</label><input id="ann-start" type="date" class="input-field"></div><div><label class="text-xs text-gray-500 block mb-1">結束日期</label><input id="ann-end" type="date" class="input-field"></div></div><button onclick="submitAnnouncement()" class="w-full btn-yellow py-2.5">發布公告</button></div></div></div>');
}
async function submitAnnouncement() {
  await fetch(API+'/announcements', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ title:document.getElementById('ann-title').value, content:document.getElementById('ann-content').value, startDate:document.getElementById('ann-start').value, endDate:document.getElementById('ann-end').value, adminId:currentUser?.id||1 }) });
  toast('公告已發布','success'); closeModal(); loadAnnouncements();
}
async function deleteAnnouncement(id) { if(!confirm('確定刪除此公告？')) return; await fetch(API+'/announcements/'+id, { method:'DELETE' }); loadAnnouncements(); }

// ========== FAQ / AI (Prompt7 9A: 整合至單一頁面) ==========
async function loadFaq() {
  const role = currentUser?.role || 'student';
  const res = await fetch(API+'/faq?role='+role).then(r=>r.json()).catch(()=>({common:[],roleSpecific:[],regulations:[]}));
  let h = '<div class="space-y-6 fade-in">';
  // AI Chat
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 智慧助理 <span class="text-xs font-normal text-gray-400 ml-2">GPT-4o via GitHub Models</span></h3>';
  h += '<div id="ai-chat-history" class="space-y-3 max-h-72 overflow-y-auto mb-3 p-3 bg-fju-bg rounded-fju-lg">';
  h += '<div class="flex"><div class="bg-white border border-gray-100 rounded-fju-lg px-4 py-2 text-sm max-w-[85%] shadow-sm"><div class="text-gray-700">👋 您好！我是課指組AI助理。您可以問我關於場地預約、器材借用、違規記點等問題。</div><div class="text-xs text-gray-400 mt-1">RAG 知識庫</div></div></div>';
  h += '</div>';
  h += '<div class="flex gap-2"><input id="ai-input" type="text" placeholder="輸入問題...（例如：場地預約怎麼操作？）" class="flex-1 input-field" onkeypress="if(event.key===\\'Enter\\')askAI()"><button onclick="askAI()" class="btn-primary px-6"><i class="fas fa-paper-plane mr-1"></i>送出</button></div>';
  h += '<div class="flex flex-wrap gap-2 mt-2">';
  ['場地預約','器材借用','違規','衝突協調','操作證','活動申請','場協大會'].forEach(k => { h += '<button onclick="document.getElementById(\\'ai-input\\').value=\\''+k+'\\';askAI()" class="text-xs px-3 py-1 rounded-fju bg-fju-blue/5 text-fju-blue hover:bg-fju-blue/10">'+k+'</button>'; });
  h += '</div></div>';
  // Role-specific FAQ
  if (res.roleSpecific?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-user-tag mr-2 text-fju-yellow"></i>'+(roleNames[role]||'')+'常見問題</h3><div class="space-y-2">';
    res.roleSpecific.forEach(f => {
      h += '<details class="border border-gray-100 rounded-fju"><summary class="p-3 cursor-pointer hover:bg-fju-bg text-sm font-medium text-fju-blue"><i class="fas fa-chevron-right mr-2 text-xs text-fju-yellow"></i>'+f.q+'</summary><div class="p-3 pt-0 text-sm text-gray-600 whitespace-pre-line border-t border-gray-100 mt-1">'+f.a+'</div></details>';
    });
    h += '</div></div>';
  }
  // Common FAQ
  if (res.common?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-question-circle mr-2 text-fju-yellow"></i>通用常見問題</h3><div class="space-y-2">';
    res.common.forEach(f => {
      h += '<details class="border border-gray-100 rounded-fju"><summary class="p-3 cursor-pointer hover:bg-fju-bg text-sm font-medium text-gray-700"><i class="fas fa-chevron-right mr-2 text-xs text-fju-yellow"></i>'+f.q+' <span class="text-xs text-gray-400 ml-2">'+f.category+'</span></summary><div class="p-3 pt-0 text-sm text-gray-600 whitespace-pre-line border-t border-gray-100 mt-1">'+f.a+'</div></details>';
    });
    h += '</div></div>';
  }
  // Regulations
  if (res.regulations?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-book mr-2 text-fju-yellow"></i>法規參考</h3><div class="space-y-2">';
    res.regulations.forEach(r => { h += '<div class="p-3 rounded-fju bg-fju-bg border border-gray-100"><div class="font-medium text-sm text-fju-blue">'+(r.name||'')+'</div><div class="text-xs text-gray-500 mt-1">'+(r.summary||'')+'</div></div>'; });
    h += '</div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function askAI() {
  const input = document.getElementById('ai-input');
  const msg = input.value.trim(); if (!msg) return;
  const history = document.getElementById('ai-chat-history');
  history.innerHTML += '<div class="flex justify-end"><div class="bg-fju-blue text-white rounded-fju-lg px-4 py-2 text-sm max-w-[80%]">'+msg+'</div></div>';
  history.innerHTML += '<div id="ai-loading" class="flex"><div class="bg-fju-bg rounded-fju-lg px-4 py-2 text-sm text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>AI 思考中...</div></div>';
  history.scrollTop = history.scrollHeight; input.value = '';
  try {
    const res = await fetch(API+'/ai/chat', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ message:msg, role:currentUser?.role||'student' }) });
    const data = await res.json();
    document.getElementById('ai-loading')?.remove();
    history.innerHTML += '<div class="flex"><div class="bg-white border border-gray-100 rounded-fju-lg px-4 py-3 text-sm max-w-[85%] shadow-sm"><div class="whitespace-pre-line text-gray-700">'+(data.response||'')+'</div><div class="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-100"><i class="fas fa-microchip mr-1"></i>'+(data.source||data.model||'AI')+'</div></div></div>';
  } catch(e) {
    document.getElementById('ai-loading')?.remove();
    history.innerHTML += '<div class="flex"><div class="bg-red-50 rounded-fju-lg px-4 py-2 text-sm text-red-500">AI 回應失敗，請稍後重試</div></div>';
  }
  history.scrollTop = history.scrollHeight;
}

// ========== Stats (Epic 10) ==========
async function loadStats() {
  const [dashRes, facRes, eqRes] = await Promise.all([fetch(API+'/stats/dashboard').then(r=>r.json()).catch(()=>({})), fetch(API+'/stats/facility-usage').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/stats/equipment-usage').then(r=>r.json()).catch(()=>({data:[]}))])
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="grid grid-cols-2 md:grid-cols-5 gap-4">'+statCard('fa-building','場地',dashRes.totalFacilities,'blue')+statCard('fa-tools','器材',dashRes.totalEquipment,'green')+statCard('fa-users','使用者',dashRes.totalUsers,'purple')+statCard('fa-calendar-check','已核准預約',dashRes.approvedBookings,'yellow')+statCard('fa-wrench','待修',dashRes.openRepairs,'red')+'</div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地使用統計</h3><div class="space-y-3">';
  (facRes.data||[]).forEach(f => { const pct = Math.min((f.booking_count||0)*5,100); h += '<div class="flex items-center gap-3"><span class="w-32 text-sm text-gray-600 truncate">'+(f.FAC_NAME||'')+'</span><div class="flex-1 bg-gray-200 rounded-full h-3"><div class="bg-fju-blue rounded-full h-3 transition-all" style="width:'+pct+'%"></div></div><span class="text-xs text-gray-500 w-24 text-right">'+(f.booking_count||0)+' 次 / '+parseFloat(f.total_hours||0).toFixed(1)+'h</span></div>'; });
  h += '</div></div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-boxes-stacked mr-2 text-fju-yellow"></i>器材使用統計</h3><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">器材</th><th class="table-cell">總數</th><th class="table-cell">可用</th><th class="table-cell">借出次數</th><th class="table-cell">累計借出</th></tr></thead><tbody>';
  (eqRes.data||[]).forEach(e => { h += '<tr class="border-t border-gray-50"><td class="table-cell font-medium text-fju-blue">'+(e.EQ_NAME||'')+'</td><td class="table-cell">'+e.EQ_TOTAL+'</td><td class="table-cell">'+e.EQ_AVAILABLE+'</td><td class="table-cell">'+e.loan_count+'</td><td class="table-cell">'+e.total_borrowed+'</td></tr>'; });
  h += '</tbody></table></div>';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI 學期總結評鑑報告</h3><p class="text-sm text-gray-500 mb-3">含 Simpson 多樣性指數、使用率分析、違規統計、資源調配建議</p><button onclick="generateAIReport()" class="btn-yellow text-sm"><i class="fas fa-magic mr-1"></i>產生學期評鑑報告</button><div id="ai-report-content" class="hidden mt-4"></div></div>';
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function generateAIReport() {
  const el = document.getElementById('ai-report-content');
  el.classList.remove('hidden'); el.innerHTML = '<div class="text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>AI 正在生成報告...</div>';
  try {
    const res = await fetch(API+'/ai/generate-report', { method:'POST', headers:{'Content-Type':'application/json'}, body:'{}' });
    const data = await res.json();
    let rh = '<div class="space-y-4 p-4 bg-fju-bg rounded-fju-lg border border-gray-200">';
    rh += '<h4 class="font-bold text-fju-blue text-lg">'+(data.reportTitle||'')+'</h4>';
    rh += '<div class="grid md:grid-cols-2 gap-4">';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-chart-pie mr-1 text-fju-yellow"></i>Simpson 多樣性指數</h5><div class="text-3xl font-black text-fju-blue">D = '+(data.simpson?.value||0)+'</div><p class="text-xs text-gray-500 mt-1">'+(data.simpson?.interpretation||'')+'</p>';
    if (data.simpson?.unitBreakdown?.length) { rh += '<div class="mt-2 space-y-1">'; data.simpson.unitBreakdown.forEach(u => { rh += '<div class="flex justify-between text-xs"><span class="text-gray-500">'+(u.UNIT_NAME||'未知')+'</span><span class="font-bold text-fju-blue">'+u.usage_count+' 次</span></div>'; }); rh += '</div>'; }
    rh += '</div>';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-calendar-check mr-1 text-fju-yellow"></i>活動統計</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-green">'+(data.activitySummary?.approved||0)+'</div><div class="text-xs text-gray-400">已核准</div></div><div><div class="text-lg font-bold text-fju-yellow">'+(data.activitySummary?.pending||0)+'</div><div class="text-xs text-gray-400">待審核</div></div><div><div class="text-lg font-bold text-fju-red">'+(data.activitySummary?.rejected||0)+'</div><div class="text-xs text-gray-400">已拒絕</div></div></div></div>';
    rh += '</div>';
    rh += '<div class="grid md:grid-cols-2 gap-4">';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-wrench mr-1 text-fju-yellow"></i>報修統計</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-yellow">'+(data.repairSummary?.pending||0)+'</div><div class="text-xs text-gray-400">待處理</div></div><div><div class="text-lg font-bold text-fju-blue">'+(data.repairSummary?.in_progress||0)+'</div><div class="text-xs text-gray-400">處理中</div></div><div><div class="text-lg font-bold text-fju-green">'+(data.repairSummary?.completed||0)+'</div><div class="text-xs text-gray-400">已完成</div></div></div></div>';
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-handshake mr-1 text-fju-yellow"></i>衝突協調</h5><div class="grid grid-cols-3 gap-2 text-center"><div><div class="text-lg font-bold text-fju-blue">'+(data.conflictSummary?.total||0)+'</div><div class="text-xs text-gray-400">總案件</div></div><div><div class="text-lg font-bold text-fju-green">'+(data.conflictSummary?.resolved||0)+'</div><div class="text-xs text-gray-400">已解決</div></div><div><div class="text-lg font-bold text-fju-red">'+(data.conflictSummary?.failed||0)+'</div><div class="text-xs text-gray-400">失敗</div></div></div></div>';
    rh += '</div>';
    if (data.peakHours?.length) { rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-clock mr-1 text-fju-yellow"></i>熱門預約時段 TOP 5</h5><div class="flex gap-2 flex-wrap">'; data.peakHours.forEach(p => { rh += '<span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs font-bold">'+p.hour+':00 <span class="text-gray-400">('+p.cnt+'次)</span></span>'; }); rh += '</div></div>'; }
    rh += '<div class="p-4 bg-white rounded-fju"><h5 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-lightbulb mr-1 text-fju-yellow"></i>AI 智慧建議</h5><ul class="text-xs text-gray-600 space-y-1.5">';
    (data.recommendations||[]).forEach(r => { rh += '<li class="flex items-start gap-1.5"><i class="fas fa-check-circle text-fju-green mt-0.5 flex-shrink-0"></i><span>'+r+'</span></li>'; });
    rh += '</ul></div>';
    rh += '<div class="text-xs text-gray-400 text-right">產生時間：'+new Date(data.generatedAt||'').toLocaleString('zh-TW')+'</div></div>';
    el.innerHTML = rh;
  } catch(e) { el.innerHTML = '<div class="text-red-500 text-sm">報告產生失敗</div>'; }
}

// ========== Users Management ==========
async function loadUsers() {
  const res = await fetch(API+'/users').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  let h = '<div class="space-y-4 fade-in"><div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">ID</th><th class="table-cell">姓名</th><th class="table-cell">Email</th><th class="table-cell">角色</th><th class="table-cell">管理員</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  items.forEach(u => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs">'+u.USR_ID+'</td><td class="table-cell font-medium text-fju-blue">'+(u.USR_NAME||'')+'</td><td class="table-cell text-xs text-gray-500">'+(u.USR_EMAIL||'')+'</td><td class="table-cell"><span class="px-2 py-0.5 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(roleNames[u.USR_ROLE]||u.USR_ROLE)+'</span></td><td class="table-cell">'+(u.USR_IS_ADMIN?'<i class="fas fa-shield-alt text-fju-yellow"></i>':'<span class="text-gray-300">-</span>')+'</td><td class="table-cell">'+(u.USR_SUSPENDED?'<span class="status-rejected">停權</span>':'<span class="status-approved">正常</span>')+'</td><td class="table-cell"><div class="flex gap-1">';
    h += '<button onclick="toggleAdmin('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-yellow/20 text-fju-blue" title="切換管理員"><i class="fas fa-shield-alt"></i></button>';
    h += u.USR_SUSPENDED ? '<button onclick="unsuspendUser('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green">解除</button>' : '<button onclick="suspendUser('+u.USR_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">停權</button>';
    h += '</div></td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function toggleAdmin(id) { await fetch(API+'/users/'+id+'/toggle-admin', { method:'PATCH' }); loadUsers(); }
async function suspendUser(id) { const reason = prompt('停權原因：'); if(!reason) return; await fetch(API+'/users/'+id+'/suspend', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({reason}) }); loadUsers(); }
async function unsuspendUser(id) { await fetch(API+'/users/'+id+'/unsuspend', { method:'PATCH' }); loadUsers(); }

// ========== Violations (Epic 9) ==========
async function loadViolations() {
  const [logRes, ptsRes] = await Promise.all([fetch(API+'/violations').then(r=>r.json()).catch(()=>({data:[]})), fetch(API+'/violations/unit-points').then(r=>r.json()).catch(()=>({data:[]}))])
  const logs = logRes.data || []; const points = ptsRes.data || [];
  const srcL = { 0:'人工', 1:'系統自動', 2:'申復撤銷', 3:'勞動服務銷點' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>單位違規點數一覽</h3><div class="grid md:grid-cols-3 gap-3">';
  points.forEach(p => { const danger = p.UVP_POINT >= 10; h += '<div class="p-3 rounded-fju border '+(danger?'border-fju-red bg-fju-red/5':'border-gray-100')+' flex items-center justify-between"><div><span class="font-medium text-sm">'+(p.UNIT_NAME||'')+'</span>'+(p.UVP_SUSPENDED?'<span class="ml-2 status-rejected">停權中</span>':'')+'</div><span class="text-lg font-black '+(danger?'text-fju-red':'text-fju-blue')+'">'+p.UVP_POINT+' 點</span></div>'; });
  h += '</div></div>';
  if (isAdmin) h += '<div class="flex justify-end"><button onclick="showAddViolationForm()" class="btn-danger text-xs"><i class="fas fa-plus mr-1"></i>新增記點</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">對象</th><th class="table-cell">增減</th><th class="table-cell">原因</th><th class="table-cell">來源</th><th class="table-cell">操作者</th><th class="table-cell">時間</th></tr></thead><tbody>';
  if (logs.length === 0) h += '<tr><td colspan="6" class="table-cell text-center text-gray-400">暫無記錄</td></tr>';
  logs.forEach(l => { const target = l.VPL_TARGET_TYPE === 0 ? (l.USR_NAME||'個人') : (l.UNIT_NAME||'單位'); h += '<tr class="border-t border-gray-50"><td class="table-cell">'+target+'</td><td class="table-cell"><span class="font-bold '+(l.VPL_DELTA>0?'text-fju-red':'text-fju-green')+'">'+((l.VPL_DELTA>0)?'+':'')+l.VPL_DELTA+'</span></td><td class="table-cell text-xs">'+(l.VPL_REASON||'')+'</td><td class="table-cell text-xs">'+(srcL[l.VPL_SOURCE]||'')+'</td><td class="table-cell text-xs">'+(l.ADMIN_NAME||'系統')+'</td><td class="table-cell text-xs text-gray-400">'+(l.VPL_CREATED_AT||'')+'</td></tr>'; });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showAddViolationForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>新增記點</h3><div class="space-y-3"><select id="vpl-target" class="input-field"><option value="1">單位</option><option value="0">個人</option></select><input id="vpl-unit-id" type="number" placeholder="單位 ID" class="input-field"><input id="vpl-delta" type="number" placeholder="點數（正數加點，負數扣點）" class="input-field" value="2"><input id="vpl-reason" placeholder="原因 *" class="input-field"><button onclick="submitViolation()" class="w-full btn-danger py-2.5">送出</button></div></div></div>');
}
async function submitViolation() {
  const body = { targetType:parseInt(document.getElementById('vpl-target').value), unitId:parseInt(document.getElementById('vpl-unit-id').value)||null, delta:parseInt(document.getElementById('vpl-delta').value), reason:document.getElementById('vpl-reason').value, source:0, adminId:currentUser?.id||1 };
  await fetch(API+'/violations', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  closeModal(); loadViolations();
}

// ========== Labor Service (Epic 9 銷點) ==========
async function loadLabor() {
  const res = await fetch(API+'/labor').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const sL = { 0:'待審核', 1:'已核准', 2:'已駁回' }; const sC = { 0:'status-pending', 1:'status-approved', 2:'status-rejected' };
  const isAdmin = currentUser?.isAdmin === 1 || currentUser?.role === 'admin';
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>勞動服務銷點說明</h3><p class="text-xs text-gray-600">違規點數可透過完成勞動服務來扣除。提交申請後經管理員審核通過即可銷點。</p></div>';
  h += '<div class="flex justify-end"><button onclick="showLaborForm()" class="btn-primary text-xs"><i class="fas fa-plus mr-1"></i>申請勞動服務銷點</button></div>';
  h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">申請人</th><th class="table-cell">服務類型</th><th class="table-cell">服務日期</th><th class="table-cell">時數</th><th class="table-cell">扣除點數</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
  if (items.length === 0) h += '<tr><td colspan="7" class="table-cell text-center text-gray-400">暫無申請</td></tr>';
  items.forEach(i => {
    h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell">'+(i.USR_NAME||'')+'</td><td class="table-cell text-xs">'+(i.LSA_SERVICE_TYPE||'')+'</td><td class="table-cell text-xs">'+(i.LSA_SERVICE_DATE||'')+'</td><td class="table-cell">'+i.LSA_HOURS+' 小時</td><td class="table-cell font-bold text-fju-green">-'+i.LSA_POINTS_TO_DEDUCT+'</td><td class="table-cell"><span class="'+(sC[i.LSA_STATUS]||'')+'">'+((sL[i.LSA_STATUS])||'')+'</span></td><td class="table-cell">';
    if (i.LSA_STATUS === 0 && isAdmin) {
      h += '<button onclick="approveLabor('+i.LSA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green mr-1">核准</button>';
      h += '<button onclick="rejectLabor('+i.LSA_ID+')" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red">駁回</button>';
    }
    h += '</td></tr>';
  });
  h += '</tbody></table></div></div>';
  document.getElementById('page-content').innerHTML = h;
}
function showLaborForm() {
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hands-helping mr-2 text-fju-yellow"></i>申請勞動服務銷點</h3><div class="space-y-3"><select id="lsa-type" class="input-field"><option value="場地清潔">場地清潔</option><option value="器材整理">器材整理</option><option value="活動支援">活動支援</option><option value="行政協助">行政協助</option></select><input id="lsa-date" type="date" class="input-field"><input id="lsa-hours" type="number" placeholder="服務時數" class="input-field" value="2"><input id="lsa-points" type="number" placeholder="申請扣除點數" class="input-field" value="1"><button onclick="submitLabor()" class="w-full btn-yellow py-2.5">送出申請</button></div></div></div>');
}
async function submitLabor() {
  const body = { userId:currentUser?.id||1, serviceType:document.getElementById('lsa-type').value, serviceDate:document.getElementById('lsa-date').value, hours:parseInt(document.getElementById('lsa-hours').value)||2, pointsToDeduct:parseInt(document.getElementById('lsa-points').value)||1 };
  await fetch(API+'/labor', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(body) });
  toast('銷點申請已送出','success'); closeModal(); loadLabor();
}
async function approveLabor(id) { await fetch(API+'/labor/'+id+'/approve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); toast('銷點已核准','success'); loadLabor(); }
async function rejectLabor(id) { await fetch(API+'/labor/'+id+'/reject', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({adminId:currentUser?.id||1}) }); loadLabor(); }

// ========== Conflicts (Epic 4.2) ==========
async function loadConflicts() {
  const res = await fetch(API+'/conflicts').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const sL = { 0:'邀請中', 1:'協調中', 2:'協調成功', 3:'協調失敗', 4:'超時關閉', 5:'邀請被拒' };
  const sC = { 0:'status-pending', 1:'status-info', 2:'status-approved', 3:'status-rejected', 4:'status-rejected', 5:'status-rejected' };
  let h = '<div class="space-y-4 fade-in">';
  h += '<div class="card bg-fju-bg"><h3 class="font-bold text-fju-blue mb-2"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>衝突協調機制</h3><div class="text-xs text-gray-600 space-y-1"><p>1. 場協大會登記：學期初登記場地需求，由課指組協調</p><p>2. 私下協調：系統偵測衝突後，雙方透過匿名聊天室協商</p><p>3. 聊天室限時 24 小時，一方撤回即解決；紀錄保存半年</p></div></div>';
  if (items.length === 0) h += '<div class="card text-center text-gray-400"><i class="fas fa-handshake text-2xl mb-2"></i><p>目前無衝突協調案件</p></div>';
  else {
    h += '<div class="card p-0 overflow-x-auto"><table class="w-full text-sm"><thead class="table-header"><tr><th class="table-cell">ID</th><th class="table-cell">甲方</th><th class="table-cell">乙方</th><th class="table-cell">場地</th><th class="table-cell">狀態</th><th class="table-cell">操作</th></tr></thead><tbody>';
    items.forEach(i => {
      h += '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="table-cell text-xs">#'+i.CN_ID+'</td><td class="table-cell text-sm">'+(i.A_USER||'-')+' <span class="text-xs text-gray-400">('+(i.A_UNIT||'')+')</span></td><td class="table-cell text-sm">'+(i.B_USER||'-')+' <span class="text-xs text-gray-400">('+(i.B_UNIT||'')+')</span></td><td class="table-cell">'+(i.FAC_NAME||'-')+'</td><td class="table-cell"><span class="'+(sC[i.CN_STATUS]||'')+'">'+((sL[i.CN_STATUS])||'')+'</span></td><td class="table-cell">';
      if (i.CN_STATUS <= 1) {
        h += '<button onclick="openChatRoom('+i.CN_ID+')" class="text-xs px-2 py-1 rounded bg-fju-blue/10 text-fju-blue mr-1"><i class="fas fa-comments mr-1"></i>聊天室</button>';
        h += '<button onclick="resolveConflict('+i.CN_ID+')" class="text-xs px-2 py-1 rounded bg-fju-green/10 text-fju-green">解決</button>';
      }
      h += '</td></tr>';
    });
    h += '</tbody></table></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}
async function resolveConflict(id) { if(!confirm('確定標記為已解決？')) return; await fetch(API+'/conflicts/'+id+'/resolve', { method:'PATCH', headers:{'Content-Type':'application/json'}, body:JSON.stringify({resolvedBy:currentUser?.id||1}) }); toast('衝突已解決','success'); loadConflicts(); }
async function openChatRoom(id) {
  const res = await fetch(API+'/conflicts/'+id).then(r=>r.json()).catch(()=>({data:{},messages:[]}));
  const cn = res.data || {}; const msgs = res.messages || [];
  let mHtml = msgs.map(m => '<div class="p-2 rounded-fju '+(m.CM_SENDER_ROLE===0?'bg-fju-blue/10 text-fju-blue ml-auto':'bg-gray-100 text-gray-700')+' max-w-[80%] text-sm"><div>'+(m.CM_CONTENT||'')+'</div><div class="text-xs text-gray-400 mt-1">'+(m.CM_SENT_AT||'')+'</div></div>').join('');
  if (!mHtml) mHtml = '<div class="text-center text-gray-400 text-sm py-4">尚無對話紀錄，開始協商吧！</div>';
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-comments mr-2 text-fju-yellow"></i>衝突協調聊天室 #'+id+'</h3><div class="text-xs text-gray-500 mb-3">'+(cn.A_USER||'')+' ('+(cn.A_UNIT||'')+') vs '+(cn.B_USER||'')+' ('+(cn.B_UNIT||'')+') — '+(cn.FAC_NAME||'')+'</div><div id="chat-messages" class="space-y-2 max-h-60 overflow-y-auto mb-3 p-3 bg-fju-bg rounded-fju">'+mHtml+'</div><div class="flex gap-2"><input id="chat-input" type="text" placeholder="輸入訊息..." class="flex-1 input-field" onkeypress="if(event.key===\\'Enter\\')sendChatMsg('+id+')"><button onclick="sendChatMsg('+id+')" class="btn-primary px-4">送出</button></div></div></div>');
}
async function sendChatMsg(id) {
  const input = document.getElementById('chat-input');
  const content = input.value.trim(); if (!content) return;
  input.value = '';
  await fetch(API+'/conflicts/'+id+'/messages', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({ senderRole:0, content }) });
  const msgs = document.getElementById('chat-messages');
  msgs.innerHTML += '<div class="p-2 rounded-fju bg-fju-blue/10 text-fju-blue ml-auto max-w-[80%] text-sm">'+content+'</div>';
  msgs.scrollTop = msgs.scrollHeight;
}

// ========== Units (Epic 1.5) ==========
async function loadUnits() {
  const res = await fetch(API+'/units').then(r=>r.json()).catch(()=>({data:[]})); const items = res.data || [];
  const typeL = { 0:'社團', 1:'學生自治組織', 2:'行政單位' };
  let h = '<div class="space-y-4 fade-in"><div class="grid md:grid-cols-3 gap-4">';
  items.forEach(u => {
    h += '<div class="card hover:border-fju-yellow/30 transition-all cursor-pointer" onclick="showUnitDetail('+u.UNIT_ID+')"><div class="flex items-center justify-between mb-2"><h3 class="font-bold text-fju-blue text-sm">'+(u.UNIT_NAME||'')+'</h3><span class="px-2 py-0.5 rounded-fju bg-fju-blue/10 text-fju-blue text-xs">'+(typeL[u.UNIT_TYPE]||'')+'</span></div><div class="text-xs text-gray-500"><i class="fas fa-user mr-1"></i>聯絡人：'+(u.CONTACT_NAME||'-')+'</div><div class="text-xs text-gray-400 mt-1"><i class="fas fa-flag mr-1"></i>違規點數：<span class="font-bold '+(u.UVP_POINT>=10?'text-fju-red':'')+'">'+(u.UVP_POINT!=null?u.UVP_POINT:0)+'</span></div></div>';
  });
  h += '</div></div>';
  document.getElementById('page-content').innerHTML = h;
}
async function showUnitDetail(id) {
  const res = await fetch(API+'/units/'+id).then(r=>r.json()).catch(()=>({data:{},members:[]}));
  const unit = res.data||{}; const members = res.members||[];
  document.body.insertAdjacentHTML('beforeend', '<div class="modal-overlay" onclick="if(event.target===this)closeModal()"><div class="modal-content"><h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-building mr-2 text-fju-yellow"></i>'+(unit.UNIT_NAME||'')+'</h3><div class="space-y-3"><div class="text-sm text-gray-600"><i class="fas fa-user mr-1"></i>聯絡人：'+(unit.CONTACT_NAME||'-')+'</div><h4 class="font-bold text-sm text-fju-blue mt-3">成員列表 ('+members.length+'人)</h4><div class="space-y-1 max-h-48 overflow-y-auto">'+members.map(m => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(m.USR_NAME||'')+'</span><span class="text-xs text-gray-400">'+(m.USR_EMAIL||'')+'</span></div>').join('')+'</div></div></div></div>');
}

// ========== Profile (Epic 1.3) ==========
async function loadProfile() {
  const userId = currentUser?.id || 1;
  const res = await fetch(API+'/users/'+userId+'/profile').then(r=>r.json()).catch(()=>({}));
  const u = res.user || currentUser;
  let h = '<div class="space-y-6 fade-in">';
  h += '<div class="card flex items-center gap-6"><div class="w-20 h-20 rounded-full bg-fju-yellow flex items-center justify-center text-4xl shadow-lg">'+(currentUser?.avatar||(u?.USR_NAME||u?.name||'?')[0])+'</div><div><h2 class="text-xl font-bold text-fju-blue">'+(u?.USR_NAME||u?.name||'')+'</h2><p class="text-sm text-gray-500">'+(u?.USR_EMAIL||u?.email||'')+'</p><div class="flex gap-2 mt-2"><span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs font-bold">'+(roleNames[u?.USR_ROLE||u?.role]||'')+'</span>';
  if (u?.USR_PHONE || u?.phone) h += '<span class="px-3 py-1 rounded-fju bg-gray-100 text-gray-600 text-xs"><i class="fas fa-phone mr-1"></i>'+(u?.USR_PHONE||u?.phone)+'</span>';
  h += '</div><button onclick="showAvatarSelection()" class="mt-2 text-xs px-3 py-1 rounded-fju bg-fju-yellow/20 text-fju-blue hover:bg-fju-yellow/30"><i class="fas fa-user-circle mr-1"></i>更換大頭貼</button></div></div>';
  const sections = [
    { title:'場地預約紀錄', icon:'fa-calendar-check', items:res.bookings||[], render:(b) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(b.FAC_NAME||'')+' ('+(b.VB_START_DATETIME||'').slice(0,10)+')</span><span class="text-xs '+((b.VB_STATUS===1)?'text-fju-green':'text-gray-400')+'">'+['待審核','已核准','已拒絕','已取消','已歸還','歸還異常'][b.VB_STATUS||0]+'</span></div>' },
    { title:'器材借用紀錄', icon:'fa-tools', items:res.loans||[], render:(l) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(l.EL_PURPOSE||'')+' ('+(l.EL_BORROW_START||'').slice(0,10)+')</span><span class="text-xs text-gray-400">'+['待領取','借用中','部分領取','部分歸還','已歸還','歸還異常'][l.EL_STATUS||0]+'</span></div>' },
    { title:'申訴紀錄', icon:'fa-gavel', items:res.appeals||[], render:(a) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(a.AC_REASON||'').substring(0,30)+'</span><span class="text-xs '+((a.AC_STATUS===1)?'text-fju-green':'text-gray-400')+'">'+['待審核','已核准','已駁回'][a.AC_STATUS||0]+'</span></div>' },
    { title:'報修紀錄', icon:'fa-wrench', items:res.repairs||[], render:(r) => '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span>'+(r.FAC_NAME||'')+': '+(r.RR_DESCRIPTION||'').substring(0,30)+'</span><span class="text-xs text-gray-400">'+['待處理','處理中','已完成'][r.RR_STATUS||0]+'</span></div>' },
  ];
  h += '<div class="grid md:grid-cols-2 gap-4">';
  sections.forEach(s => {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas '+s.icon+' mr-2 text-fju-yellow"></i>'+s.title+'</h3>';
    if (s.items.length === 0) h += '<p class="text-sm text-gray-400">暫無紀錄</p>';
    else h += '<div class="space-y-1">'+s.items.map(s.render).join('')+'</div>';
    h += '</div>';
  });
  h += '</div>';
  if (res.violations?.length > 0) {
    h += '<div class="card"><h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-exclamation-triangle mr-2 text-fju-yellow"></i>違規點數紀錄</h3><div class="space-y-1">';
    res.violations.forEach(v => { h += '<div class="p-2 rounded-fju bg-fju-bg text-sm flex justify-between"><span class="text-gray-600">'+(v.VPL_REASON||'')+'</span><span class="font-bold '+((v.VPL_DELTA>0)?'text-fju-red':'text-fju-green')+'">'+((v.VPL_DELTA>0)?'+':'')+v.VPL_DELTA+'</span></div>'; });
    h += '</div></div>';
  }
  h += '</div>';
  document.getElementById('page-content').innerHTML = h;
}

// ========== Init ==========
(function init() {
  const saved = localStorage.getItem('fjuUser');
  if (saved) { try { currentUser = JSON.parse(saved); enterApp(); } catch(e) { doLogout(); } }
})();
</script>
</body>
</html>`
}
