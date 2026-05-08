@extends('layouts.shell')
@section('title', '租借流程導引')
@php $activePage = 'rag-rental'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-book-open mr-2 text-fju-yellow"></i>租借流程導引 <span class="text-xs font-normal text-gray-400 ml-1">RAG 智慧助理</span></h2>
    <span class="px-3 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs font-medium"><i class="fas fa-circle text-fju-green text-[8px] mr-1 animate-pulse"></i>AI 知識庫已就緒</span>
  </div>

  {{-- Workflow Steps Banner --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 p-5">
    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">三階段租借流程</div>
    <div class="flex items-center gap-0 overflow-x-auto">
      @foreach([
        ['step'=>'01','icon'=>'fa-file-alt','color'=>'fju-blue','title'=>'活動申請','subtitle'=>'取得活動資格','desc'=>'填寫活動企劃書，取得 AA 序號後方可申請場地與設備','badge'=>'必要前置','href'=>'activity-application'],
        ['step'=>'02','icon'=>'fa-map-marker-alt','color'=>'fju-yellow','title'=>'場地預約','subtitle'=>'選擇三個志願','desc'=>'AI 自動排程協調，衝突時進入即時協商流程，取得 VB 序號','badge'=>'需 AA 序號','href'=>'venue-booking'],
        ['step'=>'03','icon'=>'fa-boxes-stacked','color'=>'fju-green','title'=>'設備借用','subtitle'=>'多品項購物車','desc'=>'加入借用清單後一次送出，具專業設備需持有效證照，取得 EL 序號','badge'=>'需 AA 序號','href'=>'equipment'],
      ] as $i => $s)
      <div class="flex items-center {{ $i > 0 ? 'flex-1' : '' }}">
        @if($i > 0)
        <div class="flex-1 h-0.5 bg-gradient-to-r from-gray-200 to-gray-300 mx-2 min-w-[20px]"></div>
        @endif
        <div class="flex-shrink-0 w-48 p-3 rounded-fju-lg border-2 border-{{ $s['color'] }}/20 bg-{{ $s['color'] }}/5 hover:border-{{ $s['color'] }}/40 transition-all cursor-pointer" onclick="window.location='/module/{{ $s['href'] }}?role='+ROLE">
          <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 rounded-fju bg-{{ $s['color'] }} flex items-center justify-center text-white text-xs font-black">{{ $s['step'] }}</div>
            <i class="fas {{ $s['icon'] }} text-{{ $s['color'] }}"></i>
            <span class="text-xs font-bold text-gray-600">{{ $s['title'] }}</span>
          </div>
          <div class="text-[10px] text-gray-400 mb-1">{{ $s['subtitle'] }}</div>
          <p class="text-[10px] text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
          <div class="mt-2"><span class="px-1.5 py-0.5 rounded text-[9px] bg-{{ $s['color'] }}/10 text-{{ $s['color'] }} font-medium">{{ $s['badge'] }}</span></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <div class="grid lg:grid-cols-5 gap-6">
    {{-- LEFT: Active Rentals + Quick Links --}}
    <div class="lg:col-span-2 space-y-4">
      {{-- My Active Records --}}
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-clipboard-list mr-2 text-fju-yellow"></i>我的租借紀錄</h3>
          <button onclick="loadMyRecords()" class="text-xs text-fju-blue hover:text-fju-yellow"><i class="fas fa-sync-alt"></i></button>
        </div>
        <div id="my-records" class="p-4 text-center text-gray-400 text-xs"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>
      </div>

      {{-- Quick Action Links --}}
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 p-4">
        <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">快速操作</div>
        <div class="space-y-2">
          @foreach([
            ['icon'=>'fa-file-alt','color'=>'fju-blue','label'=>'提出新活動申請','href'=>'activity-application'],
            ['icon'=>'fa-map-marker-alt','color'=>'fju-yellow','label'=>'查詢場地可用時段','href'=>'venue-booking'],
            ['icon'=>'fa-boxes-stacked','color'=>'fju-green','label'=>'瀏覽可借設備','href'=>'equipment'],
            ['icon'=>'fa-award','color'=>'purple-600','label'=>'查詢我的設備證照','href'=>'certificate'],
            ['icon'=>'fa-calendar-check','color'=>'fju-blue','label'=>'查看活動行事曆','href'=>'calendar'],
          ] as $link)
          <a href="/module/{{ $link['href'] }}?role={{ $role ?? 'student' }}" class="flex items-center gap-3 p-2.5 rounded-fju hover:bg-fju-bg transition-colors group">
            <div class="w-7 h-7 rounded-fju bg-{{ $link['color'] }}/10 flex items-center justify-center group-hover:bg-{{ $link['color'] }}/20 transition-colors">
              <i class="fas {{ $link['icon'] }} text-{{ $link['color'] }} text-xs"></i>
            </div>
            <span class="text-sm text-gray-600 group-hover:text-fju-blue transition-colors">{{ $link['label'] }}</span>
            <i class="fas fa-chevron-right text-gray-300 text-xs ml-auto group-hover:text-fju-yellow transition-colors"></i>
          </a>
          @endforeach
        </div>
      </div>

      {{-- Required Certificates Notice --}}
      <div class="bg-fju-yellow/5 border border-fju-yellow/20 rounded-fju-lg p-4">
        <div class="flex items-start gap-3">
          <i class="fas fa-exclamation-triangle text-fju-yellow mt-0.5"></i>
          <div>
            <div class="text-xs font-bold text-fju-blue mb-1">專業設備借用需持有效證照</div>
            <div class="text-xs text-gray-500 space-y-1">
              <div><i class="fas fa-dot-circle text-fju-yellow text-[8px] mr-1"></i>投影機：須通過影音設備操作認證</div>
              <div><i class="fas fa-dot-circle text-fju-yellow text-[8px] mr-1"></i>音響：須通過音控系統操作認證</div>
              <div><i class="fas fa-dot-circle text-fju-yellow text-[8px] mr-1"></i>攝影器材：須通過攝影器材使用認證</div>
            </div>
            <a href="/module/certificate?role={{ $role ?? 'student' }}" class="inline-flex items-center gap-1 mt-2 text-xs text-fju-blue hover:text-fju-yellow font-medium"><i class="fas fa-arrow-right"></i>查看我的證照</a>
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT: RAG Q&A Chat --}}
    <div class="lg:col-span-3">
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 flex flex-col" style="height: 600px;">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3">
          <div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center">
            <i class="fas fa-robot text-fju-yellow text-sm"></i>
          </div>
          <div>
            <div class="text-sm font-bold text-fju-blue">租借流程 AI 助理</div>
            <div class="text-[10px] text-fju-green"><i class="fas fa-circle text-[7px] mr-1 animate-pulse"></i>基於校園知識庫 (RAG)</div>
          </div>
          <button onclick="clearChat()" class="ml-auto text-xs text-gray-400 hover:text-fju-red transition-colors" title="清除對話"><i class="fas fa-trash-alt"></i></button>
        </div>

        {{-- Chat Messages --}}
        <div id="rag-chat-messages" class="flex-1 overflow-y-auto p-4 space-y-3">
          <div class="flex items-start gap-2">
            <div class="w-6 h-6 rounded-full bg-fju-blue flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-robot text-fju-yellow text-[10px]"></i></div>
            <div class="bg-fju-bg rounded-fju rounded-tl-none p-3 max-w-xs">
              <p class="text-xs text-gray-700">您好！我是租借流程 AI 助理，基於輔仁大學課外活動知識庫提供服務。</p>
              <p class="text-xs text-gray-700 mt-1">我可以回答關於：<strong>活動申請流程</strong>、<strong>場地預約規則</strong>、<strong>設備借用條件</strong>、<strong>衝突協商機制</strong>等問題。</p>
              <p class="text-xs text-gray-400 mt-2">請輸入您的問題，或點選下方常見問題快速查詢。</p>
            </div>
          </div>
        </div>

        {{-- FAQ Quick Buttons --}}
        <div id="rag-faq-chips" class="px-4 py-2 border-t border-gray-50 flex flex-wrap gap-1.5">
          @foreach(['如何申請場地？','設備借用需要什麼？','衝突怎麼處理？','活動申請審核多久？','場地時段如何查詢？','借完設備要怎麼還？'] as $q)
          <button onclick="sendRagQuestion('{{ $q }}')" class="px-2.5 py-1 rounded-fju bg-fju-blue/5 text-fju-blue text-[10px] hover:bg-fju-blue/15 transition-colors border border-fju-blue/10">{{ $q }}</button>
          @endforeach
        </div>

        {{-- Input --}}
        <div class="px-4 py-3 border-t border-gray-100">
          <div class="flex gap-2">
            <input type="text" id="rag-input" placeholder="詢問租借流程相關問題..." class="flex-1 px-3 py-2 rounded-fju border border-gray-200 text-sm focus:outline-none focus:border-fju-blue/50" onkeydown="if(event.key==='Enter')sendRagMessage()">
            <button onclick="sendRagMessage()" class="px-4 py-2 btn-yellow text-xs rounded-fju"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const ROLE = '{{ $role ?? "student" }}';

