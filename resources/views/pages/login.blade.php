@extends('layouts.app')
@section('title', '登入')
@section('body')
<div class="min-h-screen relative flex items-center justify-center overflow-hidden" style="background: linear-gradient(135deg, rgba(0,49,83,0.9), rgba(0,49,83,0.7)), url('https://images.unsplash.com/photo-1562774053-701939374585?w=1920&q=80') center/cover;">
  <div class="absolute inset-0 overflow-hidden pointer-events-none"><div class="absolute w-96 h-96 rounded-full bg-fju-yellow/5 -top-20 -left-20 animate-float"></div><div class="absolute w-64 h-64 rounded-full bg-white/5 bottom-10 right-10 animate-float" style="animation-delay:1s"></div></div>
  <a href="/" class="absolute top-6 left-6 z-20 flex items-center gap-2 text-white/70 hover:text-white transition-colors"><i class="fas fa-arrow-left"></i><span class="text-sm">返回首頁</span></a>
  <div class="relative z-10 w-full max-w-md mx-4">
    <div class="text-center mb-8" id="login-header"><div class="w-20 h-20 mx-auto rounded-2xl bg-fju-yellow flex items-center justify-center shadow-2xl mb-4 animate-pulse-yellow"><i class="fas fa-university text-fju-blue text-3xl"></i></div><h1 class="text-3xl font-black text-white mb-2">FJU Smart Hub</h1><p class="text-white/60 text-sm">天主教輔仁大學 · 課外活動指導組</p></div>
    <div class="glassmorphism rounded-[15px] p-8 shadow-2xl" id="login-box">
      <div class="absolute -top-4 -right-4 w-16 h-16 rounded-full bg-fju-yellow flex items-center justify-center shadow-lg transform rotate-12 hover:rotate-0 transition-transform" id="login-dog-fab"></div>

      {{-- TAB NAVIGATION (登入/忘記密碼/建立帳戶 按鈕在登入表單下方，此處僅保留隱性 Tab 切換用) --}}
      <div id="tab-nav" class="hidden"></div>

      {{-- LOGIN FORM --}}
      <div id="form-login">
        <button onclick="openMsLogin()" class="w-full flex items-center justify-center gap-3 px-6 py-3.5 bg-[#0078D4] rounded-[12px] border-2 border-[#0078D4] hover:bg-[#106EBE] hover:shadow-lg transition-all mb-3 text-white">
          <svg class="w-5 h-5" viewBox="0 0 21 21"><rect width="10" height="10" fill="#f25022"/><rect x="11" width="10" height="10" fill="#7fba00"/><rect y="11" width="10" height="10" fill="#00a4ef"/><rect x="11" y="11" width="10" height="10" fill="#ffb900"/></svg>
          <span class="font-medium">使用 Microsoft 學校帳號登入</span>
        </button>
        <p class="text-center text-xs text-gray-400 mb-4">僅接受 @mail.fju.edu.tw 或 @fju.edu.tw 帳號</p>
        {{-- MS OAuth simulation modal --}}
        <div id="ms-modal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center">
          <div class="bg-white rounded-2xl p-8 w-full max-w-sm mx-4 shadow-2xl">
            <div class="text-center mb-6">
              <svg class="w-10 h-10 mx-auto mb-3" viewBox="0 0 21 21"><rect width="10" height="10" fill="#f25022"/><rect x="11" width="10" height="10" fill="#7fba00"/><rect y="11" width="10" height="10" fill="#00a4ef"/><rect x="11" y="11" width="10" height="10" fill="#ffb900"/></svg>
              <h3 class="font-bold text-gray-800 text-lg">Microsoft 帳號登入</h3>
              <p class="text-xs text-gray-400">輔仁大學 (@mail.fju.edu.tw)</p>
            </div>
            <div class="space-y-3">
              <div>
                <label class="text-xs text-gray-500 font-medium">學校電子郵件</label>
                <input id="ms-email" type="email" placeholder="學號@mail.fju.edu.tw" class="w-full mt-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#0078D4] outline-none">
              </div>
              <div>
                <label class="text-xs text-gray-500 font-medium">姓名</label>
                <input id="ms-name" type="text" placeholder="您的姓名" class="w-full mt-1 px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#0078D4] outline-none">
              </div>
            </div>
            <div id="ms-error" class="hidden mt-3 p-3 rounded-xl bg-red-50 text-red-600 text-xs"></div>
            <div class="flex gap-2 mt-5">
              <button onclick="doMsLogin()" class="flex-1 py-2.5 rounded-xl bg-[#0078D4] text-white text-sm font-bold hover:bg-[#106EBE]">登入</button>
              <button onclick="document.getElementById('ms-modal').classList.add('hidden')" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-500">取消</button>
            </div>
          </div>
        </div>
        <div class="flex items-center gap-4 mb-6"><div class="flex-1 border-t border-gray-200"></div><span class="text-gray-400 text-xs">或使用帳號密碼</span><div class="flex-1 border-t border-gray-200"></div></div>
        <form onsubmit="event.preventDefault(); doLogin()">
          <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label><div class="relative"><i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="login-uid" placeholder="例：410XXXXXX" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-4"><label class="block text-sm font-medium text-gray-600 mb-1">密碼</label><div class="relative"><i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="password" id="login-pwd" placeholder="請輸入密碼" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-4 rounded-[12px] border border-gray-200 p-3 bg-gray-50 flex items-center gap-3"><div class="w-6 h-6 rounded border-2 border-gray-300 flex items-center justify-center cursor-pointer hover:border-fju-green" id="captcha-box" onclick="this.innerHTML='<i class=\'fas fa-check text-fju-green text-xs\'></i>';this.dataset.checked='1'"></div><span class="text-sm text-gray-500">我不是機器人</span></div>
          <div id="login-error" class="hidden mb-3 p-3 rounded-[12px] bg-red-50 text-red-600 text-xs"><i class="fas fa-exclamation-circle mr-1"></i><span></span></div>
          <div id="login-success" class="hidden mb-3 p-3 rounded-[12px] bg-green-50 text-green-600 text-xs"><i class="fas fa-check-circle mr-1"></i><span></span></div>
          <button type="submit" class="w-full btn-yellow py-3.5 text-base mb-3"><i class="fas fa-sign-in-alt mr-2"></i>登入</button>
        </form>
        {{-- 忘記密碼 & 建立帳戶 直接按鈕 --}}
        <div class="grid grid-cols-2 gap-2 mt-1">
          <button onclick="switchTab('forgot')" class="w-full py-2.5 rounded-[12px] border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 hover:border-fju-blue hover:text-fju-blue transition-all"><i class="fas fa-key mr-1 text-gray-400"></i>忘記密碼</button>
          <button onclick="switchTab('register')" class="w-full py-2.5 rounded-[12px] border border-fju-blue text-sm text-fju-blue hover:bg-fju-blue hover:text-white transition-all"><i class="fas fa-user-plus mr-1"></i>建立帳戶</button>
        </div>
      </div>

      {{-- REGISTER FORM --}}
      <div id="form-register" class="hidden">
        <form onsubmit="event.preventDefault(); doRegister()">
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">姓名</label><div class="relative"><i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="reg-name" placeholder="請輸入真實姓名" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label><div class="relative"><i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="text" id="reg-uid" placeholder="例：410XXXXXX" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">電子信箱</label><div class="relative"><i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i><input type="email" id="reg-email" placeholder="例：410xxx@cloud.fju.edu.tw" class="w-full pl-11 pr-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm" required></div></div>
          <div class="mb-3"><label class="block text-sm font-medium text-gray-600 mb-1">身分類型</label><select id="reg-role" class="w-full px-4 py-3 rounded-[12px] border border-gray-200 focus:border-fju-blue focus:ring-2 focus:ring-fju-blue/20 outline-none text-sm"><option value="student">一般學生/社員</option><option value="officer">社團幹部/學生自治組織</option><option value="professor">指導教授</option><option value="staff">處室職員</option><option value="admin">課指組審核員</option></select><p class="text-[10px] text-gray-400 mt-1"><i class="fas fa-info-circle mr-1"></i>教職員信箱（@fju.edu.tw）系統將自動驗證身份；社團幹部身份由課指組確認後賦予。</p></div>
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
// Random dog avatar for login page (same session logic)
(function(){
  const dogs=[
    {name:'雜摳',bg:'#f5c6a0',emoji:'🐕'},{name:'Dear',bg:'#3d2b1f',emoji:'🐾'},
    {name:'帥帥',bg:'#8b6f47',emoji:'🐕'},{name:'哲哲',bg:'#1a1a1a',emoji:'🐾'},
    {name:'孟軒',bg:'#2c2c2c',emoji:'🐕'},{name:'大S',bg:'#6b6b6b',emoji:'🐾'},
    {name:'迷你熊',bg:'#3b2314',emoji:'🐕'},{name:'阿毛',bg:'#c4a882',emoji:'🐾'},
  ];
  let idx=sessionStorage.getItem('_fjuDogIdx');
  if(idx===null){idx=Math.floor(Math.random()*dogs.length);sessionStorage.setItem('_fjuDogIdx',idx);}
  const d=dogs[parseInt(idx)];
  const el=document.getElementById('login-dog-fab');
  if(el) el.innerHTML=`<div style="width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;"><span style="font-size:22px;line-height:1">${d.emoji}</span><span style="font-size:8px;color:#003153;font-weight:bold">${d.name}</span></div>`;
})();

