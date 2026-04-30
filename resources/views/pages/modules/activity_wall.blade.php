@extends('layouts.shell')
@section('title', '活動牆')
@php $activePage = 'activity-wall'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-newspaper mr-2 text-fju-yellow"></i>動態活動牆</h2>
    <div class="flex gap-2">
      <select id="act-sort" onchange="applyActFilters()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="date-desc">日期 (新→舊)</option>
        <option value="date-asc">日期 (舊→新)</option>
        <option value="title-asc">名稱 A→Z</option>
      </select>
      <input id="act-search" oninput="applyActFilters()" placeholder="搜尋活動..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-44" autocomplete="off">
    </div>
  </div>
  {{-- Empty State --}}
  <div class="bg-white rounded-fju-lg p-12 shadow-sm border border-gray-100 text-center" id="act-empty">
    <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
    <h3 class="font-bold text-gray-400 text-lg mb-2">目前沒有活動</h3>
    <p class="text-sm text-gray-400">活動牆尚未有任何內容。<br>活動由課指組或借用者發布後將顯示於此。</p>
  </div>
  <div class="grid md:grid-cols-2 gap-4 hidden" id="act-grid"></div>
</div>
<script>
// Activity wall is intentionally empty per prompt-6 requirement
let allActs=[], actStatusFilter='all';
function applyActFilters(){ renderActs([]); }
function renderActs(data){
  if(data.length===0){
    document.getElementById('act-empty').classList.remove('hidden');
    document.getElementById('act-grid').classList.add('hidden');
  } else {
    document.getElementById('act-empty').classList.add('hidden');
    document.getElementById('act-grid').classList.remove('hidden');
    document.getElementById('act-grid').innerHTML=data.map(a=>'<div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue">'+a.title+'</h3><p class="text-xs text-gray-500 mt-1">'+a.event_date+'</p></div>').join('');
  }
}
renderActs([]);
</script>
@endsection
