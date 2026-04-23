@extends('layouts.shell')
@section('title', '社團資訊')
@php $activePage = 'club-info'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-users mr-2 text-fju-yellow"></i>114 學年度社團資訊</h2>
    <div class="flex gap-2">
      <select id="club-sort" onchange="applyFilters()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="name-asc">名稱 A→Z</option>
        <option value="name-desc">名稱 Z→A</option>
        <option value="member-desc">人數 (多→少)</option>
        <option value="member-asc">人數 (少→多)</option>
      </select>
      <div class="relative"><input id="club-search" oninput="applyFilters();showSuggestions(this)" placeholder="搜尋社團（模糊搜尋）..." class="px-4 py-2 rounded-fju border border-gray-200 text-sm w-56" autocomplete="off"><div id="club-suggest" class="hidden absolute top-full left-0 w-full bg-white border border-gray-200 rounded-fju shadow-lg z-50 max-h-40 overflow-y-auto"></div></div>
      <button onclick="document.getElementById('club-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增社團</button>
    </div>
  </div>
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="club-stats"></div>
  <div class="flex gap-2 flex-wrap" id="cat-filters"></div>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4" id="club-grid"><div class="p-8 text-center text-gray-400 col-span-3"><i class="fas fa-spinner fa-spin"></i> 載入中...</div></div>
  <div id="club-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center"><div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4"><h3 class="font-bold text-fju-blue text-lg mb-4">新增社團</h3><div class="space-y-3"><input id="cl-name" placeholder="社團名稱" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><select id="cl-cat" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><option value="academic">學藝</option><option value="recreation">康樂</option><option value="sports">體育</option><option value="arts">藝術</option><option value="service">服務</option></select><textarea id="cl-desc" placeholder="描述" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea></div><div class="flex gap-2 mt-4"><button onclick="addClub()" class="flex-1 btn-yellow py-2"><i class="fas fa-check mr-1"></i>新增</button><button onclick="document.getElementById('club-modal').classList.add('hidden')" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button></div></div></div>
</div>
<script>
let allClubs=[], currentCat='all';
fetch('/api/clubs').then(r=>r.json()).then(res=>{allClubs=res.data;
  const cats={};allClubs.forEach(c=>{cats[c.category_label]=(cats[c.category_label]||0)+1});
  document.getElementById('club-stats').innerHTML='<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center"><div class="text-2xl font-black text-fju-blue">'+allClubs.length+'</div><div class="text-xs text-gray-400">社團總數</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center"><div class="text-2xl font-black text-fju-yellow">'+allClubs.filter(c=>c.type==='club').length+'</div><div class="text-xs text-gray-400">社團</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center"><div class="text-2xl font-black text-fju-green">'+allClubs.filter(c=>c.type==='association').length+'</div><div class="text-xs text-gray-400">學會</div></div><div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center"><div class="text-2xl font-black text-fju-blue">'+Object.keys(cats).length+'</div><div class="text-xs text-gray-400">分類數</div></div>';
  document.getElementById('cat-filters').innerHTML='<button onclick="filterCat(\'all\')" class="cat-btn px-3 py-1 rounded-fju bg-fju-blue text-white text-xs" data-cat="all">全部</button>'+Object.keys(cats).map(c=>'<button onclick="filterCat(\''+c+'\')" class="cat-btn px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="'+c+'">'+c+' ('+cats[c]+')</button>').join('');
  applyFilters()});

// Hashtag mapping for clubs
const clubTags={'攝影社':['攝影','藝術','戶外'],'吉他社':['音樂','表演','藝術'],'程式設計社':['程式','科技','學術'],'登山社':['戶外','體育','冒險'],'街舞社':['舞蹈','表演','體育'],'桌遊社':['桌遊','休閒','社交'],'志工社':['志工','服務','公益'],'籃球社':['籃球','體育','運動'],'烹飪社':['料理','生活','美食'],'英語社':['英語','學術','國際']};
function getClubTags(name){return clubTags[name]||[name.replace(/社$/,''),({學藝:'學術',康樂:'休閒',體育:'運動',藝術:'藝術',服務:'服務'})[name]||'社團'].filter(Boolean)}

// Fuzzy search function
function fuzzyMatch(query,text){
  if(!query)return true;
  query=query.toLowerCase();text=text.toLowerCase();
  if(text.includes(query))return true;
  // Check each char in order
  let qi=0;
  for(let i=0;i<text.length&&qi<query.length;i++){if(text[i]===query[qi])qi++}
  return qi===query.length;
}

