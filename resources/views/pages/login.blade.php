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
      <button onclick="window.location.href='/dashboard?role=student'" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-white rounded-[12px] border-2 border-gray-200 hover:border-fju-blue hover:shadow-lg transition-all mb-6">
        <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        <span class="text-gray-700 font-medium">使用 FJU Google 帳號登入</span>
      </button>
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
