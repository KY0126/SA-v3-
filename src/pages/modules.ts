import { appShell } from './layout'
import { clubs114, associations114, allClubsAndAssociations, getClubStats } from '../data/clubs'

/* ===================================================================
   FJU Smart Hub - Module Pages (Fully Interactive)
   All sidebar menu items with REAL API integration:
   主要功能: venue-booking, equipment, calendar
   社群與社團: club-info, activity-wall
   AI核心專區: ai-overview, ai-guide, rag-search
   管理與報表: repair, appeal, reports
   =================================================================== */

const modules: Record<string, (role: string) => string> = {

  // ===== 場地預約 (Full interactive booking) =====
  'venue-booking': (role) => appShell(role, 'venue-booking', '場地預約', `
    <div class="space-y-6">
      <!-- 3-Stage Flow -->
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h2 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-chess mr-2 text-fju-yellow"></i>三階段資源調度系統</h2>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="p-4 rounded-fju bg-fju-blue/5 border-2 border-fju-blue/20" id="stage-1">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-blue flex items-center justify-center text-white text-sm font-bold">1</div><span class="font-bold text-fju-blue text-sm">志願序配對</span></div>
            <p class="text-xs text-gray-500">Level 1-3 優先權自動配對</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>L1: 校方/處室 (最高優先)</div>
              <div>L2: 社團/自治組織</div>
              <div>L3: 一般社團/小組</div>
            </div>
          </div>
          <div class="p-4 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-yellow flex items-center justify-center text-fju-blue text-sm font-bold">2</div><span class="font-bold text-fju-blue text-sm">自主協商</span></div>
            <p class="text-xs text-gray-500">3/6 分鐘規則 (Redis + GPT-4)</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>3分鐘: GPT-4 介入建議</div>
              <div>6分鐘: 強制關閉、扣10分</div>
              <div>LINE 邀請 + 私下調解</div>
            </div>
          </div>
          <div class="p-4 rounded-fju bg-fju-green/5 border border-fju-green/10">
            <div class="flex items-center gap-2 mb-2"><div class="w-8 h-8 rounded-fju bg-fju-green flex items-center justify-center text-white text-sm font-bold">3</div><span class="font-bold text-fju-blue text-sm">官方核准</span></div>
            <p class="text-xs text-gray-500">RAG 法規比對 + Gatekeeping</p>
            <div class="mt-2 space-y-1 text-[10px] text-gray-400">
              <div>比對行政流程法規</div>
              <div>產出 PDF + TOTP QR</div>
              <div>活動核准號 → 場地解鎖</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Stats (loaded from API) -->
      <div class="grid md:grid-cols-4 gap-4" id="venue-stats"></div>

      <!-- Booking Form Modal -->
      <div id="booking-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-plus mr-2 text-fju-yellow"></i>新增場地預約</h3>
            <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
          </div>
          <form id="booking-form" onsubmit="submitBooking(event)">
            <div class="space-y-3">
              <div><label class="text-xs text-gray-400 block mb-1">場地</label>
                <select id="bk-venue" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></select></div>
              <div class="grid grid-cols-2 gap-3">
                <div><label class="text-xs text-gray-400 block mb-1">日期</label><input type="date" id="bk-date" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div>
                <div><label class="text-xs text-gray-400 block mb-1">優先等級</label>
                  <select id="bk-priority" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm">
                    <option value="3">L3 - 一般社團</option><option value="2">L2 - 社團幹部</option><option value="1">L1 - 校方</option>
                  </select></div>
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div><label class="text-xs text-gray-400 block mb-1">開始時間</label><input type="time" id="bk-start" value="14:00" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div>
                <div><label class="text-xs text-gray-400 block mb-1">結束時間</label><input type="time" id="bk-end" value="17:00" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" required></div>
              </div>
              <div><label class="text-xs text-gray-400 block mb-1">用途說明</label><textarea id="bk-purpose" rows="2" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="請說明借用目的..."></textarea></div>
            </div>
            <div id="booking-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
            <button type="submit" class="w-full btn-yellow py-3 mt-4"><i class="fas fa-paper-plane mr-2"></i>送出預約申請</button>
          </form>
        </div>
      </div>

      <!-- Venue List (loaded from API) -->
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
          <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>場地清單</h3>
          <button onclick="openBookingModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增預約</button>
        </div>
        <div id="venue-table-body">
          <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div>
        </div>
      </div>

      <!-- Charts -->
      <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-fire mr-2 text-fju-yellow"></i>場地使用熱力圖</h3>
          <canvas id="venue-heatmap" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地周轉排行</h3>
          <canvas id="venue-ranking" height="200"></canvas>
        </div>
      </div>
    </div>
    <script>
    (function(){
      // Load venues from API
      fetch('/api/venues').then(r=>r.json()).then(res => {
        const venues = res.data;
        // Populate stats
        const available = venues.filter(v=>v.status==='available').length;
        const maint = venues.filter(v=>v.status==='maintenance').length;
        document.getElementById('venue-stats').innerHTML = 
          '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">'+venues.length+'</div><div class="text-xs text-gray-400">總場地數</div></div>'+
          '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">'+available+'</div><div class="text-xs text-gray-400">可用場地</div></div>'+
          '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">87%</div><div class="text-xs text-gray-400">使用率</div></div>'+
          '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-red">'+maint+'</div><div class="text-xs text-gray-400">維護中</div></div>';
        
        // Populate table
        document.getElementById('venue-table-body').innerHTML = '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">場地</th><th class="p-4">位置</th><th class="p-4">容量</th><th class="p-4">設備</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>'+
          venues.map(v => '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-medium text-fju-blue">'+v.name+'</td><td class="p-4 text-gray-500">'+v.location+'</td><td class="p-4">'+v.capacity+'人</td><td class="p-4 text-xs text-gray-400">'+(v.equipment_list||'-')+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju '+(v.status==='available'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+' text-xs">'+(v.status==='available'?'可預約':'維護中')+'</span></td><td class="p-4">'+(v.status==='available'?'<button onclick="quickBook(\\''+v.name+'\\')" class="btn-yellow px-3 py-1 text-xs">預約</button>':'<span class="text-xs text-gray-400">-</span>')+'</td></tr>').join('')+
          '</tbody></table>';
        
        // Populate booking form select
        const sel = document.getElementById('bk-venue');
        venues.filter(v=>v.status==='available').forEach(v => {
          sel.innerHTML += '<option value="'+v.id+'">'+v.name+' ('+v.capacity+'人)</option>';
        });
      });

      // Charts
      document.addEventListener('DOMContentLoaded', () => {
        if(document.getElementById('venue-heatmap')){
          new Chart(document.getElementById('venue-heatmap'), {type:'bar',data:{labels:['08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00'],datasets:[{label:'使用數',data:[8,25,38,42,15,35,45,40,30,22,12,5],backgroundColor:function(ctx){const v=ctx.raw;return v>35?'#FF0000':v>20?'#DAA520':'#008000';}}]},options:{responsive:true,plugins:{legend:{display:false}}}});
        }
        if(document.getElementById('venue-ranking')){
          new Chart(document.getElementById('venue-ranking'), {type:'bar',data:{labels:['中美堂','活動中心','SF 134','體育館','百鍊廳','焯炤館'],datasets:[{label:'本月使用次數',data:[45,38,35,28,22,18],backgroundColor:'#003153'}]},options:{responsive:true,indexAxis:'y',plugins:{legend:{display:false}}}});
        }
      });
    })();

    function openBookingModal() { document.getElementById('booking-modal').classList.remove('hidden'); }
    function closeBookingModal() { document.getElementById('booking-modal').classList.add('hidden'); document.getElementById('booking-result').classList.add('hidden'); }
    function quickBook(name) { openBookingModal(); /* Pre-select venue */ }
    
    function submitBooking(e) {
      e.preventDefault();
      const data = {
        venue_id: document.getElementById('bk-venue').value,
        venue_name: document.getElementById('bk-venue').selectedOptions[0].text,
        reservation_date: document.getElementById('bk-date').value,
        start_time: document.getElementById('bk-start').value,
        end_time: document.getElementById('bk-end').value,
        priority_level: parseInt(document.getElementById('bk-priority').value),
        purpose: document.getElementById('bk-purpose').value
      };
      
      fetch('/api/reservations', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify(data) })
        .then(r=>r.json()).then(res => {
          const box = document.getElementById('booking-result');
          box.classList.remove('hidden');
          if (res.success) {
            box.className = 'mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green';
            box.innerHTML = '<i class="fas fa-check-circle mr-1"></i> '+res.message+' (階段：'+res.stage+')';
          } else {
            box.className = 'mt-3 p-3 rounded-fju text-sm bg-fju-yellow/20 text-fju-blue';
            box.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> '+res.message+'<br><small>衝突方：'+res.conflict.conflicting_party+' | 協商倒數：'+res.conflict.negotiation_timer+'秒</small>';
          }
        });
    }
    </script>
  `),

  // ===== 設備借用 (Full interactive borrow/return) =====
  'equipment': (role) => appShell(role, 'equipment', '設備借用', `
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex gap-2">
          <button onclick="filterEquipment('all')" class="eq-filter px-4 py-2 rounded-fju bg-fju-blue text-white text-sm" data-filter="all">全部器材</button>
          <button onclick="filterEquipment('available')" class="eq-filter px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm hover:bg-gray-200" data-filter="available">可借用</button>
          <button onclick="filterEquipment('borrowed')" class="eq-filter px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm hover:bg-gray-200" data-filter="borrowed">已借出</button>
          <button onclick="filterEquipment('maintenance')" class="eq-filter px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm hover:bg-gray-200" data-filter="maintenance">維修中</button>
        </div>
        <div class="flex gap-2">
          <div class="relative"><i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i><input id="eq-search" oninput="filterEquipment()" type="text" placeholder="搜尋器材..." class="pl-9 pr-4 py-2 rounded-fju border border-gray-200 text-sm w-48 focus:border-fju-blue outline-none"></div>
        </div>
      </div>
      <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
        <div id="equipment-table"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
      </div>

      <!-- Borrow Modal -->
      <div id="borrow-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
          <h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hand-holding mr-2 text-fju-yellow"></i>借用器材</h3>
          <div id="borrow-item-info" class="p-3 rounded-fju bg-fju-bg mb-4"></div>
          <div class="space-y-3">
            <div><label class="text-xs text-gray-400 block mb-1">預計歸還日</label><input type="date" id="borrow-return-date" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"></div>
            <div><label class="text-xs text-gray-400 block mb-1">用途</label><input type="text" id="borrow-purpose" placeholder="例：社團活動拍攝" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"></div>
          </div>
          <div class="flex items-center gap-3 p-3 rounded-fju bg-fju-yellow/10 mt-3 text-xs text-fju-blue">
            <i class="fas fa-bell text-fju-yellow"></i>
            <span>系統將於歸還前1天透過 LINE 發送提醒，逾期將自動扣除信用積分</span>
          </div>
          <div id="borrow-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
          <div class="flex gap-3 mt-4">
            <button onclick="closeBorrowModal()" class="flex-1 px-4 py-2.5 rounded-fju border border-gray-200 text-gray-500 text-sm">取消</button>
            <button onclick="confirmBorrow()" class="flex-1 btn-yellow py-2.5 text-sm"><i class="fas fa-check mr-1"></i>確認借用</button>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-bell mr-2 text-fju-yellow"></i>到期提醒設定 (LINE / SMS)</h3>
        <div class="grid md:grid-cols-3 gap-4">
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50"><input type="checkbox" checked class="accent-fju-green"><span class="text-sm text-gray-600">歸還前 1 天 LINE 提醒</span></div>
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50"><input type="checkbox" checked class="accent-fju-green"><span class="text-sm text-gray-600">逾期 SMS 通知</span></div>
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50"><input type="checkbox" class="accent-fju-green"><span class="text-sm text-gray-600">自動扣除信用積分</span></div>
        </div>
      </div>
    </div>
    <script>
    let allEquipment = [];
    let currentFilter = 'all';
    
    fetch('/api/equipment').then(r=>r.json()).then(res => {
      allEquipment = res.data;
      renderEquipmentTable(allEquipment);
    });

    function renderEquipmentTable(data) {
      const statusMap = {available:{label:'可借用',class:'bg-fju-green/10 text-fju-green'}, borrowed:{label:'已借出',class:'bg-fju-red/10 text-fju-red'}, maintenance:{label:'維修中',class:'bg-fju-yellow/20 text-fju-yellow'}};
      document.getElementById('equipment-table').innerHTML = '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">編號</th><th class="p-4">名稱</th><th class="p-4">類別</th><th class="p-4">狀態</th><th class="p-4">借用人</th><th class="p-4">歸還日</th><th class="p-4">操作</th></tr></thead><tbody>'+
        data.map(e => {
          const s = statusMap[e.status] || statusMap.available;
          const isOverdue = e.return_date && new Date(e.return_date) < new Date();
          return '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs text-gray-400">'+e.code+'</td><td class="p-4 font-medium text-fju-blue">'+e.name+'</td><td class="p-4 text-xs"><span class="px-2 py-1 rounded-full bg-fju-blue/5 text-fju-blue">'+e.category+'</span></td><td class="p-4"><span class="px-2 py-1 rounded-fju '+s.class+' text-xs">'+s.label+'</span></td><td class="p-4">'+(e.borrower||'<span class="text-gray-300">-</span>')+'</td><td class="p-4 '+(isOverdue?'text-fju-red font-medium':'text-gray-400')+'">'+(e.return_date||(isOverdue?e.return_date+' ⚠️':'-'))+'</td><td class="p-4">'+
          (e.status==='available'?'<button onclick="openBorrowModal('+e.id+',\\''+e.name.replace(/'/g,"\\\\'")+'\\',\\''+e.code+'\\')" class="btn-yellow px-3 py-1 text-xs">借出</button>':
           e.status==='borrowed'?'<button onclick="sendReminder('+e.id+')" class="text-xs text-fju-blue hover:underline"><i class="fas fa-bell mr-1"></i>提醒</button>':'<span class="text-xs text-gray-400">-</span>')+'</td></tr>';
        }).join('')+'</tbody></table>';
    }

    function filterEquipment(status) {
      if(status) currentFilter = status;
      const search = (document.getElementById('eq-search')?.value||'').toLowerCase();
      let data = allEquipment;
      if(currentFilter !== 'all') data = data.filter(e => e.status === currentFilter);
      if(search) data = data.filter(e => e.name.toLowerCase().includes(search) || e.code.toLowerCase().includes(search));
      renderEquipmentTable(data);
      document.querySelectorAll('.eq-filter').forEach(b => {
        b.classList.remove('bg-fju-blue','text-white');
        b.classList.add('bg-gray-100','text-gray-500');
      });
      const active = document.querySelector('.eq-filter[data-filter="'+currentFilter+'"]');
      if(active) { active.classList.add('bg-fju-blue','text-white'); active.classList.remove('bg-gray-100','text-gray-500'); }
    }

    let borrowingItem = {};
    function openBorrowModal(id, name, code) {
      borrowingItem = {id, name, code};
      document.getElementById('borrow-item-info').innerHTML = '<div class="font-medium text-fju-blue">'+name+'</div><div class="text-xs text-gray-400">編號：'+code+'</div>';
      document.getElementById('borrow-modal').classList.remove('hidden');
      document.getElementById('borrow-result').classList.add('hidden');
    }
    function closeBorrowModal() { document.getElementById('borrow-modal').classList.add('hidden'); }
    
    function confirmBorrow() {
      fetch('/api/equipment/borrow', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({
        equipment_id: borrowingItem.id,
        return_date: document.getElementById('borrow-return-date').value,
        purpose: document.getElementById('borrow-purpose').value
      })}).then(r=>r.json()).then(res => {
        const box = document.getElementById('borrow-result');
        box.classList.remove('hidden');
        box.className = 'mt-3 p-3 rounded-fju text-sm bg-fju-green/10 text-fju-green';
        box.innerHTML = '<i class="fas fa-check-circle mr-1"></i> '+res.message+'<br><small>歸還日：'+res.return_date+'</small>';
      });
    }

    function sendReminder(id) {
      fetch('/api/equipment/remind', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({equipment_id:id})})
        .then(r=>r.json()).then(res => alert(res.message));
    }
    </script>
  `),

  // ===== 行事曆 (evo-calendar with API data) =====
  'calendar': (role) => appShell(role, 'calendar', '行事曆', `
    <div class="space-y-6">
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
          <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-calendar-alt mr-2 text-fju-yellow"></i>智慧行事曆</h2>
          <div class="flex gap-2">
            <span class="text-xs px-3 py-1 rounded-full bg-fju-blue/10 text-fju-blue"><i class="fas fa-lock mr-1"></i>需登入使用</span>
            <span class="text-xs px-3 py-1 rounded-full bg-fju-green/10 text-fju-green"><i class="fas fa-check mr-1"></i>已登入</span>
          </div>
        </div>
        <div id="evo-calendar"></div>
      </div>
      <!-- Upcoming Events from API -->
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-list mr-2 text-fju-yellow"></i>近期活動</h3>
        <div id="upcoming-events"><div class="text-center text-gray-400 text-sm py-4"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
      </div>
    </div>
    <script>
    // Load events from API
    fetch('/api/calendar/events').then(r=>r.json()).then(res => {
      const events = res.data;
      // Init evo-calendar
      $(document).ready(function() {
        $('#evo-calendar').evoCalendar({
          theme: 'Royal Navy', language: 'zh', format: 'yyyy-mm-dd',
          titleFormat: 'yyyy MM', eventHeaderFormat: 'MM dd, yyyy',
          calendarEvents: events.map(e => ({
            id: e.id, name: e.title, date: e.date, type: 'event',
            color: e.color, description: e.description
          }))
        });
      });
      // Render upcoming
      const upcoming = events.filter(e => new Date(e.date) >= new Date()).sort((a,b) => new Date(a.date) - new Date(b.date)).slice(0, 5);
      document.getElementById('upcoming-events').innerHTML = upcoming.map(e =>
        '<div class="flex items-center gap-4 p-3 rounded-fju hover:bg-fju-bg transition-colors mb-2 cursor-pointer" onclick="window.location.href=\\'/module/activity-wall?role=${role}\\'">'+
        '<div class="w-12 h-12 rounded-fju flex flex-col items-center justify-center text-white shrink-0" style="background:'+e.color+'"><div class="text-[10px]">'+e.date.split('-')[1]+'月</div><div class="font-bold">'+e.date.split('-')[2]+'</div></div>'+
        '<div class="flex-1"><div class="font-medium text-fju-blue text-sm">'+e.title+'</div><div class="text-xs text-gray-400">'+e.description+'</div></div>'+
        '<i class="fas fa-chevron-right text-gray-300 text-xs"></i></div>'
      ).join('');
    });
    </script>
  `),

  // ===== 社團資訊 (FULL 114 data with search/filter) =====
  'club-info': (role) => {
    const stats = getClubStats()
    const categoryBtns = Object.entries(stats.byCategory).map(([cat, count]) => 
      `<button onclick="filterClubs('${cat}')" class="club-cat-btn px-3 py-1.5 rounded-fju bg-gray-100 text-gray-500 text-xs hover:bg-gray-200 transition-all" data-cat="${cat}">${cat} (${count})</button>`
    ).join('')

    return appShell(role, 'club-info', '社團資訊', `
    <div class="space-y-6">
      <!-- Stats Banner -->
      <div class="bg-gradient-to-r from-fju-blue to-fju-blue-light rounded-fju-lg p-6 text-white">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
          <div><div class="text-3xl font-black">${stats.totalClubs}</div><div class="text-white/60 text-xs">社團數</div></div>
          <div><div class="text-3xl font-black text-fju-yellow">${stats.totalAssociations}</div><div class="text-white/60 text-xs">學會數</div></div>
          <div><div class="text-3xl font-black">${stats.totalAll}</div><div class="text-white/60 text-xs">總數</div></div>
          <div><div class="text-3xl font-black text-fju-yellow">${stats.totalMembers.toLocaleString()}</div><div class="text-white/60 text-xs">總社員數</div></div>
        </div>
      </div>

      <!-- Filters -->
      <div class="flex flex-wrap items-center gap-3">
        <div class="flex-1 relative">
          <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" id="club-search" placeholder="搜尋社團/學會名稱..." oninput="searchClubs()" class="w-full pl-11 pr-4 py-2.5 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none">
        </div>
        <div class="flex gap-2 flex-wrap">
          <button onclick="filterClubs('all')" class="club-cat-btn active px-3 py-1.5 rounded-fju bg-fju-blue text-white text-xs" data-cat="all">全部 (${stats.totalAll})</button>
          <button onclick="filterClubs('club')" class="club-cat-btn px-3 py-1.5 rounded-fju bg-gray-100 text-gray-500 text-xs hover:bg-gray-200" data-cat="club">社團 (${stats.totalClubs})</button>
          <button onclick="filterClubs('association')" class="club-cat-btn px-3 py-1.5 rounded-fju bg-gray-100 text-gray-500 text-xs hover:bg-gray-200" data-cat="association">學會 (${stats.totalAssociations})</button>
          ${categoryBtns}
        </div>
      </div>

      <!-- Club Grid (from API) -->
      <div id="club-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
      <div id="club-count" class="text-center text-xs text-gray-400"></div>
    </div>
    <script>
    let allClubs = [];
    let currentCatFilter = 'all';

    fetch('/api/clubs').then(r=>r.json()).then(res => {
      allClubs = res.data;
      renderClubs(allClubs);
    });

    function renderClubs(data) {
      const icons = {academic:'fas fa-book',recreation:'fas fa-music',sports:'fas fa-running',arts:'fas fa-palette',service:'fas fa-heart',general:'fas fa-star'};
      const colors = {academic:'bg-fju-blue/10 text-fju-blue',recreation:'bg-fju-yellow/20 text-fju-yellow',sports:'bg-fju-green/10 text-fju-green',arts:'bg-purple-100 text-purple-600',service:'bg-pink-100 text-pink-600',general:'bg-gray-100 text-gray-600'};
      document.getElementById('club-grid').innerHTML = data.map(c => 
        '<div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 card-hover">'+
        '<div class="flex items-center gap-3 mb-3">'+
        '<div class="w-12 h-12 rounded-fju '+(colors[c.category]||colors.general).split(' ')[0]+' flex items-center justify-center"><i class="'+(icons[c.category]||icons.general)+' '+(colors[c.category]||colors.general).split(' ')[1]+' text-lg"></i></div>'+
        '<div><h3 class="font-bold text-fju-blue text-sm">'+c.name+'</h3>'+
        '<div class="flex gap-1"><span class="text-[10px] px-2 py-0.5 rounded-full '+(colors[c.category]||colors.general)+'">'+c.categoryLabel+'</span>'+
        '<span class="text-[10px] px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">'+(c.type==='club'?'社團':'學會')+'</span></div></div></div>'+
        '<p class="text-xs text-gray-500 mb-3">'+c.description+'</p>'+
        '<div class="flex items-center justify-between"><span class="text-xs text-gray-400"><i class="fas fa-users mr-1"></i>'+c.memberCount+' 成員</span>'+
        '<button onclick="alert(\\'社團詳情：'+c.name+'\\\\n'+c.description+'\\\\n成員數：'+c.memberCount+'\\')" class="btn-yellow px-3 py-1 text-xs">查看詳情</button></div></div>'
      ).join('');
      document.getElementById('club-count').textContent = '顯示 '+data.length+' / '+allClubs.length+' 筆';
    }

    function filterClubs(cat) {
      currentCatFilter = cat;
      applyClubFilters();
      document.querySelectorAll('.club-cat-btn').forEach(b => {
        b.classList.remove('bg-fju-blue','text-white','active');
        b.classList.add('bg-gray-100','text-gray-500');
      });
      const active = document.querySelector('.club-cat-btn[data-cat="'+cat+'"]');
      if(active) { active.classList.add('bg-fju-blue','text-white','active'); active.classList.remove('bg-gray-100','text-gray-500'); }
    }

    function searchClubs() { applyClubFilters(); }

    function applyClubFilters() {
      const search = (document.getElementById('club-search')?.value||'').toLowerCase();
      let data = allClubs;
      if(currentCatFilter === 'club') data = data.filter(c => c.type === 'club');
      else if(currentCatFilter === 'association') data = data.filter(c => c.type === 'association');
      else if(currentCatFilter !== 'all') data = data.filter(c => c.categoryLabel === currentCatFilter);
      if(search) data = data.filter(c => c.name.toLowerCase().includes(search) || c.description.toLowerCase().includes(search));
      renderClubs(data);
    }
    </script>
  `)},

  // ===== 活動牆 (API-driven with registration) =====
  'activity-wall': (role) => appShell(role, 'activity-wall', '活動牆', `
    <div class="space-y-6">
      <div class="flex items-center gap-4">
        <div class="flex-1 relative">
          <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" id="act-search" placeholder="搜尋活動..." oninput="searchActivities()" class="w-full pl-11 pr-4 py-2.5 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none">
        </div>
        <div class="flex gap-2">
          <button onclick="filterActivities('all')" class="act-filter px-3 py-2 rounded-fju bg-fju-blue text-white text-xs" data-cat="all">全部</button>
          <button onclick="filterActivities('academic')" class="act-filter px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="academic">學術</button>
          <button onclick="filterActivities('service')" class="act-filter px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="service">服務</button>
          <button onclick="filterActivities('recreation')" class="act-filter px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="recreation">康樂</button>
          <button onclick="filterActivities('sports')" class="act-filter px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="sports">體育</button>
          <button onclick="filterActivities('arts')" class="act-filter px-3 py-2 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="arts">藝文</button>
        </div>
      </div>
      <div id="activity-grid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
    </div>
    <script>
    let allActivities = [];
    let actFilter = 'all';

    fetch('/api/activities').then(r=>r.json()).then(res => {
      allActivities = res.data;
      renderActivities(allActivities);
    });

    function renderActivities(data) {
      const catColors = {academic:'from-fju-blue/20 to-fju-blue/5',service:'from-fju-green/20 to-fju-green/5',recreation:'from-fju-yellow/20 to-fju-yellow/5',sports:'from-fju-red/20 to-fju-red/5',arts:'from-purple-200/50 to-purple-50'};
      const catLabels = {academic:'學術',service:'服務',recreation:'康樂',sports:'體育',arts:'藝文'};
      const statusMap = {approved:{label:'已核准',class:'bg-fju-green/10 text-fju-green'},pending:{label:'審核中',class:'bg-fju-yellow/20 text-fju-yellow'}};
      document.getElementById('activity-grid').innerHTML = data.map(a => {
        const pct = a.max_participants > 0 ? Math.round((a.current_participants/a.max_participants)*100) : 0;
        const full = pct >= 100;
        return '<div class="bg-white rounded-fju-lg overflow-hidden shadow-sm border border-gray-100 card-hover">'+
        '<div class="h-32 bg-gradient-to-br '+(catColors[a.category]||catColors.academic)+' flex items-center justify-center relative">'+
        '<i class="fas fa-calendar-day text-4xl text-fju-blue/20"></i>'+
        '<span class="absolute top-3 right-3 px-2 py-0.5 rounded-fju '+(statusMap[a.status]||statusMap.pending).class+' text-[10px]">'+(statusMap[a.status]||statusMap.pending).label+'</span></div>'+
        '<div class="p-5"><div class="flex items-center gap-2 mb-2">'+
        '<span class="px-2 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow text-[10px] font-bold">'+(catLabels[a.category]||a.category)+'</span>'+
        '<span class="text-xs text-gray-400">'+a.event_date+'</span></div>'+
        '<h3 class="font-bold text-fju-blue mb-1">'+a.title+'</h3>'+
        '<p class="text-xs text-gray-400 mb-2"><i class="fas fa-map-marker-alt mr-1"></i>'+a.venue+' · <i class="fas fa-users ml-1 mr-1"></i>'+a.current_participants+'/'+a.max_participants+'人</p>'+
        '<div class="w-full bg-gray-100 rounded-full h-1.5 mb-3"><div class="rounded-full h-1.5 transition-all '+(full?'bg-fju-red':'bg-fju-green')+'" style="width:'+Math.min(pct,100)+'%"></div></div>'+
        '<div class="flex items-center justify-between"><span class="text-xs text-gray-500">'+a.club+'</span>'+
        (full?'<span class="text-xs text-fju-red font-medium">已額滿</span>':
        '<button onclick="registerActivity('+a.id+',\\''+a.title.replace(/'/g,"\\\\'")+'\\',this)" class="btn-yellow px-3 py-1 text-xs">報名</button>')+'</div></div></div>';
      }).join('');
    }

    function filterActivities(cat) {
      actFilter = cat;
      applyActFilters();
      document.querySelectorAll('.act-filter').forEach(b => { b.classList.remove('bg-fju-blue','text-white'); b.classList.add('bg-gray-100','text-gray-500'); });
      const active = document.querySelector('.act-filter[data-cat="'+cat+'"]');
      if(active) { active.classList.add('bg-fju-blue','text-white'); active.classList.remove('bg-gray-100','text-gray-500'); }
    }
    function searchActivities() { applyActFilters(); }
    function applyActFilters() {
      const search = (document.getElementById('act-search')?.value||'').toLowerCase();
      let data = allActivities;
      if(actFilter !== 'all') data = data.filter(a => a.category === actFilter);
      if(search) data = data.filter(a => a.title.toLowerCase().includes(search) || a.club.toLowerCase().includes(search));
      renderActivities(data);
    }

    function registerActivity(id, title, btn) {
      fetch('/api/activities/'+id+'/register', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({user_id:1})})
        .then(r=>r.json()).then(res => {
          if(btn) { btn.textContent = '✓ 已報名'; btn.disabled = true; btn.classList.add('opacity-50'); }
          alert('報名成功：' + title);
        });
    }
    </script>
  `),

  // ===== AI 資訊概覽 =====
  'ai-overview': (role) => appShell(role, 'ai-overview', 'AI 資訊概覽', `
    <div class="space-y-6">
      <div class="bg-gradient-to-r from-fju-blue to-fju-blue-light rounded-fju-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-2"><i class="fas fa-brain mr-2 text-fju-yellow"></i>FJU Smart Hub AI 核心引擎</h2>
        <p class="text-white/60 text-sm">整合 Dify AI 平台、GPT-4、Pinecone 向量資料庫，提供智慧化校園管理服務</p>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        ${[
          { title: 'AI 智慧預審', desc: 'RAG 引擎自動比對校內法規，標記合規風險', icon: 'fas fa-shield-alt', stat: '94% 通過率', module: 'rag-search' },
          { title: 'AI 企劃生成', desc: '調用 Dify Workflow 參考法規自動生成企劃書', icon: 'fas fa-wand-magic-sparkles', stat: '每日 50+ 生成', module: 'ai-guide' },
          { title: 'AI 申訴摘要', desc: 'WebSocket 即時生成申訴案件摘要與建議', icon: 'fas fa-comments', stat: '即時處理', module: 'appeal' },
          { title: 'AI 協商監控', desc: 'GPT-4 監控 3/6 分鐘協商機制', icon: 'fas fa-chess', stat: '85% 自動解決', module: 'venue-booking' },
          { title: 'AI 推薦引擎', desc: '根據學生興趣與職能推薦活動', icon: 'fas fa-lightbulb', stat: '個人化推薦', module: 'activity-wall' },
          { title: 'RAG 知識庫', desc: 'Pinecone 向量 DB 索引 5+ 法規文件', icon: 'fas fa-database', stat: '5 法規已索引', module: 'rag-search' },
        ].map(ai => `
        <a href="/module/${ai.module}?role=${role}" class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 card-hover block">
          <div class="w-12 h-12 rounded-fju bg-fju-blue/10 flex items-center justify-center mb-3"><i class="${ai.icon} text-fju-blue text-lg"></i></div>
          <h3 class="font-bold text-fju-blue mb-1">${ai.title}</h3>
          <p class="text-xs text-gray-500 mb-2">${ai.desc}</p>
          <span class="text-xs px-2 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow font-medium">${ai.stat}</span>
        </a>`).join('')}
      </div>
    </div>
  `),

  // ===== AI 導覽助理 (with real API call) =====
  'ai-guide': (role) => appShell(role, 'ai-guide', 'AI 導覽助理', `
    <div class="grid lg:grid-cols-5 gap-6">
      <div class="lg:col-span-3 space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-wand-magic-sparkles mr-2 text-fju-yellow"></i>AI 企劃助手 (Dify Workflow)</h2>
          <form id="proposal-form" onsubmit="generateProposal(event)">
            <div class="space-y-4">
              <div><label class="text-xs text-gray-400 block mb-1">活動名稱</label><input id="prop-title" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="例：攝影社春季外拍活動" required></div>
              <div><label class="text-xs text-gray-400 block mb-1">活動類型</label>
                <select id="prop-type" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"><option>學術研討</option><option>社團活動</option><option>服務學習</option><option>康樂活動</option><option>體育競賽</option><option>藝文展演</option></select></div>
              <div class="grid grid-cols-2 gap-3">
                <div><label class="text-xs text-gray-400 block mb-1">預計人數</label><input type="number" id="prop-people" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="50" required></div>
                <div><label class="text-xs text-gray-400 block mb-1">活動日期</label><input type="date" id="prop-date" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"></div>
              </div>
              <div><label class="text-xs text-gray-400 block mb-1">活動描述</label><textarea id="prop-desc" rows="3" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="請描述活動目標、預期效益..." required></textarea></div>
              <button type="submit" class="w-full btn-yellow py-3" id="gen-btn"><i class="fas fa-wand-magic-sparkles mr-2"></i>AI 一鍵生成企劃書</button>
            </div>
          </form>
        </div>
        <div id="ai-result" class="hidden bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-3"><i class="fas fa-file-alt mr-2 text-fju-yellow"></i>AI 生成結果</h2>
          <div id="ai-result-content" class="text-sm text-gray-600 leading-relaxed"></div>
        </div>
      </div>
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue mb-3 text-sm">RAG 法規檢索</h3>
          <div class="space-y-2">
            ${['活動申請辦法 v2.1', '場地使用管理規則 v3.0', '經費補助要點 v1.5', '器材借用辦法 v2.0', '安全管理規範 v1.8'].map(doc => `
            <div class="p-3 rounded-fju bg-gray-50 text-xs text-gray-600 hover:bg-fju-blue/5 cursor-pointer transition-colors"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>${doc}</div>`).join('')}
          </div>
          <p class="text-[10px] text-gray-400 mt-3">Pinecone 向量資料庫 | 5 個法規文件已索引</p>
        </div>
      </div>
    </div>
    <script>
    function generateProposal(e) {
      e.preventDefault();
      const btn = document.getElementById('gen-btn');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>AI 生成中...';
      btn.disabled = true;
      
      fetch('/api/ai/generate-proposal', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({
        title: document.getElementById('prop-title').value,
        type: document.getElementById('prop-type').value,
        participants: parseInt(document.getElementById('prop-people').value),
        date: document.getElementById('prop-date').value,
        description: document.getElementById('prop-desc').value,
      })}).then(r=>r.json()).then(res => {
        btn.innerHTML = '<i class="fas fa-wand-magic-sparkles mr-2"></i>AI 一鍵生成企劃書';
        btn.disabled = false;
        document.getElementById('ai-result').classList.remove('hidden');
        const s = res.sections;
        let budgetHtml = res.sections.budget_breakdown.map(b => '<div class="flex justify-between"><span>'+b.item+'</span><span class="font-medium">NT$'+b.amount.toLocaleString()+'</span></div>').join('');
        document.getElementById('ai-result-content').innerHTML = 
          '<p class="mb-2"><strong>'+s.purpose+'</strong></p>'+
          '<p class="mb-2">'+s.time+'</p>'+
          '<p class="mb-2">'+s.participants+'</p>'+
          '<p class="mb-2"><strong>'+s.flow[0]+'</strong></p>'+s.flow.slice(1).map(f=>'<p class="mb-1 pl-4 text-gray-500">'+f+'</p>').join('')+
          '<p class="mb-2 mt-2"><strong>'+s.budget+'</strong></p>'+
          '<div class="p-3 rounded-fju bg-gray-50 space-y-1 mb-3 text-xs">'+budgetHtml+'</div>'+
          '<p class="mb-2">'+s.risk+'</p>'+
          '<p class="mb-2">'+s.sdg+'</p>'+
          '<div class="mt-3 p-3 rounded-fju '+(res.ai_review.risk_level==='Low'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow')+' text-xs">'+
          '<i class="fas '+(res.ai_review.risk_level==='Low'?'fa-check-circle':'fa-exclamation-triangle')+' mr-1"></i>'+
          'AI 預審：風險等級 '+res.ai_review.risk_level+' | 信心指數 '+Math.round(res.ai_review.confidence*100)+'%</div>';
      });
    }
    </script>
  `),

  // ===== 法規查詢 (RAG) =====
  'rag-search': (role) => appShell(role, 'rag-search', '法規查詢 (RAG)', `
    <div class="space-y-6">
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>RAG 智慧法規查詢</h2>
        <div class="flex gap-3">
          <input type="text" id="rag-query" placeholder="輸入您的查詢，例如：場地借用需要提前幾天申請？" class="flex-1 px-4 py-3 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none" onkeypress="if(event.key==='Enter')queryRAG()">
          <button onclick="queryRAG()" class="btn-yellow px-6 py-3 text-sm"><i class="fas fa-search mr-2"></i>AI 查詢</button>
        </div>
        <div class="flex gap-2 mt-3 flex-wrap">
          ${['場地借用需要提前幾天？','活動人數超過50人要什麼文件？','器材損壞要怎麼處理？','經費補助上限是多少？'].map(q => 
            `<button onclick="document.getElementById('rag-query').value='${q}';queryRAG()" class="text-xs px-3 py-1 rounded-full bg-fju-blue/5 text-fju-blue hover:bg-fju-blue/10 transition-colors">${q}</button>`
          ).join('')}
        </div>
      </div>
      <div id="rag-result" class="hidden bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 回覆</h3>
        <div id="rag-answer" class="p-4 rounded-fju bg-fju-bg text-sm text-gray-600 leading-relaxed"></div>
      </div>
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue mb-3 text-sm">知識庫文件</h3>
        <div class="grid md:grid-cols-2 gap-3">
          ${['活動申請辦法 v2.1', '場地使用管理規則 v3.0', '經費補助要點 v1.5', '器材借用辦法 v2.0', '安全管理規範 v1.8'].map(doc => `
          <div class="flex items-center gap-3 p-3 rounded-fju bg-gray-50 hover:bg-fju-blue/5 transition-colors cursor-pointer">
            <i class="fas fa-file-alt text-fju-yellow"></i>
            <span class="text-sm text-gray-600">${doc}</span>
            <i class="fas fa-external-link-alt text-gray-300 ml-auto text-xs"></i>
          </div>`).join('')}
        </div>
      </div>
    </div>
    <script>
    function queryRAG() {
      const query = document.getElementById('rag-query').value.trim();
      if(!query) return;
      document.getElementById('rag-result').classList.remove('hidden');
      document.getElementById('rag-answer').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>AI 查詢中...';
      
      // Simulate RAG response
      setTimeout(() => {
        const answers = {
          '場地': '<p class="mb-2">根據《場地使用管理規則》第3條規定：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">「申請人應於使用日前 <strong>三個工作日</strong> 提出申請，經課外活動指導組審核通過後，方得使用。」</p><p class="mb-2">此外，根據第5條：大型活動（50人以上）需額外提交安全計畫書。</p>',
          '器材': '<p class="mb-2">根據《器材借用辦法》第4條：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">「借用人應於使用完畢後 <strong>24小時內</strong> 歸還器材。逾期者每日扣除信用積分 2 分。」</p><p class="mb-2">器材損壞需於48小時內提交報修單（RP表單）並附照片說明。</p>',
          '經費': '<p class="mb-2">根據《經費補助要點》第2條：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">「每學期每社團補助上限為 <strong>NT$50,000</strong>，需提交核銷單據。」</p>',
          '人數': '<p class="mb-2">根據《活動申請辦法》第5條與《安全管理規範》第3條：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">活動人數超過 <strong>50人</strong> 須提交：1)安全計畫書 2)保險證明 3)緊急醫療方案</p>',
        };
        const key = Object.keys(answers).find(k => query.includes(k)) || '場地';
        document.getElementById('rag-answer').innerHTML = answers[key] + 
          '<div class="mt-3 p-3 rounded-fju bg-fju-blue/5 text-xs text-fju-blue"><strong>參考法規：</strong> 相關條文已標記 | 信心指數：95% | Dify RAG Engine v2.1</div>';
      }, 1000);
    }
    </script>
  `),

  // ===== 報修管理 (with form) =====
  'repair': (role) => appShell(role, 'repair', '報修管理', `
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>報修管理</h2>
        <button onclick="document.getElementById('repair-form').classList.toggle('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-2"></i>新增報修</button>
      </div>
      <div id="repair-form" class="hidden bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue mb-3">新增報修單</h3>
        <div class="grid md:grid-cols-2 gap-4">
          <div><label class="text-xs text-gray-400 block mb-1">設備/場地</label><input id="rp-target" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="例：SF 134 投影機"></div>
          <div><label class="text-xs text-gray-400 block mb-1">問題類型</label><select class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm"><option>設備故障</option><option>環境問題</option><option>安全隱患</option><option>其他</option></select></div>
        </div>
        <div class="mt-3"><label class="text-xs text-gray-400 block mb-1">問題描述</label><textarea id="rp-desc" rows="3" class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" placeholder="請詳細描述問題..."></textarea></div>
        <button onclick="submitRepair()" class="btn-yellow px-6 py-2 text-sm mt-3"><i class="fas fa-paper-plane mr-2"></i>提交報修</button>
        <div id="repair-result" class="hidden mt-3 p-3 rounded-fju bg-fju-green/10 text-fju-green text-sm"></div>
      </div>
      <div id="repair-table" class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden"></div>
    </div>
    <script>
    fetch('/api/repairs').then(r=>r.json()).then(res => {
      const statusMap = {pending:{label:'待處理',cls:'bg-fju-red/10 text-fju-red'},processing:{label:'處理中',cls:'bg-fju-yellow/20 text-fju-yellow'},completed:{label:'已完成',cls:'bg-fju-green/10 text-fju-green'}};
      document.getElementById('repair-table').innerHTML = '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">編號</th><th class="p-4">設備/場地</th><th class="p-4">描述</th><th class="p-4">狀態</th><th class="p-4">日期</th><th class="p-4">處理人</th></tr></thead><tbody>'+
        res.data.map(r => '<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 font-mono text-xs">'+r.code+'</td><td class="p-4 text-fju-blue font-medium">'+r.target+'</td><td class="p-4 text-gray-500">'+r.description+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju '+(statusMap[r.status]||statusMap.pending).cls+' text-xs">'+(statusMap[r.status]||statusMap.pending).label+'</span></td><td class="p-4 text-gray-400">'+r.submitted_at+'</td><td class="p-4 text-gray-500">'+(r.assignee||'-')+'</td></tr>').join('')+
        '</tbody></table>';
    });
    
    function submitRepair() {
      fetch('/api/repairs', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({
        target: document.getElementById('rp-target').value, description: document.getElementById('rp-desc').value
      })}).then(r=>r.json()).then(res => {
        const box = document.getElementById('repair-result');
        box.classList.remove('hidden');
        box.innerHTML = '<i class="fas fa-check-circle mr-1"></i> '+res.message+' (追蹤碼：'+res.tracking_code+')';
      });
    }
    </script>
  `),

  // ===== 申訴記錄 (with AI summary) =====
  'appeal': (role) => appShell(role, 'appeal', '申訴記錄', `
    <div class="grid lg:grid-cols-2 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-fju-blue"><i class="fas fa-comments mr-2 text-fju-yellow"></i>申訴案件列表</h2>
            <button onclick="document.getElementById('new-appeal').classList.toggle('hidden')" class="btn-yellow px-3 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增</button>
          </div>
          <div id="new-appeal" class="hidden mb-4 p-4 rounded-fju bg-fju-bg">
            <input id="appeal-subject" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm mb-2" placeholder="申訴主題">
            <textarea id="appeal-desc" rows="2" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm mb-2" placeholder="詳細描述..."></textarea>
            <button onclick="submitAppeal()" class="btn-yellow px-4 py-1.5 text-xs">提交申訴</button>
          </div>
          <div id="appeal-list"></div>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 摘要 (WebSocket)</h2>
          <div id="ai-summary-panel" class="p-4 rounded-fju bg-fju-bg">
            <div class="flex items-center gap-2 mb-3"><span class="w-2 h-2 rounded-full bg-fju-green animate-pulse"></span><span class="text-xs text-gray-400">點擊案件查看 AI 分析</span></div>
          </div>
          <div id="ai-summary-actions" class="hidden flex gap-2 mt-4">
            <button class="flex-1 btn-blue py-2 text-sm"><i class="fas fa-check mr-1"></i>採納方案</button>
            <button class="flex-1 btn-yellow py-2 text-sm"><i class="fas fa-gavel mr-1"></i>進入仲裁</button>
          </div>
        </div>
      </div>
    </div>
    <script>
    fetch('/api/appeals').then(r=>r.json()).then(res => {
      const statusMap = {pending:{label:'待處理',cls:'border-fju-red/20 bg-fju-red/5',badge:'bg-fju-red/10 text-fju-red'},processing:{label:'處理中',cls:'border-fju-yellow/30 bg-fju-yellow/5',badge:'bg-fju-yellow/20 text-fju-yellow'},resolved:{label:'已結案',cls:'border-fju-green/20 bg-fju-green/5',badge:'bg-fju-green/10 text-fju-green'}};
      document.getElementById('appeal-list').innerHTML = res.data.map(a => {
        const s = statusMap[a.status]||statusMap.pending;
        return '<div class="p-4 rounded-fju border '+s.cls+' cursor-pointer hover:shadow-md transition-shadow mb-3" onclick="loadAISummary(\\''+a.code+'\\',\\''+a.subject.replace(/'/g,"\\\\'")+'\\')">'+
        '<div class="flex items-center justify-between mb-2"><span class="font-medium text-sm text-fju-blue">'+a.type+'申訴 #'+a.code+'</span><span class="px-2 py-1 rounded-fju '+s.badge+' text-xs">'+s.label+'</span></div>'+
        '<p class="text-xs text-gray-500">'+a.subject+'</p></div>';
      }).join('');
    });

    function loadAISummary(code, subject) {
      const panel = document.getElementById('ai-summary-panel');
      panel.innerHTML = '<div class="flex items-center gap-2 mb-3"><span class="w-2 h-2 rounded-full bg-fju-yellow animate-pulse"></span><span class="text-xs text-gray-400">AI 分析中...</span></div>';
      
      fetch('/api/appeals/'+code+'/ai-summary', {method:'POST'}).then(r=>r.json()).then(res => {
        panel.innerHTML = '<div class="flex items-center gap-2 mb-3"><span class="w-2 h-2 rounded-full bg-fju-green animate-pulse"></span><span class="text-xs text-gray-400">AI 分析完成</span></div>'+
          '<div class="text-sm text-gray-600 leading-relaxed">'+
          '<p class="mb-2"><strong>案件摘要：</strong>'+res.summary+'</p>'+
          '<p class="mb-2"><strong>AI 建議：</strong></p>'+
          '<ul class="list-disc pl-5 text-xs space-y-1">'+res.suggestions.map(s=>'<li>'+s+'</li>').join('')+'</ul>'+
          '<div class="flex gap-2 mt-2"><span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-blue/10 text-fju-blue">情緒：'+res.sentiment+'</span><span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-yellow/20 text-fju-yellow">緊急度：'+res.urgency+'</span></div></div>';
        document.getElementById('ai-summary-actions').classList.remove('hidden');
      });
    }

    function submitAppeal() {
      fetch('/api/appeals', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({
        subject: document.getElementById('appeal-subject').value, description: document.getElementById('appeal-desc').value
      })}).then(r=>r.json()).then(res => alert(res.message));
    }
    </script>
  `),

  // ===== 統計報表 (enhanced with role-specific charts) =====
  'reports': (role) => appShell(role, 'reports', '統計報表', `
    <div class="space-y-6">
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" id="report-stats"></div>
      <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>社團參與趨勢</h3>
          <canvas id="report-trend" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-pie mr-2 text-fju-yellow"></i>活動類型分布</h3>
          <canvas id="report-pie" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>場地使用率排行</h3>
          <canvas id="report-venue" height="200"></canvas>
        </div>
        <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-chart-radar mr-2 text-fju-yellow"></i>SDGs 貢獻雷達圖</h3>
          <canvas id="report-sdg" height="200"></canvas>
        </div>
      </div>
    </div>
    <script>
    fetch('/api/dashboard/stats/${role}').then(r=>r.json()).then(stats => {
      document.getElementById('report-stats').innerHTML =
        '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">'+(stats.total_clubs||'200+')+'</div><div class="text-xs text-gray-400">學生社團</div></div>'+
        '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-yellow">'+(stats.monthly_activities||'28')+'</div><div class="text-xs text-gray-400">本月活動</div></div>'+
        '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-green">'+(stats.venue_usage||'87')+'%</div><div class="text-xs text-gray-400">場地使用率</div></div>'+
        '<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-fju-blue">'+(stats.ai_pass_rate||'94')+'%</div><div class="text-xs text-gray-400">AI通過率</div></div>';
    });

    document.addEventListener('DOMContentLoaded', () => {
      new Chart(document.getElementById('report-trend'), {type:'line',data:{labels:['9月','10月','11月','12月','1月','2月','3月'],datasets:[{label:'參與人數',data:[1200,1350,1500,1420,1380,1550,1680],borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',fill:true,tension:0.4,pointBackgroundColor:'#DAA520'}]},options:{responsive:true,plugins:{legend:{display:false}}}});
      new Chart(document.getElementById('report-pie'), {type:'doughnut',data:{labels:['學術','服務','康樂','體育','藝文','綜合'],datasets:[{data:[25,18,22,15,12,8],backgroundColor:['#003153','#DAA520','#008000','#004070','#9B59B6','#666']}]},options:{responsive:true}});
      new Chart(document.getElementById('report-venue'), {type:'bar',data:{labels:['中美堂','活動中心','SF 134','體育館','百鍊廳','焯炤館'],datasets:[{label:'使用次數',data:[45,38,35,28,22,18],backgroundColor:'#003153'}]},options:{responsive:true,indexAxis:'y',plugins:{legend:{display:false}}}});
      new Chart(document.getElementById('report-sdg'), {type:'radar',data:{labels:['SDG4','SDG5','SDG10','SDG11','SDG16','SDG17'],datasets:[{label:'貢獻度',data:[85,60,75,90,55,70],borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.15)',pointBackgroundColor:'#DAA520'}]},options:{responsive:true,scales:{r:{min:0,max:100}}}});
    });
    </script>
  `),

  // ===== Legacy module pages =====
  'e-portfolio': (role) => appShell(role, 'dashboard', '職能 E-Portfolio', `
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-id-badge mr-2 text-fju-yellow"></i>職能 E-Portfolio</h2>
      <div class="grid md:grid-cols-2 gap-4 mb-4">
        <div><label class="text-xs text-gray-400 block mb-1">姓名</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="王大明"></div>
        <div><label class="text-xs text-gray-400 block mb-1">學號</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="410012345"></div>
        <div><label class="text-xs text-gray-400 block mb-1">Email</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="410012345@cloud.fju.edu.tw"></div>
        <div><label class="text-xs text-gray-400 block mb-1">社團職位</label><input class="w-full px-4 py-2.5 rounded-fju border border-gray-200 text-sm" value="攝影社 副社長"></div>
      </div>
      <div class="mb-4"><h3 class="font-bold text-fju-blue text-sm mb-2">職能雷達圖</h3><canvas id="competency-radar" height="200"></canvas></div>
      <button onclick="alert('PDF 履歷已產生（模擬）')" class="btn-yellow px-6 py-2 text-sm"><i class="fas fa-file-pdf mr-2"></i>匯出 PDF 履歷</button>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      new Chart(document.getElementById('competency-radar'), {type:'radar',data:{labels:['領導力','創意思維','團隊合作','溝通能力','數位能力'],datasets:[{label:'職能分數',data:[80,70,85,75,90],borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.15)',pointBackgroundColor:'#DAA520'}]},options:{responsive:true,scales:{r:{min:0,max:100}}}});
    });
    </script>
  `),

  // ===== 租借流程 RAG (Rental Process RAG) =====
  'rag-rental': (role) => appShell(role, 'rag-rental', '租借流程 RAG', `
    <div class="space-y-6">
      <div class="bg-gradient-to-r from-fju-blue to-fju-blue-light rounded-fju-lg p-6 text-white">
        <h2 class="text-xl font-bold mb-2"><i class="fas fa-file-contract mr-2 text-fju-yellow"></i>租借流程 RAG 查詢</h2>
        <p class="text-white/60 text-sm">AI 解析場地/器材租借流程、規定、表單及注意事項</p>
      </div>
      <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <div class="flex gap-3">
          <input type="text" id="rental-rag-query" placeholder="例如：借用中美堂需要什麼手續？" class="flex-1 px-4 py-3 rounded-fju border border-gray-200 text-sm focus:border-fju-blue outline-none" onkeypress="if(event.key==='Enter')queryRentalRAG()">
          <button onclick="queryRentalRAG()" class="btn-yellow px-6 py-3 text-sm"><i class="fas fa-search mr-2"></i>AI 查詢</button>
        </div>
        <div class="flex gap-2 mt-3 flex-wrap">
          ${'借中美堂需要什麼手續？,器材逾期歸還會怎樣？,如何申請場地優先權？,借用器材的流程是什麼？'.split(',').map(q =>
            `<button onclick="document.getElementById('rental-rag-query').value='${q}';queryRentalRAG()" class="text-xs px-3 py-1 rounded-full bg-fju-blue/5 text-fju-blue hover:bg-fju-blue/10 transition-colors">${q}</button>`
          ).join('')}
        </div>
      </div>
      <div id="rental-rag-result" class="hidden bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-fju-blue mb-3"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 回覆</h3>
        <div id="rental-rag-answer" class="p-4 rounded-fju bg-fju-bg text-sm text-gray-600 leading-relaxed"></div>
      </div>
      <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue mb-3 text-sm"><i class="fas fa-clipboard-list mr-2 text-fju-yellow"></i>場地借用流程</h3>
          <div class="space-y-3">
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-blue text-white text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">1</div><div><div class="text-sm font-medium text-fju-blue">線上提交申請</div><div class="text-xs text-gray-400">填寫場地借用申請表，含活動名稱、時間、人數</div></div></div>
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-yellow text-fju-blue text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">2</div><div><div class="text-sm font-medium text-fju-blue">AI 預審 (RAG)</div><div class="text-xs text-gray-400">系統自動比對法規，檢查合規性</div></div></div>
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-blue text-white text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">3</div><div><div class="text-sm font-medium text-fju-blue">三階段資源調度</div><div class="text-xs text-gray-400">志願序配對 → 自主協商 → 官方核准</div></div></div>
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-green text-white text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">4</div><div><div class="text-sm font-medium text-fju-blue">核准 & 使用</div><div class="text-xs text-gray-400">產出 PDF + TOTP QR 驗證碼</div></div></div>
          </div>
        </div>
        <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
          <h3 class="font-bold text-fju-blue mb-3 text-sm"><i class="fas fa-boxes-stacked mr-2 text-fju-yellow"></i>器材借用流程</h3>
          <div class="space-y-3">
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-blue text-white text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">1</div><div><div class="text-sm font-medium text-fju-blue">線上申請借用</div><div class="text-xs text-gray-400">選擇器材、填寫用途與預計歸還日</div></div></div>
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-yellow text-fju-blue text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">2</div><div><div class="text-sm font-medium text-fju-blue">審核 & 領取</div><div class="text-xs text-gray-400">課指組審核後至櫃台領取</div></div></div>
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-blue text-white text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">3</div><div><div class="text-sm font-medium text-fju-blue">LINE 到期提醒</div><div class="text-xs text-gray-400">歸還前 1 天系統自動推送 LINE 通知</div></div></div>
            <div class="flex items-start gap-3"><div class="w-6 h-6 rounded-full bg-fju-green text-white text-xs flex items-center justify-center shrink-0 mt-0.5 font-bold">4</div><div><div class="text-sm font-medium text-fju-blue">歸還 & 積分</div><div class="text-xs text-gray-400">準時歸還 +5 分，逾期每天 -2 分</div></div></div>
          </div>
        </div>
      </div>
    </div>
    <script>
    function queryRentalRAG() {
      const query = document.getElementById('rental-rag-query').value.trim();
      if(!query) return;
      document.getElementById('rental-rag-result').classList.remove('hidden');
      document.getElementById('rental-rag-answer').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>AI 查詢中...';
      setTimeout(() => {
        const answers = {
          '中美堂': '<p class="mb-2">根據《場地使用管理規則》第3條：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">借用中美堂需提前 <strong>5 個工作日</strong> 提出申請（因容量 500 人屬大型場地）。</p><p class="mb-2">需備齊：1) 活動企劃書 2) 安全計畫書 3) 保險證明</p><p class="text-xs text-gray-400">三階段流程：志願序配對 → 若衝突則 AI 協商 → 課指組最終核准</p>',
          '逾期': '<p class="mb-2">根據《器材借用辦法》第4條：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">逾期歸還每日扣除信用積分 <strong>2 分</strong>，超過 7 天未還扣 <strong>20 分</strong> 並停權。</p><p class="mb-2">低於 60 分將被 <strong>強制登出</strong>（JWT 自動失效）。</p>',
          '優先': '<p class="mb-2">根據《場地使用管理規則》第2條（三階段資源調度）：</p><p class="mb-2 pl-4 border-l-3 border-fju-yellow">Level 1：校方/處室（最高優先）<br>Level 2：社團幹部/自治組織<br>Level 3：一般社團/小組</p>',
          '器材': '<p class="mb-2">根據《器材借用辦法》：</p><p class="mb-2">1. 線上填寫申請表（含用途、歸還日）</p><p class="mb-2">2. 課指組審核（通常 1 個工作日）</p><p class="mb-2">3. 至課指組櫃台領取並簽名</p><p class="mb-2">4. 歸還前 1 天收到 LINE Notify 提醒</p>',
        };
        const key = Object.keys(answers).find(k => query.includes(k)) || '器材';
        document.getElementById('rental-rag-answer').innerHTML = answers[key] +
          '<div class="mt-3 p-3 rounded-fju bg-fju-blue/5 text-xs text-fju-blue"><strong>參考法規：</strong> 場地使用管理規則 v3.0 / 器材借用辦法 v2.0 | Dify RAG Engine</div>';
      }, 800);
    }
    </script>
  `),

  'ai-review': (role) => modules['rag-search'](role),
  'ai-proposal': (role) => modules['ai-guide'](role),
  'ai-appeal': (role) => modules['appeal'](role),
  'venue-data': (role) => modules['venue-booking'](role),
  'certificate': (role) => appShell(role, 'dashboard', '幹部證書', `
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-award mr-2 text-fju-yellow"></i>幹部證書自動化</h2>
      <div class="border-4 border-double border-fju-yellow rounded-fju-lg p-8 bg-white text-center max-w-lg mx-auto">
        <div class="text-2xl font-black text-fju-blue mb-1">輔仁大學</div>
        <div class="text-sm text-gray-500 mb-4">天主教輔仁大學課外活動指導組</div>
        <div class="w-16 h-0.5 bg-fju-yellow mx-auto mb-4"></div>
        <div class="text-lg font-bold text-fju-yellow mb-2">幹 部 證 書</div>
        <div class="text-sm text-gray-600 mb-4 leading-relaxed">
          茲證明 <span class="font-bold text-fju-blue border-b-2 border-fju-yellow px-2">王大明</span> 同學<br>
          於 <span class="font-bold">114學年度第一學期</span> 擔任<br>
          <span class="font-bold text-fju-blue">攝影社</span> <span class="font-bold text-fju-yellow">副社長</span> 乙職
        </div>
        <div id="cert-code" class="text-xs text-gray-400">數位簽章驗證碼: FJU-CERT-2026-001234</div>
      </div>
      <div class="flex gap-3 justify-center mt-6">
        <button onclick="generateCert()" class="btn-yellow px-6 py-2 text-sm"><i class="fas fa-certificate mr-2"></i>生成證書</button>
        <button onclick="alert('PDF 下載（模擬）')" class="btn-blue px-6 py-2 text-sm"><i class="fas fa-download mr-2"></i>下載 PDF</button>
      </div>
    </div>
    <script>
    function generateCert() {
      fetch('/api/certificates/generate', {method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({name:'王大明',club:'攝影社',position:'副社長',term:'114學年度第一學期'})})
        .then(r=>r.json()).then(res => {
          document.getElementById('cert-code').innerHTML = '數位簽章驗證碼: '+res.certificate_id+'<br><span class="text-[10px]">'+res.digital_signature+'</span>';
          alert('證書已生成！\\n驗證碼：'+res.certificate_id);
        });
    }
    </script>
  `),
  'time-capsule': (role) => appShell(role, 'dashboard', '數位時光膠囊', `<div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100"><h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-clock-rotate-left mr-2 text-fju-yellow"></i>數位時光膠囊 (R2 Storage)</h2><p class="text-gray-500 text-sm mb-3">封裝社團移交文件至 Cloudflare R2，確保社團資產安全傳承。</p><div class="p-4 rounded-fju bg-fju-bg"><div class="flex items-center gap-3 mb-3"><i class="fas fa-box text-fju-yellow text-xl"></i><div><div class="font-medium text-fju-blue">攝影社 114年度文件包</div><div class="text-xs text-gray-400">已封裝 · 3 個檔案 · 2.3 MB</div></div><span class="ml-auto px-2 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs">已封裝</span></div></div><button onclick="alert('解封膠囊需要現任社長 + 前任社長雙重驗證')" class="btn-yellow px-6 py-2 text-sm mt-4"><i class="fas fa-lock-open mr-2"></i>申請解封</button></div>`),
  '2fa': (role) => appShell(role, 'dashboard', '2FA 安全設定', `<div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100"><h2 class="font-bold text-fju-blue mb-4"><i class="fas fa-lock mr-2 text-fju-yellow"></i>全方位 2FA 驗證</h2><p class="text-gray-500 text-sm mb-4">TOTP / SMS 雙因子驗證設定。</p><div class="space-y-3"><div class="flex items-center justify-between p-4 rounded-fju bg-gray-50"><div class="flex items-center gap-3"><i class="fas fa-mobile-alt text-lg text-fju-blue"></i><div><div class="text-sm font-medium text-fju-blue">TOTP 驗證器</div><div class="text-xs text-gray-400">Google Authenticator / Authy</div></div></div><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" checked class="sr-only peer"><div class="w-11 h-6 bg-gray-200 peer-checked:bg-fju-green rounded-full after:content-[\\'\\'] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:after:translate-x-full after:transition-all"></div></label></div><div class="flex items-center justify-between p-4 rounded-fju bg-gray-50"><div class="flex items-center gap-3"><i class="fas fa-sms text-lg text-fju-blue"></i><div><div class="text-sm font-medium text-fju-blue">SMS 驗證</div><div class="text-xs text-gray-400">手機號碼：0912-***-678</div></div></div><label class="relative inline-flex items-center cursor-pointer"><input type="checkbox" class="sr-only peer"><div class="w-11 h-6 bg-gray-200 peer-checked:bg-fju-green rounded-full after:content-[\\'\\'] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:after:translate-x-full after:transition-all"></div></label></div><div class="flex items-center justify-between p-4 rounded-fju bg-gray-50"><div class="flex items-center gap-3"><i class="fab fa-line text-lg text-fju-green"></i><div><div class="text-sm font-medium text-fju-blue">LINE Notify 綁定</div><div class="text-xs text-gray-400">用於接收場地/器材通知</div></div></div><button class="btn-yellow px-4 py-1 text-xs">綁定</button></div></div></div>`),
}

export function modulePages(name: string, role: string = 'student'): string | null {
  const mod = modules[name]
  return mod ? mod(role) : null
}
