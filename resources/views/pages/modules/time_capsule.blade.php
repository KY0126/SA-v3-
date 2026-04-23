@extends('layouts.shell')
@section('title', '數位時光膠囊')
@php $activePage = 'time-capsule'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-clock-rotate-left mr-2 text-fju-yellow"></i>數位時光膠囊</h2>
    <div class="flex gap-2">
      <select id="tc-sort" onchange="renderCapsules()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="newest">最新優先</option><option value="oldest">最舊優先</option><option value="open-soon">即將開啟</option>
      </select>
      <button onclick="document.getElementById('tc-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>建立膠囊</button>
    </div>
  </div>
  <div class="grid md:grid-cols-3 gap-4" id="tc-stats"></div>

  {{-- Capsule List --}}
  <div class="grid md:grid-cols-2 gap-4" id="tc-grid"><div class="p-8 text-center text-gray-400 col-span-2"><i class="fas fa-spinner fa-spin"></i> 載入中...</div></div>

  {{-- Create Modal --}}
  <div id="tc-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
      <h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-clock-rotate-left mr-2 text-fju-yellow"></i>建立新膠囊</h3>
      <div class="space-y-3">
        <input id="tc-title" placeholder="膠囊標題（例：114級攝影社畢業祝福）" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <select id="tc-type" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <option value="text">文字留言</option><option value="photo">照片紀念</option><option value="video">影片回憶</option><option value="mixed">綜合內容</option>
        </select>
        <textarea id="tc-msg" rows="4" placeholder="寫下給未來的自己或夥伴的話..." class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 block mb-1">封存日期</label><input type="date" id="tc-seal" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
          <div><label class="text-xs text-gray-400 block mb-1">開啟日期</label><input type="date" id="tc-open" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
        </div>
        <div>
          <label class="text-xs text-gray-400 block mb-1">接收通知方式（膠囊開啟時）</label>
          <div class="flex gap-3">
            <label class="flex items-center gap-1 text-xs"><input type="checkbox" id="tc-n-outlook" checked> Outlook 信箱</label>
            <label class="flex items-center gap-1 text-xs"><input type="checkbox" id="tc-n-sms"> SMS 簡訊</label>
            <label class="flex items-center gap-1 text-xs"><input type="checkbox" id="tc-n-line"> LINE Notify</label>
          </div>
        </div>
        <input id="tc-recipients" placeholder="共同參與者 (email, 用逗號分隔)" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
      <div class="flex gap-2 mt-4">
        <button onclick="createCapsule()" class="flex-1 btn-yellow py-2"><i class="fas fa-lock mr-1"></i>封存膠囊</button>
        <button onclick="document.getElementById('tc-modal').classList.add('hidden')" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
      </div>
    </div>
  </div>

  {{-- Open Capsule Modal --}}
  <div id="tc-open-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl text-center">
      <div id="tc-open-animation" class="py-8">
        <div class="w-24 h-24 mx-auto rounded-full bg-fju-yellow/20 flex items-center justify-center mb-4 animate-pulse"><i class="fas fa-gift text-fju-yellow text-4xl"></i></div>
        <h3 class="text-xl font-bold text-fju-blue mb-2" id="tc-open-title">-</h3>
        <p class="text-sm text-gray-500 mb-4" id="tc-open-meta">-</p>
        <div id="tc-open-content" class="p-4 rounded-fju bg-fju-bg text-left text-sm text-gray-700 mb-4"></div>
        <div class="flex gap-2 justify-center">
          <button onclick="document.getElementById('tc-open-modal').classList.add('hidden')" class="btn-yellow px-6 py-2 text-sm">關閉</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let allCapsules=[];
const demoData=[
  {id:1,title:'113級吉他社送舊祝福',type:'mixed',message:'親愛的學長姊們，感謝你們這一年的指導！永遠記得我們在中美堂的表演！🎸',creator:'王小明',sealed_at:'2025-12-01',open_at:'2026-06-15',status:'sealed',recipients:['alice@cloud.fju.edu.tw','bob@cloud.fju.edu.tw'],notify_channels:['outlook','line']},
  {id:2,title:'攝影社春季外拍回憶',type:'photo',message:'台北花季的美好回憶，看到這張照片就會想起那天的陽光和笑聲 📸',creator:'李美麗',sealed_at:'2026-03-20',open_at:'2026-09-01',status:'sealed',recipients:[],notify_channels:['outlook']},
  {id:3,title:'程式社黑客松紀念',type:'text',message:'48小時不睡覺完成的專案，雖然沒得獎但是過程超開心！給半年後的自己：你是不是已經變成大神了？😎',creator:'張大衛',sealed_at:'2025-06-01',open_at:'2026-01-01',status:'opened',opened_at:'2026-01-01',recipients:['team@cloud.fju.edu.tw'],notify_channels:['outlook','sms']},
  {id:4,title:'114學年度幹部交接期許',type:'text',message:'新學期新開始！希望半年後的我們回頭看這段話時，社團已經變得更好了。加油！💪',creator:'陳小華',sealed_at:'2026-02-15',open_at:'2027-01-15',status:'sealed',recipients:[],notify_channels:['outlook']},
];

function loadCapsules(){
  allCapsules=demoData;
  const total=allCapsules.length, sealed=allCapsules.filter(c=>c.status==='sealed').length, opened=allCapsules.filter(c=>c.status==='opened').length;
  document.getElementById('tc-stats').innerHTML=`
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">${total}</div><div class="text-xs text-gray-400">膠囊總數</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">${sealed}</div><div class="text-xs text-gray-400">封存中</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">${opened}</div><div class="text-xs text-gray-400">已開啟</div></div>`;
  renderCapsules();
}

