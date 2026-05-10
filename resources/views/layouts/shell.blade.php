@extends('layouts.app')

@php
// NEW ROLE STRUCTURE per prompt-6:
// 1. 課指組 (admin) - full control
// 2. 借用者 (officer/professor) - can book venues/equipment (社團幹部/學生自治組織, 處室職員, 教授)
// 3. 學生/社員 (student) - view only
$roleNames = ['admin'=>'課指組','officer'=>'借用者（社團幹部）','professor'=>'借用者（教授）','staff'=>'借用者（處室職員）','student'=>'一般學生/社員'];
$roleIcons = ['admin'=>'fas fa-user-tie','officer'=>'fas fa-user-shield','professor'=>'fas fa-chalkboard-teacher','staff'=>'fas fa-user-cog','student'=>'fas fa-user-graduate'];
$role = $role ?? 'student';
if (!isset($roleNames[$role])) $role = 'student';
$activePage = $activePage ?? 'dashboard';

// Sidebar structure per prompt-6: Dashboard, 活動申請, 場地預約, 設備借用
$roleSidebar = [
  'admin' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'activity-application','label'=>'活動申請','icon'=>'fas fa-file-alt','href'=>"/module/activity-application?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽 & 法規查詢','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
    ]],
    ['title'=>'管理工具','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
      ['id'=>'user-management','label'=>'使用者管理','icon'=>'fas fa-users-cog','href'=>"/module/user-management?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'personal-center','label'=>'個人中心','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'certificate','label'=>'數位證書','icon'=>'fas fa-award','href'=>"/module/certificate?role={$role}"],
    ]],
  ],
  'officer' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'activity-application','label'=>'活動申請','icon'=>'fas fa-file-alt','href'=>"/module/activity-application?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽 & 法規查詢','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'personal-center','label'=>'個人中心','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
    ]],
  ],
  'professor' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'activity-application','label'=>'活動申請','icon'=>'fas fa-file-alt','href'=>"/module/activity-application?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽 & 法規查詢','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'personal-center','label'=>'個人中心','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
    ]],
  ],
  'staff' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'activity-application','label'=>'活動申請','icon'=>'fas fa-file-alt','href'=>"/module/activity-application?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽 & 法規查詢','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'personal-center','label'=>'個人中心','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
    ]],
  ],
  'student' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約（檢視）','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用（檢視）','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
    ]],
    ['title'=>'瀏覽','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'ai-overview','label'=>'AI 資訊概覽 & 法規查詢','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'personal-center','label'=>'個人中心','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'certificate','label'=>'數位證書','icon'=>'fas fa-certificate','href'=>"/module/certificate?role={$role}"],
    ]],
  ],
  'it' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽 & 法規查詢','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
    ]],
    ['title'=>'系統管理','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
      ['id'=>'2fa','label'=>'雙因素驗證','icon'=>'fas fa-shield-alt','href'=>"/module/2fa?role={$role}"],
    ]],
  ],
];
$sidebarSections = $roleSidebar[$role] ?? $roleSidebar['student'];
@endphp

