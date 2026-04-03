import { layout } from './layout'

export function login(): string {
  const body = `
  <!-- Background -->
  <div class="min-h-screen relative flex items-center justify-center overflow-hidden"
       style="background: linear-gradient(135deg, rgba(0,49,83,0.9), rgba(0,49,83,0.7)), 
              url('https://images.unsplash.com/photo-1562774053-701939374585?w=1920&q=80') center/cover;">
    
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute w-96 h-96 rounded-full bg-vatican-gold/5 -top-20 -left-20 animate-float"></div>
      <div class="absolute w-64 h-64 rounded-full bg-white/5 bottom-10 right-10 animate-float" style="animation-delay:1s"></div>
      <div class="absolute w-48 h-48 rounded-full bg-vatican-gold/5 top-1/2 left-1/3 animate-float" style="animation-delay:2s"></div>
    </div>

    <!-- Back Button -->
    <a href="/" class="absolute top-6 left-6 z-20 flex items-center gap-2 text-white/70 hover:text-white transition-colors">
      <i class="fas fa-arrow-left"></i>
      <span class="text-sm">返回首頁</span>
    </a>

    <!-- Language Switcher -->
    <div class="absolute top-6 right-6 z-20">
      <select class="glassmorphism-dark text-white/80 text-sm rounded-lg px-3 py-2 border-0 outline-none cursor-pointer">
        <option value="zh-TW">繁體中文</option>
        <option value="zh-CN">简体中文</option>
        <option value="en">English</option>
        <option value="ja">日本語</option>
        <option value="ko">한국어</option>
      </select>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md mx-4">
      <!-- Logo & Title -->
      <div class="text-center mb-8" id="login-header">
        <div class="w-20 h-20 mx-auto rounded-2xl gold-gradient flex items-center justify-center shadow-2xl mb-4 animate-pulse-gold">
          <i class="fas fa-university text-mary-blue text-3xl"></i>
        </div>
        <h1 class="text-3xl font-black text-white mb-2">FJU Smart Hub</h1>
        <p class="text-white/60 text-sm">輔仁大學智慧校園管理平台</p>
      </div>

      <!-- Login Box - Glassmorphism -->
      <div class="glassmorphism rounded-3xl p-8 shadow-2xl" id="login-box">
        <!-- Mascot -->
        <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-vatican-gold flex items-center justify-center shadow-lg transform rotate-12 hover:rotate-0 transition-transform">
          <span class="text-2xl">🐕</span>
        </div>

        <h2 class="text-xl font-bold text-mary-blue mb-6 text-center">歡迎登入</h2>

        <!-- Google OAuth Button -->
        <button onclick="handleGoogleLogin()" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-white rounded-xl border-2 border-gray-200 hover:border-mary-blue hover:shadow-lg transition-all mb-6 group">
          <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
          <span class="text-gray-700 font-medium group-hover:text-mary-blue transition-colors">使用 FJU Google 帳號登入</span>
        </button>

        <div class="flex items-center gap-4 mb-6">
          <div class="flex-1 border-t border-gray-200"></div>
          <span class="text-gray-400 text-xs">或使用帳號密碼</span>
          <div class="flex-1 border-t border-gray-200"></div>
        </div>

        <!-- Login Form -->
        <form id="login-form" onsubmit="handleLogin(event)">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label>
            <div class="relative">
              <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input type="text" id="username" placeholder="例：410XXXXXX" 
                     class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 focus:border-mary-blue focus:ring-2 focus:ring-mary-blue/20 outline-none transition-all text-sm">
            </div>
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-600 mb-1">密碼</label>
            <div class="relative">
              <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input type="password" id="password" placeholder="請輸入密碼" 
                     class="w-full pl-11 pr-12 py-3 rounded-xl border border-gray-200 focus:border-mary-blue focus:ring-2 focus:ring-mary-blue/20 outline-none transition-all text-sm">
              <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                <i class="fas fa-eye" id="pw-toggle"></i>
              </button>
            </div>
          </div>

          <!-- 2FA Code -->
          <div class="mb-4" id="2fa-section" style="display:none;">
            <label class="block text-sm font-medium text-gray-600 mb-1">
              <i class="fas fa-shield-alt mr-1 text-vatican-gold"></i>雙因子驗證碼
            </label>
            <div class="flex gap-2">
              <input type="text" maxlength="6" placeholder="000000" 
                     class="flex-1 text-center tracking-[0.5em] px-4 py-3 rounded-xl border border-gray-200 focus:border-vatican-gold focus:ring-2 focus:ring-vatican-gold/20 outline-none transition-all text-lg font-mono">
              <button type="button" class="px-4 py-3 rounded-xl bg-gray-100 text-gray-500 text-xs hover:bg-gray-200 transition-colors">
                <i class="fas fa-paper-plane"></i>
              </button>
            </div>
          </div>

          <!-- Cloudflare Turnstile Placeholder -->
          <div class="mb-4 rounded-xl border border-gray-200 p-3 bg-gray-50 flex items-center gap-3">
            <div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center cursor-pointer hover:border-harvest-green transition-colors" onclick="this.innerHTML='<i class=\\'fas fa-check text-harvest-green text-xs\\'></i>'">
            </div>
            <span class="text-sm text-gray-500">我不是機器人</span>
            <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="cf" class="w-6 h-6 ml-auto opacity-50">
          </div>

          <button type="submit" class="w-full btn-gold py-3.5 rounded-xl text-base mb-4">
            <i class="fas fa-sign-in-alt mr-2"></i>登入
          </button>

          <div class="flex justify-between text-xs text-gray-400">
            <a href="#" class="hover:text-mary-blue transition-colors">忘記密碼？</a>
            <a href="#" class="hover:text-vatican-gold transition-colors" onclick="document.getElementById('2fa-section').style.display='block'">啟用 2FA</a>
          </div>
        </form>

        <!-- Role Quick Demo -->
        <div class="mt-6 pt-6 border-t border-gray-100">
          <p class="text-xs text-gray-400 text-center mb-3">快速體驗不同角色</p>
          <div class="grid grid-cols-5 gap-2">
            <a href="/dashboard?role=admin" class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-mary-blue/5 transition-colors group">
              <div class="w-8 h-8 rounded-lg blue-gradient flex items-center justify-center"><i class="fas fa-user-tie text-white text-xs"></i></div>
              <span class="text-[10px] text-gray-400 group-hover:text-mary-blue">課指組</span>
            </a>
            <a href="/dashboard?role=officer" class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-mary-blue/5 transition-colors group">
              <div class="w-8 h-8 rounded-lg gold-gradient flex items-center justify-center"><i class="fas fa-user-shield text-mary-blue text-xs"></i></div>
              <span class="text-[10px] text-gray-400 group-hover:text-mary-blue">社團幹部</span>
            </a>
            <a href="/dashboard?role=professor" class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-mary-blue/5 transition-colors group">
              <div class="w-8 h-8 rounded-lg blue-gradient flex items-center justify-center"><i class="fas fa-chalkboard-teacher text-white text-xs"></i></div>
              <span class="text-[10px] text-gray-400 group-hover:text-mary-blue">指導教授</span>
            </a>
            <a href="/dashboard?role=student" class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-mary-blue/5 transition-colors group">
              <div class="w-8 h-8 rounded-lg gold-gradient flex items-center justify-center"><i class="fas fa-user-graduate text-mary-blue text-xs"></i></div>
              <span class="text-[10px] text-gray-400 group-hover:text-mary-blue">一般學生</span>
            </a>
            <a href="/dashboard?role=it" class="flex flex-col items-center gap-1 p-2 rounded-xl hover:bg-mary-blue/5 transition-colors group">
              <div class="w-8 h-8 rounded-lg blue-gradient flex items-center justify-center"><i class="fas fa-server text-white text-xs"></i></div>
              <span class="text-[10px] text-gray-400 group-hover:text-mary-blue">資訊中心</span>
            </a>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <p class="text-center text-white/40 text-xs mt-6">
        僅限 @cloud.fju.edu.tw 帳號登入 | 
        <i class="fas fa-shield-alt mr-1"></i>Cloudflare Protected
      </p>
    </div>
  </div>

  <script>
    // GSAP entrance animations
    gsap.from('#login-header', { opacity: 0, y: -30, duration: 0.8, ease: 'power3.out' });
    gsap.from('#login-box', { opacity: 0, y: 30, scale: 0.95, duration: 0.8, delay: 0.3, ease: 'power3.out' });

    function togglePassword() {
      const pw = document.getElementById('password');
      const icon = document.getElementById('pw-toggle');
      if (pw.type === 'password') { pw.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash'); }
      else { pw.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye'); }
    }

    function handleGoogleLogin() {
      // In production: redirect to Google OAuth with hd=cloud.fju.edu.tw
      alert('將導向 Google OAuth 登入\\n(限 @cloud.fju.edu.tw 帳號)');
      window.location.href = '/dashboard?role=student';
    }

    function handleLogin(e) {
      e.preventDefault();
      const username = document.getElementById('username').value;
      if (!username) { alert('請輸入學號或員工編號'); return; }
      // Demo: route to dashboard
      window.location.href = '/dashboard?role=student';
    }
  </script>
  `

  return layout('登入', body)
}
