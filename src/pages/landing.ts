import { layout } from './layout'

export function landing(): string {
  const body = `
  <!-- Navigation -->
  <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-500" style="background: transparent;">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center">
          <i class="fas fa-university text-white text-lg"></i>
        </div>
        <div>
          <span class="text-lg font-bold text-white" id="nav-title">天主教輔仁大學</span>
          <span class="text-xs text-fju-yellow ml-2" id="nav-subtitle">課外活動指導組</span>
        </div>
      </div>
      <div class="hidden md:flex items-center gap-6">
        <a href="#features" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">功能特色</a>
        <a href="#modules" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">核心模組</a>
        <a href="#testimonials" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">使用評價</a>
        <a href="#news" class="text-white/90 hover:text-fju-yellow transition-colors text-sm font-medium">最新消息</a>
        <a href="/login" class="btn-yellow px-6 py-2 text-sm">登入系統</a>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="hero" class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <div id="hero-bg" class="absolute inset-0">
      <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" 
           style="background-image: linear-gradient(135deg, rgba(0,43,91,0.85), rgba(0,43,91,0.65)), 
           url('https://images.unsplash.com/photo-1562774053-701939374585?w=1920&q=80'); opacity: 1;"></div>
      <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" 
           style="background-image: linear-gradient(135deg, rgba(0,43,91,0.8), rgba(255,184,0,0.2)), 
           url('https://images.unsplash.com/photo-1523050854058-8df90110c476?w=1920&q=80'); opacity: 0;"></div>
      <div class="hero-slide absolute inset-0 bg-cover bg-center transition-opacity duration-1000" 
           style="background-image: linear-gradient(135deg, rgba(0,43,91,0.85), rgba(0,43,91,0.6)), 
           url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1920&q=80'); opacity: 0;"></div>
    </div>
    <div class="relative z-10 text-center max-w-5xl mx-auto px-6">
      <div id="hero-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full glassmorphism mb-8 opacity-0">
        <span class="w-2 h-2 rounded-full bg-fju-green animate-pulse"></span>
        <span class="text-sm text-fju-blue font-medium">輔仁大學課外活動指導組</span>
      </div>
      <h1 id="hero-title" class="text-5xl md:text-7xl font-black text-white mb-6 leading-tight opacity-0">
        FJU <span class="text-fju-yellow">Smart</span> Hub
      </h1>
      <p id="hero-subtitle" class="text-xl md:text-2xl text-white/80 mb-4 font-light opacity-0">
        輔仁大學智慧校園管理平台
      </p>
      <p id="hero-desc" class="text-lg text-white/60 mb-10 max-w-2xl mx-auto opacity-0">
        整合 AI 智慧預審、資源調度、數位證書、活動管理的一站式校園解決方案
      </p>
      <div id="hero-btns" class="flex flex-col sm:flex-row gap-4 justify-center opacity-0">
        <a href="/login" class="btn-yellow px-10 py-4 text-lg inline-flex items-center gap-2">
          <i class="fas fa-rocket"></i> 立即開始使用
        </a>
        <a href="#features" class="px-10 py-4 rounded-[12px] border-2 border-white/30 text-white hover:bg-white/10 transition-all text-lg inline-flex items-center gap-2">
          <i class="fas fa-play-circle"></i> 了解更多
        </a>
      </div>
      <div id="hero-stats" class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6 opacity-0">
        <div class="glassmorphism rounded-[15px] p-4">
          <div class="text-3xl font-black text-fju-blue counter" data-target="200">0</div>
          <div class="text-sm text-gray-500">學生社團</div>
        </div>
        <div class="glassmorphism rounded-[15px] p-4">
          <div class="text-3xl font-black text-fju-yellow counter" data-target="5000">0</div>
          <div class="text-sm text-gray-500">活動場次/年</div>
        </div>
        <div class="glassmorphism rounded-[15px] p-4">
          <div class="text-3xl font-black text-fju-blue counter" data-target="50">0</div>
          <div class="text-sm text-gray-500">可借用場地</div>
        </div>
        <div class="glassmorphism rounded-[15px] p-4">
          <div class="text-3xl font-black text-fju-yellow counter" data-target="98">0</div>
          <div class="text-sm text-gray-500">滿意度 %</div>
        </div>
      </div>
    </div>
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 animate-bounce">
      <i class="fas fa-chevron-down text-2xl"></i>
    </div>
  </section>

  <!-- Features -->
  <section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-sm font-bold mb-4">解決方案</span>
        <h2 class="text-4xl font-black text-fju-blue mb-4">FJU Smart Hub 的優勢</h2>
        <p class="text-gray-500 text-lg max-w-2xl mx-auto">透過 AI 與數位化轉型，全面提升校園活動管理效率</p>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        ${[
          { icon: 'fas fa-robot', title: 'AI 智慧預審', desc: 'RAG 引擎自動比對校內法規，即時標記合規風險。' },
          { icon: 'fas fa-chess', title: '三階段資源調度', desc: '志願序演算法 + AI 協商 + 官方審核。' },
          { icon: 'fas fa-map-marked-alt', title: '無障礙校園地圖', desc: 'Leaflet.js 互動地圖，標示所有無障礙設施。' },
          { icon: 'fas fa-certificate', title: '數位證書自動化', desc: '幹部證書自動產生、數位簽章驗證。' },
          { icon: 'fas fa-shield-alt', title: '全方位安全防護', desc: 'Cloudflare WAF + 2FA + 信用積分制度。' },
          { icon: 'fas fa-language', title: '五國語言支援', desc: '繁中、簡中、英、日、韓全系統翻譯。' },
        ].map((f, i) => `
        <div class="feature-card bg-white rounded-[15px] p-8 shadow-lg border border-gray-100 card-hover opacity-0">
          <div class="w-14 h-14 rounded-[12px] ${i % 2 === 0 ? 'bg-fju-yellow' : 'bg-fju-blue'} flex items-center justify-center mb-6 shadow-lg">
            <i class="${f.icon} ${i % 2 === 0 ? 'text-fju-blue' : 'text-white'} text-xl"></i>
          </div>
          <h3 class="text-lg font-bold text-fju-blue mb-2">${f.title}</h3>
          <p class="text-gray-500 text-sm">${f.desc}</p>
        </div>`).join('')}
      </div>
    </div>
  </section>

  <!-- 10 Modules -->
  <section id="modules" class="py-24 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <span class="inline-block px-4 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow text-sm font-bold mb-4">核心架構</span>
        <h2 class="text-4xl font-black text-fju-blue mb-4">十大支柱模組</h2>
      </div>
      <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
        ${[
          { icon: 'fas fa-id-badge', name: '職能 E-Portfolio', key: 'e-portfolio' },
          { icon: 'fas fa-wand-magic-sparkles', name: 'AI 企劃生成器', key: 'ai-guide' },
          { icon: 'fas fa-award', name: '幹部證書自動化', key: 'certificate' },
          { icon: 'fas fa-boxes-stacked', name: '器材盤點追蹤', key: 'equipment' },
          { icon: 'fas fa-brain', name: 'AI 智慧預審', key: 'rag-search' },
          { icon: 'fas fa-map-location-dot', name: '場地活化大數據', key: 'venue-booking' },
          { icon: 'fas fa-comments', name: 'AI 申訴摘要', key: 'appeal' },
          { icon: 'fas fa-newspaper', name: '動態活動牆', key: 'activity-wall' },
          { icon: 'fas fa-clock-rotate-left', name: '數位時光膠囊', key: 'time-capsule' },
          { icon: 'fas fa-lock', name: '全方位 2FA', key: '2fa' },
        ].map((m, i) => `
        <div class="module-card bg-white rounded-[12px] p-6 shadow-md border border-gray-100 card-hover cursor-pointer opacity-0"
             onclick="window.location.href='/module/${m.key}?role=student'">
          <div class="w-12 h-12 rounded-[12px] ${i % 2 === 0 ? 'bg-fju-yellow' : 'bg-fju-blue'} flex items-center justify-center mb-4 shadow-md">
            <i class="${m.icon} ${i % 2 === 0 ? 'text-fju-blue' : 'text-white'}"></i>
          </div>
          <h3 class="font-bold text-fju-blue text-sm">${m.name}</h3>
        </div>`).join('')}
      </div>
    </div>
  </section>

  <!-- Testimonials -->
  <section id="testimonials" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="text-center mb-16">
        <h2 class="text-4xl font-black text-fju-blue mb-4">師生好評推薦</h2>
      </div>
      <div class="grid md:grid-cols-3 gap-8">
        ${[
          { name: '陳同學', role: '攝影社社長', msg: '以前借場地要跑好幾個辦公室，現在線上一鍵申請！' },
          { name: '林老師', role: '課指組組長', msg: '大數據儀表板讓我一目了然所有社團的活動狀態。' },
          { name: '王教授', role: '指導教授', msg: '雷達圖清楚呈現學生的職能成長，非常推薦！' },
        ].map((t, i) => `
        <div class="testimonial-card bg-white rounded-[15px] p-8 shadow-lg border border-gray-100 opacity-0">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full ${i % 2 === 0 ? 'bg-fju-blue' : 'bg-fju-yellow'} flex items-center justify-center text-white font-bold">${t.name[0]}</div>
            <div>
              <div class="font-bold text-fju-blue">${t.name}</div>
              <div class="text-xs text-gray-400">${t.role}</div>
            </div>
          </div>
          <div class="flex gap-1 mb-3">${'<i class="fas fa-star text-fju-yellow text-sm"></i>'.repeat(5)}</div>
          <p class="text-gray-500 text-sm">「${t.msg}」</p>
        </div>`).join('')}
      </div>
    </div>
  </section>

  <!-- News Ticker -->
  <section id="news" class="py-12 bg-fju-blue">
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center gap-4">
        <span class="shrink-0 px-4 py-2 rounded-full bg-fju-yellow text-fju-blue font-bold text-sm"><i class="fas fa-bullhorn mr-2"></i>最新公告</span>
        <div class="overflow-hidden flex-1">
          <div id="news-ticker" class="flex gap-16 whitespace-nowrap" style="animation: ticker 30s linear infinite;">
            <span class="text-white/80">📢 114學年度第二學期社團場地申請已開放</span>
            <span class="text-white/80">🏆 第33屆金乐獎報名截止日：2026/04/30</span>
            <span class="text-white/80">📋 新版活動企劃書範本已上架 AI 企劃生成器</span>
            <span class="text-white/80">🎉 FJU Smart Hub 2.0 正式上線</span>
          </div>
        </div>
      </div>
    </div>
    <style>@keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }</style>
  </section>

  <!-- Footer -->
  <footer class="py-16 bg-fju-blue text-white">
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid md:grid-cols-3 gap-12 mb-12">
        <div>
          <div class="font-bold mb-2"><i class="fas fa-map-marker-alt mr-2 text-fju-yellow"></i>校址</div>
          <p class="text-white/50 text-sm">24205 新北市新莊區中正路510號</p>
        </div>
        <div class="text-center">
          <div class="font-bold mb-2"><i class="fab fa-facebook mr-2 text-fju-yellow"></i>粉專連結</div>
          <div class="flex gap-3 justify-center mt-2">
            <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-instagram"></i></a>
            <a href="#" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-fju-yellow/20 transition-colors"><i class="fab fa-line"></i></a>
          </div>
        </div>
        <div class="text-right">
          <div class="font-bold mb-2"><i class="fas fa-phone mr-2 text-fju-yellow"></i>櫃台資訊</div>
          <p class="text-white/50 text-sm">(02) 2905-2237<br>activity@mail.fju.edu.tw</p>
        </div>
      </div>
      <div class="border-t border-white/10 pt-8 text-center text-white/40 text-sm">
        <p>天主教輔仁大學 &copy; 2014-2024 版權所有</p>
      </div>
    </div>
  </footer>

  <!-- GSAP Animations -->
  <script>
    window.addEventListener('scroll', () => {
      const nav = document.getElementById('navbar');
      if (window.scrollY > 80) { nav.classList.add('glassmorphism', 'shadow-lg'); nav.style.background = ''; }
      else { nav.classList.remove('glassmorphism', 'shadow-lg'); nav.style.background = 'transparent'; }
    });

    gsap.registerPlugin(ScrollTrigger);
    const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' }});
    heroTl.to('#hero-badge', { opacity: 1, y: 0, duration: 0.8, delay: 0.3 })
      .to('#hero-title', { opacity: 1, y: 0, duration: 0.8 }, '-=0.4')
      .to('#hero-subtitle', { opacity: 1, y: 0, duration: 0.6 }, '-=0.3')
      .to('#hero-desc', { opacity: 1, y: 0, duration: 0.6 }, '-=0.3')
      .to('#hero-btns', { opacity: 1, y: 0, duration: 0.6 }, '-=0.3')
      .to('#hero-stats', { opacity: 1, y: 0, duration: 0.8 }, '-=0.2');

    document.querySelectorAll('.counter').forEach(el => {
      const target = parseInt(el.dataset.target);
      gsap.to(el, { innerText: target, duration: 2, delay: 1.5, snap: { innerText: 1 }, scrollTrigger: { trigger: el, start: 'top 90%' }});
    });

    let currentSlide = 0;
    const slides = document.querySelectorAll('.hero-slide');
    setInterval(() => { slides[currentSlide].style.opacity = '0'; currentSlide = (currentSlide + 1) % slides.length; slides[currentSlide].style.opacity = '1'; }, 5000);

    gsap.utils.toArray('.feature-card').forEach((card, i) => { gsap.to(card, { opacity: 1, y: 0, duration: 0.6, delay: i * 0.15, scrollTrigger: { trigger: card, start: 'top 85%' }}); });
    gsap.utils.toArray('.module-card').forEach((card, i) => { gsap.to(card, { opacity: 1, y: 0, scale: 1, duration: 0.5, delay: i * 0.08, scrollTrigger: { trigger: card, start: 'top 90%' }}); });
    gsap.utils.toArray('.testimonial-card').forEach((card, i) => { gsap.to(card, { opacity: 1, y: 0, duration: 0.6, delay: i * 0.2, scrollTrigger: { trigger: card, start: 'top 85%' }}); });

    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', e => { e.preventDefault(); const target = document.querySelector(a.getAttribute('href')); if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' }); });
    });
  </script>
  `
  return layout('首頁', body)
}
