import { layout } from './layout'

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

function sidebarMenus(role: string): string {
  const common = `
    <a href="/dashboard?role=${role}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-mary-blue/5 text-mary-blue font-medium text-sm">
      <i class="fas fa-tachometer-alt w-5 text-center"></i> 儀表板
    </a>
  `
  const menus: Record<string, string> = {
    admin: `
      <a href="/module/ai-review" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-brain w-5 text-center text-vatican-gold"></i> AI 智慧預審</a>
      <a href="/module/venue-data" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-map-location-dot w-5 text-center text-mary-blue"></i> 場地活化數據</a>
      <a href="/module/ai-appeal" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-comments w-5 text-center text-vatican-gold"></i> AI 申訴摘要</a>
      <a href="/module/certificate" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-award w-5 text-center text-mary-blue"></i> 證書管理</a>
      <a href="/module/equipment" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-boxes-stacked w-5 text-center text-vatican-gold"></i> 器材管理</a>
      <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-users-cog w-5 text-center text-mary-blue"></i> 用戶管理</a>
      <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-calendar-alt w-5 text-center text-vatican-gold"></i> 全域行事曆</a>
    `,
    officer: `
      <a href="/module/ai-proposal" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-wand-magic-sparkles w-5 text-center text-vatican-gold"></i> AI 企劃生成</a>
      <a href="/module/activity-wall" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-newspaper w-5 text-center text-mary-blue"></i> 活動管理</a>
      <a href="/module/equipment" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-boxes-stacked w-5 text-center text-vatican-gold"></i> 器材借用</a>
      <a href="/module/time-capsule" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-clock-rotate-left w-5 text-center text-mary-blue"></i> 時光膠囊</a>
      <a href="/module/certificate" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-award w-5 text-center text-vatican-gold"></i> 幹部證書</a>
    `,
    professor: `
      <a href="/module/ai-review" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-brain w-5 text-center text-vatican-gold"></i> 審核系統</a>
      <a href="/module/e-portfolio" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-id-badge w-5 text-center text-mary-blue"></i> 學生 Portfolio</a>
      <a href="/module/activity-wall" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-newspaper w-5 text-center text-vatican-gold"></i> 活動一覽</a>
    `,
    student: `
      <a href="/module/e-portfolio" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-id-badge w-5 text-center text-vatican-gold"></i> 我的 Portfolio</a>
      <a href="/module/activity-wall" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-newspaper w-5 text-center text-mary-blue"></i> 活動牆</a>
      <a href="/module/ai-proposal" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-wand-magic-sparkles w-5 text-center text-vatican-gold"></i> AI 企劃助手</a>
    `,
    it: `
      <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-chart-bar w-5 text-center text-vatican-gold"></i> 系統監控</a>
      <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-shield-halved w-5 text-center text-mary-blue"></i> WAF 日誌</a>
      <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-hard-drive w-5 text-center text-vatican-gold"></i> R2 儲存</a>
      <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-gray-50 text-gray-600 text-sm"><i class="fas fa-users-cog w-5 text-center text-mary-blue"></i> 帳號管理</a>
    `,
  }
  return common + (menus[role] || '')
}