// ── RAG Knowledge Base ──────────────────────────────────────────────────────
const ragKB = [
  {
    keys: ['申請場地','場地預約','怎麼訂場地','預約場地','如何申請場地'],
    answer: `<strong>場地預約流程</strong><br>
1. 先完成<strong>活動申請</strong>（取得 AA 序號），否則無法送出場地預約<br>
2. 進入「場地預約」模組，填寫三個志願場地及時段<br>
3. 系統以 AI 演算法自動排程，有衝突時啟動<strong>即時協商</strong>聊天<br>
4. 雙方確認後，課指組發出<strong>官方核准</strong>（VB 序號）<br>
<a href="/module/venue-booking?role=${ROLE}" class="text-fju-blue underline text-xs">→ 前往場地預約</a>`
  },
  {
    keys: ['設備借用','需要什麼','借設備','器材借用','借用條件','借什麼'],
    answer: `<strong>設備借用條件</strong><br>
• 需先有核准的活動申請（AA 序號）<br>
• <strong>一般設備</strong>：桌椅、白板等無需證照<br>
• <strong>專業設備</strong>需持有效證照：<br>
  &nbsp;— 投影機 → 影音設備操作認證<br>
  &nbsp;— 音響 → 音控系統操作認證<br>
  &nbsp;— 攝影器材 → 攝影器材使用認證<br>
• 多品項可加入購物車一次送出（取得 EL 序號）<br>
<a href="/module/equipment?role=${ROLE}" class="text-fju-blue underline text-xs">→ 前往設備借用</a>`
  },
  {
    keys: ['衝突','協商','怎麼處理','場地衝突','時段重疊'],
    answer: `<strong>場地衝突協商機制</strong><br>
1. 系統偵測到時段重疊時，自動建立衝突案件<br>
2. 雙方申請人進入<strong>即時聊天室</strong>協商換時段或換場地<br>
3. 協商有限時（48 小時），逾時由課指組裁決<br>
4. 雙方按下「確認協議」後，系統自動更新預約<br>
5. 若無法達成共識，可提出<strong>申訴</strong><br>
<a href="/module/venue-booking?role=${ROLE}" class="text-fju-blue underline text-xs">→ 查看我的衝突案件</a>`
  },
  {
    keys: ['審核多久','多久審核','幾天','審核時間','活動申請審核'],
    answer: `<strong>活動申請審核時程</strong><br>
• 一般活動：<strong>3–5 個工作天</strong><br>
• 大型活動（500 人以上）：<strong>7–10 個工作天</strong><br>
• AI 預審通常在提交後 <strong>數分鐘</strong>內完成<br>
• 課指組人工審核完成後，系統發送 Email 通知<br>
• 狀態：待審核 → AI 預審中 → 人工審核 → 核准/退回<br>
<a href="/module/activity-application?role=${ROLE}" class="text-fju-blue underline text-xs">→ 查看申請狀態</a>`
  },
  {
    keys: ['時段','查詢時段','場地時段','空檔','有空嗎','哪個時段'],
    answer: `<strong>查詢場地可用時段</strong><br>
• 在「場地預約」頁面點選任一場地，可查看該場地的<strong>每日時段表</strong><br>
• 時段範圍：08:00 – 21:00，每小時為一單位<br>
• 綠色 = 可預約，黃色 = 已預約，紅色 = 維修/停用<br>
• 可查看 30 天內的時段可用性<br>
<a href="/module/venue-booking?role=${ROLE}" class="text-fju-blue underline text-xs">→ 前往查詢時段</a>`
  },
  {
    keys: ['還設備','歸還','設備怎麼還','還器材','歸還設備'],
    answer: `<strong>設備歸還流程</strong><br>
1. 活動結束後，攜帶設備至課指組辦公室<br>
2. 工作人員確認設備狀態，在系統中按「確認歸還」<br>
3. 狀態更新為「已歸還」，借用紀錄結案<br>
4. 若設備有損壞需填寫報修單<br>
<strong>注意</strong>：逾期未歸還將扣除信用分數，影響未來借用資格<br>
<a href="/module/repair?role=${ROLE}" class="text-fju-blue underline text-xs">→ 報修管理</a>`
  },
  {
    keys: ['信用分數','信用','扣分','幾分','黑名單'],
    answer: `<strong>信用積分制度</strong><br>
• 每位使用者起始分數：<strong>100 分</strong><br>
• 逾期未歸還設備：<strong>-10 分/天</strong><br>
• 取消活動未提前通知：<strong>-5 分</strong><br>
• 設備損壞未申報：<strong>-15 分</strong><br>
• 60 分以下：<strong>暫停借用資格</strong>（需申訴）<br>
• 完成志工服務可恢復分數<br>
<a href="/module/appeal?role=${ROLE}" class="text-fju-blue underline text-xs">→ 申訴紀錄</a>`
  },
  {
    keys: ['活動申請','如何申請活動','提出申請','AA序號','申請活動'],
    answer: `<strong>活動申請流程</strong><br>
1. 進入「活動申請」模組，點選「新增申請」<br>
2. 填寫：活動名稱、舉辦日期、預計人數、活動說明<br>
3. AI 自動預審：檢查法規合規性（通常 3 分鐘內）<br>
4. 課指組人工審核後發出 <strong>AA-YYYY-NNNNN 序號</strong><br>
5. 取得 AA 序號後，方可申請場地（VB）與設備（EL）<br>
<a href="/module/activity-application?role=${ROLE}" class="text-fju-blue underline text-xs">→ 前往活動申請</a>`
  },
  {
    keys: ['證照','認證','certificate','怎麼取得證照'],
    answer: `<strong>設備操作認證說明</strong><br>
• 課指組定期舉辦<strong>設備操作認證課程</strong><br>
• 通過測驗後，系統自動核發數位證照（含有效期限）<br>
• 可在「個人中心 → 數位證書」查看持有的證照<br>
• 證照有效期：通常為 <strong>1 年</strong>，需定期更新<br>
<a href="/module/certificate?role=${ROLE}" class="text-fju-blue underline text-xs">→ 查看我的證照</a>`
  },
];

