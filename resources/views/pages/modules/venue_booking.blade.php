@extends('layouts.shell')
@section('title', '場地預約')
@php $activePage = 'venue-booking'; @endphp
@section('content')
<div class="space-y-6">
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h2 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-chess mr-2 text-fju-yellow"></i>三階段資源調度系統</h2>
    <div class="grid md:grid-cols-3 gap-4">
      <div class="p-4 rounded-fju bg-fju-blue/5 border-2 border-fju-blue/20"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-fju-blue text-sm">志願序配對</span></div><p class="text-xs text-gray-500">Level 1-3 優先權自動配對</p></div>
      <div class="p-4 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">2</div><span class="font-bold text-fju-blue text-sm">衝突協調中心</span></div><p class="text-xs text-gray-500">AI 建議 + 雙方確認 + 即時聊天</p></div>
      <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-green flex items-center justify-center text-white text-sm font-bold">3</div><span class="font-bold text-fju-blue text-sm">官方核准</span></div><p class="text-xs text-gray-500">RAG 法規比對 + Gatekeeping</p></div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid md:grid-cols-4 gap-4" id="venue-stats"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>

  {{-- Sort/Filter Bar --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <div class="flex gap-2">
      <select id="vb-sort" onchange="renderVenues()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="name-asc">名稱 A→Z</option>
        <option value="name-desc">名稱 Z→A</option>
        <option value="cap-desc">容量 (多→少)</option>
        <option value="cap-asc">容量 (少→多)</option>
      </select>
      <select id="vb-status-filter" onchange="renderVenues()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部狀態</option>
        <option value="available">可預約</option>
        <option value="maintenance">維護中</option>
      </select>
    </div>
    <div class="flex gap-2">
      <input id="vb-search" oninput="renderVenues()" type="text" placeholder="搜尋場地..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-40">
      <button onclick="openBookingModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約</button>
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

  {{-- Booking Modal --}}
  <div id="booking-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
      <div class="flex items-center justify-between mb-4"><h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>新增場地預約</h3><button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button></div>
      <form id="booking-form" onsubmit="submitBooking(event)">
        <div class="space-y-3">
          <div><label class="text-xs text-gray-400 block mb-1">場地</label><select id="bk-venue" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></select></div>
          <div class="grid grid-cols-2 gap-3"><div><label class="text-xs text-gray-400 block mb-1">日期</label><input type="date" id="bk-date" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div><div><label class="text-xs text-gray-400 block mb-1">優先等級</label><select id="bk-priority" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"><option value="3">L3 - 一般社團</option><option value="2">L2 - 社團幹部</option><option value="1">L1 - 校方</option></select></div></div>
          <div class="grid grid-cols-2 gap-3"><div><label class="text-xs text-gray-400 block mb-1">開始時間</label><input type="time" id="bk-start" value="14:00" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div><div><label class="text-xs text-gray-400 block mb-1">結束時間</label><input type="time" id="bk-end" value="17:00" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div></div>
          <div><label class="text-xs text-gray-400 block mb-1">用途說明</label><textarea id="bk-purpose" rows="2" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="請說明借用目的..."></textarea></div>
        </div>
        <div id="booking-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
        <button type="submit" class="w-full btn-yellow py-3 mt-4"><i class="fas fa-paper-plane mr-2"></i>送出預約申請</button>
      </form>
    </div>
  </div>
</div>

<script>
let allVenues=[];
(function(){
  Promise.all([
    fetch('/api/venues').then(r=>r.json()),
    fetch('/api/conflicts').then(r=>r.json())
  ]).then(([vRes,cRes])=>{
    allVenues=vRes.data;
    renderVenueStats();
    renderVenues();
    renderActiveConflicts(cRes.data||[]);
    // Fill venue dropdown
    const sel=document.getElementById('bk-venue');
    allVenues.filter(v=>v.status==='available').forEach(v=>{sel.innerHTML+='<option value="'+v.id+'">'+v.name+' ('+v.capacity+'人)</option>'});
  });
})();

function renderVenueStats(){
  const a=allVenues.filter(v=>v.status==='available').length,m=allVenues.filter(v=>v.status==='maintenance').length;
  document.getElementById('venue-stats').innerHTML='<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">'+allVenues.length+'</div><div class="text-xs text-gray-400">總場地數</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">'+a+'</div><div class="text-xs text-gray-400">可用場地</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">87%</div><div class="text-xs text-gray-400">使用率</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-red">'+m+'</div><div class="text-xs text-gray-400">維護中</div></div>';
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
    return '<div class="flex items-center justify-between p-3 bg-white rounded-fju border border-gray-100"><div class="flex items-center gap-3"><i class="fas fa-exclamation-circle text-fju-yellow"></i><div><span class="text-sm font-bold text-fju-blue">'+(c.party_a||'')+' vs '+(c.party_b||'')+'</span><span class="text-xs text-gray-400 ml-2">'+(c.venue_name||'')+' · '+(c.conflict_date||'')+'</span></div></div><div class="flex items-center gap-2"><span class="px-2 py-1 rounded-fju text-xs '+(sl[c.status]||'')+'">'+(slLabel[c.status]||c.status)+'</span><a href="/module/conflict-coordination?role={{ $role ?? "student" }}" class="btn-yellow px-3 py-1 text-xs"><i class="fas fa-handshake mr-1"></i>前往協調</a></div></div>'}).join('');
}

function openBookingModal(){document.getElementById('booking-modal').classList.remove('hidden')}
function closeBookingModal(){document.getElementById('booking-modal').classList.add('hidden');document.getElementById('booking-result').classList.add('hidden')}
function quickBook(n){openBookingModal()}
function submitBooking(e){
  e.preventDefault();
  const d={venue_id:document.getElementById('bk-venue').value,venue_name:document.getElementById('bk-venue').selectedOptions[0].text,reservation_date:document.getElementById('bk-date').value,start_time:document.getElementById('bk-start').value,end_time:document.getElementById('bk-end').value,priority_level:parseInt(document.getElementById('bk-priority').value),purpose:document.getElementById('bk-purpose').value,user_id:1,user_name:'Demo User',club_name:'攝影社'};
  fetch('/api/reservations',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).then(res=>{
    const b=document.getElementById('booking-result');b.classList.remove('hidden');
    if(res.success){
      b.className='mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green';
      b.innerHTML='<i class="fas fa-check-circle mr-1"></i> '+res.message+' (階段：'+res.stage+')';
    } else {
      b.className='mt-3 p-3 rounded-fju text-sm bg-fju-yellow/20 text-fju-blue';
      b.innerHTML='<i class="fas fa-exclamation-triangle mr-1"></i> '+res.message+(res.conflict_id?'<br><a href="/module/conflict-coordination?role={{ $role ?? "student" }}" class="inline-flex items-center gap-1 mt-2 btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-handshake"></i>前往衝突協調中心</a>':'');
    }
  });
}
</script>
@endsection
