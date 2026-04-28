@extends('layouts.shell')
@section('title', '場地預約')
@php $activePage = 'venue-booking'; @endphp
@section('content')
<div class="space-y-6">
  {{-- Three-Stage Resource Allocation --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h2 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-chess mr-2 text-fju-yellow"></i>三階段資源調度系統</h2>
    <div class="grid md:grid-cols-3 gap-4 mb-4">
      <div class="p-4 rounded-fju bg-fju-blue/5 border-2 border-fju-blue/20">
        <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-fju-blue text-sm">零信任預估 · 先搶先贏</span></div>
        <p class="text-xs text-gray-500 mb-2">三志願制 + 預估人數 + 活動屬性：</p>
        <div class="space-y-1 text-[11px]">
          <div class="flex items-center gap-1 text-fju-blue font-medium"><span class="w-4 text-center font-bold">L1</span>大型活動/課程（>50人/一學期僅1次）</div>
          <div class="flex items-center gap-1 text-fju-yellow font-medium"><span class="w-4 text-center font-bold">L2</span>校方/處室行政單位</div>
          <div class="flex items-center gap-1 text-gray-500"><span class="w-4 text-center font-bold">L3</span>學生社團或自治組織</div>
        </div>
      </div>
      <div class="p-4 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20">
        <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">2</div><span class="font-bold text-fju-blue text-sm">AI 多方協商</span></div>
        <p class="text-xs text-gray-500 mb-2">衝突時透過輔寶 AI 助理協商：</p>
        <div class="space-y-1 text-[11px] text-gray-600">
          <div><i class="fas fa-comments text-fju-yellow mr-1"></i>多人即時聊天（實名制）</div>
          <div><i class="fas fa-envelope text-fju-blue mr-1"></i>Outlook / SMS / LINE 邀請</div>
          <div><i class="fas fa-robot text-fju-green mr-1"></i>AI 三方案 + 信心度</div>
          <div><i class="fas fa-file-pdf text-fju-red mr-1"></i>AI 生成協商紀錄 PDF</div>
        </div>
      </div>
      <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10">
        <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-green flex items-center justify-center text-white text-sm font-bold">3</div><span class="font-bold text-fju-blue text-sm">官方核准</span></div>
        <p class="text-xs text-gray-500 mb-2">通過協調後送審：</p>
        <div class="space-y-1 text-[11px] text-gray-600">
          <div><i class="fas fa-gavel text-fju-blue mr-1"></i>RAG 法規比對（8項法規）</div>
          <div><i class="fas fa-shield-alt text-fju-green mr-1"></i>Gatekeeping 前置檢查</div>
          <div><i class="fas fa-bell text-fju-yellow mr-1"></i>核准後自動 Outlook 通知</div>
        </div>
      </div>
    </div>
    {{-- Cancellation Ladder --}}
    <div class="p-3 rounded-fju bg-fju-bg text-xs text-gray-600 mb-3">
      <b class="text-fju-blue"><i class="fas fa-stairs mr-1 text-fju-yellow"></i>撤銷階梯算法：</b>
      活動前 7 天取消<span class="text-fju-green font-bold">不扣分</span> → 前 3 天取消<span class="text-fju-yellow font-bold">扣 5 分</span> → 前 24 小時取消<span class="text-fju-red font-bold">扣 20 分</span> → 前 2 小時取消<span class="text-fju-red font-bold">惡意放鳥 · 停權審核</span>
    </div>
    <div class="p-3 rounded-fju bg-fju-blue/5 text-xs text-gray-600">
      <b class="text-fju-blue"><i class="fas fa-sitemap mr-1 text-fju-yellow"></i>優先權判斷邏輯：</b>
      送出三志願 → 系統先搶先贏配對 →「<span class="text-fju-blue font-bold">L1</span>: 大型活動/課程」→「<span class="text-fju-yellow font-bold">L2</span>: 校方/處室」→「<span class="font-bold">L3</span>: 社團」→ 同級衝突進入第二階段 AI 協調
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid md:grid-cols-4 gap-4" id="venue-stats"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>

  {{-- Sort/Filter --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <div class="flex gap-2">
      <select id="vb-sort" onchange="renderVenues()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="name-asc">名稱 A→Z</option><option value="name-desc">名稱 Z→A</option>
        <option value="cap-desc">容量 (多→少)</option><option value="cap-asc">容量 (少→多)</option>
      </select>
      <select id="vb-status-filter" onchange="renderVenues()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部狀態</option><option value="available">可預約</option><option value="maintenance">維護中</option>
      </select>
    </div>
    <div class="flex gap-2">
      <input id="vb-search" oninput="renderVenues()" type="text" placeholder="搜尋場地..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-40">
      <button onclick="openBookingModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約（三志願）</button>
    </div>
  </div>

  {{-- Active Conflicts --}}
  <div id="active-conflicts" class="hidden">
    <div class="bg-fju-yellow/10 rounded-fju-lg p-4 border border-fju-yellow/20">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-exclamation-triangle mr-1 text-fju-yellow"></i>進行中的場地衝突</h3>
      <div id="conflict-cards" class="space-y-2"></div>
    </div>
  </div>

  {{-- Venue Table --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>場地清單</h3></div>
    <div id="venue-table-body"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- Conflict Coordination Records --}}
  <div id="conflict-section" class="hidden space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-2">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-handshake mr-2 text-fju-yellow"></i>衝突協調紀錄</h3>
      <div class="flex gap-2 flex-wrap">
        <select id="cf-sort" onchange="renderConflicts()" class="px-3 py-1 rounded-fju border border-gray-200 text-xs">
          <option value="newest">最新優先</option><option value="oldest">最舊優先</option><option value="venue">依場地</option>
        </select>
        <button onclick="filterConflicts('all')" class="cf-f px-3 py-1 rounded-fju bg-fju-blue text-white text-xs" data-f="all">全部</button>
        <button onclick="filterConflicts('pending')" class="cf-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="pending">待處理</button>
        <button onclick="filterConflicts('negotiating')" class="cf-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="negotiating">協商中</button>
        <button onclick="filterConflicts('resolved')" class="cf-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="resolved">已解決</button>
      </div>
    </div>
    <div class="grid md:grid-cols-4 gap-4" id="conflict-stats"></div>
    <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
      <div id="conflict-list"></div>
    </div>
  </div>

  {{-- Booking Modal (3 Wishes) --}}
  <div id="booking-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4"><h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>三志願場地預約</h3><button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button></div>
      <form id="booking-form" onsubmit="submitBooking(event)">
        <div class="space-y-3">
          <div class="p-3 rounded-fju bg-fju-blue/5 border border-fju-blue/10 text-xs text-gray-600"><i class="fas fa-info-circle mr-1 text-fju-blue"></i>請依志願排序選擇三個時段，系統將依先搶先贏原則配對。衝突時段會顯示<span class="text-fju-red font-bold">紅字提醒</span>。</div>
          {{-- Wish 1 --}}
          <div class="p-3 rounded-fju border-2 border-fju-blue/30 bg-fju-blue/5">
            <div class="text-xs font-bold text-fju-blue mb-2"><i class="fas fa-star mr-1 text-fju-yellow"></i>第一志願</div>
            <select id="bk-venue-1" onchange="checkConflict(1)" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mb-2" required></select>
            <div class="grid grid-cols-2 gap-2">
              <input type="date" id="bk-date-1" onchange="checkConflict(1)" class="px-3 py-2 rounded-fju border border-gray-200 text-sm" required>
              <div class="flex gap-1"><input type="time" id="bk-start-1" value="14:00" class="flex-1 px-2 py-2 rounded-fju border border-gray-200 text-sm"><input type="time" id="bk-end-1" value="17:00" class="flex-1 px-2 py-2 rounded-fju border border-gray-200 text-sm"></div>
            </div>
            <div id="conflict-warn-1" class="hidden mt-2 text-xs text-fju-red font-medium p-2 rounded bg-fju-red/5 border border-fju-red/20"><i class="fas fa-exclamation-triangle mr-1"></i><span id="conflict-text-1"></span></div>
          </div>
          {{-- Wish 2 --}}
          <div class="p-3 rounded-fju border border-fju-yellow/30 bg-fju-yellow/5">
            <div class="text-xs font-bold text-fju-yellow mb-2"><i class="fas fa-star-half-alt mr-1"></i>第二志願</div>
            <select id="bk-venue-2" onchange="checkConflict(2)" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mb-2"></select>
            <div class="grid grid-cols-2 gap-2">
              <input type="date" id="bk-date-2" onchange="checkConflict(2)" class="px-3 py-2 rounded-fju border border-gray-200 text-sm">
              <div class="flex gap-1"><input type="time" id="bk-start-2" value="14:00" class="flex-1 px-2 py-2 rounded-fju border border-gray-200 text-sm"><input type="time" id="bk-end-2" value="17:00" class="flex-1 px-2 py-2 rounded-fju border border-gray-200 text-sm"></div>
            </div>
            <div id="conflict-warn-2" class="hidden mt-2 text-xs text-fju-red font-medium p-2 rounded bg-fju-red/5 border border-fju-red/20"><i class="fas fa-exclamation-triangle mr-1"></i><span id="conflict-text-2"></span></div>
          </div>
          {{-- Wish 3 --}}
          <div class="p-3 rounded-fju border border-gray-200 bg-gray-50">
            <div class="text-xs font-bold text-gray-500 mb-2"><i class="far fa-star mr-1"></i>第三志願</div>
            <select id="bk-venue-3" onchange="checkConflict(3)" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mb-2"></select>
            <div class="grid grid-cols-2 gap-2">
              <input type="date" id="bk-date-3" onchange="checkConflict(3)" class="px-3 py-2 rounded-fju border border-gray-200 text-sm">
              <div class="flex gap-1"><input type="time" id="bk-start-3" value="14:00" class="flex-1 px-2 py-2 rounded-fju border border-gray-200 text-sm"><input type="time" id="bk-end-3" value="17:00" class="flex-1 px-2 py-2 rounded-fju border border-gray-200 text-sm"></div>
            </div>
            <div id="conflict-warn-3" class="hidden mt-2 text-xs text-fju-red font-medium p-2 rounded bg-fju-red/5 border border-fju-red/20"><i class="fas fa-exclamation-triangle mr-1"></i><span id="conflict-text-3"></span></div>
          </div>
          {{-- Extra fields --}}
          <div class="grid grid-cols-2 gap-3">
            <div><label class="text-xs text-gray-400 block mb-1">預估規模人數</label><input type="number" id="bk-est-ppl" value="30" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
            <div><label class="text-xs text-gray-400 block mb-1">優先等級</label><select id="bk-priority" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><option value="3">L3 - 一般社團</option><option value="2">L2 - 校方/處室</option><option value="1">L1 - 大型活動/課程</option></select></div>
          </div>
          <div><label class="text-xs text-gray-400 block mb-1">用途說明</label><textarea id="bk-purpose" rows="2" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm" placeholder="請說明借用目的..."></textarea></div>
          <div><label class="text-xs text-gray-400 block mb-1">核准後通知方式</label>
            <div class="flex gap-3 text-xs">
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-outlook" checked> Outlook 信箱</label>
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-sms"> SMS 簡訊</label>
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-line"> LINE Notify</label>
            </div>
          </div>
        </div>
        <div id="booking-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
        <button type="submit" class="w-full btn-yellow py-3 mt-4"><i class="fas fa-paper-plane mr-2"></i>送出三志願預約申請</button>
      </form>
    </div>
  </div>

  {{-- Conflict Detail Modal (multi-person chat) --}}
  <div id="conflict-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg w-full max-w-4xl mx-4 shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-fju-blue">
        <h3 class="font-bold text-white text-lg"><i class="fas fa-handshake mr-2 text-fju-yellow"></i>多方協商 <span id="cd-title" class="text-fju-yellow"></span></h3>
        <button onclick="closeDetailModal()" class="text-white/60 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 space-y-4">
        {{-- Parties --}}
        <div class="grid md:grid-cols-2 gap-4">
          <div class="bg-fju-blue/5 rounded-fju p-4 border border-fju-blue/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">A</div><span class="font-bold text-fju-blue" id="cd-party-a">-</span></div>
            <div class="text-xs text-gray-500" id="cd-info-a"></div>
            <div class="mt-2 flex gap-2">
              <button onclick="sendEmail('a')" class="text-xs px-3 py-1.5 rounded-fju bg-fju-blue text-white hover:bg-fju-blue-light" id="btn-email-a"><i class="fas fa-envelope mr-1"></i>Outlook 通知</button>
            </div>
            <button onclick="confirmResolution('a')" class="mt-2 w-full py-2 rounded-fju text-sm font-bold transition-all" id="btn-confirm-a"><i class="fas fa-check-circle mr-1"></i>確認</button>
          </div>
          <div class="bg-fju-yellow/5 rounded-fju p-4 border border-fju-yellow/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">B</div><span class="font-bold text-fju-blue" id="cd-party-b">-</span></div>
            <div class="text-xs text-gray-500" id="cd-info-b"></div>
            <div class="mt-2 flex gap-2">
              <button onclick="sendEmail('b')" class="text-xs px-3 py-1.5 rounded-fju bg-fju-yellow text-fju-blue hover:bg-fju-yellow-light" id="btn-email-b"><i class="fas fa-envelope mr-1"></i>Outlook 通知</button>
            </div>
            <button onclick="confirmResolution('b')" class="mt-2 w-full py-2 rounded-fju text-sm font-bold transition-all" id="btn-confirm-b"><i class="fas fa-check-circle mr-1"></i>確認</button>
          </div>
        </div>
        {{-- Meta --}}
        <div class="flex flex-wrap gap-3 text-xs text-gray-500">
          <span><i class="fas fa-map-marker-alt mr-1 text-fju-yellow"></i>場地：<b id="cd-venue">-</b></span>
          <span><i class="fas fa-calendar mr-1 text-fju-yellow"></i>日期：<b id="cd-date">-</b></span>
          <span><i class="fas fa-clock mr-1 text-fju-yellow"></i>時段：<b id="cd-slot">-</b></span>
          <span><i class="fas fa-flag mr-1 text-fju-yellow"></i>狀態：<b id="cd-status">-</b></span>
        </div>
        {{-- AI Suggestions --}}
        <div class="bg-gradient-to-r from-fju-blue/5 to-fju-yellow/5 rounded-fju p-4 border border-fju-blue/10">
          <h4 class="text-sm font-bold text-fju-blue mb-2"><i class="fas fa-robot mr-1 text-fju-yellow"></i>AI 協商建議</h4>
          <div id="cd-ai-suggestions" class="space-y-2"></div>
        </div>
        {{-- Multi-person Chat --}}
        <div class="bg-white rounded-fju border border-gray-200">
          <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-fju-blue"><i class="fas fa-comments mr-1 text-fju-yellow"></i>多方即時協商對話</h4>
            <div class="flex items-center gap-2">
              <button onclick="inviteParticipant()" class="text-xs px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green hover:bg-fju-green/20"><i class="fas fa-user-plus mr-1"></i>邀請成員</button>
              <span class="text-[10px] text-gray-400" id="cd-timer"></span>
            </div>
          </div>
          <div id="cd-chat-messages" class="p-4 space-y-3 max-h-64 overflow-y-auto bg-fju-bg"></div>
          <div class="p-3 border-t border-gray-100 flex gap-2">
            <input id="chat-sender-name" type="text" value="Demo User" placeholder="您的姓名" class="px-3 py-2 rounded-fju border border-gray-200 text-xs w-28">
            <input id="chat-input" type="text" placeholder="輸入訊息..." class="flex-1 px-4 py-2 rounded-fju border border-gray-200 text-sm" onkeypress="if(event.key==='Enter')sendChat()">
            <button onclick="sendChat()" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
        {{-- Invite Modal --}}
        <div id="invite-panel" class="hidden p-4 rounded-fju bg-fju-green/5 border border-fju-green/20">
          <h4 class="text-sm font-bold text-fju-blue mb-2"><i class="fas fa-user-plus mr-1 text-fju-green"></i>邀請其他人加入協商</h4>
          <div class="grid grid-cols-3 gap-2 mb-3">
            <button onclick="doInvite('outlook')" class="px-3 py-2 rounded-fju bg-[#0078D4] text-white text-xs"><i class="fas fa-envelope mr-1"></i>Outlook 信箱</button>
            <button onclick="doInvite('sms')" class="px-3 py-2 rounded-fju bg-fju-green text-white text-xs"><i class="fas fa-sms mr-1"></i>SMS 簡訊</button>
            <button onclick="doInvite('line')" class="px-3 py-2 rounded-fju bg-[#06C755] text-white text-xs"><i class="fab fa-line mr-1"></i>LINE</button>
          </div>
          <input id="invite-email" type="text" placeholder="輸入信箱或手機號碼..." class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <button onclick="document.getElementById('invite-panel').classList.add('hidden')" class="mt-2 text-xs text-gray-400 hover:text-gray-600">關閉</button>
        </div>
        {{-- Generate PDF --}}
        <div class="flex gap-2">
          <button onclick="generatePDF()" class="flex-1 px-4 py-2 rounded-fju bg-fju-blue text-white text-sm font-bold hover:bg-fju-blue-light transition-all"><i class="fas fa-file-pdf mr-1"></i>AI 生成協商紀錄 PDF</button>
          <button onclick="notifyAdmin()" class="px-4 py-2 rounded-fju bg-fju-yellow text-fju-blue text-sm font-bold hover:bg-fju-yellow-light transition-all"><i class="fas fa-bell mr-1"></i>通知課指組</button>
        </div>
        {{-- Resolution --}}
        <div id="cd-resolution-status" class="hidden p-4 rounded-fju bg-fju-green/10 border border-fju-green/20 text-center">
          <i class="fas fa-check-circle text-fju-green text-2xl mb-2"></i>
          <p class="font-bold text-fju-green" id="cd-resolution-msg">衝突已解決</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let allVenues=[],allConflicts=[],allReservations=[],currentConflictId=null,currentFilter='all',timerInterval=null,timerSeconds=0;

(function(){
  Promise.all([
    fetch('/api/venues').then(r=>r.json()),
    fetch('/api/conflicts').then(r=>r.json()),
    fetch('/api/reservations').then(r=>r.json())
  ]).then(([vRes,cRes,rRes])=>{
    allVenues=vRes.data;
    allConflicts=cRes.data||[];
    allReservations=rRes.data||[];
    renderVenueStats();
    renderVenues();
    renderActiveConflicts(allConflicts);
    // Fill all venue dropdowns (3 wishes)
    [1,2,3].forEach(i=>{
      const sel=document.getElementById('bk-venue-'+i);
      if(!sel) return;
      sel.innerHTML='<option value="">選擇場地</option>';
      allVenues.filter(v=>v.status==='available').forEach(v=>{sel.innerHTML+='<option value="'+v.id+'">'+v.name+' ('+v.capacity+'人)</option>'});
    });
    if(allConflicts.length>0){
      document.getElementById('conflict-section').classList.remove('hidden');
      renderConflictStats();
      renderConflicts();
    }
  });
})();

function checkConflict(wishNum){
  const venueId=document.getElementById('bk-venue-'+wishNum)?.value;
  const date=document.getElementById('bk-date-'+wishNum)?.value;
  const warnEl=document.getElementById('conflict-warn-'+wishNum);
  const textEl=document.getElementById('conflict-text-'+wishNum);
  if(!venueId||!date){warnEl?.classList.add('hidden');return}
  // Check existing reservations for this venue/date
  const conflicting=allReservations.filter(r=>String(r.venue_id)===String(venueId)&&r.reservation_date===date);
  if(conflicting.length>0){
    const c=conflicting[0];
    textEl.innerHTML=`此場地在 ${date} 已有人預約（${c.user_name||c.club_name||'其他使用者'}，狀態: ${c.status==='confirmed'?'已完成申請流程':'網路登記中'}，時段: ${c.start_time||''}-${c.end_time||''}）。若仍送出，將進入衝突協調流程。`;
    warnEl.classList.remove('hidden');
  } else {
    warnEl.classList.add('hidden');
  }
}

function renderVenueStats(){
  const a=allVenues.filter(v=>v.status==='available').length,m=allVenues.filter(v=>v.status==='maintenance').length;
  document.getElementById('venue-stats').innerHTML=`
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">${allVenues.length}</div><div class="text-xs text-gray-400">總場地數</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">${a}</div><div class="text-xs text-gray-400">可用場地</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">87%</div><div class="text-xs text-gray-400">使用率</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-red">${m}</div><div class="text-xs text-gray-400">維護中</div></div>`;
}

function renderVenues(){
  let data=[...allVenues];
  const search=(document.getElementById('vb-search')?.value||'').toLowerCase();
  const statusF=document.getElementById('vb-status-filter')?.value||'all';
  const sort=document.getElementById('vb-sort')?.value||'name-asc';
  if(search) data=data.filter(v=>v.name.toLowerCase().includes(search)||(v.location||'').toLowerCase().includes(search));
  if(statusF!=='all') data=data.filter(v=>v.status===statusF);
  if(sort==='name-asc') data.sort((a,b)=>a.name.localeCompare(b.name));
  else if(sort==='name-desc') data.sort((a,b)=>b.name.localeCompare(a.name));
  else if(sort==='cap-desc') data.sort((a,b)=>b.capacity-a.capacity);
  else if(sort==='cap-asc') data.sort((a,b)=>a.capacity-b.capacity);
  document.getElementById('venue-table-body').innerHTML='<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">場地</th><th class="p-4">位置</th><th class="p-4">容量</th><th class="p-4">設備</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>'+data.map(v=>'<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">'+v.name+'</td><td class="p-4 text-gray-500">'+(v.location||'')+'</td><td class="p-4">'+v.capacity+'人</td><td class="p-4 text-xs text-gray-400">'+(v.equipment_list||'-')+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju '+(v.status==='available'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+' text-xs">'+(v.status==='available'?'可預約':'維護中')+'</span></td><td class="p-4">'+(v.status==='available'?'<button onclick="quickBook(\''+v.name+'\')" class="btn-yellow px-3 py-1 text-xs">預約</button>':'<span class="text-xs text-gray-400">-</span>')+'</td></tr>').join('')+'</tbody></table>';
}

function renderActiveConflicts(conflicts){
  const active=conflicts.filter(c=>c.status!=='resolved');
  if(active.length===0){document.getElementById('active-conflicts').classList.add('hidden');return}
  document.getElementById('active-conflicts').classList.remove('hidden');
  document.getElementById('conflict-cards').innerHTML=active.map(c=>{
    const sl={'pending':'bg-fju-red/10 text-fju-red','negotiating':'bg-fju-yellow/20 text-fju-yellow'};
    const slLabel={'pending':'待處理','negotiating':'協商中'};
    return `<div class="flex items-center justify-between p-3 bg-white rounded-fju border border-gray-100"><div class="flex items-center gap-3"><i class="fas fa-exclamation-circle text-fju-yellow"></i><div><span class="text-sm font-bold text-fju-blue">${c.party_a||''} vs ${c.party_b||''}</span><span class="text-xs text-gray-400 ml-2">${c.venue_name||''} · ${c.conflict_date||''}</span></div></div><div class="flex items-center gap-2"><span class="px-2 py-1 rounded-fju text-xs ${sl[c.status]||''}">${slLabel[c.status]||c.status}</span><button onclick="openDetail(${c.id})" class="btn-yellow px-3 py-1 text-xs"><i class="fas fa-handshake mr-1"></i>協調</button></div></div>`}).join('');
}

function renderConflictStats(){
  const total=allConflicts.length,pending=allConflicts.filter(c=>c.status==='pending').length,
    negotiating=allConflicts.filter(c=>c.status==='negotiating').length,
    resolved=allConflicts.filter(c=>c.status==='resolved').length;
  document.getElementById('conflict-stats').innerHTML=`
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">${total}</div><div class="text-xs text-gray-400">衝突總數</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-red">${pending}</div><div class="text-xs text-gray-400">待處理</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">${negotiating}</div><div class="text-xs text-gray-400">協商中</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">${resolved}</div><div class="text-xs text-gray-400">已解決</div></div>`;
}

function renderConflicts(){
  let data=currentFilter==='all'?[...allConflicts]:allConflicts.filter(c=>c.status===currentFilter);
  const sort=document.getElementById('cf-sort')?.value||'newest';
  if(sort==='newest') data.sort((a,b)=>b.id-a.id);
  else if(sort==='oldest') data.sort((a,b)=>a.id-b.id);
  else if(sort==='venue') data.sort((a,b)=>(a.venue_name||'').localeCompare(b.venue_name||''));
  if(data.length===0){document.getElementById('conflict-list').innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-check-circle mr-2 text-fju-green"></i>目前無衝突</div>';return}
  const sc={'pending':'bg-fju-red/10 text-fju-red','negotiating':'bg-fju-yellow/20 text-fju-yellow','resolved':'bg-fju-green/10 text-fju-green'};
  const sl={'pending':'待處理','negotiating':'協商中','resolved':'已解決'};
  document.getElementById('conflict-list').innerHTML='<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">#</th><th class="p-4">甲方</th><th class="p-4">乙方</th><th class="p-4">場地</th><th class="p-4">日期</th><th class="p-4">狀態</th><th class="p-4">確認</th><th class="p-4">操作</th></tr></thead><tbody>'+data.map(c=>{
    const cA=c.party_a_confirmed?'<i class="fas fa-check-circle text-fju-green"></i>':'<i class="fas fa-times-circle text-gray-300"></i>';
    const cB=c.party_b_confirmed?'<i class="fas fa-check-circle text-fju-green"></i>':'<i class="fas fa-times-circle text-gray-300"></i>';
    return `<tr class="border-t border-gray-50 hover:bg-gray-50 cursor-pointer" onclick="openDetail(${c.id})"><td class="p-4 text-xs text-gray-400">#${c.id}</td><td class="p-4 font-medium text-fju-blue">${c.party_a||'-'}</td><td class="p-4 font-medium text-fju-blue">${c.party_b||'-'}</td><td class="p-4 text-xs">${c.venue_name||'-'}</td><td class="p-4 text-xs text-gray-500">${c.conflict_date||''}</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs ${sc[c.status]||''}">${sl[c.status]||c.status}</span></td><td class="p-4">${cA} ${cB}</td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">詳情</button></td></tr>`}).join('')+'</tbody></table>';
}

function filterConflicts(f){currentFilter=f;document.querySelectorAll('.cf-f').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});document.querySelector(`.cf-f[data-f="${f}"]`)?.classList.add('bg-fju-blue','text-white');document.querySelector(`.cf-f[data-f="${f}"]`)?.classList.remove('bg-gray-100','text-gray-500');renderConflicts()}

function startTimer(){
  clearInterval(timerInterval);timerSeconds=0;
  timerInterval=setInterval(()=>{
    timerSeconds++;
    const min=Math.floor(timerSeconds/60),sec=timerSeconds%60;
    const el=document.getElementById('cd-timer');
    if(el){el.textContent=`${String(min).padStart(2,'0')}:${String(sec).padStart(2,'0')} / 06:00`;
      if(timerSeconds>=360){el.className='text-xs text-fju-red font-bold animate-pulse';el.textContent='06:00 超時！';clearInterval(timerInterval)}
      else if(timerSeconds>=180){el.className='text-xs text-fju-yellow font-bold'}
      else{el.className='text-[10px] text-gray-400'}
    }
  },1000);
}

function openDetail(id){
  currentConflictId=id;
  fetch(`/api/conflicts/${id}`).then(r=>r.json()).then(res=>{
    const c=res.data;
    document.getElementById('cd-title').textContent=` #${c.id}`;
    document.getElementById('cd-party-a').textContent=c.party_a||'甲方';
    document.getElementById('cd-party-b').textContent=c.party_b||'乙方';
    document.getElementById('cd-venue').textContent=c.venue_name||'-';
    document.getElementById('cd-date').textContent=c.conflict_date||'-';
    document.getElementById('cd-slot').textContent=c.time_slot||'-';
    document.getElementById('cd-status').textContent={'pending':'待處理','negotiating':'協商中','resolved':'已解決'}[c.status]||c.status;
    document.getElementById('cd-info-a').innerHTML=res.reservation_a?`預約日期: ${res.reservation_a.reservation_date}<br>時段: ${res.reservation_a.start_time}-${res.reservation_a.end_time}`:'無關聯預約';
    document.getElementById('cd-info-b').innerHTML=res.reservation_b?`預約日期: ${res.reservation_b.reservation_date}<br>時段: ${res.reservation_b.start_time}-${res.reservation_b.end_time}`:'申請中的預約';
    // Confirm buttons
    updateConfirmBtn('a',c.party_a_confirmed,c.status==='resolved');
    updateConfirmBtn('b',c.party_b_confirmed,c.status==='resolved');
    if(c.status==='resolved'){document.getElementById('cd-resolution-status').classList.remove('hidden');document.getElementById('cd-resolution-msg').textContent='衝突已解決！雙方均已確認。'}
    else{document.getElementById('cd-resolution-status').classList.add('hidden')}
    renderChatMessages(c.chat_messages||[]);
    loadAISuggestions();
    document.getElementById('conflict-detail-modal').classList.remove('hidden');
    if(c.status!=='resolved') startTimer();
  });
}

function updateConfirmBtn(party,confirmed,resolved){
  const btn=document.getElementById('btn-confirm-'+party);
  if(confirmed||resolved){btn.className='mt-2 w-full py-2 rounded-fju text-sm font-bold bg-fju-green/10 text-fju-green cursor-default';btn.innerHTML='<i class="fas fa-check-circle mr-1"></i>'+(confirmed?'已確認':'已解決');btn.disabled=true}
  else{btn.className='mt-2 w-full py-2 rounded-fju text-sm font-bold btn-yellow';btn.innerHTML='<i class="fas fa-check-circle mr-1"></i>確認協商結果';btn.disabled=false}
}

function renderChatMessages(msgs){
  const el=document.getElementById('cd-chat-messages');
  if(!msgs||msgs.length===0){el.innerHTML='<div class="text-center text-gray-400 text-xs py-4"><i class="fas fa-comments mr-1"></i>尚無對話</div>';return}
  const colors=['bg-fju-blue/10 text-fju-blue','bg-fju-yellow/20 text-fju-blue','bg-fju-green/10 text-fju-blue','bg-purple-100 text-purple-800'];
  const senderColorMap={};let colorIdx=0;
  el.innerHTML=msgs.map(m=>{
    if(!senderColorMap[m.sender]) senderColorMap[m.sender]=colors[colorIdx++%colors.length];
    const isFirst=m.party==='a';
    return `<div class="flex ${isFirst?'justify-start':'justify-end'}"><div class="max-w-[70%] px-3 py-2 rounded-fju text-sm ${senderColorMap[m.sender]}"><div class="text-[10px] font-bold mb-1">${m.sender}</div><div>${m.message}</div><div class="text-[9px] text-gray-400 mt-1">${new Date(m.timestamp).toLocaleTimeString('zh-TW')}</div></div></div>`}).join('');
  el.scrollTop=el.scrollHeight;
}

function sendChat(){
  const msg=document.getElementById('chat-input').value.trim();
  if(!msg||!currentConflictId)return;
  const sender=document.getElementById('chat-sender-name').value||'Demo User';
  const c=allConflicts.find(x=>x.id===currentConflictId);
  const party=(sender===c?.party_a)?'a':'b';
  fetch(`/api/conflicts/${currentConflictId}/chat`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({sender,party,message:msg})}).then(r=>r.json()).then(res=>{
    if(res.success){renderChatMessages(res.messages);document.getElementById('chat-input').value=''}
  });
}

function sendEmail(party){
  if(!currentConflictId)return;
  fetch(`/api/conflicts/${currentConflictId}/send-email`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({party})}).then(r=>r.json()).then(res=>{
    if(res.success){alert(res.message)}
  });
}

function confirmResolution(party){
  if(!currentConflictId)return;
  if(!confirm(`確認要確認協商結果嗎？`))return;
  fetch(`/api/conflicts/${currentConflictId}/confirm`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({party})}).then(r=>r.json()).then(res=>{
    alert(res.message);
    updateConfirmBtn(party,true,res.both_confirmed);
    if(res.both_confirmed){
      clearInterval(timerInterval);
      document.getElementById('cd-resolution-status').classList.remove('hidden');
      document.getElementById('cd-resolution-msg').innerHTML='衝突已解決！雙方均已確認，預約已自動更新。<br><a href="/module/calendar?role={{ $role ?? "student" }}" class="inline-flex items-center gap-1 mt-3 btn-yellow px-6 py-2 text-sm"><i class="fas fa-calendar-alt"></i>前往活動申請頁面</a>';
      document.getElementById('cd-status').textContent='已解決';
      fetch('/api/conflicts').then(r=>r.json()).then(res2=>{allConflicts=res2.data||[];renderConflictStats();renderConflicts();renderActiveConflicts(allConflicts)});
    }
  });
}

