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
        <option value="ppl-desc">人數 (多→少)</option>
      </select>
      <div class="relative"><input id="act-search" oninput="applyActFilters();showActSuggestions(this)" placeholder="搜尋活動 / #標籤..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-44" autocomplete="off"><div id="act-suggest" class="hidden absolute top-full left-0 w-full bg-white border border-gray-200 rounded-fju shadow-lg z-50 max-h-40 overflow-y-auto"></div></div>
      <button onclick="document.getElementById('act-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增活動</button>
    </div>
  </div>
  <div class="flex gap-2 mb-0">
    <button onclick="filterAct('all')" class="act-f px-3 py-1 rounded-fju bg-fju-blue text-white text-xs" data-f="all">全部</button>
    <button onclick="filterAct('approved')" class="act-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="approved">已核准</button>
    <button onclick="filterAct('pending')" class="act-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="pending">待審核</button>
  </div>
  <div class="grid md:grid-cols-2 gap-4" id="act-grid"><div class="p-8 text-center text-gray-400 col-span-2"><i class="fas fa-spinner fa-spin"></i></div></div>
  <div id="act-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center"><div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4"><h3 class="font-bold text-fju-blue text-lg mb-4">新增活動</h3><div class="space-y-3"><input id="a-title" placeholder="活動名稱" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><textarea id="a-desc" placeholder="說明" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea><div class="grid grid-cols-2 gap-3"><input type="date" id="a-date" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"><input type="number" id="a-max" placeholder="人數上限" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"></div></div><div class="flex gap-2 mt-4"><button onclick="addAct()" class="flex-1 btn-yellow py-2">新增</button><button onclick="document.getElementById('act-modal').classList.add('hidden')" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button></div></div></div>
</div>
<script>
let allActs=[], actStatusFilter='all';
fetch('/api/activities').then(r=>r.json()).then(res=>{allActs=res.data;applyActFilters()});

// Hashtag mapping for activities
const actTags={};
function getActTags(a){
  const base=[];
  if(a.category) base.push(a.category);
  const t=a.title||'';
  if(t.includes('攝影')||t.includes('外拍'))base.push('攝影','藝術');
  if(t.includes('程式')||t.includes('工作坊'))base.push('程式','學術');
  if(t.includes('音樂')||t.includes('吉他'))base.push('音樂','表演');
  if(t.includes('志工')||t.includes('服務'))base.push('志工','服務');
  if(t.includes('評鑑'))base.push('評鑑','行政');
  if(t.includes('體育')||t.includes('運動'))base.push('體育','運動');
  if(base.length===0) base.push('活動','社團');
  return [...new Set(base)].slice(0,4);
}
function fuzzyMatchAct(q,t){if(!q)return true;q=q.toLowerCase();t=t.toLowerCase();if(t.includes(q))return true;let qi=0;for(let i=0;i<t.length&&qi<q.length;i++){if(t[i]===q[qi])qi++}return qi===q.length}