@section('body')
<div class="flex flex-col min-h-screen">
  {{-- TOP HEADER (White) --}}
  <div class="bg-white border-b border-gray-200 px-6 py-2">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="https://www.fju.edu.tw/images/ci/fujen_ci.jpg" alt="輔仁大學校徽" class="w-10 h-10 rounded-full object-cover shadow border border-gray-200" onerror="this.onerror=null;this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><circle cx=%2220%22 cy=%2220%22 r=%2220%22 fill=%22%23003153%22/><text x=%2220%22 y=%2226%22 text-anchor=%22middle%22 fill=%22white%22 font-size=%2216%22>輔</text></svg>'">
        <div>
          <div class="font-bold text-base leading-tight" style="background: linear-gradient(to left, #003153, #6699CC); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">天主教輔仁大學</div>
          <div class="text-fju-green text-xs font-medium">學務處 課外活動指導組</div>
        </div>
      </div>
      <div class="flex items-center gap-4 text-sm">
        <a href="/" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">首頁</a>
        <a href="https://www.fju.edu.tw" target="_blank" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">輔仁大學</a>
        <a href="#" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">ENGLISH</a>
        <div class="flex gap-1 ml-2 pl-3 border-l border-gray-200">
          @foreach(['admin'=>'課指組','officer'=>'借用者','professor'=>'教授','staff'=>'職員','student'=>'學生'] as $r => $label)
          <a href="/dashboard?role={{ $r }}" class="w-7 h-7 rounded-lg flex items-center justify-center text-xs transition-all {{ $r === $role ? 'bg-fju-yellow text-fju-blue' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}" title="{{ $label }}"><i class="{{ $roleIcons[$r] }}"></i></a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  {{-- BOTTOM HEADER (Deep Blue) --}}
  <div class="bg-fju-blue px-6 py-0">
    <div class="flex items-center justify-between">
      <nav class="flex items-center gap-1">
        @foreach([['認識課指組','#','fas fa-info-circle'],['學會．社團',"/module/club-info?role={$role}",'fas fa-users'],['場地/器材借用',"/module/venue-booking?role={$role}",'fas fa-key'],['重要訊息','#','fas fa-bullhorn'],['表單下載',"/module/form-download?role={$role}",'fas fa-download'],['常見問題',"/module/faq?role={$role}",'fas fa-question-circle']] as $nav)
        <a href="{{ $nav[1] }}" class="flex items-center gap-1.5 px-4 py-3 text-white/80 hover:text-fju-yellow hover:bg-white/10 transition-all text-sm font-medium rounded-t-lg"><i class="{{ $nav[2] }} text-xs"></i><span>{{ $nav[0] }}</span></a>
        @endforeach
      </nav>
      <div class="flex items-center gap-3">
        {{-- WORKING SEARCH BAR --}}
        <div class="relative" id="search-wrapper">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i>
          <input type="text" id="global-search-input" placeholder="搜尋活動、場地、社團..." class="bg-white/10 border border-white/15 rounded-fju pl-9 pr-4 py-1.5 text-white text-xs outline-none w-52 focus:w-72 focus:bg-white/20 focus:border-fju-yellow/50 transition-all" onkeyup="doGlobalSearch(event)" />
          <div id="search-results-dropdown" class="hidden absolute top-full left-0 right-0 mt-1 bg-white rounded-fju shadow-2xl border border-gray-200 z-[200] max-h-80 overflow-y-auto"></div>
        </div>
        {{-- NOTIFICATION BELL --}}
        <div class="relative" id="notif-wrapper">
          <button onclick="toggleNotifPanel()" class="relative w-8 h-8 rounded-fju bg-white/10 flex items-center justify-center text-white/70 hover:bg-white/15 hover:text-white transition-all" id="notif-bell">
            <i class="fas fa-bell text-sm"></i>
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-fju-red text-white text-[9px] font-bold flex items-center justify-center hidden" id="notif-badge">0</span>
          </button>
          <div id="notif-panel" class="hidden absolute right-0 top-full mt-2 w-96 bg-white rounded-fju-lg shadow-2xl border border-gray-200 z-[100] overflow-hidden">
            <div class="px-4 py-3 bg-fju-blue flex items-center justify-between">
              <span class="text-white font-bold text-sm"><i class="fas fa-bell mr-2 text-fju-yellow"></i>系統通知</span>
              <button onclick="markAllRead()" class="text-fju-yellow text-xs hover:underline">全部標為已讀</button>
            </div>
            <div id="notif-list" class="max-h-80 overflow-y-auto">
              <div class="p-6 text-center text-gray-400 text-sm"><i class="fas fa-bell-slash mr-1"></i>暫無通知</div>
            </div>
            <div class="px-4 py-2 border-t border-gray-100 text-center">
              <a href="#" class="text-fju-blue text-xs hover:text-fju-yellow">查看所有通知</a>
            </div>
          </div>
        </div>
        {{-- USER AVATAR → links to 個人中心 (E-Portfolio by role) --}}
        <div class="flex items-center gap-2 pl-3 border-l border-white/15">
          <a href="/module/e-portfolio?role={{ $role }}" class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue text-xs font-bold hover:ring-2 hover:ring-fju-yellow/50 transition-all" title="前往個人中心"><i class="{{ $roleIcons[$role] }}"></i></a>
          <div class="hidden lg:block"><div class="text-white text-xs font-medium leading-tight">Demo User</div><div class="text-white/40 text-[10px]">{{ $roleNames[$role] }}</div></div>
          <a href="/login" class="text-white/40 hover:text-fju-red transition-colors ml-1" title="登出"><i class="fas fa-sign-out-alt text-xs"></i></a>
        </div>
      </div>
    </div>
  </div>

  {{-- MIDDLE: SIDEBAR + MAIN --}}
  <div class="flex flex-1 overflow-hidden" style="height: calc(100vh - 160px);">
    {{-- SIDEBAR — light theme --}}
    <aside class="w-64 min-w-[256px] bg-gray-50 border-r border-gray-200 flex flex-col overflow-y-auto" style="position: sticky; top: 0;">
      {{-- Role Badge --}}
      <div class="px-4 pt-4 pb-2">
        <div class="flex items-center gap-2 px-3 py-2 rounded-fju bg-fju-blue/10 border border-fju-blue/20">
          <i class="{{ $roleIcons[$role] }} text-fju-blue text-sm"></i>
          <span class="text-xs font-bold text-fju-blue">{{ $roleNames[$role] }}</span>
        </div>
      </div>
      <nav class="flex-1 pb-4">
        @foreach($sidebarSections as $section)
        <div class="mt-3 first:mt-0">
          <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $section['title'] }}</div>
          @foreach($section['items'] as $item)
          <a href="{{ $item['href'] }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-fju text-sm transition-all duration-200 {{ $item['id'] === $activePage ? 'bg-fju-blue text-white font-bold shadow-sm' : 'text-gray-600 hover:bg-fju-blue/5 hover:text-fju-blue' }}">
            <i class="{{ $item['icon'] }} w-5 text-center text-sm {{ $item['id'] === $activePage ? 'text-fju-yellow' : '' }}"></i>
            <span>{{ $item['label'] }}</span>
          </a>
          @endforeach
        </div>
        @endforeach
      </nav>
    </aside>
    <main class="flex-1 bg-fju-bg overflow-y-auto p-6">@yield('content')</main>
  </div>

  {{-- FOOTER --}}
  @include('partials.footer')
