@extends('layouts.shell')
@section('title', '衝突協調中心')
@php $activePage = 'conflict-coordination'; @endphp
@section('content')
<div class="space-y-6">
  {{-- Header --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-handshake mr-2 text-fju-yellow"></i>衝突協調中心</h2>
    <div class="flex gap-2">
      <button onclick="filterConflicts('all')" class="cf-f px-3 py-1 rounded-fju bg-fju-blue text-white text-xs" data-f="all">全部</button>
      <button onclick="filterConflicts('pending')" class="cf-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="pending">待處理</button>
      <button onclick="filterConflicts('negotiating')" class="cf-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="negotiating">協商中</button>
      <button onclick="filterConflicts('resolved')" class="cf-f px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-f="resolved">已解決</button>
    </div>
  </div>

  {{-- Conflict Stats --}}
  <div class="grid md:grid-cols-4 gap-4" id="conflict-stats">
    <div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div>
  </div>

  {{-- Conflict List --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>衝突事件列表</h3></div>
    <div id="conflict-list"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- Conflict Detail Modal --}}
  <div id="conflict-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg w-full max-w-4xl mx-4 shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
      {{-- Modal Header --}}
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-fju-blue">
        <h3 class="font-bold text-white text-lg"><i class="fas fa-handshake mr-2 text-fju-yellow"></i>衝突協調詳情 <span id="cd-title" class="text-fju-yellow"></span></h3>
        <button onclick="closeDetailModal()" class="text-white/60 hover:text-white"><i class="fas fa-times text-lg"></i></button>
      </div>
      {{-- Modal Body --}}
      <div class="flex-1 overflow-y-auto p-6 space-y-4">
        {{-- Conflict Info --}}
        <div class="grid md:grid-cols-2 gap-4">
          <div class="bg-fju-blue/5 rounded-fju p-4 border border-fju-blue/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">A</div><span class="font-bold text-fju-blue" id="cd-party-a">—</span></div>
            <div class="text-xs text-gray-500" id="cd-info-a"></div>
            <div class="mt-2 flex gap-2">
              <button onclick="sendEmail('a')" class="text-xs px-3 py-1.5 rounded-fju bg-fju-blue text-white hover:bg-fju-blue-light" id="btn-email-a"><i class="fas fa-envelope mr-1"></i>發送郵件通知</button>
              <span id="email-status-a" class="text-xs text-fju-green hidden"><i class="fas fa-check-circle mr-1"></i>已發送</span>
            </div>
            <button onclick="confirmResolution('a')" class="mt-2 w-full py-2 rounded-fju text-sm font-bold transition-all" id="btn-confirm-a"><i class="fas fa-check-circle mr-1"></i>甲方確認協商結果</button>
          </div>
          <div class="bg-fju-yellow/5 rounded-fju p-4 border border-fju-yellow/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">B</div><span class="font-bold text-fju-blue" id="cd-party-b">—</span></div>
            <div class="text-xs text-gray-500" id="cd-info-b"></div>
            <div class="mt-2 flex gap-2">
              <button onclick="sendEmail('b')" class="text-xs px-3 py-1.5 rounded-fju bg-fju-yellow text-fju-blue hover:bg-fju-yellow-light" id="btn-email-b"><i class="fas fa-envelope mr-1"></i>發送郵件通知</button>
              <span id="email-status-b" class="text-xs text-fju-green hidden"><i class="fas fa-check-circle mr-1"></i>已發送</span>
            </div>
            <button onclick="confirmResolution('b')" class="mt-2 w-full py-2 rounded-fju text-sm font-bold transition-all" id="btn-confirm-b"><i class="fas fa-check-circle mr-1"></i>乙方確認協商結果</button>
          </div>
        </div>

        {{-- Conflict meta --}}
        <div class="flex flex-wrap gap-3 text-xs text-gray-500">
          <span><i class="fas fa-map-marker-alt mr-1 text-fju-yellow"></i>場地：<b id="cd-venue">—</b></span>
          <span><i class="fas fa-calendar mr-1 text-fju-yellow"></i>日期：<b id="cd-date">—</b></span>
          <span><i class="fas fa-clock mr-1 text-fju-yellow"></i>時段：<b id="cd-slot">—</b></span>
          <span><i class="fas fa-flag mr-1 text-fju-yellow"></i>狀態：<b id="cd-status">—</b></span>
        </div>

        {{-- AI Suggestions --}}
        <div class="bg-gradient-to-r from-fju-blue/5 to-fju-yellow/5 rounded-fju p-4 border border-fju-blue/10">
          <h4 class="text-sm font-bold text-fju-blue mb-2"><i class="fas fa-robot mr-1 text-fju-yellow"></i>AI 協商建議</h4>
          <div id="cd-ai-suggestions" class="space-y-2"></div>
        </div>

        {{-- Chat Section --}}
        <div class="bg-white rounded-fju border border-gray-200">
          <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
            <h4 class="text-sm font-bold text-fju-blue"><i class="fas fa-comments mr-1 text-fju-yellow"></i>即時協商對話</h4>
            <span class="text-[10px] text-gray-400" id="cd-timer"></span>
          </div>
          <div id="cd-chat-messages" class="p-4 space-y-3 max-h-48 overflow-y-auto bg-fju-bg"></div>
          <div class="p-3 border-t border-gray-100 flex gap-2">
            <select id="chat-party" class="px-3 py-2 rounded-fju border border-gray-200 text-xs">
              <option value="a">甲方</option>
              <option value="b">乙方</option>
            </select>
            <input id="chat-input" type="text" placeholder="輸入訊息..." class="flex-1 px-4 py-2 rounded-fju border border-gray-200 text-sm" onkeypress="if(event.key==='Enter')sendChat()">
            <button onclick="sendChat()" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-paper-plane"></i></button>
          </div>
        </div>

        {{-- Resolution status --}}
        <div id="cd-resolution-status" class="hidden p-4 rounded-fju bg-fju-green/10 border border-fju-green/20 text-center">
          <i class="fas fa-check-circle text-fju-green text-2xl mb-2"></i>
          <p class="font-bold text-fju-green" id="cd-resolution-msg">衝突已解決</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let allConflicts=[], currentConflictId=null, currentFilter='all';

function loadConflicts(){
  fetch('/api/conflicts').then(r=>r.json()).then(res=>{
    allConflicts=res.data;
    renderStats();
    renderConflicts();
  });
}

function renderStats(){
  const total=allConflicts.length, pending=allConflicts.filter(c=>c.status==='pending').length,
    negotiating=allConflicts.filter(c=>c.status==='negotiating').length,
    resolved=allConflicts.filter(c=>c.status==='resolved').length;
  document.getElementById('conflict-stats').innerHTML=
    `<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">${total}</div><div class="text-xs text-gray-400">衝突總數</div></div>
     <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-red">${pending}</div><div class="text-xs text-gray-400">待處理</div></div>
     <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">${negotiating}</div><div class="text-xs text-gray-400">協商中</div></div>
     <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">${resolved}</div><div class="text-xs text-gray-400">已解決</div></div>`;
}

function renderConflicts(){
  const data = currentFilter==='all' ? allConflicts : allConflicts.filter(c=>c.status===currentFilter);
  if(data.length===0){document.getElementById('conflict-list').innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-check-circle mr-2 text-fju-green"></i>目前無衝突事件</div>';return}
  const sc={'pending':'bg-fju-red/10 text-fju-red','negotiating':'bg-fju-yellow/20 text-fju-yellow','resolved':'bg-fju-green/10 text-fju-green','escalated':'bg-purple-100 text-purple-600'};
  const sl={'pending':'待處理','negotiating':'協商中','resolved':'已解決','escalated':'已升級'};
  document.getElementById('conflict-list').innerHTML=`<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">#</th><th class="p-4">甲方</th><th class="p-4">乙方</th><th class="p-4">場地</th><th class="p-4">日期/時段</th><th class="p-4">狀態</th><th class="p-4">確認</th><th class="p-4">操作</th></tr></thead><tbody>`+data.map(c=>{
    const confirmA=c.party_a_confirmed?'<i class="fas fa-check-circle text-fju-green"></i>':'<i class="fas fa-times-circle text-gray-300"></i>';
    const confirmB=c.party_b_confirmed?'<i class="fas fa-check-circle text-fju-green"></i>':'<i class="fas fa-times-circle text-gray-300"></i>';
    return `<tr class="border-t border-gray-50 hover:bg-gray-50 cursor-pointer" onclick="openDetail(${c.id})"><td class="p-4 text-xs text-gray-400">#${c.id}</td><td class="p-4 font-medium text-fju-blue">${c.party_a||'—'}</td><td class="p-4 font-medium text-fju-blue">${c.party_b||'—'}</td><td class="p-4 text-xs">${c.venue_name||'—'}</td><td class="p-4 text-xs text-gray-500">${c.conflict_date||''} ${c.time_slot||''}</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs ${sc[c.status]||'bg-gray-100 text-gray-500'}">${sl[c.status]||c.status}</span></td><td class="p-4 text-sm">${confirmA} ${confirmB}</td><td class="p-4"><button class="btn-yellow px-3 py-1 text-xs">詳情</button></td></tr>`}).join('')+'</tbody></table>';
}

function filterConflicts(f){
  currentFilter=f;
  document.querySelectorAll('.cf-f').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector(`.cf-f[data-f="${f}"]`)?.classList.add('bg-fju-blue','text-white');
  document.querySelector(`.cf-f[data-f="${f}"]`)?.classList.remove('bg-gray-100','text-gray-500');
  renderConflicts();
}

function openDetail(id){
  currentConflictId=id;
  fetch(`/api/conflicts/${id}`).then(r=>r.json()).then(res=>{
    const c=res.data;
    document.getElementById('cd-title').textContent=` #${c.id}`;
    document.getElementById('cd-party-a').textContent=c.party_a||'甲方';
    document.getElementById('cd-party-b').textContent=c.party_b||'乙方';
    document.getElementById('cd-venue').textContent=c.venue_name||'—';
    document.getElementById('cd-date').textContent=c.conflict_date||'—';
    document.getElementById('cd-slot').textContent=c.time_slot||'—';
    document.getElementById('cd-status').textContent={'pending':'待處理','negotiating':'協商中','resolved':'已解決'}[c.status]||c.status;
    document.getElementById('cd-info-a').innerHTML=res.reservation_a?`預約日期: ${res.reservation_a.reservation_date}<br>時段: ${res.reservation_a.start_time}-${res.reservation_a.end_time}`:'無關聯預約';
    document.getElementById('cd-info-b').innerHTML=res.reservation_b?`預約日期: ${res.reservation_b.reservation_date}<br>時段: ${res.reservation_b.start_time}-${res.reservation_b.end_time}`:'申請中的預約';
    // Email status
    document.getElementById('email-status-a').classList.toggle('hidden', !c.email_sent_a);
    document.getElementById('btn-email-a').classList.toggle('hidden', c.email_sent_a);
    document.getElementById('email-status-b').classList.toggle('hidden', !c.email_sent_b);
    document.getElementById('btn-email-b').classList.toggle('hidden', c.email_sent_b);
    // Confirm buttons
    updateConfirmBtn('a', c.party_a_confirmed, c.status==='resolved');
    updateConfirmBtn('b', c.party_b_confirmed, c.status==='resolved');
    // Resolution status
    if(c.status==='resolved'){
      document.getElementById('cd-resolution-status').classList.remove('hidden');
      document.getElementById('cd-resolution-msg').textContent='🎉 衝突已解決！雙方均已確認，預約已自動更新。';
    } else {
      document.getElementById('cd-resolution-status').classList.add('hidden');
    }
    // Chat messages
    renderChatMessages(c.chat_messages || []);
    // AI suggestions
    loadAISuggestions();
    document.getElementById('conflict-detail-modal').classList.remove('hidden');
  });
}

function updateConfirmBtn(party, confirmed, resolved){
  const btn=document.getElementById('btn-confirm-'+party);
  if(confirmed||resolved){
    btn.className='mt-2 w-full py-2 rounded-fju text-sm font-bold bg-fju-green/10 text-fju-green cursor-default';
    btn.innerHTML='<i class="fas fa-check-circle mr-1"></i>'+(confirmed?'已確認':'已解決');
    btn.disabled=true;
  } else {
    btn.className='mt-2 w-full py-2 rounded-fju text-sm font-bold btn-yellow';
    btn.innerHTML='<i class="fas fa-check-circle mr-1"></i>'+(party==='a'?'甲方':'乙方')+'確認協商結果';
    btn.disabled=false;
  }
}

function renderChatMessages(msgs){
  const el=document.getElementById('cd-chat-messages');
  if(!msgs||msgs.length===0){el.innerHTML='<div class="text-center text-gray-400 text-xs py-4"><i class="fas fa-comments mr-1"></i>尚無對話訊息</div>';return}
  el.innerHTML=msgs.map(m=>{
    const isA=m.party==='a';
    return `<div class="flex ${isA?'justify-start':'justify-end'}"><div class="max-w-[70%] px-3 py-2 rounded-fju text-sm ${isA?'bg-fju-blue/10 text-fju-blue':'bg-fju-yellow/20 text-fju-blue'}"><div class="text-[10px] ${isA?'text-fju-blue/60':'text-fju-yellow'} font-bold mb-1">${m.sender}</div><div>${m.message}</div><div class="text-[9px] text-gray-400 mt-1">${new Date(m.timestamp).toLocaleTimeString('zh-TW')}</div></div></div>`;
  }).join('');
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
    if(res.success){
      alert(res.message);
      document.getElementById('email-status-'+party).classList.remove('hidden');
      document.getElementById('btn-email-'+party).classList.add('hidden');
    }
  });
}

function confirmResolution(party){
  if(!currentConflictId)return;
  if(!confirm(`確認要以${party==='a'?'甲方':'乙方'}身份確認協商結果嗎？`))return;
  fetch(`/api/conflicts/${currentConflictId}/confirm`,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({party})}).then(r=>r.json()).then(res=>{
    alert(res.message);
    updateConfirmBtn(party, true, res.both_confirmed);
    if(res.both_confirmed){
      document.getElementById('cd-resolution-status').classList.remove('hidden');
      document.getElementById('cd-resolution-msg').textContent='🎉 衝突已解決！雙方均已確認，預約已自動更新。';
      document.getElementById('cd-status').textContent='已解決';
      loadConflicts(); // refresh list
    }
  });
}

function loadAISuggestions(){
  const el=document.getElementById('cd-ai-suggestions');
  el.innerHTML=[
    {desc:'甲方改至 SF 134 教室',confidence:0.85},
    {desc:'時段分割：各使用一半時間',confidence:0.78},
    {desc:'乙方延後至隔天同時段',confidence:0.72}
  ].map((s,i)=>`<div class="flex items-center gap-3 p-2 rounded-fju ${i===0?'bg-fju-green/10 border border-fju-green/20':'bg-white border border-gray-100'}"><span class="w-6 h-6 rounded-full ${i===0?'bg-fju-green text-white':'bg-gray-200 text-gray-500'} flex items-center justify-center text-xs font-bold">${i+1}</span><span class="flex-1 text-sm text-gray-700">${s.desc}</span><span class="text-xs px-2 py-0.5 rounded-full ${s.confidence>0.8?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow'}">${(s.confidence*100).toFixed(0)}%</span></div>`).join('');
}

function closeDetailModal(){document.getElementById('conflict-detail-modal').classList.add('hidden')}

loadConflicts();
</script>
@endsection
