@extends('layouts.shell')
@section('title', '場地預約')
@php $activePage = 'venue-booking'; @endphp
@section('content')
<div class="space-y-6">
  {{-- Task 3: Simplified single-preference booking info --}}
  <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 flex items-start gap-3">
    <div class="w-9 h-9 rounded-fju bg-fju-blue flex items-center justify-center shrink-0">
      <i class="fas fa-bolt text-fju-yellow"></i>
    </div>
    <div>
      <div class="font-bold text-fju-blue text-sm">先搶先贏制場地預約</div>
      <div class="text-xs text-gray-500 mt-1">送出預約申請後，系統依伺服器收到請求的時間戳記決定優先順序。若所選時段已被他人預約，系統將直接拒絕並通知，請另選時段。</div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid md:grid-cols-4 gap-4" id="venue-stats"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>

  {{-- View Mode Tabs --}}
  <div class="flex gap-2">
    <button onclick="setVbView('list')" id="vb-tab-list" class="vb-tab px-4 py-1.5 rounded-fju text-xs bg-fju-blue text-white font-medium">原始清單</button>
    <button onclick="setVbView('rank')" id="vb-tab-rank" class="vb-tab px-4 py-1.5 rounded-fju text-xs bg-gray-100 text-gray-500 font-medium"><i class="fas fa-trophy mr-1 text-fju-yellow"></i>熱門借用</button>
    <button onclick="setVbView('history')" id="vb-tab-history" class="vb-tab px-4 py-1.5 rounded-fju text-xs bg-gray-100 text-gray-500 font-medium"><i class="fas fa-history mr-1"></i>過往紀錄</button>
  </div>

  {{-- Sort/Filter --}}
  <div id="vb-filter-bar" class="flex items-center justify-between flex-wrap gap-2">
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
      @if(!in_array($role ?? 'student', ['admin']))
      <button onclick="openBookingModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約</button>
      @endif
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
  <div id="vb-main-list" class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>場地清單</h3></div>
    <div id="venue-table-body"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- Ranking View --}}
  <div id="vb-rank-view" class="hidden bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-trophy mr-2 text-fju-yellow"></i>熱門場地借用排行榜（近 3 個月）</h3></div>
    <div id="vb-rank-body" class="p-4 space-y-2"></div>
  </div>

  {{-- History View --}}
  <div id="vb-history-view" class="hidden bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-history mr-2 text-fju-yellow"></i>過往借用紀錄</h3></div>
    <div id="vb-history-body"></div>
  </div>

  {{-- Admin Review Panel (課指組審核場地預約) --}}
  <div id="admin-vb-panel" class="hidden space-y-4">
    <div class="flex items-center justify-between flex-wrap gap-2">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-clipboard-check mr-2 text-fju-yellow"></i>場地預約申請審核</h3>
      <div class="flex gap-1 flex-wrap">
        <button onclick="filterVbAdmin('all')"       class="vba-f px-3 py-1 rounded-fju bg-fju-blue text-white text-xs" data-f="all">全部</button>
        <button onclick="filterVbAdmin('pending')"   class="vba-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="pending">待審核</button>
        <button onclick="filterVbAdmin('approved')"  class="vba-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="approved">已核准</button>
        <button onclick="filterVbAdmin('rejected')"  class="vba-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="rejected">已拒絕</button>
        <button onclick="filterVbAdmin('cancelled')" class="vba-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="cancelled">已取消</button>
      </div>
    </div>
    <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
      <div id="admin-vb-table"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
    </div>
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

  {{-- Admin Venue Booking Review Modal --}}
  <div id="vb-review-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-map-marker-alt mr-2 text-fju-yellow"></i>場地預約詳情</h3>
        <button onclick="closeVbReviewModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
      </div>
      <div id="vb-review-detail" class="space-y-3 text-sm"></div>
      <div id="vb-review-actions" class="mt-4 space-y-2"></div>
    </div>
  </div>

  {{-- Task 3: Booking Modal — single preference, first-come-first-served --}}
  <div id="booking-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>新增預約</h3>
        <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
      </div>
      <form id="booking-form" onsubmit="submitBooking(event)">
        <div class="space-y-3">
          <div class="p-3 rounded-fju bg-fju-blue/5 border border-fju-blue/10 text-xs text-gray-600">
            <i class="fas fa-bolt mr-1 text-fju-yellow"></i>先搶先贏：系統以收到請求的時間為準，若時段已被預約將直接拒絕，請另選時段。
          </div>
          <div>
            <label class="text-xs text-gray-400 block mb-1">單位代碼 <span class="text-red-400">*</span></label>
            <div class="relative">
              <input id="uc-input-vb" type="text" placeholder="輸入代碼或名稱篩選..." autocomplete="off"
                class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
              <input id="uc-val-vb" type="hidden">
              <div id="uc-drop-vb" class="hidden absolute z-50 w-full bg-white border border-gray-200 rounded-fju shadow-lg max-h-48 overflow-y-auto"></div>
            </div>
          </div>
          {{-- Single preference --}}
          <div>
            <label class="text-xs text-gray-400 block mb-1">選擇場地 <span class="text-red-400">*</span></label>
            <select id="bk-venue-1" onchange="checkConflict()" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm" required></select>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-gray-400 block mb-1">預約日期 <span class="text-red-400">*</span></label>
              <input type="date" id="bk-date-1" onchange="checkConflict()" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required>
            </div>
            <div>
              <label class="text-xs text-gray-400 block mb-1">預估人數</label>
              <input type="number" id="bk-est-ppl" value="30" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
            </div>
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="text-xs text-gray-400 block mb-1">開始時間 <span class="text-red-400">*</span></label>
              <input type="time" id="bk-start-1" value="14:00" onchange="checkConflict()" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required>
            </div>
            <div>
              <label class="text-xs text-gray-400 block mb-1">結束時間 <span class="text-red-400">*</span></label>
              <input type="time" id="bk-end-1" value="17:00" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required>
            </div>
          </div>
          {{-- Conflict warning (blocking) --}}
          <div id="conflict-warn-1" class="hidden p-3 rounded-fju bg-red-50 border border-red-200 text-xs text-red-600 font-medium">
            <i class="fas fa-times-circle mr-1"></i><span id="conflict-text-1"></span>
            <div class="mt-1 text-red-400">此時段已被他人優先預約，依先搶先贏原則無法送出，請選擇其他時段。</div>
          </div>
          <div>
            <label class="text-xs text-gray-400 block mb-1">活動類型</label>
            <select id="bk-priority" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
              <option value="3">一般社團活動</option>
              <option value="2">行政/課程用途</option>
              <option value="1">大型活動（>50人）</option>
            </select>
          </div>
          <div>
            <label class="text-xs text-gray-400 block mb-1">用途說明</label>
            <textarea id="bk-purpose" rows="2" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm resize-none" placeholder="請說明借用目的..."></textarea>
          </div>
          <div>
            <label class="text-xs text-gray-400 block mb-1">核准後通知方式</label>
            <div class="flex gap-3 text-xs">
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-outlook" checked> Outlook 信箱</label>
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-sms"> SMS 簡訊</label>
              <label class="flex items-center gap-1"><input type="checkbox" id="bk-n-line"> LINE Notify</label>
            </div>
          </div>
        </div>
        <div id="booking-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
        <button type="submit" id="booking-submit-btn" class="w-full btn-yellow py-3 mt-4">
          <i class="fas fa-paper-plane mr-2"></i>送出預約申請
        </button>
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
const IS_ADMIN = {{ in_array($role ?? 'student', ['admin']) ? 'true' : 'false' }};
let allVenues=[],allConflicts=[],allReservations=[],currentConflictId=null,currentFilter='all',timerInterval=null,timerSeconds=0;
let currentVbView = 'list';
let allVbBookings=[], currentVbAdminFilter='all', currentVbReviewId=null;

