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

      {{-- TAB NAVIGATION --}}
      <div class="flex border-b border-gray-200 mb-6">
        <button onclick="switchTab('login')" id="tab-login" class="flex-1 pb-3 text-sm font-bold text-fju-blue border-b-2 border-fju-blue transition-all">登入</button>
        <button onclick="switchTab('register')" id="tab-register" class="flex-1 pb-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue transition-all">註冊</button>
        <button onclick="switchTab('forgot')" id="tab-forgot" class="flex-1 pb-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue transition-all">忘記密碼</button>
      </div>

      {{-- LOGIN FORM --}}
      <div id="form-login">
        <button onclick="window.location.href='/dashboard?role=student'" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-[#0078D4] rounded-[12px] border-2 border-[#0078D4] hover:bg-[#106EBE] hover:shadow-lg transition-all mb-3 text-white">
          <svg class="w-5 h-5" viewBox="0 0 21 21"><rect width="10" height="10" fill="#f25022"/><rect x="11" width="10" height="10" fill="#7fba00"/><rect y="11" width="10" height="10" fill="#00a4ef"/><rect x="11" y="11" width="10" height="10" fill="#ffb900"/></svg>
          <span class="font-medium">使用 Outlook 學校帳號登入</span>
        </button>
        <p class="text-center text-xs text-gray-400 mb-4">請使用 學號@cloud.fju.edu.tw 帳號登入</p>
        <div class="flex items-center gap-4 mb-6"><div class="flex-1 border-t border-gray-200"></div><span class="text-gray-400 text-xs">或使用帳號密碼</span><div class="flex-1 border-t border-gray-200"></div></div>
        <form onsubmit="event.preventDefault(); doLogin()">
          <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label><div class="relative"><i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="login-uid" placeholder="例：410XXXXXX" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">密碼</label><div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" id="login-pwd" placeholder="請輸入密碼" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-4 rounded-[12px] border border-gray-200 p-3 bg-gray-50 flex items-center gap-3"><div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center cursor-pointer hover:border-fju-green" id="captcha-box" onclick="this.innerHTML='<i class=\'fas fa-check text-fju-green text-xs\'></i>';this.dataset.checked='1'"></div><span class="text-sm text-gray-500">我不是機器人</span></div>
          <div id="login-error" class="hidden mb-3 p-3 rounded-[12px] bg-red-50 text-red-600 text-xs"><i class="fas fa-exclamation-circle mr-1"></i><span></span></div>
          <div id="login-success" class="hidden mb-3 p-3 rounded-[12px] bg-green-50 text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i><span></span></div>
          <button type="submit" class="w-full btn-yellow py-3.5 text-base mb-4"><i class="fas fa-sign-in-alt mr-2"></i>登入</button>
        </form>
      </div>

      {{-- REGISTER FORM --}}
      <div id="form-register" class="hidden">
        <form onsubmit="event.preventDefault(); doRegister()">
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">姓名</label><div class="relative"><i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="reg-name" placeholder="請輸入真實姓名" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label><div class="relative"><i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="reg-uid" placeholder="例：410XXXXXX" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">電子信箱</label><div class="relative"><i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="email" id="reg-email" placeholder="例：410xxx@cloud.fju.edu.tw" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">身分類型</label><select id="reg-role" class="w-full px-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm"><option value="student">一般學生/社員</option><option value="officer">社團幹部/學生自治組織</option><option value="professor">指導教授</option><option value="admin">課指組/處室職員</option></select></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">密碼</label><div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" id="reg-pwd" placeholder="至少 8 位元" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" minlength="8" required></div></div>
          <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">確認密碼</label><div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" id="reg-pwd2" placeholder="再次輸入密碼" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div id="reg-error" class="hidden mb-3 p-3 rounded-[12px] bg-red-50 text-red-600 text-xs"><i class="fas fa-exclamation-circle mr-1"></i><span></span></div>
          <div id="reg-success" class="hidden mb-3 p-3 rounded-[12px] bg-green-50 text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i><span></span></div>
          <button type="submit" class="w-full btn-yellow py-3.5 text-base"><i class="fas fa-user-plus mr-2"></i>註冊帳號</button>
        </form>
        <p class="text-center text-xs text-gray-400 mt-4">已有帳號？<a href="#" onclick="switchTab('login')" class="text-fju-blue font-bold hover:underline">前往登入</a></p>
      </div>

      {{-- FORGOT PASSWORD FORM --}}
      <div id="form-forgot" class="hidden">
        <div class="text-center mb-4"><i class="fas fa-key text-fju-yellow text-3xl mb-2"></i><p class="text-sm text-gray-500">輸入您的學號或信箱，系統將寄送密碼重設連結</p></div>
        <form onsubmit="event.preventDefault(); doForgot()">
          <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 電子信箱</label><div class="relative"><i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="forgot-uid" placeholder="請輸入學號或 @cloud.fju.edu.tw 信箱" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div id="forgot-error" class="hidden mb-3 p-3 rounded-[12px] bg-red-50 text-red-600 text-xs"><i class="fas fa-exclamation-circle mr-1"></i><span></span></div>
          <div id="forgot-success" class="hidden mb-3 p-3 rounded-[12px] bg-green-50 text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i><span></span></div>
          <button type="submit" class="w-full btn-yellow py-3.5 text-base mb-4"><i class="fas fa-paper-plane mr-2"></i>寄送重設連結</button>
        </form>
        <p class="text-center text-xs text-gray-400">記起密碼了？<a href="#" onclick="switchTab('login')" class="text-fju-blue font-bold hover:underline">返回登入</a></p>
      </div>

      {{-- QUICK ROLE BUTTONS (preserved) --}}
      <div class="mt-6 pt-6 border-t border-gray-100">
        <p class="text-xs text-gray-400 text-center mb-3">快速體驗不同角色</p>
        <div class="grid grid-cols-3 gap-2">
          @foreach([['admin','課指組','fas fa-user-tie','bg-fju-blue'],['officer','借用者(幹部)','fas fa-user-shield','bg-fju-yellow'],['student','學生(檢視)','fas fa-user-graduate','bg-fju-blue']] as $r)
          <a href="/dashboard?role={{ $r[0] }}" class="flex flex-col items-center gap-1 p-2 rounded-[12px] hover:bg-fju-blue/5 transition-colors group"><div class="w-8 h-8 rounded-lg {{ $r[3] }} flex items-center justify-center"><i class="{{ $r[2] }} {{ $r[3]==='bg-fju-yellow' ? 'text-fju-blue' : 'text-white' }} text-xs"></i></div><span class="text-[10px] text-gray-400 group-hover:text-fju-blue">{{ $r[1] }}</span></a>
          @endforeach
        </div>
      </div>
    </div>
    <p class="text-center text-white/40 text-xs mt-6">僅限 @cloud.fju.edu.tw 帳號登入 | <i class="fas fa-shield-alt mr-1"></i>Cloudflare Protected</p>
  </div>
</div>
<script>
function switchTab(tab){
  ['login','register','forgot'].forEach(t=>{
    document.getElementById('form-'+t).classList.toggle('hidden',t!==tab);
    const btn=document.getElementById('tab-'+t);
    if(t===tab){btn.className='flex-1 pb-3 text-sm font-bold text-fju-blue border-b-2 border-fju-blue transition-all'}
    else{btn.className='flex-1 pb-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue transition-all'}
  });
}
function showMsg(prefix,type,msg){
  const el=document.getElementById(prefix+'-'+type);
  el.querySelector('span').textContent=msg;el.classList.remove('hidden');
  const other=type==='error'?'success':'error';
  document.getElementById(prefix+'-'+other)?.classList.add('hidden');
  setTimeout(()=>el.classList.add('hidden'),5000);
}
function doLogin(){
  const uid=document.getElementById('login-uid').value.trim();
  const pwd=document.getElementById('login-pwd').value;
  const captcha=document.getElementById('captcha-box').dataset.checked;
  if(!uid||!pwd){showMsg('login','error','請填寫學號和密碼');return}
  if(!captcha){showMsg('login','error','請勾選「我不是機器人」');return}
  // Simulate login API
  fetch('/api/auth/login',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({uid,password:pwd})})
    .then(r=>r.json()).then(res=>{
      if(res.success){showMsg('login','success','登入成功！正在跳轉...');setTimeout(()=>window.location.href='/dashboard?role='+(res.role||'student'),800)}
      else{showMsg('login','error',res.message||'登入失敗')}
    }).catch(()=>{showMsg('login','error','系統連線失敗，請稍後再試')});
}
function doRegister(){
  const name=document.getElementById('reg-name').value.trim();
  const uid=document.getElementById('reg-uid').value.trim();
  const email=document.getElementById('reg-email').value.trim();
  const role=document.getElementById('reg-role').value;
  const pwd=document.getElementById('reg-pwd').value;
  const pwd2=document.getElementById('reg-pwd2').value;
  if(!name||!uid||!email||!pwd){showMsg('reg','error','請填寫所有欄位');return}
  if(pwd.length<8){showMsg('reg','error','密碼需至少 8 位元');return}
  if(pwd!==pwd2){showMsg('reg','error','兩次密碼不一致');return}
  if(!email.includes('@cloud.fju.edu.tw')&&!email.includes('@fju.edu.tw')){showMsg('reg','error','請使用輔仁大學電子信箱');return}
  fetch('/api/auth/register',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({name,uid,email,role,password:pwd})})
    .then(r=>r.json()).then(res=>{
      if(res.success){showMsg('reg','success','註冊成功！已寄送驗證信至 '+email+'，請確認後登入。');setTimeout(()=>switchTab('login'),2000)}
      else{showMsg('reg','error',res.message||'註冊失敗')}
    }).catch(()=>{showMsg('reg','error','系統連線失敗，請稍後再試')});
}
function doForgot(){
  const uid=document.getElementById('forgot-uid').value.trim();
  if(!uid){showMsg('forgot','error','請輸入學號或信箱');return}
  fetch('/api/auth/forgot-password',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({uid})})
    .then(r=>r.json()).then(res=>{
      if(res.success){showMsg('forgot','success','已寄送重設連結至您的輔大信箱，請查收。')}
      else{showMsg('forgot','error',res.message||'查無此帳號')}
    }).catch(()=>{showMsg('forgot','error','系統連線失敗，請稍後再試')});
}
gsap.from('#login-header',{opacity:0,y:-30,duration:0.8,ease:'power3.out'});gsap.from('#login-box',{opacity:0,y:30,scale:0.95,duration:0.8,delay:0.3,ease:'power3.out'});
</script>
@endsection
