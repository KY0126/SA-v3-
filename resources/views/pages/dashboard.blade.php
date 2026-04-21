@php
$activePage = 'dashboard';
$_roles = ['admin'=>'課指組/處室','officer'=>'社團幹部','professor'=>'指導教授','student'=>'一般學生','it'=>'資訊中心'];
$_title = 'Dashboard - ' . ($_roles[$role] ?? '一般學生');
@endphp
@extends('layouts.shell')
@section('title', $_title)
@section('content')
{{-- Carousel --}}
<div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
  <div class="relative" id="carousel-container" style="height: 260px;">
    <div class="carousel-slide absolute inset-0 transition-opacity duration-700 opacity-100" style="background: linear-gradient(135deg, rgba(0,49,83,0.85), rgba(0,49,83,0.6)), url('https://images.unsplash.com/photo-1523050854058-8df90110c476?w=1200&q=80') center/cover;"><div class="flex items-center h-full px-8"><div class="text-white"><span class="inline-block px-3 py-1 rounded-full bg-fju-yellow text-fju-blue text-xs font-bold mb-3">最新活動</span><h2 class="text-2xl font-black mb-2">攝影社春季外拍活動</h2><p class="text-white/60 text-sm mb-4">2026/04/20 · 校園各處 · 50人</p><button class="btn-yellow px-6 py-2 text-sm" onclick="window.location.href='/module/activity-wall?role={{ $role }}'">了解更多</button></div></div></div>
    <div class="carousel-slide absolute inset-0 transition-opacity duration-700 opacity-0" style="background: linear-gradient(135deg, rgba(0,49,83,0.85), rgba(218,165,32,0.2)), url('https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=1200&q=80') center/cover;"><div class="flex items-center h-full px-8"><div class="text-white"><span class="inline-block px-3 py-1 rounded-full bg-fju-yellow text-fju-blue text-xs font-bold mb-3">即將舉辦</span><h2 class="text-2xl font-black mb-2">吉他社期末成果展</h2><p class="text-white/60 text-sm mb-4">2026/04/15 · 中美堂 · 200人</p><button class="btn-yellow px-6 py-2 text-sm" onclick="window.location.href='/module/activity-wall?role={{ $role }}'">了解更多</button></div></div></div>
    <div class="carousel-slide absolute inset-0 transition-opacity duration-700 opacity-0" style="background: linear-gradient(135deg, rgba(0,49,83,0.85), rgba(0,128,0,0.2)), url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&q=80') center/cover;"><div class="flex items-center h-full px-8"><div class="text-white"><span class="inline-block px-3 py-1 rounded-full bg-fju-yellow text-fju-blue text-xs font-bold mb-3">報名中</span><h2 class="text-2xl font-black mb-2">程式設計工作坊</h2><p class="text-white/60 text-sm mb-4">2026/04/25 · SF 134 · 40人</p><button class="btn-yellow px-6 py-2 text-sm" onclick="window.location.href='/module/activity-wall?role={{ $role }}'">了解更多</button></div></div></div>
    <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 text-white hover:bg-white/30 transition-all flex items-center justify-center"><i class="fas fa-chevron-left text-sm"></i></button>
    <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 text-white hover:bg-white/30 transition-all flex items-center justify-center"><i class="fas fa-chevron-right text-sm"></i></button>
    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2"><span class="carousel-dot w-2 h-2 rounded-full bg-white cursor-pointer" onclick="goToSlide(0)"></span><span class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer" onclick="goToSlide(1)"></span><span class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer" onclick="goToSlide(2)"></span></div>
  </div>
</div>

{{-- Role-specific dashboard content --}}
<div id="role-dashboard"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入 {{ $_roles[$role] ?? '一般學生' }} 儀表板...</div></div>

<script>
let currentSlideIdx=0;const slides=document.querySelectorAll('.carousel-slide'),dots=document.querySelectorAll('.carousel-dot');
function goToSlide(i){slides[currentSlideIdx].style.opacity='0';dots[currentSlideIdx].classList.replace('bg-white','bg-white/40');currentSlideIdx=i;slides[currentSlideIdx].style.opacity='1';dots[currentSlideIdx].classList.replace('bg-white/40','bg-white')}
function nextSlide(){goToSlide((currentSlideIdx+1)%slides.length)}function prevSlide(){goToSlide((currentSlideIdx-1+slides.length)%slides.length)}setInterval(nextSlide,5000);

