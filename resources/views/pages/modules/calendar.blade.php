@extends('layouts.shell')
@section('title', '行事曆總覽')
@php $activePage = 'calendar'; @endphp
@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <div>
      <h2 class="font-bold text-fju-blue text-xl"><i class="fas fa-calendar-alt mr-2 text-fju-yellow"></i>行事曆總覽</h2>
      <p class="text-xs text-gray-400 mt-0.5">整合活動申請、器材借用、場地預約於同一行事曆</p>
    </div>
    <div class="flex items-center gap-2">
      {{-- Task 1: 審核者（admin）不顯示「新增事件」 --}}
      @if(!in_array($role ?? 'student', ['admin']))
      <button onclick="openEventModal()" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增事件</button>
      @endif
    </div>
  </div>

  {{-- Task 2: Legend --}}
  <div class="flex flex-wrap gap-3 items-center bg-white px-4 py-3 rounded-fju-lg shadow-sm border border-gray-100">
    <span class="text-xs text-gray-400 font-bold mr-1">圖例：</span>
    <span class="flex items-center gap-1.5 text-xs"><span class="w-3 h-3 rounded-full inline-block" style="background:#3b82f6"></span><span class="text-gray-600">活動申請</span></span>
    <span class="flex items-center gap-1.5 text-xs"><span class="w-3 h-3 rounded-full inline-block" style="background:#f59e0b"></span><span class="text-gray-600">器材借用</span></span>
    <span class="flex items-center gap-1.5 text-xs"><span class="w-3 h-3 rounded-full inline-block" style="background:#22c55e"></span><span class="text-gray-600">場地預約</span></span>
    <span class="flex items-center gap-1.5 text-xs"><span class="w-3 h-3 rounded-full inline-block" style="background:#8b5cf6"></span><span class="text-gray-600">行事曆事件</span></span>
    <div class="ml-auto flex gap-1">
      <select id="cal-type-filter" onchange="renderCalendar()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部類型</option>
        <option value="activity">僅活動申請</option>
        <option value="equipment">僅器材借用</option>
        <option value="venue">僅場地預約</option>
        <option value="event">僅行事曆事件</option>
      </select>
    </div>
  </div>

  {{-- Month Calendar --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
      <button onclick="prevMonth()" class="w-8 h-8 rounded-fju hover:bg-fju-bg flex items-center justify-center"><i class="fas fa-chevron-left text-fju-blue"></i></button>
      <h3 class="font-bold text-fju-blue text-base" id="cal-month-label"></h3>
      <button onclick="nextMonth()" class="w-8 h-8 rounded-fju hover:bg-fju-bg flex items-center justify-center"><i class="fas fa-chevron-right text-fju-blue"></i></button>
    </div>
    <div id="cal-grid" class="p-2">
      <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div>
    </div>
  </div>

  {{-- Unified Event List --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>近期事項</h3>
      <select id="list-sort" onchange="renderList()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="date-asc">日期 (舊→新)</option>
        <option value="date-desc" selected>日期 (新→舊)</option>
      </select>
    </div>
    <div id="unified-list"><div class="p-6 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

</div>

{{-- Add Event Modal (hidden for admin per Task 1) --}}
<div id="event-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>新增行事曆事件</h3>
      <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <div class="space-y-3">
      <div><label class="text-xs text-gray-400">標題</label><input id="ev-title" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mt-1"></div>
      <div><label class="text-xs text-gray-400">日期</label><input type="date" id="ev-date" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mt-1"></div>
      <div><label class="text-xs text-gray-400">類型</label>
        <select id="ev-type" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mt-1">
          <option>meeting</option><option>competition</option><option>performance</option><option>workshop</option><option>service</option><option>sports</option>
        </select>
      </div>
      <div><label class="text-xs text-gray-400">說明</label><textarea id="ev-desc" rows="2" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mt-1 resize-none"></textarea></div>
    </div>
    <div class="flex gap-2 mt-4">
      <button onclick="addEvent()" class="flex-1 btn-yellow py-2.5 text-sm"><i class="fas fa-check mr-1"></i>新增</button>
      <button onclick="closeEventModal()" class="flex-1 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
    </div>
  </div>
</div>

{{-- Day Detail Modal --}}
<div id="day-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[80vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-day mr-2 text-fju-yellow"></i><span id="day-modal-date"></span></h3>
      <button onclick="document.getElementById('day-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <div id="day-modal-body" class="space-y-2 text-sm"></div>
  </div>
</div>

<script>
// Task 2: Three data sources + calendar events
let allActivities = [], allLoans = [], allVenues = [], allCalEvents = [];
let calYear, calMonth;

const COLORS = {
  activity:  { bg: '#eff6ff', border: '#3b82f6', text: '#1d4ed8', dot: '#3b82f6' },
  equipment: { bg: '#fffbeb', border: '#f59e0b', text: '#b45309', dot: '#f59e0b' },
  venue:     { bg: '#f0fdf4', border: '#22c55e', text: '#15803d', dot: '#22c55e' },
  event:     { bg: '#f5f3ff', border: '#8b5cf6', text: '#6d28d9', dot: '#8b5cf6' },
};

const STATUS_LABELS = {
  // activities
  submitted: '待審核', approved: '已核准', returned: '已退件', rejected: '已拒絕', draft: '草稿',
  // loans
  pending: '待審核', picked_up: '使用中', returned_eq: '已歸還',
  // venues
  confirmed: '已確認', negotiating: '協商中',
};

(function(){
  const now = new Date();
  calYear = now.getFullYear(); calMonth = now.getMonth();
  loadAllData();
})();

function loadAllData() {
  Promise.all([
    fetch('/api/activity-applications').then(r => r.json()).catch(() => ({ data: [] })),
    fetch('/api/equipment-loans').then(r => r.json()).catch(() => ({ data: [] })),
    fetch('/api/venue-bookings').then(r => r.json()).catch(() => ({ data: [] })),
    fetch('/api/calendar/events').then(r => r.json()).catch(() => ({ data: [] })),
  ]).then(([aaRes, elRes, vbRes, evRes]) => {
    allActivities  = (aaRes.data || []).filter(a => a.status !== 'draft');
    allLoans       = elRes.data || [];
    allVenues      = vbRes.data || [];
    allCalEvents   = evRes.data || [];
    renderCalendar();
    renderList();
  });
}

// Build a map of date → array of unified events
function buildDayMap() {
  const typeFilter = document.getElementById('cal-type-filter')?.value || 'all';
  const map = {};
  const add = (dateStr, item) => { if (!map[dateStr]) map[dateStr] = []; map[dateStr].push(item); };

  if (typeFilter === 'all' || typeFilter === 'activity') {
    allActivities.forEach(a => {
      if (a.event_date) add(a.event_date, { type: 'activity', title: a.activity_name, sub: STATUS_LABELS[a.status] || a.status, date: a.event_date });
    });
  }
  if (typeFilter === 'all' || typeFilter === 'equipment') {
    allLoans.forEach(l => {
      const names = (l.details || []).map(d => d.equipment?.name || '').filter(Boolean).join('、') || '器材借用';
      if (l.borrow_date) add(l.borrow_date, { type: 'equipment', title: names, sub: STATUS_LABELS[l.status] || l.status, date: l.borrow_date });
      if (l.expected_return_date && l.expected_return_date !== l.borrow_date)
        add(l.expected_return_date, { type: 'equipment', title: names + '（歸還）', sub: l.expected_return_date, date: l.expected_return_date });
    });
  }
  if (typeFilter === 'all' || typeFilter === 'venue') {
    allVenues.forEach(v => {
      const dateKey = v.booking_date || v.reservation_date || v.event_date;
      if (dateKey) add(dateKey, { type: 'venue', title: v.venue?.name || v.venue_name || '場地預約', sub: STATUS_LABELS[v.status] || v.status, date: dateKey });
    });
  }
  if (typeFilter === 'all' || typeFilter === 'event') {
    allCalEvents.forEach(e => {
      if (e.date) add(e.date, { type: 'event', title: e.title, sub: e.type || '', date: e.date, id: e.id });
    });
  }
  return map;
}

function renderCalendar() {
  const map = buildDayMap();
  const label = calYear + '年 ' + (calMonth + 1) + '月';
  document.getElementById('cal-month-label').textContent = label;
  const firstDay   = new Date(calYear, calMonth, 1).getDay();
  const daysInMonth = new Date(calYear, calMonth + 1, 0).getDate();
  const today       = new Date();
  const days = ['日', '一', '二', '三', '四', '五', '六'];

  let html = '<div class="grid grid-cols-7 gap-0">';
  days.forEach(d => { html += '<div class="text-center text-xs font-bold text-white py-2 bg-fju-blue">' + d + '</div>'; });
  for (let i = 0; i < firstDay; i++) { html += '<div class="p-1.5 min-h-[90px] border border-gray-50 bg-gray-50/40"></div>'; }
  for (let d = 1; d <= daysInMonth; d++) {
    const ds = calYear + '-' + String(calMonth + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
    const isToday = today.getFullYear() === calYear && today.getMonth() === calMonth && today.getDate() === d;
    const dayItems = map[ds] || [];
    html += '<div class="p-1.5 min-h-[90px] border border-gray-100 cursor-pointer hover:bg-fju-yellow/5 transition-all '
          + (isToday ? 'bg-fju-blue/5 ring-2 ring-inset ring-fju-blue/30' : '')
          + '" onclick="openDayModal(\'' + ds + '\')">';
    html += '<div class="flex items-center justify-between mb-0.5">';
    html += '<span class="text-sm font-bold ' + (isToday ? 'w-6 h-6 rounded-full bg-fju-blue text-white flex items-center justify-center' : 'text-gray-700') + '">' + d + '</span>';
    if (dayItems.length > 2) html += '<span class="text-[9px] text-gray-400">+' + (dayItems.length - 2) + '</span>';
    html += '</div>';
    dayItems.slice(0, 3).forEach(item => {
      const c = COLORS[item.type];
      html += '<div class="text-[9px] px-1 py-0.5 rounded mb-0.5 truncate" style="background:' + c.bg + ';color:' + c.text + ';border-left:2px solid ' + c.border + '">' + item.title + '</div>';
    });
    html += '</div>';
  }
  html += '</div>';
  document.getElementById('cal-grid').innerHTML = html;
}

function openDayModal(ds) {
  const map = buildDayMap();
  document.getElementById('day-modal-date').textContent = ds;
  const items = map[ds] || [];
  if (!items.length) {
    document.getElementById('day-modal-body').innerHTML = '<p class="text-gray-400 text-center py-4"><i class="fas fa-calendar-check mr-1 text-fju-green"></i>此日無任何事項</p>';
  } else {
    document.getElementById('day-modal-body').innerHTML = items.map(item => {
      const c = COLORS[item.type];
      const typeLabel = { activity: '活動申請', equipment: '器材借用', venue: '場地預約', event: '行事曆事件' }[item.type];
      return `<div class="flex items-start gap-3 p-3 rounded-fju" style="background:${c.bg};border-left:3px solid ${c.border}">
        <div class="w-2 h-2 rounded-full mt-1.5 shrink-0" style="background:${c.dot}"></div>
        <div class="flex-1">
          <div class="font-bold text-xs mb-0.5" style="color:${c.text}">${typeLabel}</div>
          <div class="text-sm text-gray-700">${item.title}</div>
          ${item.sub ? '<div class="text-xs text-gray-400 mt-0.5">' + item.sub + '</div>' : ''}
        </div>
      </div>`;
    }).join('');
  }
  document.getElementById('day-modal').classList.remove('hidden');
}

function renderList() {
  const typeFilter = document.getElementById('cal-type-filter')?.value || 'all';
  const sort = document.getElementById('list-sort')?.value || 'date-desc';
  let items = [];

  if (typeFilter === 'all' || typeFilter === 'activity') {
    allActivities.forEach(a => { if (a.event_date) items.push({ type: 'activity', title: a.activity_name, date: a.event_date, sub: (STATUS_LABELS[a.status] || a.status) + ' · ' + a.event_date }); });
  }
  if (typeFilter === 'all' || typeFilter === 'equipment') {
    allLoans.forEach(l => {
      const names = (l.details || []).map(d => d.equipment?.name || '').filter(Boolean).join('、') || '器材借用';
      if (l.borrow_date) items.push({ type: 'equipment', title: names, date: l.borrow_date, sub: (STATUS_LABELS[l.status] || l.status) + ' · 借出 ' + l.borrow_date + ' 歸還 ' + l.expected_return_date });
    });
  }
  if (typeFilter === 'all' || typeFilter === 'venue') {
    allVenues.forEach(v => {
      const dateKey = v.booking_date || v.reservation_date || v.event_date;
      if (dateKey) items.push({ type: 'venue', title: v.venue?.name || v.venue_name || '場地預約', date: dateKey, sub: (STATUS_LABELS[v.status] || v.status) + ' · ' + dateKey });
    });
  }
  if (typeFilter === 'all' || typeFilter === 'event') {
    allCalEvents.forEach(e => { if (e.date) items.push({ type: 'event', title: e.title, date: e.date, sub: (e.type || '') + ' · ' + e.date, id: e.id }); });
  }

  if (sort === 'date-asc') items.sort((a, b) => a.date.localeCompare(b.date));
  else items.sort((a, b) => b.date.localeCompare(a.date));

  if (!items.length) {
    document.getElementById('unified-list').innerHTML = '<div class="p-8 text-center text-gray-400">目前無資料</div>';
    return;
  }

  const typeLabel = { activity: '活動申請', equipment: '器材借用', venue: '場地預約', event: '行事曆事件' };
  document.getElementById('unified-list').innerHTML =
    '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">' +
    '<th class="p-4">類型</th><th class="p-4">標題</th><th class="p-4">日期</th><th class="p-4">狀態</th></tr></thead><tbody>' +
    items.map(item => {
      const c = COLORS[item.type];
      return `<tr class="border-t border-gray-50 hover:bg-gray-50">
        <td class="p-4"><span class="flex items-center gap-1.5 text-xs font-bold" style="color:${c.text}">
          <span class="w-2 h-2 rounded-full shrink-0" style="background:${c.dot}"></span>${typeLabel[item.type]}
        </span></td>
        <td class="p-4 font-medium text-fju-blue">${item.title}</td>
        <td class="p-4 text-xs text-gray-500">${item.date}</td>
        <td class="p-4 text-xs text-gray-400">${item.sub}</td>
      </tr>`;
    }).join('') + '</tbody></table>';
}

function prevMonth() { calMonth--; if (calMonth < 0) { calMonth = 11; calYear--; } renderCalendar(); }
function nextMonth() { calMonth++; if (calMonth > 11) { calMonth = 0; calYear++; } renderCalendar(); }

function openEventModal() { document.getElementById('event-modal').classList.remove('hidden'); }
function closeEventModal() { document.getElementById('event-modal').classList.add('hidden'); }
function addEvent() {
  const d = {
    title:       document.getElementById('ev-title').value,
    date:        document.getElementById('ev-date').value,
    type:        document.getElementById('ev-type').value,
    description: document.getElementById('ev-desc').value,
    color:       '#8b5cf6',
  };
  if (!d.title || !d.date) { alert('請填寫標題和日期'); return; }
  fetch('/api/calendar/events', {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify(d),
  }).then(r => r.json()).then(() => { closeEventModal(); loadAllData(); });
}
</script>
@endsection