function renderCapsules(){
  let data=[...allCapsules];
  const sort=document.getElementById('tc-sort')?.value||'newest';
  if(sort==='newest') data.sort((a,b)=>b.sealed_at.localeCompare(a.sealed_at));
  else if(sort==='oldest') data.sort((a,b)=>a.sealed_at.localeCompare(b.sealed_at));
  else if(sort==='open-soon') data.sort((a,b)=>a.open_at.localeCompare(b.open_at));
  const icons={text:'fa-file-alt',photo:'fa-camera',video:'fa-video',mixed:'fa-layer-group'};
  document.getElementById('tc-grid').innerHTML=data.map(c=>{
    const today=new Date().toISOString().split('T')[0];
    const canOpen=c.status==='sealed' && c.open_at<=today;
    const isOpened=c.status==='opened';
    const daysLeft=Math.ceil((new Date(c.open_at)-new Date())/(1000*60*60*24));
    return `<div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 card-hover ${isOpened?'border-l-4 border-l-fju-green':'border-l-4 border-l-fju-yellow'}">
      <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-2"><div class="w-10 h-10 rounded-fju ${isOpened?'bg-fju-green/10':'bg-fju-yellow/10'} flex items-center justify-center"><i class="fas ${icons[c.type]||'fa-box'} ${isOpened?'text-fju-green':'text-fju-yellow'}"></i></div>
        <div><h3 class="font-bold text-fju-blue text-sm">${c.title}</h3><span class="text-[10px] text-gray-400">建立者：${c.creator}</span></div></div>
        <span class="px-2 py-1 rounded-fju text-[10px] ${isOpened?'bg-fju-green/10 text-fju-green':'canOpen'?'bg-fju-yellow/20 text-fju-yellow animate-pulse':'bg-gray-100 text-gray-500'}">${isOpened?'已開啟':canOpen?'可開啟！':'封存中'}</span>
      </div>
      <div class="flex items-center gap-4 text-[11px] text-gray-400 mb-2">
        <span><i class="fas fa-lock mr-1"></i>封存：${c.sealed_at}</span>
        <span><i class="fas fa-calendar-check mr-1"></i>開啟：${c.open_at}</span>
        ${!isOpened?`<span class="${daysLeft<=7?'text-fju-yellow font-bold':''}"><i class="fas fa-hourglass-half mr-1"></i>${daysLeft>0?daysLeft+'天後':'可開啟'}</span>`:''}
      </div>
      <div class="flex items-center gap-1 mb-3">${(c.notify_channels||[]).map(ch=>`<span class="px-1.5 py-0.5 rounded text-[9px] ${ch==='outlook'?'bg-blue-50 text-blue-600':ch==='sms'?'bg-green-50 text-green-600':'bg-emerald-50 text-emerald-600'}"><i class="fas ${ch==='outlook'?'fa-envelope':ch==='sms'?'fa-sms':'fa-bell'} mr-0.5"></i>${ch}</span>`).join('')}</div>
      <div class="flex gap-2">
        ${canOpen?`<button onclick="openCapsule(${c.id})" class="btn-yellow px-4 py-1.5 text-xs flex-1"><i class="fas fa-gift mr-1"></i>開啟膠囊</button>`:''}
        ${isOpened?`<button onclick="viewCapsule(${c.id})" class="btn-yellow px-4 py-1.5 text-xs flex-1"><i class="fas fa-eye mr-1"></i>查看內容</button>`:''}
        <button onclick="deleteCapsule(${c.id})" class="text-fju-red text-xs px-2 py-1 rounded hover:bg-fju-red/10"><i class="fas fa-trash"></i></button>
      </div>
    </div>`}).join('');
}

function createCapsule(){
  const d={title:document.getElementById('tc-title').value,type:document.getElementById('tc-type').value,message:document.getElementById('tc-msg').value,sealed_at:document.getElementById('tc-seal').value||new Date().toISOString().split('T')[0],open_at:document.getElementById('tc-open').value,creator:'Demo User',status:'sealed',notify_channels:[],recipients:document.getElementById('tc-recipients').value.split(',').map(s=>s.trim()).filter(Boolean)};
  if(document.getElementById('tc-n-outlook').checked) d.notify_channels.push('outlook');
  if(document.getElementById('tc-n-sms').checked) d.notify_channels.push('sms');
  if(document.getElementById('tc-n-line').checked) d.notify_channels.push('line');
  d.id=allCapsules.length+10;
  allCapsules.push(d);
  document.getElementById('tc-modal').classList.add('hidden');
  alert('時光膠囊已封存！將於 '+d.open_at+' 開啟，届時將透過 '+d.notify_channels.join(', ')+' 通知您。');
  renderCapsules();
}

function openCapsule(id){
  const c=allCapsules.find(x=>x.id===id); if(!c) return;
  c.status='opened'; c.opened_at=new Date().toISOString().split('T')[0];
  document.getElementById('tc-open-title').textContent=c.title;
  document.getElementById('tc-open-meta').textContent=`封存於 ${c.sealed_at} · ${c.type} · 建立者：${c.creator}`;
  document.getElementById('tc-open-content').innerHTML=`<div class="whitespace-pre-wrap">${c.message}</div>`;
  document.getElementById('tc-open-modal').classList.remove('hidden');
  renderCapsules();
}

function viewCapsule(id){ openCapsule(id); }

function deleteCapsule(id){
  if(!confirm('確定刪除此膠囊？'))return;
  allCapsules=allCapsules.filter(c=>c.id!==id);
  loadCapsules();
}

loadCapsules();
</script>
@endsection
