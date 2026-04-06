import { appShell, roleNames } from './layout'

/* ===================================================================
   FJU Smart Hub - Dashboard
   Main content area: carousel + function cards + interactive map
   Uses Leaflet.js with L.imageOverlay and LayerGroups
   =================================================================== */

function mainDashboardContent(role: string): string {
  return `
    <!-- ===== RECENT ACTIVITIES CAROUSEL ===== -->
    <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
      <div class="relative" id="carousel-container" style="height: 260px;">
        <!-- Slides -->
        <div class="carousel-slide absolute inset-0 transition-opacity duration-700 opacity-100" style="background: linear-gradient(135deg, rgba(0,43,91,0.85), rgba(0,43,91,0.6)), url('https://images.unsplash.com/photo-1523050854058-8df90110c476?w=1200&q=80') center/cover;">
          <div class="flex items-center h-full px-8">
            <div class="text-white">
              <span class="inline-block px-3 py-1 rounded-full bg-fju-yellow text-fju-blue text-xs font-bold mb-3">最新活動</span>
              <h2 class="text-2xl font-black mb-2">攝影社春季外拍活動</h2>
              <p class="text-white/60 text-sm mb-4">2026/04/20 · 校園各處 · 50人</p>
              <button class="btn-yellow px-6 py-2 text-sm" onclick="window.location.href='/module/activity-wall?role=${role}'">了解更多</button>
            </div>
          </div>
        </div>
        <div class="carousel-slide absolute inset-0 transition-opacity duration-700 opacity-0" style="background: linear-gradient(135deg, rgba(0,43,91,0.85), rgba(255,184,0,0.2)), url('https://images.unsplash.com/photo-1514320291840-2e0a9bf2a9ae?w=1200&q=80') center/cover;">
          <div class="flex items-center h-full px-8">
            <div class="text-white">
              <span class="inline-block px-3 py-1 rounded-full bg-fju-yellow text-fju-blue text-xs font-bold mb-3">即將舉辦</span>
              <h2 class="text-2xl font-black mb-2">吉他社期末成果展</h2>
              <p class="text-white/60 text-sm mb-4">2026/04/15 · 中美堂 · 200人</p>
              <button class="btn-yellow px-6 py-2 text-sm" onclick="window.location.href='/module/activity-wall?role=${role}'">了解更多</button>
            </div>
          </div>
        </div>
        <div class="carousel-slide absolute inset-0 transition-opacity duration-700 opacity-0" style="background: linear-gradient(135deg, rgba(0,43,91,0.85), rgba(0,128,0,0.2)), url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=1200&q=80') center/cover;">
          <div class="flex items-center h-full px-8">
            <div class="text-white">
              <span class="inline-block px-3 py-1 rounded-full bg-fju-yellow text-fju-blue text-xs font-bold mb-3">報名中</span>
              <h2 class="text-2xl font-black mb-2">程式設計工作坊</h2>
              <p class="text-white/60 text-sm mb-4">2026/04/25 · SF 134 · 40人</p>
              <button class="btn-yellow px-6 py-2 text-sm" onclick="window.location.href='/module/activity-wall?role=${role}'">了解更多</button>
            </div>
          </div>
        </div>
        <!-- Carousel Controls -->
        <button onclick="prevSlide()" class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 text-white hover:bg-white/30 transition-all flex items-center justify-center"><i class="fas fa-chevron-left text-sm"></i></button>
        <button onclick="nextSlide()" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/20 text-white hover:bg-white/30 transition-all flex items-center justify-center"><i class="fas fa-chevron-right text-sm"></i></button>
        <!-- Dots -->
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
          <span class="carousel-dot w-2 h-2 rounded-full bg-white cursor-pointer" onclick="goToSlide(0)"></span>
          <span class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer" onclick="goToSlide(1)"></span>
          <span class="carousel-dot w-2 h-2 rounded-full bg-white/40 cursor-pointer" onclick="goToSlide(2)"></span>
        </div>
      </div>
    </div>

    <!-- ===== FUNCTION CARDS ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <!-- 場地預約 Card -->
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-fju bg-fju-blue flex items-center justify-center shrink-0">
            <i class="fas fa-map-marker-alt text-white text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-bold text-fju-blue text-lg mb-1">場地預約</h3>
            <p class="text-gray-500 text-sm mb-3">三階段智慧資源調度系統，支援志願序演算法、AI 自主協商與官方審核。</p>
            <div class="flex items-center gap-3 mb-3">
              <span class="text-xs px-2 py-1 rounded-full bg-fju-green/10 text-fju-green font-medium"><i class="fas fa-check-circle mr-1"></i>42 場地可用</span>
              <span class="text-xs px-2 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow font-medium"><i class="fas fa-clock mr-1"></i>8 待協商</span>
            </div>
            <a href="/module/venue-booking?role=${role}" class="inline-flex items-center gap-2 btn-yellow px-5 py-2 text-sm">
              了解更多 <i class="fas fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- 設備借用 Card -->
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-start gap-4">
          <div class="w-14 h-14 rounded-fju bg-fju-yellow flex items-center justify-center shrink-0">
            <i class="fas fa-boxes-stacked text-fju-blue text-xl"></i>
          </div>
          <div class="flex-1">
            <h3 class="font-bold text-fju-blue text-lg mb-1">設備借用</h3>
            <p class="text-gray-500 text-sm mb-3">器材盤點追蹤系統，支援 LINE/SMS 到期提醒與自動信用扣分機制。</p>
            <div class="flex items-center gap-3 mb-3">
              <span class="text-xs px-2 py-1 rounded-full bg-fju-green/10 text-fju-green font-medium"><i class="fas fa-check-circle mr-1"></i>15 可借用</span>
              <span class="text-xs px-2 py-1 rounded-full bg-fju-red/10 text-fju-red font-medium"><i class="fas fa-exclamation-triangle mr-1"></i>2 逾期</span>
            </div>
            <a href="/module/equipment?role=${role}" class="inline-flex items-center gap-2 btn-yellow px-5 py-2 text-sm">
              了解更多 <i class="fas fa-arrow-right text-xs"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== INTERACTIVE CAMPUS MAP ===== -->
    <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
      <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-fju-blue text-sm">
          <i class="fas fa-map-marked-alt mr-2 text-fju-yellow"></i>輔仁大學無障礙校園互動地圖
        </h3>
        <a href="/campus-map?role=${role}" class="text-[10px] px-3 py-1 rounded-full bg-fju-yellow text-fju-blue font-bold hover:bg-fju-yellow-light transition-all">
          <i class="fas fa-expand mr-1"></i>GeoJSON 分區地圖
        </a>
        <div class="flex gap-2">
          <button onclick="toggleLayer('teaching')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-blue text-white transition-all" data-layer="teaching">
            <i class="fas fa-building mr-1"></i>教學行政區
          </button>
          <button onclick="toggleLayer('accessible')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-yellow text-fju-blue transition-all" data-layer="accessible">
            <i class="fas fa-wheelchair mr-1"></i>無障礙
          </button>
          <button onclick="toggleLayer('life')" class="layer-btn active text-[11px] px-3 py-1 rounded-full bg-fju-green text-white transition-all" data-layer="life">
            <i class="fas fa-utensils mr-1"></i>生活
          </button>
          <button onclick="toggleLayer('transport')" class="layer-btn text-[11px] px-3 py-1 rounded-full bg-gray-200 text-gray-600 transition-all" data-layer="transport">
            <i class="fas fa-bus mr-1"></i>交通
          </button>
        </div>
      </div>
      <div id="campus-map" style="height: 480px; background: #e8ecf1;"></div>
    </div>

    <!-- ===== QUICK STATS ===== -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-gray-400">待審核</span>
          <div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-clock text-fju-blue text-xs"></i></div>
        </div>
        <div class="text-2xl font-black text-fju-blue">5</div>
        <div class="text-[10px] text-gray-400 mt-1">較昨日 <span class="text-fju-red">+2</span></div>
      </div>
      <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-gray-400">本月活動</span>
          <div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-calendar text-white text-xs"></i></div>
        </div>
        <div class="text-2xl font-black text-fju-yellow">28</div>
        <div class="text-[10px] text-gray-400 mt-1">較上月 <span class="text-fju-green">+5</span></div>
      </div>
      <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-gray-400">場地使用率</span>
          <div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-building text-fju-blue text-xs"></i></div>
        </div>
        <div class="text-2xl font-black text-fju-blue">87%</div>
        <div class="text-[10px] text-gray-400 mt-1">較上月 <span class="text-fju-green">+3%</span></div>
      </div>
      <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 card-hover">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs text-gray-400">AI 通過率</span>
          <div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-robot text-white text-xs"></i></div>
        </div>
        <div class="text-2xl font-black text-fju-green">94%</div>
        <div class="text-[10px] text-gray-400 mt-1">Dify RAG 預審</div>
      </div>
    </div>

    <!-- ===== CHARTS ROW ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>社團參與趨勢</h3>
        <canvas id="chart-trend" height="180"></canvas>
      </div>
      <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>活動類型分布</h3>
        <canvas id="chart-type" height="180"></canvas>
      </div>
    </div>

    <!-- ===== CAROUSEL SCRIPT ===== -->
    <script>
      let currentSlideIdx = 0;
      const slides = document.querySelectorAll('.carousel-slide');
      const dots = document.querySelectorAll('.carousel-dot');
      function goToSlide(idx) {
        slides[currentSlideIdx].style.opacity = '0';
        dots[currentSlideIdx].classList.replace('bg-white', 'bg-white/40');
        currentSlideIdx = idx;
        slides[currentSlideIdx].style.opacity = '1';
        dots[currentSlideIdx].classList.replace('bg-white/40', 'bg-white');
      }
      function nextSlide() { goToSlide((currentSlideIdx + 1) % slides.length); }
      function prevSlide() { goToSlide((currentSlideIdx - 1 + slides.length) % slides.length); }
      setInterval(nextSlide, 5000);
    </script>

    <!-- ===== CAMPUS MAP SCRIPT ===== -->
    <script>
    (function() {
      // Initialize map centered on FJU campus (淨心堂 area)
      const map = L.map('campus-map', {
        center: [25.0363, 121.4318],
        zoom: 17,
        zoomControl: false,
        attributionControl: false
      });

      L.control.zoom({ position: 'topright' }).addTo(map);

      // Base tile layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20,
        attribution: '&copy; OpenStreetMap'
      }).addTo(map);

      // ===== LAYER GROUP 1: 教學行政區 (All Buildings) =====
      const teachingLayer = L.layerGroup();
      const buildings = [
        { name: '淨心堂', pos: [25.0363, 121.4318], college: '校園中心', icon: '⛪', color: '#002B5B' },
        { name: 'LF 文友樓', pos: [25.0370, 121.4305], college: '傳播學院', icon: '🏢', color: '#002B5B' },
        { name: '羅耀拉大樓', pos: [25.0358, 121.4330], college: '管理學院', icon: '🏢', color: '#002B5B' },
        { name: '利瑪竇大樓', pos: [25.0372, 121.4325], college: '文學院', icon: '🏢', color: '#002B5B' },
        { name: '聖言樓', pos: [25.0355, 121.4310], college: '外語學院', icon: '🏢', color: '#002B5B' },
        { name: '伯達樓', pos: [25.0368, 121.4335], college: '理工學院', icon: '🏢', color: '#002B5B' },
        { name: '濟時樓', pos: [25.0350, 121.4325], college: '法律學院', icon: '🏢', color: '#002B5B' },
        { name: '國璽樓', pos: [25.0345, 121.4315], college: '民生學院', icon: '🏢', color: '#002B5B' },
        { name: '醫學大樓', pos: [25.0375, 121.4340], college: '醫學院', icon: '🏥', color: '#002B5B' },
        { name: '理工綜合教室', pos: [25.0360, 121.4340], college: '理工學院', icon: '🏫', color: '#002B5B' },
        { name: '中美堂', pos: [25.0356, 121.4300], college: '學務處', icon: '🏟️', color: '#FFB800', capacity: 500 },
        { name: '活動中心', pos: [25.0348, 121.4305], college: '學務處', icon: '🏢', color: '#FFB800', capacity: 200 },
        { name: '體育館', pos: [25.0340, 121.4320], college: '體育室', icon: '🏋️', color: '#FFB800', capacity: 800 },
        { name: 'SF 134 教室', pos: [25.0365, 121.4345], college: '理工學院', icon: '📚', color: '#002B5B', capacity: 80 },
        { name: '野聲樓', pos: [25.0378, 121.4310], college: '教育學院', icon: '🏢', color: '#002B5B' },
        { name: '舒德樓', pos: [25.0380, 121.4320], college: '社會科學院', icon: '🏢', color: '#002B5B' },
        { name: '焯炤館 (圖書館)', pos: [25.0352, 121.4335], college: '圖書資訊', icon: '📖', color: '#002B5B' },
      ];

      buildings.forEach(b => {
        const marker = L.circleMarker(b.pos, {
          radius: 8,
          fillColor: b.color,
          color: '#fff',
          weight: 2,
          opacity: 1,
          fillOpacity: 0.9
        });
        const accessInfo = '';
        const capacityInfo = b.capacity ? '<div class="popup-row"><i class="fas fa-users"></i><span>容量：' + b.capacity + ' 人</span></div>' : '';
        marker.bindPopup(
          '<div class="popup-header">' + b.icon + ' ' + b.name + '</div>' +
          '<div class="popup-body">' +
            '<div class="popup-row"><i class="fas fa-school"></i><span>' + b.college + '</span></div>' +
            capacityInfo +
            '<div class="popup-row"><i class="fas fa-circle" style="color:#008000;font-size:8px"></i><span>狀態：可使用</span></div>' +
            '<a href="/module/venue-booking?role=${role}" class="popup-btn"><i class="fas fa-calendar-plus mr-1"></i>我要預約</a>' +
          '</div>',
          { className: 'fju-popup', maxWidth: 280 }
        );
        marker.addTo(teachingLayer);
      });
      teachingLayer.addTo(map);

      // ===== LAYER GROUP 2: 無障礙設施 =====
      const accessibleLayer = L.layerGroup();
      const accessibleItems = [
        { name: '醫學大樓 - 無障礙廁所', pos: [25.0376, 121.4342], type: 'toilet', icon: '♿' },
        { name: '理工綜合教室 - 無障礙廁所', pos: [25.0361, 121.4342], type: 'toilet', icon: '♿' },
        { name: '體育館(中美堂) - 無障礙設施', pos: [25.0341, 121.4321], type: 'ramp', icon: '♿' },
        { name: '淨心堂 - 坡道', pos: [25.0362, 121.4316], type: 'ramp', icon: '🔄' },
        { name: '羅耀拉大樓 - 電梯', pos: [25.0357, 121.4328], type: 'elevator', icon: '🛗' },
        { name: '醫學大樓 - 電梯', pos: [25.0374, 121.4338], type: 'elevator', icon: '🛗' },
        { name: '圖書館 - 坡道+電梯', pos: [25.0351, 121.4333], type: 'ramp', icon: '🔄' },
        { name: '伯達樓 - 電梯', pos: [25.0367, 121.4333], type: 'elevator', icon: '🛗' },
        { name: '國璽樓 - 坡道', pos: [25.0344, 121.4313], type: 'ramp', icon: '🔄' },
      ];

      accessibleItems.forEach(a => {
        const iconColor = a.type === 'toilet' ? '#0066CC' : a.type === 'elevator' ? '#009933' : '#FF6600';
        const typeLabel = a.type === 'toilet' ? '無障礙廁所' : a.type === 'elevator' ? '無障礙電梯' : '無障礙坡道';
        const marker = L.circleMarker(a.pos, {
          radius: 6,
          fillColor: iconColor,
          color: '#fff',
          weight: 2,
          opacity: 1,
          fillOpacity: 0.9
        });
        marker.bindPopup(
          '<div class="popup-header" style="background:' + iconColor + '">' + a.icon + ' ' + typeLabel + '</div>' +
          '<div class="popup-body">' +
            '<div class="popup-row"><i class="fas fa-location-dot"></i><span>' + a.name + '</span></div>' +
            '<div class="popup-row"><i class="fas fa-wheelchair"></i><span>類型：' + typeLabel + '</span></div>' +
          '</div>',
          { className: 'fju-popup', maxWidth: 260 }
        );
        marker.addTo(accessibleLayer);
      });
      accessibleLayer.addTo(map);

      // ===== LAYER GROUP 3: 生活設施 =====
      const lifeLayer = L.layerGroup();
      const lifeItems = [
        { name: '輔園餐廳', pos: [25.0347, 121.4328], type: '學餐', icon: '🍽️' },
        { name: '理園餐廳', pos: [25.0362, 121.4348], type: '學餐', icon: '🍽️' },
        { name: '心園餐廳', pos: [25.0370, 121.4315], type: '學餐', icon: '🍽️' },
        { name: '宜聖宿舍', pos: [25.0380, 121.4345], type: '宿舍', icon: '🏠' },
        { name: '立言宿舍', pos: [25.0382, 121.4335], type: '宿舍', icon: '🏠' },
        { name: '格物宿舍', pos: [25.0385, 121.4325], type: '宿舍', icon: '🏠' },
      ];

      lifeItems.forEach(l => {
        const color = l.type === '學餐' ? '#FF6B35' : '#8B5CF6';
        const marker = L.circleMarker(l.pos, {
          radius: 7,
          fillColor: color,
          color: '#fff',
          weight: 2,
          opacity: 1,
          fillOpacity: 0.9
        });
        marker.bindPopup(
          '<div class="popup-header" style="background:' + color + '">' + l.icon + ' ' + l.name + '</div>' +
          '<div class="popup-body">' +
            '<div class="popup-row"><i class="fas fa-tag"></i><span>類型：' + l.type + '</span></div>' +
            '<div class="popup-row"><i class="fas fa-clock"></i><span>營業時間：07:00 - 20:00</span></div>' +
          '</div>',
          { className: 'fju-popup', maxWidth: 260 }
        );
        marker.addTo(lifeLayer);
      });
      lifeLayer.addTo(map);

      // ===== LAYER GROUP 4: 交通設施 =====
      const transportLayer = L.layerGroup();
      const transportItems = [
        { name: '輔大捷運站', pos: [25.0333, 121.4348], type: '捷運站', icon: '🚇' },
        { name: 'YouBike 正門站', pos: [25.0338, 121.4310], type: 'YouBike', icon: '🚲' },
        { name: 'YouBike 後門站', pos: [25.0385, 121.4315], type: 'YouBike', icon: '🚲' },
        { name: '汽車停車場', pos: [25.0340, 121.4340], type: '停車場', icon: '🅿️' },
        { name: '正門 (車用出入口)', pos: [25.0335, 121.4305], type: '出入口', icon: '🚗' },
        { name: '後門 (行人出入口)', pos: [25.0388, 121.4318], type: '出入口', icon: '🚶' },
        { name: '側門 (行人出入口)', pos: [25.0365, 121.4355], type: '出入口', icon: '🚶' },
      ];

      transportItems.forEach(t => {
        const color = t.type === '捷運站' ? '#0052D4' : t.type === 'YouBike' ? '#00B894' : t.type === '停車場' ? '#6C5CE7' : '#E17055';
        const marker = L.circleMarker(t.pos, {
          radius: 7,
          fillColor: color,
          color: '#fff',
          weight: 2,
          opacity: 1,
          fillOpacity: 0.9
        });
        marker.bindPopup(
          '<div class="popup-header" style="background:' + color + '">' + t.icon + ' ' + t.name + '</div>' +
          '<div class="popup-body">' +
            '<div class="popup-row"><i class="fas fa-tag"></i><span>類型：' + t.type + '</span></div>' +
          '</div>',
          { className: 'fju-popup', maxWidth: 260 }
        );
        marker.addTo(transportLayer);
      });
      // Transport layer NOT added by default

      // Store layers globally
      window._mapLayers = { teaching: teachingLayer, accessible: accessibleLayer, life: lifeLayer, transport: transportLayer };
      window._map = map;
    })();

    // Toggle layer visibility
    function toggleLayer(layerName) {
      const layer = window._mapLayers[layerName];
      const btn = document.querySelector('.layer-btn[data-layer="' + layerName + '"]');
      if (!layer || !btn) return;
      
      if (window._map.hasLayer(layer)) {
        window._map.removeLayer(layer);
        btn.classList.remove('active');
        btn.classList.add('bg-gray-200', 'text-gray-600');
        btn.classList.remove('bg-fju-blue', 'text-white', 'bg-fju-yellow', 'text-fju-blue', 'bg-fju-green');
      } else {
        window._map.addLayer(layer);
        btn.classList.add('active');
        btn.classList.remove('bg-gray-200', 'text-gray-600');
        // Restore original colors
        const colors = { teaching: ['bg-fju-blue', 'text-white'], accessible: ['bg-fju-yellow', 'text-fju-blue'], life: ['bg-fju-green', 'text-white'], transport: ['bg-fju-blue', 'text-white'] };
        (colors[layerName] || []).forEach(c => btn.classList.add(c));
      }
    }
    </script>

    <!-- ===== CHARTS SCRIPT ===== -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      if (document.getElementById('chart-trend')) {
        new Chart(document.getElementById('chart-trend'), {
          type: 'line',
          data: { labels: ['9月','10月','11月','12月','1月','2月','3月'],
            datasets: [{ label: '社團參與人數', data: [1200,1350,1500,1420,1380,1550,1680], borderColor: '#002B5B', backgroundColor: 'rgba(0,43,91,0.1)', fill: true, tension: 0.4, pointBackgroundColor: '#FFB800' }]
          }, options: { responsive: true, plugins: { legend: { display: false }}}
        });
      }
      if (document.getElementById('chart-type')) {
        new Chart(document.getElementById('chart-type'), {
          type: 'doughnut',
          data: { labels: ['學術','服務','康樂','體育','藝文','綜合'], datasets: [{ data: [25,18,22,15,12,8], backgroundColor: ['#002B5B','#FFB800','#008000','#003A75','#FFC933','#666'] }]},
          options: { responsive: true }
        });
      }
    });
    </script>
  `
}

export function dashboard(role: string): string {
  if (!roleNames[role]) role = 'student'
  return appShell(role, 'dashboard', `Dashboard - ${roleNames[role]}`, mainDashboardContent(role))
}
