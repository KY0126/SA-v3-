{{-- Dashboard carousel + cards + FULL campus map + charts --}}
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
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 card-hover"><div class="flex items-start gap-4"><div class="w-14 h-14 rounded-fju bg-fju-blue flex items-center justify-center shrink-0"><i class="fas fa-map-marker-alt text-white text-xl"></i></div><div class="flex-1"><h3 class="font-bold text-fju-blue text-lg mb-1">場地預約</h3><p class="text-gray-500 text-sm mb-3">三階段智慧資源調度系統，支援志願序演算法、AI 自主協商與官方審核。</p><div class="flex items-center gap-3 mb-3"><span class="text-xs px-2 py-1 rounded-full bg-fju-green/10 text-fju-green font-medium"><i class="fas fa-check-circle mr-1"></i>42 場地可用</span><span class="text-xs px-2 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow font-medium"><i class="fas fa-clock mr-1"></i>8 待協商</span></div><a href="/module/venue-booking?role={{ $role }}" class="inline-flex items-center gap-2 btn-yellow px-5 py-2 text-sm">了解更多 <i class="fas fa-arrow-right text-xs"></i></a></div></div></div>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 card-hover"><div class="flex items-start gap-4"><div class="w-14 h-14 rounded-fju bg-fju-yellow flex items-center justify-center shrink-0"><i class="fas fa-boxes-stacked text-fju-blue text-xl"></i></div><div class="flex-1"><h3 class="font-bold text-fju-blue text-lg mb-1">設備借用</h3><p class="text-gray-500 text-sm mb-3">器材盤點追蹤系統，支援 LINE/SMS 到期提醒與自動信用扣分機制。</p><div class="flex items-center gap-3 mb-3"><span class="text-xs px-2 py-1 rounded-full bg-fju-green/10 text-fju-green font-medium"><i class="fas fa-check-circle mr-1"></i>15 可借用</span><span class="text-xs px-2 py-1 rounded-full bg-fju-red/10 text-fju-red font-medium"><i class="fas fa-exclamation-triangle mr-1"></i>2 逾期</span></div><a href="/module/equipment?role={{ $role }}" class="inline-flex items-center gap-2 btn-yellow px-5 py-2 text-sm">了解更多 <i class="fas fa-arrow-right text-xs"></i></a></div></div></div>
</div>

{{-- FULL CAMPUS MAP (merged from campus-map page) --}}
<div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
  <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between flex-wrap gap-2">
    <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-map-marked-alt mr-2 text-fju-yellow"></i>輔仁大學校園互動地圖</h3>
    <div class="flex gap-2 flex-wrap">
      <button onclick="toggleLayer('teaching')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-blue text-white" data-layer="teaching"><i class="fas fa-building mr-1"></i>教學行政</button>
      <button onclick="toggleLayer('accessible')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-yellow text-fju-blue" data-layer="accessible"><i class="fas fa-wheelchair mr-1"></i>無障礙</button>
      <button onclick="toggleLayer('life')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-green text-white" data-layer="life"><i class="fas fa-utensils mr-1"></i>生活</button>
      <button onclick="toggleLayer('transport')" class="layer-btn text-[11px] px-3 py-1 rounded-full bg-gray-200 text-gray-600" data-layer="transport"><i class="fas fa-bus mr-1"></i>交通</button>
    </div>
  </div>
  <div class="flex" style="min-height: 520px;">
    {{-- Sidebar: Building list + search + type filter --}}
    <div class="w-56 shrink-0 border-r border-gray-100 p-3 overflow-y-auto bg-white" style="max-height: 520px;">
      <div class="relative mb-3"><i class="fas fa-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i><input id="map-search" type="text" placeholder="搜尋建築..." class="w-full pl-7 pr-2 py-1.5 rounded-fju border border-gray-200 text-xs focus:border-fju-blue outline-none" oninput="filterMapBuildings()"></div>
      <div class="flex flex-wrap gap-1 mb-3">
        <button onclick="filterMapType('all')" class="map-type-btn px-2 py-0.5 rounded-fju bg-fju-blue text-white text-[10px]" data-type="all">全部</button>
        <button onclick="filterMapType('教學')" class="map-type-btn px-2 py-0.5 rounded-fju bg-gray-100 text-gray-500 text-[10px]" data-type="教學">教學</button>
        <button onclick="filterMapType('行政')" class="map-type-btn px-2 py-0.5 rounded-fju bg-gray-100 text-gray-500 text-[10px]" data-type="行政">行政</button>
        <button onclick="filterMapType('體育')" class="map-type-btn px-2 py-0.5 rounded-fju bg-gray-100 text-gray-500 text-[10px]" data-type="體育">體育</button>
        <button onclick="filterMapType('宿舍')" class="map-type-btn px-2 py-0.5 rounded-fju bg-gray-100 text-gray-500 text-[10px]" data-type="宿舍">宿舍</button>
        <button onclick="filterMapType('餐飲')" class="map-type-btn px-2 py-0.5 rounded-fju bg-gray-100 text-gray-500 text-[10px]" data-type="餐飲">餐飲</button>
      </div>
      <div id="map-building-list" class="space-y-0.5"></div>
    </div>
    {{-- Map --}}
    <div class="flex-1"><div id="campus-map" style="height: 520px; background: #e8ecf1;"></div></div>
  </div>
