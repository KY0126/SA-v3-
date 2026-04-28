@extends('layouts.shell')
@section('title', '活動申請')
@php $activePage = 'calendar'; @endphp
@section('content')
<div class="space-y-6">
  {{-- Header + Sorter --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-check mr-2 text-fju-yellow"></i>活動申請管理</h2>
    <div class="flex items-center gap-2">
      <select id="cal-sort" onchange="renderEventList()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="date-asc">日期 (舊→新)</option>
        <option value="date-desc" selected>日期 (新→舊)</option>
        <option value="title-asc">標題 A→Z</option>
      </select>
      <select id="cal-type-filter" onchange="renderEventList()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部類型</option>
        <option value="meeting">會議</option>
        <option value="competition">比賽</option>
        <option value="performance">表演</option>
        <option value="workshop">工作坊</option>
        <option value="service">服務</option>
        <option value="sports">體育</option>
      </select>
      <button onclick="openEventModal()" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增事件</button>
    </div>
  </div>

  {{-- View Toggle --}}
  <div class="flex items-center gap-2 justify-end">
    <button onclick="setCalView('week')" id="view-week" class="px-3 py-1 rounded-fju text-xs bg-gray-100 text-gray-500">週</button>
    <button onclick="setCalView('month')" id="view-month" class="px-3 py-1 rounded-fju text-xs bg-fju-blue text-white">月</button>
  </div>

  {{-- Custom Calendar --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
      <button onclick="prevMonth()" class="w-8 h-8 rounded-fju hover:bg-fju-bg flex items-center justify-center"><i class="fas fa-chevron-left text-fju-blue"></i></button>
      <h3 class="font-bold text-fju-blue text-base" id="cal-month-label"></h3>
      <button onclick="nextMonth()" class="w-8 h-8 rounded-fju hover:bg-fju-bg flex items-center justify-center"><i class="fas fa-chevron-right text-fju-blue"></i></button>
    </div>
    <div id="cal-grid" class="p-2"></div>
  </div>

  {{-- Event List --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>事件列表</h3></div>
    <div id="event-list"><div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>
  </div>

  {{-- Add Event Modal --}}
  <div id="event-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4">
      <h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>新增行事曆事件</h3>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400">標題</label><input id="ev-title" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm" required></div>
        <div><label class="text-xs text-gray-400">日期</label><input type="date" id="ev-date" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm" required></div>
        <div><label class="text-xs text-gray-400">類型</label><select id="ev-type" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><option>meeting</option><option>competition</option><option>performance</option><option>workshop</option><option>service</option><option>sports</option></select></div>
        <div><label class="text-xs text-gray-400">說明</label><textarea id="ev-desc" rows="2" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea></div>
      </div>
      <div class="flex gap-2 mt-4">
        <button onclick="addEvent()" class="flex-1 btn-yellow py-2"><i class="fas fa-check mr-1"></i>新增</button>
        <button onclick="closeEventModal()" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
      </div>
    </div>
  </div>

  {{-- Date Detail Modal: shows who booked this date + reservation modify --}}
  <div id="date-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
      <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-day mr-2 text-fju-yellow"></i><span id="dd-date"></span></h3>
        <button onclick="closeDateDetail()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
      </div>
      <div id="dd-events" class="mb-4"></div>
      <div id="dd-reservations" class="mb-4"></div>
      <div id="dd-modify" class="hidden">
        <h4 class="font-bold text-fju-blue text-sm mb-2"><i class="fas fa-edit mr-1 text-fju-yellow"></i>修改場地預約</h4>
        <div class="p-3 rounded-fju bg-fju-bg mb-3 text-sm" id="dd-current-res"></div>
        <div><label class="text-xs text-gray-400">選擇新時段</label>
          <select id="dd-new-slot" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm mt-1"></select>
        </div>
        <div class="flex gap-2 mt-3">
          <button onclick="closeModify()" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
          <button onclick="confirmModify()" class="flex-1 btn-yellow py-2 text-sm"><i class="fas fa-check mr-1"></i>確認修改</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let allEvents=[], allReservations=[], calYear, calMonth;

// Init
(function(){
  const now=new Date();
  calYear=now.getFullYear(); calMonth=now.getMonth();
  loadCalData();
})();

function loadCalData(){
  Promise.all([
    fetch('/api/calendar/events').then(r=>r.json()),
    fetch('/api/reservations').then(r=>r.json())
  ]).then(([evRes,resRes])=>{
    allEvents=evRes.data||[];
    allReservations=(resRes.data||[]);
    renderCalendar();
    renderEventList();
  });
}

function renderCalendar(){
  const label=calYear+'年 '+(calMonth+1)+'月';
  document.getElementById('cal-month-label').textContent=label;
  const firstDay=new Date(calYear,calMonth,1).getDay();
  const daysInMonth=new Date(calYear,calMonth+1,0).getDate();
  const today=new Date();
  const days=['日','一','二','三','四','五','六'];

  let html='<div class="grid grid-cols-7 gap-0">';
  days.forEach(d=>{html+='<div class="text-center text-xs font-bold text-white py-2 bg-fju-blue">'+d+'</div>'});
  for(let i=0;i<firstDay;i++){html+='<div class="p-2 min-h-[80px] border border-gray-50 bg-gray-50/50"></div>'}
  for(let d=1;d<=daysInMonth;d++){
    const dateStr=calYear+'-'+String(calMonth+1).padStart(2,'0')+'-'+String(d).padStart(2,'0');
    const isToday=today.getFullYear()===calYear&&today.getMonth()===calMonth&&today.getDate()===d;
    const dayEvents=allEvents.filter(e=>e.date===dateStr);
    const dayRes=allReservations.filter(r=>r.reservation_date===dateStr);
    const hasBooking=dayRes.length>0;
    html+='<div class="p-1.5 min-h-[80px] border border-gray-100 cursor-pointer hover:bg-fju-yellow/5 transition-all relative '+(isToday?'bg-fju-blue/5 ring-2 ring-fju-blue/30':'')+'" onclick="openDateDetail(\''+dateStr+'\')">';
    html+='<div class="flex items-center justify-between"><span class="text-sm font-bold '+(isToday?'text-fju-blue':'text-gray-700')+'">'+d+'</span>';
    if(hasBooking) html+='<span class="text-[9px] px-1.5 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow font-bold">'+dayRes.length+'預約</span>';
    html+='</div>';
    html+='<div class="text-[10px] text-gray-400 mt-0.5">'+(dayRes.length===0&&dayEvents.length===0?'可用':'')+'</div>';
    dayEvents.slice(0,2).forEach(e=>{html+='<div class="text-[9px] px-1 py-0.5 rounded mt-0.5 truncate" style="background:'+((e.color||'#003153')+'20')+';color:'+(e.color||'#003153')+'">'+e.title+'</div>'});
    dayRes.slice(0,1).forEach(r=>{html+='<div class="text-[9px] px-1 py-0.5 rounded mt-0.5 truncate bg-fju-blue/10 text-fju-blue"><i class="fas fa-user text-[8px] mr-0.5"></i>'+(r.user_name||r.club_name||'')+'</div>'});
    html+='</div>';
  }
  html+='</div>';
  document.getElementById('cal-grid').innerHTML=html;
}

function renderEventList(){
  const sort=document.getElementById('cal-sort').value;
  const typeF=document.getElementById('cal-type-filter').value;
  let data=[...allEvents];
  if(typeF!=='all') data=data.filter(e=>e.type===typeF);
  if(sort==='date-asc') data.sort((a,b)=>a.date.localeCompare(b.date));
  else if(sort==='date-desc') data.sort((a,b)=>b.date.localeCompare(a.date));
  else if(sort==='title-asc') data.sort((a,b)=>a.title.localeCompare(b.title));
  document.getElementById('event-list').innerHTML=data.length===0?'<div class="p-4 text-center text-gray-400">無事件</div>':
    '<table class="w-full text-sm"><tbody>'+data.map(e=>'<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-3"><span class="w-3 h-3 rounded-full inline-block mr-2" style="background:'+(e.color||'#003153')+'"></span></td><td class="p-3 font-medium text-fju-blue">'+e.title+'</td><td class="p-3 text-xs text-gray-400">'+e.date+'</td><td class="p-3 text-xs text-gray-400">'+(e.type||'')+'</td><td class="p-3 text-xs text-gray-400">'+(e.venue||'')+'</td><td class="p-3"><button onclick="event.stopPropagation();deleteEvent('+e.id+')" class="text-fju-red text-xs hover:bg-fju-red/10 px-2 py-1 rounded"><i class="fas fa-trash"></i></button></td></tr>').join('')+'</tbody></table>';
}

function prevMonth(){calMonth--;if(calMonth<0){calMonth=11;calYear--}renderCalendar()}
function nextMonth(){calMonth++;if(calMonth>11){calMonth=0;calYear++}renderCalendar()}
function setCalView(v){
  document.getElementById('view-week').className='px-3 py-1 rounded-fju text-xs '+(v==='week'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500');
  document.getElementById('view-month').className='px-3 py-1 rounded-fju text-xs '+(v==='month'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500');
}

function openDateDetail(dateStr){
  document.getElementById('dd-date').textContent=dateStr;
  const dayEvents=allEvents.filter(e=>e.date===dateStr);
  const dayRes=allReservations.filter(r=>r.reservation_date===dateStr);

  // Events
  document.getElementById('dd-events').innerHTML=dayEvents.length>0?
    '<h4 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-calendar-check mr-1 text-fju-yellow"></i>當日事件</h4>'+dayEvents.map(e=>'<div class="flex items-center gap-2 p-2 rounded-fju bg-fju-bg mb-1"><span class="w-3 h-3 rounded-full" style="background:'+(e.color||'#003153')+'"></span><span class="text-sm text-fju-blue font-medium">'+e.title+'</span><span class="text-xs text-gray-400 ml-auto">'+(e.type||'')+'</span></div>').join(''):'<p class="text-xs text-gray-400 mb-2">當日無事件</p>';

  // Reservations (who has booked)
  if(dayRes.length>0){
    document.getElementById('dd-reservations').innerHTML='<h4 class="font-bold text-sm text-fju-blue mb-2"><i class="fas fa-users mr-1 text-fju-yellow"></i>已預約時段</h4>'+dayRes.map(r=>{
      const confirmed=r.status==='confirmed';
      return '<div class="flex items-center justify-between p-2 rounded-fju border border-gray-100 mb-1"><div><span class="text-sm font-medium text-fju-blue">'+(r.venue_name||'場地')+'</span><span class="text-xs text-gray-400 ml-2">'+r.start_time+' - '+r.end_time+'</span></div><div class="flex items-center gap-2"><span class="text-xs px-2 py-0.5 rounded-full '+(confirmed?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+'">'+r.status+'</span><span class="text-xs text-gray-500"><i class="fas fa-user mr-0.5"></i>'+(r.user_name||r.club_name||'')+'</span>'+(confirmed?'<button onclick="openModify('+r.id+',\''+dateStr+'\')" class="text-xs btn-yellow px-2 py-0.5">修改</button>':'')+'</div></div>'}).join('');
  } else {
    document.getElementById('dd-reservations').innerHTML='<p class="text-xs text-fju-green"><i class="fas fa-check-circle mr-1"></i>此日尚無場地預約，可用</p>';
  }
  document.getElementById('dd-modify').classList.add('hidden');
  document.getElementById('date-detail-modal').classList.remove('hidden');
}

let modifyResId=null;
function openModify(resId, dateStr){
  modifyResId=resId;
  const res=allReservations.find(r=>r.id===resId);
  document.getElementById('dd-current-res').innerHTML='<b>現有預約：</b>'+(res?.venue_name||'場地')+'<br>'+dateStr+' '+(res?.start_time||'')+' - '+(res?.end_time||'');
  // Generate available slots
  const slots=['09:00-12:00','13:00-16:00','14:00-17:00','18:00-21:00'];
  document.getElementById('dd-new-slot').innerHTML=slots.map(s=>'<option value="'+s+'">'+dateStr.replace(/-/g,'/')+'  '+s+'</option>').join('');
  document.getElementById('dd-modify').classList.remove('hidden');
}
function closeModify(){document.getElementById('dd-modify').classList.add('hidden')}
function confirmModify(){
  const slot=document.getElementById('dd-new-slot').value;
  const [start,end]=slot.split('-');
  fetch('/api/reservations/'+modifyResId,{method:'PUT',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({start_time:start,end_time:end,status:'confirmed'})}).then(r=>r.json()).then(()=>{
    alert('預約時段已修改！');
    closeDateDetail();
    loadCalData();
  });
}
function closeDateDetail(){document.getElementById('date-detail-modal').classList.add('hidden')}

function openEventModal(){document.getElementById('event-modal').classList.remove('hidden')}
function closeEventModal(){document.getElementById('event-modal').classList.add('hidden')}
function addEvent(){
  const d={title:document.getElementById('ev-title').value,date:document.getElementById('ev-date').value,type:document.getElementById('ev-type').value,description:document.getElementById('ev-desc').value,color:'#003153'};
  fetch('/api/calendar/events',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(d)}).then(r=>r.json()).then(()=>{closeEventModal();loadCalData()});
}
function deleteEvent(id){if(!confirm('確定刪除？'))return;fetch('/api/calendar/events/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>loadCalData())}
</script>
@endsection