function applyActFilters(){
  let data=actStatusFilter==='all'?[...allActs]:allActs.filter(a=>a.status===actStatusFilter);
  const s=(document.getElementById('act-search')?.value||'').toLowerCase().trim();
  if(s){
    if(s.startsWith('#')){const tag=s.slice(1);data=data.filter(a=>getActTags(a).some(t=>t.includes(tag)))}
    else{data=data.filter(a=>fuzzyMatchAct(s,a.title)||fuzzyMatchAct(s,(a.description||''))||getActTags(a).some(t=>t.includes(s)))}
  }
  const sort=document.getElementById('act-sort')?.value||'date-desc';
  if(sort==='date-desc') data.sort((a,b)=>(b.event_date||'').localeCompare(a.event_date||''));
  else if(sort==='date-asc') data.sort((a,b)=>(a.event_date||'').localeCompare(b.event_date||''));
  else if(sort==='title-asc') data.sort((a,b)=>a.title.localeCompare(b.title));
  else if(sort==='ppl-desc') data.sort((a,b)=>(b.current_participants||0)-(a.current_participants||0));
  renderActs(data);
}
function showActSuggestions(input){
  const s=input.value.toLowerCase().trim();const box=document.getElementById('act-suggest');
  if(!s){box.classList.add('hidden');return}
  const matches=allActs.filter(a=>fuzzyMatchAct(s,a.title)||(a.description||'').toLowerCase().includes(s)).slice(0,5);
  if(matches.length===0){box.classList.add('hidden');return}
  box.classList.remove('hidden');
  box.innerHTML=matches.map(a=>`<div class="px-3 py-2 hover:bg-fju-bg cursor-pointer text-xs" onclick="document.getElementById('act-search').value='${a.title}';document.getElementById('act-suggest').classList.add('hidden');applyActFilters()"><span class="font-medium text-fju-blue">${a.title}</span> <span class="text-gray-400">${a.event_date||''}</span></div>`).join('');
}
document.addEventListener('click',e=>{if(!e.target.closest('#act-search')&&!e.target.closest('#act-suggest'))document.getElementById('act-suggest')?.classList.add('hidden')});

function renderActs(data){
  document.getElementById('act-grid').innerHTML=data.length===0?'<div class="p-8 text-center text-gray-400 col-span-2">無活動</div>':data.map(a=>{
    const tags=getActTags(a);
    return '<div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 card-hover"><div class="flex items-start justify-between mb-2"><h3 class="font-bold text-fju-blue">'+a.title+'</h3><span class="px-2 py-1 rounded-fju text-xs '+(a.status==='approved'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+'">'+a.status+'</span></div><p class="text-xs text-gray-500 mb-2">'+(a.description||'')+'</p><div class="flex flex-wrap gap-1 mb-2">'+tags.map(t=>'<span class="px-1.5 py-0.5 rounded text-[9px] bg-fju-yellow/10 text-fju-yellow cursor-pointer hover:bg-fju-yellow/20" onclick="document.getElementById(\'act-search\').value=\'#'+t+'\';applyActFilters()">#'+t+'</span>').join('')+'</div><div class="flex items-center gap-4 text-xs text-gray-400"><span><i class="fas fa-calendar mr-1"></i>'+a.event_date+'</span><span><i class="fas fa-clock mr-1"></i>'+a.start_time+'-'+a.end_time+'</span><span><i class="fas fa-users mr-1"></i>'+a.current_participants+'/'+a.max_participants+'</span></div><div class="flex gap-2 mt-3"><button onclick="registerAct('+a.id+')" class="btn-yellow px-3 py-1 text-xs">報名</button><button onclick="deleteAct('+a.id+')" class="text-fju-red text-xs px-2 py-1 rounded hover:bg-fju-red/10"><i class="fas fa-trash"></i></button></div></div>'}).join('')}
function filterAct(f){actStatusFilter=f;document.querySelectorAll('.act-f').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});document.querySelector('.act-f[data-f="'+f+'"]')?.classList.add('bg-fju-blue','text-white');document.querySelector('.act-f[data-f="'+f+'"]')?.classList.remove('bg-gray-100','text-gray-500');applyActFilters()}
function addAct(){fetch('/api/activities',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({title:document.getElementById('a-title').value,description:document.getElementById('a-desc').value,event_date:document.getElementById('a-date').value,start_time:'09:00',end_time:'17:00',max_participants:parseInt(document.getElementById('a-max').value)||50,category:'academic'})}).then(r=>r.json()).then(()=>{document.getElementById('act-modal').classList.add('hidden');location.reload()})}
function registerAct(id){fetch('/api/activities/'+id+'/register',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({user_id:1})}).then(r=>r.json()).then(res=>alert(res.message))}
function deleteAct(id){if(!confirm('確定刪除？'))return;fetch('/api/activities/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>location.reload())}
</script>
@endsection
