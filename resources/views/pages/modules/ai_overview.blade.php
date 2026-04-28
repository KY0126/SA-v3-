@extends('layouts.shell')
@section('title', 'AI 資訊概覽')
@php $activePage = 'ai-overview'; $currentRole = $role ?? 'student'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI 資訊概覽</h2>
    <select id="ai-role-view" onchange="switchAIRole(this.value)" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
      <option value="all">全部角色</option>
      <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>課指組審核員</option>
      <option value="officer" {{ $currentRole === 'officer' ? 'selected' : '' }}>社團幹部</option>
      <option value="professor" {{ $currentRole === 'professor' ? 'selected' : '' }}>指導教授</option>
      <option value="student" {{ $currentRole === 'student' ? 'selected' : '' }}>一般學生</option>
      <option value="it" {{ $currentRole === 'it' ? 'selected' : '' }}>資訊中心</option>
    </select>
  </div>

  {{-- AI Architecture --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-project-diagram mr-2 text-fju-yellow"></i>AI 系統設計邏輯與架構</h3>
    <div class="grid md:grid-cols-3 gap-4 mb-4">
      <div class="p-4 rounded-fju bg-fju-blue/5 border border-fju-blue/10 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-fju-blue flex items-center justify-center mb-2"><i class="fas fa-search text-white text-lg"></i></div>
        <h4 class="font-bold text-fju-blue text-sm">RAG 法規查詢</h4>
        <p class="text-xs text-gray-500 mt-1">Dify RAG + Embedding 技術比對 8 項校內法規，自動判斷申請是否合規。</p>
        <div class="mt-2 text-[10px] text-fju-blue bg-fju-blue/5 rounded px-2 py-1">Dify RAG + Embedding</div>
      </div>
      <div class="p-4 rounded-fju bg-fju-yellow/5 border border-fju-yellow/10 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-fju-yellow flex items-center justify-center mb-2"><i class="fas fa-robot text-fju-blue text-lg"></i></div>
        <h4 class="font-bold text-fju-blue text-sm">AI 預審引擎</h4>
        <p class="text-xs text-gray-500 mt-1">三層防護：規則引擎 → RAG 語義比對 → GPT-4 深度分析。</p>
        <div class="mt-2 text-[10px] text-fju-yellow bg-fju-yellow/10 rounded px-2 py-1">GPT-4 + 規則引擎</div>
      </div>
      <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10 text-center">
        <div class="w-12 h-12 mx-auto rounded-full bg-fju-green flex items-center justify-center mb-2"><i class="fas fa-handshake text-white text-lg"></i></div>
        <h4 class="font-bold text-fju-blue text-sm">AI 衝突協商</h4>
        <p class="text-xs text-gray-500 mt-1">場地衝突時 AI 提供建議 → 透過「輔寶 AI 助理」進行即時協商。</p>
        <div class="mt-2 text-[10px] text-fju-green bg-fju-green/10 rounded px-2 py-1">多方博弈演算法</div>
      </div>
    </div>
    <div class="p-3 rounded-fju bg-fju-bg text-xs text-gray-500">
      <i class="fas fa-info-circle mr-1 text-fju-yellow"></i>
      <b>設計邏輯：</b>三層防護 — 第一層規則引擎自動過濾，第二層 RAG 語義比對法規，第三層 GPT-4 深度分析。每次 AI 決策均有完整審計日誌。
      <b class="ml-2">衝突協商</b>已整合至「輔寶 AI 助理」，發生衝突時直接透過右下角 🐕 輔寶諮詢。
    </div>
    <div class="mt-3 p-3 rounded-fju bg-fju-blue/5 border border-fju-blue/10 text-xs text-gray-600">
      <i class="fas fa-lightbulb mr-1 text-fju-yellow"></i>
      <b>AI 運作方式說明：</b>目前 AI 功能採模擬引擎實作。建議未來可透過 <b>GitHub Models</b> 功能，使用 GitHub 個人存取權杖 (PAT) 呼叫 AI 模型 API（如 GPT-4o、Phi-3 等），讓系統 AI 實際運作。可直接在 GitHub Copilot 當中選用模型，透過 REST API 串接至本系統。
    </div>
  </div>

  {{-- Role-based AI Features --}}
  <div id="ai-features-container"></div>

  {{-- AI Pre-Review Card (with embedded demo) --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue"><i class="fas fa-search mr-2 text-fju-yellow"></i>AI 預審檢查（實作）</h3>
      <button onclick="togglePrereviewDemo()" class="text-xs px-3 py-1.5 rounded-fju bg-fju-blue/10 text-fju-blue hover:bg-fju-blue/20 transition-colors"><i class="fas fa-play-circle mr-1"></i>查看模擬演示</button>
    </div>
    <div id="prereview-demo-area" class="hidden mb-4 rounded-fju bg-fju-bg p-4 border border-gray-100">
      <div id="prereview-demo-content" class="space-y-2"></div>
    </div>
    <div class="grid md:grid-cols-2 gap-4">
      <div class="space-y-3">
        <input type="number" id="pr-ppl" value="60" placeholder="預計參與人數" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <button onclick="doPreReview()" class="btn-yellow px-6 py-2 text-sm w-full"><i class="fas fa-robot mr-1"></i>執行 AI 預審</button>
      </div>
      <div id="prereview-result" class="p-4 rounded-fju bg-fju-bg text-sm text-gray-500">點擊左方按鈕開始預審</div>
    </div>
  </div>

  {{-- AI Proposal Generator Card (with embedded demo) --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue"><i class="fas fa-wand-magic-sparkles mr-2 text-fju-yellow"></i>AI 企劃生成器（實作）</h3>
      <button onclick="toggleProposalDemo()" class="text-xs px-3 py-1.5 rounded-fju bg-fju-yellow/10 text-fju-yellow hover:bg-fju-yellow/20 transition-colors"><i class="fas fa-play-circle mr-1"></i>查看模擬演示</button>
    </div>
    <div id="proposal-demo-area" class="hidden mb-4 rounded-fju bg-fju-bg p-4 border border-gray-100">
      <div id="proposal-demo-content" class="space-y-2"></div>
    </div>
    <div class="space-y-3">
      <input id="pg-title" placeholder="活動名稱" value="攝影社春季外拍" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <textarea id="pg-desc" placeholder="活動說明" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">走訪台北花季景點，學習構圖技巧</textarea>
      <div class="grid grid-cols-2 gap-3"><input type="number" id="pg-ppl" placeholder="預計人數" value="50" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"><input type="date" id="pg-date" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
      <button onclick="genProposal()" class="btn-yellow px-6 py-2 text-sm w-full"><i class="fas fa-robot mr-1"></i>生成企劃書</button>
    </div>
    <div id="proposal-result" class="hidden mt-4 p-4 rounded-fju bg-fju-bg text-sm"></div>
  </div>

  {{-- AI Activity Recommendation --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-lightbulb mr-2 text-fju-yellow"></i>AI 活動推薦（實作）</h3>
    <button onclick="getRecommendations()" class="btn-yellow px-6 py-2 text-sm mb-3"><i class="fas fa-magic mr-1"></i>取得個人化推薦</button>
    <div id="recommend-result" class="space-y-2"></div>
  </div>

  {{-- AI Credit Warning --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-user-check mr-2 text-fju-yellow"></i>AI 信用預警系統（實作）</h3>
    <button onclick="checkCreditWarnings()" class="btn-yellow px-6 py-2 text-sm mb-3"><i class="fas fa-shield-alt mr-1"></i>掃描信用異常</button>
    <div id="credit-warning-result" class="space-y-2"></div>
  </div>

  {{-- AI Service Monitor --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-server mr-2 text-fju-yellow"></i>AI 服務監控面板（實作）</h3>
    <div id="ai-monitor" class="grid md:grid-cols-4 gap-3"></div>
  </div>
</div>

<script>
const aiFeatures={
  admin:[
    {icon:'fa-chart-bar',name:'審核統計看板',desc:'即時查看所有 AI 預審通過率、風險分佈、審核時間等統計數據。',color:'#003153',action:'showAdminDashboard()'},
    {icon:'fa-gavel',name:'RAG 法規比對',desc:'批量上傳企劃書，AI 自動比對 8 項校內法規並標記不合規條款。',color:'#DAA520',action:'window.location.href="/module/rag-search?role=admin"'},
    {icon:'fa-user-check',name:'信用預警系統',desc:'AI 監控全校社團信用積分，自動發送預警通知。',color:'#008000',action:'checkCreditWarnings()'},
    {icon:'fa-robot',name:'自動核准建議',desc:'對低風險案件（人數≤50、預算合理），AI 自動產出核准建議。',color:'#004070',action:'doPreReview()'}
  ],
  officer:[
    {icon:'fa-file-alt',name:'企劃書 AI 生成',desc:'輸入活動基本資訊，AI 自動生成含預算、流程、SDGs 的完整企劃書。',color:'#003153',action:'document.getElementById("pg-title").scrollIntoView({behavior:"smooth"})'},
    {icon:'fa-search',name:'AI 預審自檢',desc:'送出前先透過 AI 預審系統自我檢查，確保通過率。',color:'#DAA520',action:'doPreReview()'},
    {icon:'fa-handshake',name:'衝突 AI 協商',desc:'場地衝突時，透過輔寶 AI 助理取得建議並開啟多方協商。',color:'#008000',action:'document.querySelector(".chatbot-fab").click()'}
  ],
  professor:[
    {icon:'fa-clipboard-check',name:'審查輔助',desc:'AI 自動摘要學生企劃書，標示重點和潛在風險。',color:'#003153',action:'doPreReview()'},
    {icon:'fa-chart-line',name:'社團表現分析',desc:'AI 分析指導社團的活動成效、參與率、預算執行率等指標趨勢。',color:'#DAA520',action:'showProfessorAnalysis()'}
  ],
  student:[
    {icon:'fa-lightbulb',name:'活動推薦',desc:'根據過往參與紀錄和興趣標籤，AI 推薦最適合的活動。',color:'#003153',action:'getRecommendations()'},
    {icon:'fa-search',name:'法規查詢助手',desc:'用自然語言詢問校內活動相關法規，AI 搜尋法規庫回答。',color:'#DAA520',action:'window.location.href="/module/rag-search?role=student"'},
    {icon:'fa-file-alt',name:'企劃書 AI 生成',desc:'填入活動概要，AI 自動產出完整企劃書草稿。',color:'#008000',action:'document.getElementById("pg-title").scrollIntoView({behavior:"smooth"})'}
  ],
  it:[
    {icon:'fa-server',name:'AI 服務監控',desc:'監控 AI API 回應時間、成功率、Token 使用量。',color:'#003153',action:'loadAIMonitor()'},
    {icon:'fa-shield-alt',name:'WAF + 安全分析',desc:'AI 分析 API 呼叫模式，偵測異常行為。',color:'#C0392B',action:'loadAIMonitor()'},
    {icon:'fa-database',name:'資料品質監控',desc:'AI 定期掃描資料庫一致性，偵測異常資料。',color:'#DAA520',action:'loadAIMonitor()'}
  ]
};

function switchAIRole(r){
  const roles=r==='all'?['admin','officer','professor','student','it']:[r];
  const names={admin:'課指組審核員',officer:'社團幹部',professor:'指導教授',student:'一般學生',it:'資訊中心'};
  const icons={admin:'fa-user-tie',officer:'fa-user-shield',professor:'fa-chalkboard-teacher',student:'fa-user-graduate',it:'fa-server'};
  let html='';
  roles.forEach(role=>{
    const features=aiFeatures[role]||[];
    html+=`<div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 mb-4">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas ${icons[role]} mr-2 text-fju-yellow"></i>${names[role]} — 可用 AI 功能</h3>
      <div class="grid md:grid-cols-${Math.min(features.length,4)} gap-3">`;
    features.forEach(f=>{
      html+=`<div class="p-4 rounded-fju border border-gray-100 hover:shadow-md transition-all card-hover cursor-pointer" onclick="${f.action||''}">
        <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju flex items-center justify-center" style="background:${f.color}20"><i class="fas ${f.icon}" style="color:${f.color}"></i></div><span class="font-bold text-fju-blue text-xs">${f.name}</span></div>
        <p class="text-[11px] text-gray-500">${f.desc}</p>
        <div class="mt-2"><span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-green/10 text-fju-green"><i class="fas fa-check mr-0.5"></i>已實作</span></div>
      </div>`;
    });
    html+='</div></div>';
  });
  document.getElementById('ai-features-container').innerHTML=html;
}
switchAIRole(document.getElementById('ai-role-view').value);

// Demo toggles (embedded in cards)
function togglePrereviewDemo(){
  const area=document.getElementById('prereview-demo-area');
  if(!area.classList.contains('hidden')){area.classList.add('hidden');return}
  area.classList.remove('hidden');
  const content=document.getElementById('prereview-demo-content');
  content.innerHTML='<div class="text-center py-2"><i class="fas fa-spinner fa-spin text-fju-blue"></i> AI 模擬中...</div>';
  setTimeout(()=>animateSteps(content,[
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn"><i class="fas fa-file-upload text-fju-blue"></i><span class="text-sm">收到企劃書：「攝影社春季外拍」，預計 60 人</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-yellow/10 animate-fadeIn mt-2"><i class="fas fa-cog fa-spin text-fju-yellow"></i><span class="text-sm">Step 1: 規則引擎 — 人數 60 ≤ 80 ✓ | 預算 18,000 ✓</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn mt-2"><i class="fas fa-search text-fju-blue"></i><span class="text-sm">Step 2: RAG 法規比對 — 匹配「校園活動安全管理辦法」第 12 條</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-green/10 animate-fadeIn mt-2"><i class="fas fa-robot text-fju-green"></i><span class="text-sm">Step 3: GPT-4 深度分析 — 風險等級: low</span></div>',
    '<div class="mt-3 p-3 rounded-fju bg-fju-green/10 border border-fju-green/20 animate-fadeIn"><div class="flex items-center gap-2"><i class="fas fa-check-circle text-fju-green text-xl"></i><div><span class="font-bold text-fju-green">AI 預審通過</span><br><span class="text-xs text-gray-500">風險等級: 低 | 信心度: 92% | 審核時間: 1.3s</span></div></div></div>'
  ],700),800);
}

function toggleProposalDemo(){
  const area=document.getElementById('proposal-demo-area');
  if(!area.classList.contains('hidden')){area.classList.add('hidden');return}
  area.classList.remove('hidden');
  const content=document.getElementById('proposal-demo-content');
  content.innerHTML='<div class="text-center py-2"><i class="fas fa-spinner fa-spin text-fju-yellow"></i> AI 模擬中...</div>';
  setTimeout(()=>animateSteps(content,[
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn"><i class="fas fa-keyboard text-fju-blue"></i><span class="text-sm">輸入：「程式設計工作坊」40 人</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-yellow/10 animate-fadeIn mt-2"><i class="fas fa-cog fa-spin text-fju-yellow"></i><span class="text-sm">匹配歷史成功企劃書範本...</span></div>',
    '<div class="flex items-center gap-2 p-2 rounded bg-fju-blue/10 animate-fadeIn mt-2"><i class="fas fa-file-alt text-fju-blue"></i><span class="text-sm">生成預算: 場地 $3,000 + 講師 $5,000 + 材料 $4,000 = $12,000</span></div>',
    '<div class="mt-3 p-3 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20 animate-fadeIn"><b class="text-fju-blue">AI 企劃書摘要</b><br><span class="text-sm">一、宗旨：培養程式能力<br>二、地點：SF 134<br>三、預算：NT$12,000<br>四、SDGs：SDG4 (優質教育)<br>五、AI 風險評估：低風險 (89%)</span></div>'
  ],700),800);
}

function animateSteps(area,steps,delay){let html='';steps.forEach((s,i)=>{setTimeout(()=>{html+=s;area.innerHTML=html;area.scrollTop=area.scrollHeight},i*delay)})}

// Working AI functions
function genProposal(){
  fetch('/api/ai/generate-proposal',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({title:document.getElementById('pg-title').value,description:document.getElementById('pg-desc').value,participants:parseInt(document.getElementById('pg-ppl').value),date:document.getElementById('pg-date').value})}).then(r=>r.json()).then(res=>{
    const b=document.getElementById('proposal-result');b.classList.remove('hidden');
    b.innerHTML=`<h4 class="font-bold text-fju-blue mb-2">${res.title}</h4>
      <div class="space-y-1 text-xs text-gray-600">${Object.entries(res.sections).map(([k,v])=>Array.isArray(v)?v.map(l=>`<p>${l}</p>`).join(''):`<p>${v}</p>`).join('')}</div>
      ${res.sections.budget_breakdown?`<table class="w-full mt-2 text-xs"><thead><tr class="text-gray-400"><th class="text-left py-1">項目</th><th class="text-right py-1">金額</th></tr></thead><tbody>${res.sections.budget_breakdown.map(b=>`<tr class="border-t border-gray-100"><td class="py-1">${b.item}</td><td class="text-right">$${b.amount.toLocaleString()}</td></tr>`).join('')}</tbody></table>`:''}
      <div class="mt-3 p-2 rounded bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>AI 預審：${res.ai_review.risk_level} 風險 (信心度 ${(res.ai_review.confidence*100).toFixed(0)}%)</div>`;
  });
}

function doPreReview(){
  const ppl=parseInt(document.getElementById('pr-ppl').value)||60;
  const el=document.getElementById('prereview-result');
  el.innerHTML='<i class="fas fa-spinner fa-spin mr-1 text-fju-blue"></i>AI 預審中...';
  fetch('/api/ai/pre-review',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({participants:ppl})}).then(r=>r.json()).then(res=>{
    const pass=res.allow_next_step;
    el.innerHTML=`<div class="p-3 rounded-fju ${pass?'bg-fju-green/10 border border-fju-green/20':'bg-fju-red/10 border border-fju-red/20'}">
      <div class="flex items-center gap-2 mb-2"><i class="fas ${pass?'fa-check-circle text-fju-green':'fa-times-circle text-fju-red'} text-xl"></i><span class="font-bold ${pass?'text-fju-green':'text-fju-red'}">${pass?'AI 預審通過':'AI 預審未通過'}</span></div>
      <div class="text-xs text-gray-600"><b>風險等級：</b>${res.risk_level} | <b>信心度：</b>${(res.confidence*100).toFixed(0)}%</div>
      <div class="text-xs text-gray-600 mt-1"><b>分析：</b>${res.reasoning}</div>
      ${res.violations.length?`<div class="text-xs text-fju-red mt-1"><b>違規項目：</b>${res.violations.join(', ')}</div>`:''}
      ${res.suggestions.length?`<div class="text-xs text-fju-blue mt-1"><b>建議：</b><ul class="list-disc list-inside">${res.suggestions.map(s=>`<li>${s}</li>`).join('')}</ul></div>`:''}
      <div class="text-[10px] text-gray-400 mt-2">比對法規：${res.reviewed_regulations.join(' · ')}</div>
    </div>`;
  });
}

function getRecommendations(){
  const el=document.getElementById('recommend-result');
  el.innerHTML='<div class="text-center py-2"><i class="fas fa-spinner fa-spin text-fju-blue"></i></div>';
  setTimeout(()=>{
    const recs=[
      {title:'攝影社春季外拍',match:95,reason:'您曾參加 3 次攝影相關活動，興趣匹配度極高',date:'2026/04/20',tags:['攝影','戶外','藝術']},
      {title:'程式設計工作坊',match:88,reason:'您修過「資料結構」課程，且曾報名黑客松',date:'2026/04/25',tags:['程式','學術','科技']},
      {title:'社團評鑑準備講座',match:82,reason:'您擔任社團幹部，評鑑在即建議參加',date:'2026/05/01',tags:['評鑑','行政','必修']},
    ];
    el.innerHTML=recs.map(r=>`<div class="p-3 rounded-fju bg-white border border-gray-100 flex items-center justify-between card-hover">
      <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-fju bg-fju-blue/10 flex items-center justify-center"><span class="text-sm font-bold text-fju-blue">${r.match}%</span></div>
      <div><div class="font-bold text-fju-blue text-sm">${r.title}</div><div class="text-[10px] text-gray-400">${r.reason}</div>
      <div class="flex gap-1 mt-1">${r.tags.map(t=>`<span class="px-1.5 py-0.5 rounded text-[9px] bg-fju-yellow/10 text-fju-yellow">#${t}</span>`).join('')}</div></div></div>
      <div class="text-right"><div class="text-xs text-gray-400">${r.date}</div><button class="btn-yellow px-3 py-1 text-xs mt-1">報名</button></div>
    </div>`).join('');
  },800);
}

function checkCreditWarnings(){
  const el=document.getElementById('credit-warning-result');
  el.innerHTML='<div class="text-center py-2"><i class="fas fa-spinner fa-spin text-fju-blue"></i></div>';
  setTimeout(()=>{
    const warnings=[
      {club:'街舞社',score:62,alert:'連續 3 月下降，接近停權門檻 (60)'},
      {club:'桌遊社',score:71,alert:'本月因逾期歸還器材扣 5 分'},
      {club:'登山社',score:55,alert:'已低於 60 分門檻！建議立即處理'},
    ];
    el.innerHTML=warnings.map(w=>`<div class="p-3 rounded-fju border ${w.score<60?'border-fju-red/30 bg-fju-red/5':'border-fju-yellow/30 bg-fju-yellow/5'} flex items-center justify-between">
      <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-fju ${w.score<60?'bg-fju-red/10':'bg-fju-yellow/10'} flex items-center justify-center"><span class="text-sm font-bold ${w.score<60?'text-fju-red':'text-fju-yellow'}">${w.score}</span></div>
      <div><div class="font-bold text-fju-blue text-sm">${w.club}</div><div class="text-[10px] ${w.score<60?'text-fju-red':'text-fju-yellow'}">${w.alert}</div></div></div>
      <button class="px-3 py-1 rounded-fju text-xs ${w.score<60?'bg-fju-red text-white':'bg-fju-yellow text-fju-blue'}">${w.score<60?'緊急處理':'發送預警'}</button>
    </div>`).join('');
  },600);
}

function showAdminDashboard(){window.location.href='/module/reports?role=admin'}
function showProfessorAnalysis(){window.location.href='/dashboard?role=professor'}

function loadAIMonitor(){
  const metrics=[
    {label:'API 回應時間',value:'92ms',icon:'fa-tachometer-alt',color:'fju-green',sub:'P99: 185ms'},
    {label:'預審成功率',value:'99.5%',icon:'fa-check-circle',color:'fju-green',sub:'今日 47/47'},
    {label:'Token 使用量',value:'12.3K',icon:'fa-coins',color:'fju-yellow',sub:'本月上限 100K'},
    {label:'WAF 攔截',value:'23',icon:'fa-shield-alt',color:'fju-red',sub:'今日攔截次數'},
  ];
  document.getElementById('ai-monitor').innerHTML=metrics.map(m=>`
    <div class="p-3 rounded-fju bg-white border border-gray-100 text-center card-hover">
      <i class="fas ${m.icon} text-${m.color} text-lg mb-1"></i>
      <div class="text-lg font-black text-fju-blue">${m.value}</div>
      <div class="text-[10px] text-gray-400">${m.label}</div>
      <div class="text-[9px] text-gray-300 mt-1">${m.sub}</div>
    </div>`).join('');
}
loadAIMonitor();
</script>
<style>
@keyframes fadeIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}
.animate-fadeIn{animation:fadeIn 0.5s ease-out}
</style>
@endsection