function loadAISuggestions(){
  document.getElementById('cd-ai-suggestions').innerHTML=[
    {desc:'甲方改至 SF 134 教室（容量50人）',confidence:0.85},
    {desc:'時段分割：各使用一半時間',confidence:0.78},
    {desc:'乙方延後至隔天同時段',confidence:0.72}
  ].map((s,i)=>`<div class="flex items-center gap-3 p-2 rounded-fju ${i===0?'bg-fju-green/10 border border-fju-green/20':'bg-white border border-gray-100'}"><span class="w-6 h-6 rounded-full ${i===0?'bg-fju-green text-white':'bg-gray-200 text-gray-500'} flex items-center justify-center text-xs font-bold">${i+1}</span><span class="flex-1 text-sm">${s.desc}</span><span class="text-xs px-2 py-0.5 rounded-full ${s.confidence>0.8?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow'}">${(s.confidence*100).toFixed(0)}%</span></div>`).join('');
}

function inviteParticipant(){document.getElementById('invite-panel').classList.toggle('hidden')}
function doInvite(method){
  const contact=document.getElementById('invite-email').value;
  alert(`已透過 ${method.toUpperCase()} 邀請 ${contact||'（請輸入聯絡方式）'} 加入協商`);
  document.getElementById('invite-panel').classList.add('hidden');
}