</div>
{{-- === GLOBAL SEARCH SCRIPT === --}}
<script>
const searchIndex = [
  {title:'儀表板', url:'/dashboard?role={{ $role }}', cat:'頁面', icon:'fa-tachometer-alt'},
  {title:'活動申請', url:'/module/calendar?role={{ $role }}', cat:'頁面', icon:'fa-calendar-check'},
  {title:'場地預約', url:'/module/venue-booking?role={{ $role }}', cat:'頁面', icon:'fa-map-marker-alt'},
  {title:'設備借用', url:'/module/equipment?role={{ $role }}', cat:'頁面', icon:'fa-boxes-stacked'},
  {title:'社團資訊', url:'/module/club-info?role={{ $role }}', cat:'頁面', icon:'fa-users'},
  {title:'AI 資訊概覽 & 法規查詢', url:'/module/ai-overview?role={{ $role }}', cat:'頁面', icon:'fa-brain'},
  {title:'報修管理', url:'/module/repair?role={{ $role }}', cat:'頁面', icon:'fa-wrench'},
  {title:'申訴紀錄', url:'/module/appeal?role={{ $role }}', cat:'頁面', icon:'fa-comments'},
  {title:'個人中心', url:'/module/e-portfolio?role={{ $role }}', cat:'頁面', icon:'fa-id-badge'},
  {title:'數位證書', url:'/module/certificate?role={{ $role }}', cat:'頁面', icon:'fa-award'},
  {title:'統計報表', url:'/module/reports?role={{ $role }}', cat:'頁面', icon:'fa-chart-bar'},
  {title:'使用者管理', url:'/module/user-management?role={{ $role }}', cat:'頁面', icon:'fa-users-cog'},
  {title:'常見問題 FAQ', url:'/module/faq?role={{ $role }}', cat:'頁面', icon:'fa-question-circle'},
  {title:'淨心堂', url:'/module/venue-booking?role={{ $role }}', cat:'場地', icon:'fa-building'},
  {title:'中美堂', url:'/module/venue-booking?role={{ $role }}', cat:'場地', icon:'fa-building'},
  {title:'百鍊廳', url:'/module/venue-booking?role={{ $role }}', cat:'場地', icon:'fa-building'},
  {title:'SF 134 教室', url:'/module/venue-booking?role={{ $role }}', cat:'場地', icon:'fa-building'},
  {title:'攝影社', url:'/module/club-info?role={{ $role }}', cat:'社團', icon:'fa-camera'},
  {title:'吉他社', url:'/module/club-info?role={{ $role }}', cat:'社團', icon:'fa-guitar'},
  {title:'街舞社', url:'/module/club-info?role={{ $role }}', cat:'社團', icon:'fa-music'},
  {title:'AI 預審', url:'/module/ai-overview?role={{ $role }}', cat:'AI 功能', icon:'fa-robot'},
  {title:'AI 企劃生成', url:'/module/ai-overview?role={{ $role }}', cat:'AI 功能', icon:'fa-wand-magic-sparkles'},
  {title:'校園活動安全管理辦法', url:'/module/ai-overview?role={{ $role }}', cat:'法規', icon:'fa-gavel'},
  {title:'場地管理規則', url:'/module/rag-search?role={{ $role }}', cat:'法規', icon:'fa-gavel'},
];
function doGlobalSearch(e){
  const q = document.getElementById('global-search-input').value.trim().toLowerCase();
  const dropdown = document.getElementById('search-results-dropdown');
  if(!q || q.length < 1){dropdown.classList.add('hidden');return}
  const results = searchIndex.filter(s => s.title.toLowerCase().includes(q) || s.cat.toLowerCase().includes(q));
  if(results.length === 0){
    dropdown.innerHTML = '<div class="p-4 text-center text-gray-400 text-sm"><i class="fas fa-search mr-1"></i>找不到「'+q+'」相關結果</div>';
  } else {
    dropdown.innerHTML = results.slice(0, 8).map(r =>
      `<a href="${r.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-fju-bg transition-colors border-b border-gray-50">
        <div class="w-8 h-8 rounded-fju bg-fju-blue/10 flex items-center justify-center shrink-0"><i class="fas ${r.icon} text-fju-blue text-xs"></i></div>
        <div class="flex-1 min-w-0"><div class="text-sm font-medium text-fju-blue truncate">${r.title}</div><div class="text-[10px] text-gray-400">${r.cat}</div></div>
        <i class="fas fa-chevron-right text-gray-300 text-xs"></i>
      </a>`).join('');
  }
  dropdown.classList.remove('hidden');
  if(e && e.key === 'Enter' && results.length > 0) window.location.href = results[0].url;
}
document.addEventListener('click',function(e){
  const w=document.getElementById('search-wrapper');
  if(w && !w.contains(e.target)){document.getElementById('search-results-dropdown')?.classList.add('hidden')}
});
</script>

