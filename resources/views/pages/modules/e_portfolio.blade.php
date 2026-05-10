@extends('layouts.shell')
@section('title', '個人中心')
@php $activePage = 'personal-center'; @endphp
@section('content')
@php
$role = request('role', 'student');
$roleLabels = ['admin'=>'課指組','officer'=>'借用者（社團幹部）','professor'=>'借用者（教授）','student'=>'一般學生/社員'];
$roleLabel = $roleLabels[$role] ?? '一般學生/社員';
@endphp
<div class="space-y-6">
  {{-- Header --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="flex items-center gap-4">

      {{-- Avatar (clickable, hover overlay) --}}
      <div class="relative group cursor-pointer flex-shrink-0" onclick="document.getElementById('avatar-input').click()" title="更換頭貼">
        <div id="avatar-display" class="w-16 h-16 rounded-full bg-fju-blue flex items-center justify-center text-white text-2xl font-bold shadow-lg overflow-hidden">
          <img id="avatar-img" src="" alt="" class="hidden w-full h-full object-cover rounded-full">
          <span id="avatar-initial">D</span>
        </div>
        {{-- Hover overlay --}}
        <div class="absolute inset-0 rounded-full bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
          <i class="fas fa-camera text-white text-sm"></i>
          <span class="text-white text-[10px] mt-0.5 leading-tight">更換頭貼</span>
        </div>
      </div>
      <input id="avatar-input" type="file" accept="image/*" class="hidden" onchange="handleAvatarChange(event)">

      {{-- Name & info --}}
      <div class="flex-1 min-w-0">
        {{-- View mode --}}
        <div id="name-view" class="flex items-center gap-2 group/name">
          <h2 id="name-display" class="font-bold text-fju-blue text-xl">Demo User</h2>
          <button onclick="startNameEdit()" class="opacity-0 group-hover/name:opacity-100 transition-opacity text-gray-300 hover:text-fju-blue p-1" title="編輯名稱">
            <i class="fas fa-pencil-alt text-xs"></i>
          </button>
        </div>
        {{-- Edit mode --}}
        <div id="name-edit" class="hidden flex items-center gap-2">
          <input id="name-input" type="text" value="Demo User"
            class="font-bold text-fju-blue text-xl border-b-2 border-fju-blue outline-none bg-transparent w-48 px-0 py-0.5"
            onkeydown="handleNameKey(event)">
          <button onclick="saveName()" class="px-2 py-0.5 rounded text-xs bg-fju-blue text-white hover:bg-fju-blue/80 transition-colors">儲存</button>
          <button onclick="cancelNameEdit()" class="px-2 py-0.5 rounded text-xs border border-gray-200 text-gray-400 hover:text-gray-600 transition-colors">取消</button>
        </div>
        <p class="text-sm text-gray-500 mt-0.5">{{ $roleLabel }} · 410XXXXXX</p>
        <p class="text-xs text-gray-400 mt-1"><i class="fas fa-envelope mr-1"></i>410xxx@cloud.fju.edu.tw</p>
      </div>

      <div class="text-right flex-shrink-0">
        <span class="inline-block px-3 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-xs font-bold"><i class="fas fa-shield-alt mr-1"></i>已驗證</span>
      </div>
    </div>
  </div>

  <script>
  // ── Avatar ──────────────────────────────────────────────
  function handleAvatarChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(ev) {
      const img = document.getElementById('avatar-img');
      const initial = document.getElementById('avatar-initial');
      img.src = ev.target.result;
      img.classList.remove('hidden');
      initial.classList.add('hidden');
    };
    reader.readAsDataURL(file);
    e.target.value = '';
  }

  // ── Name edit ───────────────────────────────────────────
  let _prevName = 'Demo User';

  function startNameEdit() {
    _prevName = document.getElementById('name-display').textContent;
    document.getElementById('name-input').value = _prevName;
    document.getElementById('name-view').classList.add('hidden');
    document.getElementById('name-edit').classList.remove('hidden');
    document.getElementById('name-input').focus();
    document.getElementById('name-input').select();
  }

  function saveName() {
    const val = document.getElementById('name-input').value.trim();
    if (!val) return;
    document.getElementById('name-display').textContent = val;
    document.getElementById('name-view').classList.remove('hidden');
    document.getElementById('name-edit').classList.add('hidden');
  }

  function cancelNameEdit() {
    document.getElementById('name-view').classList.remove('hidden');
    document.getElementById('name-edit').classList.add('hidden');
  }

  function handleNameKey(e) {
    if (e.key === 'Enter')  saveName();
    if (e.key === 'Escape') cancelNameEdit();
  }
  </script>

  {{-- Tab Navigation --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="flex border-b border-gray-100">
      <button onclick="switchPCTab('reservations')" class="pc-tab flex-1 px-4 py-3 text-sm font-bold text-fju-blue border-b-2 border-fju-blue" data-tab="reservations"><i class="fas fa-calendar-check mr-1"></i>預約紀錄</button>
      <button onclick="switchPCTab('appeals')" class="pc-tab flex-1 px-4 py-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue" data-tab="appeals"><i class="fas fa-comments mr-1"></i>申訴紀錄</button>
      <button onclick="switchPCTab('negotiations')" class="pc-tab flex-1 px-4 py-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue" data-tab="negotiations"><i class="fas fa-handshake mr-1"></i>協調紀錄</button>
      <button onclick="switchPCTab('repairs')" class="pc-tab flex-1 px-4 py-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue" data-tab="repairs"><i class="fas fa-wrench mr-1"></i>報修紀錄</button>
      <button onclick="switchPCTab('settings')" class="pc-tab flex-1 px-4 py-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue" data-tab="settings"><i class="fas fa-cog mr-1"></i>帳號設定</button>
    </div>

    {{-- Tab Contents --}}
    <div id="pc-content" class="p-6">
      {{-- Reservations Tab --}}
      <div id="tab-reservations" class="pc-panel">
        <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-calendar-check mr-2 text-fju-yellow"></i>我的預約紀錄</h3>
        <div id="pc-reservations-list">
          <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>
        </div>
      </div>

      {{-- Appeals Tab --}}
      <div id="tab-appeals" class="pc-panel hidden">
        <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-comments mr-2 text-fju-yellow"></i>我的申訴紀錄</h3>
        <div id="pc-appeals-list">
          <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>
        </div>
      </div>

      {{-- Negotiations Tab --}}
      <div id="tab-negotiations" class="pc-panel hidden">
        <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-handshake mr-2 text-fju-yellow"></i>私下協調紀錄</h3>
        <div id="pc-negotiations-list">
          <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>
        </div>
      </div>

      {{-- Repairs Tab --}}
      <div id="tab-repairs" class="pc-panel hidden">
        <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>我的報修紀錄</h3>
        <div id="pc-repairs-list">
          <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>
        </div>
      </div>

      {{-- Settings Tab --}}
      <div id="tab-settings" class="pc-panel hidden">
        <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-cog mr-2 text-fju-yellow"></i>帳號設定</h3>
        <div class="space-y-4 max-w-lg">
          <div><label class="block text-sm font-medium text-gray-600 mb-1">姓名</label><input type="text" value="Demo User" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
          <div><label class="block text-sm font-medium text-gray-600 mb-1">學號 / 員工編號</label><input type="text" value="410XXXXXX" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm bg-gray-50" disabled></div>
          <div><label class="block text-sm font-medium text-gray-600 mb-1">電子信箱</label><input type="email" value="410xxx@cloud.fju.edu.tw" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
          <div><label class="block text-sm font-medium text-gray-600 mb-1">身分類型</label><input type="text" value="{{ $roleLabel }}" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm bg-gray-50" disabled></div>
          <div class="pt-4 border-t border-gray-100">
            <h4 class="font-bold text-fju-blue text-sm mb-3">變更密碼</h4>
            <div class="space-y-3">
              <div><label class="block text-xs text-gray-500 mb-1">目前密碼</label><input type="password" placeholder="輸入目前密碼" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
              <div><label class="block text-xs text-gray-500 mb-1">新密碼</label><input type="password" placeholder="輸入新密碼（至少 8 位元）" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
              <div><label class="block text-xs text-gray-500 mb-1">確認新密碼</label><input type="password" placeholder="再次輸入新密碼" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
            </div>
          </div>
          <button onclick="alert('帳號設定已儲存')" class="btn-yellow px-6 py-2 text-sm mt-4"><i class="fas fa-save mr-1"></i>儲存變更</button>
        </div>

        @if(in_array($role ?? 'student', ['admin']))
        {{-- Admin: Add New Account --}}
        <div class="mt-8 pt-6 border-t border-gray-100">
          <div class="flex items-center justify-between mb-4">
            <h4 class="font-bold text-fju-blue text-sm"><i class="fas fa-user-plus mr-1 text-fju-yellow"></i>帳號管理</h4>
            <button onclick="openAddUserModal()" class="btn-yellow px-4 py-1.5 text-xs"><i class="fas fa-plus mr-1"></i>新增帳號</button>
          </div>
          <div id="pc-user-list" class="bg-gray-50 rounded-fju-lg p-4 text-xs text-gray-400 text-center">
            <button onclick="loadPcUsers()" class="text-fju-blue underline text-xs"><i class="fas fa-list mr-1"></i>載入使用者列表</button>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

@if(in_array($role ?? 'student', ['admin']))
{{-- Add User Modal --}}
<div id="add-user-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center" onclick="if(event.target===this)closeAddUserModal()">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue"><i class="fas fa-user-plus mr-2 text-fju-yellow"></i>新增帳號</h3>
      <button onclick="closeAddUserModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <form id="add-user-form" onsubmit="submitAddUser(event)">
      <div class="grid grid-cols-2 gap-3">
        <div><label class="text-xs text-gray-400 block mb-1">姓名 <span class="text-red-400">*</span></label><input type="text" id="u-name" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required></div>
        <div><label class="text-xs text-gray-400 block mb-1">學號 / 員工編號 <span class="text-red-400">*</span></label><input type="text" id="u-sid" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required></div>
        <div><label class="text-xs text-gray-400 block mb-1">Email (Outlook) <span class="text-red-400">*</span></label><input type="email" id="u-email" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" placeholder="xxx@cloud.fju.edu.tw" required></div>
        <div><label class="text-xs text-gray-400 block mb-1">手機</label><input type="tel" id="u-phone" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" placeholder="09xx-xxx-xxx"></div>
        <div><label class="text-xs text-gray-400 block mb-1">角色 <span class="text-red-400">*</span></label>
          <select id="u-role" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm">
            <option value="student">一般學生</option>
            <option value="officer">社團幹部</option>
            <option value="professor">指導教授</option>
            <option value="staff">處室職員</option>
            <option value="admin">課指組行政</option>
          </select>
        </div>
        <div><label class="text-xs text-gray-400 block mb-1">職位</label><input type="text" id="u-position" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" placeholder="例：社長"></div>
      </div>
      <div id="add-user-result" class="hidden mt-3 p-3 rounded-fju text-sm"></div>
      <button type="submit" class="w-full btn-yellow py-2.5 mt-4"><i class="fas fa-save mr-1"></i>建立帳號</button>
    </form>
  </div>
</div>
@endif

<script>
function switchPCTab(tab){
  document.querySelectorAll('.pc-tab').forEach(b=>{
    b.className='pc-tab flex-1 px-4 py-3 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-fju-blue';
  });
  document.querySelector(`.pc-tab[data-tab="${tab}"]`).className='pc-tab flex-1 px-4 py-3 text-sm font-bold text-fju-blue border-b-2 border-fju-blue';
  document.querySelectorAll('.pc-panel').forEach(p=>p.classList.add('hidden'));
  document.getElementById('tab-'+tab).classList.remove('hidden');
}

// Load data
(function(){
  // Reservations
  fetch('/api/reservations').then(r=>r.json()).then(res=>{
    const list=res.data||[];
    const el=document.getElementById('pc-reservations-list');
    if(list.length===0){el.innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-calendar-times mr-1"></i>目前沒有預約紀錄</div>';return}
    el.innerHTML='<div class="space-y-3">'+list.slice(0,10).map(r=>`<div class="flex items-center justify-between p-3 rounded-fju border border-gray-100 hover:bg-fju-bg"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-fju bg-fju-blue/10 flex items-center justify-center"><i class="fas fa-map-marker-alt text-fju-blue text-sm"></i></div><div><div class="text-sm font-medium text-fju-blue">${r.venue_name||'場地'}</div><div class="text-xs text-gray-400">${r.reservation_date||''} ${r.start_time||''}-${r.end_time||''}</div></div></div><span class="px-2 py-1 rounded-fju text-xs ${r.status==='confirmed'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow'}">${r.status==='confirmed'?'已確認':'待審核'}</span></div>`).join('')+'</div>';
  }).catch(()=>{document.getElementById('pc-reservations-list').innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-calendar-times mr-1"></i>目前沒有預約紀錄</div>'});

  // Appeals
  fetch('/api/appeals').then(r=>r.json()).then(res=>{
    const list=res.data||[];
    const el=document.getElementById('pc-appeals-list');
    if(list.length===0){el.innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-check-circle mr-1 text-fju-green"></i>目前沒有申訴紀錄</div>';return}
    el.innerHTML='<div class="space-y-3">'+list.slice(0,10).map(a=>`<div class="flex items-center justify-between p-3 rounded-fju border border-gray-100 hover:bg-fju-bg"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-fju bg-fju-yellow/10 flex items-center justify-center"><i class="fas fa-comments text-fju-yellow text-sm"></i></div><div><div class="text-sm font-medium text-fju-blue">${a.title||a.subject||'申訴'}</div><div class="text-xs text-gray-400">${a.created_at?new Date(a.created_at).toLocaleDateString('zh-TW'):''}</div></div></div><span class="px-2 py-1 rounded-fju text-xs ${a.status==='resolved'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow'}">${a.status==='resolved'?'已解決':(a.status||'處理中')}</span></div>`).join('')+'</div>';
  }).catch(()=>{document.getElementById('pc-appeals-list').innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-check-circle mr-1 text-fju-green"></i>目前沒有申訴紀錄</div>'});

  // Negotiations (conflicts)
  fetch('/api/conflicts').then(r=>r.json()).then(res=>{
    const list=res.data||[];
    const el=document.getElementById('pc-negotiations-list');
    if(list.length===0){el.innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-handshake mr-1"></i>目前沒有協調紀錄</div>';return}
    el.innerHTML='<div class="space-y-3">'+list.slice(0,10).map(c=>`<div class="flex items-center justify-between p-3 rounded-fju border border-gray-100 hover:bg-fju-bg"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-fju bg-fju-blue/10 flex items-center justify-center"><i class="fas fa-handshake text-fju-blue text-sm"></i></div><div><div class="text-sm font-medium text-fju-blue">${c.party_a||''} vs ${c.party_b||''}</div><div class="text-xs text-gray-400">${c.venue_name||''} · ${c.conflict_date||''}</div></div></div><span class="px-2 py-1 rounded-fju text-xs ${c.status==='resolved'?'bg-fju-green/10 text-fju-green':c.status==='negotiating'?'bg-fju-yellow/20 text-fju-yellow':'bg-fju-red/10 text-fju-red'}">${c.status==='resolved'?'已解決':c.status==='negotiating'?'協商中':'待處理'}</span></div>`).join('')+'</div>';
  }).catch(()=>{document.getElementById('pc-negotiations-list').innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-handshake mr-1"></i>目前沒有協調紀錄</div>'});

  // Repairs
  fetch('/api/repairs').then(r=>r.json()).then(res=>{
    const list=res.data||[];
    const el=document.getElementById('pc-repairs-list');
    if(list.length===0){el.innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-tools mr-1"></i>目前沒有報修紀錄</div>';return}
    el.innerHTML='<div class="space-y-3">'+list.slice(0,10).map(r=>`<div class="flex items-center justify-between p-3 rounded-fju border border-gray-100 hover:bg-fju-bg"><div class="flex items-center gap-3"><div class="w-10 h-10 rounded-fju bg-fju-green/10 flex items-center justify-center"><i class="fas fa-wrench text-fju-green text-sm"></i></div><div><div class="text-sm font-medium text-fju-blue">${r.title||r.location||'報修'}</div><div class="text-xs text-gray-400">${r.created_at?new Date(r.created_at).toLocaleDateString('zh-TW'):''}</div></div></div><span class="px-2 py-1 rounded-fju text-xs ${r.status==='completed'?'bg-fju-green/10 text-fju-green':'bg-fju-yellow/20 text-fju-yellow'}">${r.status==='completed'?'已完成':(r.status||'處理中')}</span></div>`).join('')+'</div>';
  }).catch(()=>{document.getElementById('pc-repairs-list').innerHTML='<div class="p-8 text-center text-gray-400"><i class="fas fa-tools mr-1"></i>目前沒有報修紀錄</div>'});
})();

// ── Admin: Add User ──────────────────────────────────────────────────────
function openAddUserModal() {
  document.getElementById('add-user-form')?.reset();
  document.getElementById('add-user-result')?.classList.add('hidden');
  document.getElementById('add-user-modal')?.classList.remove('hidden');
}
function closeAddUserModal() { document.getElementById('add-user-modal')?.classList.add('hidden'); }

function submitAddUser(e) {
  e.preventDefault();
  const payload = {
    name:       document.getElementById('u-name').value,
    student_id: document.getElementById('u-sid').value,
    email:      document.getElementById('u-email').value,
    phone:      document.getElementById('u-phone').value,
    role:       document.getElementById('u-role').value,
    position:   document.getElementById('u-position').value,
    password:   'password123',
  };
  fetch('/api/users', { method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify(payload) })
    .then(r=>r.json()).then(res => {
      const el = document.getElementById('add-user-result');
      el.classList.remove('hidden');
      if (res.success || res.id) {
        el.className = 'mt-3 p-3 rounded-fju text-sm bg-green-50 text-green-700 border border-green-200';
        el.innerHTML = '<i class="fas fa-check-circle mr-1"></i>帳號建立成功！';
        setTimeout(() => { closeAddUserModal(); loadPcUsers(); }, 1200);
      } else {
        el.className = 'mt-3 p-3 rounded-fju text-sm bg-red-50 text-red-600 border border-red-200';
        el.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>' + (res.error || res.message || '建立失敗，請再試一次');
      }
    }).catch(() => {
      const el = document.getElementById('add-user-result');
      el.classList.remove('hidden');
      el.className = 'mt-3 p-3 rounded-fju text-sm bg-red-50 text-red-600';
      el.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>網路錯誤，請稍後再試';
    });
}

function loadPcUsers() {
  const el = document.getElementById('pc-user-list');
  if (!el) return;
  el.innerHTML = '<div class="py-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-1"></i>載入中...</div>';
  const roleLabel = {admin:'課指組',officer:'社團幹部',professor:'教授',staff:'職員',student:'學生'};
  const roleCls = {admin:'bg-fju-blue/10 text-fju-blue',officer:'bg-yellow-100 text-yellow-700',professor:'bg-green-100 text-green-700',staff:'bg-orange-100 text-orange-600',student:'bg-gray-100 text-gray-600',it:'bg-purple-100 text-purple-600'};
  fetch('/api/users').then(r=>r.json()).then(res => {
    const users = res.data || res || [];
    if (!users.length) { el.innerHTML='<div class="py-4 text-center text-gray-400">目前無使用者資料</div>'; return; }
    el.innerHTML = '<div class="overflow-x-auto"><table class="w-full text-xs"><thead class="bg-gray-100"><tr class="text-left text-gray-500"><th class="px-3 py-2">姓名</th><th class="px-3 py-2">學號</th><th class="px-3 py-2">Email</th><th class="px-3 py-2">角色</th><th class="px-3 py-2">操作</th></tr></thead><tbody>'+
      users.map(u=>`<tr class="border-t border-gray-100 hover:bg-gray-50">
        <td class="px-3 py-2 font-medium text-fju-blue">${u.name}</td>
        <td class="px-3 py-2 text-gray-500">${u.student_id||'-'}</td>
        <td class="px-3 py-2 text-gray-400">${u.email}</td>
        <td class="px-3 py-2"><span class="px-2 py-0.5 rounded-fju ${roleCls[u.role]||'bg-gray-100 text-gray-500'}">${roleLabel[u.role]||u.role}</span></td>
        <td class="px-3 py-2"><button onclick="deletePcUser(${u.id})" class="px-2 py-0.5 rounded bg-red-50 text-red-500 hover:bg-red-100 text-xs"><i class="fas fa-trash"></i></button></td>
      </tr>`).join('') + '</tbody></table></div>';
  }).catch(() => { el.innerHTML='<div class="py-4 text-center text-red-400">載入失敗</div>'; });
}

function deletePcUser(id) {
  if (!confirm('確定要刪除此帳號？此操作無法復原。')) return;
  fetch('/api/users/'+id, { method:'DELETE', headers:{'Accept':'application/json'} })
    .then(() => loadPcUsers());
}
</script>
@endsection
