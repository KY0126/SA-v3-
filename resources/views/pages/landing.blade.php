@extends('layouts.app')
@section('title', '首頁')
@section('body')
<nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-500" style="background: transparent;">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center"><i class="fas fa-university text-white text-lg"></i></div><div><span class="text-lg font-bold transition-colors duration-500" id="nav-title" style="color:#fff;">天主教輔仁大學</span><span class="text-xs ml-2 transition-colors duration-500" id="nav-subtitle" style="color:#DAA520;">課外活動指導組</span></div></div>
    <div class="hidden md:flex items-center gap-6"><a href="#features" class="nav-link text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">功能特色</a><a href="#modules" class="nav-link text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">核心模組</a><a href="#testimonials" class="nav-link text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">使用評價</a><a href="#news" class="nav-link text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">最新消息</a><a href="/login" class="btn-yellow px-6 py-2 text-sm">登入系統</a></div>
  </div>
</nav>
<section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
  <div id="hero-bg" class="absolute inset-0">
    <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: linear-gradient(135deg, rgba(0,49,83,0.85), rgba(0,49,83,0.65)), url('https://images.unsplash.com/photo-1562774053-701939374585?w=1920&q=80'); opacity: 1;"></div>
    <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: linear-gradient(135deg, rgba(0,49,83,0.8), rgba(218,165,32,0.2)), url('https://images.unsplash.com/photo-1523050854058-8df90110c476?w=1920&q=80'); opacity: 0;"></div>
    <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" style="background-image: linear-gradient(135deg, rgba(0,49,83,0.85), rgba(0,49,83,0.6)), url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1920&q=80'); opacity: 0;"></div>
  </div>
  <div class="relative z-10 text-center max-w-5xl mx-auto px-6">
    <div id="hero-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full glassmorphism mb-8 opacity-0"><span class="w-2 h-2 rounded-full bg-fju-green animate-pulse"></span><span class="text-sm text-fju-blue font-medium">輔仁大學課外活動指導組</span></div>
    <h1 id="hero-title" class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight opacity-0">FJU <span class="text-fju-yellow">Smart</span> Hub</h1>
    <p id="hero-subtitle" class="text-xl md:text-2xl text-white/80 mb-4 font-light opacity-0">輔仁大學智慧校園管理平台</p>
    <p id="hero-desc" class="text-lg text-white/60 mb-10 max-w-2xl mx-auto opacity-0">整合 AI 智慧預審、資源調度、數位證書、活動管理的一站式校園解決方案</p>
    <div id="hero-btns" class="flex flex-col sm:flex-row gap-4 justify-center opacity-0"><a href="/login" class="btn-yellow px-10 py-4 text-lg inline-flex items-center gap-2"><i class="fas fa-rocket"></i> 立即開始使用</a><a href="#features" class="px-10 py-4 rounded-[12px] border-2 border-white/30 text-white hover:bg-white/10 transition-all text-lg inline-flex items-center gap-2"><i class="fas fa-play-circle"></i> 了解更多</a></div>
    <div id="hero-stats" class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 opacity-0">
      <div class="glassmorphism rounded-[15px] p-4"><div class="text-3xl font-black text-fju-blue counter" data-target="200">0</div><div class="text-sm text-gray-500">學生社團</div></div>
      <div class="glassmorphism rounded-[15px] p-4"><div class="text-3xl font-black text-fju-yellow counter" data-target="5000">0</div><div class="text-sm text-gray-500">活動場次/年</div></div>
      <div class="glassmorphism rounded-[15px] p-4"><div class="text-3xl font-black text-fju-blue counter" data-target="50">0</div><div class="text-sm text-gray-500">可借用場地</div></div>
      <div class="glassmorphism rounded-[15px] p-4"><div class="text-3xl font-black text-fju-yellow counter" data-target="98">0</div><div class="text-sm text-gray-500">滿意度 %</div></div>
    </div>
  </div>
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 animate-bounce"><i class="fas fa-chevron-down text-2xl"></i></div>
</section>