function setVbView(v) {
  currentVbView = v;
  document.querySelectorAll('.vb-tab').forEach(b => {
    b.classList.remove('bg-fju-blue','text-white'); b.classList.add('bg-gray-100','text-gray-500');
  });
  const tab = document.getElementById('vb-tab-'+v);
  tab?.classList.add('bg-fju-blue','text-white'); tab?.classList.remove('bg-gray-100','text-gray-500');
  document.getElementById('vb-filter-bar')?.classList.toggle('hidden', v !== 'list');
  document.getElementById('vb-main-list')?.classList.toggle('hidden', v !== 'list');
  document.getElementById('vb-rank-view')?.classList.toggle('hidden', v !== 'rank');
  document.getElementById('vb-history-view')?.classList.toggle('hidden', v !== 'history');
  if (v === 'rank') renderVbRanking();
  if (v === 'history') renderVbHistory();
}

function renderVbRanking() {
  const cutoff = new Date(); cutoff.setMonth(cutoff.getMonth()-3);
  const cutoffStr = cutoff.toISOString().split('T')[0];
  const counts = {}, names = {};
  allReservations.filter(r => (r.reservation_date||'') >= cutoffStr).forEach(r => {
    const key = r.venue_id || r.venue_name || '?';
    counts[key] = (counts[key]||0) + 1;
    names[key] = r.venue_name || '場地 #'+r.venue_id;
  });
  const ranked = Object.keys(counts).map(k=>({id:k,name:names[k],count:counts[k]})).sort((a,b)=>b.count-a.count).slice(0,10);
  const el = document.getElementById('vb-rank-body');
  if (!ranked.length) { el.innerHTML='<div class="p-8 text-center text-gray-400">近 3 個月內暫無借用紀錄</div>'; return; }
  const medals=['🥇','🥈','🥉'];
  const medalBg=['bg-yellow-50 border-yellow-200','bg-gray-50 border-gray-300','bg-orange-50 border-orange-200'];
  el.innerHTML = ranked.map((item,i)=>{
    const cls = i<3 ? medalBg[i] : 'bg-white border-gray-100';
    const rank = i<3 ? `<span class="text-2xl">${medals[i]}</span>` : `<span class="text-xl font-black text-gray-300">#${i+1}</span>`;
    return `<div class="flex items-center justify-between p-4 border rounded-fju ${cls}">
      <div class="flex items-center gap-4">
        <div class="w-12 text-center shrink-0">${rank}</div>
        <div><div class="font-bold text-fju-blue">${item.name}</div><div class="text-xs text-gray-400">場地編號 ${item.id}</div></div>
      </div>
      <div class="text-right shrink-0">
        <div class="text-2xl font-black text-fju-blue">${item.count}</div>
        <div class="text-xs text-gray-400">次預約</div>
      </div>
    </div>`;
  }).join('');
}