function generatePDF(){
  alert('AI 正在生成協商紀錄 PDF...\n\n包含：\n• 對話者身分（具名）\n• 訊息發送時間\n• 對話內容摘要\n• 協商前後對比表格\n• 違約預授權扣分條款\n\n生成完成後可預覽與下載。');
  setTimeout(()=>{
    const pdfBtn=document.createElement('div');
    pdfBtn.className='p-4 rounded-fju bg-fju-blue/5 border border-fju-blue/10 mt-2';
    pdfBtn.innerHTML='<div class="flex items-center justify-between"><div><i class="fas fa-file-pdf text-fju-red mr-2"></i><b class="text-fju-blue text-sm">協商紀錄_#'+currentConflictId+'.pdf</b><div class="text-[10px] text-gray-400 mt-1">生成時間: '+new Date().toLocaleString('zh-TW')+'</div></div><button class="btn-yellow px-4 py-2 text-xs" onclick="alert(\'PDF 下載中...\')"><i class="fas fa-download mr-1"></i>下載</button></div>';
    document.getElementById('cd-resolution-status').before(pdfBtn);
    document.getElementById('cd-resolution-status').classList.remove('hidden');
  },1500);
}

function notifyAdmin(){
  alert('已將協商紀錄和變更資訊通知課指組審核員！\n通知管道：Outlook 信箱 + 系統通知');
}