function switchTab(tab){
  ['login','register','forgot'].forEach(t=>{
    document.getElementById('form-'+t).classList.toggle('hidden',t!==tab);
  });
}
function showMsg(prefix,type,msg){
  const el=document.getElementById(prefix+'-'+type);
  el.querySelector('span').textContent=msg;el.classList.remove('hidden');
  const other=type==='error'?'success':'error';
  document.getElementById(prefix+'-'+other)?.classList.add('hidden');
  setTimeout(()=>el.classList.add('hidden'),5000);
}
function openMsLogin(){document.getElementById('ms-modal').classList.remove('hidden');document.getElementById('ms-email').focus();}
function doMsLogin(){
  const email=document.getElementById('ms-email').value.trim();
  const name=document.getElementById('ms-name').value.trim()||'FJU User';
  if(!email){document.getElementById('ms-error').textContent='請輸入學校電子郵件';document.getElementById('ms-error').classList.remove('hidden');return;}
  fetch('/api/auth/ms-login',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({email,name})})
    .then(r=>r.json()).then(res=>{
      if(res.success){
        localStorage.setItem('fju_token',res.token);
        localStorage.setItem('fju_user',JSON.stringify(res.user));
        window.location.href='/dashboard?role='+res.user.role;
      } else {
        document.getElementById('ms-error').textContent=res.error||'登入失敗';
        document.getElementById('ms-error').classList.remove('hidden');
      }
    }).catch(()=>{document.getElementById('ms-error').textContent='網路錯誤，請稍後再試';document.getElementById('ms-error').classList.remove('hidden');});
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
