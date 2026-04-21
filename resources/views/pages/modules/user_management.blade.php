@extends('layouts.shell')
@section('title', '用戶管理')
@php $activePage = 'user-management'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-users-cog mr-2 text-fju-yellow"></i>用戶管理</h2>
    <button onclick="document.getElementById('add-user-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-xs"><i class="fas fa-user-plus mr-1"></i>新增使用者</button>
  </div>

  {{-- Stats --}}
  <div class="grid md:grid-cols-5 gap-4" id="user-stats"><div class="p-4 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>

  {{-- User Table --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>使用者列表</h3>
      <input type="text" id="user-search" placeholder="搜尋姓名/學號..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-48" oninput="searchUsers()">
    </div>
    <div id="user-table"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- Add User Modal --}}
  <div id="add-user-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl">
      <div class="flex items-center justify-between mb-4"><h3 class="font-bold text-fju-blue"><i class="fas fa-user-plus mr-2 text-fju-yellow"></i>新增使用者</h3><button onclick="document.getElementById('add-user-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button></div>
      <form id="add-user-form" onsubmit="addUser(event)">
        <div class="grid grid-cols-2 gap-3">
          <div><label class="text-xs text-gray-400 block mb-1">姓名</label><input type="text" id="u-name" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required></div>
          <div><label class="text-xs text-gray-400 block mb-1">學號/員工編號</label><input type="text" id="u-sid" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" required></div>
          <div><label class="text-xs text-gray-400 block mb-1">Email (Outlook)</label><input type="email" id="u-email" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" placeholder="xxx@cloud.fju.edu.tw" required></div>
          <div><label class="text-xs text-gray-400 block mb-1">手機</label><input type="tel" id="u-phone" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" placeholder="09xx-xxx-xxx"></div>
          <div><label class="text-xs text-gray-400 block mb-1">角色</label><select id="u-role" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm"><option value="student">一般學生</option><option value="officer">社團幹部</option><option value="professor">指導教授</option><option value="admin">課指組行政</option><option value="it">資訊中心</option></select></div>
          <div><label class="text-xs text-gray-400 block mb-1">社團職位</label><input type="text" id="u-position" class="w-full px-3 py-2 rounded-fju border border-gray-200 text-sm" placeholder="例：社長"></div>
        </div>
        <button type="submit" class="w-full btn-yellow py-2.5 mt-4"><i class="fas fa-save mr-1"></i>建立帳號</button>
      </form>
    </div>
  </div>
</div>
<script>
let allUsers=[];
function loadUsers(){
  fetch('/api/users').then(r=>r.json()).then(res=>{
    allUsers=res.data||res;
    renderStats();
    renderUsers(allUsers);
  });
}
function renderStats(){
  const roles={admin:0,officer:0,professor:0,student:0,it:0};
  allUsers.forEach(u=>{if(roles[u.role]!==undefined)roles[u.role]++});
  const labels={admin:'課指組',officer:'社團幹部',professor:'指導教授',student:'學生',it:'資訊中心'};
  const colors={admin:'fju-blue',officer:'fju-yellow',professor:'fju-green',student:'fju-blue',it:'purple-500'};
  document.getElementById('user-stats').innerHTML=Object.entries(labels).map(([k,v])=>`<div class="bg-white rounded-fju-lg p-4 shadow-sm border border-gray-100 text-center card-hover"><div class="text-2xl font-black text-${colors[k]}">${roles[k]}</div><div class="text-xs text-gray-400">${v}</div></div>`).join('');
}
function renderUsers(users){
  const rl={admin:'課指組',officer:'社團幹部',professor:'指導教授',student:'學生',it:'資訊中心'};
  const rc={admin:'bg-fju-blue/10 text-fju-blue',officer:'bg-fju-yellow/20 text-fju-yellow',professor:'bg-fju-green/10 text-fju-green',student:'bg-gray-100 text-gray-600',it:'bg-purple-100 text-purple-600'};
  document.getElementById('user-table').innerHTML=`<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">ID</th><th class="p-4">姓名</th><th class="p-4">學號</th><th class="p-4">Email</th><th class="p-4">角色</th><th class="p-4">信用</th><th class="p-4">2FA</th><th class="p-4">操作</th></tr></thead><tbody>`+users.map(u=>`<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 text-xs text-gray-400">#${u.id}</td><td class="p-4 font-medium text-fju-blue">${u.name}</td><td class="p-4 text-xs">${u.student_id||'-'}</td><td class="p-4 text-xs text-gray-500">${u.email}</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs ${rc[u.role]||'bg-gray-100 text-gray-500'}">${rl[u.role]||u.role}</span></td><td class="p-4"><span class="${(u.credit_score||85)<60?'text-fju-red font-bold':'text-fju-green'}">${u.credit_score||85}</span></td><td class="p-4">${u.two_factor_enabled?'<i class="fas fa-check-circle text-fju-green"></i>':'<i class="fas fa-times-circle text-gray-300"></i>'}</td><td class="p-4 flex gap-1"><button onclick="deleteUser(${u.id})" class="text-xs px-2 py-1 rounded bg-fju-red/10 text-fju-red hover:bg-fju-red/20"><i class="fas fa-trash"></i></button></td></tr>`).join('')+'</tbody></table>';
}
function searchUsers(){
  const q=document.getElementById('user-search').value.toLowerCase();
  renderUsers(allUsers.filter(u=>(u.name||'').toLowerCase().includes(q)||(u.student_id||'').includes(q)||(u.email||'').toLowerCase().includes(q)));
}
function addUser(e){
  e.preventDefault();
  fetch('/api/users',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({name:document.getElementById('u-name').value,student_id:document.getElementById('u-sid').value,email:document.getElementById('u-email').value,phone:document.getElementById('u-phone').value,role:document.getElementById('u-role').value,position:document.getElementById('u-position').value,password:'password123'})}).then(r=>r.json()).then(()=>{document.getElementById('add-user-modal').classList.add('hidden');loadUsers()});
}
function deleteUser(id){if(confirm('確定要刪除此使用者？'))fetch('/api/users/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>loadUsers())}
loadUsers();
</script>
@endsection