function dashboardCharts(role: string): string {
  const charts: Record<string, string> = {
    admin: `
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-line mr-2 text-vatican-gold"></i>社團參與趨勢</h3>
          <canvas id="chart-trend" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-pie mr-2 text-vatican-gold"></i>活動類型分布</h3>
          <canvas id="chart-type" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-gauge-high mr-2 text-vatican-gold"></i>經費執行率</h3>
          <canvas id="chart-budget" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-bar mr-2 text-vatican-gold"></i>場地周轉率</h3>
          <canvas id="chart-venue" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-diagram-project mr-2 text-vatican-gold"></i>SDGs 貢獻雷達圖</h3>
          <canvas id="chart-sdg" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-filter mr-2 text-vatican-gold"></i>審核時效漏斗</h3>
          <canvas id="chart-funnel" height="200"></canvas>
        </div>
      </div>`,
    officer: `
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-bar mr-2 text-vatican-gold"></i>幹部出勤率</h3>
          <canvas id="chart-attendance" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-line mr-2 text-vatican-gold"></i>成員留存率</h3>
          <canvas id="chart-retention" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-pie mr-2 text-vatican-gold"></i>滿意度分析</h3>
          <canvas id="chart-satisfaction" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-droplet mr-2 text-vatican-gold"></i>經費使用水滴圖</h3>
          <canvas id="chart-budget-drop" height="200"></canvas>
        </div>
      </div>`,
    professor: `
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-radar mr-2 text-vatican-gold"></i>績效考評雷達圖</h3>
          <canvas id="chart-perf" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-fire mr-2 text-vatican-gold"></i>活動熱力圖</h3>
          <canvas id="chart-heatmap" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-spider mr-2 text-vatican-gold"></i>職能成長蛛網圖</h3>
          <canvas id="chart-spider" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-traffic-light mr-2 text-vatican-gold"></i>風險狀態燈號</h3>
          <div class="flex justify-around items-center py-8">
            <div class="text-center"><div class="w-16 h-16 rounded-full bg-harvest-green mx-auto mb-2 shadow-lg shadow-green-200"></div><span class="text-xs text-gray-500">正常 12</span></div>
            <div class="text-center"><div class="w-16 h-16 rounded-full bg-yellow-400 mx-auto mb-2 shadow-lg shadow-yellow-200"></div><span class="text-xs text-gray-500">注意 3</span></div>
            <div class="text-center"><div class="w-16 h-16 rounded-full bg-warning-red mx-auto mb-2 shadow-lg shadow-red-200"></div><span class="text-xs text-gray-500">警告 1</span></div>
          </div>
        </div>
      </div>`,
    student: `
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 lg:col-span-2">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-id-badge mr-2 text-vatican-gold"></i>個人履歷儀表板</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 rounded-xl bg-mary-blue/5"><div class="text-2xl font-black text-mary-blue">8</div><div class="text-xs text-gray-500">參與活動</div></div>
            <div class="text-center p-4 rounded-xl bg-vatican-gold/10"><div class="text-2xl font-black text-vatican-gold">3</div><div class="text-xs text-gray-500">擔任幹部</div></div>
            <div class="text-center p-4 rounded-xl bg-mary-blue/5"><div class="text-2xl font-black text-mary-blue">85</div><div class="text-xs text-gray-500">信用積分</div></div>
            <div class="text-center p-4 rounded-xl bg-harvest-green/10"><div class="text-2xl font-black text-harvest-green">A</div><div class="text-xs text-gray-500">職能等級</div></div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chart-radar mr-2 text-vatican-gold"></i>職能 Stepper</h3>
          <canvas id="chart-competency" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-robot mr-2 text-vatican-gold"></i>AI 推薦活動</h3>
          <div class="space-y-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-mary-blue/5 cursor-pointer transition-colors">
              <div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center shrink-0"><i class="fas fa-music text-white text-sm"></i></div>
              <div><div class="text-sm font-medium text-mary-blue">吉他社期末成果展</div><div class="text-xs text-gray-400">04/15 · 中美堂</div></div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-mary-blue/5 cursor-pointer transition-colors">
              <div class="w-10 h-10 rounded-lg blue-gradient flex items-center justify-center shrink-0"><i class="fas fa-camera text-white text-sm"></i></div>
              <div><div class="text-sm font-medium text-mary-blue">攝影社外拍活動</div><div class="text-xs text-gray-400">04/20 · 校園各處</div></div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-mary-blue/5 cursor-pointer transition-colors">
              <div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center shrink-0"><i class="fas fa-laptop-code text-white text-sm"></i></div>
              <div><div class="text-sm font-medium text-mary-blue">程式設計工作坊</div><div class="text-xs text-gray-400">04/25 · SF 134</div></div>
            </div>
          </div>
        </div>
      </div>`,
    it: `
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-server mr-2 text-vatican-gold"></i>系統負載熱圖</h3>
          <canvas id="chart-load" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-tachometer-alt mr-2 text-vatican-gold"></i>API 延遲/成功率</h3>
          <canvas id="chart-api" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-shield-halved mr-2 text-vatican-gold"></i>WAF 攔截日誌</h3>
          <canvas id="chart-waf" height="200"></canvas>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-hard-drive mr-2 text-vatican-gold"></i>R2 儲存使用率</h3>
          <canvas id="chart-r2" height="200"></canvas>
        </div>
      </div>`,
  }
  return charts[role] || charts.student
}

