import { appShell } from './layout'

/* ===================================================================
   FJU Smart Hub - Module Pages
   All sidebar menu items implemented:
   主要功能: venue-booking, equipment, calendar
   社群與社團: club-info, activity-wall
   AI核心專區: ai-overview, ai-guide, rag-search
   管理與報表: repair, appeal, reports
   =================================================================== */

const modules: Record<string, (role: string) => string> = {

  // ===== 場地預約 =====
  'venue-booking': (role) => appShell(role, 'venue-booking', '場地預約', `
    <div class="space-y-6">
      <!-- 3-Stage Flow -->
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h2 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-chess mr-2 text-fju-yellow"></i>三階段資源調度系統</h2>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="p-4 rounded-fju bg-fju-blue/5 border border-fju-blue/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-fju-blue text-sm">志願序配對</span></div>
            <p class="text-xs text-gray-500">Level 1-3 優先權自動配對</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>L1: 校方/處室 (最高優先)</div>
              <div>L2: 社團/自治組織</div>
              <div>L3: 一般社團/小組</div>
            </div>
          </div>
          <div class="p-4 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">2</div><span class="font-bold text-fju-blue text-sm">自主協商</span></div>
            <p class="text-xs text-gray-500">3/6 分鐘規則 (Redis + GPT-4)</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>3分鐘: GPT-4 介入建議</div>
              <div>6分鐘: 強制關閉、扣10分</div>
              <div>LINE 邀請 + 私下調解</div>
            </div>
          </div>
          <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-green flex items-center justify-center text-white text-sm font-bold">3</div><span class="font-bold text-fju-blue text-sm">官方核准</span></div>
            <p class="text-xs text-gray-500">RAG 法規比對 + Gatekeeping</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>比對行政流程法規</div>
              <div>產出 PDF + TOTP QR</div>
              <div>活動核准號 → 場地解鎖</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats -->
      <div class="grid md:grid-cols-4 gap-4">
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-blue">50</div><div class="text-xs text-gray-400">可用場地</div>
        </div>
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-yellow">87%</div><div class="text-xs text-gray-400">使用率</div>
        </div>
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-green">142</div><div class="text-xs text-gray-400">本月預約</div>
        </div>
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-red">8</div><div class="text-xs text-gray-400">衝突待協商</div>
        </div>
      </div>

      <!-- Venue List -->
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>場地清單</h3>
          <button class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約</button>
        </div>
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">
            <th class="p-4">場地</th><th class="p-4">位置</th><th class="p-4">容量</th><th class="p-4">狀態</th><th class="p-4">操作</th>
          </tr></thead>
          <tbody>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">中美堂</td><td class="p-4 text-gray-500">校園中心</td><td class="p-4">500人</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">可預約</span></td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">預約</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">活動中心</td><td class="p-4 text-gray-500">學生中心</td><td class="p-4">200人</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">可預約</span></td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">預約</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">SF 134</td><td class="p-4 text-gray-500">理工大樓</td><td class="p-4">80人</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">可預約</span></td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">預約</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">草地廣場</td><td class="p-4 text-gray-500">校園東側</td><td class="p-4">300人</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-yellow/20 text-fju-yellow text-xs">維護中</span></td><td class="p-4"><span class="text-xs text-gray-400">-</span></td></tr>
          </tbody>
        </table>
      </div>

      <!-- Heatmap Charts -->
      <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-fire mr-2 text-fju-yellow"></i>場地使用熱力圖</h3>
          <canvas id="venue-heatmap" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地周轉排行</h3>
          <canvas id="venue-ranking" height="200"></canvas>
        </div>
      </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      new Chart(document.getElementById('venue-heatmap'), {
        type: 'bar',
        data: { labels: ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00'],
          datasets: [{ label: '使用數', data: [8,25,38,42,15,35,45,40,30,22,12,5], backgroundColor: function(ctx) { const v = ctx.raw; return v > 35 ? '#FF0000' : v > 20 ? '#FFB800' : '#008000'; }}]
        }, options: { responsive: true, plugins: { legend: { display: false }}}
      });
      new Chart(document.getElementById('venue-ranking'), {
        type: 'bar',
        data: { labels: ['中美堂','活動中心','SF 134','草地廣場','會議室A','體育館'],
          datasets: [{ label: '本月使用次數', data: [45,38,35,28,22,18], backgroundColor: '#002B5B' }]
        }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }}}
      });
    });
    </script>
  `),

  // ===== 設備借用 =====
  'equipment': (role) => appShell(role, 'equipment', '設備借用', `
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex gap-2">
          <button class="px-4 py-2 rounded-fju bg-fju-blue text-white text-sm">全部器材</button>
          <button class="px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm hover:bg-gray-200">已借出</button>
          <button class="px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm hover:bg-gray-200">逾期</button>
        </div>
        <button class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-2"></i>新增器材</button>
      </div>
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">
            <th class="p-4">編號</th><th class="p-4">名稱</th><th class="p-4">狀態</th><th class="p-4">借用人</th><th class="p-4">歸還日</th><th class="p-4">操作</th>
          </tr></thead>
          <tbody>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-001</td><td class="p-4 font-medium text-fju-blue">Canon EOS R6</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-red/10 text-fju-red text-xs">已借出</span></td><td class="p-4">陳同學</td><td class="p-4 text-fju-red font-medium">04/02 ⚠️</td><td class="p-4"><button class="text-xs text-fju-blue hover:underline">提醒</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-002</td><td class="p-4 font-medium text-fju-blue">Sony A7III</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">可借用</span></td><td class="p-4 text-gray-400">-</td><td class="p-4 text-gray-400">-</td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">借出</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-003</td><td class="p-4 font-medium text-fju-blue">投影機 EPSON EB-X51</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-yellow/20 text-fju-yellow text-xs">維修中</span></td><td class="p-4 text-gray-400">-</td><td class="p-4 text-gray-400">-</td><td class="p-4"><span class="text-xs text-gray-400">-</span></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-004</td><td class="p-4 font-medium text-fju-blue">無線麥克風組</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">可借用</span></td><td class="p-4 text-gray-400">-</td><td class="p-4 text-gray-400">-</td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">借出</button></td></tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-bell mr-2 text-fju-yellow"></i>到期提醒設定 (LINE / SMS)</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50"><input type="checkbox" checked class="accent-fju-green"><span class="text-sm text-gray-600">歸還前 1 天 LINE 提醒</span></div>
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50"><input type="checkbox" checked class="accent-fju-green"><span class="text-sm text-gray-600">逾期 SMS 通知</span></div>
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50"><input type="checkbox" class="accent-fju-green"><span class="text-sm text-gray-600">自動扣除信用積分</span></div>
        </div>
      </div>
    </div>
  `),

  // ===== 行事曆 (evo-calendar) =====
  'calendar': (role) => appShell(role, 'calendar', '行事曆', `
    <div class="space-y-6">
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-alt mr-2 text-fju-yellow"></i>智慧行事曆</h2>
          <div class="flex gap-2">
            <span class="text-xs px-3 py-1 rounded-full bg-fju-blue/10 text-fju-blue"><i class="fas fa-lock mr-1"></i>需登入使用</span>
            <span class="text-xs px-3 py-1 rounded-full bg-fju-green/10 text-fju-green"><i class="fas fa-check mr-1"></i>已登入</span>
          </div>
        </div>
        <div id="evo-calendar"></div>
      </div>
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-info-circle mr-2 text-fju-yellow"></i>使用說明</h3>
        <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-500">
          <div class="flex items-start gap-2"><i class="fas fa-check-circle text-fju-green mt-0.5"></i><span>行事曆需要登入後才能查看預約內容</span></div>
          <div class="flex items-start gap-2"><i class="fas fa-check-circle text-fju-green mt-0.5"></i><span>系統會自動進行前處理檢查 (pre-process check)</span></div>
          <div class="flex items-start gap-2"><i class="fas fa-check-circle text-fju-green mt-0.5"></i><span>點擊日期可查看當天活動與預約</span></div>
          <div class="flex items-start gap-2"><i class="fas fa-check-circle text-fju-green mt-0.5"></i><span>衝突時段會以紅色標示</span></div>
        </div>
      </div>
    </div>
    <script>
    $(document).ready(function() {
      // Pre-process check simulation
      const isLoggedIn = true; // Simulated login check
      if (!isLoggedIn) {
        alert('請先登入才能使用行事曆功能');
        window.location.href = '/login';
        return;
      }
      
      $('#evo-calendar').evoCalendar({
        theme: 'Royal Navy',
        language: 'zh',
        format: 'yyyy-mm-dd',
        titleFormat: 'yyyy MM',
        eventHeaderFormat: 'MM dd, yyyy',
        calendarEvents: [
          { id: 1, name: '社團評鑑會議', date: '2026-04-05', type: 'event', color: '#002B5B', description: '地點：活動中心 / 14:00-16:00' },
          { id: 2, name: '金乐獎初審', date: '2026-04-08', type: 'event', color: '#FFB800', description: '地點：中美堂 / 10:00-12:00' },
          { id: 3, name: '吉他社成果展', date: '2026-04-15', type: 'event', color: '#002B5B', description: '地點：中美堂 / 14:00-17:00' },
          { id: 4, name: '攝影社春季外拍', date: '2026-04-20', type: 'event', color: '#FFB800', description: '地點：校園各處 / 09:00-17:00' },
          { id: 5, name: '程式設計工作坊', date: '2026-04-25', type: 'event', color: '#008000', description: '地點：SF 134 / 13:00-17:00' },
          { id: 6, name: '環保社淨灘活動', date: '2026-05-01', type: 'event', color: '#008000', description: '地點：白沙灣 / 08:00-16:00' },
          { id: 7, name: '校園路跑挑戰', date: '2026-05-05', type: 'event', color: '#FF0000', description: '地點：操場 / 07:00-12:00' },
        ]
      });
    });
    </script>
  `),

  // ===== 社團資訊 =====
  'club-info': (role) => appShell(role, 'club-info', '社團資訊', `
    <div class="space-y-6">
      <div class="flex items-center gap-4 mb-2">
        <div class="flex-1 relative">
          <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" placeholder="搜尋社團..." class="w-full pl-11 pr-4 py-2.5 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none">
        </div>
        <div class="flex gap-2">
          <button class="px-3 py-2 rounded-fju bg-fju-blue text-white text-xs">全部</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">學術</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">服務</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">藝文</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">體育</button>
        </div>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        ${[
          { name: '攝影社', cat: '藝文', members: 50, desc: '培養攝影技巧與美學素養', icon: 'fas fa-camera' },
          { name: '吉他社', cat: '藝文', members: 45, desc: '吉他演奏與音樂創作', icon: 'fas fa-guitar' },
          { name: '資訊社', cat: '學術', members: 60, desc: '程式設計與資訊技術交流', icon: 'fas fa-laptop-code' },
          { name: '環保社', cat: '服務', members: 35, desc: '環境保護與永續發展推廣', icon: 'fas fa-leaf' },
          { name: '田徑社', cat: '體育', members: 40, desc: '田徑訓練與體育競技', icon: 'fas fa-running' },
          { name: '日文社', cat: '學術', members: 25, desc: '日語學習與日本文化體驗', icon: 'fas fa-language' },
        ].map(c => `
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 card-hover">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-12 h-12 rounded-fju bg-fju-blue/10 flex items-center justify-center"><i class="${c.icon} text-fju-blue text-lg"></i></div>
            <div>
              <h3 class="font-bold text-fju-blue">${c.name}</h3>
              <span class="text-xs px-2 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow font-medium">${c.cat}</span>
            </div>
          </div>
          <p class="text-sm text-gray-500 mb-3">${c.desc}</p>
          <div class="flex items-center justify-between">
            <span class="text-xs text-gray-400"><i class="fas fa-users mr-1"></i>${c.members} 成員</span>
            <button class="btn-yellow px-3 py-1 text-xs">查看詳情</button>
          </div>
        </div>`).join('')}
      </div>
    </div>
  `),

  // ===== 活動牆 =====
  'activity-wall': (role) => appShell(role, 'activity-wall', '活動牆', `
    <div class="space-y-6">
      <div class="flex items-center gap-4">
        <div class="flex-1 relative">
          <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" placeholder="搜尋活動關鍵字 / 標籤..." class="w-full pl-11 pr-4 py-2.5 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none">
        </div>
        <div class="flex gap-2">
          <button class="px-3 py-2 rounded-fju bg-fju-blue text-white text-xs">全部</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">學術</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">服務</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">康樂</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">體育</button>
          <button class="px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs">藝文</button>
        </div>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        ${[
          { title: '攝影社春季外拍', tag: '藝文', date: '04/20', venue: '校園各處', org: '攝影社', people: 50 },
          { title: '吉他社期末成果展', tag: '藝文', date: '04/15', venue: '中美堂', org: '吉他社', people: 200 },
          { title: '程式設計工作坊', tag: '學術', date: '04/25', venue: 'SF 134', org: '資訊社', people: 40 },
          { title: '淨灘環保活動', tag: '服務', date: '05/01', venue: '白沙灣', org: '環保社', people: 80 },
          { title: '校園路跑挑戰', tag: '體育', date: '05/05', venue: '操場', org: '田徑社', people: 150 },
          { title: '日語讀書會', tag: '學術', date: '04/10', venue: '圖書館', org: '日文社', people: 25 },
        ].map(a => `
        <div class="bg-white rounded-fju-lg overflow-hidden shadow-sm border border-gray-100 card-hover">
          <div class="h-32 bg-gradient-to-br from-fju-blue/20 to-fju-blue/5 flex items-center justify-center">
            <i class="fas fa-calendar-day text-4xl text-fju-blue/20"></i>
          </div>
          <div class="p-5">
            <div class="flex items-center gap-2 mb-2">
              <span class="px-2 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow text-[10px] font-bold">${a.tag}</span>
              <span class="text-xs text-gray-400">${a.date}</span>
            </div>
            <h3 class="font-bold text-fju-blue mb-1">${a.title}</h3>
            <p class="text-xs text-gray-400 mb-3"><i class="fas fa-map-marker-alt mr-1"></i>${a.venue} · <i class="fas fa-users ml-1 mr-1"></i>${a.people}人</p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">${a.org}</span>
              <button class="btn-yellow px-3 py-1 text-xs">報名</button>
            </div>
          </div>
        </div>`).join('')}
      </div>
    </div>
  `),

  // ===== AI 資訊概覽 =====
  'ai-overview': (role) => appShell(role, 'ai-overview', 'AI 資訊概覽', `
    <div class="space-y-6">
      <div class="bg-gradient-to-r from-fju-blue to-fju-blue-light rounded-fju-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-2"><i class="fas fa-brain mr-2 text-fju-yellow"></i>FJU Smart Hub AI 核心引擎</h2>
        <p class="text-white/60 text-sm">整合 Dify AI 平台、GPT-4、Pinecone 向量資料庫，提供智慧化校園管理服務</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        ${[
          { title: 'AI 智慧預審', desc: 'RAG 引擎自動比對校內法規，標記合規風險', icon: 'fas fa-shield-alt', stat: '94% 通過率', module: 'rag-search' },
          { title: 'AI 企劃生成', desc: '調用 Dify Workflow 參考法規自動生成企劃書', icon: 'fas fa-wand-magic-sparkles', stat: '每日 50+ 生成', module: 'ai-guide' },
          { title: 'AI 申訴摘要', desc: 'WebSocket 即時生成申訴案件摘要與建議', icon: 'fas fa-comments', stat: '即時處理', module: 'appeal' },
          { title: 'AI 協商監控', desc: 'GPT-4 監控 3/6 分鐘協商機制', icon: 'fas fa-chess', stat: '85% 自動解決', module: 'venue-booking' },
          { title: 'AI 推薦引擎', desc: '根據學生興趣與職能推薦活動', icon: 'fas fa-lightbulb', stat: '個人化推薦', module: 'activity-wall' },
          { title: 'RAG 知識庫', desc: 'Pinecone 向量 DB 索引 5+ 法規文件', icon: 'fas fa-database', stat: '5 法規已索引', module: 'rag-search' },
        ].map(ai => `
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 card-hover">
          <div class="w-12 h-12 rounded-fju bg-fju-blue/10 flex items-center justify-center mb-3"><i class="${ai.icon} text-fju-blue text-lg"></i></div>
          <h3 class="font-bold text-fju-blue mb-1">${ai.title}</h3>
          <p class="text-xs text-gray-500 mb-2">${ai.desc}</p>
          <span class="text-xs px-2 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow font-medium">${ai.stat}</span>
        </div>`).join('')}
      </div>
    </div>
  `),

  // ===== AI 導覽助理 =====
  'ai-guide': (role) => appShell(role, 'ai-guide', 'AI 導覽助理', `
    <div class="grid lg:grid-cols-5 gap-6">
      <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-wand-magic-sparkles mr-2 text-fju-yellow"></i>AI 企劃助手 (Dify Workflow)</h2>
          <div class="space-y-4">
            <div><label class="text-xs text-gray-400 block mb-1">活動名稱</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="例：攝影社春季外拍活動"></div>
            <div><label class="text-xs text-gray-400 block mb-1">活動類型</label>
              <select class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"><option>學術研討</option><option>社團活動</option><option>服務學習</option><option>康樂活動</option><option>體育競賽</option><option>藝文展演</option></select>
            </div>
            <div><label class="text-xs text-gray-400 block mb-1">預計人數</label><input type="number" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="50"></div>
            <div><label class="text-xs text-gray-400 block mb-1">活動描述</label><textarea rows="4" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="請描述活動目標、預期效益..."></textarea></div>
            <button class="w-full btn-yellow py-3" onclick="document.getElementById('ai-result').classList.remove('hidden')"><i class="fas fa-wand-magic-sparkles mr-2"></i>AI 一鍵生成企劃書</button>
          </div>
        </div>
        <div id="ai-result" class="hidden bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-3"><i class="fas fa-file-alt mr-2 text-fju-yellow"></i>AI 生成結果</h2>
          <div class="text-sm text-gray-600 leading-relaxed">
            <p class="mb-2"><strong>一、活動目的：</strong>提升社員攝影技巧，促進社團凝聚力。</p>
            <p class="mb-2"><strong>二、活動時間：</strong>2026年4月20日 09:00-17:00</p>
            <p class="mb-2"><strong>三、預算概估：</strong>NT$15,000</p>
            <div class="mt-3 p-3 rounded-fju bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>AI 預審：符合活動申請辦法，無違規項目</div>
          </div>
        </div>
      </div>
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue mb-3 text-sm">RAG 法規檢索</h3>
          <div class="space-y-2">
            <div class="p-3 rounded-fju bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>活動申請辦法</div>
            <div class="p-3 rounded-fju bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>場地使用管理規則</div>
            <div class="p-3 rounded-fju bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>經費補助要點</div>
            <div class="p-3 rounded-fju bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>安全管理規範</div>
          </div>
          <p class="text-[10px] text-gray-400 mt-3">Pinecone 向量資料庫 | 5 個法規文件已索引</p>
        </div>
      </div>
    </div>
  `),

  // ===== 法規查詢 (RAG) =====
  'rag-search': (role) => appShell(role, 'rag-search', '法規查詢 (RAG)', `
    <div class="space-y-6">
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>RAG 智慧法規查詢</h2>
        <div class="flex gap-3">
          <input type="text" placeholder="輸入您的查詢，例如：場地借用需要提前幾天申請？" class="flex-1 px-4 py-3 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none">
          <button class="btn-yellow px-6 py-3 text-sm"><i class="fas fa-search mr-2"></i>AI 查詢</button>
        </div>
      </div>
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 回覆</h3>
        <div class="p-4 rounded-fju bg-fju-bg text-sm text-gray-600 leading-relaxed">
          <p class="mb-2">根據《場地使用管理規則》第3條規定：</p>
          <p class="mb-2 pl-4 border-l-3 border-fju-yellow">「申請人應於使用日前 <strong>三個工作日</strong> 提出申請，經課外活動指導組審核通過後，方得使用。」</p>
          <p class="mb-2">此外，根據第5條：大型活動（50人以上）需額外提交安全計畫書。</p>
          <div class="mt-3 p-3 rounded-fju bg-fju-blue/5 text-xs text-fju-blue">
            <strong>參考法規：</strong> 場地使用管理規則 第3條、第5條 | 信心指數：95%
          </div>
        </div>
      </div>
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue mb-3 text-sm">知識庫文件</h3>
        <div class="grid md:grid-cols-2 gap-3">
          ${['活動申請辦法 v2.1', '場地使用管理規則 v3.0', '經費補助要點 v1.5', '器材借用辦法 v2.0', '安全管理規範 v1.8'].map(doc => `
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50 hover:bg-fju-blue/5 transition-colors cursor-pointer">
            <i class="fas fa-file-alt text-fju-yellow"></i>
            <span class="text-sm text-gray-600">${doc}</span>
            <i class="fas fa-external-link-alt text-gray-300 ml-auto text-xs"></i>
          </div>`).join('')}
        </div>
      </div>
    </div>
  `),

  // ===== 報修管理 =====
  'repair': (role) => appShell(role, 'repair', '報修管理', `
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>報修管理</h2>
        <button class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-2"></i>新增報修</button>
      </div>
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">
            <th class="p-4">編號</th><th class="p-4">場地/設備</th><th class="p-4">問題描述</th><th class="p-4">狀態</th><th class="p-4">提交日期</th>
          </tr></thead>
          <tbody>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs">RP-001</td><td class="p-4 text-fju-blue font-medium">投影機 EPSON EB-X51</td><td class="p-4 text-gray-500">燈泡更換</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-yellow/20 text-fju-yellow text-xs">處理中</span></td><td class="p-4 text-gray-400">04/01</td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs">RP-002</td><td class="p-4 text-fju-blue font-medium">SF 134 冷氣</td><td class="p-4 text-gray-500">冷氣不涼</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-red/10 text-fju-red text-xs">待處理</span></td><td class="p-4 text-gray-400">04/03</td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs">RP-003</td><td class="p-4 text-fju-blue font-medium">中美堂音響</td><td class="p-4 text-gray-500">音質異常</td><td class="p-4"><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">已完成</span></td><td class="p-4 text-gray-400">03/28</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  `),

  // ===== 申訴記錄 =====
  'appeal': (role) => appShell(role, 'appeal', '申訴記錄', `
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-comments mr-2 text-fju-yellow"></i>申訴案件列表</h2>
          <div class="space-y-3">
            <div class="p-4 rounded-fju border border-fju-red/20 bg-fju-red/5 cursor-pointer hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-fju-blue">場地衝突申訴 #AP-042</span><span class="px-2 py-1 rounded-fju bg-fju-red/10 text-fju-red text-xs">待處理</span></div>
              <p class="text-xs text-gray-500">攝影社與吉他社中美堂使用時段衝突</p>
            </div>
            <div class="p-4 rounded-fju border border-fju-yellow/30 bg-fju-yellow/5 cursor-pointer hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-fju-blue">器材損壞申訴 #AP-038</span><span class="px-2 py-1 rounded-fju bg-fju-yellow/20 text-fju-yellow text-xs">處理中</span></div>
              <p class="text-xs text-gray-500">借用投影機歸還時發現損壞</p>
            </div>
            <div class="p-4 rounded-fju border border-fju-green/20 bg-fju-green/5 cursor-pointer hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-fju-blue">信用積分申訴 #AP-035</span><span class="px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">已結案</span></div>
              <p class="text-xs text-gray-500">因系統故障導致簽到失敗扣分</p>
            </div>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 摘要 (WebSocket)</h2>
          <div class="p-4 rounded-fju bg-fju-bg">
            <div class="flex items-center gap-2 mb-3"><span class="w-2 h-2 rounded-full bg-fju-green animate-pulse"></span><span class="text-xs text-gray-400">即時生成中...</span></div>
            <div class="text-sm text-gray-600 leading-relaxed">
              <p class="mb-2"><strong>案件摘要：</strong>攝影社（Level 2）與吉他社（Level 2）同時申請中美堂。</p>
              <p class="mb-2"><strong>AI 建議：</strong>(1) 攝影社改至 SF 134；(2) 時段分割；(3) 吉他社延至隔天。</p>
            </div>
          </div>
          <div class="flex gap-2 mt-4">
            <button class="flex-1 btn-blue py-2 text-sm"><i class="fas fa-check mr-1"></i>採納方案</button>
            <button class="flex-1 btn-yellow py-2 text-sm"><i class="fas fa-gavel mr-1"></i>進入仲裁</button>
          </div>
        </div>
      </div>
    </div>
  `),

  // ===== 統計報表 =====
  'reports': (role) => appShell(role, 'reports', '統計報表', `
    <div class="space-y-6">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-blue">200+</div><div class="text-xs text-gray-400">學生社團</div>
        </div>
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-yellow">5,000+</div><div class="text-xs text-gray-400">活動場次/年</div>
        </div>
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-green">87%</div><div class="text-xs text-gray-400">場地使用率</div>
        </div>
        <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
          <div class="text-2xl font-black text-fju-blue">98%</div><div class="text-xs text-gray-400">滿意度</div>
        </div>
      </div>
      <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>社團參與趨勢</h3>
          <canvas id="report-trend" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>活動類型分布</h3>
          <canvas id="report-pie" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地使用率</h3>
          <canvas id="report-venue" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-radar mr-2 text-fju-yellow"></i>SDGs 貢獻</h3>
          <canvas id="report-sdg" height="200"></canvas>
        </div>
      </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      new Chart(document.getElementById('report-trend'), { type: 'line', data: { labels: ['9月','10月','11月','12月','1月','2月','3月'], datasets: [{ label: '參與人數', data: [1200,1350,1500,1420,1380,1550,1680], borderColor: '#002B5B', backgroundColor: 'rgba(0,43,91,0.1)', fill: true, tension: 0.4 }]}, options: { responsive: true, plugins: { legend: { display: false }}}});
      new Chart(document.getElementById('report-pie'), { type: 'doughnut', data: { labels: ['學術','服務','康樂','體育','藝文','綜合'], datasets: [{ data: [25,18,22,15,12,8], backgroundColor: ['#002B5B','#FFB800','#008000','#003A75','#FFC933','#666'] }]}, options: { responsive: true }});
      new Chart(document.getElementById('report-venue'), { type: 'bar', data: { labels: ['中美堂','活動中心','SF 134','草地廣場','會議室A','體育館'], datasets: [{ label: '使用次數', data: [45,38,35,28,22,18], backgroundColor: '#002B5B' }]}, options: { responsive: true, plugins: { legend: { display: false }}}});
      new Chart(document.getElementById('report-sdg'), { type: 'radar', data: { labels: ['SDG4','SDG5','SDG10','SDG11','SDG16','SDG17'], datasets: [{ label: '貢獻度', data: [85,60,75,90,55,70], borderColor: '#002B5B', backgroundColor: 'rgba(0,43,91,0.1)' }]}, options: { responsive: true, scales: { r: { min: 0, max: 100 }}}});
    });
    </script>
  `),

  // ===== Legacy module redirects =====
  'e-portfolio': (role) => appShell(role, 'dashboard', '職能 E-Portfolio', `
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-id-badge mr-2 text-fju-yellow"></i>職能 E-Portfolio</h2>
      <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div><label class="text-xs text-gray-400 block mb-1">姓名</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="王大明"></div>
        <div><label class="text-xs text-gray-400 block mb-1">學號</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="410012345"></div>
        <div><label class="text-xs text-gray-400 block mb-1">Email</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="410012345@cloud.fju.edu.tw"></div>
        <div><label class="text-xs text-gray-400 block mb-1">社團職位</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="攝影社 副社長"></div>
      </div>
      <div class="flex flex-wrap gap-2 mb-4">
        <span class="px-3 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-xs font-medium">領導力</span>
        <span class="px-3 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow text-xs font-medium">活動策劃</span>
        <span class="px-3 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-xs font-medium">攝影專業</span>
        <span class="px-3 py-1 rounded-full bg-fju-green/10 text-fju-green text-xs font-medium">團隊合作</span>
      </div>
      <button class="btn-yellow px-6 py-2 text-sm"><i class="fas fa-file-pdf mr-2"></i>匯出 PDF 履歷</button>
    </div>
  `),

  'ai-review': (role) => modules['rag-search'](role),
  'ai-proposal': (role) => modules['ai-guide'](role),
  'ai-appeal': (role) => modules['appeal'](role),
  'venue-data': (role) => modules['venue-booking'](role),
  'certificate': (role) => appShell(role, 'dashboard', '幹部證書', `
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-award mr-2 text-fju-yellow"></i>幹部證書自動化</h2>
      <div class="border-4 border-double border-fju-yellow rounded-fju-lg p-8 bg-white text-center max-w-lg mx-auto">
        <div class="text-2xl font-black text-fju-blue mb-1">輔仁大學</div>
        <div class="text-sm text-gray-500 mb-4">天主教輔仁大學課外活動指導組</div>
        <div class="w-16 h-0.5 bg-fju-yellow mx-auto mb-4"></div>
        <div class="text-lg font-bold text-fju-yellow mb-2">幹 部 證 書</div>
        <div class="text-sm text-gray-600 mb-4 leading-relaxed">
          茲證明 <span class="font-bold text-fju-blue border-b-2 border-fju-yellow px-2">王大明</span> 同學<br>
          於 <span class="font-bold">114學年度第一學期</span> 擔任<br>
          <span class="font-bold text-fju-blue">攝影社</span> <span class="font-bold text-fju-yellow">副社長</span> 乙職
        </div>
        <div class="text-xs text-gray-400">數位簽章驗證碼: FJU-CERT-2026-001234</div>
      </div>
      <div class="flex gap-3 justify-center mt-6">
        <button class="btn-blue px-6 py-2 text-sm"><i class="fas fa-download mr-2"></i>下載 PDF</button>
        <button class="btn-yellow px-6 py-2 text-sm"><i class="fas fa-certificate mr-2"></i>生成證書</button>
      </div>
    </div>
  `),
  'time-capsule': (role) => appShell(role, 'dashboard', '數位時光膠囊', `<div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100"><h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-clock-rotate-left mr-2 text-fju-yellow"></i>數位時光膠囊 (R2 Storage)</h2><p class="text-gray-500 text-sm">封裝社團移交文件至 Cloudflare R2。</p></div>`),
  '2fa': (role) => appShell(role, 'dashboard', '2FA 安全設定', `<div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100"><h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-lock mr-2 text-fju-yellow"></i>全方位 2FA 驗證</h2><p class="text-gray-500 text-sm mb-4">TOTP / SMS 雙因子驗證設定。</p><div class="flex items-center justify-between p-4 rounded-fju bg-gray-50 mb-3"><div class="flex items-center gap-3"><i class="fas fa-mobile-alt text-lg text-fju-blue"></i><div><div class="text-sm font-medium text-fju-blue">TOTP 驗證器</div><div class="text-xs text-gray-400">Google Authenticator</div></div></div><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" checked class="sr-only peer"><div class="w-11 h-6 bg-gray-200 peer-checked:bg-fju-green rounded-full after:content-[\\'\\'] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:after:translate-x-full after:transition-all"></div></label></div></div>`),
}

export function modulePages(name: string, role: string = 'student'): string | null {
  const mod = modules[name]
  return mod ? mod(role) : null
}
