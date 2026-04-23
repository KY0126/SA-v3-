@extends('layouts.app')
@section('title', '登入')
@section('body')
<div class="min-h-screen relative flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, rgba(0,49,83,0.9), rgba(0,49,83,0.7)), url('https://images.unsplash.com/photo-1562774053-701939374585?w=1920&q=80') center/cover;">
  <div class="absolute inset-0 overflow-hidden pointer-events-none"><div class="absolute w-96 h-96 rounded-full bg-fju-yellow/5 -top-20 -left-20 animate-float"></div><div class="absolute w-64 h-64 rounded-full bg-white/5 bottom-10 right-10 animate-float" style="animation-delay:1s"></div></div>
  <a href="/" class="absolute top-6 left-6 z-20 flex items-center gap-2 text-white/70 hover:text-white transition-colors"><i class="fas fa-arrow-left"></i><span class="text-sm">返回首頁</span></a>
  <div class="relative z-10 w-full max-w-md mx-4">
    <div class="text-center mb-8" id="login-header"><div class="w-20 h-20 mx-auto rounded-2xl bg-fju-yellow flex items-center justify-center shadow-2xl mb-4 animate-pulse-yellow"><i class="fas fa-university text-fju-blue text-3xl"></i></div><h1 class="text-3xl font-black text-white mb-2">FJU Smart Hub</h1><p class="text-white/60 text-sm">天主教輔仁大學 · 課外活動指導組</p></div>
    <div class="glassmorphism rounded-[15px] p-8 shadow-2xl" id="login-box">
      <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-fju-yellow flex items-center justify-center shadow-lg transform rotate-12 hover:rotate-0 transition-transform"><span class="text-2xl">🐕</span></div>
      <h2 class="text-xl font-bold text-fju-blue mb-6 text-center">歡迎登入</h2>
      <button onclick="window.location.href='/dashboard?role=student'" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-[#0078D4] rounded-[12px] border-2 border-[#0078D4] hover:bg-[#106EBE] hover:shadow-lg transition-all mb-3 text-white">
        <svg class="w-5 h-5" viewBox="0 0 21 21"><rect width="10" height="10" fill="#f25022"/><rect x="11" width="10" height="10" fill="#7fba00"/><rect y="11" width="10" height="10" fill="#00a4ef"/><rect x="11" y="11" width="10" height="10" fill="#ffb900"/></svg>
        <span class="font-medium">使用 Outlook 學校帳號登入</span>
      </button>
      <p class="text-center text-xs text-gray-400 mb-4">請使用 學號@cloud.fju.edu.tw 帳號登入</p>
      <div class="flex items-center gap-4 mb-6"><div class="flex-1 border-t border-gray-200"></div><span class="text-gray-400 text-xs">或使用帳號密碼</span><div class="flex-1 border-t border-gray-200"></div></div>
      <form onsubmit="event.preventDefault(); window.location.href='/dashboard?role=student'">
        <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label><div class="relative"><i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" placeholder="例：410XXXXXX" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm"></div></div>
        <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">密碼</label><div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" placeholder="請輸入密碼" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm"></div></div>
        <div class="mb-4 rounded-[12px] border border-gray-200 p-3 bg-gray-50 flex items-center gap-3"><div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center cursor-pointer hover:border-fju-green" onclick="this.innerHTML='<i class=\'fas fa-check text-fju-green text-xs\'></i>'"></div><span class="text-sm text-gray-500">我不是機器人</span></div>
        <button type="submit" class="w-full btn-yellow py-3.5 text-base mb-4"><i class="fas fa-sign-in-alt mr-2"></i>登入</button>
      </form>
      <div class="mt-6 pt-6 border-t border-gray-100">
        <p class="text-xs text-gray-400 text-center mb-3">快速體驗不同角色</p>
        <div class="grid grid-cols-5 gap-2">
          @foreach([['admin','課指組','fas fa-user-tie','bg-fju-blue'],['officer','社團幹部','fas fa-user-shield','bg-fju-yellow'],['professor','指導教授','fas fa-chalkboard-teacher','bg-fju-blue'],['student','一般學生','fas fa-user-graduate','bg-fju-yellow'],['it','資訊中心','fas fa-server','bg-fju-blue']] as $r)
          <a href="/dashboard?role={{ $r[0] }}" class="flex flex-col items-center gap-1 p-2 rounded-[12px] hover:bg-fju-blue/5 transition-colors group"><div class="w-8 h-8 rounded-lg {{ $r[3] }} flex items-center justify-center"><i class="{{ $r[2] }} {{ $r[3]==='bg-fju-yellow' ? 'text-fju-blue' : 'text-white' }} text-xs"></i></div><span class="text-[10px] text-gray-400 group-hover:text-fju-blue">{{ $r[1] }}</span></a>
          @endforeach
        </div>
      </div>
    </div>
    <p class="text-center text-white/40 text-xs mt-6">僅限 @cloud.fju.edu.tw 帳號登入 | <i class="fas fa-shield-alt mr-1"></i>Cloudflare Protected</p>
  </div>
</div>
<script>gsap.from('#login-header',{opacity:0,y:-30,duration:0.8,ease:'power3.out'});gsap.from('#login-box',{opacity:0,y:30,scale:0.95,duration:0.8,delay:0.3,ease:'power3.out'});</script>
@endsection