{{-- Notification Panel Script (FIXED: proper error handling, stops loading on failure) --}}
<script>
function toggleNotifPanel(){
  const panel=document.getElementById('notif-panel');
  panel.classList.toggle('hidden');
  if(!panel.classList.contains('hidden')) loadNotifications();
}
document.addEventListener('click',function(e){
  const w=document.getElementById('notif-wrapper');
  if(w && !w.contains(e.target)){document.getElementById('notif-panel')?.classList.add('hidden')}
});
function loadNotifications(){
  const listEl=document.getElementById('notif-list');
  listEl.innerHTML='<div class="p-4 text-center text-gray-400 text-sm"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>';
  // Use paginated endpoint with 30-day limit
  fetch('/api/notifications/1?limit=20&days=30',{signal:AbortSignal.timeout(5000)})
    .then(r=>{if(!r.ok) throw new Error('API error');return r.json()})
    .then(res=>{
      const list=res.data||[];
      const unread=res.unread_count||0;
      const badge=document.getElementById('notif-badge');
      badge.textContent=unread;
      if(unread>0){badge.classList.remove('hidden')} else {badge.classList.add('hidden')}
      if(list.length===0){
        listEl.innerHTML='<div class="p-6 text-center text-gray-400 text-sm"><i class="fas fa-bell-slash mr-1"></i>暫無通知</div>';
        return;
      }
      listEl.innerHTML=list.map(n=>{
        const isUnread=!n.read&&!n.is_read;
        return `<div class="px-4 py-3 border-b border-gray-50 hover:bg-fju-bg/50 cursor-pointer transition-all ${isUnread?'bg-fju-yellow/5':''}" onclick="readNotif(${n.id})">
          <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full ${isUnread?'bg-fju-yellow/20':'bg-gray-100'} flex items-center justify-center shrink-0">
              <i class="fas ${n.channel==='email'?'fa-envelope':n.channel==='sms'?'fa-sms':'fa-bell'} ${isUnread?'text-fju-yellow':'text-gray-400'} text-xs"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold ${isUnread?'text-fju-blue':'text-gray-500'}">${n.title||'系統通知'}</span>
                ${isUnread?'<span class="w-2 h-2 rounded-full bg-fju-yellow shrink-0"></span>':''}
              </div>
              <p class="text-[11px] text-gray-500 mt-0.5 truncate">${n.message||''}</p>
              <span class="text-[10px] text-gray-400 mt-1">${n.created_at?new Date(n.created_at).toLocaleDateString('zh-TW'):''}</span>
            </div>
          </div>
        </div>`;
      }).join('');
    })
    .catch(()=>{
      // FIXED: Stop loading animation on failure
      listEl.innerHTML='<div class="p-6 text-center text-gray-400 text-sm"><i class="fas fa-bell-slash mr-1"></i>暫無通知</div>';
      document.getElementById('notif-badge').classList.add('hidden');
    });
}
function readNotif(id){
  fetch('/api/notifications/'+id+'/read',{method:'POST',headers:{'Accept':'application/json'}}).then(()=>loadNotifications()).catch(()=>{});
}
function markAllRead(){
  fetch('/api/notifications/1?limit=50',{signal:AbortSignal.timeout(3000)}).then(r=>r.json()).then(res=>{
    const promises=(res.data||[]).filter(n=>!n.read&&!n.is_read).map(n=>fetch('/api/notifications/'+n.id+'/read',{method:'POST',headers:{'Accept':'application/json'}}));
    Promise.all(promises).then(()=>loadNotifications());
  }).catch(()=>{
    document.getElementById('notif-list').innerHTML='<div class="p-6 text-center text-gray-400 text-sm"><i class="fas fa-bell-slash mr-1"></i>暫無通知</div>';
    document.getElementById('notif-badge').classList.add('hidden');
  });
}
// Initial badge load with error handling
document.addEventListener('DOMContentLoaded',function(){
  fetch('/api/notifications/1?limit=5',{signal:AbortSignal.timeout(3000)}).then(r=>{if(!r.ok)throw new Error();return r.json()}).then(res=>{
    const unread=res.unread_count||0;
    const badge=document.getElementById('notif-badge');
    badge.textContent=unread;
    if(unread>0) badge.classList.remove('hidden'); else badge.classList.add('hidden');
    _eqNotifBaseline=unread; // Task 2: set baseline after initial load
  }).catch(()=>{document.getElementById('notif-badge').classList.add('hidden')});
});