function closeDetailModal(){document.getElementById('conflict-detail-modal').classList.add('hidden');clearInterval(timerInterval)}
function openBookingModal(){document.getElementById('booking-modal').classList.remove('hidden')}
function closeBookingModal(){document.getElementById('booking-modal').classList.add('hidden');document.getElementById('booking-result').classList.add('hidden')}
function quickBook(n){openBookingModal()}

function submitBooking(e){
  e.preventDefault();
  const channels=[];
  if(document.getElementById('bk-n-outlook').checked) channels.push('outlook');
  if(document.getElementById('bk-n-sms').checked) channels.push('sms');
  if(document.getElementById('bk-n-line').checked) channels.push('line');
  // Submit first wish
  const d={venue_id:document.getElementById('bk-venue-1').value,venue_name:document.getElementById('bk-venue-1').selectedOptions[0]?.text||'',reservation_date:document.getElementById('bk-date-1').value,start_time:document.getElementById('bk-start-1').value,end_time:document.getElementById('bk-end-1').value,priority_level:parseInt(document.getElementById('bk-priority').value),purpose:document.getElementById('bk-purpose').value,estimated_participants:parseInt(document.getElementById('bk-est-ppl').value)||30,user_id:1,user_name:'Demo User',club_name:'攝影社',notify_channels:channels};
  fetch('/api/reservations',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).then(res=>{
    const b=document.getElementById('booking-result');b.classList.remove('hidden');
    if(res.success){
      b.className='mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green';
      b.innerHTML=`<i class="fas fa-check-circle mr-1"></i>${res.message} (階段：${res.stage})<br><span class="text-xs text-gray-500 mt-1 block">通知管道：${channels.join(', ')||'無'}</span>`;
    } else {
      b.className='mt-3 p-3 rounded-fju text-sm bg-fju-yellow/20 text-fju-blue';
      b.innerHTML=`<i class="fas fa-exclamation-triangle mr-1"></i>${res.message}<br><span class="text-xs text-gray-500">系統將自動嘗試第二、第三志願...</span>${res.conflict_id?'<br><button onclick="openDetail('+res.conflict_id+');closeBookingModal()" class="inline-flex items-center gap-1 mt-2 btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-handshake"></i>進入多方協商</button>':''}`;
    }
  });
}
</script>
@endsection
