import { appShell } from './layout'

/* ===================================================================
   FJU Smart Hub - Interactive Segmented Campus Map
   Uses GeoJSON polygons with L.CRS.Simple + L.imageOverlay
   Anchor-based affine coordinate transformation
   College-based color coding with hover effects
   Vue 3 script-setup style reactivity via vanilla JS
   =================================================================== */

// College color mapping - extracted from FJU campus design
const COLLEGE_COLORS: Record<string, { fill: string; name: string; icon: string }> = {
  '文學院':     { fill: '#E67E22', name: '文學院 (College of Liberal Arts)', icon: 'fas fa-book' },
  '外語學院':   { fill: '#2980B9', name: '外語學院 (College of Foreign Languages)', icon: 'fas fa-language' },
  '理工學院':   { fill: '#27AE60', name: '理工學院 (College of Science & Engineering)', icon: 'fas fa-flask' },
  '管理學院':   { fill: '#8E44AD', name: '管理學院 (College of Management)', icon: 'fas fa-briefcase' },
  '法律學院':   { fill: '#C0392B', name: '法律學院 (College of Law)', icon: 'fas fa-gavel' },
  '醫學院':     { fill: '#E74C3C', name: '醫學院 (College of Medicine)', icon: 'fas fa-stethoscope' },
  '教育學院':   { fill: '#F39C12', name: '教育學院 (College of Education)', icon: 'fas fa-graduation-cap' },
  '社會科學院': { fill: '#16A085', name: '社會科學院 (College of Social Sciences)', icon: 'fas fa-users' },
  '藝術學院':   { fill: '#D35400', name: '藝術學院 (College of Art)', icon: 'fas fa-palette' },
  '民生學院':   { fill: '#1ABC9C', name: '民生學院 (College of Human Ecology)', icon: 'fas fa-heart' },
  '傳播學院':   { fill: '#3498DB', name: '傳播學院 (College of Communication)', icon: 'fas fa-broadcast-tower' },
  '織品學院':   { fill: '#9B59B6', name: '織品學院 (College of Textiles)', icon: 'fas fa-tshirt' },
  '宿舍':       { fill: '#7F8C8D', name: '宿舍區 (Dormitories)', icon: 'fas fa-home' },
  '餐飲':       { fill: '#FF6B35', name: '餐飲區 (Food Courts)', icon: 'fas fa-utensils' },
  '行政':       { fill: '#002B5B', name: '行政區 (Administration)', icon: 'fas fa-building' },
  '宗教':       { fill: '#FFB800', name: '宗教建築 (Religious)', icon: 'fas fa-church' },
  '交通':       { fill: '#6C5CE7', name: '交通設施 (Transportation)', icon: 'fas fa-bus' },
  '體育':       { fill: '#00B894', name: '體育場館 (Sports)', icon: 'fas fa-running' },
  '其他':       { fill: '#95A5A6', name: '其他設施 (Others)', icon: 'fas fa-building' },
}

// Building to college mapping
const BUILDING_COLLEGE_MAP: Record<string, string> = {
  '羅耀拉大樓': '管理學院',
  '文友樓': '傳播學院',
  '聖心學苑': '民生學院',
  '耕莘樓': '理工學院',
  '聖言樓': '外語學院',
  '外語學院': '外語學院',
  '正門停車場': '交通',
  '焯炤館': '行政',
  '于斌樓 (野聲樓)': '行政',
  '中美堂': '體育',
  '濟時樓': '行政',
  '伯達樓': '文學院',
  '樹德樓': '文學院',
  '淨心堂': '宗教',
  '理工學院': '理工學院',
  '文華樓': '文學院',
  '積健樓': '體育',
  '藝術學院': '藝術學院',
  '舒德樓': '社會科學院',
  '輔園': '餐飲',
  '理工綜合教室': '理工學院',
  '文舍學苑': '宿舍',
  '文開樓': '文學院',
  '利瑪竇大樓(LM)': '文學院',
  '文研樓': '文學院',
  '文鐸樓': '宗教',
  '文德學苑': '宿舍',
  '法園': '法律學院',
  '仁園': '餐飲',
  '倬章樓': '教育學院',
  '宜美學苑': '宿舍',
  '冠五樓': '行政',
  '食品科研大樓': '民生學院',
  '輔幼中心': '教育學院',
  '宜真/宜善學苑': '宿舍',
  '秉雅樓': '民生學院',
  '朝橒樓': '織品學院',
  '格物學苑': '宿舍',
  '德芳外語大樓': '外語學院',
  '百鍊廳': '體育',
  '立言學苑': '宿舍',
  '和平/信義學苑': '宿舍',
  '仁愛學苑': '宿舍',
  '宜聖學苑': '宿舍',
  '進修部大樓(ES)': '行政',
  '國璽樓': '醫學院',
  '于公陵園': '宗教',
  '司令台、看台區': '體育',
  '7-Eleven': '其他',
  '理工實驗大樓': '理工學院',
  '輔大站通風口': '交通',
  '警衛室': '行政',
}

