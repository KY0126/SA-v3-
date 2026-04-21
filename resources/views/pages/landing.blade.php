@extends('layouts.app')
@section('title', '首頁')
@section('body')
<nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-500" style="background: transparent;">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3"><div class="w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center"><i class="fas fa-university text-white text-lg"></i></div><div><span class="text-lg font-bold text-white" id="nav-title">天主教輔仁大學</span><span class="text-xs text-fju-yellow ml-2" id="nav-subtitle">課外活動指導組</span></div></div>
    <div class="hidden md:flex items-center gap-6"><a href="#features" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">功能特色</a><a href="#modules" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">核心模組</a><a href="#testimonials" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">使用評價</a><a href="#news" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">最新消息</a><a href="/login" class="btn-yellow px-6 py-2 text-sm">登入系統</a></div>
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
<section id="features" class="py-24 bg-white">
  <div class="max-w-7xl mx-auto px-6">
    <div class="text-center mb-16"><span class="inline-block px-4 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-sm font-bold mb-4">痛點 → 解方</span><h2 class="text-4xl font-black text-fju-blue mb-4">使用者痛點 & FJU Smart Hub 解方</h2><p class="text-gray-500 text-lg max-w-2xl mx-auto">透過 AI 與數位化轉型，全面提升校園活動管理效率</p></div>
    <div class="grid md:grid-cols-3 gap-8">
      @foreach([
        ['fas fa-times-circle','#FF0000','場地預約跑三趟','以前借場地要跑教務處、課指組、總務處...','fas fa-robot','#008000','AI 三階段一鍵搞定','志願序演算法 + AI 協商 + 官方審核，線上完成全流程。'],
        ['fas fa-clock','#FF0000','申請等半個月','紙本文件層層簽核，等待時間無法預估','fas fa-bolt','#008000','AI 預審即時回覆','RAG 引擎自動比對法規，即時標記合規風險，審核速度提升 10 倍。'],
        ['fas fa-file-alt','#FF0000','表單填不完','每次活動都要填十幾份不同的表單','fas fa-wand-magic-sparkles','#008000','AI 一鍵生成企劃書','輸入基本資訊，AI 自動生成完整企劃書、預算表、風險評估。'],
        ['fas fa-language','#FF0000','外籍生看不懂','系統介面只有中文，外籍生無法使用','fas fa-globe','#008000','五國語言即時切換','繁中、簡中、英、日、韓全系統翻譯，點一下全部切換。'],
        ['fas fa-wheelchair','#FF0000','找不到無障礙設施','校園太大，不知道哪裡有坡道和電梯','fas fa-map-marked-alt','#008000','互動式無障礙地圖','Leaflet.js 互動地圖，一鍵顯示所有無障礙設施位置。'],
        ['fas fa-shield-alt','#FF0000','場地衝突沒人管','兩個社團搶同一個場地，互相推諉','fas fa-handshake','#008000','AI 衝突協調中心','即時對話 + 郵件通知 + AI 建議方案 + 雙方確認按鈕。']
      ] as $idx => $f)
      <div class="feature-card bg-white rounded-[15px] p-6 shadow-lg border border-gray-100 card-hover opacity-0">
        <div class="flex items-start gap-3 mb-4">
          <div class="w-10 h-10 rounded-[12px] bg-red-50 flex items-center justify-center shrink-0"><i class="{{ $f[0] }}" style="color:{{ $f[1] }}"></i></div>
          <div><div class="font-bold text-fju-red text-sm line-through decoration-2">{{ $f[2] }}</div><p class="text-gray-400 text-xs mt-1">{{ $f[3] }}</p></div>
        </div>
        <div class="flex items-center gap-2 mb-3"><i class="fas fa-arrow-down text-fju-yellow"></i><span class="text-[10px] text-fju-yellow font-bold">Smart Hub 解方</span></div>
        <div class="flex items-start gap-3">
          <div class="w-10 h-10 rounded-[12px] bg-green-50 flex items-center justify-center shrink-0"><i class="{{ $f[4] }}" style="color:{{ $f[5] }}"></i></div>
          <div><div class="font-bold text-fju-green text-sm">{{ $f[6] }}</div><p class="text-gray-500 text-xs mt-1">{{ $f[7] }}</p></div>
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
      @foreach([['fas fa-id-badge','職能 E-Portfolio','e-portfolio'],['fas fa-wand-magic-sparkles','AI 企劃生成器','ai-guide'],['fas fa-award','幹部證書自動化','certificate'],['fas fa-boxes-stacked','器材盤點追蹤','equipment'],['fas fa-brain','AI 智慧預審','rag-search'],['fas fa-map-location-dot','場地活化大數據','venue-booking'],['fas fa-comments','AI 申訴摘要','appeal'],['fas fa-newspaper','動態活動牆','activity-wall'],['fas fa-clock-rotate-left','數位時光膠囊','time-capsule'],['fas fa-lock','全方位 2FA','2fa']] as $idx => $m)
      <div class="module-card bg-white rounded-[12px] p-6 shadow-md border border-gray-100 card-hover cursor-pointer opacity-0" onclick="window.location.href='/module/{{ $m[2] }}?role=student'">
        <div class="w-12 h-12 rounded-[12px] {{ $idx % 2 === 0 ? 'bg-fju-yellow' : 'bg-fju-blue' }} flex items-center justify-center mb-4 shadow-md"><i class="{{ $m[0] }} {{ $idx % 2 === 0 ? 'text-fju-blue' : 'text-white' }}"></i></div>
        <h3 class="font-bold text-fju-blue text-sm">{{ $m[1] }}</h3>
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
window.addEventListener('scroll',()=>{const n=document.getElementById('navbar');if(window.scrollY>80){n.classList.add('glassmorphism','shadow-lg');n.style.background=''}else{n.classList.remove('glassmorphism','shadow-lg');n.style.background='transparent'}});
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
