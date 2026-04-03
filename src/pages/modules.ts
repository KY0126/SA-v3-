import { layout } from './layout'

function moduleLayout(title: string, icon: string, content: string): string {
  const body = `
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Compact Sidebar -->
    <aside class="w-16 bg-mary-blue flex flex-col items-center py-6 h-screen sticky top-0">
      <a href="/" class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center mb-8 shadow-md">
        <i class="fas fa-university text-mary-blue text-sm"></i>
      </a>
      <nav class="flex-1 flex flex-col items-center gap-3">
        <a href="/dashboard" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="儀表板"><i class="fas fa-tachometer-alt text-sm"></i></a>
        <a href="/module/e-portfolio" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="E-Portfolio"><i class="fas fa-id-badge text-sm"></i></a>
        <a href="/module/ai-proposal" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="AI 企劃生成"><i class="fas fa-wand-magic-sparkles text-sm"></i></a>
        <a href="/module/certificate" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="幹部證書"><i class="fas fa-award text-sm"></i></a>
        <a href="/module/equipment" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="器材管理"><i class="fas fa-boxes-stacked text-sm"></i></a>
        <a href="/module/ai-review" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="AI 預審"><i class="fas fa-brain text-sm"></i></a>
        <a href="/module/venue-data" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="場地數據"><i class="fas fa-map-location-dot text-sm"></i></a>
        <a href="/module/activity-wall" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-white/20 hover:text-white transition-all" title="活動牆"><i class="fas fa-newspaper text-sm"></i></a>
      </nav>
      <a href="/login" class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 hover:bg-warning-red/50 hover:text-white transition-all" title="登出"><i class="fas fa-sign-out-alt text-sm"></i></a>
    </aside>
    <!-- Main -->
    <main class="flex-1 min-h-screen">
      <header class="glassmorphism sticky top-0 z-40 px-6 py-4 flex items-center gap-4 border-b border-gray-100">
        <a href="/dashboard" class="text-gray-400 hover:text-mary-blue transition-colors"><i class="fas fa-arrow-left"></i></a>
        <div class="w-10 h-10 rounded-xl gold-gradient flex items-center justify-center shadow-md"><i class="${icon} text-white"></i></div>
        <h1 class="text-lg font-bold text-mary-blue">${title}</h1>
      </header>
      <div class="p-6">${content}</div>
    </main>
  </div>`
  return layout(title, body)
}