@if(in_array($role ?? 'student', ['admin', 'officer']))
// Task 2: Background polling for new equipment loan notifications (admin/officer only)
let _eqNotifBaseline = null;
setInterval(function(){
  fetch('/api/notifications/1?limit=10',{signal:AbortSignal.timeout(3000)})
    .then(r=>{if(!r.ok)throw new Error();return r.json()})
    .then(res=>{
      const unread=res.unread_count||0;
      const badge=document.getElementById('notif-badge');
      badge.textContent=unread;
      if(unread>0) badge.classList.remove('hidden'); else badge.classList.add('hidden');
      if(_eqNotifBaseline!==null && unread>_eqNotifBaseline){
        const newNotifs=(res.data||[]).filter(n=>!n.read&&!n.is_read);
        const eqNotif=newNotifs.find(n=>n.title&&n.title.includes('器材借用'));
        if(eqNotif) showEqLoanPopup(eqNotif.title,eqNotif.message);
      }
      _eqNotifBaseline=unread;
    }).catch(()=>{});
},30000);

function showEqLoanPopup(title,msg){
  const t=document.createElement('div');
  t.style.cssText='position:fixed;top:24px;right:24px;z-index:9999;max-width:320px;animation:slideIn .3s ease';
  t.innerHTML=`<div style="background:#fff;border-left:4px solid #eab308;border-radius:10px;box-shadow:0 8px 32px rgba(0,0,0,.18);padding:16px 18px;display:flex;gap:12px;align-items:flex-start;">
    <div style="width:40px;height:40px;border-radius:50%;background:#fef9c3;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
      <i class="fas fa-box" style="color:#a16207;font-size:16px;"></i>
    </div>
    <div style="flex:1;">
      <div style="font-weight:bold;color:#1e3a8a;font-size:13px;margin-bottom:3px;">${title}</div>
      <div style="font-size:11px;color:#64748b;line-height:1.4;">${msg}</div>
      <a href="/module/equipment?role={{ $role }}" style="display:inline-block;margin-top:6px;font-size:11px;color:#1e3a8a;font-weight:bold;text-decoration:underline;">前往審核 →</a>
    </div>
    <button onclick="this.closest('[style*=position]').remove()" style="background:none;border:none;color:#94a3b8;cursor:pointer;font-size:14px;line-height:1;padding:0;flex-shrink:0;">&times;</button>
  </div>`;
  document.body.appendChild(t);
  setTimeout(()=>t.remove(),10000);
}
@endif
</script>

