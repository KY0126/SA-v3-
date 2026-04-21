@extends('layouts.app')

@php
$roleNames = ['admin'=>'課指組/處室','officer'=>'社團幹部','professor'=>'指導教授','student'=>'一般學生','it'=>'資訊中心'];
$roleIcons = ['admin'=>'fas fa-user-tie','officer'=>'fas fa-user-shield','professor'=>'fas fa-chalkboard-teacher','student'=>'fas fa-user-graduate','it'=>'fas fa-server'];
$role = $role ?? 'student';
if (!isset($roleNames[$role])) $role = 'student';
$activePage = $activePage ?? 'dashboard';

// Role-based sidebar: each role gets different modules
$roleSidebar = [
  'admin' => [
    ['title'=>'主要功能','items'=>[
      ['id'=>'dashboard','label'=>'Dashboard','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-alt','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'社群與社團','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 核心專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'管理與報表','items'=>[
      ['id'=>'conflict-coordination','label'=>'衝突協調','icon'=>'fas fa-handshake','href'=>"/module/conflict-coordination?role={$role}"],
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴記錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
      ['id'=>'user-management','label'=>'用戶管理','icon'=>'fas fa-users-cog','href'=>"/module/user-management?role={$role}"],
    ]],
    ['title'=>'個人與證書','items'=>[
      ['id'=>'e-portfolio','label'=>'職能 E-Portfolio','icon'=>'fas fa-id-badge','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'certificate','label'=>'數位證書','icon'=>'fas fa-award','href'=>"/module/certificate?role={$role}"],
      ['id'=>'time-capsule','label'=>'時光膠囊','icon'=>'fas fa-clock-rotate-left','href'=>"/module/time-capsule?role={$role}"],
      ['id'=>'2fa','label'=>'2FA 驗證','icon'=>'fas fa-lock','href'=>"/module/2fa?role={$role}"],
    ]],
  ],
  'officer' => [
    ['title'=>'主要功能','items'=>[
      ['id'=>'dashboard','label'=>'Dashboard','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-alt','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'社群與社團','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 核心專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'管理','items'=>[
      ['id'=>'conflict-coordination','label'=>'衝突協調','icon'=>'fas fa-handshake','href'=>"/module/conflict-coordination?role={$role}"],
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴記錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'e-portfolio','label'=>'ePortfolio','icon'=>'fas fa-folder-open','href'=>"/module/e-portfolio?role={$role}"],
    ]],
  ],
  'professor' => [
    ['title'=>'主要功能','items'=>[
      ['id'=>'dashboard','label'=>'Dashboard','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-alt','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'社群與社團','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 核心專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'管理','items'=>[
      ['id'=>'conflict-coordination','label'=>'衝突協調','icon'=>'fas fa-handshake','href'=>"/module/conflict-coordination?role={$role}"],
      ['id'=>'appeal','label'=>'申訴記錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
    ]],
  ],
  'student' => [
    ['title'=>'主要功能','items'=>[
      ['id'=>'dashboard','label'=>'Dashboard','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-alt','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'社群與社團','items'=>[
      ['id'=>'club-info','label'=>'社團資訊','icon'=>'fas fa-users','href'=>"/module/club-info?role={$role}"],
      ['id'=>'activity-wall','label'=>'活動牆','icon'=>'fas fa-newspaper','href'=>"/module/activity-wall?role={$role}"],
    ]],
    ['title'=>'AI 核心專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'個人','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴記錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'e-portfolio','label'=>'ePortfolio','icon'=>'fas fa-folder-open','href'=>"/module/e-portfolio?role={$role}"],
      ['id'=>'certificate','label'=>'數位證書','icon'=>'fas fa-certificate','href'=>"/module/certificate?role={$role}"],
    ]],
  ],
  'it' => [
    ['title'=>'主要功能','items'=>[
      ['id'=>'dashboard','label'=>'Dashboard','icon'=>'fas fa-tachometer-alt','href'=>"/dashboard?role={$role}"],
      ['id'=>'venue-booking','label'=>'場地預約','icon'=>'fas fa-map-marker-alt','href'=>"/module/venue-booking?role={$role}"],
      ['id'=>'equipment','label'=>'設備借用','icon'=>'fas fa-boxes-stacked','href'=>"/module/equipment?role={$role}"],
      ['id'=>'calendar','label'=>'行事曆','icon'=>'fas fa-calendar-alt','href'=>"/module/calendar?role={$role}"],
    ]],
    ['title'=>'AI 核心專區','items'=>[
      ['id'=>'ai-overview','label'=>'AI 資訊概覽','icon'=>'fas fa-brain','href'=>"/module/ai-overview?role={$role}"],
      ['id'=>'rag-search','label'=>'法規查詢 (RAG)','icon'=>'fas fa-gavel','href'=>"/module/rag-search?role={$role}"],
    ]],
    ['title'=>'系統管理','items'=>[
      ['id'=>'repair','label'=>'報修管理','icon'=>'fas fa-wrench','href'=>"/module/repair?role={$role}"],
      ['id'=>'appeal','label'=>'申訴記錄','icon'=>'fas fa-comments','href'=>"/module/appeal?role={$role}"],
      ['id'=>'reports','label'=>'統計報表','icon'=>'fas fa-chart-bar','href'=>"/module/reports?role={$role}"],
      ['id'=>'2fa','label'=>'雙因素認證','icon'=>'fas fa-shield-alt','href'=>"/module/2fa?role={$role}"],
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
        <div class="w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center shadow"><i class="fas fa-university text-white text-lg"></i></div>
        <div>
          <div class="text-fju-blue font-bold text-base leading-tight">天主教輔仁大學</div>
          <div class="text-fju-green text-xs font-medium">學務處 課外活動指導組</div>
        </div>
      </div>
      <div class="flex items-center gap-4 text-sm">
        <a href="/" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">Home</a>
        <a href="#" class="text-fju-blue hover:text-fju-yellow transition-colors font-medium">輔仁大學</a>
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
        @foreach([['認識課指組','#','fas fa-info-circle'],['學會．社團',"/module/club-info?role={$role}",'fas fa-users'],['場地/器材借用',"/module/venue-booking?role={$role}",'fas fa-key'],['重要訊息','#','fas fa-bullhorn'],['表單下載','#','fas fa-download'],['常見問題','#','fas fa-question-circle']] as $nav)
        <a href="{{ $nav[1] }}" class="flex items-center gap-1.5 px-4 py-3 text-white/80 hover:text-fju-yellow hover:bg-white/10 transition-all text-sm font-medium rounded-t-lg"><i class="{{ $nav[2] }} text-xs"></i><span>{{ $nav[0] }}</span></a>
        @endforeach
      </nav>
      <div class="flex items-center gap-3">
        <div class="relative"><i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i><input type="text" placeholder="搜尋活動、場地、社團..." class="bg-white/10 border border-white/15 rounded-fju pl-9 pr-4 py-1.5 text-white text-xs outline-none w-52 focus:w-72 focus:bg-white/20 focus:border-fju-yellow/50 transition-all" /></div>
        {{-- NOTIFICATION BELL --}}
        <div class="relative" id="notif-wrapper">
          <button onclick="toggleNotifPanel()" class="relative w-8 h-8 rounded-fju bg-white/10 flex items-center justify-center text-white/70 hover:bg-white/15 hover:text-white transition-all" id="notif-bell">
            <i class="fas fa-bell text-sm"></i>
            <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full bg-fju-red text-white text-[9px] font-bold flex items-center justify-center" id="notif-badge">3</span>
          </button>
          {{-- Notification Dropdown Panel --}}
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
        <div class="flex items-center gap-2 pl-3 border-l border-white/15">
          <div class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue text-xs font-bold"><i class="{{ $roleIcons[$role] }}"></i></div>
          <div class="hidden lg:block"><div class="text-white text-xs font-medium leading-tight">Demo User</div><div class="text-white/40 text-[10px]">{{ $roleNames[$role] }}</div></div>
          <a href="/login" class="text-white/40 hover:text-fju-red transition-colors ml-1" title="登出"><i class="fas fa-sign-out-alt text-xs"></i></a>
        </div>
      </div>
    </div>
  </div>

  {{-- MIDDLE: SIDEBAR + MAIN --}}
  <div class="flex flex-1 overflow-hidden" style="height: calc(100vh - 160px);">
    <aside class="w-64 min-w-[256px] bg-fju-sidebar flex flex-col overflow-y-auto" style="position: sticky; top: 0;">
      <div class="p-4">
        <div class="rounded-fju-lg bg-gradient-to-br from-fju-blue to-fju-blue-light p-4 text-white">
          <div class="flex items-center justify-between mb-2"><span class="text-[10px] text-white/60 font-medium">信用積分</span><span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-green/20 text-fju-green font-medium"><i class="fas fa-check-circle mr-0.5"></i>正常</span></div>
          <div class="text-2xl font-black mb-2">85<span class="text-xs font-normal text-white/40"> / 100</span></div>
          <div class="w-full bg-white/15 rounded-full h-1.5"><div class="bg-fju-yellow rounded-full h-1.5 transition-all duration-1000" style="width: 85%"></div></div>
          <div class="text-[9px] text-white/40 mt-1">低於 60 分將被強制登出</div>
        </div>
      </div>
      {{-- Role Badge --}}
      <div class="px-4 mb-2">
        <div class="flex items-center gap-2 px-3 py-2 rounded-fju bg-fju-yellow/10 border border-fju-yellow/20">
          <i class="{{ $roleIcons[$role] }} text-fju-yellow text-sm"></i>
          <span class="text-xs font-bold text-fju-yellow">{{ $roleNames[$role] }}</span>
        </div>
      </div>
      <nav class="flex-1 pb-4">
        @foreach($sidebarSections as $section)
        <div class="mt-4 first:mt-0">
          <div class="px-4 py-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $section['title'] }}</div>
          @foreach($section['items'] as $item)
          <a href="{{ $item['href'] }}" class="flex items-center gap-3 px-4 py-2.5 mx-2 rounded-fju text-sm transition-all duration-200 {{ $item['id'] === $activePage ? 'bg-fju-yellow text-fju-blue font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
            <i class="{{ $item['icon'] }} w-5 text-center text-sm {{ $item['id'] === $activePage ? 'text-fju-blue' : '' }}"></i>
            <span>{{ $item['label'] }}</span>
          </a>
          @endforeach
        </div>
        @endforeach
      </nav>
      <div class="p-3 mx-3 mb-3 rounded-fju bg-white/5">
        <div class="flex items-center gap-2 mb-2"><i class="fas fa-info-circle text-fju-yellow text-xs"></i><span class="text-[10px] font-bold text-gray-400">系統狀態</span></div>
        <div class="grid grid-cols-2 gap-1.5 text-[10px]">
          <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">DB 正常</span></div>
          <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">AI 就緒</span></div>
          <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">快取啟用</span></div>
          <div class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-green"></span><span class="text-gray-400">WAF 保護</span></div>
        </div>
      </div>
    </aside>
    <main class="flex-1 bg-fju-bg overflow-y-auto p-6">@yield('content')</main>
  </div>

  {{-- FOOTER --}}
  @include('partials.footer')
</div>
@include('partials.chatbot')

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
// Auto-load notification count on page load
document.addEventListener('DOMContentLoaded',function(){
  fetch('/api/users/1/notifications').then(r=>r.json()).then(res=>{
    const unread=res.unread_count||0;
    document.getElementById('notif-badge').textContent=unread;
    if(unread===0) document.getElementById('notif-badge').classList.add('hidden');
  }).catch(()=>{});
});
</script>
@endsection
