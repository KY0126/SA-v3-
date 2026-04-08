// ============================================================
// FJU Smart Hub - Layout System
// Design specs: rounded 12-15px, 微軟正黑體
// Colors: 60-30-10 rule → Main #FFFFFF, Secondary #003153 (Virgin Mary Blue), Accent #DAA520/#FDB913 (Vatican Gold)
// ============================================================

export function layout(title: string, body: string, extraHead: string = ''): string {
  return `<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>${title} - FJU Smart Hub</title>
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            'fju-blue': '#003153',
            'fju-blue-light': '#004070',
            'fju-dark': '#333333',
            'fju-bg': '#F4F6F8',
            'fju-yellow': '#DAA520',
            'fju-yellow-light': '#FDB913',
            'fju-green': '#008000',
            'fju-red': '#FF0000',
            'fju-gold': '#DAA520',
            'fju-sidebar': '#333333',
            'fju-card': '#FFFFFF',
          },
          fontFamily: {
            sans: ['"Microsoft JhengHei"', '"微軟正黑體"', 'system-ui', 'sans-serif'],
          },
          borderRadius: {
            'fju': '12px',
            'fju-lg': '15px',
          }
        }
      }
    }
  </script>
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <!-- evo-calendar -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/css/evo-calendar.min.css" />
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/evo-calendar@1.1.3/evo-calendar/js/evo-calendar.min.js"></script>
  <style>
    * { font-family: 'Microsoft JhengHei', '微軟正黑體', system-ui, sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
    
    /* ===== GLASSMORPHISM ===== */
    .glassmorphism {
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid rgba(255,255,255,0.3);
    }
    .glassmorphism-dark {
      background: rgba(0,43,91,0.85);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);
      border: 1px solid rgba(255,255,255,0.15);
    }
    
    /* ===== BUTTONS ===== */
    .btn-yellow {
      background: #DAA520;
      color: #003153;
      font-weight: 700;
      border-radius: 12px;
      transition: all 0.3s;
      border: none;
      cursor: pointer;
    }
    .btn-yellow:hover {
      background: #FDB913;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(218,165,32,0.4);
    }
    .btn-blue {
      background: #003153;
      color: #FFFFFF;
      font-weight: 700;
      border-radius: 12px;
      transition: all 0.3s;
      border: none;
      cursor: pointer;
    }
    .btn-blue:hover {
      background: #004070;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0,49,83,0.4);
    }
    
    /* ===== CARD HOVER ===== */
    .card-hover {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 40px rgba(0,49,83,0.12);
    }
    
    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    
    /* ===== ANIMATIONS ===== */
    .animate-float {
      animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    .animate-pulse-yellow {
      animation: pulseYellow 2s ease-in-out infinite;
    }
    @keyframes pulseYellow {
      0%, 100% { box-shadow: 0 0 0 0 rgba(218,165,32,0.4); }
      50% { box-shadow: 0 0 0 15px rgba(218,165,32,0); }
    }
    
    /* ===== LEAFLET POPUP CUSTOM ===== */
    .fju-popup .leaflet-popup-content-wrapper {
      border-radius: 12px;
      padding: 0;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }
    .fju-popup .leaflet-popup-content {
      margin: 0;
      min-width: 220px;
    }
    .fju-popup .leaflet-popup-tip {
      background: #003153;
    }
    .popup-header {
      background: #003153;
      color: white;
      padding: 10px 14px;
      font-weight: 700;
      font-size: 14px;
    }
    .popup-body {
      padding: 12px 14px;
      font-size: 13px;
      color: #333;
    }
    .popup-body .popup-row {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 6px;
    }
    .popup-body .popup-row i {
      color: #DAA520;
      width: 16px;
      text-align: center;
    }
    .popup-btn {
      display: inline-block;
      margin-top: 8px;
      background: #DAA520;
      color: #003153;
      padding: 6px 16px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 12px;
      cursor: pointer;
      border: none;
      text-decoration: none;
    }
    .popup-btn:hover {
      background: #FDB913;
    }

    /* ===== EVO CALENDAR OVERRIDES ===== */
    .evo-calendar {
      border-radius: 12px !important;
      overflow: hidden;
      box-shadow: none !important;
      border: 1px solid #e5e7eb !important;
    }
    .calendar-sidebar { background: #003153 !important; }
    .calendar-sidebar > .month-list > li.active-month { background: #DAA520 !important; color: #003153 !important; }
    .calendar-sidebar > span#sidebarToggler { background: #003153 !important; }
    .calendar-day > .day.calendar-today { background: #DAA520 !important; color: #003153 !important; }
    .calendar-day > .day.calendar-active { border-color: #003153 !important; }
    .event-indicator > .type-bullet > div { background: #DAA520 !important; }
    th[colspan] { background: #003153 !important; color: white !important; }
    
    /* ===== CHATBOT ===== */
    .chatbot-fab {
      position: fixed;
      bottom: 24px;
      right: 24px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: #DAA520;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(218,165,32,0.4);
      z-index: 9999;
      transition: transform 0.3s;
      border: 3px solid white;
    }
    .chatbot-fab:hover { transform: scale(1.1); }
    
    .chatbot-panel {
      position: fixed;
      bottom: 96px;
      right: 24px;
      width: 360px;
      max-height: 480px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 8px 40px rgba(0,0,0,0.2);
      z-index: 9998;
      overflow: hidden;
      display: none;
      flex-direction: column;
    }
    .chatbot-panel.active { display: flex; }
    .chatbot-header {
      background: #003153;
      color: white;
      padding: 14px 16px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .chatbot-messages {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      background: #F4F6F8;
      max-height: 300px;
    }
    .chatbot-input-area {
      padding: 12px;
      border-top: 1px solid #e5e7eb;
      display: flex;
      gap: 8px;
    }
  </style>
  ${extraHead}
</head>
<body>
${body}
</body>
</html>`
}

