@extends('layouts.shell')
@section('title', '系統架構與流程圖')
@php $activePage = 'reports'; @endphp
@section('content')
<div class="space-y-6">
  <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>FJU Smart Hub — 統計報表 & 系統架構</h2>

  {{-- Tabs --}}
  <div class="flex gap-2 flex-wrap">
    <button onclick="showTab('flowchart')" class="tab-btn px-4 py-2 rounded-fju bg-fju-blue text-white text-sm font-bold" data-tab="flowchart"><i class="fas fa-project-diagram mr-1"></i>系統流程圖</button>
    <button onclick="showTab('permissions')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="permissions"><i class="fas fa-user-shield mr-1"></i>權限矩陣</button>
    <button onclick="showTab('features')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="features"><i class="fas fa-list-check mr-1"></i>功能清單</button>
    <button onclick="showTab('rubric')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="rubric"><i class="fas fa-award mr-1"></i>評分對照</button>
  </div>

  {{-- Tab: Flowchart --}}
  <div id="tab-flowchart" class="tab-content">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-project-diagram mr-2 text-fju-yellow"></i>三階段資源調度系統流程圖</h3>
      <div class="min-w-[800px]">
        <svg viewBox="0 0 1100 620" xmlns="http://www.w3.org/2000/svg" class="w-full">
          <defs>
            <marker id="arrow" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#003153"/></marker>
            <marker id="arrow-y" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#DAA520"/></marker>
            <marker id="arrow-g" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#008000"/></marker>
            <marker id="arrow-r" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#FF0000"/></marker>
          </defs>
          {{-- Stage labels --}}
          <rect x="30" y="10" width="300" height="30" rx="15" fill="#003153"/><text x="180" y="30" text-anchor="middle" fill="white" font-size="13" font-weight="bold">第一階段：志願序配對</text>
          <rect x="400" y="10" width="300" height="30" rx="15" fill="#DAA520"/><text x="550" y="30" text-anchor="middle" fill="#003153" font-size="13" font-weight="bold">第二階段：自主協商</text>
          <rect x="770" y="10" width="300" height="30" rx="15" fill="#008000"/><text x="920" y="30" text-anchor="middle" fill="white" font-size="13" font-weight="bold">第三階段：官方審核</text>

          {{-- Stage 1 --}}
          <rect x="80" y="70" rx="12" width="200" height="50" fill="#003153" opacity="0.1" stroke="#003153" stroke-width="2"/><text x="180" y="100" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">使用者提交預約申請</text>
          <line x1="180" y1="120" x2="180" y2="150" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          <rect x="70" y="155" rx="12" width="220" height="50" fill="#003153" opacity="0.05" stroke="#003153" stroke-width="2"/><text x="180" y="178" text-anchor="middle" fill="#003153" font-size="11">志願序演算法 (L1→L3)</text><text x="180" y="193" text-anchor="middle" fill="#003153" font-size="10">L1:校方 L2:幹部 L3:一般</text>
          <line x1="180" y1="205" x2="180" y2="235" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Decision diamond --}}
          <polygon points="180,240 260,280 180,320 100,280" fill="#FFE8B0" stroke="#DAA520" stroke-width="2"/>
          <text x="180" y="283" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">衝突?</text>

          {{-- No conflict --}}
          <line x1="100" y1="280" x2="30" y2="280" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>
          <text x="65" y="273" text-anchor="middle" fill="#008000" font-size="10">無衝突</text>
          <rect x="-35" y="255" rx="12" width="65" height="50" fill="#008000" opacity="0.1" stroke="#008000" stroke-width="2"/><text x="-3" y="283" text-anchor="middle" fill="#008000" font-size="10" font-weight="bold">直接</text><text x="-3" y="296" text-anchor="middle" fill="#008000" font-size="10" font-weight="bold">通過</text>

          {{-- Has conflict → Stage 2 --}}
          <line x1="260" y1="280" x2="400" y2="280" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>
          <text x="330" y="273" text-anchor="middle" fill="#DAA520" font-size="10" font-weight="bold">有衝突</text>

          {{-- Stage 2 --}}
          <rect x="400" y="255" rx="12" width="200" height="50" fill="#DAA520" opacity="0.15" stroke="#DAA520" stroke-width="2"/><text x="500" y="278" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">衝突協調中心</text><text x="500" y="293" text-anchor="middle" fill="#003153" font-size="10">查看衝突對象</text>
          <line x1="500" y1="305" x2="500" y2="340" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          {{-- Coordination sub-steps --}}
          <rect x="395" y="345" rx="8" width="95" height="40" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="443" y="362" text-anchor="middle" fill="#003153" font-size="9"><tspan x="443" dy="0">📧 郵件</tspan><tspan x="443" dy="12">通知</tspan></text>
          <rect x="505" y="345" rx="8" width="95" height="40" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="553" y="362" text-anchor="middle" fill="#003153" font-size="9"><tspan x="553" dy="0">💬 即時</tspan><tspan x="553" dy="12">對話</tspan></text>
          <line x1="500" y1="385" x2="500" y2="410" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          <rect x="415" y="415" rx="12" width="170" height="40" fill="#DAA520" opacity="0.2" stroke="#DAA520" stroke-width="2"/><text x="500" y="433" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🤖 AI 協商建議</text><text x="500" y="446" text-anchor="middle" fill="#003153" font-size="9">3分鐘/6分鐘規則</text>
          <line x1="500" y1="455" x2="500" y2="485" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          {{-- Confirm buttons --}}
          <rect x="405" y="490" rx="12" width="190" height="45" fill="#DAA520" opacity="0.1" stroke="#DAA520" stroke-width="2"/><text x="500" y="510" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">✅ 雙方確認按鈕</text><text x="500" y="524" text-anchor="middle" fill="#003153" font-size="9">甲方確認 + 乙方確認</text>

          {{-- Timeout path --}}
          <line x1="405" y1="512" x2="340" y2="512" stroke="#FF0000" stroke-width="1.5" stroke-dasharray="4" marker-end="url(#arrow-r)"/>
          <rect x="260" y="495" rx="8" width="80" height="35" fill="#FF0000" opacity="0.1" stroke="#FF0000" stroke-width="1.5"/><text x="300" y="510" text-anchor="middle" fill="#FF0000" font-size="9" font-weight="bold">超時</text><text x="300" y="522" text-anchor="middle" fill="#FF0000" font-size="8">扣信用10分</text>

          {{-- Both confirmed → Stage 3 --}}
          <line x1="595" y1="512" x2="770" y2="512" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>
          <text x="682" y="505" text-anchor="middle" fill="#008000" font-size="10" font-weight="bold">雙方確認</text>

          {{-- Stage 3 --}}
          <rect x="770" y="490" rx="12" width="200" height="45" fill="#008000" opacity="0.1" stroke="#008000" stroke-width="2"/><text x="870" y="510" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">RAG 法規比對</text><text x="870" y="524" text-anchor="middle" fill="#003153" font-size="9">+ Gatekeeping 審核</text>
          <line x1="870" y1="535" x2="870" y2="565" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>

          <rect x="800" y="570" rx="12" width="140" height="40" fill="#008000" opacity="0.15" stroke="#008000" stroke-width="2"/><text x="870" y="593" text-anchor="middle" fill="#008000" font-size="12" font-weight="bold">✅ 核准完成</text>

          {{-- System notification --}}
          <rect x="770" y="70" rx="12" width="200" height="80" fill="white" stroke="#003153" stroke-width="1.5"/><text x="870" y="93" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">📋 系統通知</text><text x="870" y="110" text-anchor="middle" fill="#666" font-size="9">• 衝突偵測即時推播</text><text x="870" y="123" text-anchor="middle" fill="#666" font-size="9">• 協商結果更新通知</text><text x="870" y="136" text-anchor="middle" fill="#666" font-size="9">• 審核狀態變更通知</text>
        </svg>
      </div>
    </div>

    {{-- Module Architecture --}}
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 mt-4">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-sitemap mr-2 text-fju-yellow"></i>模組架構圖</h3>
      <div class="grid md:grid-cols-4 gap-3">
        @foreach([
          ['前台模組','fas fa-desktop','bg-fju-blue/10 border-fju-blue/20',['Landing Page','登入頁面','Dashboard','校園地圖']],
          ['核心業務','fas fa-cogs','bg-fju-yellow/10 border-fju-yellow/20',['場地預約','設備借用','活動牆','社團資訊','行事曆','衝突協調']],
          ['AI 智慧模組','fas fa-brain','bg-fju-green/10 border-fju-green/20',['AI 預審','AI 企劃生成','AI 申訴摘要','AI 協商建議','RAG 法規檢索']],
          ['管理與安全','fas fa-shield-alt','bg-purple-50 border-purple-200',['報修管理','申訴記錄','信用積分','數位證書','2FA','通知系統','i18n 多語言']]
        ] as $group)
        <div class="rounded-fju p-4 border {{ $group[2] }}">
          <div class="flex items-center gap-2 mb-3"><i class="{{ $group[1] }} text-fju-blue"></i><span class="font-bold text-fju-blue text-sm">{{ $group[0] }}</span></div>
          <ul class="space-y-1">
            @foreach($group[3] as $item)
            <li class="text-xs text-gray-600 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-yellow"></span>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Tab: Permissions --}}
  <div id="tab-permissions" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-user-shield mr-2 text-fju-yellow"></i>角色權限矩陣</h3>
      <table class="w-full text-sm min-w-[700px]">
        <thead class="bg-fju-blue text-white"><tr>
          <th class="p-3 text-left">功能模組</th>
          <th class="p-3 text-center">Admin<br><span class="text-[10px] text-white/60">課指組</span></th>
          <th class="p-3 text-center">Officer<br><span class="text-[10px] text-white/60">社團幹部</span></th>
          <th class="p-3 text-center">Professor<br><span class="text-[10px] text-white/60">指導教授</span></th>
          <th class="p-3 text-center">Student<br><span class="text-[10px] text-white/60">一般學生</span></th>
          <th class="p-3 text-center">IT<br><span class="text-[10px] text-white/60">資訊中心</span></th>
        </tr></thead>
        <tbody>
          @foreach([
            ['Dashboard 儀表板','✅ 全域','✅ 社團','✅ 指導','✅ 個人','✅ 系統'],
            ['場地預約 / 衝突協調','✅ 核准','✅ 申請','✅ 審閱','✅ 申請','🔧 維護'],
            ['設備借用','✅ 管理','✅ 借還','👁️ 瀏覽','✅ 借還','🔧 維護'],
            ['活動牆','✅ 管理','✅ CRUD','✅ 審閱','✅ 報名','🔧 維護'],
            ['社團資訊','✅ 管理','✅ 編輯','✅ 審閱','👁️ 瀏覽','🔧 維護'],
            ['AI 預審系統','✅ 管理','✅ 使用','✅ 審閱','👁️ 瀏覽','🔧 維護'],
            ['報修管理','✅ 指派','✅ 報修','👁️ 瀏覽','✅ 報修','✅ 處理'],
            ['申訴記錄','✅ 處理','✅ 申訴','✅ 審閱','✅ 申訴','🔧 維護'],
            ['信用積分','✅ 管理','👁️ 瀏覽','👁️ 瀏覽','👁️ 瀏覽','🔧 維護'],
            ['數位證書','✅ 簽發','✅ 申請','✅ 簽章','👁️ 瀏覽','🔧 維護'],
            ['統計報表','✅ 全域','✅ 社團','✅ 指導','👁️ 個人','✅ 系統'],
            ['系統管理 / 2FA','✅ 管理','❌','❌','❌','✅ 管理'],
          ] as $row)
          <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="p-3 font-medium text-fju-blue">{{ $row[0] }}</td>
            @for($i=1;$i<=5;$i++)<td class="p-3 text-center text-xs">{{ $row[$i] }}</td>@endfor
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Tab: Features --}}
  <div id="tab-features" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-list-check mr-2 text-fju-yellow"></i>完整功能清單</h3>
      <div class="grid md:grid-cols-2 gap-4">
        @foreach([
          ['場地預約系統','✅',['三階段資源調度（志願序→協商→審核）','L1/L2/L3 優先權自動配對','衝突偵測與自動建立衝突記錄','即時協商對話（Chat）','郵件/系統通知','AI 協商建議','雙方確認按鈕 → 更新協調頁','3分鐘/6分鐘超時規則']],
          ['社團管理','✅',['社團 CRUD（新增/查詢/刪除）','8大分類篩選','關鍵字搜尋','成員統計','社團/學會分類']],
          ['活動牆','✅',['活動 CRUD','狀態篩選（全部/已核准/待審核）','活動報名/取消報名','AI 預審 + 企劃書生成']],
          ['設備借用','✅',['設備 CRUD','借用/歸還流程','狀態篩選（可借用/已借出/維修中）','關鍵字搜尋']],
          ['行事曆','✅',['行事曆事件 CRUD','EvoCalendar 視覺化','6種事件類型']],
          ['AI 智慧模組','✅',['AI 預審（法規比對）','AI 企劃書自動生成','AI 申訴案件摘要','AI 衝突協商建議','信心指數評估']],
          ['報修管理','✅',['報修單 CRUD','追蹤碼自動產生','狀態管理']],
          ['申訴記錄','✅',['申訴 CRUD','4種申訴類型','AI 摘要生成']],
          ['信用積分','✅',['積分查詢','扣分機制','低於60分強制登出','積分紀錄']],
          ['通知系統','✅',['多管道通知（系統/郵件/LINE）','已讀/未讀管理','衝突自動推播']],
          ['數位證書','✅',['自動生成證書碼','數位簽章','驗證連結']],
          ['多語言 i18n','✅',['繁體中文/簡體中文','英文/日文/韓文']],
          ['校園互動地圖','✅',['Leaflet.js 互動地圖','20+ 建築物標示','無障礙設施圖層','生活/交通圖層切換']],
          ['Dashboard 儀表板','✅',['5種角色專屬儀表板','即時統計數據','Chart.js 圖表','趨勢分析']],
          ['安全防護','✅',['信用積分制度','WAF 防護','2FA 模組']],
          ['衝突協調中心','✅',['衝突清單與狀態管理','即時對話功能','郵件通知','雙方確認按鈕','AI 建議方案','自動更新預約狀態']],
        ] as $feature)
        <div class="rounded-fju p-4 border border-gray-100">
          <div class="flex items-center gap-2 mb-2"><span class="text-sm">{{ $feature[1] }}</span><span class="font-bold text-fju-blue text-sm">{{ $feature[0] }}</span></div>
          <ul class="space-y-1">@foreach($feature[2] as $item)<li class="text-xs text-gray-500 flex items-center gap-1"><span class="w-1 h-1 rounded-full bg-fju-green"></span>{{ $item }}</li>@endforeach</ul>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Tab: Rubric --}}
  <div id="tab-rubric" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-award mr-2 text-fju-yellow"></i>評分標準對照表</h3>
      <div class="space-y-4">
        @foreach([
          ['創新性 Innovation','20%','fas fa-lightbulb','bg-fju-yellow/10 border-fju-yellow/20',['三階段資源調度系統（志願序→AI協商→官方審核）','AI 預審與企劃書自動生成器（Dify RAG 整合）','衝突協調中心：即時對話 + 郵件通知 + 雙方確認','數位時光膠囊（跨屆傳承）','無障礙校園互動地圖']],
          ['實用性 Practicality','50%','fas fa-tools','bg-fju-blue/5 border-fju-blue/10',['完整場地預約流程（衝突偵測→協商→核准）','設備借用管理（借還、提醒、逾期管理）','活動牆（CRUD + 報名 + 篩選）','社團管理（102社團 + 8分類 + 搜尋）','行事曆管理（EvoCalendar）','報修管理（追蹤碼 + 狀態流轉）','申訴系統（AI 摘要 + 類型分類）','信用積分（扣分 + 強制登出）','通知系統（多管道推播）','數位證書自動化']],
          ['技術完善度 Technical','10%','fas fa-code','bg-fju-green/5 border-fju-green/10',['Laravel 12 + PHP 8.2 + MySQL (MariaDB)','RESTful API 設計（30+ endpoints）','Eloquent ORM + Migration + Seeder','E2E 測試 (230 tests, 100% pass)','前後端分離（Blade + AJAX API）','5角色權限矩陣']],
          ['UI/UX 友善度','10%','fas fa-palette','bg-purple-50 border-purple-200',['Tailwind CSS 響應式設計','一致的 FJU 品牌色彩系統','GSAP 動畫效果','FontAwesome 圖標系統','Leaflet.js 互動地圖','Chart.js 數據視覺化','5角色即時切換']],
          ['內容豐富度 Content','10%','fas fa-book','bg-orange-50 border-orange-200',['102 個真實社團數據','10 個校園場地','8 項活動資料','7 位使用者角色','10 項行事曆事件','20+ 校園建築標示','5 國語言 i18n','SDGs 對應（SDG4, SDG5, SDG10 等）']],
        ] as $rubric)
        <div class="rounded-fju-lg p-5 border {{ $rubric[3] }}">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-fju bg-fju-blue flex items-center justify-center text-white"><i class="{{ $rubric[2] }}"></i></div>
            <div><div class="font-bold text-fju-blue">{{ $rubric[0] }}</div><div class="text-fju-yellow font-bold">權重：{{ $rubric[1] }}</div></div>
          </div>
          <div class="grid md:grid-cols-2 gap-1">
            @foreach($rubric[4] as $item)
            <div class="text-xs text-gray-600 flex items-center gap-1.5"><i class="fas fa-check text-fju-green text-[10px]"></i>{{ $item }}</div>
            @endforeach
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<script>
function showTab(tab){
  document.querySelectorAll('.tab-content').forEach(t=>t.classList.add('hidden'));
  document.getElementById('tab-'+tab)?.classList.remove('hidden');
  document.querySelectorAll('.tab-btn').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector(`.tab-btn[data-tab="${tab}"]`)?.classList.add('bg-fju-blue','text-white');
  document.querySelector(`.tab-btn[data-tab="${tab}"]`)?.classList.remove('bg-gray-100','text-gray-500');
}
</script>
@endsection