function renderVbHistory() {
  const sorted = [...allReservations].sort((a,b)=>{
    const da=a.reservation_date||'', db=b.reservation_date||'';
    return db.localeCompare(da)||(b.id-a.id);
  });
  const el = document.getElementById('vb-history-body');
  if (!sorted.length) { el.innerHTML='<div class="p-8 text-center text-gray-400">暫無歷史紀錄</div>'; return; }
  const statusLabel = s=>({pending:'待審核',approved:'已核准',rejected:'已拒絕',cancelled:'已取消',active:'進行中',completed:'已完成'}[s]||s||'—');
  const statusCls = s=>s==='approved'?'bg-green-100 text-green-700':s==='pending'?'bg-yellow-100 text-yellow-700':s==='rejected'?'bg-red-100 text-red-600':'bg-gray-100 text-gray-500';
  el.innerHTML = '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">借用人</th><th class="p-4">場地</th><th class="p-4">借用日期</th><th class="p-4">時段</th><th class="p-4">狀態</th></tr></thead><tbody>'+
    sorted.map(r=>`<tr class="border-t border-gray-50 hover:bg-gray-50">
      <td class="p-4 font-medium text-fju-blue">${r.user_name||r.club_name||'—'}</td>
      <td class="p-4 text-xs">${r.venue_name||'—'}</td>
      <td class="p-4 text-xs text-gray-500">${r.reservation_date||'—'}</td>
      <td class="p-4 text-xs text-gray-400">${r.start_time||'—'}–${r.end_time||'—'}</td>
      <td class="p-4"><span class="px-2 py-1 rounded-fju text-xs ${statusCls(r.status)}">${statusLabel(r.status)}</span></td>
    </tr>`).join('')+'</tbody></table>';
}

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
    // Task 3: Fill single venue dropdown only
    const sel=document.getElementById('bk-venue-1');
    if(sel){
      sel.innerHTML='<option value="">選擇場地</option>';
      allVenues.filter(v=>v.status==='available').forEach(v=>{sel.innerHTML+='<option value="'+v.id+'">'+v.name+' ('+v.capacity+'人)</option>'});
    }
    if(allConflicts.length>0){
      document.getElementById('conflict-section').classList.remove('hidden');
      renderConflictStats();
      renderConflicts();
    }
    if(IS_ADMIN) loadVbBookings();
  });
})();

