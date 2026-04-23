@extends('layouts.shell')
@section('title', '場地預約')
@php $activePage = 'venue-booking'; @endphp
@section('content')
<div class="space-y-6">
  {{-- Three-Stage Resource Allocation with detailed rules --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h2 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-chess mr-2 text-fju-yellow"></i>三階段資源調度系統</h2>
    <div class="grid md:grid-cols-3 gap-4 mb-4">
      <div class="p-4 rounded-fju bg-fju-blue/5 border-2 border-fju-blue/20">
        <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-fju-blue text-sm">志願序配對</span></div>
        <p class="text-xs text-gray-500 mb-2">系統依志願序自動配對，優先權規則如下：</p>
        <div class="space-y-1 text-[11px]">
          <div class="flex items-center gap-1 text-fju-blue font-medium"><span class="w-4 text-center font-bold">L1</span>活動規模/學期頻率 + 教室課程優先</div>
          <div class="flex items-center gap-1 text-fju-yellow font-medium"><span class="w-4 text-center font-bold">L2</span>校方/處室/行政單位申請</div>
          <div class="flex items-center gap-1 text-gray-500"><span class="w-4 text-center font-bold">L3</span>學生社團或自治組織</div>
        </div>
      </div>
      <div class="p-4 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20">
        <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">2</div><span class="font-bold text-fju-blue text-sm">衝突協調</span></div>
        <p class="text-xs text-gray-500 mb-2">衝突發生時自動進入此階段：</p>
        <div class="space-y-1 text-[11px] text-gray-600">
          <div><i class="fas fa-comments text-fju-yellow mr-1"></i>即時聊天對話</div>
          <div><i class="fas fa-envelope text-fju-blue mr-1"></i>Outlook / SMS / LINE 通知</div>
          <div><i class="fas fa-robot text-fju-green mr-1"></i>AI 產出三方案 + 信心度</div>
          <div><i class="fas fa-check-double text-fju-green mr-1"></i>雙方確認 → 自動更新</div>
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
    {{-- Priority Rule Flowchart --}}
    <div class="p-3 rounded-fju bg-fju-bg text-xs text-gray-600">
      <b class="text-fju-blue"><i class="fas fa-sitemap mr-1 text-fju-yellow"></i>優先權判斷邏輯：</b>
      使用者送出志願序 → 系統自動比對「<span class="text-fju-blue font-bold">L1</span>: 大型活動(>50人)/一學期僅1次 + 既有課程教室」→「<span class="text-fju-yellow font-bold">L2</span>: 校方/處室行政用途（申請時間早者優先）」→「<span class="font-bold">L3</span>: 社團/自治組織（依申請時間排序）」→ 若同級衝突則進入第二階段 AI 協調
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid md:grid-cols-4 gap-4" id="venue-stats"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>

  {{-- Sort/Filter Bar --}}
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
      <button onclick="openBookingModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約</button>
    </div>
  </div>

  {{-- Active Conflicts (integrated) --}}
  <div id="active-conflicts" class="hidden">
    <div class="bg-fju-yellow/10 rounded-fju-lg p-4 border border-fju-yellow/20">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-exclamation-triangle mr-1 text-fju-yellow"></i>進行中的場地衝突（第二階段協調）</h3>
      <div id="conflict-cards" class="space-y-2"></div>
    </div>
  </div>

  {{-- Venue Table --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>場地清單</h3></div>
    <div id="venue-table-body"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- ==================== INTEGRATED CONFLICT COORDINATION ==================== --}}
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
      <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>衝突事件列表</h3></div>
      <div id="conflict-list"></div>
    </div>
  </div>

  {{-- Booking Modal --}}
  <div id="booking-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
      <div class="flex items-center justify-between mb-4"><h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>新增場地預約</h3><button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button></div>
      <form id="booking-form" onsubmit="submitBooking(event)">
        <div class="space-y-3">
          <div><label class="text-xs text-gray-400 block mb-1">場地</label><select id="bk-venue" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></select></div>
          <div class="grid grid-cols-2 gap-3"><div><label class="text-xs text-gray-400 block mb-1">日期</label><input type="date" id="bk-date" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div><div><label class="text-xs text-gray-400 block mb-1">優先等級</label><select id="bk-priority" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"><option value="3">L3 - 一般社團</option><option value="2">L2 - 校方/處室</option><option value="1">L1 - 大型活動/課程</option></select></div></div>
          <div class="grid grid-cols-2 gap-3"><div><label class="text-xs text-gray-400 block mb-1">開始時間</label><input type="time" id="bk-start" value="14:00" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div><div><label class="text-xs text-gray-400 block mb-1">結束時間</label><input type="time" id="bk-end" value="17:00" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div></div>
          <div><label class="text-xs text-gray-400 block mb-1">用途說明</label><textarea id="bk-purpose" rows="2" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="請說明借用目的..."></textarea></div>
          <div><label class="text-xs text-gray-400 block mb-1">核准後通知方式</label>
            <div class="flex gap-3 text-xs">
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-outlook" checked> Outlook 信箱</label>
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-sms"> SMS 簡訊</label>
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-line"> LINE Notify</label>
            </div>
          </div>
        </div>
        <div id="booking-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
        <button type="submit" class="w-full btn-yellow py-3 mt-4"><i class="fas fa-paper-plane mr-2"></i>送出預約申請</button>
      </form>
    </div>
  </div>

  {{-- Conflict Detail Modal --}}
  <div id="conflict-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg w-full max-w-4xl mx-4 shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-fju-blue">
        <h3 class="font-bold text-white text-lg"><i class="fas fa-handshake mr-2 text-fju-yellow"></i>衝突協調詳情 <span id="cd-title" class="text-fju-yellow"></span></h3>
        <button onclick="closeDetailModal()" class="text-white/60 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
          <div class="bg-fju-blue/5 rounded-fju p-4 border border-fju-blue/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">A</div><span class="font-bold text-fju-blue" id="cd-party-a">-</span></div>
            <div class="text-xs text-gray-500" id="cd-info-a"></div>
            <div class="mt-2 flex gap-2">
              <button onclick="sendEmail('a')" class="text-xs px-3 py-1.5 rounded-fju bg-fju-blue text-white hover:bg-fju-blue-light" id="btn-email-a"><i class="fas fa-envelope mr-1"></i>Outlook 通知</button>
              <span id="email-status-a" class="text-xs text-fju-green hidden"><i class="fas fa-check-circle mr-1"></i>已通知</span>
            </div>
            <button onclick="confirmResolution('a')" class="mt-2 w-full py-2 rounded-fju text-sm font-bold transition-all" id="btn-confirm-a"><i class="fas fa-check-circle mr-1"></i>甲方確認</button>
          </div>
          <div class="bg-fju-yellow/5 rounded-fju p-4 border border-fju-yellow/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">B</div><span class="font-bold text-fju-blue" id="cd-party-b">-</span></div>
            <div class="text-xs text-gray-500" id="cd-info-b"></div>
            <div class="mt-2 flex gap-2">
              <button onclick="sendEmail('b')" class="text-xs px-3 py-1.5 rounded-fju bg-fju-yellow text-fju-blue hover:bg-fju-yellow-light" id="btn-email-b"><i class="fas fa-envelope mr-1"></i>Outlook 通知</button>
              <span id="email-status-b" class="text-xs text-fju-green hidden"><i class="fas fa-check-circle mr-1"></i>已通知</span>
            </div>
            <button onclick="confirmResolution('b')" class="mt-2 w-full py-2 rounded-fju text-sm font-bold transition-all" id="btn-confirm-b"><i class="fas fa-check-circle mr-1"></i>乙方確認</button>
          </div>
        </div>
        <div class="flex flex-wrap gap-3 text-xs text-gray-500">
          <span><i class="fas fa-map-marker-alt mr-1 text-fju-yellow"></i>場地：<b id="cd-venue">-</b></span>
          <span><i class="fas fa-calendar mr-1 text-fju-yellow"></i>日期：<b id="cd-date">-</b></span>
          <span><i class="fas fa-clock mr-1 text-fju-yellow"></i>時段：<b id="cd-slot">-</b></span>
          <span><i class="fas fa-flag mr-1 text-fju-yellow"></i>狀態：<b id="cd-status">-</b></span>
        </div>
        <div class="bg-gradient-to-r from-fju-blue/5 to-fju-yellow/5 rounded-fju p-4 border border-fju-blue/10">
          <h4 class="text-sm font-bold text-fju-blue mb-2"><i class="fas fa-robot mr-1 text-fju-yellow"></i>AI 協商建議</h4>
          <div id="cd-ai-suggestions" class="space-y-2"></div>
        </div>
        <div class="bg-white rounded-fju border border-gray-200">
          <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-fju-blue"><i class="fas fa-comments mr-1 text-fju-yellow"></i>即時協商對話</h4>
            <span class="text-[10px] text-gray-400" id="cd-timer"></span>
          </div>
          <div id="cd-chat-messages" class="p-4 space-y-3 max-h-48 overflow-y-auto bg-fju-bg"></div>
          <div class="p-3 border-t border-gray-100 flex gap-2">
            <select id="chat-party" class="px-3 py-2 rounded-fju border border-gray-200 text-xs">
              <option value="a">甲方</option><option value="b">乙方</option>
            </select>
            <input id="chat-input" type="text" placeholder="輸入訊息..." class="flex-1 px-4 py-2 rounded-fju border border-gray-200 text-sm" onkeypress="if(event.key==='Enter')sendChat()">
            <button onclick="sendChat()" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>
        <div id="cd-resolution-status" class="hidden p-4 rounded-fju bg-fju-green/10 border border-fju-green/20 text-center">
          <i class="fas fa-check-circle text-fju-green text-2xl mb-2"></i>
          <p class="font-bold text-fju-green" id="cd-resolution-msg">衝突已解決</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let allVenues=[],allConflicts=[],currentConflictId=null,currentFilter='all',timerInterval=null,timerSeconds=0;

(function(){
  Promise.all([
    fetch('/api/venues').then(r=>r.json()),
    fetch('/api/conflicts').then(r=>r.json())
  ]).then(([vRes,cRes])=>{
    allVenues=vRes.data;
    allConflicts=cRes.data||[];
    renderVenueStats();
    renderVenues();
    renderActiveConflicts(allConflicts);
    // Fill venue dropdown
    const sel=document.getElementById('bk-venue');
    allVenues.filter(v=>v.status==='available').forEach(v=>{sel.innerHTML+='<option value="'+v.id+'">'+v.name+' ('+v.capacity+'人)</option>'});
    // Show conflict section if there are conflicts
    if(allConflicts.length>0){
      document.getElementById('conflict-section').classList.remove('hidden');
      renderConflictStats();
      renderConflicts();
    }
  });
})();

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

// ===== Conflict Coordination (Integrated) =====
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

function filterConflicts(f){
  currentFilter=f;
  document.querySelectorAll('.cf-f').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector(`.cf-f[data-f="${f}"]`)?.classList.add('bg-fju-blue','text-white');
  document.querySelector(`.cf-f[data-f="${f}"]`)?.classList.remove('bg-gray-100','text-gray-500');
  renderConflicts();
}

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
    document.getElementById('email-status-a').classList.toggle('hidden',!c.email_sent_a);
    document.getElementById('btn-email-a').classList.toggle('hidden',c.email_sent_a);
    document.getElementById('email-status-b').classList.toggle('hidden',!c.email_sent_b);
    document.getElementById('btn-email-b').classList.toggle('hidden',c.email_sent_b);
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
  else{btn.className='mt-2 w-full py-2 rounded-fju text-sm font-bold btn-yellow';btn.innerHTML='<i class="fas fa-check-circle mr-1"></i>'+(party==='a'?'甲方':'乙方')+'確認';btn.disabled=false}
}

function renderChatMessages(msgs){
  const el=document.getElementById('cd-chat-messages');
  if(!msgs||msgs.length===0){el.innerHTML='<div class="text-center text-gray-400 text-xs py-4"><i class="fas fa-comments mr-1"></i>尚無對話</div>';return}
  el.innerHTML=msgs.map(m=>{const isA=m.party==='a';return `<div class="flex ${isA?'justify-start':'justify-end'}"><div class="max-w-[70%] px-3 py-2 rounded-fju text-sm ${isA?'bg-fju-blue/10 text-fju-blue':'bg-fju-yellow/20 text-fju-blue'}"><div class="text-[10px] font-bold mb-1">${m.sender}</div><div>${m.message}</div><div class="text-[9px] text-gray-400 mt-1">${new Date(m.timestamp).toLocaleTimeString('zh-TW')}</div></div></div>`}).join('');
  el.scrollTop=el.scrollHeight;
}

function sendChat(){
  const msg=document.getElementById('chat-input').value.trim();
  if(!msg||!currentConflictId)return;
  const party=document.getElementById('chat-party').value;
  const c=allConflicts.find(x=>x.id===currentConflictId);
  const sender=party==='a'?(c?.party_a||'甲方'):(c?.party_b||'乙方');
  fetch(`/api/conflicts/${currentConflictId}/chat`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({sender,party,message:msg})}).then(r=>r.json()).then(res=>{
    if(res.success){renderChatMessages(res.messages);document.getElementById('chat-input').value=''}
  });
}