</div>

{{-- Quick Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">待審核</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-clock text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue" id="stat-pending">5</div><div class="text-[10px] text-gray-400 mt-1">較昨日 <span class="text-fju-red">+2</span></div></div>
  <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">本月活動</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-calendar text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-yellow">28</div><div class="text-[10px] text-gray-400 mt-1">較上月 <span class="text-fju-green">+5</span></div></div>
  <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">場地使用率</span><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-building text-fju-blue text-xs"></i></div></div><div class="text-2xl font-black text-fju-blue">87%</div><div class="text-[10px] text-gray-400 mt-1">較上月 <span class="text-fju-green">+3%</span></div></div>
  <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover"><div class="flex items-center justify-between mb-2"><span class="text-xs text-gray-400">AI 通過率</span><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-robot text-white text-xs"></i></div></div><div class="text-2xl font-black text-fju-green">94%</div><div class="text-[10px] text-gray-400 mt-1">Dify RAG 預審</div></div>
</div>
{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>社團參與趨勢</h3><canvas id="chart-trend" height="180"></canvas></div>
  <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100"><h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>活動類型分布</h3><canvas id="chart-type" height="180"></canvas></div>
</div>

{{-- Carousel Script --}}
<script>
let currentSlideIdx=0;const slides=document.querySelectorAll('.carousel-slide'),dots=document.querySelectorAll('.carousel-dot');
function goToSlide(i){slides[currentSlideIdx].style.opacity='0';dots[currentSlideIdx].classList.replace('bg-white','bg-white/40');currentSlideIdx=i;slides[currentSlideIdx].style.opacity='1';dots[currentSlideIdx].classList.replace('bg-white/40','bg-white')}
function nextSlide(){goToSlide((currentSlideIdx+1)%slides.length)}function prevSlide(){goToSlide((currentSlideIdx-1+slides.length)%slides.length)}setInterval(nextSlide,5000);
</script>

{{-- FULL Campus Map Script (integrated from campus-map page) --}}
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
  {name:'百鍊廳',pos:[25.0345,121.4325],college:'體育室',type:'體育',c:'#00B894',cap:100},
  {name:'舒德樓',pos:[25.038,121.432],college:'社科院',type:'教學',c:'#16A085'},
  {name:'野聲樓',pos:[25.0378,121.431],college:'教育學院',type:'教學',c:'#F39C12'},
  {name:'輔園餐廳',pos:[25.0347,121.4328],college:'餐飲',type:'餐飲',c:'#FF6B35'},
  {name:'理園餐廳',pos:[25.0362,121.4348],college:'餐飲',type:'餐飲',c:'#FF6B35'},
  {name:'心園餐廳',pos:[25.037,121.4315],college:'餐飲',type:'餐飲',c:'#FF6B35'},
  {name:'宜聖宿舍',pos:[25.038,121.4345],college:'宿舍',type:'宿舍',c:'#7F8C8D'},
  {name:'立言宿舍',pos:[25.0382,121.4335],college:'宿舍',type:'宿舍',c:'#7F8C8D'}
];

// Teaching layer
const teachingLayer=L.layerGroup();
window._mapBuildingData=[];
allBuildings.forEach(b=>{
  const m=L.circleMarker(b.pos,{radius:10,fillColor:b.c,color:'#fff',weight:2,fillOpacity:0.85});
  const cap=b.cap?'<div class="popup-row"><i class="fas fa-users"></i><span>容量：'+b.cap+' 人</span></div>':'';
  m.bindPopup('<div class="popup-header" style="background:'+b.c+'">'+b.name+'</div><div class="popup-body"><div class="popup-row"><i class="fas fa-school"></i><span>'+b.college+'</span></div>'+cap+'<a href="/module/venue-booking?role={{ $role }}" class="popup-btn"><i class="fas fa-calendar-plus mr-1"></i>我要預約</a></div>',{className:'fju-popup',maxWidth:280});
  m.addTo(teachingLayer);
  window._mapBuildingData.push({...b,marker:m});
});
teachingLayer.addTo(map);

// Accessible layer
const accessibleLayer=L.layerGroup();
[{name:'醫學大樓 - 無障礙廁所',pos:[25.0376,121.4342],c:'#0066CC'},{name:'理工綜合 - 無障礙廁所',pos:[25.0361,121.4342],c:'#0066CC'},{name:'淨心堂 - 坡道',pos:[25.0362,121.4316],c:'#FF6600'},{name:'羅耀拉大樓 - 電梯',pos:[25.0357,121.4328],c:'#009933'},{name:'醫學大樓 - 電梯',pos:[25.0374,121.4338],c:'#009933'},{name:'圖書館 - 坡道+電梯',pos:[25.0351,121.4333],c:'#FF6600'},{name:'伯達樓 - 電梯',pos:[25.0367,121.4333],c:'#009933'},{name:'國璽樓 - 坡道',pos:[25.0344,121.4313],c:'#FF6600'}].forEach(a=>{
  const m=L.circleMarker(a.pos,{radius:6,fillColor:a.c,color:'#fff',weight:2,fillOpacity:0.9});
  m.bindPopup('<div class="popup-header" style="background:'+a.c+'">'+a.name+'</div>',{className:'fju-popup'});
  m.addTo(accessibleLayer);
});
accessibleLayer.addTo(map);

// Life layer
const lifeLayer=L.layerGroup();
[{name:'輔園餐廳',pos:[25.0347,121.4328],c:'#FF6B35'},{name:'理園餐廳',pos:[25.0362,121.4348],c:'#FF6B35'},{name:'心園餐廳',pos:[25.037,121.4315],c:'#FF6B35'},{name:'宜聖宿舍',pos:[25.038,121.4345],c:'#8B5CF6'},{name:'立言宿舍',pos:[25.0382,121.4335],c:'#8B5CF6'}].forEach(l=>{
  const m=L.circleMarker(l.pos,{radius:7,fillColor:l.c,color:'#fff',weight:2,fillOpacity:0.9});
  m.bindPopup('<div class="popup-header" style="background:'+l.c+'">'+l.name+'</div><div class="popup-body"><div class="popup-row"><i class="fas fa-clock"></i><span>營業時間：07:00 - 20:00</span></div></div>',{className:'fju-popup'});
  m.addTo(lifeLayer);
});
lifeLayer.addTo(map);

// Transport layer
const transportLayer=L.layerGroup();
[{name:'輔大捷運站',pos:[25.0333,121.4348],c:'#0052D4'},{name:'YouBike 正門站',pos:[25.0338,121.431],c:'#00B894'},{name:'汽車停車場',pos:[25.034,121.434],c:'#6C5CE7'}].forEach(t=>{
  const m=L.circleMarker(t.pos,{radius:7,fillColor:t.c,color:'#fff',weight:2,fillOpacity:0.9});
  m.bindPopup('<div class="popup-header" style="background:'+t.c+'">'+t.name+'</div>',{className:'fju-popup'});
  m.addTo(transportLayer);
});

window._mapLayers={teaching:teachingLayer,accessible:accessibleLayer,life:lifeLayer,transport:transportLayer};
window._dashMap=map;

// Render sidebar building list
function renderMapList(data){
  document.getElementById('map-building-list').innerHTML=data.map(b=>
    '<div class="flex items-center gap-2 p-1.5 rounded-fju hover:bg-fju-bg cursor-pointer transition-all" onclick="window._dashMap.flyTo(['+b.pos+'],19);window._mapBuildingData.find(x=>x.name===\''+b.name.replace(/'/g,"\\'")+'\').marker.openPopup()">'+
    '<div class="w-2.5 h-2.5 rounded-full shrink-0" style="background:'+b.c+'"></div>'+
    '<div><div class="text-[11px] font-bold text-fju-blue leading-tight">'+b.name+'</div><div class="text-[9px] text-gray-400">'+b.college+'</div></div></div>'
  ).join('');
}
renderMapList(allBuildings);

window.filterMapBuildings=function(){
  const s=document.getElementById('map-search').value.toLowerCase();
  const f=window._mapBuildingData.filter(b=>b.name.toLowerCase().includes(s)||b.college.toLowerCase().includes(s));
  renderMapList(f);
  window._mapBuildingData.forEach(b=>{if(f.includes(b))map.addLayer(b.marker);else map.removeLayer(b.marker)});
};
window.filterMapType=function(t){
  document.querySelectorAll('.map-type-btn').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector('.map-type-btn[data-type="'+t+'"]').classList.add('bg-fju-blue','text-white');
  document.querySelector('.map-type-btn[data-type="'+t+'"]').classList.remove('bg-gray-100','text-gray-500');
  const f=t==='all'?window._mapBuildingData:window._mapBuildingData.filter(b=>b.type===t);
  renderMapList(f);
  window._mapBuildingData.forEach(b=>{if(f.includes(b))map.addLayer(b.marker);else map.removeLayer(b.marker)});
};
})();

function toggleLayer(n){const l=window._mapLayers[n],b=document.querySelector('.layer-btn[data-layer="'+n+'"]');if(!l||!b)return;const map=window._dashMap;if(map.hasLayer(l)){map.removeLayer(l);b.classList.add('bg-gray-200','text-gray-600');b.classList.remove('bg-fju-blue','text-white','bg-fju-yellow','text-fju-blue','bg-fju-green','active')}else{map.addLayer(l);b.classList.remove('bg-gray-200','text-gray-600');b.classList.add('active');const c={teaching:['bg-fju-blue','text-white'],accessible:['bg-fju-yellow','text-fju-blue'],life:['bg-fju-green','text-white'],transport:['bg-fju-blue','text-white']};(c[n]||[]).forEach(x=>b.classList.add(x))}}
</script>

{{-- Chart Script --}}
<script>
document.addEventListener('DOMContentLoaded',()=>{
  if(document.getElementById('chart-trend')){new Chart(document.getElementById('chart-trend'),{type:'line',data:{labels:['9月','10月','11月','12月','1月','2月','3月'],datasets:[{label:'社團參與人數',data:[1200,1350,1500,1420,1380,1550,1680],borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',fill:true,tension:0.4,pointBackgroundColor:'#DAA520'}]},options:{responsive:true,plugins:{legend:{display:false}}}})}
  if(document.getElementById('chart-type')){new Chart(document.getElementById('chart-type'),{type:'doughnut',data:{labels:['學術','服務','康樂','體育','藝文','綜合'],datasets:[{data:[25,18,22,15,12,8],backgroundColor:['#003153','#DAA520','#008000','#004070','#FDB913','#666']}]},options:{responsive:true}})}
});
</script>