const ROLE='{{ $role }}';
fetch('/api/dashboard/stats/'+ROLE).then(r=>r.json()).then(d=>{
  const el=document.getElementById('role-dashboard');
  if(ROLE==='admin') renderAdminDashboard(el,d);
  else if(ROLE==='officer') renderOfficerDashboard(el,d);
  else if(ROLE==='professor') renderProfessorDashboard(el,d);
  else if(ROLE==='it') renderITDashboard(el,d);
  else renderStudentDashboard(el,d);
});

function renderAdminDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">待審核</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-clock text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue">${d.pending_reviews}</div><div class="text-[10px] text-gray-400 mt-1">較昨日 <span class="text-fju-red">+2</span></div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">本月活動</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-calendar text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-yellow">${d.monthly_activities}</div><div class="text-[10px] text-gray-400 mt-1">較上月 <span class="text-fju-green">+5</span></div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">場地使用率</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-building text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue">${d.venue_usage}%</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">AI 通過率</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-robot text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-green">${d.ai_pass_rate}%</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>社團參與趨勢 (折線)</h3><canvas id="c-trend" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>活動類型分布 (圓餅)</h3><canvas id="c-type" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-tachometer-alt mr-2 text-fju-yellow"></i>經費執行率 (Gauge)</h3><canvas id="c-gauge" height="200"></canvas></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地周轉 (堆疊長條)</h3><canvas id="c-venue" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-spider mr-2 text-fju-yellow"></i>SDGs 貢獻 (雷達)</h3><canvas id="c-sdg" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-filter mr-2 text-fju-yellow"></i>審核時效 (漏斗)</h3><canvas id="c-funnel" height="200"></canvas></div>
  </div>`;
  setTimeout(()=>{
    new Chart('c-trend',{type:'line',data:{labels:['9月','10月','11月','12月','1月','2月','3月'],datasets:[{label:'參與人數',data:d.trend_data,borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',fill:true,tension:0.4,pointBackgroundColor:'#DAA520'}]},options:{responsive:true,plugins:{legend:{display:false}}}});
    new Chart('c-type',{type:'doughnut',data:{labels:['學術','服務','康樂','體育','藝文','綜合'],datasets:[{data:[25,18,22,15,12,8],backgroundColor:['#003153','#DAA520','#008000','#004070','#FDB913','#666']}]},options:{responsive:true}});
    new Chart('c-gauge',{type:'doughnut',data:{labels:['已執行','剩餘'],datasets:[{data:[72,28],backgroundColor:['#008000','#e5e7eb'],borderWidth:0}]},options:{responsive:true,circumference:180,rotation:270,cutout:'75%',plugins:{legend:{display:false}}}});
    new Chart('c-venue',{type:'bar',data:{labels:['中美堂','活動中心','體育館','SF134','焯炤館'],datasets:[{label:'上午',data:[12,8,15,6,10],backgroundColor:'#003153'},{label:'下午',data:[18,14,12,8,6],backgroundColor:'#DAA520'},{label:'晚上',data:[8,10,5,4,3],backgroundColor:'#008000'}]},options:{responsive:true,scales:{x:{stacked:true},y:{stacked:true}},plugins:{legend:{position:'bottom',labels:{font:{size:10}}}}}});
    new Chart('c-sdg',{type:'radar',data:{labels:Object.keys(d.sdg_data),datasets:[{label:'SDGs',data:Object.values(d.sdg_data),borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',pointBackgroundColor:'#DAA520'}]},options:{responsive:true,scales:{r:{beginAtZero:true,max:100}},plugins:{legend:{display:false}}}});
    const fd=d.funnel_data;
    new Chart('c-funnel',{type:'bar',data:{labels:['已提交','AI審核','已核准','已完成'],datasets:[{data:[fd.submitted,fd.ai_reviewed,fd.approved,fd.completed],backgroundColor:['#003153','#DAA520','#008000','#004070']}]},options:{indexAxis:'y',responsive:true,plugins:{legend:{display:false}}}});
  },50);
}

function renderOfficerDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.pending_tasks}</div><div class="text-xs text-gray-400">待辦事項</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-yellow">${d.member_count}</div><div class="text-xs text-gray-400">社團人數</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-green">${d.budget_used}%</div><div class="text-xs text-gray-400">經費使用率</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.next_event_days}天</div><div class="text-xs text-gray-400">下次活動</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>幹部簽到 (長條)</h3><canvas id="c-attend" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>社員留存 (折線)</h3><canvas id="c-retention" height="200"></canvas></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>滿意度 (圓餅)</h3><canvas id="c-satis" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-tint mr-2 text-fju-yellow"></i>經費水滴圖</h3><canvas id="c-budget" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-clipboard-check mr-2 text-fju-yellow"></i>傳承檢查表</h3><div class="space-y-2 mt-3">${['幹部交接','檔案移轉','帳號移交','財務結算','成果報告'].map((t,i)=>'<div class="flex items-center gap-2"><span class="w-5 h-5 rounded '+(i<3?'bg-fju-green text-white':'bg-gray-200 text-gray-400')+' flex items-center justify-center text-xs"><i class="fas fa-'+(i<3?'check':'times')+'"></i></span><span class="text-xs '+(i<3?'text-fju-green':'text-gray-500')+'">'+t+'</span></div>').join('')}</div></div>
  </div>`;
  setTimeout(()=>{
    new Chart('c-attend',{type:'bar',data:{labels:['W1','W2','W3','W4','W5','W6','W7'],datasets:[{label:'出席',data:d.attendance_data,backgroundColor:'#003153'}]},options:{responsive:true,plugins:{legend:{display:false}}}});
    new Chart('c-retention',{type:'line',data:{labels:['期初','W2','W4','W6','W8','W10','W12'],datasets:[{label:'留存率%',data:d.retention_data,borderColor:'#DAA520',backgroundColor:'rgba(218,165,32,0.1)',fill:true,tension:0.3}]},options:{responsive:true,plugins:{legend:{display:false}}}});
    const sd=d.satisfaction_data;
    new Chart('c-satis',{type:'doughnut',data:{labels:['非常滿意','滿意','普通','不滿意'],datasets:[{data:[sd.very_satisfied,sd.satisfied,sd.neutral,sd.unsatisfied],backgroundColor:['#008000','#DAA520','#666','#FF0000']}]},options:{responsive:true}});
    new Chart('c-budget',{type:'doughnut',data:{labels:['已使用','剩餘'],datasets:[{data:[65,35],backgroundColor:['#003153','rgba(0,49,83,0.1)'],borderWidth:0}]},options:{responsive:true,cutout:'70%',plugins:{legend:{display:false}}}});
  },50);
}

function renderProfessorDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.supervised_clubs}</div><div class="text-xs text-gray-400">指導社團</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black ${d.risk_alerts>0?'text-fju-red':'text-fju-green'}">${d.risk_alerts}</div><div class="text-xs text-gray-400">風險警示</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-yellow">${d.review_pending}</div><div class="text-xs text-gray-400">待審核</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex gap-2 justify-center">${Object.entries(d.risk_indicators).map(([k,v])=>'<div class="text-center"><div class="w-8 h-8 rounded-full '+(k==='high'?'bg-fju-red':k==='medium'?'bg-fju-yellow':'bg-fju-green')+' flex items-center justify-center text-white text-xs font-bold">'+v+'</div><div class="text-[9px] text-gray-400 mt-1">'+k+'</div></div>').join('')}</div><div class="text-xs text-gray-400 text-center mt-1">紅黃綠燈</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-spider mr-2 text-fju-yellow"></i>績效考核 (雷達)</h3><canvas id="c-perf" height="250"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>職能成長 (Spider)</h3><canvas id="c-growth" height="250"></canvas></div>
  </div>`;
  setTimeout(()=>{
    const pd=d.performance_data;
    new Chart('c-perf',{type:'radar',data:{labels:Object.keys(pd),datasets:[{label:'績效',data:Object.values(pd),borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',pointBackgroundColor:'#DAA520'}]},options:{responsive:true,scales:{r:{beginAtZero:true,max:100}}}});
    new Chart('c-growth',{type:'line',data:{labels:['9月','10月','11月','12月','1月','2月'],datasets:[{label:'成長',data:d.growth_data,borderColor:'#008000',backgroundColor:'rgba(0,128,0,0.1)',fill:true,tension:0.4,pointBackgroundColor:'#DAA520'}]},options:{responsive:true,plugins:{legend:{display:false}}}});
  },50);
}

function renderStudentDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.activities_joined}</div><div class="text-xs text-gray-400">參加活動</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-yellow">${d.officer_roles}</div><div class="text-xs text-gray-400">幹部經歷</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black ${d.credit_score<60?'text-fju-red':'text-fju-green'}">${d.credit_score}</div><div class="text-xs text-gray-400">信用積分</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.competency_level}</div><div class="text-xs text-gray-400">職能等級</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-user mr-2 text-fju-yellow"></i>個人履歷 Dashboard</h3><canvas id="c-comp" height="250"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 推薦清單</h3>
      <div class="space-y-2">${['加入程式設計社 (匹配度92%)','參加攝影比賽 (匹配度88%)','報名職涯講座 (匹配度85%)','申請交換學生 (匹配度78%)'].map((r,i)=>'<div class="flex items-center gap-3 p-2 rounded-fju '+(i===0?'bg-fju-green/10 border border-fju-green/20':'bg-gray-50')+' card-hover"><span class="w-6 h-6 rounded-full '+(i===0?'bg-fju-green text-white':'bg-gray-200 text-gray-500')+' flex items-center justify-center text-xs font-bold">'+(i+1)+'</span><span class="text-xs text-gray-700 flex-1">'+r+'</span></div>').join('')}</div>
    </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-tasks mr-2 text-fju-yellow"></i>職能 Steppers</h3>
      <div class="space-y-3">${Object.entries(d.competency_data).map(([k,v])=>'<div><div class="flex items-center justify-between mb-1"><span class="text-xs text-gray-600">'+k+'</span><span class="text-xs text-fju-blue font-bold">'+v+'%</span></div><div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-fju-blue rounded-full h-2" style="width:'+v+'%"></div></div></div>').join('')}</div>
    </div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-star mr-2 text-fju-yellow"></i>社團評價牆</h3>
      <div class="space-y-2">${[['攝影社','⭐⭐⭐⭐⭐','超讚的社團！學到很多攝影技巧'],['吉他社','⭐⭐⭐⭐','老師很有耐心，推薦！'],['程式設計社','⭐⭐⭐⭐⭐','很棒的學習環境']].map(r=>'<div class="p-3 rounded-fju bg-gray-50 border border-gray-100"><div class="flex items-center justify-between mb-1"><span class="font-bold text-fju-blue text-xs">'+r[0]+'</span><span class="text-[10px]">'+r[1]+'</span></div><p class="text-[10px] text-gray-500">'+r[2]+'</p></div>').join('')}</div>
    </div>
  </div>`;
  setTimeout(()=>{
    new Chart('c-comp',{type:'radar',data:{labels:Object.keys(d.competency_data),datasets:[{label:'職能',data:Object.values(d.competency_data),borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',pointBackgroundColor:'#DAA520'}]},options:{responsive:true,scales:{r:{beginAtZero:true,max:100}},plugins:{legend:{display:false}}}});
  },50);
}

function renderITDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.cpu_usage}%</div><div class="text-xs text-gray-400">CPU 使用率</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-yellow">${d.memory_usage}%</div><div class="text-xs text-gray-400">記憶體</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-green">${d.api_success_rate}%</div><div class="text-xs text-gray-400">API 成功率</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-red">${d.waf_blocks_today}</div><div class="text-xs text-gray-400">WAF 攔截</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-fire mr-2 text-fju-yellow"></i>負載熱力圖</h3><canvas id="c-load" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-tachometer-alt mr-2 text-fju-yellow"></i>API 延遲 (ms)</h3><canvas id="c-latency" height="200"></canvas></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-shield-alt mr-2 text-fju-yellow"></i>WAF 日誌</h3><div class="space-y-2">${['SQL Injection 攔截 (5次)','XSS 攻擊攔截 (8次)','DDoS 防護觸發 (2次)','非法 IP 封鎖 (8次)'].map((w,i)=>'<div class="flex items-center gap-2 p-2 rounded-fju bg-fju-red/5 border border-fju-red/10 text-xs"><i class="fas fa-exclamation-triangle text-fju-red"></i><span class="text-gray-600">'+w+'</span></div>').join('')}</div></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-hdd mr-2 text-fju-yellow"></i>R2 空間利用率 (圓餅)</h3><canvas id="c-r2" height="200"></canvas></div>
  </div>`;
  setTimeout(()=>{
    new Chart('c-load',{type:'bar',data:{labels:Array.from({length:12},(_,i)=>i*2+'h'),datasets:[{label:'CPU%',data:d.load_data,backgroundColor:d.load_data.map(v=>v>60?'#FF0000':v>40?'#DAA520':'#008000')}]},options:{responsive:true,plugins:{legend:{display:false}}}});
    new Chart('c-latency',{type:'line',data:{labels:Array.from({length:12},(_,i)=>i*2+'h'),datasets:[{label:'延遲(ms)',data:d.api_latency,borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',fill:true,tension:0.3,pointBackgroundColor:'#DAA520'}]},options:{responsive:true,plugins:{legend:{display:false}}}});
    const r2=d.r2_usage;
    new Chart('c-r2',{type:'doughnut',data:{labels:Object.keys(r2),datasets:[{data:Object.values(r2),backgroundColor:['#003153','#DAA520','#008000','#666']}]},options:{responsive:true}});
  },50);
}
</script>
@endsection