function findRagAnswer(q) {
  const lq = q.toLowerCase();
  for (const item of ragKB) {
    if (item.keys.some(k => lq.includes(k) || k.includes(lq))) return item.answer;
  }
  return null;
}

// ── Chat Functions ──────────────────────────────────────────────────────────
function appendMsg(html, isUser) {
  const container = document.getElementById('rag-chat-messages');
  const div = document.createElement('div');
  div.className = 'flex items-start gap-2 ' + (isUser ? 'flex-row-reverse' : '');
  if (isUser) {
    div.innerHTML = `<div class="w-6 h-6 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-user text-fju-blue text-[10px]"></i></div>
      <div class="bg-fju-blue text-white rounded-fju rounded-tr-none p-3 max-w-xs"><p class="text-xs">${html}</p></div>`;
  } else {
    div.innerHTML = `<div class="w-6 h-6 rounded-full bg-fju-blue flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-robot text-fju-yellow text-[10px]"></i></div>
      <div class="bg-fju-bg rounded-fju rounded-tl-none p-3 max-w-sm"><div class="text-xs text-gray-700 leading-relaxed">${html}</div></div>`;
  }
  container.appendChild(div);
  container.scrollTop = container.scrollHeight;
}

function showTyping() {
  const container = document.getElementById('rag-chat-messages');
  const div = document.createElement('div');
  div.id = 'typing-indicator';
  div.className = 'flex items-start gap-2';
  div.innerHTML = `<div class="w-6 h-6 rounded-full bg-fju-blue flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-robot text-fju-yellow text-[10px]"></i></div>
    <div class="bg-fju-bg rounded-fju rounded-tl-none p-3"><div class="flex gap-1"><span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:0ms"></span><span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:150ms"></span><span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay:300ms"></span></div></div>`;
  container.appendChild(div);
  container.scrollTop = container.scrollHeight;
}

