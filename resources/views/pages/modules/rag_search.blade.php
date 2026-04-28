@extends('layouts.shell')
@section('title', '法規查詢 (RAG)')
@php $activePage = 'rag-search'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>法規查詢 (RAG)</h2>
    <select id="rag-sort" onchange="sortRules()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
      <option value="id-asc">條文序號</option>
      <option value="cat">依分類</option>
      <option value="risk">依風險等級</option>
    </select>
  </div>

  {{-- RAG Flowchart --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-project-diagram mr-2 text-fju-yellow"></i>RAG 法規審查流程圖</h3>
    <div class="flex items-center justify-center gap-0 flex-wrap">
      <div class="flex flex-col items-center"><div class="w-28 py-3 rounded-fju bg-fju-blue text-white text-center text-xs font-bold">使用者提交<br>活動企劃</div><i class="fas fa-arrow-down text-fju-blue my-1"></i></div>
      <i class="fas fa-arrow-right text-fju-yellow mx-2 hidden md:block"></i>
      <div class="flex flex-col items-center"><div class="w-28 py-3 rounded-fju bg-fju-yellow/20 text-fju-blue text-center text-xs font-bold">規則引擎<br>基本驗證</div><i class="fas fa-arrow-down text-fju-yellow my-1"></i></div>
      <i class="fas fa-arrow-right text-fju-yellow mx-2 hidden md:block"></i>
      <div class="flex flex-col items-center"><div class="w-28 py-3 rounded-fju bg-fju-blue/10 text-fju-blue text-center text-xs font-bold">Embedding<br>向量搜索</div><i class="fas fa-arrow-down text-fju-blue my-1"></i></div>
      <i class="fas fa-arrow-right text-fju-yellow mx-2 hidden md:block"></i>
      <div class="flex flex-col items-center"><div class="w-28 py-3 rounded-fju bg-fju-yellow text-fju-blue text-center text-xs font-bold">RAG 法規<br>語義比對</div><i class="fas fa-arrow-down text-fju-yellow my-1"></i></div>
      <i class="fas fa-arrow-right text-fju-yellow mx-2 hidden md:block"></i>
      <div class="flex flex-col items-center"><div class="w-28 py-3 rounded-fju bg-fju-green text-white text-center text-xs font-bold">GPT-4<br>風險判斷</div><i class="fas fa-arrow-down text-fju-green my-1"></i></div>
      <i class="fas fa-arrow-right text-fju-yellow mx-2 hidden md:block"></i>
      <div class="flex flex-col items-center"><div class="w-28 py-3 rounded-fju bg-fju-blue text-white text-center text-xs font-bold">產出審查<br>結果報告</div></div>
    </div>
    <div class="mt-3 flex justify-center gap-4 text-[10px] text-gray-500">
      <span><span class="inline-block w-3 h-3 rounded bg-fju-green mr-1"></span>通過 → 進入 Gatekeeping</span>
      <span><span class="inline-block w-3 h-3 rounded bg-fju-yellow mr-1"></span>警告 → 附修改建議</span>
      <span><span class="inline-block w-3 h-3 rounded bg-fju-red mr-1"></span>不通過 → 退回申請人</span>
    </div>
  </div>

  {{-- Rules & Judgment Logic --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-book mr-2 text-fju-yellow"></i>法規規則與判斷邏輯</h3></div>
    <div id="rules-list" class="divide-y divide-gray-50">
      {{-- Hardcoded rules --}}
    </div>
  </div>

  {{-- Rules as Dropdown FAQ --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-question-circle mr-2 text-fju-yellow"></i>法規規則 FAQ（下拉式）</h3>
    <div id="rules-faq" class="space-y-2"></div>
  </div>

  {{-- Content Checker --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-spell-check mr-2 text-fju-yellow"></i>內容合規檢查器</h3>
    <p class="text-xs text-gray-500 mb-3">輸入活動企劃的內容片段，AI 將逐條比對法規，標記潛在違規項目並提供修改建議。</p>
    <div class="space-y-3">
      <textarea id="rag-content" rows="4" placeholder="請輸入活動企劃內容，例如：本社擬於 5 月 1 日在中美堂舉辦年度成果展，預計邀請 150 位校內外人士參加，活動時間為 18:00-22:00，需使用音響設備..." class="w-full px-4 py-3 rounded-fju border border-gray-200 text-sm"></textarea>
      <button onclick="checkContent()" class="btn-yellow px-6 py-2 text-sm"><i class="fas fa-search mr-1"></i>執行法規合規檢查</button>
    </div>
    <div id="content-check-result" class="hidden mt-4"></div>
  </div>

  {{-- AI Pre-review Simulation --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI 預審模擬</h3>
    <div class="grid md:grid-cols-2 gap-3">
      <div>
        <label class="text-xs text-gray-400">預計參加人數</label>
        <input type="number" id="ra-ppl" placeholder="預計參加人數" value="60" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
      <div>
        <label class="text-xs text-gray-400">活動類型</label>
        <select id="ra-type" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <option>學術講座</option><option>成果展覽</option><option>體育競賽</option><option>戶外活動</option><option>公益服務</option>
        </select>
      </div>
    </div>
    <button onclick="doReview()" class="btn-yellow px-6 py-2 text-sm mt-3"><i class="fas fa-search mr-1"></i>進行 RAG 法規預審</button>
    <div id="review-result" class="hidden mt-4 p-4 rounded-fju text-sm"></div>
  </div>
</div>

<script>
// Rules data
const rules=[
  {id:'R01',cat:'人數限制',rule:'室內活動超過 80 人需申請大型活動許可',risk:'high',logic:'participants > 80 → 觸發大型活動審查流程',action:'退回並要求補件「大型集會安全計畫書」'},
  {id:'R02',cat:'場地使用',rule:'校園場地使用不得超過 22:00',risk:'medium',logic:'end_time > 22:00 → 警告超時',action:'建議調整結束時間，或申請延長許可'},
  {id:'R03',cat:'預算',rule:'單次活動預算超過 50,000 元需經費稽核',risk:'high',logic:'budget > 50000 → 觸發稽核',action:'自動轉介經費審查委員會'},
  {id:'R04',cat:'安全',rule:'戶外活動需提交安全計畫與保險證明',risk:'high',logic:'activity_type === "outdoor" → 檢查安全計畫',action:'退回並要求上傳安全計畫及保險文件'},
  {id:'R05',cat:'場地使用',rule:'校級場地（中美堂、體育館）需提前 14 天申請',risk:'medium',logic:'(venue in premium_venues) && days_advance < 14 → 警告',action:'提示提前預約期限不足，建議更換場地'},
  {id:'R06',cat:'人員',rule:'指導教授需於活動前 7 天完成簽核',risk:'low',logic:'advisor_approval_date > activity_date - 7 → 提醒',action:'發送提醒通知給指導教授'},
  {id:'R07',cat:'飲食',rule:'活動涉及飲食需符合校園食品安全規範',risk:'medium',logic:'has_food === true → 檢查食安合格證明',action:'要求上傳食品安全相關證明'},
  {id:'R08',cat:'噪音',rule:'位於教學區的活動需控制音量在 70 分貝以下',risk:'low',logic:'venue_zone === "teaching" && has_speaker → 警告',action:'提醒使用低音量設備或更換場地'}
];

function renderRules(){
  const sort=document.getElementById('rag-sort').value;
  let data=[...rules];
  if(sort==='cat') data.sort((a,b)=>a.cat.localeCompare(b.cat));
  else if(sort==='risk'){const riskOrder={high:0,medium:1,low:2};data.sort((a,b)=>riskOrder[a.risk]-riskOrder[b.risk])}
  const riskColors={high:'bg-fju-red/10 text-fju-red',medium:'bg-fju-yellow/20 text-fju-yellow',low:'bg-fju-green/10 text-fju-green'};
  const riskLabels={high:'高風險',medium:'中風險',low:'低風險'};
  document.getElementById('rules-list').innerHTML=data.map(r=>
    '<div class="px-5 py-3 hover:bg-fju-bg/50 transition-all">'+
    '<div class="flex items-start justify-between gap-4">'+
    '<div class="flex-1"><div class="flex items-center gap-2 mb-1"><span class="text-xs font-mono bg-fju-blue/10 text-fju-blue px-2 py-0.5 rounded">'+r.id+'</span><span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">'+r.cat+'</span><span class="text-xs px-2 py-0.5 rounded-full '+riskColors[r.risk]+'">'+riskLabels[r.risk]+'</span></div>'+
    '<p class="text-sm font-medium text-fju-blue">'+r.rule+'</p>'+
    '<div class="mt-1 text-xs text-gray-500"><i class="fas fa-code mr-1 text-fju-yellow"></i><code class="bg-gray-100 px-1 rounded text-[11px]">'+r.logic+'</code></div>'+
    '<div class="mt-1 text-xs text-gray-500"><i class="fas fa-arrow-right mr-1 text-fju-green"></i>'+r.action+'</div>'+
    '</div></div></div>'
  ).join('');
  // Also render as dropdown FAQ
  document.getElementById('rules-faq').innerHTML=data.map((r,i)=>
    `<div class="rounded-fju border border-gray-100 overflow-hidden">
      <button onclick="document.getElementById('rfaq-${i}').classList.toggle('hidden');document.getElementById('rfaq-i-${i}').classList.toggle('rotate-180')" class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 transition-all">
        <div class="flex items-center gap-2"><span class="text-xs font-mono bg-fju-blue/10 text-fju-blue px-2 py-0.5 rounded">${r.id}</span><span class="text-xs px-2 py-0.5 rounded-full ${riskColors[r.risk]}">${riskLabels[r.risk]}</span><span class="text-sm font-medium text-fju-blue">${r.rule}</span></div>
        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" id="rfaq-i-${i}"></i>
      </button>
      <div id="rfaq-${i}" class="hidden px-4 pb-3">
        <div class="p-3 rounded-fju bg-fju-bg text-sm text-gray-600 space-y-1">
          <div><b>分類：</b>${r.cat}</div>
          <div><b>判斷邏輯：</b><code class="bg-gray-200 px-1 rounded text-xs">${r.logic}</code></div>
          <div><b>系統處置：</b>${r.action}</div>
        </div>
      </div>
    </div>`
  ).join('');
}
renderRules();
function sortRules(){renderRules()}

// Content checker
function checkContent(){
  const content=document.getElementById('rag-content').value;
  if(!content.trim()){alert('請輸入內容');return}
  const resultDiv=document.getElementById('content-check-result');
  resultDiv.classList.remove('hidden');
  resultDiv.innerHTML='<div class="text-center py-3"><i class="fas fa-spinner fa-spin text-fju-yellow text-xl"></i><p class="text-xs text-gray-400 mt-1">AI 正在比對法規...</p></div>';

  setTimeout(()=>{
    // Simulated analysis based on content
    let findings=[];
    if(content.includes('150')||content.includes('200')||content.includes('100')||parseInt(content.match(/\d{3}/)?.[0]||0)>80){
      findings.push({rule:'R01',severity:'high',msg:'偵測到大型活動（超過 80 人），需額外提交大型集會安全計畫書',fix:'建議將參加人數控制在 80 人以下，或補提「大型集會安全計畫書」'});
    }
    if(content.includes('22:')||content.includes('23:')||content.includes('22:00')){
      findings.push({rule:'R02',severity:'medium',msg:'活動時間超過校園場地使用上限（22:00）',fix:'建議將結束時間調整為 21:30 前'});
    }
    if(content.includes('音響')||content.includes('喇叭')){
      findings.push({rule:'R08',severity:'low',msg:'使用音響設備，需注意噪音控制',fix:'建議使用低音量設備，或選擇非教學區場地'});
    }
    if(content.includes('飲食')||content.includes('餐')||content.includes('食物')){
      findings.push({rule:'R07',severity:'medium',msg:'活動涉及飲食，需符合食品安全規範',fix:'請上傳食品安全合格證明'});
    }
    if(findings.length===0){
      findings.push({rule:'—',severity:'pass',msg:'內容初步比對未發現違規項目',fix:''});
    }
    const sevColors={high:'border-fju-red bg-fju-red/5',medium:'border-fju-yellow bg-fju-yellow/5',low:'border-fju-blue bg-fju-blue/5',pass:'border-fju-green bg-fju-green/5'};
    const sevIcons={high:'fa-times-circle text-fju-red',medium:'fa-exclamation-triangle text-fju-yellow',low:'fa-info-circle text-fju-blue',pass:'fa-check-circle text-fju-green'};
    resultDiv.innerHTML='<h4 class="font-bold text-fju-blue text-sm mb-2"><i class="fas fa-clipboard-check mr-1 text-fju-yellow"></i>合規檢查結果</h4>'+findings.map(f=>
      '<div class="p-3 rounded-fju border '+sevColors[f.severity]+' mb-2"><div class="flex items-start gap-2"><i class="fas '+sevIcons[f.severity]+' mt-0.5"></i><div class="flex-1"><div class="flex items-center gap-2 mb-1"><span class="text-xs font-mono bg-white/80 px-1.5 py-0.5 rounded">'+f.rule+'</span></div><p class="text-sm font-medium text-gray-700">'+f.msg+'</p>'+(f.fix?'<p class="text-xs text-gray-500 mt-1"><i class="fas fa-lightbulb mr-1 text-fju-yellow"></i>'+f.fix+'</p>':'')+'</div></div></div>'
    ).join('');
  }, 1500);
}

// AI pre-review
function doReview(){
  fetch('/api/ai/pre-review',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({participants:parseInt(document.getElementById('ra-ppl').value)})}).then(r=>r.json()).then(res=>{
    const b=document.getElementById('review-result');b.classList.remove('hidden');
    b.className='mt-4 p-4 rounded-fju text-sm '+(res.allow_next_step?'bg-fju-green/10':'bg-fju-red/10');
    b.innerHTML='<div class="font-bold mb-2">'+(res.allow_next_step?'✅ 通過':'❌ 未通過')+' (風險：'+res.risk_level+')</div><p>'+res.reasoning+'</p>'+(res.violations.length?'<div class="mt-2"><b>違規項目：</b>'+res.violations.join(', ')+'</div>':'')+(res.suggestions.length?'<div class="mt-2"><b>建議：</b><ul class="list-disc pl-4">'+res.suggestions.map(s=>'<li>'+s+'</li>').join('')+'</ul></div>':'');
  });
}
</script>
@endsection
