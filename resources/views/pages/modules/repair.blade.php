@extends('layouts.shell')
@section('title', '報修管理')
@php $activePage = 'repair'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>報修管理</h2>
    <div class="flex gap-2">
      <select id="rep-sort" onchange="renderRepairs()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="newest">最新優先</option>
        <option value="oldest">最舊優先</option>
        <option value="status">依狀態</option>
      </select>
      <select id="rep-filter" onchange="renderRepairs()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部狀態</option>
        <option value="pending">待處理</option>
        <option value="processing">處理中</option>
        <option value="completed">已完成</option>
      </select>
      <input id="rep-search" oninput="renderRepairs()" placeholder="搜尋..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-32">
      <button onclick="document.getElementById('rep-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增報修</button>
    </div>
  </div>
  <div id="repair-list"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>
  <div id="rep-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center"><div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4"><h3 class="font-bold text-fju-blue text-lg mb-4">新增報修</h3><div class="space-y-3"><input id="rp-target" placeholder="維修對象" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><textarea id="rp-desc" placeholder="問題描述" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea></div><div class="flex gap-2 mt-4"><button onclick="addRepair()" class="flex-1 btn-yellow py-2">送出</button><button onclick="document.getElementById('rep-modal').classList.add('hidden')" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button></div></div></div>
</div>
<script>
let allRepairs=[];
function loadRepairs(){fetch('/api/repairs').then(r=>r.json()).then(res=>{allRepairs=res.data;renderRepairs()})}
function renderRepairs(){
  let data=[...allRepairs];
  const search=(document.getElementById('rep-search')?.value||'').toLowerCase();
  const filter=document.getElementById('rep-filter')?.value||'all';
  const sort=document.getElementById('rep-sort')?.value||'newest';
  if(search) data=data.filter(r=>r.target.toLowerCase().includes(search)||r.description.toLowerCase().includes(search)||(r.code||'').toLowerCase().includes(search));
  if(filter!=='all') data=data.filter(r=>r.status===filter);
  if(sort==='newest') data.sort((a,b)=>b.id-a.id);
  else if(sort==='oldest') data.sort((a,b)=>a.id-b.id);
  else if(sort==='status'){const o={pending:0,processing:1,completed:2};data.sort((a,b)=>(o[a.status]||0)-(o[b.status]||0))}
  document.getElementById('repair-list').innerHTML='<div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">編號</th><th class="p-4">對象</th><th class="p-4">描述</th><th class="p-4">狀態</th><th class="p-4">指派</th><th class="p-4">操作</th></tr></thead><tbody>'+data.map(r=>'<tr class="border-t hover:bg-gray-50"><td class="p-4 text-xs text-gray-400">'+r.code+'</td><td class="p-4 font-medium text-fju-blue">'+r.target+'</td><td class="p-4 text-xs text-gray-500">'+r.description+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs '+(r.status==='completed'?'bg-fju-green/10 text-fju-green':r.status==='processing'?'bg-fju-yellow/20 text-fju-yellow':'bg-gray-100 text-gray-500')+'">'+r.status+'</span></td><td class="p-4 text-xs">'+(r.assignee||'未指派')+'</td><td class="p-4"><button onclick="delRepair('+r.id+')" class="text-fju-red text-xs"><i class="fas fa-trash"></i></button></td></tr>').join('')+'</tbody></table></div>';
}
loadRepairs();
function addRepair(){fetch('/api/repairs',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({target:document.getElementById('rp-target').value,description:document.getElementById('rp-desc').value})}).then(r=>r.json()).then(res=>{alert('報修單 '+res.tracking_code+' 已建立');document.getElementById('rep-modal').classList.add('hidden');loadRepairs()})}
function delRepair(id){if(!confirm('確定刪除？'))return;fetch('/api/repairs/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>loadRepairs())}
</script>
@endsection