<script>
// ─── Shared unit code list (UNIT_CODES) ──────────────────────────────────
const UNIT_CODES = [
  {code:'001',name:'學生會'},{code:'002',name:'學生議會'},{code:'002',name:'學生法庭'},
  {code:'-20',name:'輔大之聲'},{code:'112',name:'文學院學生代表會'},{code:'008',name:'國學會'},
  {code:'009',name:'歷史學會'},{code:'010',name:'哲學學會'},{code:'301',name:'社創學程學會'},
  {code:'108',name:'教育學院學生代表會'},{code:'011',name:'圖資學會'},{code:'014',name:'體育學會'},
  {code:'181',name:'領科學程學會'},{code:'113',name:'藝術學院學生代表會'},{code:'015',name:'音樂學會'},
  {code:'016',name:'應美學會'},{code:'105',name:'景觀學會'},{code:'110',name:'傳播學院學生代表會'},
  {code:'130',name:'影傳學會'},{code:'133',name:'新傳學會'},{code:'134',name:'廣告學會'},
  {code:'005',name:'外語學院學生代表會'},{code:'026',name:'英文學會'},{code:'027',name:'德語學會'},
  {code:'028',name:'法文學會'},{code:'029',name:'西文學會'},{code:'030',name:'日文學會'},
  {code:'040',name:'義文學會'},{code:'004',name:'理工學院學生代表會'},{code:'017',name:'數學學會'},
  {code:'018',name:'物理學會'},{code:'019',name:'化學學會'},{code:'020',name:'生科學會'},
  {code:'024',name:'電機學會'},{code:'025',name:'資工學會'},{code:'182',name:'醫資學程學會'},
  {code:'120',name:'民生學院學生代表會'},{code:'012',name:'兒家學會'},{code:'021',name:'餐旅學會'},
  {code:'022',name:'食科學會'},{code:'145',name:'營養學會'},{code:'187',name:'織品學院學生代表會'},
  {code:'023',name:'織品學會'},{code:'115',name:'社會科學院學生代表會'},{code:'031',name:'社會學會'},
  {code:'032',name:'社工學會'},{code:'033',name:'經濟學會'},{code:'121',name:'宗教學會'},
  {code:'013',name:'心理學會'},{code:'176',name:'天主教研修學士學位學程學會'},
  {code:'114',name:'醫學院學生代表會'},{code:'103',name:'公衛學會'},{code:'104',name:'護理學會'},
  {code:'122',name:'臨心學會'},{code:'102',name:'醫學學會'},{code:'135',name:'職治學會'},
  {code:'154',name:'呼治學會'},{code:'007',name:'法律學院學生代表會'},{code:'034',name:'法律學會'},
  {code:'111',name:'財法學會'},{code:'152',name:'學士後法律學會'},{code:'006',name:'管理學院學生代表會'},
  {code:'035',name:'企管學會'},{code:'036',name:'會計學會'},{code:'037',name:'統資學會'},
  {code:'038',name:'金融國企學會'},{code:'039',name:'資管學會'},{code:'202',name:'進修部學生代表會'},
  {code:'203',name:'進-國學會'},{code:'204',name:'進-歷史學會'},{code:'205',name:'進-哲學學會'},
  {code:'206',name:'進-大傳學會'},{code:'207',name:'進-資創學程學會'},{code:'208',name:'進-英文學會'},
  {code:'209',name:'進-日文學會'},{code:'211',name:'進-法律學會'},{code:'212',name:'進-經濟學會'},
  {code:'225',name:'進-餐旅學會'},{code:'226',name:'進-應美學會'},{code:'227',name:'進-宗教學會'},
  {code:'228',name:'進-文創學程學會'},{code:'230',name:'進-運管學程學會'},{code:'231',name:'進-商管學程學會'},
  {code:'232',name:'進-軟創學程學會'},{code:'233',name:'進-長照學程學會'},{code:'234',name:'進-文服學程學會'},
  {code:'235',name:'進-醫健學程學會'},{code:'236',name:'進-室設學程學會'},{code:'237',name:'進-服飾學程學會'},
  {code:'048',name:'黑水溝社'},{code:'049',name:'健言社'},{code:'050',name:'大千社'},
  {code:'051',name:'天文社'},{code:'052',name:'綠野社'},{code:'053',name:'中華醫藥研習社'},
  {code:'054',name:'國際經濟商管學生會'},{code:'056',name:'占星塔羅社'},{code:'058',name:'信望愛社'},
  {code:'140',name:'學園團契社'},{code:'141',name:'禪學社'},{code:'142',name:'聖經研究社'},
  {code:'143',name:'國際英語言講社'},{code:'155',name:'模擬聯合國社'},{code:'159',name:'教育學程學會'},
  {code:'161',name:'福智青年社'},{code:'174',name:'性別研究社'},{code:'177',name:'音樂遊戲研究社'},
  {code:'229',name:'光鹽社'},{code:'191',name:'永續影響力大使社'},{code:'192',name:'創新創業社'},
  {code:'042-1',name:'橋生聯誼會'},{code:'042-2',name:'港澳同學會'},{code:'042-3',name:'馬來西亞同學會'},
  {code:'042-4',name:'印尼同學會'},{code:'043-1',name:'高中校友聯合總會'},{code:'043-2',name:'台北校友會'},
  {code:'043-3',name:'桃園校友會'},{code:'043-4',name:'竹苗校友會'},{code:'043-5',name:'台中校友會'},
  {code:'043-6',name:'彰化校友會'},{code:'043-7',name:'嘉雲校友會'},{code:'043-8',name:'台南校友會'},
  {code:'043-9',name:'高雄校友會'},{code:'043-10',name:'宜蘭校友會'},{code:'043-11',name:'花東校友會'},
  {code:'043-12',name:'金門校友會'},{code:'060',name:'轉學生聯誼會'},{code:'076',name:'野營社'},
  {code:'078',name:'橋藝社'},{code:'080',name:'魔術社'},{code:'082',name:'棋藝社'},
  {code:'083',name:'飲料調製社'},{code:'109',name:'嚕啦啦社'},{code:'129',name:'努瑪社'},
  {code:'168',name:'桌上遊戲社'},{code:'163',name:'國際菁英學生會'},{code:'175',name:'陸生聯誼會'},
  {code:'184',name:'電子競技社'},{code:'185',name:'二輪社'},{code:'193',name:'咖啡研究社'},
  {code:'198',name:'韓國流行文化研究社'},{code:'097',name:'同舟共濟服務社'},{code:'098',name:'醒新社'},
  {code:'099',name:'淨仁社'},{code:'100',name:'急救康輔社'},{code:'101',name:'崇德志工服務社'},
  {code:'116',name:'基層文化服務社'},{code:'126',name:'慈濟青年社'},{code:'148',name:'繪本服務學習社'},
  {code:'189',name:'勵德青少年服務社'},{code:'075',name:'登山社'},{code:'084',name:'國術社'},
  {code:'086',name:'跆拳道社'},{code:'087',name:'柔道社'},{code:'088',name:'劍道社'},
  {code:'089',name:'擊劍社'},{code:'090',name:'羽球社'},{code:'091',name:'桌球社'},
  {code:'092',name:'網球社'},{code:'093',name:'射箭社'},{code:'117',name:'有氧健身社'},
  {code:'118',name:'同心救生社'},{code:'119',name:'足球社'},{code:'131',name:'空手道社'},
  {code:'136',name:'黑輪社'},{code:'147',name:'競技啦啦隊社'},{code:'166',name:'合氣道社'},
  {code:'172',name:'歐洲劍術社'},{code:'173',name:'競技飛盤社'},{code:'188',name:'撞球社'},
  {code:'190',name:'Kali武術社'},{code:'195',name:'保齡球社'},{code:'199',name:'自由潛水社'},
  {code:'064',name:'書法社'},{code:'066',name:'攝影社'},{code:'067',name:'熱舞社'},
  {code:'070',name:'戲劇社'},{code:'072',name:'國際標準舞蹈社'},{code:'073',name:'電影藝術研究社'},
  {code:'077',name:'映綠世界舞蹈社'},{code:'081',name:'廣播演藝社'},{code:'132',name:'動漫電玩研習社'},
  {code:'157',name:'影片創作社'},{code:'171',name:'弓道社'},{code:'219',name:'創意巧手社'},
  {code:'179',name:'民俗體育社'},{code:'178',name:'光火藝術社'},{code:'194',name:'生活花藝設計社'},
  {code:'061',name:'國樂社'},{code:'068',name:'管弦樂社'},{code:'071',name:'民謠吉他社'},
  {code:'074',name:'搖滾音樂研究社'},{code:'123',name:'鋼琴社'},{code:'124',name:'數位音樂創作研習社'},
  {code:'167',name:'烏克麗麗社'},{code:'186',name:'嘻哈文化社'},{code:'223',name:'爵士鋼琴社'},
  {code:'000',name:'課指組'},{code:'+s8',name:'課指組-陳佳業'},{code:'+s9',name:'課指組-余承憲'},
  {code:'-s1',name:'課指組-饒詠雯'},{code:'-s4',name:'課指組-林淑君'},{code:'-s5',name:'課指組-李于萱'},
  {code:'-s6',name:'課指組-林銘雄'},{code:'-s7',name:'課指組-張秉倪'},{code:'-s8',name:'王思涵'},
  {code:'666',name:'燈音組'},{code:'-01',name:'軍訓室'},{code:'-10',name:'公共事務室'},
  {code:'-03',name:'學輔中心'},{code:'-04',name:'學生學習中心'},{code:'-05',name:'僑陸組'},
  {code:'-07',name:'衛保組'},{code:'-28',name:'人事室'},{code:'991',name:'學生輔導中心'},
  {code:'-08',name:'西文系'},{code:'-14',name:'兒家系'},{code:'-16',name:'外語學院'},
  {code:'-18',name:'日文系'},{code:'-24',name:'西文系'},{code:'-27',name:'英文系'},
  {code:'-41',name:'企管系'},{code:'-50',name:'大眾傳播學士學位學程'},{code:'-51',name:'藝術與文化創意學士學位學程'},
  {code:'-58',name:'數學系'},{code:'-61',name:'醫資學程'},{code:'-64',name:'營養系'},
  {code:'-68',name:'中文系'},{code:'-69',name:'電機系'},{code:'-72',name:'金企系'},
  {code:'-76',name:'管理學院'},{code:'-83',name:'社工系'},{code:'-89',name:'景觀系'},
  {code:'-91',name:'生科系'},{code:'-92',name:'會計系'},{code:'-94',name:'體育系'},
  {code:'-97',name:'歷史系'},{code:'dwg',name:'社會科學院'},{code:'-40',name:'進修部生活輔導組'},
  {code:'-59',name:'輔進商管學程'},{code:'-65',name:'輔進文創學程'},{code:'-67',name:'進修部學生代表會'},
  {code:'-70',name:'進修部'},{code:'-82',name:'進修宗教系'},{code:'-90',name:'進修部中文系'},
  {code:'-93',name:'進修部導師團體'},{code:'-86',name:'輔進日文系'},{code:'-15',name:'職輔組'},
  {code:'-25',name:'生輔組'},{code:'-02',name:'學務長室'},{code:'-13',name:'出納組'},
  {code:'-53',name:'總務處'},{code:'-60',name:'總務處事務組'},{code:'-31',name:'教師發展中心'},
  {code:'-39',name:'註冊組'},{code:'-66',name:'招生組'},{code:'-84',name:'教發中心'},
  {code:'-57',name:'創意設計中心'},{code:'-19',name:'進修部使命宗輔室'},{code:'-32',name:'聖言志工團'},
  {code:'-55',name:'中國聖職單位'},{code:'-71',name:'耶穌會使命室'},{code:'-85',name:'宗輔中心'},
  {code:'-88',name:'全人教育課程中心'},{code:'-09',name:'原民中心'},{code:'-11',name:'台灣偏鄉發展教育中心'},
  {code:'-22',name:'原資中心'},{code:'-35',name:'宿舍服務中心'},{code:'-56',name:'宜美宿舍'},
  {code:'-17',name:'語言中心'},{code:'-26',name:'育成中心'},{code:'-30',name:'服務學習中心'},
  {code:'-33',name:'推廣部'},{code:'-36',name:'藝文中心'},{code:'-52',name:'創發中心'},
  {code:'-54',name:'理圖劇場'},{code:'-63',name:'資金室'},{code:'-77',name:'環安衛'},
  {code:'-98',name:'體育室'},{code:'998',name:'圖書館'},{code:'-20',name:'輔大之聲'},
  {code:'-23',name:'愛狗社'},{code:'-42',name:'乙組女籃'},{code:'-99',name:'校慶團隊'},
  {code:'-ss',name:'媒體中心'},{code:'-87',name:'揚生青年'},
];