{{-- FEATURES: Only green solutions, with complete functional descriptions --}}
<section id="features" class="py-24 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-16"><span class="inline-block px-4 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-sm font-bold mb-4">痛點 → 解方</span><h2 class="text-4xl font-black text-fju-blue mb-4">使用者痛點 & FJU Smart Hub 解方</h2><p class="text-gray-500 text-lg max-w-2xl mx-auto">透過 AI 與數位化轉型，全面提升校園活動管理效率</p></div>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach([
        ['fas fa-robot','AI 三階段一鍵搞定','志願序演算法自動依優先權（活動規模/頻率 → 校方/處室 → 一般社團）配對場地，衝突時 AI 產出三方案協商，最終由課指組官方審核。全程線上完成，不必再跑三趟。','場地預約 · 志願序配對 · 衝突協調 · 官方核准'],
        ['fas fa-bolt','AI 預審即時回覆','Dify RAG 引擎自動比對 8 項校內法規（活動申請辦法、場地管理規則、安全管理規範等），即時標記合規風險，審核速度提升 10 倍，1.3 秒完成全部檢查。','法規查詢 · RAG 語義比對 · 風險等級 · 合規報告'],
        ['fas fa-wand-magic-sparkles','AI 一鍵生成企劃書','輸入活動名稱、人數、日期，AI 自動生成含宗旨、流程、預算明細（場地/餐飲/文具/保險）、SDGs 對應、風險評估的完整企劃書，直接送審。','企劃生成 · 預算規劃 · SDGs 對應 · 風險評估'],
        ['fas fa-globe','五國語言即時切換','系統全面支援繁中、簡中、英文、日文、韓文介面，模組名稱與按鈕全部翻譯，點一下即時切換，協助外籍生順利使用校園服務。','國際化 · i18n API · 5 語言包 · 即時切換'],
        ['fas fa-map-marked-alt','互動式無障礙地圖','Leaflet.js 互動地圖整合校園 20 棟建築、無障礙坡道/電梯/廁所、生活服務點、大眾運輸站，支援分類篩選、搜尋和即時定位。','校園地圖 · 無障礙設施 · 分類圖層 · 搜尋定位'],
        ['fas fa-handshake','AI 衝突協調中心','當場地衝突發生時，系統自動偵測並進入協調流程：即時聊天對話 + Outlook/SMS/LINE 多管道通知 + AI 產出三方案（含信心度評分）+ 雙方確認按鈕。','即時聊天 · 郵件通知 · AI 建議 · 雙方確認']
      ] as $idx => $f)
      <div class="feature-card bg-white rounded-[15px] p-6 shadow-lg border border-gray-100 card-hover opacity-0">
        <div class="flex items-start gap-3 mb-4">
          <div class="w-12 h-12 rounded-[12px] bg-green-50 flex items-center justify-center shrink-0"><i class="{{ $f[0] }} text-lg" style="color:#008000"></i></div>
          <div>
            <div class="font-bold text-[#008000] text-base">{{ $f[1] }}</div>
            <p class="text-gray-600 text-sm mt-2 leading-relaxed">{{ $f[2] }}</p>
          </div>
        </div>
        <div class="flex items-center gap-1 flex-wrap mt-3 pt-3 border-t border-gray-100">
          @foreach(explode(' · ', $f[3]) as $tag)
          <span class="px-2 py-0.5 rounded-full bg-green-50 text-[#008000] text-[10px] font-medium">{{ $tag }}</span>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section id="modules" class="py-24 bg-gradient-to-b from-white to-gray-50">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-16"><span class="inline-block px-4 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow text-sm font-bold mb-4">核心架構</span><h2 class="text-4xl font-black text-fju-blue mb-4">十大支柱模組</h2></div>
    <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
      @foreach([
        ['fas fa-id-badge','職能 E-Portfolio','e-portfolio','記錄社團幹部服務歷程、技能徽章、活動參與，生成雷達圖與成長曲線，支援匯出 PDF 求職履歷。'],
        ['fas fa-wand-magic-sparkles','AI 企劃生成器','ai-overview','輸入基本資訊即可自動生成含預算、流程、SDGs、風險評估的完整企劃書，並自動送 AI 預審。'],
        ['fas fa-award','幹部證書自動化','certificate','產出輔仁大學正式獎狀格式的數位證書，含校徽浮水印、數位簽章、QR Code 驗證，可下載 PDF 或列印。'],
        ['fas fa-boxes-stacked','器材盤點追蹤','equipment','五大類器材即時盤點（擴音/影音/場佈/燈光/其他），借還流程全程追蹤，逾期自動記點通知。'],
        ['fas fa-brain','AI 智慧預審','rag-search','Dify RAG + GPT-4 三層審核引擎，自動比對 8 項法規，1.3 秒完成審查，輸出風險等級與合規報告。'],
        ['fas fa-map-location-dot','場地活化大數據','venue-booking','三階段資源調度（志願序配對→衝突協調→官方核准），即時使用率統計，支援 Outlook/SMS/LINE 通知。'],
        ['fas fa-comments','AI 申訴摘要','appeal','AI 自動摘要申訴內容，分析情緒與緊急程度，提出三項建議方案，加速案件處理。'],
        ['fas fa-newspaper','動態活動牆','activity-wall','即時活動卡片牆，支援標籤搜尋、狀態篩選、一鍵報名，Hashtag 分類讓探索更快速。'],
        ['fas fa-clock-rotate-left','數位時光膠囊','time-capsule','社團留言、照片、影片時光膠囊，設定未來開啟日期，屆時自動 Outlook 通知，保存珍貴回憶。'],
        ['fas fa-lock','全方位 2FA','2fa','支援 TOTP 驗證碼 + Outlook 信箱驗證 + SMS 簡訊驗證三種方式，保障帳號安全，管理已綁定裝置。']
      ] as $idx => $m)
      <div class="module-card bg-white rounded-[12px] p-5 shadow-md border border-gray-100 card-hover cursor-pointer opacity-0" onclick="window.location.href='/module/{{ $m[2] }}?role=student'">
        <div class="w-12 h-12 rounded-[12px] {{ $idx % 2 === 0 ? 'bg-fju-yellow' : 'bg-fju-blue' }} flex items-center justify-center mb-3 shadow-md"><i class="{{ $m[0] }} {{ $idx % 2 === 0 ? 'text-fju-blue' : 'text-white' }}"></i></div>
        <h3 class="font-bold text-fju-blue text-sm mb-1">{{ $m[1] }}</h3>
        <p class="text-[11px] text-gray-500 leading-relaxed">{{ $m[3] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section id="testimonials" class="py-24 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-16"><h2 class="text-4xl font-black text-fju-blue mb-4">師生好評推薦</h2></div>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach([['陳同學','攝影社社長','以前借場地要跑好幾個辦公室，現在線上一鍵申請！'],['林老師','課指組組長','大數據儀表板讓我一目了然所有社團的活動狀態。'],['王教授','指導教授','雷達圖清楚呈現學生的職能成長，非常推薦！']] as $idx => $t)
      <div class="testimonial-card bg-white rounded-[15px] p-8 shadow-lg border border-gray-100 opacity-0">
        <div class="flex items-center gap-3 mb-4"><div class="w-12 h-12 rounded-full {{ $idx % 2 === 0 ? 'bg-fju-blue' : 'bg-fju-yellow' }} flex items-center justify-center text-white font-bold">{{ mb_substr($t[0],0,1) }}</div><div><div class="font-bold text-fju-blue">{{ $t[0] }}</div><div class="text-xs text-gray-400">{{ $t[1] }}</div></div></div>
        <div class="flex gap-1 mb-3">@for($i=0;$i<5;$i++)<i class="fas fa-star text-fju-yellow text-sm"></i>@endfor</div>
        <p class="text-gray-500 text-sm">「{{ $t[2] }}」</p>
      </div>
      @endforeach
    </div>
  </div>
</section>
<section id="news" class="py-12 bg-fju-blue">
  <div class="max-w-7xl mx-auto px-6"><div class="flex items-center gap-4"><span class="shrink-0 px-4 py-2 rounded-full bg-fju-yellow text-fju-blue font-bold text-sm"><i class="fas fa-bullhorn mr-2"></i>最新公告</span><div class="overflow-hidden flex-1"><div id="news-ticker" class="flex gap-16 whitespace-nowrap" style="animation: ticker 30s linear infinite;"><span class="text-white/80">📢 114學年度第二學期社團場地申請已開放</span><span class="text-white/80">🏆 第33屆金乐獎報名截止日：2026/04/30</span><span class="text-white/80">📋 新版活動企劃書範本已上架 AI 企劃生成器</span><span class="text-white/80">🎉 FJU Smart Hub 3.0 正式上線 (Laravel + MySQL)</span></div></div></div></div>
  <style>@keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }</style>
</section>
<footer class="py-16 bg-fju-blue text-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="grid md:grid-cols-3 gap-12 mb-12"><div><div class="font-bold mb-2"><i class="fas fa-map-marker-alt mr-2 text-fju-yellow"></i>校址</div><p class="text-white/50 text-sm">24205 新北市新莊區中正路510號</p></div><div class="text-center"><div class="font-bold mb-2"><i class="fab fa-facebook mr-2 text-fju-yellow"></i>粉專連結</div><div class="flex gap-3 justify-center mt-2"><a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-facebook-f"></i></a><a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-instagram"></i></a><a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-line"></i></a></div></div><div class="text-right"><div class="font-bold mb-2"><i class="fas fa-phone mr-2 text-fju-yellow"></i>櫃台資訊</div><p class="text-white/50 text-sm">(02) 2905-2237<br>activity@mail.fju.edu.tw</p></div></div>
    <div class="border-t border-white/10 pt-8 text-center text-white/40 text-sm"><p>天主教輔仁大學 &copy; 2014-2026 版權所有 | FJU Smart Hub v3.0</p></div>
  </div>
</footer>
<script>
// Navbar scroll handler: change university name color on scroll
window.addEventListener('scroll',()=>{
  const n=document.getElementById('navbar');
  const t=document.getElementById('nav-title');
  const st=document.getElementById('nav-subtitle');
  const links=document.querySelectorAll('.nav-link');
  if(window.scrollY>80){
    n.classList.add('glassmorphism','shadow-lg');
    n.style.background='';
    t.style.color='#003153'; // fju-blue for readability on white
    st.style.color='#DAA520';
    links.forEach(l=>{l.classList.remove('text-white/90');l.classList.add('text-fju-blue')});
  }else{
    n.classList.remove('glassmorphism','shadow-lg');
    n.style.background='transparent';
    t.style.color='#ffffff';
    st.style.color='#DAA520';
    links.forEach(l=>{l.classList.remove('text-fju-blue');l.classList.add('text-white/90')});
  }
});
gsap.registerPlugin(ScrollTrigger);const heroTl=gsap.timeline({defaults:{ease:'power3.out'}});
heroTl.to('#hero-badge',{opacity:1,y:0,duration:0.8,delay:0.3}).to('#hero-title',{opacity:1,y:0,duration:0.8},'-=0.4').to('#hero-subtitle',{opacity:1,y:0,duration:0.6},'-=0.3').to('#hero-desc',{opacity:1,y:0,duration:0.6},'-=0.3').to('#hero-btns',{opacity:1,y:0,duration:0.6},'-=0.3').to('#hero-stats',{opacity:1,y:0,duration:0.8},'-=0.2');
document.querySelectorAll('.counter').forEach(el=>{const t=parseInt(el.dataset.target);gsap.to(el,{innerText:t,duration:2,delay:1.5,snap:{innerText:1},scrollTrigger:{trigger:el,start:'top 90%'}})});
let cs=0;const sl=document.querySelectorAll('.hero-slide');setInterval(()=>{sl[cs].style.opacity='0';cs=(cs+1)%sl.length;sl[cs].style.opacity='1'},5000);
gsap.utils.toArray('.feature-card').forEach((c,i)=>{gsap.to(c,{opacity:1,y:0,duration:0.6,delay:i*0.15,scrollTrigger:{trigger:c,start:'top 85%'}})});
gsap.utils.toArray('.module-card').forEach((c,i)=>{gsap.to(c,{opacity:1,y:0,scale:1,duration:0.5,delay:i*0.08,scrollTrigger:{trigger:c,start:'top 90%'}})});
gsap.utils.toArray('.testimonial-card').forEach((c,i)=>{gsap.to(c,{opacity:1,y:0,duration:0.6,delay:i*0.2,scrollTrigger:{trigger:c,start:'top 85%'}})});
document.querySelectorAll('a[href^="#"]').forEach(a=>{a.addEventListener('click',e=>{e.preventDefault();const t=document.querySelector(a.getAttribute('href'));if(t)t.scrollIntoView({behavior:'smooth',block:'start'})})});
</script>
@endsection
