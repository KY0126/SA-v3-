@extends('layouts.shell')
@section('title', 'AI 資訊概覽')
@php $activePage = 'ai-overview'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI 資訊概覽</h2>
    <select id="ai-role-view" onchange="switchAIRole(this.value)" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
      <option value="all">全部角色</option>
      <option value="admin" {{ ($role??'') === 'admin' ? 'selected' : '' }}>課指組/處室</option>
      <option value="officer" {{ ($role??'') === 'officer' ? 'selected' : '' }}>社團幹部</option>
      <option value="professor" {{ ($role??'') === 'professor' ? 'selected' : '' }}>指導教授</option>
      <option value="student" {{ ($role??'') === 'student' ? 'selected' : '' }}>一般學生</option>
      <option value="it" {{ ($role??'') === 'it' ? 'selected' : '' }}>資訊中心</option>
    </select>
  </div>

  {{-- AI System Architecture --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-project-diagram mr-2 text-fju-yellow"></i>AI 系統設計邏輯</h3>
    <div class="grid md:grid-cols-3 gap-4 mb-4">
      <div class="p-4 rounded-fju bg-fju-blue/5 border border-fju-blue/10 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-fju-blue flex items-center justify-center mb-2"><i class="fas fa-search text-white text-lg"></i></div>
        <h4 class="font-bold text-fju-blue text-sm">RAG 法規查詢</h4>
        <p class="text-xs text-gray-500 mt-1">利用 Retrieval-Augmented Generation 技術比對輔仁大學課外活動法規，自動判斷申請案件是否符合規範。</p>
        <div class="mt-2 text-[10px] text-fju-blue bg-fju-blue/5 rounded px-2 py-1">Dify RAG + Embedding</div>
      </div>
      <div class="p-4 rounded-fju bg-fju-yellow/5 border border-fju-yellow/10 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-fju-yellow flex items-center justify-center mb-2"><i class="fas fa-robot text-fju-blue text-lg"></i></div>
        <h4 class="font-bold text-fju-blue text-sm">AI 預審引擎</h4>
        <p class="text-xs text-gray-500 mt-1">活動企劃書自動審核引擎，檢查參與人數、預算合理性、場地安全等指標，產出風險評估報告。</p>
        <div class="mt-2 text-[10px] text-fju-yellow bg-fju-yellow/10 rounded px-2 py-1">GPT-4 + 規則引擎</div>
      </div>
      <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-fju-green flex items-center justify-center mb-2"><i class="fas fa-handshake text-white text-lg"></i></div>
        <h4 class="font-bold text-fju-blue text-sm">AI 衝突協商</h4>
        <p class="text-xs text-gray-500 mt-1">當場地預約發生衝突時，AI 自動產出三個替代方案並計算各方案信心度，協助雙方快速達成共識。</p>
        <div class="mt-2 text-[10px] text-fju-green bg-fju-green/10 rounded px-2 py-1">多方博弈演算法</div>
      </div>
    </div>
    <div class="p-3 rounded-fju bg-fju-bg text-xs text-gray-500">
      <i class="fas fa-info-circle mr-1 text-fju-yellow"></i>
      <b>設計邏輯：</b>所有 AI 功能採用三層防護架構 — 第一層為規則引擎自動過濾，第二層為 RAG 語義比對法規，第三層為 GPT-4 深度分析。每次 AI 決策均有完整審計日誌記錄於 ai_review_logs 資料表。
    </div>
  </div>

  {{-- Role-based AI Features --}}
  <div id="ai-features-container">
    {{-- Will be filled by JS --}}
  </div>

  {{-- Live AI Demo with Animation --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-play-circle mr-2 text-fju-yellow"></i>AI 功能模擬演示</h3>
    <div class="flex gap-2 mb-4">
      <button onclick="runDemo('prereview')" class="demo-btn px-4 py-2 rounded-fju bg-fju-blue text-white text-xs" data-demo="prereview"><i class="fas fa-search mr-1"></i>AI 預審</button>
      <button onclick="runDemo('proposal')" class="demo-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-demo="proposal"><i class="fas fa-file-alt mr-1"></i>企劃生成</button>
      <button onclick="runDemo('conflict')" class="demo-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-demo="conflict"><i class="fas fa-handshake mr-1"></i>衝突協商</button>
    </div>
    <div id="demo-area" class="min-h-[200px] rounded-fju bg-fju-bg p-4 relative overflow-hidden">
      <div id="demo-content" class="space-y-3">
        <p class="text-center text-gray-400 text-sm py-8">請選擇上方按鈕開始 AI 功能演示</p>
      </div>
    </div>
  </div>

  {{-- AI Proposal Generator (merged from ai-guide) --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-wand-magic-sparkles mr-2 text-fju-yellow"></i>AI 企劃生成器</h3>
    <div class="space-y-3">
      <input id="pg-title" placeholder="活動名稱" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <textarea id="pg-desc" placeholder="活動說明" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
      <div class="grid grid-cols-2 gap-3"><input type="number" id="pg-ppl" placeholder="預計人數" value="50" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"><input type="date" id="pg-date" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
      <button onclick="genProposal()" class="btn-yellow px-6 py-2 text-sm w-full"><i class="fas fa-robot mr-1"></i>生成企劃書</button>
    </div>
    <div id="proposal-result" class="hidden mt-4 p-4 rounded-fju bg-fju-bg text-sm"></div>
  </div>
</div>

<script>
// Role-based AI features data
const aiFeatures={
  admin:[
    {icon:'fa-chart-bar',name:'審核統計看板',desc:'即時查看所有 AI 預審通過率、風險分布、審核時間等統計數據，支援匯出報表。',color:'#003153'},
    {icon:'fa-gavel',name:'RAG 法規比對',desc:'批量上傳活動企劃書，AI 自動比對校內法規並標記不合規條款，提供修改建議。',color:'#DAA520'},
    {icon:'fa-user-check',name:'信用預警系統',desc:'AI 監控全校社團信用積分，自動發送預警通知，防止信用崩潰。',color:'#008000'},
    {icon:'fa-robot',name:'自動核准建議',desc:'對低風險案件，AI 自動產出核准建議，加速審批流程。',color:'#004070'}
  ],
  officer:[
    {icon:'fa-file-alt',name:'企劃書 AI 生成',desc:'輸入活動基本資訊，AI 自動生成完整企劃書，含預算規劃、風險評估、時程規劃。',color:'#003153'},
    {icon:'fa-search',name:'AI 預審自檢',desc:'送出前先透過 AI 預審系統自我檢查，確保通過率。系統會標示需修改項目。',color:'#DAA520'},
    {icon:'fa-handshake',name:'衝突 AI 協商',desc:'場地衝突時 AI 提供三方案供選擇，含信心度評分和替代建議。',color:'#008000'}
  ],
  professor:[
    {icon:'fa-clipboard-check',name:'審查輔助',desc:'AI 自動摘要學生提交的企劃書，標示重點和潛在風險，協助快速審查。',color:'#003153'},
    {icon:'fa-chart-line',name:'社團表現分析',desc:'AI 分析指導社團的活動成效、參與率、預算執行率等指標趨勢。',color:'#DAA520'}
  ],
  student:[
    {icon:'fa-lightbulb',name:'活動推薦',desc:'根據過往參與記錄和興趣標籤，AI 推薦最適合的社團活動。',color:'#003153'},
    {icon:'fa-search',name:'法規查詢助手',desc:'用自然語言詢問校內活動相關法規，AI 搜尋法規庫並回答。',color:'#DAA520'},
    {icon:'fa-file-alt',name:'企劃書 AI 生成',desc:'填入活動概要，AI 自動產出規格完整的企劃書草稿。',color:'#008000'}
  ],
  it:[
    {icon:'fa-server',name:'AI 服務監控',desc:'監控 AI API 回應時間、成功率、Token 使用量，確保服務穩定。',color:'#003153'},
    {icon:'fa-shield-alt',name:'WAF + 安全分析',desc:'AI 分析 API 呼叫模式，偵測異常行為，自動觸發 WAF 防護。',color:'#C0392B'},
    {icon:'fa-database',name:'資料品質監控',desc:'AI 定期掃描資料庫一致性，偵測髒資料並產出清理建議。',color:'#DAA520'}
  ]
};

function switchAIRole(r){
  const roles=r==='all'?['admin','officer','professor','student','it']:[r];
  const names={admin:'課指組/處室',officer:'社團幹部',professor:'指導教授',student:'一般學生',it:'資訊中心'};
  const icons={admin:'fa-user-tie',officer:'fa-user-shield',professor:'fa-chalkboard-teacher',student:'fa-user-graduate',it:'fa-server'};
  let html='';
  roles.forEach(role=>{
    const features=aiFeatures[role]||[];
    html+='<div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 mb-4">';
    html+='<h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas '+icons[role]+' mr-2 text-fju-yellow"></i>'+names[role]+' — 可用 AI 功能</h3>';
    html+='<div class="grid md:grid-cols-'+Math.min(features.length,3)+' gap-3">';
    features.forEach(f=>{
      html+='<div class="p-4 rounded-fju border border-gray-100 hover:shadow-md transition-all card-hover"><div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju flex items-center justify-center" style="background:'+f.color+'20"><i class="fas '+f.icon+'" style="color:'+f.color+'"></i></div><span class="font-bold text-fju-blue text-xs">'+f.name+'</span></div><p class="text-[11px] text-gray-500">'+f.desc+'</p></div>';
    });
    html+='</div></div>';
  });
  document.getElementById('ai-features-container').innerHTML=html;
}
// Init
switchAIRole(document.getElementById('ai-role-view').value);

// Demo animations
function runDemo(type){
  document.querySelectorAll('.demo-btn').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector('.demo-btn[data-demo="'+type+'"]')?.classList.add('bg-fju-blue','text-white');
  document.querySelector('.demo-btn[data-demo="'+type+'"]')?.classList.remove('bg-gray-100','text-gray-500');
  const area=document.getElementById('demo-content');
  area.innerHTML='<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-fju-blue text-2xl"></i><p class="text-sm text-gray-500 mt-2">AI 模擬運算中...</p></div>';

  setTimeout(()=>{
    if(type==='prereview') showPrereviewDemo(area);
    else if(type==='proposal') showProposalDemo(area);
    else if(type==='conflict') showConflictDemo(area);
  }, 1200);
}

function animateSteps(area, steps, delay){
  let html='';
  steps.forEach((step,i)=>{
    setTimeout(()=>{
      html+=step;
      area.innerHTML=html;
      area.scrollTop=area.scrollHeight;
    }, i*delay);
  });
}

function showPrereviewDemo(area){
  const steps=[
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn"><i class="fas fa-file-upload text-fju-blue"></i><span class="text-sm">收到企劃書：「攝影社春季外拍」，預計 60 人</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-yellow/10 animate-fadeIn mt-2"><i class="fas fa-cog fa-spin text-fju-yellow"></i><span class="text-sm">Step 1: 規則引擎 — 人數 60 <= 80 ✓ | 預算 18,000 ✓</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn mt-2"><i class="fas fa-search text-fju-blue"></i><span class="text-sm">Step 2: RAG 法規比對 — 匹配「校園活動安全管理辦法」第 12 條</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-green/10 animate-fadeIn mt-2"><i class="fas fa-robot text-fju-green"></i><span class="text-sm">Step 3: GPT-4 深度分析 — 風險等級: low</span></div>',
    '<div class="mt-3 p-3 rounded-fju bg-fju-green/10 border border-fju-green/20 animate-fadeIn"><div class="flex items-center gap-2"><i class="fas fa-check-circle text-fju-green text-xl"></i><div><span class="font-bold text-fju-green">AI 預審通過</span><br><span class="text-xs text-gray-500">風險等級: 低 | 信心度: 92% | 審核時間: 1.3s</span></div></div></div>'
  ];
  animateSteps(area, steps, 800);
}

function showProposalDemo(area){
  const steps=[
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn"><i class="fas fa-keyboard text-fju-blue"></i><span class="text-sm">輸入：活動名稱「程式設計工作坊」，40 人</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-yellow/10 animate-fadeIn mt-2"><i class="fas fa-cog fa-spin text-fju-yellow"></i><span class="text-sm">AI 分析中... 匹配歷史成功企劃書模板</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn mt-2"><i class="fas fa-file-alt text-fju-blue"></i><span class="text-sm">生成預算: 場地費 $3,000 + 講師費 $5,000 + 材料費 $4,000 = $12,000</span></div>',
    '<div class="mt-3 p-3 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20 animate-fadeIn"><div class="text-sm"><b class="text-fju-blue">AI 企劃書摘要</b><br>一、活動宗旨：培養學生程式設計能力...<br>二、時間地點：SF 134 教室<br>三、預算：NT$12,000<br>四、AI 風險評估：低風險 (信心度 89%)</div></div>'
  ];
  animateSteps(area, steps, 800);
}

function showConflictDemo(area){
  const steps=[
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-red/10 animate-fadeIn"><i class="fas fa-exclamation-triangle text-fju-red"></i><span class="text-sm">衝突偵測：攝影社 vs 吉他社 — 中美堂 04/15 14:00-17:00</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-yellow/10 animate-fadeIn mt-2"><i class="fas fa-cog fa-spin text-fju-yellow"></i><span class="text-sm">AI 分析雙方歷史預約、優先權、場地替代方案...</span></div>',
    '<div class="mt-2 space-y-1 animate-fadeIn"><div class="flex items-center gap-2 p-2 rounded bg-fju-green/10 border border-fju-green/20"><span class="w-6 h-6 rounded-full bg-fju-green text-white flex items-center justify-center text-xs font-bold">1</span><span class="text-sm flex-1">方案一：攝影社改至 SF 134 教室</span><span class="text-xs px-2 py-0.5 rounded-full bg-fju-green/20 text-fju-green">85%</span></div><div class="flex items-center gap-2 p-2 rounded bg-white border border-gray-100"><span class="w-6 h-6 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold">2</span><span class="text-sm flex-1">方案二：時段分割各用一半</span><span class="text-xs px-2 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow">78%</span></div><div class="flex items-center gap-2 p-2 rounded bg-white border border-gray-100"><span class="w-6 h-6 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-xs font-bold">3</span><span class="text-sm flex-1">方案三：吉他社延後至隔天</span><span class="text-xs px-2 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow">72%</span></div></div>',
    '<div class="mt-3 p-3 rounded-fju bg-fju-blue/5 border border-fju-blue/10 animate-fadeIn"><i class="fas fa-lightbulb text-fju-yellow mr-1"></i><span class="text-xs text-gray-600">AI 推薦方案一（信心度最高 85%），雙方點擊「確認」後自動更新行事曆與預約記錄。</span></div>'
  ];
  animateSteps(area, steps, 900);
}

// Proposal generator
function genProposal(){
  fetch('/api/ai/generate-proposal',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({title:document.getElementById('pg-title').value,description:document.getElementById('pg-desc').value,participants:parseInt(document.getElementById('pg-ppl').value),date:document.getElementById('pg-date').value})}).then(r=>r.json()).then(res=>{
    const b=document.getElementById('proposal-result');b.classList.remove('hidden');
    b.innerHTML='<h4 class="font-bold text-fju-blue mb-2">'+res.title+'</h4><div class="space-y-1 text-xs text-gray-600">'+Object.values(res.sections).map(s=>Array.isArray(s)?s.join('<br>'):'<p>'+s+'</p>').join('')+'</div><div class="mt-3 p-2 rounded bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>AI 預審：'+res.ai_review.risk_level+' 風險 (信心度 '+res.ai_review.confidence+')</div>';
  });
}
</script>

<style>
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.animate-fadeIn{animation:fadeIn 0.5s ease-out}
</style>
@endsection
