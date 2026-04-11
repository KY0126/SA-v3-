@extends('layouts.shell')
@section('title', '設備借用')
@php $activePage = 'equipment'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <div class="flex gap-2">
      @foreach(['all'=>'全部器材','available'=>'可借用','borrowed'=>'已借出','maintenance'=>'維修中'] as $k=>$v)
      <button onclick="filterEquipment('{{$k}}')" class="eq-filter px-4 py-2 rounded-fju {{ $k==='all'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500' }} text-sm" data-filter="{{$k}}">{{$v}}</button>
      @endforeach
    </div>
    <input id="eq-search" oninput="filterEquipment()" type="text" placeholder="搜尋器材..." class="pl-4 pr-4 py-2 rounded-fju border border-gray-200 text-sm w-48 focus:border-fju-blue outline-none">
  </div>
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div id="equipment-table"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>
  <div id="borrow-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
      <h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-hand-holding mr-2 text-fju-yellow"></i>借用器材</h3>
      <div id="borrow-item-info" class="p-3 rounded-fju bg-fju-bg mb-4"></div>
      <div class="space-y-3">
        <div><label class="text-xs text-gray-400">借用人</label><input id="bw-name" value="Demo User" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
        <div><label class="text-xs text-gray-400">預計歸還日</label><input type="date" id="bw-return" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
      </div>
      <div class="flex gap-2 mt-4"><button onclick="confirmBorrow()" class="flex-1 btn-yellow py-2.5 text-sm"><i class="fas fa-check mr-1"></i>確認借用</button><button onclick="document.getElementById('borrow-modal').classList.add('hidden')" class="flex-1 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button></div>
    </div>
  </div>
</div>
<script>
let allEquipment=[],currentFilter='all';
function loadEquipment(){fetch('/api/equipment').then(r=>r.json()).then(res=>{allEquipment=res.data;renderEquipment()})}
function renderEquipment(){const s=document.getElementById('eq-search')?.value.toLowerCase()||'';let data=allEquipment;if(currentFilter!=='all')data=data.filter(e=>e.status===currentFilter);if(s)data=data.filter(e=>e.name.toLowerCase().includes(s)||e.code.toLowerCase().includes(s));
document.getElementById('equipment-table').innerHTML='<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">編號</th><th class="p-4">名稱</th><th class="p-4">分類</th><th class="p-4">狀態</th><th class="p-4">借用人</th><th class="p-4">操作</th></tr></thead><tbody>'+data.map(e=>'<tr class="border-t border-gray-50 hover:bg-gray-50"><td class="p-4 text-xs text-gray-400">'+e.code+'</td><td class="p-4 font-medium text-fju-blue">'+e.name+'</td><td class="p-4 text-xs">'+e.category+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs '+(e.status==='available'?'bg-fju-green/10 text-fju-green':e.status==='borrowed'?'bg-fju-yellow/20 text-fju-yellow':'bg-fju-red/10 text-fju-red')+'">'+(e.status==='available'?'可借用':e.status==='borrowed'?'已借出':'維修中')+'</span></td><td class="p-4 text-xs text-gray-500">'+(e.borrower||'-')+'</td><td class="p-4">'+(e.status==='available'?'<button onclick="openBorrow('+e.id+',\''+e.name+'\')" class="btn-yellow px-3 py-1 text-xs">借用</button>':e.status==='borrowed'?'<button onclick="returnEq('+e.id+')" class="btn-blue px-3 py-1 text-xs">歸還</button>':'-')+'</td></tr>').join('')+'</tbody></table>'}
function filterEquipment(f){if(f)currentFilter=f;document.querySelectorAll('.eq-filter').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});document.querySelector('.eq-filter[data-filter="'+currentFilter+'"]')?.classList.add('bg-fju-blue','text-white');document.querySelector('.eq-filter[data-filter="'+currentFilter+'"]')?.classList.remove('bg-gray-100','text-gray-500');renderEquipment()}
let borrowId=0;function openBorrow(id,name){borrowId=id;document.getElementById('borrow-item-info').innerHTML='<b>'+name+'</b>';document.getElementById('borrow-modal').classList.remove('hidden')}
function confirmBorrow(){fetch('/api/equipment/borrow',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({equipment_id:borrowId,borrower_name:document.getElementById('bw-name').value})}).then(r=>r.json()).then(res=>{alert(res.message);document.getElementById('borrow-modal').classList.add('hidden');loadEquipment()})}
function returnEq(id){fetch('/api/equipment/return',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({equipment_id:id})}).then(r=>r.json()).then(res=>{alert(res.message);loadEquipment()})}
loadEquipment();
</script>
@endsection