function getCollegeForBuilding(name: string): string {
  return BUILDING_COLLEGE_MAP[name] || '其他'
}

function getColorForBuilding(name: string): string {
  const college = getCollegeForBuilding(name)
  return COLLEGE_COLORS[college]?.fill || '#95A5A6'
}

export function campusMapPage(role: string): string {
  return appShell(role, 'campus-map', '互動校園分區地圖', campusMapContent(role))
}

function campusMapContent(role: string): string {
  // Build college legend data for the sidebar
  const collegeEntries = Object.entries(COLLEGE_COLORS)
    .map(([key, val]) => `
      <label class="college-filter-item flex items-center gap-2 px-3 py-2 rounded-fju cursor-pointer hover:bg-white/10 transition-all" data-college="${key}">
        <input type="checkbox" checked class="college-checkbox accent-[${val.fill}] w-3.5 h-3.5" data-college="${key}" />
        <span class="w-3 h-3 rounded-sm shrink-0" style="background:${val.fill}"></span>
        <span class="text-xs text-gray-300 truncate">${key}</span>
      </label>
    `).join('')

  return `
    <div class="flex gap-4 h-[calc(100vh-220px)]" id="map-app">
      <!-- ===== LEFT: FILTER SIDEBAR ===== -->
      <aside class="w-72 min-w-[280px] bg-fju-dark/95 rounded-fju-lg overflow-hidden flex flex-col shadow-lg border border-white/5" id="filter-panel">
        <!-- Search -->
        <div class="p-4 border-b border-white/10">
          <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            <input type="text" id="building-search" placeholder="搜尋大樓名稱 / 英文名..." 
              class="w-full pl-9 pr-3 py-2 rounded-fju bg-white/10 border border-white/10 text-white text-xs outline-none focus:border-fju-yellow/50 focus:bg-white/15 transition-all placeholder:text-gray-500" />
          </div>
        </div>

        <!-- Building Type Filters -->
        <div class="p-3 border-b border-white/10">
          <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2 px-1">建築類型篩選</div>
          <div class="flex flex-wrap gap-1.5">
            <button class="btype-btn active text-[10px] px-2.5 py-1 rounded-full bg-fju-blue text-white transition-all" data-btype="all">
              <i class="fas fa-globe mr-1"></i>全部
            </button>
            <button class="btype-btn text-[10px] px-2.5 py-1 rounded-full bg-white/10 text-gray-400 transition-all hover:bg-white/20" data-btype="university">
              <i class="fas fa-university mr-1"></i>教學
            </button>
            <button class="btype-btn text-[10px] px-2.5 py-1 rounded-full bg-white/10 text-gray-400 transition-all hover:bg-white/20" data-btype="dormitory">
              <i class="fas fa-home mr-1"></i>宿舍
            </button>
            <button class="btype-btn text-[10px] px-2.5 py-1 rounded-full bg-white/10 text-gray-400 transition-all hover:bg-white/20" data-btype="food">
              <i class="fas fa-utensils mr-1"></i>餐飲
            </button>
            <button class="btype-btn text-[10px] px-2.5 py-1 rounded-full bg-white/10 text-gray-400 transition-all hover:bg-white/20" data-btype="other">
              <i class="fas fa-ellipsis-h mr-1"></i>其他
            </button>
          </div>
        </div>

        <!-- College Color Legend + Filters -->
        <div class="flex-1 overflow-y-auto p-3">
          <div class="flex items-center justify-between mb-2 px-1">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">學院分色</span>
            <button id="toggle-all-colleges" class="text-[10px] text-fju-yellow hover:text-fju-yellow-light transition-colors">
              <i class="fas fa-toggle-on mr-1"></i>全選/取消
            </button>
          </div>
          <div class="space-y-0.5" id="college-filters">
            ${collegeEntries}
          </div>
        </div>

        <!-- Building List -->
        <div class="border-t border-white/10 max-h-48 overflow-y-auto" id="building-list-panel">
          <div class="p-2 px-3">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">
              搜尋結果 (<span id="building-count">0</span>)
            </div>
          </div>
          <div id="building-list" class="px-2 pb-2 space-y-0.5"></div>
        </div>
      </aside>

      <!-- ===== RIGHT: MAP CONTAINER ===== -->
      <div class="flex-1 flex flex-col">
        <!-- Map Toolbar -->
        <div class="bg-white rounded-t-fju-lg border border-gray-100 border-b-0 px-4 py-2 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <h3 class="font-bold text-fju-blue text-sm">
              <i class="fas fa-map-marked-alt mr-2 text-fju-yellow"></i>輔仁大學互動分區校園地圖
            </h3>
            <span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-green/10 text-fju-green font-medium">
              <i class="fas fa-database mr-1"></i>GeoJSON v2
            </span>
          </div>
          <div class="flex items-center gap-2">
            <!-- Layer toggle buttons -->
            <button onclick="toggleGeoLayer('polygons')" class="geo-layer-btn active text-[10px] px-3 py-1 rounded-full bg-fju-blue text-white transition-all" data-layer="polygons">
              <i class="fas fa-draw-polygon mr-1"></i>建築分區
            </button>
            <button onclick="toggleGeoLayer('markers')" class="geo-layer-btn active text-[10px] px-3 py-1 rounded-full bg-fju-yellow text-fju-blue transition-all" data-layer="markers">
              <i class="fas fa-map-pin mr-1"></i>標記點
            </button>
            <button onclick="toggleGeoLayer('labels')" class="geo-layer-btn active text-[10px] px-3 py-1 rounded-full bg-fju-green text-white transition-all" data-layer="labels">
              <i class="fas fa-font mr-1"></i>名稱
            </button>
            <span class="w-px h-5 bg-gray-200 mx-1"></span>
            <button onclick="resetMapView()" class="text-[10px] px-3 py-1 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">
              <i class="fas fa-expand mr-1"></i>重置視圖
            </button>
            <button onclick="flyToBuilding('淨心堂')" class="text-[10px] px-3 py-1 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">
              <i class="fas fa-crosshairs mr-1"></i>淨心堂
            </button>
          </div>
        </div>

        <!-- Map -->
        <div id="geo-campus-map" class="flex-1 rounded-b-fju-lg border border-gray-100 overflow-hidden" style="min-height: 500px; background: #e8ecf1;"></div>
      </div>
    </div>

    <!-- ===== EXTRA STYLES FOR MAP ===== -->
    <style>
      /* Polygon hover effects */
      .building-polygon {
        transition: all 0.3s ease;
      }
      .building-polygon:hover {
        filter: drop-shadow(0 10px 25px rgba(0,0,0,0.3));
      }
      
      /* Custom tooltip */
      .building-tooltip {
        background: rgba(0,43,91,0.95) !important;
        color: white !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 6px 12px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2) !important;
        font-family: 'Microsoft JhengHei', sans-serif !important;
      }
      .building-tooltip::before {
        border-top-color: rgba(0,43,91,0.95) !important;
      }
      .leaflet-tooltip-top:before {
        border-top-color: rgba(0,43,91,0.95) !important;
      }
      
      /* Building label markers */
      .building-label {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        font-family: 'Microsoft JhengHei', sans-serif !important;
        font-size: 10px !important;
        font-weight: 700;
        color: #002B5B;
        text-shadow: 
          -1px -1px 0 #fff,
          1px -1px 0 #fff,
          -1px  1px 0 #fff,
          1px  1px 0 #fff,
          0   -1px 0 #fff,
          0    1px 0 #fff,
          -1px  0  0 #fff,
          1px  0  0 #fff;
        white-space: nowrap;
        pointer-events: none !important;
        text-align: center;
      }
      
      /* Popup styles */
      .geo-popup .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 0;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(0,0,0,0.2);
      }
      .geo-popup .leaflet-popup-content {
        margin: 0;
        min-width: 260px;
        max-width: 320px;
      }
      .geo-popup .leaflet-popup-tip {
        background: #002B5B;
      }
      .geo-popup-header {
        padding: 12px 16px;
        color: white;
        display: flex;
        align-items: center;
        gap: 10px;
      }
      .geo-popup-body {
        padding: 14px 16px;
        font-size: 13px;
        color: #333;
      }
      .geo-popup-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
      }
      .geo-popup-row i {
        color: #FFB800;
        width: 16px;
        text-align: center;
        font-size: 11px;
      }
      .geo-popup-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
      }
      
      /* Filter panel animations */
      .college-filter-item.dimmed {
        opacity: 0.4;
      }
      .building-list-item {
        transition: all 0.2s;
      }
      .building-list-item:hover {
        background: rgba(255,184,0,0.15) !important;
      }
      .building-list-item.highlight {
        background: rgba(255,184,0,0.2) !important;
        border-left: 3px solid #FFB800;
      }
      
      /* Pulse anchor markers */
      .anchor-marker {
        background: #FF0000;
        border: 3px solid white;
        border-radius: 50%;
        width: 14px;
        height: 14px;
        box-shadow: 0 0 0 0 rgba(255,0,0,0.4);
        animation: anchorPulse 2s ease-in-out infinite;
      }
      @keyframes anchorPulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255,0,0,0.4); }
        50% { box-shadow: 0 0 0 10px rgba(255,0,0,0); }
      }
    </style>

    <!-- ===== MAP INITIALIZATION SCRIPT ===== -->
    <script>
    (function() {
      'use strict';

      /* ========================================
         ANCHOR POINTS FOR COORDINATE REFERENCE
         A = 中美堂: lat 25.038427, lng 121.431574
         B = 第二個圓環: lat 25.036411, lng 121.432654
         
         For L.CRS.Simple mode, we define pixel anchors:
         A_pixel = [400, 300]  (中美堂 on the image)
         B_pixel = [600, 750]  (第二個圓環 on the image)
         
         The transformCoords function calculates the affine
         transform to map lat/lng → pixel coords.
         ======================================== */

      // Anchor definitions
      const ANCHOR_A = { lat: 25.038427, lng: 121.431574, px: [400, 300], name: '中美堂' };
      const ANCHOR_B = { lat: 25.036411, lng: 121.432654, px: [600, 750], name: '第二個圓環' };

      /**
       * transformCoords(lat, lng) → [y, x] in pixel space
       * 
       * Calculates distance ratio and rotation angle between two anchor points
       * in geographic space vs pixel space, then applies affine transformation.
       * 
       * This implements a 2-point affine transform:
       * 1. Compute vector AB in geo-space and pixel-space
       * 2. Find scale factor = |AB_pixel| / |AB_geo|
       * 3. Find rotation angle = angle(AB_pixel) - angle(AB_geo)
       * 4. For any point P, compute AP_geo, apply scale+rotate, add to A_pixel
       */
      function transformCoords(lat, lng) {
        // Vectors in geographic space (using approximate meter-scale)
        const dLat_geo = ANCHOR_B.lat - ANCHOR_A.lat;
        const dLng_geo = ANCHOR_B.lng - ANCHOR_A.lng;
        
        // Vectors in pixel space
        const dX_px = ANCHOR_B.px[0] - ANCHOR_A.px[0]; // 600 - 400 = 200
        const dY_px = ANCHOR_B.px[1] - ANCHOR_A.px[1]; // 750 - 300 = 450
        
        // Distance in geo space
        const dist_geo = Math.sqrt(dLat_geo * dLat_geo + dLng_geo * dLng_geo);
        // Distance in pixel space
        const dist_px = Math.sqrt(dX_px * dX_px + dY_px * dY_px);
        
        // Scale factor: pixels per geo-unit
        const scale = dist_px / dist_geo;
        
        // Rotation angles
        const angle_geo = Math.atan2(dLng_geo, dLat_geo);  // angle of AB in geo
        const angle_px  = Math.atan2(dX_px, dY_px);        // angle of AB in pixel
        const rotation  = angle_px - angle_geo;
        
        // Transform point P relative to anchor A
        const pLat = lat - ANCHOR_A.lat;
        const pLng = lng - ANCHOR_A.lng;
        
        // Apply rotation + scale
        const cosR = Math.cos(rotation);
        const sinR = Math.sin(rotation);
        
        const x_px = ANCHOR_A.px[0] + scale * (pLat * sinR + pLng * cosR);
        const y_px = ANCHOR_A.px[1] + scale * (pLat * cosR - pLng * sinR);
        
        return [y_px, x_px]; // [lat, lng] format for Leaflet
      }

      /* ========================================
         COLLEGE COLOR DEFINITIONS
         ======================================== */
      const COLLEGE_COLORS = ${JSON.stringify(COLLEGE_COLORS)};

      const BUILDING_COLLEGE_MAP = ${JSON.stringify(BUILDING_COLLEGE_MAP)};

      function getCollege(name) {
        return BUILDING_COLLEGE_MAP[name] || '其他';
      }
      function getColor(name) {
        const college = getCollege(name);
        return (COLLEGE_COLORS[college] || COLLEGE_COLORS['其他']).fill;
      }

      /* ========================================
         INITIALIZE MAP (Geographic CRS with OSM)
         We use real lat/lng coordinates so the GeoJSON
         polygons render correctly on the map.
         ======================================== */
      
      // Campus center (淨心堂 area)
      const CENTER = [25.0360, 121.4320];
      
      const map = L.map('geo-campus-map', {
        center: CENTER,
        zoom: 17,
        minZoom: 15,
        maxZoom: 20,
        zoomControl: false,
        attributionControl: false
      });

      L.control.zoom({ position: 'topright' }).addTo(map);

      // Attribution
      L.control.attribution({ position: 'bottomright', prefix: false })
        .addAttribution('&copy; <a href="https://osm.org">OpenStreetMap</a> | FJU Smart Hub')
        .addTo(map);

      // Base tile layer
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 20
      }).addTo(map);

      /* ========================================
         LAYER GROUPS
         ======================================== */
      const polygonLayer = L.layerGroup().addTo(map);
      const markerLayer  = L.layerGroup().addTo(map);
      const labelLayer   = L.layerGroup().addTo(map);

      // Store references for filtering
      const buildingData = []; // { name, nameEn, ref, college, color, building, polygon, marker, label, feature }
      
      // Anchor markers
      const anchorA_icon = L.divIcon({ className: 'anchor-marker', iconSize: [14, 14], iconAnchor: [7, 7] });
      const anchorB_icon = L.divIcon({ className: 'anchor-marker', iconSize: [14, 14], iconAnchor: [7, 7] });
      
      L.marker([ANCHOR_A.lat, ANCHOR_A.lng], { icon: anchorA_icon })
        .bindTooltip('錨點A: ' + ANCHOR_A.name, { className: 'building-tooltip', direction: 'top', offset: [0, -10] })
        .addTo(markerLayer);
      
      L.marker([ANCHOR_B.lat, ANCHOR_B.lng], { icon: anchorB_icon })
        .bindTooltip('錨點B: ' + ANCHOR_B.name, { className: 'building-tooltip', direction: 'top', offset: [0, -10] })
        .addTo(markerLayer);

      /* ========================================
         LOAD & RENDER GEOJSON
         ======================================== */
      fetch('/static/campus.geojson')
        .then(r => r.json())
        .then(geojson => {
          geojson.features.forEach((feature, idx) => {
            const props = feature.properties;
            const name = props.name || '';
            const nameEn = props['name:en'] || '';
            const ref = props.ref || '';
            const buildingType = props.building || 'university';
            const levels = props['building:levels'] || '';
            
            // Skip unnamed minor structures
            if (!name && buildingType === 'roof') return;
            if (!name && buildingType === 'garage') return;
            if (!name && buildingType === 'garages') return;
            if (!name && buildingType === 'school') return;
            
            const college = getCollege(name);
            const color = getColor(name);
            const displayName = name || (nameEn || ('建築 #' + idx));
            
            // Create polygon with college color
            const polygon = L.geoJSON(feature, {
              style: {
                fillColor: color,
                fillOpacity: 0.35,
                color: '#FFFFFF',
                weight: 2,
                opacity: 0.9,
                className: 'building-polygon'
              },
              onEachFeature: function(feat, layer) {
                // Hover effects
                layer.on('mouseover', function(e) {
                  this.setStyle({
                    fillOpacity: 0.7,
                    weight: 3,
                    color: '#FFB800'
                  });
                  this.bringToFront();
                  // Update filter sidebar highlight
                  highlightBuildingInList(displayName);
                });
                layer.on('mouseout', function(e) {
                  this.setStyle({
                    fillOpacity: 0.35,
                    weight: 2,
                    color: '#FFFFFF'
                  });
                  unhighlightBuildingInList(displayName);
                });

                // Popup content
                const collegeInfo = COLLEGE_COLORS[college] || COLLEGE_COLORS['其他'];
                const popupHTML = \`
                  <div class="geo-popup-header" style="background:\${color}">
                    <i class="\${collegeInfo.icon} text-lg"></i>
                    <div>
                      <div class="font-bold text-sm">\${displayName}</div>
                      \${nameEn ? '<div class="text-white/60 text-[10px]">' + nameEn + '</div>' : ''}
                    </div>
                  </div>
                  <div class="geo-popup-body">
                    <div class="geo-popup-row">
                      <i class="fas fa-school"></i>
                      <span>\${college}</span>
                    </div>
                    \${ref ? '<div class="geo-popup-row"><i class="fas fa-tag"></i><span>代碼：' + ref + '</span></div>' : ''}
                    \${levels ? '<div class="geo-popup-row"><i class="fas fa-layer-group"></i><span>樓層：' + levels + ' 層</span></div>' : ''}
                    <div class="geo-popup-row">
                      <i class="fas fa-palette"></i>
                      <span class="geo-popup-badge" style="background:\${color}22;color:\${color}">\${college}</span>
                    </div>
                    <div class="geo-popup-row">
                      <i class="fas fa-circle" style="color:#008000;font-size:8px"></i>
                      <span>狀態：可使用</span>
                    </div>
                    <div style="margin-top:10px; display:flex; gap:6px;">
                      <a href="/module/venue-booking?role=${role}" class="popup-btn" style="flex:1;text-align:center;display:inline-block;background:#FFB800;color:#002B5B;padding:6px 12px;border-radius:12px;font-weight:700;font-size:11px;text-decoration:none;">
                        <i class="fas fa-calendar-plus mr-1"></i>我要預約
                      </a>
                      <button onclick="flyToBuilding('\${displayName}')" class="popup-btn" style="flex:1;text-align:center;background:#002B5B;color:white;padding:6px 12px;border-radius:12px;font-weight:700;font-size:11px;border:none;cursor:pointer;">
                        <i class="fas fa-crosshairs mr-1"></i>定位
                      </button>
                    </div>
                  </div>
                \`;
                layer.bindPopup(popupHTML, { className: 'geo-popup fju-popup', maxWidth: 320 });
              }
            });
            polygon.addTo(polygonLayer);

            // Compute centroid for marker + label
            const bounds = polygon.getBounds();
            const centroid = bounds.getCenter();

            // Circle marker at centroid
            const marker = L.circleMarker(centroid, {
              radius: 5,
              fillColor: color,
              color: '#fff',
              weight: 1.5,
              fillOpacity: 0.9
            });
            marker.bindTooltip(displayName, {
              className: 'building-tooltip',
              direction: 'top',
              offset: [0, -8]
            });
            marker.addTo(markerLayer);

            // Text label at centroid
            const label = L.marker(centroid, {
              icon: L.divIcon({
                className: 'building-label',
                html: '<div>' + (name ? (name.length > 5 ? name.substring(0,4) + '…' : name) : '') + '</div>',
                iconSize: [80, 16],
                iconAnchor: [40, 8]
              }),
              interactive: false
            });
            label.addTo(labelLayer);

            // Store data
            buildingData.push({
              name: displayName,
              nameEn: nameEn,
              ref: ref,
              college: college,
              color: color,
              buildingType: buildingType,
              polygon: polygon,
              marker: marker,
              label: label,
              centroid: centroid,
              feature: feature
            });
          });

          // Update building count
          document.getElementById('building-count').textContent = buildingData.length;
          
          // Populate building list
          renderBuildingList(buildingData);
          
          // Fit map to data bounds
          if (buildingData.length > 0) {
            const allBounds = L.latLngBounds(buildingData.map(b => b.centroid));
            map.fitBounds(allBounds.pad(0.05));
          }
        })
        .catch(err => {
          console.error('Failed to load GeoJSON:', err);
          document.getElementById('geo-campus-map').innerHTML = 
            '<div class="flex items-center justify-center h-full text-gray-400"><i class="fas fa-exclamation-triangle mr-2"></i>無法載入校園地圖資料</div>';
        });

      /* ========================================
         BUILDING LIST RENDERING
         ======================================== */
      function renderBuildingList(data) {
        const container = document.getElementById('building-list');
        container.innerHTML = data.map(b => \`
          <div class="building-list-item flex items-center gap-2 px-3 py-1.5 rounded cursor-pointer"
               data-name="\${b.name}" data-college="\${b.college}"
               onclick="flyToBuilding('\${b.name.replace(/'/g, "\\\\'")}')">
            <span class="w-2 h-2 rounded-sm shrink-0" style="background:\${b.color}"></span>
            <span class="text-xs text-gray-300 truncate flex-1">\${b.name}</span>
            \${b.ref ? '<span class="text-[9px] text-gray-500 font-mono">' + b.ref + '</span>' : ''}
          </div>
        \`).join('');
        document.getElementById('building-count').textContent = data.length;
      }

      function highlightBuildingInList(name) {
        document.querySelectorAll('.building-list-item').forEach(el => {
          if (el.dataset.name === name) el.classList.add('highlight');
        });
      }
      function unhighlightBuildingInList(name) {
        document.querySelectorAll('.building-list-item').forEach(el => {
          if (el.dataset.name === name) el.classList.remove('highlight');
        });
      }

      /* ========================================
         SEARCH FILTER
         ======================================== */
      const searchInput = document.getElementById('building-search');
      searchInput.addEventListener('input', applyFilters);

      /* ========================================
         BUILDING TYPE FILTERS
         ======================================== */
      document.querySelectorAll('.btype-btn').forEach(btn => {
        btn.addEventListener('click', function() {
          document.querySelectorAll('.btype-btn').forEach(b => {
            b.classList.remove('active', 'bg-fju-blue', 'text-white');
            b.classList.add('bg-white/10', 'text-gray-400');
          });
          this.classList.add('active', 'bg-fju-blue', 'text-white');
          this.classList.remove('bg-white/10', 'text-gray-400');
          applyFilters();
        });
      });

      /* ========================================
         COLLEGE CHECKBOX FILTERS
         ======================================== */
      document.querySelectorAll('.college-checkbox').forEach(cb => {
        cb.addEventListener('change', applyFilters);
      });
      
      document.getElementById('toggle-all-colleges').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.college-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        checkboxes.forEach(cb => { cb.checked = !allChecked; });
        applyFilters();
      });

      /* ========================================
         APPLY ALL FILTERS
         ======================================== */
      function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const activeBtype = document.querySelector('.btype-btn.active')?.dataset.btype || 'all';
        
        // Get checked colleges
        const checkedColleges = new Set();
        document.querySelectorAll('.college-checkbox:checked').forEach(cb => {
          checkedColleges.add(cb.dataset.college);
        });

        const visible = [];
        
        buildingData.forEach(b => {
          let show = true;

          // Search filter
          if (searchTerm) {
            const matchName = b.name.toLowerCase().includes(searchTerm);
            const matchEn = b.nameEn.toLowerCase().includes(searchTerm);
            const matchRef = b.ref.toLowerCase().includes(searchTerm);
            const matchCollege = b.college.toLowerCase().includes(searchTerm);
            if (!matchName && !matchEn && !matchRef && !matchCollege) show = false;
          }

          // Building type filter
          if (activeBtype !== 'all') {
            if (activeBtype === 'university') {
              if (!['university', 'chapel', 'kindergarten'].includes(b.buildingType)) show = false;
            } else if (activeBtype === 'dormitory') {
              if (b.buildingType !== 'dormitory') show = false;
            } else if (activeBtype === 'food') {
              if (b.college !== '餐飲') show = false;
            } else {
              if (['university', 'chapel', 'kindergarten', 'dormitory'].includes(b.buildingType) && b.college !== '餐飲') show = false;
            }
          }

          // College filter
          if (!checkedColleges.has(b.college)) show = false;

          // Toggle visibility
          if (show) {
            if (!polygonLayer.hasLayer(b.polygon)) polygonLayer.addLayer(b.polygon);
            if (!markerLayer.hasLayer(b.marker)) markerLayer.addLayer(b.marker);
            if (!labelLayer.hasLayer(b.label)) labelLayer.addLayer(b.label);
            visible.push(b);
          } else {
            polygonLayer.removeLayer(b.polygon);
            markerLayer.removeLayer(b.marker);
            labelLayer.removeLayer(b.label);
          }
        });

        renderBuildingList(visible);
      }

      /* ========================================
         LAYER TOGGLE
         ======================================== */
      window.toggleGeoLayer = function(layerName) {
        const layers = { polygons: polygonLayer, markers: markerLayer, labels: labelLayer };
        const layer = layers[layerName];
        const btn = document.querySelector('.geo-layer-btn[data-layer="' + layerName + '"]');
        if (!layer || !btn) return;

        if (map.hasLayer(layer)) {
          map.removeLayer(layer);
          btn.classList.remove('active');
          btn.classList.add('bg-gray-200', 'text-gray-500');
          btn.classList.remove('bg-fju-blue', 'text-white', 'bg-fju-yellow', 'text-fju-blue', 'bg-fju-green');
        } else {
          map.addLayer(layer);
          btn.classList.add('active');
          btn.classList.remove('bg-gray-200', 'text-gray-500');
          const colors = {
            polygons: ['bg-fju-blue', 'text-white'],
            markers: ['bg-fju-yellow', 'text-fju-blue'],
            labels: ['bg-fju-green', 'text-white']
          };
          (colors[layerName] || []).forEach(c => btn.classList.add(c));
        }
      };

      /* ========================================
         FLY TO BUILDING
         ======================================== */
      window.flyToBuilding = function(name) {
        const building = buildingData.find(b => b.name === name);
        if (building) {
          map.flyTo(building.centroid, 19, { duration: 1.2 });
          // Open popup
          building.polygon.eachLayer(layer => {
            if (layer.openPopup) layer.openPopup();
          });
          // Flash effect
          building.polygon.eachLayer(layer => {
            if (layer.setStyle) {
              layer.setStyle({ fillOpacity: 0.8, weight: 4, color: '#FFB800' });
              setTimeout(() => {
                layer.setStyle({ fillOpacity: 0.35, weight: 2, color: '#FFFFFF' });
              }, 1500);
            }
          });
        }
      };

      /* ========================================
         RESET VIEW
         ======================================== */
      window.resetMapView = function() {
        if (buildingData.length > 0) {
          const allBounds = L.latLngBounds(buildingData.map(b => b.centroid));
          map.flyToBounds(allBounds.pad(0.05), { duration: 0.8 });
        } else {
          map.flyTo(CENTER, 17, { duration: 0.8 });
        }
      };

      // Store references globally for dashboard integration
      window._geoMap = map;
      window._geoBuildingData = buildingData;

      /* ========================================
         TRANSFORM COORDS DEMO (exposed globally)
         For verifying the affine transform with
         actual pixel coordinates.
         ======================================== */
      window.transformCoords = transformCoords;
      
      // Log anchor verification
      console.log('Anchor A (中美堂) → pixel:', transformCoords(ANCHOR_A.lat, ANCHOR_A.lng));
      console.log('Anchor B (第二個圓環) → pixel:', transformCoords(ANCHOR_B.lat, ANCHOR_B.lng));
      console.log('淨心堂 → pixel:', transformCoords(25.0359381, 121.4323090));

    })();
    </script>
  `
}