/* ===== ROLE DEFINITIONS ===== */
const roleNames: Record<string, string> = {
  admin: '課指組/處室',
  officer: '社團幹部',
  professor: '指導教授',
  student: '一般學生',
  it: '資訊中心',
}

const roleIcons: Record<string, string> = {
  admin: 'fas fa-user-tie',
  officer: 'fas fa-user-shield',
  professor: 'fas fa-chalkboard-teacher',
  student: 'fas fa-user-graduate',
  it: 'fas fa-server',
}

/* ===== SIDEBAR MENUS - NEW STRUCTURE PER SPEC ===== */
export function getSidebarMenus(role: string, activePage: string = 'dashboard'): string {
  // Universal sidebar structure per spec
  const sections = [
    {
      title: '主要功能',
      items: [
        { id: 'dashboard', label: 'Dashboard', icon: 'fas fa-tachometer-alt', href: `/dashboard?role=${role}` },
        { id: 'campus-map', label: '校園分區地圖', icon: 'fas fa-map-marked-alt', href: `/campus-map?role=${role}` },
        { id: 'venue-booking', label: '場地預約', icon: 'fas fa-map-marker-alt', href: `/module/venue-booking?role=${role}` },
        { id: 'equipment', label: '設備借用', icon: 'fas fa-boxes-stacked', href: `/module/equipment?role=${role}` },
        { id: 'calendar', label: '行事曆', icon: 'fas fa-calendar-alt', href: `/module/calendar?role=${role}` },
      ]
    },
    {
      title: '社群與社團',
      items: [
        { id: 'club-info', label: '社團資訊', icon: 'fas fa-users', href: `/module/club-info?role=${role}` },
        { id: 'activity-wall', label: '活動牆', icon: 'fas fa-newspaper', href: `/module/activity-wall?role=${role}` },
      ]
    },
    {
      title: 'AI 核心專區',
      items: [
        { id: 'ai-overview', label: 'AI 資訊概覽', icon: 'fas fa-brain', href: `/module/ai-overview?role=${role}` },
        { id: 'ai-guide', label: 'AI 導覽助理', icon: 'fas fa-robot', href: `/module/ai-guide?role=${role}` },
        { id: 'rag-search', label: '法規查詢 (RAG)', icon: 'fas fa-gavel', href: `/module/rag-search?role=${role}` },
      ]
    },
    {
      title: '管理與報表',
      items: [
        { id: 'repair', label: '報修管理', icon: 'fas fa-wrench', href: `/module/repair?role=${role}` },
        { id: 'appeal', label: '申訴記錄', icon: 'fas fa-comments', href: `/module/appeal?role=${role}` },
        { id: 'reports', label: '統計報表', icon: 'fas fa-chart-bar', href: `/module/reports?role=${role}` },
      ]
    }
  ]

  return sections.map(section => `
    <div class="mt-4 first:mt-0">
      <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">${section.title}</div>
      ${section.items.map(item => `
        <a href="${item.href}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-fju text-sm transition-all duration-200 ${item.id === activePage ? 'bg-fju-yellow text-fju-blue font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white'}">
          <i class="${item.icon} w-5 text-center text-sm ${item.id === activePage ? 'text-fju-blue' : ''}"></i>
          <span>${item.label}</span>
        </a>
      `).join('')}
    </div>
  `).join('')
}