// Task 3: Single-preference conflict check — blocks submission if slot is taken
function checkConflict(){
  const venueId=document.getElementById('bk-venue-1')?.value;
  const date=document.getElementById('bk-date-1')?.value;
  const start=document.getElementById('bk-start-1')?.value;
  const end=document.getElementById('bk-end-1')?.value;
  const warnEl=document.getElementById('conflict-warn-1');
  const textEl=document.getElementById('conflict-text-1');
  const submitBtn=document.getElementById('booking-submit-btn');
  if(!venueId||!date){warnEl?.classList.add('hidden');if(submitBtn)submitBtn.disabled=false;return}
  // Overlap check: existing reservations for this venue/date that overlap the chosen time
  const conflicting=allReservations.filter(r=>{
    if(String(r.venue_id)!==String(venueId)||r.reservation_date!==date) return false;
    if(!start||!end) return true; // date-only check
    const rs=r.start_time||'00:00', re=r.end_time||'23:59';
    return start<re && end>rs; // overlap
  });
  if(conflicting.length>0){
    const c=conflicting[0];
    textEl.innerHTML=`${c.user_name||c.club_name||'其他使用者'} 已於 ${c.start_time||'—'}–${c.end_time||'—'} 預約此場地`;
    warnEl.classList.remove('hidden');
    if(submitBtn){submitBtn.disabled=true;submitBtn.className='w-full py-3 mt-4 rounded-fju bg-gray-200 text-gray-400 text-sm font-bold cursor-not-allowed';}
  } else {
    warnEl.classList.add('hidden');
    if(submitBtn){submitBtn.disabled=false;submitBtn.className='w-full btn-yellow py-3 mt-4';}
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
  const actionCol = IS_ADMIN ? '' : '<th class="p-4">操作</th>';
  document.getElementById('venue-table-body').innerHTML='<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">場地</th><th class="p-4">位置</th><th class="p-4">容量</th><th class="p-4">設備</th><th class="p-4">狀態</th>'+actionCol+'</tr></thead><tbody>'+data.map(v=>{
    const actionCell = IS_ADMIN ? '' : '<td class="p-4">'+(v.status==='available'?'<button onclick="quickBook(\''+v.name+'\')" class="btn-yellow px-3 py-1 text-xs">預約</button>':'<span class="text-xs text-gray-400">-</span>')+'</td>';
    return '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">'+v.name+'</td><td class="p-4 text-gray-500">'+(v.location||'')+'</td><td class="p-4">'+v.capacity+'人</td><td class="p-4 text-xs text-gray-400">'+(v.equipment_list||'-')+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju '+(v.status==='available'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+' text-xs">'+(v.status==='available'?'可預約':'維護中')+'</span></td>'+actionCell+'</tr>';
  }).join('')+'</tbody></table>';
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
function openBookingModal(){
  if (IS_ADMIN) return;
  resetUnitCombobox('vb');
  initUnitCombobox('vb');
  document.getElementById('booking-modal').classList.remove('hidden');
}
function closeBookingModal(){document.getElementById('booking-modal').classList.add('hidden');document.getElementById('booking-result').classList.add('hidden')}
function quickBook(n){ if (!IS_ADMIN) openBookingModal(); }

// Task 3: Submit single preference — first-come-first-served
// Admin venue booking review
function loadVbBookings(){
  fetch('/api/venue-bookings?per_page=100').then(r=>r.json()).then(res=>{
    allVbBookings=res.data||[];
    renderAdminVbPanel();
    document.getElementById('admin-vb-panel').classList.remove('hidden');
  });
}

function filterVbAdmin(f){
  currentVbAdminFilter=f;
  document.querySelectorAll('.vba-f').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector(`.vba-f[data-f="${f}"]`)?.classList.add('bg-fju-blue','text-white');
  document.querySelector(`.vba-f[data-f="${f}"]`)?.classList.remove('bg-gray-100','text-gray-500');
  renderAdminVbPanel();
}

function renderAdminVbPanel(){
  const data=currentVbAdminFilter==='all'?allVbBookings:allVbBookings.filter(b=>b.status===currentVbAdminFilter);
  const el=document.getElementById('admin-vb-table');
  if(!data.length){el.innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-inbox mr-2"></i>目前無申請紀錄</div>';return}
  const sLabel=s=>({pending:'待審核',approved:'已核准',rejected:'已拒絕',cancelled:'已取消',conflicted:'衝突'}[s]||s);
  const sCls=s=>s==='approved'?'bg-green-100 text-green-700':s==='pending'?'bg-yellow-100 text-yellow-700':s==='rejected'?'bg-red-100 text-red-600':'bg-gray-100 text-gray-500';
  el.innerHTML='<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">序號</th><th class="p-4">申請人</th><th class="p-4">場地</th><th class="p-4">日期</th><th class="p-4">時段</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>'+
    data.map(b=>`<tr class="border-t border-gray-50 hover:bg-gray-50">
      <td class="p-4 text-xs text-gray-400 font-mono">${b.serial_no||'#'+b.id}</td>
      <td class="p-4 font-medium text-fju-blue">${(b.applicant&&b.applicant.name)||'—'}<div class="text-xs text-gray-400">${b.unit_code||''}</div></td>
      <td class="p-4 text-xs">${(b.venue&&b.venue.name)||'—'}</td>
      <td class="p-4 text-xs text-gray-500">${b.booking_date||'—'}</td>
      <td class="p-4 text-xs text-gray-400">${b.start_time||'—'}–${b.end_time||'—'}</td>
      <td class="p-4"><span class="px-2 py-1 rounded-fju text-xs ${sCls(b.status)}">${sLabel(b.status)}</span></td>
      <td class="p-4"><button onclick="openVbReview(${b.id})" class="btn-yellow px-3 py-1 text-xs"><i class="fas fa-search mr-1"></i>詳情</button></td>
    </tr>`).join('')+'</tbody></table>';
}

function openVbReview(id){
  if(!IS_ADMIN)return;
  fetch(`/api/venue-bookings/${id}`).then(r=>r.json()).then(res=>{
    const b=res.data;
    currentVbReviewId=id;
    const sLabel=s=>({pending:'待審核',approved:'已核准',rejected:'已拒絕',cancelled:'已取消',conflicted:'衝突'}[s]||s);
    const sCls=s=>s==='approved'?'bg-green-100 text-green-700':s==='pending'?'bg-yellow-100 text-yellow-700':s==='rejected'?'bg-red-100 text-red-600':'bg-gray-100 text-gray-500';
    document.getElementById('vb-review-detail').innerHTML=`
      <div class="grid grid-cols-2 gap-3">
        <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">序號</div><div class="font-mono text-xs font-bold text-fju-blue">${b.serial_no||'#'+b.id}</div></div>
        <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">狀態</div><span class="px-2 py-1 rounded-fju text-xs ${sCls(b.status)}">${sLabel(b.status)}</span></div>
      </div>
      <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">申請人</div><div class="font-medium">${(b.applicant&&b.applicant.name)||'—'}</div><div class="text-xs text-gray-400">${b.unit_code||''}</div></div>
      <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">場地</div><div class="font-medium">${(b.venue&&b.venue.name)||'—'}</div></div>
      <div class="grid grid-cols-2 gap-3">
        <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">預約日期</div><div>${b.booking_date||'—'}</div></div>
        <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">時段</div><div>${b.start_time||'—'} – ${b.end_time||'—'}</div></div>
      </div>
      <div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">預估人數</div><div>${b.expected_participants||'—'} 人</div></div>
      ${b.purpose?`<div class="bg-gray-50 rounded-fju p-3"><div class="text-xs text-gray-400 mb-1">用途說明</div><div class="text-sm">${b.purpose}</div></div>`:''}
      ${b.reject_reason?`<div class="bg-red-50 rounded-fju p-3 border border-red-100"><div class="text-xs text-red-400 mb-1">拒絕原因</div><div class="text-sm text-red-600">${b.reject_reason}</div></div>`:''}
    `;
    const actEl=document.getElementById('vb-review-actions');
    if(b.status==='pending'){
      actEl.innerHTML=`
        <button onclick="doVbApprove()" class="w-full py-2.5 rounded-fju bg-fju-green text-white text-sm font-bold hover:opacity-80 transition-all"><i class="fas fa-check-circle mr-2"></i>核准預約</button>
        <div class="flex gap-2 mt-2">
          <input id="vb-reject-reason" type="text" placeholder="拒絕原因（選填）" class="flex-1 px-3 py-2 rounded-fju border border-gray-200 text-sm">
          <button onclick="doVbReject()" class="px-4 py-2 rounded-fju bg-red-500 text-white text-sm font-bold hover:opacity-80 transition-all"><i class="fas fa-times-circle mr-1"></i>拒絕</button>
        </div>`;
    } else {
      actEl.innerHTML='<div class="text-center text-xs text-gray-400 py-2">此申請已審核完畢</div>';
    }
    document.getElementById('vb-review-modal').classList.remove('hidden');
  });
}

function doVbApprove(){
  if(!IS_ADMIN||!currentVbReviewId)return;
  fetch(`/api/venue-bookings/${currentVbReviewId}/approve`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({reviewer_id:1})})
    .then(r=>r.json()).then(res=>{
      if(res.success){alert('場地預約已核准！');closeVbReviewModal();loadVbBookings();}
      else alert(res.error||'操作失敗');
    });
}

function doVbReject(){
  if(!IS_ADMIN||!currentVbReviewId)return;
  const reason=document.getElementById('vb-reject-reason')?.value||'場地不符需求';
  fetch(`/api/venue-bookings/${currentVbReviewId}/reject`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({reviewer_id:1,reason})})
    .then(r=>r.json()).then(res=>{
      if(res.success){alert('場地預約已拒絕');closeVbReviewModal();loadVbBookings();}
      else alert(res.error||'操作失敗');
    });
}

function closeVbReviewModal(){document.getElementById('vb-review-modal').classList.add('hidden');currentVbReviewId=null;}

// Task 3: Submit single preference — first-come-first-served
function submitBooking(e){
  e.preventDefault();
  // Block if conflict detected on frontend
  if(!document.getElementById('conflict-warn-1').classList.contains('hidden')){
    return;
  }
  const unitCode = getUnitCode('vb');
  if (!unitCode) {
    const b=document.getElementById('booking-result');
    b.classList.remove('hidden');
    b.className='mt-3 p-3 rounded-fju text-sm bg-red-50 text-red-600 border border-red-200';
    b.innerHTML='<i class="fas fa-exclamation-circle mr-1"></i>請選擇單位代碼';
    return;
  }
  const channels=[];
  if(document.getElementById('bk-n-outlook').checked) channels.push('outlook');
  if(document.getElementById('bk-n-sms').checked) channels.push('sms');
  if(document.getElementById('bk-n-line').checked) channels.push('line');
  const d={
    unit_code: unitCode,
    venue_id:               document.getElementById('bk-venue-1').value,
    venue_name:             document.getElementById('bk-venue-1').selectedOptions[0]?.text||'',
    reservation_date:       document.getElementById('bk-date-1').value,
    start_time:             document.getElementById('bk-start-1').value,
    end_time:               document.getElementById('bk-end-1').value,
    priority_level:         parseInt(document.getElementById('bk-priority').value),
    purpose:                document.getElementById('bk-purpose').value,
    estimated_participants: parseInt(document.getElementById('bk-est-ppl').value)||30,
    user_id:    1,
    user_name:  'Demo User',
    club_name:  '攝影社',
    notify_channels: channels,
  };
  const submitBtn=document.getElementById('booking-submit-btn');
  if(submitBtn){submitBtn.disabled=true;submitBtn.innerHTML='<i class="fas fa-spinner fa-spin mr-2"></i>送出中...';}
  fetch('/api/reservations',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(d)})
    .then(r=>r.json())
    .then(res=>{
      const b=document.getElementById('booking-result');
      b.classList.remove('hidden');
      if(res.success){
        b.className='mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green border border-fju-green/20';
        b.innerHTML=`<i class="fas fa-check-circle mr-1"></i>預約成功！${res.message||''}<br><span class="text-xs text-gray-500 mt-1 block">通知管道：${channels.join(', ')||'無'}</span>`;
        if(submitBtn) submitBtn.classList.add('hidden');
        // Refresh reservation list
        fetch('/api/reservations').then(r=>r.json()).then(r2=>{allReservations=r2.data||[];renderVenues();});
      } else {
        // Task 3: On conflict — show rejection, do NOT enter negotiation
        b.className='mt-3 p-3 rounded-fju text-sm bg-red-50 text-red-600 border border-red-200';
        b.innerHTML=`<i class="fas fa-times-circle mr-1"></i>預約失敗：${res.message||'此時段已被他人優先預約'}<br><span class="text-xs text-red-400 mt-1 block">依先搶先贏原則，請選擇其他時段重新送出。</span>`;
        if(submitBtn){submitBtn.disabled=false;submitBtn.innerHTML='<i class="fas fa-paper-plane mr-2"></i>送出預約申請';submitBtn.className='w-full btn-yellow py-3 mt-4';}
      }
    })
    .catch(()=>{
      const b=document.getElementById('booking-result');
      b.classList.remove('hidden');
      b.className='mt-3 p-3 rounded-fju text-sm bg-red-50 text-red-600';
      b.innerHTML='<i class="fas fa-exclamation-circle mr-1"></i>網路錯誤，請稍後再試';
      if(submitBtn){submitBtn.disabled=false;submitBtn.innerHTML='<i class="fas fa-paper-plane mr-2"></i>送出預約申請';}
    });
}
</script>
@endsection
