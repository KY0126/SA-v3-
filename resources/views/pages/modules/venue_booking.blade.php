@extends('layouts.shell')
@section('title', '場地預約')
@php $activePage = 'venue-booking'; @endphp
@section('content')
<div class="space-y-6">
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h2 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-chess mr-2 text-fju-yellow"></i>三階段資源調度系統</h2>
    <div class="grid md:grid-cols-3 gap-4">
      <div class="p-4 rounded-fju bg-fju-blue/5 border-2 border-fju-blue/20"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-fju-blue text-sm">志願序配對</span></div><p class="text-xs text-gray-500">Level 1-3 優先權自動配對</p></div>
      <div class="p-4 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">2</div><span class="font-bold text-fju-blue text-sm">自主協商</span></div><p class="text-xs text-gray-500">3/6 分鐘規則 (Redis + GPT-4)</p></div>
      <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-green flex items-center justify-center text-white text-sm font-bold">3</div><span class="font-bold text-fju-blue text-sm">官方核准</span></div><p class="text-xs text-gray-500">RAG 法規比對 + Gatekeeping</p></div>
    </div>
  </div>
  <div class="grid md:grid-cols-4 gap-4" id="venue-stats"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>
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
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>場地清單</h3><button onclick="openBookingModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約</button></div>
    <div id="venue-table-body"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>
</div>
<script>
(function(){
  fetch('/api/venues').then(r=>r.json()).then(res=>{
    const venues=res.data;const a=venues.filter(v=>v.status==='available').length,m=venues.filter(v=>v.status==='maintenance').length;
    document.getElementById('venue-stats').innerHTML='<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">'+venues.length+'</div><div class="text-xs text-gray-400">總場地數</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">'+a+'</div><div class="text-xs text-gray-400">可用場地</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">87%</div><div class="text-xs text-gray-400">使用率</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-red">'+m+'</div><div class="text-xs text-gray-400">維護中</div></div>';
    document.getElementById('venue-table-body').innerHTML='<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">場地</th><th class="p-4">位置</th><th class="p-4">容量</th><th class="p-4">設備</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>'+venues.map(v=>'<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">'+v.name+'</td><td class="p-4 text-gray-500">'+(v.location||'')+'</td><td class="p-4">'+v.capacity+'人</td><td class="p-4 text-xs text-gray-400">'+(v.equipment_list||'-')+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju '+(v.status==='available'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+' text-xs">'+(v.status==='available'?'可預約':'維護中')+'</span></td><td class="p-4">'+(v.status==='available'?'<button onclick="quickBook(\''+v.name+'\')" class="btn-yellow px-3 py-1 text-xs">預約</button>':'<span class="text-xs text-gray-400">-</span>')+'</td></tr>').join('')+'</tbody></table>';
    const sel=document.getElementById('bk-venue');venues.filter(v=>v.status==='available').forEach(v=>{sel.innerHTML+='<option value="'+v.id+'">'+v.name+' ('+v.capacity+'人)</option>'});
  });
})();
function openBookingModal(){document.getElementById('booking-modal').classList.remove('hidden')}
function closeBookingModal(){document.getElementById('booking-modal').classList.add('hidden');document.getElementById('booking-result').classList.add('hidden')}
function quickBook(n){openBookingModal()}
function submitBooking(e){e.preventDefault();const d={venue_id:document.getElementById('bk-venue').value,venue_name:document.getElementById('bk-venue').selectedOptions[0].text,reservation_date:document.getElementById('bk-date').value,start_time:document.getElementById('bk-start').value,end_time:document.getElementById('bk-end').value,priority_level:parseInt(document.getElementById('bk-priority').value),purpose:document.getElementById('bk-purpose').value,user_id:1,user_name:'Demo User',club_name:'攝影社'};
fetch('/api/reservations',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).then(res=>{const b=document.getElementById('booking-result');b.classList.remove('hidden');if(res.success){b.className='mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green';b.innerHTML='<i class="fas fa-check-circle mr-1"></i> '+res.message+' (階段：'+res.stage+')'}else{b.className='mt-3 p-3 rounded-fju text-sm bg-fju-yellow/20 text-fju-blue';b.innerHTML='<i class="fas fa-exclamation-triangle mr-1"></i> '+res.message+(res.conflict_id?'<br><a href="/module/conflict-coordination?role={{ $role ?? "student" }}" class="inline-flex items-center gap-1 mt-2 btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-handshake"></i>前往衝突協調中心</a>':'')}})}
</script>
@endsection