function chartScript(role: string): string {
  const scripts: Record<string, string> = {
    admin: `
      // Trend Chart
      new Chart(document.getElementById('chart-trend'), {
        type: 'line',
        data: { labels: ['9月','10月','11月','12月','1月','2月','3月'],
          datasets: [{ label: '社團參與人數', data: [1200,1350,1500,1420,1380,1550,1680], borderColor: '#003153', backgroundColor: 'rgba(0,49,83,0.1)', fill: true, tension: 0.4 }]
        }, options: { responsive: true, plugins: { legend: { display: false }}}
      });
      // Type Chart
      new Chart(document.getElementById('chart-type'), {
        type: 'doughnut',
        data: { labels: ['學術','服務','康樂','體育','藝文','綜合'], datasets: [{ data: [25,18,22,15,12,8], backgroundColor: ['#003153','#DAA520','#008000','#004a7c','#FDB913','#666'] }]},
        options: { responsive: true }
      });
      // Budget Gauge
      new Chart(document.getElementById('chart-budget'), {
        type: 'doughnut',
        data: { labels: ['已執行','未執行'], datasets: [{ data: [72,28], backgroundColor: ['#DAA520','#e5e7eb'], borderWidth: 0 }]},
        options: { responsive: true, cutout: '75%', plugins: { legend: { position: 'bottom' }}}
      });
      // Venue Bar
      new Chart(document.getElementById('chart-venue'), {
        type: 'bar',
        data: { labels: ['中美堂','活動中心','草地廣場','教室A','教室B','會議室'],
          datasets: [
            { label: '已預約', data: [45,38,28,52,35,20], backgroundColor: '#003153' },
            { label: '空閒', data: [5,12,22,8,15,30], backgroundColor: '#DAA520' }
          ]
        }, options: { responsive: true, scales: { x: { stacked: true }, y: { stacked: true }}}
      });
      // SDG Radar
      new Chart(document.getElementById('chart-sdg'), {
        type: 'radar',
        data: { labels: ['SDG4','SDG5','SDG10','SDG11','SDG16','SDG17'],
          datasets: [{ label: '貢獻度', data: [85,60,75,90,55,70], borderColor: '#003153', backgroundColor: 'rgba(0,49,83,0.1)' }]
        }, options: { responsive: true, scales: { r: { min: 0, max: 100 }}}
      });
      // Funnel (horizontal bar)
      new Chart(document.getElementById('chart-funnel'), {
        type: 'bar',
        data: { labels: ['提交申請','AI 預審','人工審核','核准','完成'],
          datasets: [{ data: [100,88,72,65,58], backgroundColor: ['#003153','#004a7c','#DAA520','#FDB913','#008000'] }]
        }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }}}
      });`,
    officer: `
      new Chart(document.getElementById('chart-attendance'), {
        type: 'bar',
        data: { labels: ['社長','副社長','財務','公關','活動','總務'],
          datasets: [{ label: '出勤率%', data: [95,88,92,78,85,90], backgroundColor: '#003153' }]
        }, options: { responsive: true, scales: { y: { max: 100 }}}
      });
      new Chart(document.getElementById('chart-retention'), {
        type: 'line',
        data: { labels: ['9月','10月','11月','12月','1月','2月','3月'],
          datasets: [{ label: '留存率%', data: [100,95,88,85,82,80,78], borderColor: '#DAA520', backgroundColor: 'rgba(218,165,32,0.1)', fill: true, tension: 0.4 }]
        }, options: { responsive: true }
      });
      new Chart(document.getElementById('chart-satisfaction'), {
        type: 'pie',
        data: { labels: ['非常滿意','滿意','普通','不滿意'],
          datasets: [{ data: [45,35,15,5], backgroundColor: ['#008000','#DAA520','#003153','#FF0000'] }]
        }, options: { responsive: true }
      });
      new Chart(document.getElementById('chart-budget-drop'), {
        type: 'doughnut',
        data: { labels: ['已使用','剩餘'], datasets: [{ data: [65,35], backgroundColor: ['#003153','#e5e7eb'], borderWidth: 0 }]},
        options: { responsive: true, cutout: '75%' }
      });`,
    professor: `
      new Chart(document.getElementById('chart-perf'), {
        type: 'radar',
        data: { labels: ['活動策劃','團隊領導','預算管理','創新能力','溝通協調','時間管理'],
          datasets: [{ label: '績效', data: [85,78,90,72,88,82], borderColor: '#003153', backgroundColor: 'rgba(0,49,83,0.1)' }]
        }, options: { responsive: true, scales: { r: { min: 0, max: 100 }}}
      });
      new Chart(document.getElementById('chart-heatmap'), {
        type: 'bar',
        data: { labels: ['週一','週二','週三','週四','週五','週六','週日'],
          datasets: [{ label: '活動數', data: [3,5,8,4,6,12,2], backgroundColor: '#DAA520' }]
        }, options: { responsive: true }
      });
      new Chart(document.getElementById('chart-spider'), {
        type: 'radar',
        data: { labels: ['專業知識','實作能力','團隊合作','溝通表達','問題解決','創意思維'],
          datasets: [
            { label: '學期初', data: [50,45,60,55,40,35], borderColor: '#ccc', backgroundColor: 'rgba(200,200,200,0.1)' },
            { label: '學期末', data: [80,75,85,78,70,65], borderColor: '#DAA520', backgroundColor: 'rgba(218,165,32,0.1)' }
          ]
        }, options: { responsive: true, scales: { r: { min: 0, max: 100 }}}
      });`,
    student: `
      new Chart(document.getElementById('chart-competency'), {
        type: 'radar',
        data: { labels: ['領導力','溝通','專業','創新','執行','服務'],
          datasets: [{ label: '我的職能', data: [75,82,68,70,88,72], borderColor: '#003153', backgroundColor: 'rgba(0,49,83,0.15)' }]
        }, options: { responsive: true, scales: { r: { min: 0, max: 100 }}}
      });`,
    it: `
      new Chart(document.getElementById('chart-load'), {
        type: 'line',
        data: { labels: ['00:00','04:00','08:00','12:00','16:00','20:00','24:00'],
          datasets: [{ label: 'CPU %', data: [15,10,35,78,65,45,20], borderColor: '#003153', tension: 0.4 },
                     { label: 'Memory %', data: [40,38,55,72,68,52,42], borderColor: '#DAA520', tension: 0.4 }]
        }, options: { responsive: true }
      });
      new Chart(document.getElementById('chart-api'), {
        type: 'bar',
        data: { labels: ['Auth','Users','Venues','Activities','Equipment','AI'],
          datasets: [{ label: '延遲 ms', data: [45,120,85,200,95,350], backgroundColor: '#003153' },
                     { label: '成功率 %', data: [99.9,99.5,99.8,98.2,99.7,97.5], backgroundColor: '#DAA520' }]
        }, options: { responsive: true }
      });
      new Chart(document.getElementById('chart-waf'), {
        type: 'line',
        data: { labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
          datasets: [{ label: '攔截次數', data: [23,45,12,67,34,8,5], borderColor: '#FF0000', backgroundColor: 'rgba(255,0,0,0.1)', fill: true, tension: 0.4 }]
        }, options: { responsive: true }
      });
      new Chart(document.getElementById('chart-r2'), {
        type: 'doughnut',
        data: { labels: ['已使用','可用'], datasets: [{ data: [45,55], backgroundColor: ['#003153','#e5e7eb'], borderWidth: 0 }]},
        options: { responsive: true, cutout: '75%' }
      });`,
  }
  return scripts[role] || scripts.student
}

