@php
$activePage = 'dashboard';
$_roles = ['admin'=>'課指組/處室','officer'=>'社團幹部','professor'=>'指導教授','student'=>'一般學生'];
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

{{-- FULL CAMPUS MAP (integrated from campus-map page) --}}
<div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden mb-6 mt-6">
  <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
    <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-map-marked-alt mr-2 text-fju-yellow"></i>輔仁大學校園互動地圖</h3>
    <div class="flex gap-2 flex-wrap">
      <button onclick="toggleLayer('teaching')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-blue text-white" data-layer="teaching"><i class="fas fa-building mr-1"></i>教學行政</button>
      <button onclick="toggleLayer('accessible')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-yellow text-fju-blue" data-layer="accessible"><i class="fas fa-wheelchair mr-1"></i>無障礙</button>
      <button onclick="toggleLayer('life')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-green text-white" data-layer="life"><i class="fas fa-utensils mr-1"></i>生活</button>
      <button onclick="toggleLayer('transport')" class="layer-btn text-[11px] px-3 py-1 rounded-full bg-gray-200 text-gray-600" data-layer="transport"><i class="fas fa-bus mr-1"></i>交通</button>
    </div>
  </div>
  <div class="flex" style="min-height: 480px;">
    <div class="w-48 shrink-0 border-r border-gray-100 p-2 overflow-y-auto bg-white" style="max-height: 480px;">
      <div class="relative mb-2"><i class="fas fa-search absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-[9px]"></i><input id="map-search" type="text" placeholder="搜尋..." class="w-full pl-6 pr-2 py-1 rounded-fju border border-gray-200 text-[10px] focus:border-fju-blue outline-none" oninput="filterMapBuildings()"></div>
      <div class="flex flex-wrap gap-0.5 mb-2">
        <button onclick="filterMapType('all')" class="map-type-btn px-1.5 py-0.5 rounded text-[9px] bg-fju-blue text-white" data-type="all">全部</button>
        <button onclick="filterMapType('教學')" class="map-type-btn px-1.5 py-0.5 rounded text-[9px] bg-gray-100 text-gray-500" data-type="教學">教學</button>
        <button onclick="filterMapType('體育')" class="map-type-btn px-1.5 py-0.5 rounded text-[9px] bg-gray-100 text-gray-500" data-type="體育">體育</button>
        <button onclick="filterMapType('餐飲')" class="map-type-btn px-1.5 py-0.5 rounded text-[9px] bg-gray-100 text-gray-500" data-type="餐飲">餐飲</button>
      </div>
      <div id="map-building-list" class="space-y-0.5"></div>
    </div>
    <div class="flex-1"><div id="campus-map" style="height: 480px; background: #e8ecf1;"></div></div>
  </div>
</div>

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
  else if(ROLE==='staff') renderStaffDashboard(el,d);
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
  <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-blue">${d.activities_joined}</div><div class="text-xs text-gray-400">參加活動</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="text-2xl font-black text-fju-yellow">${d.officer_roles}</div><div class="text-xs text-gray-400">幹部經歷</div></div>
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

function renderStaffDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">待確認預約</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-calendar-check text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue">${d.pending_bookings}</div><div class="text-[10px] text-gray-400 mt-1">場地預約申請</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">場地使用率</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-building text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue">${d.venue_usage}%</div><div class="text-[10px] text-gray-400 mt-1">本月平均</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">設備借出中</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-box text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-yellow">${d.equipment_on_loan}</div><div class="text-[10px] text-gray-400 mt-1">件設備</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">近期活動</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-calendar-alt text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-green">${d.upcoming_events}</div><div class="text-[10px] text-gray-400 mt-1">近7天</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>本月場地預約量趨勢</h3><canvas id="c-booking-trend" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>各場地使用佔比</h3><canvas id="c-dept-usage" height="200"></canvas></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-calendar-check mr-2 text-fju-yellow"></i>快速操作</h3>
      <div class="space-y-2">
        <a href="/module/activity-application?role=staff" class="flex items-center gap-3 p-3 rounded-fju bg-fju-blue/5 hover:bg-fju-blue/10 transition-all cursor-pointer"><i class="fas fa-file-alt text-fju-blue w-5 text-center"></i><span class="text-sm font-medium text-fju-blue">提交活動申請</span><i class="fas fa-chevron-right text-gray-300 ml-auto text-xs"></i></a>
        <a href="/module/venue-booking?role=staff" class="flex items-center gap-3 p-3 rounded-fju bg-fju-yellow/5 hover:bg-fju-yellow/10 transition-all cursor-pointer"><i class="fas fa-map-marker-alt text-fju-yellow w-5 text-center"></i><span class="text-sm font-medium text-fju-blue">預約場地</span><i class="fas fa-chevron-right text-gray-300 ml-auto text-xs"></i></a>
        <a href="/module/equipment?role=staff" class="flex items-center gap-3 p-3 rounded-fju bg-fju-green/5 hover:bg-fju-green/10 transition-all cursor-pointer"><i class="fas fa-boxes-stacked text-fju-green w-5 text-center"></i><span class="text-sm font-medium text-fju-blue">借用設備</span><i class="fas fa-chevron-right text-gray-300 ml-auto text-xs"></i></a>
        <a href="/module/repair?role=staff" class="flex items-center gap-3 p-3 rounded-fju bg-gray-50 hover:bg-gray-100 transition-all cursor-pointer"><i class="fas fa-wrench text-gray-400 w-5 text-center"></i><span class="text-sm font-medium text-fju-blue">提交報修</span><i class="fas fa-chevron-right text-gray-300 ml-auto text-xs"></i></a>
      </div>
    </div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-bell mr-2 text-fju-yellow"></i>近期通知</h3>
      <div class="space-y-2 text-sm">
        <div class="p-3 rounded-fju bg-fju-yellow/5 border-l-4 border-fju-yellow"><div class="font-medium text-fju-blue text-xs">場地預約核准通知</div><div class="text-[10px] text-gray-400 mt-1">中美堂 2026/05/10 14:00-17:00 已核准</div></div>
        <div class="p-3 rounded-fju bg-fju-blue/5 border-l-4 border-fju-blue"><div class="font-medium text-fju-blue text-xs">設備歸還提醒</div><div class="text-[10px] text-gray-400 mt-1">音響設備借用期限將於明日到期</div></div>
        <div class="p-3 rounded-fju bg-fju-green/5 border-l-4 border-fju-green"><div class="font-medium text-fju-blue text-xs">活動申請通過</div><div class="text-[10px] text-gray-400 mt-1">5月份部門茶會活動申請已核備</div></div>
      </div>
    </div>
  </div>`;
  setTimeout(()=>{
    new Chart('c-booking-trend',{type:'bar',data:{labels:['5/1','5/2','5/3','5/4','5/5','5/6','5/7','5/8','5/9','5/10','5/11','5/12'],datasets:[{label:'預約數',data:d.booking_data,backgroundColor:'#003153',borderRadius:4}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
    new Chart('c-dept-usage',{type:'doughnut',data:{labels:Object.keys(d.dept_usage),datasets:[{data:Object.values(d.dept_usage),backgroundColor:['#003153','#DAA520','#008000','#004070','#999']}]},options:{responsive:true}});
  },50);
}

function renderITDashboard(el,d){
  el.innerHTML=`
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">CPU 使用率</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-microchip text-white text-xs"></i></div></div><div class="text-2xl font-black ${d.cpu_usage>80?'text-fju-red':d.cpu_usage>60?'text-fju-yellow':'text-fju-green'}">${d.cpu_usage}%</div><div class="w-full bg-gray-100 rounded-full h-1.5 mt-2"><div class="rounded-full h-1.5 ${d.cpu_usage>80?'bg-fju-red':d.cpu_usage>60?'bg-fju-yellow':'bg-fju-green'}" style="width:${d.cpu_usage}%"></div></div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">記憶體使用</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-memory text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue">${d.memory_usage}%</div><div class="w-full bg-gray-100 rounded-full h-1.5 mt-2"><div class="rounded-full h-1.5 bg-fju-yellow" style="width:${d.memory_usage}%"></div></div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">API 成功率</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-server text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-green">${d.api_success_rate}%</div><div class="text-[10px] text-gray-400 mt-1">近24小時</div></div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">WAF 攔截</span><div class="w-8 h-8 rounded-fju bg-fju-red/20 flex items-center justify-center"><i class="fas fa-shield-alt text-fju-red text-xs"></i></div></div><div class="text-2xl font-black text-fju-red">${d.waf_blocks_today}</div><div class="text-[10px] text-gray-400 mt-1">今日已攔截請求</div></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>系統負載 (過去12小時)</h3><canvas id="c-load" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-tachometer-alt mr-2 text-fju-yellow"></i>API 回應延遲 (ms)</h3><canvas id="c-latency" height="200"></canvas></div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-database mr-2 text-fju-yellow"></i>儲存空間使用 (R2)</h3><canvas id="c-r2" height="200"></canvas></div>
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-heartbeat mr-2 text-fju-yellow"></i>服務健康狀態</h3>
      <div class="space-y-2">${[['Laravel API','fa-server','ok'],['MySQL 資料庫','fa-database','ok'],['Sanctum Auth','fa-key','ok'],['WAF/DDoS 防護','fa-shield-alt','ok'],['AI RAG 服務','fa-robot','warning'],['R2 物件儲存','fa-cloud','ok']].map(([name,icon,status])=>`<div class="flex items-center justify-between p-2 rounded-fju bg-gray-50"><div class="flex items-center gap-2"><i class="fas ${icon} text-fju-blue text-sm w-5 text-center"></i><span class="text-xs font-medium text-gray-700">${name}</span></div><div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full ${status==='ok'?'bg-fju-green':'bg-fju-yellow'} animate-pulse"></span><span class="text-[10px] ${status==='ok'?'text-fju-green font-bold':'text-fju-yellow font-bold'}">${status==='ok'?'正常運行':'待確認'}</span></div></div>`).join('')}</div>
    </div>
  </div>`;
  setTimeout(()=>{
    const labels=['00:00','02:00','04:00','06:00','08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00'];
    new Chart('c-load',{type:'line',data:{labels,datasets:[{label:'負載%',data:d.load_data,borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',fill:true,tension:0.4,pointBackgroundColor:'#DAA520',pointRadius:3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,max:100}}}});
    new Chart('c-latency',{type:'line',data:{labels,datasets:[{label:'延遲 ms',data:d.api_latency,borderColor:'#DAA520',backgroundColor:'rgba(218,165,32,0.1)',fill:true,tension:0.4,pointBackgroundColor:'#003153',pointRadius:3}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
    new Chart('c-r2',{type:'doughnut',data:{labels:Object.keys(d.r2_usage),datasets:[{data:Object.values(d.r2_usage),backgroundColor:['#003153','#DAA520','#008000','#666']}]},options:{responsive:true}});
  },50);
}
</script>

{{-- Campus Map Script --}}
<script>
(function(){
const map=L.map('campus-map',{center:[25.036,121.432],zoom:17,zoomControl:true});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:20}).addTo(map);
const allBuildings=[
  {name:'淨心堂',pos:[25.0363,121.4318],college:'宗教',type:'教學',c:'#DAA520'},
  {name:'中美堂',pos:[25.0356,121.43],college:'學務處',type:'體育',c:'#00B894',cap:500},
  {name:'活動中心',pos:[25.0348,121.4305],college:'學務處',type:'行政',c:'#DAA520',cap:200},
  {name:'羅耀拉大樓',pos:[25.0358,121.433],college:'管理學院',type:'教學',c:'#8E44AD'},
  {name:'利瑪竇大樓',pos:[25.0372,121.4325],college:'文學院',type:'教學',c:'#E67E22'},
  {name:'聖言樓',pos:[25.0355,121.431],college:'外語學院',type:'教學',c:'#2980B9'},
  {name:'伯達樓',pos:[25.0368,121.4335],college:'理工學院',type:'教學',c:'#27AE60'},
  {name:'濟時樓',pos:[25.035,121.4325],college:'法律學院',type:'行政',c:'#C0392B'},
  {name:'國璽樓',pos:[25.0345,121.4315],college:'醫學院',type:'教學',c:'#E74C3C'},
  {name:'醫學大樓',pos:[25.0375,121.434],college:'醫學院',type:'教學',c:'#E74C3C'},
  {name:'體育館',pos:[25.034,121.432],college:'體育室',type:'體育',c:'#00B894',cap:800},
  {name:'SF 134',pos:[25.0365,121.4345],college:'理工學院',type:'教學',c:'#27AE60',cap:80},
  {name:'焯炤館',pos:[25.0352,121.4335],college:'圖書資訊',type:'行政',c:'#003153'},
  {name:'輔園餐廳',pos:[25.0347,121.4328],college:'餐飲',type:'餐飲',c:'#FF6B35'},
  {name:'理園餐廳',pos:[25.0362,121.4348],college:'餐飲',type:'餐飲',c:'#FF6B35'},
  {name:'宜聖宿舍',pos:[25.038,121.4345],college:'宿舍',type:'宿舍',c:'#7F8C8D'},
  {name:'立言宿舍',pos:[25.0382,121.4335],college:'宿舍',type:'宿舍',c:'#7F8C8D'}
];
const teachingLayer=L.layerGroup();window._mapBData=[];
allBuildings.forEach(b=>{const m=L.circleMarker(b.pos,{radius:9,fillColor:b.c,color:'#fff',weight:2,fillOpacity:0.85});const cap=b.cap?'<br>容量：'+b.cap+'人':'';m.bindPopup('<div class="popup-header" style="background:'+b.c+'">'+b.name+'</div><div class="popup-body"><div class="popup-row"><i class="fas fa-school"></i><span>'+b.college+cap+'</span></div><a href="/module/venue-booking?role={{ $role }}" class="popup-btn"><i class="fas fa-calendar-plus mr-1"></i>預約</a></div>',{className:'fju-popup'});m.addTo(teachingLayer);window._mapBData.push({...b,marker:m})});
teachingLayer.addTo(map);
const accessibleLayer=L.layerGroup();
[{name:'醫學大樓-無障礙廁所',pos:[25.0376,121.4342],c:'#0066CC'},{name:'淨心堂-坡道',pos:[25.0362,121.4316],c:'#FF6600'},{name:'羅耀拉-電梯',pos:[25.0357,121.4328],c:'#009933'},{name:'圖書館-坡道',pos:[25.0351,121.4333],c:'#FF6600'}].forEach(a=>{const m=L.circleMarker(a.pos,{radius:5,fillColor:a.c,color:'#fff',weight:2,fillOpacity:0.9});m.bindPopup(a.name,{className:'fju-popup'});m.addTo(accessibleLayer)});accessibleLayer.addTo(map);
const lifeLayer=L.layerGroup();
[{name:'輔園餐廳',pos:[25.0347,121.4328],c:'#FF6B35'},{name:'心園餐廳',pos:[25.037,121.4315],c:'#FF6B35'},{name:'宜聖宿舍',pos:[25.038,121.4345],c:'#8B5CF6'}].forEach(l=>{const m=L.circleMarker(l.pos,{radius:6,fillColor:l.c,color:'#fff',weight:2,fillOpacity:0.9});m.bindPopup(l.name);m.addTo(lifeLayer)});lifeLayer.addTo(map);
const transportLayer=L.layerGroup();
[{name:'輔大捷運站',pos:[25.0333,121.4348],c:'#0052D4'},{name:'YouBike',pos:[25.0338,121.431],c:'#00B894'}].forEach(t=>{const m=L.circleMarker(t.pos,{radius:6,fillColor:t.c,color:'#fff',weight:2,fillOpacity:0.9});m.bindPopup(t.name);m.addTo(transportLayer)});
window._mapLayers={teaching:teachingLayer,accessible:accessibleLayer,life:lifeLayer,transport:transportLayer};window._dashMap=map;
function renderMapList(data){document.getElementById('map-building-list').innerHTML=data.map(b=>'<div class="flex items-center gap-1.5 p-1 rounded hover:bg-fju-bg cursor-pointer text-[10px]" onclick="window._dashMap.flyTo(['+b.pos+'],19);window._mapBData.find(x=>x.name===\''+b.name.replace(/'/g,"\\'")+'\').marker.openPopup()"><div class="w-2 h-2 rounded-full shrink-0" style="background:'+b.c+'"></div><span class="text-fju-blue font-medium truncate">'+b.name+'</span></div>').join('')}
renderMapList(allBuildings);
window.filterMapBuildings=function(){const s=document.getElementById('map-search').value.toLowerCase();const f=window._mapBData.filter(b=>b.name.toLowerCase().includes(s)||b.college.toLowerCase().includes(s));renderMapList(f);window._mapBData.forEach(b=>{if(f.includes(b))map.addLayer(b.marker);else map.removeLayer(b.marker)})};
window.filterMapType=function(t){document.querySelectorAll('.map-type-btn').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});document.querySelector('.map-type-btn[data-type="'+t+'"]')?.classList.add('bg-fju-blue','text-white');document.querySelector('.map-type-btn[data-type="'+t+'"]')?.classList.remove('bg-gray-100','text-gray-500');const f=t==='all'?window._mapBData:window._mapBData.filter(b=>b.type===t);renderMapList(f);window._mapBData.forEach(b=>{if(f.includes(b))map.addLayer(b.marker);else map.removeLayer(b.marker)})};
})();
function toggleLayer(n){const l=window._mapLayers[n],b=document.querySelector('.layer-btn[data-layer="'+n+'"]');if(!l||!b)return;const m=window._dashMap;if(m.hasLayer(l)){m.removeLayer(l);b.classList.add('bg-gray-200','text-gray-600');b.classList.remove('bg-fju-blue','text-white','bg-fju-yellow','text-fju-blue','bg-fju-green','active')}else{m.addLayer(l);b.classList.remove('bg-gray-200','text-gray-600');b.classList.add('active');({teaching:['bg-fju-blue','text-white'],accessible:['bg-fju-yellow','text-fju-blue'],life:['bg-fju-green','text-white'],transport:['bg-fju-blue','text-white']}[n]||[]).forEach(x=>b.classList.add(x))}}
</script>
@endsection