function removeTyping() {
  document.getElementById('typing-indicator')?.remove();
}

function sendRagMessage() {
  const input = document.getElementById('rag-input');
  const q = input.value.trim();
  if (!q) return;
  input.value = '';
  sendRagQuestion(q);
}

function sendRagQuestion(q) {
  appendMsg(q, true);
  document.getElementById('rag-faq-chips').classList.add('hidden');
  showTyping();
  setTimeout(() => {
    removeTyping();
    const local = findRagAnswer(q);
    if (local) {
      appendMsg(local, false);
    } else {
      // Fall back to API
      fetch('/api/ai/chat', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({message: q, context: 'rental_workflow'})
      }).then(r => r.json()).then(res => {
        appendMsg(res.reply || res.message || '抱歉，目前無法取得回應，請稍後再試。', false);
      }).catch(() => {
        appendMsg(`抱歉，我目前無法找到「<strong>${q}</strong>」的相關資訊。<br>您可以嘗試：<a href="/module/faq?role=${ROLE}" class="text-fju-blue underline">查看 FAQ</a> 或聯繫課指組。`, false);
      });
    }
  }, 600 + Math.random() * 400);
}

function clearChat() {
  const container = document.getElementById('rag-chat-messages');
  container.innerHTML = `<div class="flex items-start gap-2">
    <div class="w-6 h-6 rounded-full bg-fju-blue flex items-center justify-center shrink-0 mt-0.5"><i class="fas fa-robot text-fju-yellow text-[10px]"></i></div>
    <div class="bg-fju-bg rounded-fju rounded-tl-none p-3 max-w-xs">
      <p class="text-xs text-gray-700">對話已清除，請重新提問。</p>
    </div>
  </div>`;
  document.getElementById('rag-faq-chips').classList.remove('hidden');
}