/* ===== DOUBLE-LAYER HEADER ===== */
function doubleHeader(role: string): string {
  return `
  <!-- TOP HEADER (White) -->
  <div class="bg-white border-b border-gray-200 px-6 py-2">
    <div class="flex items-center justify-between">
      <!-- Left: Logo + Name -->
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center shadow">
          <i class="fas fa-university text-white text-lg"></i>
        </div>
        <div>
          <div class="text-fju-blue font-bold text-base leading-tight">天主教輔仁大學</div>
          <div class="text-fju-green text-xs font-medium">學務處 課外活動指導組</div>
        </div>
      </div>
      <!-- Right: Links -->
      <div class="flex items-center gap-4 text-sm">
        <a href="/" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">Home</a>
        <a href="#" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">輔仁大學</a>
        <a href="#" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">ENGLISH</a>
        <a href="#" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">網站地圖</a>
        <!-- Role Switch -->
        <div class="flex gap-1 ml-2 pl-3 border-l border-gray-200">
          ${Object.keys(roleNames).map(r => `
            <a href="/dashboard?role=${r}" class="w-7 h-7 rounded-lg flex items-center justify-center text-xs transition-all ${r === role ? 'bg-fju-yellow text-fju-blue' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'}" title="${roleNames[r]}">
              <i class="${roleIcons[r]}"></i>
            </a>
          `).join('')}
        </div>
      </div>
    </div>
  </div>
  <!-- BOTTOM HEADER (Deep Blue) -->
  <div class="bg-fju-blue px-6 py-0">
    <div class="flex items-center justify-between">
      <nav class="flex items-center gap-1">
        ${[
          { label: '認識課指組', href: '#', icon: 'fas fa-info-circle' },
          { label: '學會．社團', href: '/module/club-info?role=' + role, icon: 'fas fa-users' },
          { label: '場地/器材借用', href: '/module/venue-booking?role=' + role, icon: 'fas fa-key' },
          { label: '重要訊息', href: '#', icon: 'fas fa-bullhorn' },
          { label: '表單下載', href: '#', icon: 'fas fa-download' },
          { label: '常見問題', href: '#', icon: 'fas fa-question-circle' },
        ].map(item => `
          <a href="${item.href}" class="flex items-center gap-1.5 px-4 py-3 text-white/80 hover:text-fju-yellow hover:bg-white/10 transition-all text-sm font-medium rounded-t-lg">
            <i class="${item.icon} text-xs"></i>
            <span>${item.label}</span>
          </a>
        `).join('')}
      </nav>
      <!-- Search + User -->
      <div class="flex items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i>
          <input type="text" placeholder="搜尋活動、場地、社團..." class="bg-white/10 border border-white/15 rounded-fju pl-9 pr-4 py-1.5 text-white text-xs outline-none w-52 focus:w-72 focus:bg-white/20 focus:border-fju-yellow/50 transition-all" />
        </div>
        <button class="relative w-8 h-8 rounded-fju bg-white/10 flex items-center justify-center text-white/70 hover:bg-white/15 hover:text-white transition-all">
          <i class="fas fa-bell text-sm"></i>
          <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-fju-red text-white text-[9px] font-bold flex items-center justify-center">3</span>
        </button>
        <div class="flex items-center gap-2 pl-3 border-l border-white/15">
          <div class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue text-xs font-bold">
            <i class="${roleIcons[role]}"></i>
          </div>
          <div class="hidden lg:block">
            <div class="text-white text-xs font-medium leading-tight">Demo User</div>
            <div class="text-white/40 text-[10px]">${roleNames[role]}</div>
          </div>
          <a href="/login" class="text-white/40 hover:text-fju-red transition-colors ml-1" title="登出">
            <i class="fas fa-sign-out-alt text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </div>`
}