const modules: Record<string, () => string> = {
  'e-portfolio': () => moduleLayout('職能 E-Portfolio', 'fas fa-id-badge', `
    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">個人資訊</h2>
          <div class="grid md:grid-cols-2 gap-4">
            <div><label class="text-xs text-gray-400 block mb-1">姓名</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="王大明"></div>
            <div><label class="text-xs text-gray-400 block mb-1">學號</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="410012345"></div>
            <div><label class="text-xs text-gray-400 block mb-1">Outlook</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="410012345@cloud.fju.edu.tw"></div>
            <div><label class="text-xs text-gray-400 block mb-1">手機</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="0912-345-678"></div>
            <div class="md:col-span-2"><label class="text-xs text-gray-400 block mb-1">社團職位</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="攝影社 副社長"></div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">職能標籤</h2>
          <div class="flex flex-wrap gap-2 mb-4">
            <span class="px-3 py-1 rounded-full bg-mary-blue/10 text-mary-blue text-xs font-medium">領導力</span>
            <span class="px-3 py-1 rounded-full bg-vatican-gold/20 text-vatican-gold text-xs font-medium">活動策劃</span>
            <span class="px-3 py-1 rounded-full bg-mary-blue/10 text-mary-blue text-xs font-medium">攝影專業</span>
            <span class="px-3 py-1 rounded-full bg-harvest-green/10 text-harvest-green text-xs font-medium">團隊合作</span>
            <span class="px-3 py-1 rounded-full bg-vatican-gold/20 text-vatican-gold text-xs font-medium">溝通協調</span>
            <button class="px-3 py-1 rounded-full border-2 border-dashed border-gray-300 text-gray-400 text-xs hover:border-mary-blue hover:text-mary-blue transition-colors">+ 新增標籤</button>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">活動經歷</h2>
          <div class="space-y-3">
            <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50">
              <div class="w-10 h-10 rounded-lg gold-gradient flex items-center justify-center shrink-0"><i class="fas fa-camera text-white text-sm"></i></div>
              <div class="flex-1"><div class="font-medium text-sm text-mary-blue">攝影社期末成果展</div><div class="text-xs text-gray-400 mt-1">2026/01 · 活動策劃、現場執行</div></div>
              <span class="px-2 py-1 rounded-lg bg-harvest-green/10 text-harvest-green text-xs">已完成</span>
            </div>
            <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50">
              <div class="w-10 h-10 rounded-lg blue-gradient flex items-center justify-center shrink-0"><i class="fas fa-handshake text-white text-sm"></i></div>
              <div class="flex-1"><div class="font-medium text-sm text-mary-blue">社團聯合公益活動</div><div class="text-xs text-gray-400 mt-1">2025/12 · 志工服務、攝影記錄</div></div>
              <span class="px-2 py-1 rounded-lg bg-harvest-green/10 text-harvest-green text-xs">已完成</span>
            </div>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">職能雷達圖</h2>
          <canvas id="portfolio-radar" height="250"></canvas>
        </div>
        <button class="w-full btn-gold py-3 rounded-xl text-sm"><i class="fas fa-file-pdf mr-2"></i>匯出 PDF 履歷</button>
        <button class="w-full btn-blue py-3 rounded-xl text-sm"><i class="fas fa-print mr-2"></i>列印 Portfolio</button>
      </div>
    </div>
    <script>
      new Chart(document.getElementById('portfolio-radar'), {
        type: 'radar',
        data: { labels: ['領導力','溝通','專業技能','創新','執行力','服務'],
          datasets: [{ label: '職能分數', data: [80,85,75,70,90,78], borderColor: '#003153', backgroundColor: 'rgba(0,49,83,0.15)', pointBackgroundColor: '#DAA520' }]
        }, options: { scales: { r: { min: 0, max: 100 }}, plugins: { legend: { display: false }}}
      });
    </script>
  `),

  'ai-proposal': () => moduleLayout('AI 自動企劃生成器', 'fas fa-wand-magic-sparkles', `
    <div class="grid lg:grid-cols-5 gap-6">
      <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4"><i class="fas fa-brain mr-2 text-vatican-gold"></i>AI 企劃助手 (Dify Workflow)</h2>
          <div class="space-y-4">
            <div><label class="text-xs text-gray-400 block mb-1">活動名稱</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" placeholder="例：攝影社春季外拍活動"></div>
            <div><label class="text-xs text-gray-400 block mb-1">活動類型</label>
              <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm"><option>學術研討</option><option>社團活動</option><option>服務學習</option><option>康樂活動</option><option>體育競賽</option><option>藝文展演</option></select>
            </div>
            <div><label class="text-xs text-gray-400 block mb-1">預計參加人數</label><input type="number" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" placeholder="50"></div>
            <div><label class="text-xs text-gray-400 block mb-1">活動描述 / 需求</label><textarea rows="4" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" placeholder="請描述活動目標、預期效益..."></textarea></div>
            <button class="w-full btn-gold py-3 rounded-xl" onclick="generateProposal()"><i class="fas fa-wand-magic-sparkles mr-2"></i>AI 一鍵生成企劃書</button>
          </div>
        </div>
        <div id="proposal-result" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hidden">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-mary-blue"><i class="fas fa-file-alt mr-2 text-vatican-gold"></i>AI 生成結果</h2>
            <div class="flex gap-2">
              <button class="px-3 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs hover:bg-gray-200"><i class="fas fa-copy mr-1"></i>複製</button>
              <button class="px-3 py-1 rounded-lg bg-mary-blue text-white text-xs hover:bg-mary-blue/80"><i class="fas fa-download mr-1"></i>下載 Word</button>
            </div>
          </div>
          <div id="proposal-content" class="prose prose-sm text-gray-600"></div>
        </div>
      </div>
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-3 text-sm">RAG 法規檢索</h3>
          <p class="text-xs text-gray-400 mb-3">AI 將自動比對以下法規：</p>
          <div class="space-y-2">
            <div class="p-3 rounded-xl bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-vatican-gold"></i>活動申請辦法</div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-vatican-gold"></i>場地使用管理規則</div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-vatican-gold"></i>經費補助要點</div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs text-gray-600"><i class="fas fa-gavel mr-2 text-vatican-gold"></i>安全管理規範</div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-3 text-sm">歷史企劃書</h3>
          <div class="space-y-2">
            <div class="p-3 rounded-xl bg-gray-50 flex items-center gap-2 text-xs cursor-pointer hover:bg-mary-blue/5">
              <i class="fas fa-file-alt text-mary-blue"></i><span class="text-gray-600 flex-1">2026春季社團博覽會</span><span class="text-gray-400">03/15</span>
            </div>
            <div class="p-3 rounded-xl bg-gray-50 flex items-center gap-2 text-xs cursor-pointer hover:bg-mary-blue/5">
              <i class="fas fa-file-alt text-mary-blue"></i><span class="text-gray-600 flex-1">聖誕晚會企劃</span><span class="text-gray-400">12/01</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      function generateProposal() {
        const result = document.getElementById('proposal-result');
        const content = document.getElementById('proposal-content');
        result.classList.remove('hidden');
        content.innerHTML = '<div class="animate-pulse"><div class="h-4 bg-gray-200 rounded mb-2 w-3/4"></div><div class="h-4 bg-gray-200 rounded mb-2"></div><div class="h-4 bg-gray-200 rounded mb-2 w-5/6"></div></div>';
        setTimeout(() => {
          content.innerHTML = \`
            <h3 class="font-bold text-mary-blue">攝影社春季外拍活動企劃書</h3>
            <p><strong>一、活動目的：</strong>提升社員攝影技巧，促進社團凝聚力，記錄校園春季美景。</p>
            <p><strong>二、活動時間：</strong>2026年4月20日 (週日) 09:00-17:00</p>
            <p><strong>三、活動地點：</strong>輔仁大學校園及周邊地區</p>
            <p><strong>四、預計參加人數：</strong>50人</p>
            <p><strong>五、活動流程：</strong></p>
            <ul><li>09:00-09:30 集合與器材檢查</li><li>09:30-12:00 分組外拍</li><li>12:00-13:00 午餐休息</li><li>13:00-15:30 自由創作時間</li><li>15:30-17:00 作品分享與講評</li></ul>
            <p><strong>六、預算概估：</strong>NT$15,000（含午餐、保險、獎品）</p>
            <div class="mt-3 p-3 rounded-xl bg-harvest-green/10 text-harvest-green text-xs"><i class="fas fa-check-circle mr-1"></i>AI 預審結果：符合活動申請辦法，無違規項目</div>
          \`;
        }, 2000);
      }
    </script>
  `),

  'certificate': () => moduleLayout('幹部證書自動化', 'fas fa-award', `
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">證書資訊填寫</h2>
          <div class="space-y-4">
            <div><label class="text-xs text-gray-400 block mb-1">社團名稱</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="攝影社"></div>
            <div><label class="text-xs text-gray-400 block mb-1">幹部姓名</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="王大明"></div>
            <div><label class="text-xs text-gray-400 block mb-1">職位</label>
              <select class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm"><option>社長</option><option>副社長</option><option>財務</option><option>公關</option><option>活動</option><option>總務</option></select>
            </div>
            <div><label class="text-xs text-gray-400 block mb-1">任期</label><input class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm" value="114學年度第一學期"></div>
            <button class="w-full btn-gold py-3 rounded-xl"><i class="fas fa-certificate mr-2"></i>生成數位證書</button>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-4 border-b border-gray-100"><h2 class="font-bold text-mary-blue text-sm">證書預覽</h2></div>
          <div class="p-8 bg-gradient-to-br from-gray-50 to-white">
            <div class="border-4 border-double border-vatican-gold rounded-xl p-8 bg-white text-center relative">
              <div class="absolute top-2 right-2 w-8 h-8 rounded-full bg-harvest-green/20 flex items-center justify-center"><i class="fas fa-shield-alt text-harvest-green text-xs"></i></div>
              <div class="text-2xl font-black text-mary-blue mb-1">輔仁大學</div>
              <div class="text-sm text-gray-500 mb-4">天主教輔仁大學課外活動指導組</div>
              <div class="w-16 h-0.5 bg-vatican-gold mx-auto mb-4"></div>
              <div class="text-lg font-bold text-vatican-gold mb-2">幹 部 證 書</div>
              <div class="text-sm text-gray-600 mb-4 leading-relaxed">
                茲證明 <span class="font-bold text-mary-blue border-b-2 border-vatican-gold px-2">王大明</span> 同學<br>
                於 <span class="font-bold">114學年度第一學期</span> 擔任<br>
                <span class="font-bold text-mary-blue">攝影社</span> <span class="font-bold text-vatican-gold">副社長</span> 乙職
              </div>
              <div class="text-xs text-gray-400 mb-2">數位簽章驗證碼: FJU-CERT-2026-001234</div>
              <div class="flex justify-between items-end mt-6 text-xs text-gray-500">
                <div>中華民國 115 年 1 月</div>
                <div class="text-right"><div class="border-t border-gray-300 pt-1">課外活動指導組 組長</div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="flex gap-3">
          <button class="flex-1 btn-blue py-3 rounded-xl text-sm"><i class="fas fa-download mr-2"></i>下載 PDF</button>
          <button class="flex-1 btn-gold py-3 rounded-xl text-sm"><i class="fas fa-qrcode mr-2"></i>驗證 QR Code</button>
        </div>
      </div>
    </div>
  `),

  'equipment': () => moduleLayout('器材盤點與追蹤', 'fas fa-boxes-stacked', `
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex gap-2">
          <button class="px-4 py-2 rounded-xl bg-mary-blue text-white text-sm">全部器材</button>
          <button class="px-4 py-2 rounded-xl bg-gray-100 text-gray-500 text-sm hover:bg-gray-200">已借出</button>
          <button class="px-4 py-2 rounded-xl bg-gray-100 text-gray-500 text-sm hover:bg-gray-200">逾期</button>
        </div>
        <button class="btn-gold px-4 py-2 rounded-xl text-sm"><i class="fas fa-plus mr-2"></i>新增器材</button>
      </div>
      <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">
            <th class="p-4">器材編號</th><th class="p-4">名稱</th><th class="p-4">狀態</th><th class="p-4">借用人</th><th class="p-4">歸還日</th><th class="p-4">操作</th>
          </tr></thead>
          <tbody>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-001</td><td class="p-4 font-medium text-mary-blue">Canon EOS R6</td><td class="p-4"><span class="px-2 py-1 rounded-lg bg-warning-red/10 text-warning-red text-xs">已借出</span></td><td class="p-4">陳同學</td><td class="p-4 text-warning-red font-medium">04/02 ⚠️</td><td class="p-4"><button class="text-xs text-mary-blue hover:underline">提醒</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-002</td><td class="p-4 font-medium text-mary-blue">Sony A7III</td><td class="p-4"><span class="px-2 py-1 rounded-lg bg-harvest-green/10 text-harvest-green text-xs">可借用</span></td><td class="p-4 text-gray-400">-</td><td class="p-4 text-gray-400">-</td><td class="p-4"><button class="text-xs text-mary-blue hover:underline">借出</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-003</td><td class="p-4 font-medium text-mary-blue">投影機 EPSON EB-X51</td><td class="p-4"><span class="px-2 py-1 rounded-lg bg-yellow-100 text-yellow-600 text-xs">維修中</span></td><td class="p-4 text-gray-400">-</td><td class="p-4 text-gray-400">-</td><td class="p-4"><button class="text-xs text-gray-400">-</button></td></tr>
            <tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">EQ-004</td><td class="p-4 font-medium text-mary-blue">無線麥克風組</td><td class="p-4"><span class="px-2 py-1 rounded-lg bg-harvest-green/10 text-harvest-green text-xs">可借用</span></td><td class="p-4 text-gray-400">-</td><td class="p-4 text-gray-400">-</td><td class="p-4"><button class="text-xs text-mary-blue hover:underline">借出</button></td></tr>
          </tbody>
        </table>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-mary-blue mb-3 text-sm"><i class="fas fa-bell mr-2 text-vatican-gold"></i>到期提醒設定 (LINE / SMS)</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
            <input type="checkbox" checked class="accent-harvest-green"><span class="text-sm text-gray-600">歸還前 1 天 LINE 提醒</span>
          </div>
          <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
            <input type="checkbox" checked class="accent-harvest-green"><span class="text-sm text-gray-600">逾期 SMS 通知</span>
          </div>
          <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50">
            <input type="checkbox" class="accent-harvest-green"><span class="text-sm text-gray-600">自動扣除信用積分</span>
          </div>
        </div>
      </div>
    </div>
  `),

  'ai-review': () => moduleLayout('AI 智慧預審', 'fas fa-brain', `
    <div class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4"><i class="fas fa-upload mr-2 text-vatican-gold"></i>上傳申請文件</h2>
          <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-mary-blue transition-colors cursor-pointer">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
            <p class="text-sm text-gray-500">拖曳文件至此或 <span class="text-mary-blue font-medium">點擊上傳</span></p>
            <p class="text-xs text-gray-400 mt-1">支援 PDF、DOC、DOCX（最大 10MB）</p>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4"><i class="fas fa-robot mr-2 text-vatican-gold"></i>AI 預審結果 (RAG)</h2>
          <div class="space-y-3">
            <div class="p-4 rounded-xl bg-harvest-green/5 border border-harvest-green/20">
              <div class="flex items-center gap-2 mb-2"><span class="w-2 h-2 rounded-full bg-harvest-green"></span><span class="text-sm font-medium text-harvest-green">通過 - 活動名稱格式正確</span></div>
              <p class="text-xs text-gray-500 pl-4">符合《活動申請辦法》第3條規定</p>
            </div>
            <div class="p-4 rounded-xl bg-warning-red/5 border border-warning-red/20">
              <div class="flex items-center gap-2 mb-2"><span class="w-2 h-2 rounded-full bg-warning-red"></span><span class="text-sm font-medium text-warning-red">警告 - 預計人數超過場地容量</span></div>
              <p class="text-xs text-gray-500 pl-4">場地容量 80 人，申請 120 人。依《場地使用管理規則》第5條需調整。</p>
            </div>
            <div class="p-4 rounded-xl bg-yellow-50 border border-yellow-200">
              <div class="flex items-center gap-2 mb-2"><span class="w-2 h-2 rounded-full bg-yellow-500"></span><span class="text-sm font-medium text-yellow-600">建議 - 缺少安全計畫</span></div>
              <p class="text-xs text-gray-500 pl-4">50人以上活動建議附安全計畫書。參考《安全管理規範》。</p>
            </div>
          </div>
          <div class="mt-4 p-4 rounded-xl bg-gray-50">
            <h4 class="text-xs font-bold text-gray-500 mb-2">Reasoning JSON</h4>
            <pre class="text-xs text-gray-600 font-mono overflow-x-auto">{
  "allow_next_step": false,
  "risk_level": "Medium",
  "violations": ["crowd_overload"],
  "references": ["場地使用管理規則 第5條"],
  "suggestions": ["調整人數至80人以下", "或申請更大場地"]
}</pre>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-3 text-sm">Dify RAG 知識庫</h3>
          <div class="space-y-2">
            <div class="p-3 rounded-xl bg-gray-50 text-xs flex items-center gap-2"><i class="fas fa-database text-vatican-gold"></i><span class="text-gray-600">活動申請辦法</span><span class="text-gray-300 ml-auto">v2.1</span></div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs flex items-center gap-2"><i class="fas fa-database text-vatican-gold"></i><span class="text-gray-600">場地使用管理規則</span><span class="text-gray-300 ml-auto">v3.0</span></div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs flex items-center gap-2"><i class="fas fa-database text-vatican-gold"></i><span class="text-gray-600">經費補助要點</span><span class="text-gray-300 ml-auto">v1.5</span></div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs flex items-center gap-2"><i class="fas fa-database text-vatican-gold"></i><span class="text-gray-600">器材借用辦法</span><span class="text-gray-300 ml-auto">v2.0</span></div>
            <div class="p-3 rounded-xl bg-gray-50 text-xs flex items-center gap-2"><i class="fas fa-database text-vatican-gold"></i><span class="text-gray-600">安全管理規範</span><span class="text-gray-300 ml-auto">v1.8</span></div>
          </div>
          <p class="text-[10px] text-gray-400 mt-3">Pinecone 向量資料庫 | 5 個法規文件已索引</p>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-3 text-sm">審核統計</h3>
          <canvas id="review-stats" height="200"></canvas>
        </div>
      </div>
    </div>
    <script>
      new Chart(document.getElementById('review-stats'), {
        type: 'doughnut',
        data: { labels: ['通過','待修改','駁回'], datasets: [{ data: [65,25,10], backgroundColor: ['#008000','#DAA520','#FF0000'] }]},
        options: { responsive: true }
      });
    </script>
  `),

  'venue-data': () => moduleLayout('場地活化大數據', 'fas fa-map-location-dot', `
    <div class="space-y-6">
      <div class="grid md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
          <div class="text-2xl font-black text-mary-blue">50</div><div class="text-xs text-gray-400">可用場地</div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
          <div class="text-2xl font-black text-vatican-gold">87%</div><div class="text-xs text-gray-400">使用率</div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
          <div class="text-2xl font-black text-harvest-green">142</div><div class="text-xs text-gray-400">本月預約</div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
          <div class="text-2xl font-black text-warning-red">8</div><div class="text-xs text-gray-400">衝突待協商</div>
        </div>
      </div>
      <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-4 border-b border-gray-100"><h3 class="font-bold text-mary-blue text-sm"><i class="fas fa-fire mr-2 text-vatican-gold"></i>場地使用熱力圖</h3></div>
          <div class="p-4"><canvas id="venue-heatmap" height="250"></canvas></div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="p-4 border-b border-gray-100"><h3 class="font-bold text-mary-blue text-sm"><i class="fas fa-chart-bar mr-2 text-vatican-gold"></i>場地周轉排行</h3></div>
          <div class="p-4"><canvas id="venue-ranking" height="250"></canvas></div>
        </div>
      </div>
      <!-- 3-Stage Reservation Flow -->
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-mary-blue mb-4"><i class="fas fa-chess mr-2 text-vatican-gold"></i>三階段資源調度系統</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="p-4 rounded-xl bg-mary-blue/5 border border-mary-blue/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-lg blue-gradient flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-mary-blue text-sm">自動配對</span></div>
            <p class="text-xs text-gray-500">志願序演算法 Level 1-3 優先配對</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>L1: 校方/處室 (最高優先)</div>
              <div>L2: 社團/自治組織</div>
              <div>L3: 一般社團/小組</div>
            </div>
          </div>
          <div class="p-4 rounded-xl bg-vatican-gold/5 border border-vatican-gold/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-lg gold-gradient flex items-center justify-center text-mary-blue text-sm font-bold">2</div><span class="font-bold text-mary-blue text-sm">自主協商</span></div>
            <p class="text-xs text-gray-500">3/6 分鐘規則 (Redis + OpenAI)</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>3分鐘: GPT-4 介入建議</div>
              <div>6分鐘: 強制關閉、扣10分</div>
              <div>LINE 邀請 + 私下調解</div>
            </div>
          </div>
          <div class="p-4 rounded-xl bg-harvest-green/5 border border-harvest-green/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-lg bg-harvest-green flex items-center justify-center text-white text-sm font-bold">3</div><span class="font-bold text-mary-blue text-sm">官方核准</span></div>
            <p class="text-xs text-gray-500">RAG 法規檢索 + Gatekeeping</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>比對行政流程</div>
              <div>產出 PDF + TOTP QR</div>
              <div>活動核准號 → 場地解鎖</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      new Chart(document.getElementById('venue-heatmap'), {
        type: 'bar',
        data: { labels: ['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00'],
          datasets: [{ label: '使用數', data: [8,25,38,42,15,35,45,40,30,22,12,5], backgroundColor: function(ctx) { const v = ctx.raw; return v > 35 ? '#FF0000' : v > 20 ? '#DAA520' : '#008000'; }}]
        }, options: { responsive: true, plugins: { legend: { display: false }}}
      });
      new Chart(document.getElementById('venue-ranking'), {
        type: 'bar',
        data: { labels: ['中美堂','活動中心','SF 134','草地廣場','會議室A','體育館'],
          datasets: [{ label: '本月使用次數', data: [45,38,35,28,22,18], backgroundColor: '#003153' }]
        }, options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false }}}
      });
    </script>
  `),

  'ai-appeal': () => moduleLayout('AI 申訴摘要生成器', 'fas fa-comments', `
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">申訴案件列表</h2>
          <div class="space-y-3">
            <div class="p-4 rounded-xl border border-warning-red/20 bg-warning-red/5 cursor-pointer hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-mary-blue">場地衝突申訴 #AP-2026-042</span><span class="px-2 py-1 rounded-lg bg-warning-red/10 text-warning-red text-xs">待處理</span></div>
              <p class="text-xs text-gray-500">攝影社與吉他社中美堂使用時段衝突</p>
              <p class="text-xs text-gray-400 mt-1">2026/04/01 提交</p>
            </div>
            <div class="p-4 rounded-xl border border-yellow-200 bg-yellow-50 cursor-pointer hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-mary-blue">器材損壞申訴 #AP-2026-038</span><span class="px-2 py-1 rounded-lg bg-yellow-100 text-yellow-600 text-xs">處理中</span></div>
              <p class="text-xs text-gray-500">借用投影機歸還時發現損壞</p>
              <p class="text-xs text-gray-400 mt-1">2026/03/28 提交</p>
            </div>
            <div class="p-4 rounded-xl border border-harvest-green/20 bg-harvest-green/5 cursor-pointer hover:shadow-md transition-shadow">
              <div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-mary-blue">信用積分申訴 #AP-2026-035</span><span class="px-2 py-1 rounded-lg bg-harvest-green/10 text-harvest-green text-xs">已結案</span></div>
              <p class="text-xs text-gray-500">因系統故障導致簽到失敗扣分</p>
              <p class="text-xs text-gray-400 mt-1">2026/03/25 提交</p>
            </div>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4"><i class="fas fa-robot mr-2 text-vatican-gold"></i>AI 摘要 (WebSocket)</h2>
          <div class="p-4 rounded-xl bg-gray-50 mb-4">
            <div class="flex items-center gap-2 mb-3"><span class="w-2 h-2 rounded-full bg-harvest-green animate-pulse"></span><span class="text-xs text-gray-400">即時生成中...</span></div>
            <div class="text-sm text-gray-600 leading-relaxed">
              <p class="mb-2"><strong>案件摘要：</strong>攝影社（Level 2）與吉他社（Level 2）同時申請 4/15 14:00-17:00 中美堂使用權，系統自動觸發協商機制。</p>
              <p class="mb-2"><strong>協商狀態：</strong>雙方已進入 3/6 分鐘協商階段。GPT-4 已於 3 分鐘時介入，提供三個建議方案。</p>
              <p><strong>AI 建議：</strong>(1) 攝影社改至 SF 134；(2) 時段分割各使用 1.5 小時；(3) 吉他社延至 4/16。</p>
            </div>
          </div>
          <div class="flex gap-2">
            <button class="flex-1 btn-blue py-2 rounded-xl text-sm"><i class="fas fa-check mr-1"></i>採納方案一</button>
            <button class="flex-1 btn-gold py-2 rounded-xl text-sm"><i class="fas fa-gavel mr-1"></i>進入仲裁</button>
          </div>
        </div>
      </div>
    </div>
  `),

  'activity-wall': () => moduleLayout('動態活動牆', 'fas fa-newspaper', `
    <div class="space-y-6">
      <div class="flex items-center gap-4">
        <div class="flex-1 relative">
          <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" placeholder="搜尋活動關鍵字..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-mary-blue outline-none">
        </div>
        <div class="flex gap-2">
          <button class="px-3 py-2 rounded-xl bg-mary-blue text-white text-xs">全部</button>
          <button class="px-3 py-2 rounded-xl bg-gray-100 text-gray-500 text-xs">學術</button>
          <button class="px-3 py-2 rounded-xl bg-gray-100 text-gray-500 text-xs">服務</button>
          <button class="px-3 py-2 rounded-xl bg-gray-100 text-gray-500 text-xs">康樂</button>
          <button class="px-3 py-2 rounded-xl bg-gray-100 text-gray-500 text-xs">體育</button>
          <button class="px-3 py-2 rounded-xl bg-gray-100 text-gray-500 text-xs">藝文</button>
        </div>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        ${[
          { title: '攝影社春季外拍', tag: '藝文', date: '04/20', venue: '校園各處', org: '攝影社', people: 50, color: 'vatican-gold' },
          { title: '吉他社期末成果展', tag: '藝文', date: '04/15', venue: '中美堂', org: '吉他社', people: 200, color: 'mary-blue' },
          { title: '程式設計工作坊', tag: '學術', date: '04/25', venue: 'SF 134', org: '資訊社', people: 40, color: 'harvest-green' },
          { title: '淨灘環保活動', tag: '服務', date: '05/01', venue: '白沙灣', org: '環保社', people: 80, color: 'mary-blue' },
          { title: '校園路跑挑戰', tag: '體育', date: '05/05', venue: '操場', org: '田徑社', people: 150, color: 'vatican-gold' },
          { title: '日語讀書會', tag: '學術', date: '04/10', venue: '圖書館', org: '日文社', people: 25, color: 'harvest-green' },
        ].map(a => `
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover cursor-pointer">
          <div class="h-32 bg-gradient-to-br from-${a.color}/20 to-${a.color}/5 flex items-center justify-center">
            <i class="fas fa-calendar-day text-4xl text-${a.color}/30"></i>
          </div>
          <div class="p-5">
            <div class="flex items-center gap-2 mb-2">
              <span class="px-2 py-0.5 rounded-full bg-${a.color}/10 text-${a.color} text-[10px] font-bold">${a.tag}</span>
              <span class="text-xs text-gray-400">${a.date}</span>
            </div>
            <h3 class="font-bold text-mary-blue mb-1">${a.title}</h3>
            <p class="text-xs text-gray-400 mb-3"><i class="fas fa-map-marker-alt mr-1"></i>${a.venue} · <i class="fas fa-users ml-1 mr-1"></i>${a.people}人</p>
            <div class="flex items-center justify-between">
              <span class="text-xs text-gray-500">${a.org}</span>
              <button class="px-3 py-1 rounded-lg btn-gold text-xs">報名</button>
            </div>
          </div>
        </div>
        `).join('')}
      </div>
    </div>
  `),

  'time-capsule': () => moduleLayout('數位時光膠囊', 'fas fa-clock-rotate-left', `
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4"><i class="fas fa-archive mr-2 text-vatican-gold"></i>封裝文件 (R2 Storage)</h2>
          <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-vatican-gold transition-colors cursor-pointer mb-4">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
            <p class="text-sm text-gray-500">上傳社團移交文件</p>
            <p class="text-xs text-gray-400 mt-1">支援所有檔案格式 (Canvas 自動壓縮圖片)</p>
          </div>
          <div class="space-y-2">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50"><i class="fas fa-file-pdf text-warning-red"></i><span class="text-sm text-gray-600 flex-1">114學年度社團運作報告.pdf</span><span class="text-xs text-gray-400">2.3MB</span></div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50"><i class="fas fa-file-excel text-harvest-green"></i><span class="text-sm text-gray-600 flex-1">財務報表_114.xlsx</span><span class="text-xs text-gray-400">856KB</span></div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50"><i class="fas fa-file-image text-mary-blue"></i><span class="text-sm text-gray-600 flex-1">活動照片集_114.zip</span><span class="text-xs text-gray-400">45MB</span></div>
          </div>
        </div>
        <button class="w-full btn-gold py-3 rounded-xl"><i class="fas fa-lock mr-2"></i>封裝並移交給新幹部</button>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4">歷史封裝紀錄</h2>
          <div class="space-y-3">
            <div class="p-4 rounded-xl bg-gray-50">
              <div class="flex items-center justify-between mb-1"><span class="font-medium text-sm text-mary-blue">113學年度移交檔案</span><span class="text-xs text-gray-400">2025/07</span></div>
              <p class="text-xs text-gray-500">封裝者：李前社長 → 接收者：王大明</p>
              <div class="flex items-center gap-2 mt-2"><span class="px-2 py-0.5 rounded-full bg-harvest-green/10 text-harvest-green text-[10px]">已接收</span><span class="text-[10px] text-gray-400">GPS 浮水印已嵌入</span></div>
            </div>
            <div class="p-4 rounded-xl bg-gray-50">
              <div class="flex items-center justify-between mb-1"><span class="font-medium text-sm text-mary-blue">112學年度移交檔案</span><span class="text-xs text-gray-400">2024/07</span></div>
              <p class="text-xs text-gray-500">封裝者：張前前社長 → 接收者：李前社長</p>
              <div class="flex items-center gap-2 mt-2"><span class="px-2 py-0.5 rounded-full bg-harvest-green/10 text-harvest-green text-[10px]">已接收</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  `),

  '2fa': () => moduleLayout('全方位 2FA 驗證', 'fas fa-lock', `
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-mary-blue mb-4"><i class="fas fa-shield-alt mr-2 text-vatican-gold"></i>雙因子驗證設定</h2>
          <div class="space-y-4">
            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50">
              <div class="flex items-center gap-3"><i class="fas fa-mobile-alt text-lg text-mary-blue"></i><div><div class="text-sm font-medium text-mary-blue">TOTP 驗證器</div><div class="text-xs text-gray-400">Google Authenticator / Authy</div></div></div>
              <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" checked class="sr-only peer"><div class="w-11 h-6 bg-gray-200 peer-checked:bg-harvest-green rounded-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:after:translate-x-full after:transition-all"></div></label>
            </div>
            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50">
              <div class="flex items-center gap-3"><i class="fas fa-sms text-lg text-vatican-gold"></i><div><div class="text-sm font-medium text-mary-blue">SMS 簡訊驗證</div><div class="text-xs text-gray-400">手機號碼: 0912-***-678</div></div></div>
              <label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" class="sr-only peer"><div class="w-11 h-6 bg-gray-200 peer-checked:bg-harvest-green rounded-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:after:translate-x-full after:transition-all"></div></label>
            </div>
          </div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-3 text-sm">需要 2FA 驗證的操作</h3>
          <div class="space-y-2 text-sm text-gray-600">
            <div class="flex items-center gap-2 p-2"><i class="fas fa-check-circle text-harvest-green"></i>仲裁案件處理</div>
            <div class="flex items-center gap-2 p-2"><i class="fas fa-check-circle text-harvest-green"></i>經費核銷操作</div>
            <div class="flex items-center gap-2 p-2"><i class="fas fa-check-circle text-harvest-green"></i>後台管理登入</div>
            <div class="flex items-center gap-2 p-2"><i class="fas fa-check-circle text-harvest-green"></i>信用積分調整</div>
            <div class="flex items-center gap-2 p-2"><i class="fas fa-check-circle text-harvest-green"></i>使用者權限變更</div>
          </div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 text-center">
          <h3 class="font-bold text-mary-blue mb-4">TOTP QR Code</h3>
          <div class="w-48 h-48 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
            <i class="fas fa-qrcode text-6xl text-mary-blue/30"></i>
          </div>
          <p class="text-xs text-gray-400 mb-4">使用 Google Authenticator 掃描</p>
          <div class="p-3 rounded-xl bg-gray-50 font-mono text-xs text-gray-600 tracking-wider">JBSW Y3DP EHPK 3PXP</div>
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-mary-blue mb-3 text-sm">驗證記錄</h3>
          <div class="space-y-2 text-xs">
            <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50"><span class="text-gray-600">後台登入</span><span class="text-harvest-green">成功</span><span class="text-gray-400">04/03 09:15</span></div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50"><span class="text-gray-600">經費核銷</span><span class="text-harvest-green">成功</span><span class="text-gray-400">04/02 14:30</span></div>
            <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50"><span class="text-gray-600">權限變更</span><span class="text-warning-red">失敗</span><span class="text-gray-400">04/01 16:45</span></div>
          </div>
        </div>
      </div>
    </div>
  `),
}

export function modulePages(name: string): string | null {
  const mod = modules[name]
  return mod ? mod() : null
}
