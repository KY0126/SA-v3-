@extends('layouts.app')

@php
$roleNames = ['admin'=>'課指組審核員','officer'=>'社團幹部','professor'=>'指導教授','student'=>'一般學生','it'=>'資訊中心'];
$roleIcons = ['admin'=>'fas fa-user-tie','officer'=>'fas fa-user-shield','professor'=>'fas fa-chalkboard-teacher','student'=>'fas fa-user-graduate','it'=>'fas fa-server'];
$role = $role ?? 'student';
if (!isset($roleNames[$role])) $role = 'student';
$activePage = $activePage ?? 'dashboard';

// Simplified sidebar: 1.儀表板 2.活動申請 3.場地預約 4.設備借用 + role-specific extras
$roleSidebar = [
  'admin' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'calendar','label'=>'活動申請','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'管理工具','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
      ['id'=>'user-management','label'=>'使用者管理','icon'=>'fas fa-users-cog','href'=>"/module/user-management?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'e-portfolio','label'=>'職能 E-Portfolio','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'certificate','label'=>'數位證書','icon'=>'fas fa-award','href'=>"/module/certificate?role={$role}"],
      ['id'=>'time-capsule','label'=>'時光膠囊','icon'=>'fas fa-clock-rotate-left','href'=>"/module/time-capsule?role={$role}"],
      ['id'=>'2fa','label'=>'雙因素驗證','icon'=>'fas fa-lock','href'=>"/module/2fa?role={$role}"],
    ]],
  ],
  'officer' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'calendar','label'=>'活動申請','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'e-portfolio','label'=>'職能 E-Portfolio','icon'=>'fas fa-folder-open','href'=>"/module/e-portfolio?role={$role}"],
    ]],
  ],
  'professor' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'calendar','label'=>'活動申請','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
    ]],
  ],
  'student' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'calendar','label'=>'活動申請','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
    ]],
    ['title'=>'社團管理','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'個人專區','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴紀錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'e-portfolio','label'=>'職能 E-Portfolio','icon'=>'fas fa-folder-open','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'certificate','label'=>'數位證書','icon'=>'fas fa-certificate','href'=>"/module/certificate?role={$role}"],
    ]],
  ],
  'it' => [
    ['title'=>'核心功能','items'=>[
      ['id'=>'dashboard','label'=>'儀表板','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'calendar','label'=>'活動申請','icon'=>'fas fa-calendar-check','href'=>"/module/calendar?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
    ]],
    ['title'=>'AI 智慧專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
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
        <a href="#" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">網站地圖</a>
        <div class="flex gap-1 ml-2 pl-3 border-l border-gray-200">
          @foreach($roleNames as $r => $label)
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
        @foreach([['認識課指組','#','fas fa-info-circle'],['學會．社團',"/module/club-info?role={$role}",'fas fa-users'],['場地/器材借用',"/module/venue-booking?role={$role}",'fas fa-key'],['重要訊息','#','fas fa-bullhorn'],['表單下載','#','fas fa-download'],['常見問題',"/module/faq?role={$role}",'fas fa-question-circle']] as $nav)
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
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-fju-red text-white text-[9px] font-bold flex items-center justify-center" id="notif-badge">3</span>
          </button>
          <div id="notif-panel" class="hidden absolute right-0 top-full mt-2 w-96 bg-white rounded-fju-lg shadow-2xl border border-gray-200 z-[100] overflow-hidden">
            <div class="px-4 py-3 bg-fju-blue flex items-center justify-between">
              <span class="text-white font-bold text-sm"><i class="fas fa-bell mr-2 text-fju-yellow"></i>系統通知</span>
              <button onclick="markAllRead()" class="text-fju-yellow text-xs hover:underline">全部標為已讀</button>
            </div>
            <div id="notif-list" class="max-h-80 overflow-y-auto">
              <div class="p-4 text-center text-gray-400 text-sm"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>
            </div>
            <div class="px-4 py-2 border-t border-gray-100 text-center">
              <a href="#" class="text-fju-blue text-xs hover:text-fju-yellow">查看所有通知</a>
            </div>
          </div>
        </div>
        {{-- USER AVATAR → links to E-Portfolio --}}
        <div class="flex items-center gap-2 pl-3 border-l border-white/15">
          <a href="/module/e-portfolio?role={{ $role }}" class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue text-xs font-bold hover:ring-2 hover:ring-fju-yellow/50 transition-all" title="前往 E-Portfolio"><i class="{{ $roleIcons[$role] }}"></i></a>
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
@include('partials.chatbot')

{{-- === GLOBAL SEARCH SCRIPT === --}}
<script>
const searchIndex = [
  {title:'儀表板', url:'/dashboard?role={{ $role }}', cat:'頁面', icon:'fa-tachometer-alt'},
  {title:'活動申請', url:'/module/calendar?role={{ $role }}', cat:'頁面', icon:'fa-calendar-check'},
  {title:'場地預約', url:'/module/venue-booking?role={{ $role }}', cat:'頁面', icon:'fa-map-marker-alt'},
  {title:'設備借用', url:'/module/equipment?role={{ $role }}', cat:'頁面', icon:'fa-boxes-stacked'},
  {title:'社團資訊', url:'/module/club-info?role={{ $role }}', cat:'頁面', icon:'fa-users'},
  {title:'活動牆', url:'/module/activity-wall?role={{ $role }}', cat:'頁面', icon:'fa-newspaper'},
  {title:'AI 資訊概覽', url:'/module/ai-overview?role={{ $role }}', cat:'頁面', icon:'fa-brain'},
  {title:'法規查詢 (RAG)', url:'/module/rag-search?role={{ $role }}', cat:'頁面', icon:'fa-gavel'},
  {title:'報修管理', url:'/module/repair?role={{ $role }}', cat:'頁面', icon:'fa-wrench'},
  {title:'申訴紀錄', url:'/module/appeal?role={{ $role }}', cat:'頁面', icon:'fa-comments'},
  {title:'職能 E-Portfolio', url:'/module/e-portfolio?role={{ $role }}', cat:'頁面', icon:'fa-id-badge'},
  {title:'數位證書', url:'/module/certificate?role={{ $role }}', cat:'頁面', icon:'fa-award'},
  {title:'時光膠囊', url:'/module/time-capsule?role={{ $role }}', cat:'頁面', icon:'fa-clock-rotate-left'},
  {title:'雙因素驗證', url:'/module/2fa?role={{ $role }}', cat:'頁面', icon:'fa-lock'},
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
  {title:'登山社', url:'/module/club-info?role={{ $role }}', cat:'社團', icon:'fa-mountain'},
  {title:'程式設計社', url:'/module/club-info?role={{ $role }}', cat:'社團', icon:'fa-laptop-code'},
  {title:'AI 預審', url:'/module/ai-overview?role={{ $role }}', cat:'AI 功能', icon:'fa-robot'},
  {title:'AI 企劃生成', url:'/module/ai-overview?role={{ $role }}', cat:'AI 功能', icon:'fa-wand-magic-sparkles'},
  {title:'AI 衝突協商', url:'/module/venue-booking?role={{ $role }}', cat:'AI 功能', icon:'fa-handshake'},
  {title:'校園活動安全管理辦法', url:'/module/rag-search?role={{ $role }}', cat:'法規', icon:'fa-gavel'},
  {title:'場地管理規則', url:'/module/rag-search?role={{ $role }}', cat:'法規', icon:'fa-gavel'},
  {title:'社團評鑑辦法', url:'/module/rag-search?role={{ $role }}', cat:'法規', icon:'fa-gavel'},
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

{{-- Notification Panel Script --}}
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
  fetch('/api/users/1/notifications').then(r=>r.json()).then(res=>{
    const list=res.data||[];
    const unread=res.unread_count||0;
    document.getElementById('notif-badge').textContent=unread;
    if(unread===0) document.getElementById('notif-badge').classList.add('hidden');
    else document.getElementById('notif-badge').classList.remove('hidden');
    if(list.length===0){
      document.getElementById('notif-list').innerHTML='<div class="p-6 text-center text-gray-400 text-sm"><i class="fas fa-bell-slash mr-1"></i>暫無通知</div>';
      return;
    }
    document.getElementById('notif-list').innerHTML=list.map(n=>{
      const isUnread=!n.is_read;
      return `<div class="px-4 py-3 border-b border-gray-50 hover:bg-fju-bg/50 cursor-pointer transition-all ${isUnread?'bg-fju-yellow/5':''}" onclick="readNotif(${n.id})">
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full ${isUnread?'bg-fju-yellow/20':'bg-gray-100'} flex items-center justify-center shrink-0">
            <i class="fas ${n.channel==='email'?'fa-envelope':n.channel==='sms'?'fa-sms':'fa-bell'} ${isUnread?'text-fju-yellow':'text-gray-400'} text-xs"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold ${isUnread?'text-fju-blue':'text-gray-500'}">${n.title}</span>
              ${isUnread?'<span class="w-2 h-2 rounded-full bg-fju-yellow shrink-0"></span>':''}
            </div>
            <p class="text-[11px] text-gray-500 mt-0.5 truncate">${n.message}</p>
            <span class="text-[10px] text-gray-400 mt-1">${new Date(n.created_at).toLocaleDateString('zh-TW')}</span>
          </div>
        </div>
      </div>`;
    }).join('');
  });
}
function readNotif(id){
  fetch('/api/notifications/'+id+'/read',{method:'PATCH',headers:{'Accept':'application/json'}}).then(()=>loadNotifications());
}
function markAllRead(){
  fetch('/api/users/1/notifications').then(r=>r.json()).then(res=>{
    const promises=(res.data||[]).filter(n=>!n.is_read).map(n=>fetch('/api/notifications/'+n.id+'/read',{method:'PATCH',headers:{'Accept':'application/json'}}));
    Promise.all(promises).then(()=>loadNotifications());
  });
}
document.addEventListener('DOMContentLoaded',function(){
  fetch('/api/users/1/notifications').then(r=>r.json()).then(res=>{
    const unread=res.unread_count||0;
    document.getElementById('notif-badge').textContent=unread;
    if(unread===0) document.getElementById('notif-badge').classList.add('hidden');
  }).catch(()=>{});
});
</script>
@endsection
