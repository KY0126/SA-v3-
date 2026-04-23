@extends('layouts.shell')
@section('title', '全方位 2FA')
@php $activePage = '2fa'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-lock mr-2 text-fju-yellow"></i>全方位 2FA 雙因素認證</h2>
    <select id="tfa-sort" onchange="renderDevices()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
      <option value="newest">最新優先</option><option value="oldest">最舊優先</option><option value="type">依類型</option>
    </select>
  </div>

  {{-- 2FA Status Overview --}}
  <div class="grid md:grid-cols-4 gap-4">
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
      <div class="text-2xl font-black text-fju-green"><i class="fas fa-shield-alt"></i></div>
      <div class="text-xs text-gray-400 mt-1">2FA 狀態</div>
      <div class="text-sm font-bold text-fju-green mt-1">已啟用</div>
    </div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
      <div class="text-2xl font-black text-fju-blue" id="tfa-device-count">2</div>
      <div class="text-xs text-gray-400 mt-1">已綁定裝置</div>
    </div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
      <div class="text-2xl font-black text-fju-yellow" id="tfa-last-verify">今天</div>
      <div class="text-xs text-gray-400 mt-1">最近驗證</div>
    </div>
    <div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover">
      <div class="text-2xl font-black text-fju-green">100%</div>
      <div class="text-xs text-gray-400 mt-1">安全評分</div>
    </div>
  </div>

  {{-- 3 Authentication Methods --}}
  <div class="grid md:grid-cols-3 gap-4">
    {{-- TOTP --}}
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-fju bg-fju-blue flex items-center justify-center"><i class="fas fa-key text-white"></i></div>
        <div><h3 class="font-bold text-fju-blue text-sm">TOTP 驗證碼</h3><span class="text-[10px] text-fju-green"><i class="fas fa-check-circle mr-1"></i>已啟用</span></div>
      </div>
      <p class="text-xs text-gray-500 mb-3">使用 Google Authenticator 或 Microsoft Authenticator 產生的一次性驗證碼登入。每 30 秒自動更新一組 6 位數碼。</p>
      <div class="bg-fju-bg rounded-fju p-3 mb-3 text-center">
        <div class="text-3xl font-mono font-black text-fju-blue tracking-widest" id="totp-code">483 291</div>
        <div class="flex items-center justify-center gap-2 mt-1">
          <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-fju-yellow rounded-full transition-all duration-1000" id="totp-progress" style="width:100%"></div></div>
          <span class="text-[10px] text-gray-400" id="totp-timer">30s</span>
        </div>
      </div>
      <button onclick="regenerateTOTP()" class="w-full py-2 rounded-fju border border-fju-blue text-fju-blue text-xs hover:bg-fju-blue hover:text-white transition-all"><i class="fas fa-sync-alt mr-1"></i>重新產生密鑰</button>
    </div>

    {{-- Outlook Email --}}
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-fju bg-fju-yellow flex items-center justify-center"><i class="fas fa-envelope text-fju-blue"></i></div>
        <div><h3 class="font-bold text-fju-blue text-sm">Outlook 信箱驗證</h3><span class="text-[10px] text-fju-green"><i class="fas fa-check-circle mr-1"></i>已啟用</span></div>
      </div>
      <p class="text-xs text-gray-500 mb-3">系統將發送 6 位數驗證碼到您的 Outlook 學校信箱 (學號@cloud.fju.edu.tw)，輸入驗證碼完成登入。</p>
      <div class="space-y-2 mb-3">
        <div class="flex items-center gap-2 p-2 rounded-fju bg-fju-bg text-xs"><i class="fas fa-envelope text-fju-yellow mr-1"></i><span class="text-gray-600">410XXXXXX@cloud.fju.edu.tw</span><span class="ml-auto px-2 py-0.5 rounded-full bg-fju-green/10 text-fju-green text-[10px]">已綁定</span></div>
      </div>
      <button onclick="sendEmailOTP()" class="w-full py-2 rounded-fju border border-fju-yellow text-fju-blue text-xs hover:bg-fju-yellow transition-all"><i class="fas fa-paper-plane mr-1"></i>發送測試驗證碼</button>
      <div id="email-otp-result" class="hidden mt-2 p-2 rounded-fju bg-fju-green/10 text-fju-green text-xs text-center"></div>
    </div>

    {{-- SMS --}}
    <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-fju bg-fju-green flex items-center justify-center"><i class="fas fa-sms text-white"></i></div>
        <div><h3 class="font-bold text-fju-blue text-sm">SMS 簡訊驗證</h3><span class="text-[10px] text-fju-green"><i class="fas fa-check-circle mr-1"></i>已啟用</span></div>
      </div>
      <p class="text-xs text-gray-500 mb-3">系統將發送 6 位數驗證碼到您的手機號碼。適用於無法使用 APP 或信箱的緊急情況。</p>
      <div class="space-y-2 mb-3">
        <div class="flex items-center gap-2 p-2 rounded-fju bg-fju-bg text-xs"><i class="fas fa-mobile-alt text-fju-green mr-1"></i><span class="text-gray-600">09XX-XXX-XXX</span><span class="ml-auto px-2 py-0.5 rounded-full bg-fju-green/10 text-fju-green text-[10px]">已綁定</span></div>
      </div>
      <button onclick="sendSMSOTP()" class="w-full py-2 rounded-fju border border-fju-green text-fju-green text-xs hover:bg-fju-green hover:text-white transition-all"><i class="fas fa-sms mr-1"></i>發送測試簡訊</button>
      <div id="sms-otp-result" class="hidden mt-2 p-2 rounded-fju bg-fju-green/10 text-fju-green text-xs text-center"></div>
    </div>
  </div>

  {{-- Verified Devices --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-laptop mr-2 text-fju-yellow"></i>已驗證裝置</h3>
      <button onclick="revokeAll()" class="text-fju-red text-xs hover:underline"><i class="fas fa-ban mr-1"></i>撤銷所有裝置</button>
    </div>
    <div id="device-list"></div>
  </div>

  {{-- Backup Codes --}}
  <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-3"><i class="fas fa-key mr-2 text-fju-yellow"></i>備用復原碼</h3>
    <p class="text-xs text-gray-500 mb-3">當無法使用任何驗證方式時，可使用備用碼登入。每組碼只能使用一次，請妥善保管。</p>
    <div id="backup-codes" class="grid grid-cols-2 md:grid-cols-4 gap-2 mb-3"></div>
    <div class="flex gap-2">
      <button onclick="generateBackupCodes()" class="btn-yellow px-4 py-2 text-xs"><i class="fas fa-sync-alt mr-1"></i>重新產生</button>
      <button onclick="copyBackupCodes()" class="px-4 py-2 rounded-fju border border-gray-200 text-xs text-gray-500 hover:bg-gray-50"><i class="fas fa-copy mr-1"></i>複製全部</button>
    </div>
  </div>
</div>

<script>
let totpSec=30;
const devices=[
  {id:1,name:'Chrome on MacBook Pro',type:'browser',ip:'140.136.xxx.xxx',lastUsed:'2026-04-23 09:15',current:true},
  {id:2,name:'Safari on iPhone 15',type:'mobile',ip:'140.136.xxx.xxx',lastUsed:'2026-04-22 18:30',current:false},
];

function renderDevices(){
  let data=[...devices];
  const sort=document.getElementById('tfa-sort')?.value||'newest';
  if(sort==='oldest') data.reverse();
  else if(sort==='type') data.sort((a,b)=>a.type.localeCompare(b.type));
  document.getElementById('device-list').innerHTML=data.map(d=>`
    <div class="px-5 py-3 border-b border-gray-50 flex items-center justify-between hover:bg-gray-50">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-fju ${d.type==='mobile'?'bg-fju-green/10':'bg-fju-blue/10'} flex items-center justify-center">
          <i class="fas ${d.type==='mobile'?'fa-mobile-alt text-fju-green':'fa-laptop text-fju-blue'} text-sm"></i>
        </div>
        <div>
          <div class="text-sm font-medium text-fju-blue">${d.name} ${d.current?'<span class="px-1.5 py-0.5 rounded-full bg-fju-green/10 text-fju-green text-[9px]">目前裝置</span>':''}</div>
          <div class="text-[10px] text-gray-400">IP: ${d.ip} · 最後使用: ${d.lastUsed}</div>
        </div>
      </div>
      ${!d.current?`<button onclick="revokeDevice(${d.id})" class="text-fju-red text-xs hover:underline"><i class="fas fa-times mr-1"></i>撤銷</button>`:''}
    </div>
  `).join('');
}

// TOTP countdown
setInterval(()=>{
  totpSec--;
  if(totpSec<=0){totpSec=30;document.getElementById('totp-code').textContent=Math.floor(100000+Math.random()*900000).toString().replace(/(.{3})/,'$1 ')}
  document.getElementById('totp-progress').style.width=(totpSec/30*100)+'%';
  document.getElementById('totp-timer').textContent=totpSec+'s';
  if(totpSec<=5){document.getElementById('totp-progress').classList.add('bg-fju-red');document.getElementById('totp-progress').classList.remove('bg-fju-yellow')}
  else{document.getElementById('totp-progress').classList.remove('bg-fju-red');document.getElementById('totp-progress').classList.add('bg-fju-yellow')}
},1000);

function regenerateTOTP(){ document.getElementById('totp-code').textContent=Math.floor(100000+Math.random()*900000).toString().replace(/(.{3})/,'$1 '); totpSec=30; alert('TOTP 密鑰已重新產生。請重新在 Authenticator APP 掃描 QR Code。'); }

function sendEmailOTP(){
  const el=document.getElementById('email-otp-result'); el.classList.remove('hidden');
  el.innerHTML='<i class="fas fa-check-circle mr-1"></i>驗證碼已發送至 410XXXXXX@cloud.fju.edu.tw，請於 5 分鐘內輸入。';
}

function sendSMSOTP(){
  const el=document.getElementById('sms-otp-result'); el.classList.remove('hidden');
  el.innerHTML='<i class="fas fa-check-circle mr-1"></i>驗證碼已發送至 09XX-XXX-XXX，請於 5 分鐘內輸入。';
}

function revokeDevice(id){ if(!confirm('確定撤銷此裝置？'))return; alert('裝置已撤銷。'); }
function revokeAll(){ if(!confirm('確定撤銷所有裝置？這將導致您需要重新登入。'))return; alert('所有裝置已撤銷。'); }

let backupCodes=[];
function generateBackupCodes(){
  backupCodes=Array.from({length:8},()=>Math.random().toString(36).substr(2,8).toUpperCase());
  document.getElementById('backup-codes').innerHTML=backupCodes.map(c=>`<div class="px-3 py-2 rounded-fju bg-fju-bg text-center font-mono text-sm text-fju-blue border border-gray-200">${c}</div>`).join('');
}
function copyBackupCodes(){ navigator.clipboard.writeText(backupCodes.join('\n')).then(()=>alert('備用碼已複製到剪貼簿')); }

renderDevices();
generateBackupCodes();
</script>
@endsection