// ─── Unit code searchable combobox helpers ───────────────────────────────
// Usage: call initUnitCombobox('suffix') after placing HTML with ids:
//   uc-input-{suffix}, uc-val-{suffix}, uc-drop-{suffix}
function initUnitCombobox(suffix) {
  const input = document.getElementById('uc-input-' + suffix);
  const valEl = document.getElementById('uc-val-' + suffix);
  const drop  = document.getElementById('uc-drop-' + suffix);
  if (!input) return;

  function renderDrop(q) {
    const kw = (q || '').toLowerCase();
    const hits = kw
      ? UNIT_CODES.filter(u => u.code.toLowerCase().includes(kw) || u.name.includes(kw))
      : UNIT_CODES;
    drop.innerHTML = hits.length
      ? hits.map(u =>
          `<div class="px-3 py-2 hover:bg-fju-blue/5 cursor-pointer text-sm text-gray-700" data-code="${u.code}" data-name="${u.name}">
            <span class="font-mono text-xs text-gray-400 mr-2">${u.code}</span>${u.name}
          </div>`
        ).join('')
      : '<div class="px-3 py-2 text-xs text-gray-400">無符合結果</div>';

    drop.querySelectorAll('[data-code]').forEach(el => {
      el.addEventListener('mousedown', function(e) {
        e.preventDefault();
        input.value  = this.dataset.code + ' — ' + this.dataset.name;
        valEl.value  = this.dataset.code;
        drop.classList.add('hidden');
        input.dispatchEvent(new Event('change'));
      });
    });
  }

  input.addEventListener('input',  () => { renderDrop(input.value); drop.classList.remove('hidden'); });
  input.addEventListener('focus',  () => { renderDrop(input.value); drop.classList.remove('hidden'); });
  input.addEventListener('blur',   () => setTimeout(() => drop.classList.add('hidden'), 150));
  input.addEventListener('keydown', e => {
    if (e.key === 'Escape') { drop.classList.add('hidden'); input.blur(); }
  });
}

function resetUnitCombobox(suffix) {
  const input = document.getElementById('uc-input-' + suffix);
  const valEl = document.getElementById('uc-val-' + suffix);
  if (input) input.value = '';
  if (valEl) valEl.value = '';
}

function getUnitCode(suffix) {
  return document.getElementById('uc-val-' + suffix)?.value || '';
}
</script>
@endsection