// ── My Records ──────────────────────────────────────────────────────────────
function loadMyRecords() {
  const el = document.getElementById('my-records');
  el.innerHTML = '<div class="text-center text-gray-400 text-xs py-4"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>';

  Promise.allSettled([
    fetch('/api/activity-applications').then(r => r.json()),
    fetch('/api/venue-bookings').then(r => r.json()),
    fetch('/api/equipment-loans').then(r => r.json()),
  ]).then(([aa, vb, el2]) => {
    const apps  = (aa.status === 'fulfilled'  ? (aa.value.data  || aa.value  || []) : []).slice(0, 3);
    const books = (vb.status === 'fulfilled'  ? (vb.value.data  || vb.value  || []) : []).slice(0, 3);
    const loans = (el2.status === 'fulfilled' ? (el2.value.data || el2.value || []) : []).slice(0, 3);

    const statusColor = s => ({'pending':'bg-fju-yellow/20 text-fju-yellow','approved':'bg-fju-green/10 text-fju-green','rejected':'bg-fju-red/10 text-fju-red','returned':'bg-gray-100 text-gray-400','active':'bg-fju-blue/10 text-fju-blue'}[s] || 'bg-gray-100 text-gray-400');
    const statusLabel = s => ({'pending':'待審核','approved':'已核准','rejected':'已退回','returned':'已歸還','active':'借用中','under_review':'審核中','negotiating':'協商中','confirmed':'已確認'}[s] || s);

    let html = '';

    if (apps.length) {
      html += `<div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 mt-2 first:mt-0">活動申請</div>`;
      html += apps.map(a => `<div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
        <div class="flex-1 min-w-0"><div class="text-xs font-medium text-gray-700 truncate">${a.activity_name || a.name || '活動申請'}</div>
        <div class="text-[10px] text-gray-400">${a.serial_number || ''}</div></div>
        <span class="px-1.5 py-0.5 rounded text-[9px] ${statusColor(a.status)}">${statusLabel(a.status)}</span>
      </div>`).join('');
    }

    if (books.length) {
      html += `<div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 mt-3">場地預約</div>`;
      html += books.map(b => `<div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
        <div class="flex-1 min-w-0"><div class="text-xs font-medium text-gray-700 truncate">${b.venue_name || b.venue?.name || '場地預約'}</div>
        <div class="text-[10px] text-gray-400">${b.serial_number || b.booking_date || ''}</div></div>
        <span class="px-1.5 py-0.5 rounded text-[9px] ${statusColor(b.status)}">${statusLabel(b.status)}</span>
      </div>`).join('');
    }

    if (loans.length) {
      html += `<div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 mt-3">設備借用</div>`;
      html += loans.map(l => `<div class="flex items-center justify-between py-1.5 border-b border-gray-50 last:border-0">
        <div class="flex-1 min-w-0"><div class="text-xs font-medium text-gray-700 truncate">${l.serial_number || 'EL 借用單'}</div>
        <div class="text-[10px] text-gray-400">${l.borrow_date || ''}</div></div>
        <span class="px-1.5 py-0.5 rounded text-[9px] ${statusColor(l.status)}">${statusLabel(l.status)}</span>
      </div>`).join('');
    }

    if (!html) {
      html = '<div class="text-center text-gray-400 text-xs py-6"><i class="fas fa-inbox text-2xl block mb-2 text-gray-200"></i>目前沒有租借紀錄</div>';
    } else {
      html += `<a href="/module/activity-application?role=${ROLE}" class="block text-center text-xs text-fju-blue hover:text-fju-yellow mt-3 transition-colors">查看全部紀錄 →</a>`;
    }

    el.innerHTML = `<div class="px-4 py-2">${html}</div>`;
  });
}

loadMyRecords();
</script>
@endsection