/* ===== FOOTER (Deep Blue) ===== */
function footerBar(): string {
  return `
  <footer class="bg-fju-blue text-white">
    <div class="px-6 py-6">
      <div class="flex flex-wrap items-start justify-between gap-6">
        <!-- Left: Address -->
        <div class="text-sm">
          <div class="font-bold mb-1"><i class="fas fa-map-marker-alt mr-2 text-fju-yellow"></i>校址</div>
          <div class="text-white/60 text-xs leading-relaxed">24205 新北市新莊區中正路510號<br>No.510, Zhongzheng Rd., Xinzhuang Dist., New Taipei City 24205, Taiwan</div>
        </div>
        <!-- Center: Social -->
        <div class="text-sm text-center">
          <div class="font-bold mb-1"><i class="fab fa-facebook mr-2 text-fju-yellow"></i>粉絲專頁</div>
          <div class="flex gap-3 justify-center mt-2">
            <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-facebook-f text-white text-sm"></i></a>
            <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-instagram text-white text-sm"></i></a>
            <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-line text-white text-sm"></i></a>
          </div>
        </div>
        <!-- Right: Contact -->
        <div class="text-sm text-right">
          <div class="font-bold mb-1"><i class="fas fa-phone mr-2 text-fju-yellow"></i>櫃台資訊</div>
          <div class="text-white/60 text-xs leading-relaxed">電話：(02) 2905-2237<br>維護信箱：activity@mail.fju.edu.tw</div>
        </div>
      </div>
    </div>
    <div class="border-t border-white/10 px-6 py-3 text-center text-white/40 text-xs">
      天主教輔仁大學 &copy; 2014-2024 版權所有 | Powered by Vue 3 + Laravel + Dify AI
    </div>
  </footer>`
}

/* ===== AI CHATBOT WIDGET ===== */
function chatbotWidget(): string {
  return `
  <!-- Chatbot FAB -->
  <div class="chatbot-fab" onclick="toggleChatbot()" title="AI 導覽助理 - 輔寶">
    🐕
  </div>
  <!-- Chatbot Panel -->
  <div id="chatbot-panel" class="chatbot-panel">
    <div class="chatbot-header">
      <div class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-lg">🐕</div>
      <div>
        <div class="font-bold text-sm">輔寶 AI 助理</div>
        <div class="text-white/50 text-[10px]">FJU Smart Hub Navigator</div>
      </div>
      <button onclick="toggleChatbot()" class="ml-auto text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
      <div class="flex gap-2 mb-3">
        <div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div>
        <div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-600 shadow-sm max-w-[85%]">
          汪！你好～我是輔寶 🐾<br><br>
          今天想要問輔寶甚麼事呢？我可以幫你：<br>
          📍 查詢場地資訊<br>
          📅 預約場地/設備<br>
          📋 查詢法規與表單<br>
          🗺️ 校園無障礙導覽
        </div>
      </div>
    </div>
    <div class="chatbot-input-area">
      <input type="text" id="chatbot-input" placeholder="輸入你的問題..." class="flex-1 px-3 py-2 rounded-fju bg-gray-50 border border-gray-200 text-xs outline-none focus:border-fju-blue" onkeypress="if(event.key==='Enter')sendChatMessage()" />
      <button onclick="sendChatMessage()" class="w-8 h-8 rounded-fju btn-yellow flex items-center justify-center"><i class="fas fa-paper-plane text-xs"></i></button>
    </div>
  </div>
  <script>
    function toggleChatbot() {
      document.getElementById('chatbot-panel').classList.toggle('active');
    }
    function sendChatMessage() {
      const input = document.getElementById('chatbot-input');
      const msg = input.value.trim();
      if (!msg) return;
      const container = document.getElementById('chatbot-messages');
      // User message
      container.innerHTML += '<div class="flex gap-2 mb-3 justify-end"><div class="bg-fju-blue text-white rounded-fju rounded-tr-none p-3 text-xs max-w-[85%]">' + msg + '</div></div>';
      input.value = '';
      // Bot reply
      setTimeout(() => {
        const replies = [
          '汪！讓我幫你查查看～ 📝',
          '好的！根據校園法規，這個場地需要提前3天申請喔 🏫',
          '淨心堂目前可以預約，要幫你查詢空閒時段嗎？',
          '你可以到「場地預約」頁面進行線上申請，AI 會自動幫你檢查文件是否完整 ✅',
          '校園無障礙設施包括坡道、電梯和無障礙廁所，分布在醫學大樓、理工綜合教室等處 ♿',
        ];
        const reply = replies[Math.floor(Math.random() * replies.length)];
        container.innerHTML += '<div class="flex gap-2 mb-3"><div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div><div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-600 shadow-sm max-w-[85%]">' + reply + '</div></div>';
        container.scrollTop = container.scrollHeight;
      }, 800);
      container.scrollTop = container.scrollHeight;
    }
  </script>`
}