export function dashboard(role: string): string {
  if (!roleNames[role]) role = 'student'
  const body = `
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar (30%) -->
    <aside class="w-72 bg-white border-r border-gray-100 flex flex-col h-screen sticky top-0 overflow-y-auto">
      <!-- Logo -->
      <div class="p-6 border-b border-gray-100">
        <a href="/" class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center shadow-md">
            <i class="fas fa-university text-mary-blue"></i>
          </div>
          <div>
            <div class="font-bold text-mary-blue text-sm">FJU Smart Hub</div>
            <div class="text-xs text-gray-400">${roleNames[role]}</div>
          </div>
        </a>
      </div>

      <!-- Credit Dashboard -->
      <div class="p-4">
        <div class="rounded-2xl bg-gradient-to-br from-mary-blue to-blue-900 p-4 text-white mb-4">
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs text-white/70">信用積分</span>
            <span class="text-xs px-2 py-0.5 rounded-full bg-harvest-green/20 text-harvest-green"><i class="fas fa-check-circle mr-1"></i>正常</span>
          </div>
          <div class="text-3xl font-black mb-2">85<span class="text-sm font-normal text-white/50"> / 100</span></div>
          <div class="w-full bg-white/20 rounded-full h-2 mb-2">
            <div class="credit-gauge bg-vatican-gold rounded-full h-2" style="width: 85%"></div>
          </div>
          <div class="text-[10px] text-white/50">低於 60 分將被強制登出</div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="flex-1 p-4 space-y-1">
        ${sidebarMenus(role)}
      </nav>

      <!-- User Info -->
      <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full blue-gradient flex items-center justify-center text-white font-bold text-sm">
            <i class="${roleIcons[role]}"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-sm font-medium text-mary-blue truncate">Demo User</div>
            <div class="text-xs text-gray-400 truncate">demo@cloud.fju.edu.tw</div>
          </div>
          <a href="/login" class="text-gray-400 hover:text-warning-red transition-colors">
            <i class="fas fa-sign-out-alt"></i>
          </a>
        </div>
      </div>
    </aside>

    <!-- Main Content (70%) -->
    <main class="flex-1 flex flex-col min-h-screen">
      <!-- Header -->
      <header class="glassmorphism sticky top-0 z-40 px-6 py-4 flex items-center justify-between border-b border-gray-100">
        <!-- Floating Search Bar (Google Maps style) -->
        <div class="flex-1 max-w-xl mx-auto">
          <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="搜尋活動、場地、社團..." 
                   class="w-full pl-11 pr-4 py-2.5 rounded-full bg-gray-50 border border-gray-200 focus:border-mary-blue focus:ring-2 focus:ring-mary-blue/10 outline-none text-sm">
          </div>
        </div>
        <!-- Right Actions -->
        <div class="flex items-center gap-4 ml-4">
          <!-- Language Selector -->
          <select class="text-xs bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 outline-none">
            <option>繁體中文</option><option>简体中文</option><option>English</option><option>日本語</option><option>한국어</option>
          </select>
          <!-- Notifications -->
          <button class="relative w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center hover:bg-gray-100 transition-colors">
            <i class="fas fa-bell text-gray-500"></i>
            <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-warning-red text-white text-[10px] flex items-center justify-center font-bold">3</span>
          </button>
          <!-- Role Switch -->
          <div class="flex gap-1">
            ${Object.keys(roleNames).map(r => `
              <a href="/dashboard?role=${r}" class="w-8 h-8 rounded-lg ${r === role ? 'bg-mary-blue text-white' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'} flex items-center justify-center transition-colors" title="${roleNames[r]}">
                <i class="${roleIcons[r]} text-xs"></i>
              </a>
            `).join('')}
          </div>
        </div>
      </header>

      <!-- Dashboard Content -->
      <div class="flex-1 p-6 overflow-y-auto">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-mary-blue to-blue-800 rounded-2xl p-6 mb-6 text-white relative overflow-hidden">
          <div class="absolute top-0 right-0 w-32 h-32 bg-vatican-gold/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
          <div class="relative z-10">
            <h1 class="text-2xl font-bold mb-1">歡迎回來，${roleNames[role]}</h1>
            <p class="text-white/60 text-sm">今天是 2026年4月3日 星期五 | ${role === 'admin' ? '有 5 件待審核事項' : '系統運行正常'}</p>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
          <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs text-gray-400">待處理</span>
              <div class="w-8 h-8 rounded-lg gold-gradient flex items-center justify-center"><i class="fas fa-clock text-white text-xs"></i></div>
            </div>
            <div class="text-2xl font-black text-mary-blue">12</div>
          </div>
          <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs text-gray-400">本月活動</span>
              <div class="w-8 h-8 rounded-lg blue-gradient flex items-center justify-center"><i class="fas fa-calendar text-white text-xs"></i></div>
            </div>
            <div class="text-2xl font-black text-vatican-gold">28</div>
          </div>
          <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs text-gray-400">場地使用</span>
              <div class="w-8 h-8 rounded-lg gold-gradient flex items-center justify-center"><i class="fas fa-building text-white text-xs"></i></div>
            </div>
            <div class="text-2xl font-black text-mary-blue">87%</div>
          </div>
          <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 card-hover">
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs text-gray-400">AI 審核通過</span>
              <div class="w-8 h-8 rounded-lg blue-gradient flex items-center justify-center"><i class="fas fa-robot text-white text-xs"></i></div>
            </div>
            <div class="text-2xl font-black text-harvest-green">94%</div>
          </div>
        </div>

        <!-- Dual Modal: Map + Calendar -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Interactive Map Placeholder -->
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
              <h3 class="font-bold text-mary-blue text-sm"><i class="fas fa-map mr-2 text-vatican-gold"></i>校園互動地圖</h3>
              <span class="text-xs text-gray-400">Leaflet.js</span>
            </div>
            <div class="h-64 bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center relative">
              <div class="text-center">
                <i class="fas fa-map-marked-alt text-5xl text-mary-blue/20 mb-3"></i>
                <p class="text-gray-400 text-sm">校園場地分布地圖</p>
                <p class="text-gray-300 text-xs">點擊場地查看預約狀態</p>
              </div>
              <!-- Simulated map pins -->
              <div class="absolute top-12 left-16 w-6 h-6 rounded-full bg-harvest-green text-white flex items-center justify-center text-[10px] shadow-md cursor-pointer hover:scale-125 transition-transform" title="中美堂">1</div>
              <div class="absolute top-20 right-24 w-6 h-6 rounded-full bg-vatican-gold text-white flex items-center justify-center text-[10px] shadow-md cursor-pointer hover:scale-125 transition-transform" title="活動中心">2</div>
              <div class="absolute bottom-16 left-1/3 w-6 h-6 rounded-full bg-warning-red text-white flex items-center justify-center text-[10px] shadow-md cursor-pointer hover:scale-125 transition-transform" title="草地廣場(維修中)">3</div>
              <div class="absolute bottom-24 right-16 w-6 h-6 rounded-full bg-harvest-green text-white flex items-center justify-center text-[10px] shadow-md cursor-pointer hover:scale-125 transition-transform" title="SF 134">4</div>
            </div>
          </div>
          <!-- Smart Calendar (Glassmorphism) -->
          <div class="glassmorphism rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100/50 flex items-center justify-between">
              <h3 class="font-bold text-mary-blue text-sm"><i class="fas fa-calendar-alt mr-2 text-vatican-gold"></i>智慧行事曆</h3>
              <div class="flex gap-2">
                <button class="text-xs px-3 py-1 rounded-full bg-mary-blue text-white">月</button>
                <button class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-500">週</button>
              </div>
            </div>
            <div class="p-4">
              <div class="grid grid-cols-7 gap-1 text-center text-xs mb-2">
                ${['日','一','二','三','四','五','六'].map(d => `<span class="text-gray-400 font-medium py-1">${d}</span>`).join('')}
              </div>
              <div class="grid grid-cols-7 gap-1 text-center text-xs">
                ${Array.from({length:35}, (_, i) => {
                  const day = i - 2 // April starts Wed
                  const isToday = day === 3
                  const hasEvent = [5,8,12,15,20,25].includes(day)
                  if (day < 1 || day > 30) return `<span class="py-2 text-gray-300">${day < 1 ? 28+day : day-30}</span>`
                  return `<span class="py-2 rounded-lg cursor-pointer transition-colors ${isToday ? 'bg-mary-blue text-white font-bold' : hasEvent ? 'bg-vatican-gold/10 text-mary-blue font-medium' : 'hover:bg-gray-100 text-gray-600'} relative">
                    ${day}${hasEvent ? '<span class="absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-vatican-gold"></span>' : ''}
                  </span>`
                }).join('')}
              </div>
              <!-- Upcoming Events -->
              <div class="mt-4 space-y-2">
                <div class="flex items-center gap-2 p-2 rounded-lg bg-white/50 text-xs">
                  <div class="w-1 h-8 rounded-full bg-mary-blue"></div>
                  <div><div class="font-medium text-mary-blue">社團評鑑會議</div><div class="text-gray-400">04/05 14:00</div></div>
                </div>
                <div class="flex items-center gap-2 p-2 rounded-lg bg-white/50 text-xs">
                  <div class="w-1 h-8 rounded-full bg-vatican-gold"></div>
                  <div><div class="font-medium text-mary-blue">金乐獎初審</div><div class="text-gray-400">04/08 10:00</div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Role-specific Charts -->
        ${dashboardCharts(role)}
      </div>
    </main>

    <!-- FAB: AI Assistant (Shiba Inu) -->
    <button class="fixed bottom-6 right-6 w-14 h-14 rounded-full gold-gradient shadow-2xl flex items-center justify-center text-2xl hover:scale-110 transition-transform z-50 animate-pulse-gold" 
            onclick="document.getElementById('ai-panel').classList.toggle('hidden')" title="AI 智慧助手">
      🐕
    </button>
    <!-- AI Chat Panel -->
    <div id="ai-panel" class="hidden fixed bottom-24 right-6 w-80 glassmorphism rounded-2xl shadow-2xl z-50 overflow-hidden">
      <div class="bg-mary-blue text-white p-4 flex items-center justify-between">
        <span class="font-bold text-sm"><i class="fas fa-robot mr-2"></i>AI 智慧助手</span>
        <button onclick="document.getElementById('ai-panel').classList.add('hidden')" class="text-white/60 hover:text-white"><i class="fas fa-times"></i></button>
      </div>
      <div class="p-4 h-64 overflow-y-auto bg-gray-50">
        <div class="flex gap-2 mb-3">
          <div class="w-8 h-8 rounded-full gold-gradient flex items-center justify-center shrink-0 text-sm">🐕</div>
          <div class="bg-white rounded-2xl rounded-tl-none p-3 text-sm text-gray-600 shadow-sm">
            你好！我是 FJU Smart Hub AI 助手。我可以幫你查詢場地、審核狀態，或回答任何系統問題。有什麼我可以幫忙的嗎？
          </div>
        </div>
      </div>
      <div class="p-3 border-t border-gray-100 flex gap-2">
        <input type="text" placeholder="輸入你的問題..." class="flex-1 px-3 py-2 rounded-xl bg-gray-50 border border-gray-200 text-sm outline-none focus:border-mary-blue">
        <button class="w-10 h-10 rounded-xl btn-gold flex items-center justify-center"><i class="fas fa-paper-plane text-sm"></i></button>
      </div>
    </div>
  </div>

  <script>
    // Initialize Charts
    document.addEventListener('DOMContentLoaded', () => {
      ${chartScript(role)}
    });

    // GSAP entrance
    gsap.from('.card-hover', { opacity: 0, y: 20, stagger: 0.1, duration: 0.5, ease: 'power2.out' });
  </script>
  `

  return layout(`${roleNames[role]}儀表板`, body)
}