function sendEmail(party){
  if(!currentConflictId)return;
  fetch(`/api/conflicts/${currentConflictId}/send-email`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({party})}).then(r=>r.json()).then(res=>{
    if(res.success){alert(res.message);document.getElementById('email-status-'+party).classList.remove('hidden');document.getElementById('btn-email-'+party).classList.add('hidden')}
  });
}

function confirmResolution(party){
  if(!currentConflictId)return;
  if(!confirm(`確認要以${party==='a'?'甲方':'乙方'}身份確認協商結果嗎？`))return;
  fetch(`/api/conflicts/${currentConflictId}/confirm`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({party})}).then(r=>r.json()).then(res=>{
    alert(res.message);
    updateConfirmBtn(party,true,res.both_confirmed);
    if(res.both_confirmed){
      clearInterval(timerInterval);
      document.getElementById('cd-resolution-status').classList.remove('hidden');
      document.getElementById('cd-resolution-msg').innerHTML='衝突已解決！雙方均已確認，預約已自動更新。<br><a href="/module/calendar?role={{ $role ?? "student" }}" class="inline-flex items-center gap-1 mt-3 btn-yellow px-6 py-2 text-sm"><i class="fas fa-calendar-alt"></i>前往行事曆</a>';
      document.getElementById('cd-status').textContent='已解決';
      // Reload conflicts
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
  const d={venue_id:document.getElementById('bk-venue').value,venue_name:document.getElementById('bk-venue').selectedOptions[0].text,reservation_date:document.getElementById('bk-date').value,start_time:document.getElementById('bk-start').value,end_time:document.getElementById('bk-end').value,priority_level:parseInt(document.getElementById('bk-priority').value),purpose:document.getElementById('bk-purpose').value,user_id:1,user_name:'Demo User',club_name:'攝影社',notify_channels:channels};
  fetch('/api/reservations',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).then(res=>{
    const b=document.getElementById('booking-result');b.classList.remove('hidden');
    if(res.success){
      b.className='mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green';
      b.innerHTML=`<i class="fas fa-check-circle mr-1"></i>${res.message} (階段：${res.stage})<br><span class="text-xs text-gray-500 mt-1 block">通知管道：${channels.join(', ')||'無'}</span>`;
    } else {
      b.className='mt-3 p-3 rounded-fju text-sm bg-fju-yellow/20 text-fju-blue';
      b.innerHTML=`<i class="fas fa-exclamation-triangle mr-1"></i>${res.message}${res.conflict_id?'<br><button onclick="openDetail('+res.conflict_id+');closeBookingModal()" class="inline-flex items-center gap-1 mt-2 btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-handshake"></i>進入衝突協調</button>':''}`;
    }
  });
}
</script>
@endsection