/* ===== APP SHELL (Post-Login Layout) ===== */
export function appShell(role: string, activePage: string, title: string, bodyContent: string): string {
  if (!roleNames[role]) role = 'student'
  
  const shell = `
  <div class="flex flex-col min-h-screen">
    <!-- DOUBLE HEADER -->
    ${doubleHeader(role)}

    <!-- MIDDLE: SIDEBAR + MAIN -->
    <div class="flex flex-1 overflow-hidden" style="height: calc(100vh - 160px);">
      <!-- SIDEBAR (Dark Gray, Sticky Left) -->
      <aside class="w-64 min-w-[256px] bg-fju-sidebar flex flex-col overflow-y-auto" style="position: sticky; top: 0;">
        <!-- Credit Dashboard -->
        <div class="p-4">
          <div class="rounded-fju-lg bg-gradient-to-br from-fju-blue to-fju-blue-light p-4 text-white">
            <div class="flex items-center justify-between mb-2">
              <span class="text-[10px] text-white/60 font-medium">信用積分</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-green/20 text-fju-green font-medium">
                <i class="fas fa-check-circle mr-0.5"></i>正常
              </span>
            </div>
            <div class="text-2xl font-black mb-2">85<span class="text-xs font-normal text-white/40"> / 100</span></div>
            <div class="w-full bg-white/15 rounded-full h-1.5">
              <div class="bg-fju-yellow rounded-full h-1.5 transition-all duration-1000" style="width: 85%"></div>
            </div>
            <div class="text-[9px] text-white/40 mt-1">低於 60 分將被強制登出</div>
          </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 pb-4">
          ${getSidebarMenus(role, activePage)}
        </nav>

        <!-- System Status -->
        <div class="p-3 mx-3 mb-3 rounded-fju bg-white/5">
          <div class="flex items-center gap-2 mb-2">
            <i class="fas fa-info-circle text-fju-yellow text-xs"></i>
            <span class="text-[10px] font-bold text-gray-400">系統狀態</span>
          </div>
          <div class="grid grid-cols-2 gap-1.5 text-[10px]">
            <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">DB 正常</span></div>
            <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">AI 就緒</span></div>
            <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">快取啟用</span></div>
            <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">WAF 保護</span></div>
          </div>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main class="flex-1 bg-fju-bg overflow-y-auto p-6">
        ${bodyContent}
      </main>
    </div>

    <!-- FOOTER -->
    ${footerBar()}
  </div>

  <!-- AI CHATBOT -->
  ${chatbotWidget()}
  `

  return layout(title, shell)
}

export { roleNames, roleIcons }
