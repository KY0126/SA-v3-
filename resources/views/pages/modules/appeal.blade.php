@extends('layouts.shell')
@section('title', '申訴記錄')
@php $activePage = 'appeal'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-comments mr-2 text-fju-yellow"></i>申訴記錄</h2>
    <div class="flex gap-2">
      <select id="ap-sort" onchange="renderAppeals()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="newest">最新優先</option>
        <option value="oldest">最舊優先</option>
        <option value="type">依類型</option>
      </select>
      <select id="ap-filter" onchange="renderAppeals()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部狀態</option>
        <option value="pending">待處理</option>
        <option value="processing">處理中</option>
        <option value="resolved">已解決</option>
      </select>
      <input id="ap-search" oninput="renderAppeals()" placeholder="搜尋..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-32">
      <button onclick="document.getElementById('ap-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增申訴</button>
    </div>
  </div>
  <div id="appeal-list"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>
  <div id="ap-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center"><div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4"><h3 class="font-bold text-fju-blue text-lg mb-4">新增申訴</h3><div class="space-y-3"><select id="ap-type-input" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><option>場地衝突</option><option>器材損壞</option><option>信用積分</option><option>活動爭議</option></select><input id="ap-subj" placeholder="主旨" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><textarea id="ap-desc" placeholder="詳細說明" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea></div><div class="flex gap-2 mt-4"><button onclick="addAppeal()" class="flex-1 btn-yellow py-2">送出</button><button onclick="document.getElementById('ap-modal').classList.add('hidden')" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button></div></div></div>
</div>
<script>
let allAppeals=[];
function loadAppeals(){fetch('/api/appeals').then(r=>r.json()).then(res=>{allAppeals=res.data;renderAppeals()})}
function renderAppeals(){
  let data=[...allAppeals];
  const search=(document.getElementById('ap-search')?.value||'').toLowerCase();
  const filter=document.getElementById('ap-filter')?.value||'all';
  const sort=document.getElementById('ap-sort')?.value||'newest';
  if(search) data=data.filter(a=>a.subject.toLowerCase().includes(search)||(a.appeal_type||'').toLowerCase().includes(search)||(a.code||'').toLowerCase().includes(search));
  if(filter!=='all') data=data.filter(a=>a.status===filter);
  if(sort==='newest') data.sort((a,b)=>b.id-a.id);
  else if(sort==='oldest') data.sort((a,b)=>a.id-b.id);
  else if(sort==='type') data.sort((a,b)=>(a.appeal_type||'').localeCompare(b.appeal_type||''));
  document.getElementById('appeal-list').innerHTML='<div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">編號</th><th class="p-4">類型</th><th class="p-4">主旨</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>'+data.map(a=>'<tr class="border-t hover:bg-gray-50"><td class="p-4 text-xs text-gray-400">'+a.code+'</td><td class="p-4 text-xs">'+a.appeal_type+'</td><td class="p-4 font-medium text-fju-blue">'+a.subject+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs '+(a.status==='resolved'?'bg-fju-green/10 text-fju-green':a.status==='processing'?'bg-fju-yellow/20 text-fju-yellow':'bg-gray-100 text-gray-500')+'">'+a.status+'</span></td><td class="p-4"><button onclick="aiSummary('+a.id+')" class="btn-blue px-2 py-1 text-xs mr-1"><i class="fas fa-robot"></i> AI</button><button onclick="delAppeal('+a.id+')" class="text-fju-red text-xs"><i class="fas fa-trash"></i></button></td></tr>').join('')+'</tbody></table></div>';
}
loadAppeals();
function addAppeal(){fetch('/api/appeals',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({appeal_type:document.getElementById('ap-type-input').value,subject:document.getElementById('ap-subj').value,description:document.getElementById('ap-desc').value})}).then(r=>r.json()).then(res=>{alert(res.message);document.getElementById('ap-modal').classList.add('hidden');loadAppeals()})}
function aiSummary(id){fetch('/api/appeals/'+id+'/ai-summary',{method:'POST',headers:{'Accept':'application/json'}}).then(r=>r.json()).then(res=>alert('AI 摘要：\n'+res.summary+'\n\n建議：\n'+res.suggestions.join('\n')))}
function delAppeal(id){if(!confirm('確定刪除？'))return;fetch('/api/appeals/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>loadAppeals())}
</script>
@endsection