function applyFilters(){
  let data=currentCat==='all'?[...allClubs]:allClubs.filter(c=>c.category_label===currentCat);
  const s=(document.getElementById('club-search')?.value||'').toLowerCase().trim();
  if(s){
    // Check if searching by hashtag
    if(s.startsWith('#')){
      const tag=s.slice(1);
      data=data.filter(c=>getClubTags(c.name).some(t=>t.includes(tag)));
    }else{
      data=data.filter(c=>fuzzyMatch(s,c.name)||fuzzyMatch(s,(c.description||''))||fuzzyMatch(s,(c.category_label||''))||getClubTags(c.name).some(t=>t.includes(s)));
    }
  }
  const sort=document.getElementById('club-sort')?.value||'name-asc';
  if(sort==='name-asc') data.sort((a,b)=>a.name.localeCompare(b.name));
  else if(sort==='name-desc') data.sort((a,b)=>b.name.localeCompare(a.name));
  else if(sort==='member-desc') data.sort((a,b)=>(b.member_count||0)-(a.member_count||0));
  else if(sort==='member-asc') data.sort((a,b)=>(a.member_count||0)-(b.member_count||0));
  renderClubs(data);
}

function showSuggestions(input){
  const s=input.value.toLowerCase().trim();
  const box=document.getElementById('club-suggest');
  if(!s||s.length<1){box.classList.add('hidden');return}
  const matches=allClubs.filter(c=>fuzzyMatch(s,c.name)||(c.description||'').toLowerCase().includes(s)).slice(0,6);
  if(matches.length===0){box.classList.add('hidden');return}
  box.classList.remove('hidden');
  box.innerHTML=matches.map(c=>`<div class="px-3 py-2 hover:bg-fju-bg cursor-pointer text-sm" onclick="document.getElementById('club-search').value='${c.name}';document.getElementById('club-suggest').classList.add('hidden');applyFilters()"><span class="font-medium text-fju-blue">${c.name}</span> <span class="text-xs text-gray-400">${c.category_label} · ${c.member_count}人</span></div>`).join('');
}
document.addEventListener('click',e=>{if(!e.target.closest('#club-search')&&!e.target.closest('#club-suggest'))document.getElementById('club-suggest')?.classList.add('hidden')});

function renderClubs(data){
  document.getElementById('club-grid').innerHTML=data.length===0?'<div class="p-8 text-center text-gray-400 col-span-3">無符合的社團</div>':data.map(c=>{
    const tags=getClubTags(c.name);
    return '<div class="bg-white rounded-fju p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-start justify-between"><div><h3 class="font-bold text-fju-blue text-sm">'+c.name+'</h3><span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-blue/10 text-fju-blue">'+c.category_label+'</span></div><span class="text-xs text-gray-400">'+c.member_count+' 人</span></div><p class="text-xs text-gray-500 mt-2">'+(c.description||'')+'</p><div class="flex flex-wrap gap-1 mt-2">'+tags.map(t=>'<span class="px-1.5 py-0.5 rounded text-[9px] bg-fju-yellow/10 text-fju-yellow cursor-pointer hover:bg-fju-yellow/20" onclick="document.getElementById(\'club-search\').value=\'#'+t+'\';applyFilters()">#'+t+'</span>').join('')+'</div><div class="flex gap-1 mt-2"><button onclick="deleteClub('+c.id+')" class="text-fju-red text-xs px-2 py-1 rounded hover:bg-fju-red/10"><i class="fas fa-trash"></i></button></div></div>'}).join('')}
function filterCat(cat){currentCat=cat;document.querySelectorAll('.cat-btn').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});document.querySelector('.cat-btn[data-cat="'+cat+'"]')?.classList.add('bg-fju-blue','text-white');document.querySelector('.cat-btn[data-cat="'+cat+'"]')?.classList.remove('bg-gray-100','text-gray-500');applyFilters()}
function addClub(){fetch('/api/clubs',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({name:document.getElementById('cl-name').value,category:document.getElementById('cl-cat').value,category_label:document.getElementById('cl-cat').selectedOptions[0].text,description:document.getElementById('cl-desc').value,type:'club',member_count:0,is_active:true})}).then(r=>r.json()).then(()=>{document.getElementById('club-modal').classList.add('hidden');location.reload()})}
function deleteClub(id){if(!confirm('確定刪除？'))return;fetch('/api/clubs/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>location.reload())}
</script>
@endsection
