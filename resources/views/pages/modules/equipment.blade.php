@extends('layouts.shell')
@section('title', '設備借用')
@php $activePage = 'equipment'; @endphp
@section('content')
<div class="space-y-6">

  {{-- Toolbar --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <div>
      <h2 class="font-bold text-fju-blue text-xl">
        <i class="fas fa-boxes-stacked mr-2 text-fju-yellow"></i>
        @if(in_array($role ?? 'student', ['admin','officer'])) 器材借用管理 @else 設備借用 @endif
      </h2>
      <p class="text-xs text-gray-400 mt-0.5">
        @if(in_array($role ?? 'student', ['admin','officer'])) 審核借用申請、確認取件與歸還 @else 選取器材加入清單，批次送出借用申請 @endif
      </p>
    </div>
    {{-- Only borrowers see multi-borrow button --}}
    @if(!in_array($role ?? 'student', ['admin','officer']))
    <button onclick="openMultiBorrow()" id="multi-borrow-btn" class="hidden btn-yellow px-4 py-2 text-sm">
      <i class="fas fa-cart-plus mr-1"></i>批次借用 (<span id="cart-count">0</span>)
    </button>
    @endif
  </div>

  @if(in_array($role ?? 'student', ['admin','officer']))
  {{-- ===== ADMIN / OFFICER VIEW: Loan Application Management ===== --}}

  {{-- Status filter tabs for loans --}}
  <div class="flex gap-2 flex-wrap">
    @foreach(['all'=>'全部','pending'=>'待審核','approved'=>'已核准','picked_up'=>'使用中','returned'=>'已歸還','rejected'=>'已拒絕'] as $k=>$v)
    <button onclick="filterLoans('{{$k}}')" class="loan-filter px-4 py-2 rounded-fju text-sm {{ $k==='all'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500' }}" data-f="{{$k}}">{{$v}}</button>
    @endforeach
  </div>

  {{-- Loan list table --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div id="loan-admin-list"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- Equipment inventory (read-only for admin) --}}
  <div>
    <button onclick="toggleInventory()" class="text-sm text-fju-blue underline"><i class="fas fa-warehouse mr-1"></i>器材庫存總覽</button>
    <div id="inventory-panel" class="hidden mt-3 bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
      <div class="flex gap-2 flex-wrap p-3 border-b border-gray-100">
        @foreach(['all'=>'全部器材','available'=>'可借用','borrowed'=>'已借出','maintenance'=>'維修中'] as $k=>$v)
        <button onclick="filterEquipment('{{$k}}')" class="eq-filter px-3 py-1.5 rounded-fju {{ $k==='all'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500' }} text-xs" data-filter="{{$k}}">{{$v}}</button>
        @endforeach
        <input id="eq-search" oninput="renderEquipment()" type="text" placeholder="搜尋器材..." class="ml-auto pl-4 pr-4 py-1.5 rounded-fju border border-gray-200 text-xs w-44 focus:border-fju-blue outline-none">
      </div>
      <div id="equipment-table"><div class="p-6 text-center text-gray-400">載入中...</div></div>
    </div>
  </div>

  @else
  {{-- ===== BORROWER VIEW: Equipment list + cart ===== --}}
  <div class="flex gap-2 flex-wrap items-center">
    @foreach(['all'=>'全部器材','available'=>'可借用','borrowed'=>'已借出','maintenance'=>'維修中'] as $k=>$v)
    <button onclick="filterEquipment('{{$k}}')" class="eq-filter px-4 py-2 rounded-fju {{ $k==='all'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500' }} text-sm" data-filter="{{$k}}">{{$v}}</button>
    @endforeach
    <div class="ml-auto flex gap-2">
      <select id="eq-sort" onchange="renderEquipment()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="code-asc">編號 A→Z</option>
        <option value="name-asc">名稱 A→Z</option>
        <option value="cat">依分類</option>
      </select>
      <input id="eq-search" oninput="renderEquipment()" type="text" placeholder="搜尋器材..." class="pl-4 pr-4 py-2 rounded-fju border border-gray-200 text-sm w-48 focus:border-fju-blue outline-none">
    </div>
  </div>

  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div id="equipment-table"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- My loans --}}
  <div>
    <button onclick="toggleMyLoans()" class="text-sm text-fju-blue underline"><i class="fas fa-history mr-1"></i>我的借用紀錄</button>
    <div id="my-loans-panel" class="hidden mt-3 bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
      <div id="my-loans-table"><div class="p-6 text-center text-gray-400">載入中...</div></div>
    </div>
  </div>
  @endif

</div>

{{-- Multi-item borrow modal (borrower only) --}}
<div id="borrow-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
    <h3 class="font-bold text-fju-blue text-lg mb-4"><i class="fas fa-cart-plus mr-2 text-fju-yellow"></i>批次借用申請</h3>
    <div id="borrow-cart" class="space-y-2 mb-4"></div>
    <div class="space-y-3">
      <div>
        <label class="text-xs text-gray-400">借用人</label>
        <input id="bw-name" value="Demo User" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
      <div>
        <label class="text-xs text-gray-400">預計歸還日 <span class="text-red-400">*</span></label>
        <input type="date" id="bw-return" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
      <div>
        <label class="text-xs text-gray-400">借用事由</label>
        <textarea id="bw-purpose" rows="2" placeholder="活動名稱或用途..." class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm resize-none"></textarea>
      </div>
      <div>
        <label class="text-xs text-gray-400">關聯活動申請序號（已核准）</label>
        <input id="bw-aa-serial" placeholder="AA-2026-00001（選填）" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
    </div>
    <div id="borrow-error" class="hidden mt-3 p-3 rounded-fju bg-red-50 text-red-600 text-sm"></div>
    <div class="flex gap-2 mt-4">
      <button onclick="confirmBorrow()" class="flex-1 btn-yellow py-2.5 text-sm"><i class="fas fa-check mr-1"></i>確認送出</button>
      <button onclick="closeBorrowModal()" class="flex-1 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
    </div>
  </div>
</div>

{{-- Loan detail modal (admin review) --}}
<div id="loan-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-search mr-2 text-fju-yellow"></i>借用申請詳情</h3>
      <button onclick="closeLoanDetailModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <div id="loan-detail-body" class="space-y-3 text-sm"></div>
    <div id="loan-review-actions" class="hidden mt-4 space-y-2"></div>
  </div>
</div>

{{-- Reject loan modal (admin, forced non-empty reason) --}}
<div id="loan-reject-modal" class="hidden fixed inset-0 bg-black/50 z-[60] flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-red-600 text-lg"><i class="fas fa-times-circle mr-2"></i>拒絕借用申請</h3>
      <button onclick="closeLoanRejectModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <p class="text-sm text-gray-500 mb-3">請填寫拒絕原因（必填）。</p>
    <textarea id="loan-reject-reason" rows="3" placeholder="請輸入拒絕原因..."
      class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm resize-none focus:border-red-400 outline-none"></textarea>
    <div id="loan-reject-error" class="hidden mt-2 text-red-500 text-xs"><i class="fas fa-exclamation-circle mr-1"></i>拒絕原因不可空白</div>
    <div class="flex gap-2 mt-4">
      <button onclick="confirmLoanReject()" class="flex-1 py-2.5 rounded-fju bg-red-500 text-white text-sm font-bold hover:bg-red-600"><i class="fas fa-times mr-1"></i>確認拒絕</button>
      <button onclick="closeLoanRejectModal()" class="flex-1 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
    </div>
  </div>
</div>

<script>
const IS_ADMIN = {{ in_array($role ?? 'student', ['admin','officer']) ? 'true' : 'false' }};
let allEquipment = [], currentFilter = 'all', cart = {};
let allLoans = [], currentLoanFilter = 'all', currentLoanId = null;

// ─── Equipment helpers ───────────────────────────────────────────────────
function loanStatusBadge(s) {
  return ({
    pending:   '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#fef9c3;color:#a16207;">待審核</span>',
    approved:  '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#dcfce7;color:#15803d;">已核准</span>',
    picked_up: '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#dbeafe;color:#1d4ed8;">使用中</span>',
    returned:  '<span class="px-2 py-1 rounded-fju text-xs bg-gray-100 text-gray-500">已歸還</span>',
    rejected:  '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#fee2e2;color:#dc2626;">已拒絕</span>',
    overdue:   '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#fde8d8;color:#c2410c;">逾期</span>',
  })[s] || `<span class="text-xs text-gray-400">${s}</span>`;
}

function eqStatusBadge(s) {
  return s === 'available'
    ? '<span class="px-2 py-1 rounded-fju text-xs bg-green-100 text-green-700">可借用</span>'
    : s === 'borrowed'
    ? '<span class="px-2 py-1 rounded-fju text-xs bg-yellow-100 text-yellow-700">已借出</span>'
    : '<span class="px-2 py-1 rounded-fju text-xs bg-red-100 text-red-600">維修中</span>';
}

// ─── Equipment list (shared) ─────────────────────────────────────────────
function loadEquipment() {
  fetch('/api/equipment').then(r => r.json()).then(res => { allEquipment = res.data; renderEquipment(); });
}

function renderEquipment() {
  const s    = document.getElementById('eq-search')?.value.toLowerCase() || '';
  const sort = document.getElementById('eq-sort')?.value || 'code-asc';
  let data = [...allEquipment];
  if (currentFilter !== 'all') data = data.filter(e => e.status === currentFilter);
  if (s) data = data.filter(e =>
    e.name.toLowerCase().includes(s) || e.code.toLowerCase().includes(s) || (e.category||'').toLowerCase().includes(s)
  );
  if (sort === 'name-asc') data.sort((a,b) => a.name.localeCompare(b.name));
  else if (sort === 'cat') data.sort((a,b) => (a.category||'').localeCompare(b.category||''));
  else data.sort((a,b) => a.code.localeCompare(b.code));

  const actionCol = IS_ADMIN ? '' : '<th class="p-4">操作</th>';
  document.getElementById('equipment-table').innerHTML =
    '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">' +
    '<th class="p-4">編號</th><th class="p-4">名稱</th><th class="p-4">分類</th><th class="p-4">狀態</th><th class="p-4">借用人</th>' + actionCol + '</tr></thead><tbody>' +
    data.map(e =>
      `<tr class="border-t border-gray-50 hover:bg-gray-50">
        <td class="p-4 text-xs text-gray-400">${e.code}</td>
        <td class="p-4 font-medium text-fju-blue">${e.name}${e.cert_type_id ? ' <span class="text-xs text-orange-500 bg-orange-50 px-1 rounded">需證照</span>' : ''}</td>
        <td class="p-4 text-xs">${e.category}</td>
        <td class="p-4">${eqStatusBadge(e.status)}</td>
        <td class="p-4 text-xs text-gray-500">${e.borrower||'-'}</td>
        ${IS_ADMIN ? '' : `<td class="p-4">${borrowerActionBtn(e)}</td>`}
      </tr>`
    ).join('') + '</tbody></table>';
}

function borrowerActionBtn(e) {
  if (e.status === 'available') {
    const inCart = !!cart[e.id];
    return `<button onclick="toggleCart(${e.id},'${e.name.replace(/'/g,"\\'")}',${e.cert_type_id||'null'})"
      class="${inCart ? 'btn-blue' : 'btn-yellow'} px-3 py-1 text-xs cart-btn-${e.id}">
      ${inCart ? '<i class="fas fa-minus mr-1"></i>移除' : '<i class="fas fa-plus mr-1"></i>加入'}
    </button>`;
  }
  if (e.status === 'borrowed') return `<button onclick="returnSingleEq(${e.id})" class="btn-blue px-3 py-1 text-xs">歸還</button>`;
  return '-';
}

function filterEquipment(f) {
  if (f) currentFilter = f;
  document.querySelectorAll('.eq-filter').forEach(b => {
    b.classList.remove('bg-fju-blue','text-white'); b.classList.add('bg-gray-100','text-gray-500');
  });
  const active = document.querySelector(`.eq-filter[data-filter="${currentFilter}"]`);
  active?.classList.add('bg-fju-blue','text-white'); active?.classList.remove('bg-gray-100','text-gray-500');
  renderEquipment();
}

// ─── Admin: Loan management ──────────────────────────────────────────────
function loadAdminLoans() {
  fetch('/api/equipment-loans').then(r => r.json()).then(res => {
    allLoans = res.data || [];
    renderAdminLoans();
  });
}

function renderAdminLoans() {
  const data = currentLoanFilter === 'all' ? [...allLoans] : allLoans.filter(l => l.status === currentLoanFilter);
  if (!data.length) {
    document.getElementById('loan-admin-list').innerHTML = '<div class="p-8 text-center text-gray-400">目前沒有符合的借用申請</div>';
    return;
  }
  const statusLabel = s => ({ pending:'待審核', approved:'已核准', picked_up:'使用中', returned:'已歸還', rejected:'已拒絕', overdue:'逾期' }[s] || s);
  document.getElementById('loan-admin-list').innerHTML =
    '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">' +
    '<th class="p-4">序號</th><th class="p-4">借用人</th><th class="p-4">器材</th><th class="p-4">歸還日</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>' +
    data.map(l => {
      const names = (l.details||[]).map(d => d.equipment?.name || '').filter(Boolean).join('、') || '—';
      return `<tr class="border-t border-gray-50 hover:bg-gray-50">
        <td class="p-4 text-xs text-gray-400 font-mono">${l.serial_no}</td>
        <td class="p-4 font-medium text-fju-blue">${l.borrower?.name || l.borrower_id}</td>
        <td class="p-4 text-xs text-gray-600 max-w-[180px] truncate" title="${names}">${names}</td>
        <td class="p-4 text-xs">${l.expected_return_date}</td>
        <td class="p-4">${loanStatusBadge(l.status)}</td>
        <td class="p-4"><button onclick="openLoanDetail(${l.id})" class="btn-blue px-3 py-1 text-xs">詳情</button></td>
      </tr>`;
    }).join('') + '</tbody></table>';
}

function filterLoans(f) {
  currentLoanFilter = f;
  document.querySelectorAll('.loan-filter').forEach(b => {
    b.classList.remove('bg-fju-blue','text-white'); b.classList.add('bg-gray-100','text-gray-500');
  });
  const btn = document.querySelector(`.loan-filter[data-f="${f}"]`);
  btn?.classList.add('bg-fju-blue','text-white'); btn?.classList.remove('bg-gray-100','text-gray-500');
  renderAdminLoans();
}

function openLoanDetail(id) {
  currentLoanId = id;
  const l = allLoans.find(x => x.id === id);
  if (!l) return;
  const names = (l.details||[]).map(d => `<li>${d.equipment?.name || '—'} × ${d.quantity}</li>`).join('');
  document.getElementById('loan-detail-body').innerHTML = `
    <div class="p-3 rounded-fju bg-fju-bg space-y-1">
      <div class="flex items-center justify-between">
        <span class="text-xs text-gray-400">序號</span>
        <span class="font-mono font-bold text-fju-blue">${l.serial_no}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-xs text-gray-400">狀態</span>
        ${loanStatusBadge(l.status)}
      </div>
    </div>
    <div><span class="text-xs text-gray-400">借用人</span><div class="font-bold text-fju-blue">${l.borrower?.name || l.borrower_id}</div></div>
    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
      <div><i class="fas fa-calendar mr-1 text-fju-yellow"></i>借用日：${l.borrow_date||'—'}</div>
      <div><i class="fas fa-calendar-check mr-1 text-fju-yellow"></i>預計還：${l.expected_return_date}</div>
    </div>
    <div>
      <span class="text-xs text-gray-400">器材清單</span>
      <ul class="mt-1 space-y-0.5 text-sm text-fju-blue list-disc list-inside">${names || '<li>（無明細）</li>'}</ul>
    </div>
    ${l.purpose ? `<div><span class="text-xs text-gray-400">借用事由</span><div class="text-sm">${l.purpose}</div></div>` : ''}
    ${l.activity_application_id ? `<div><span class="text-xs text-gray-400">關聯活動申請</span><div class="text-sm text-fju-blue">#${l.activity_application_id}</div></div>` : ''}
    ${l.reject_reason ? `<div class="p-3 rounded-fju bg-red-50 border border-red-100"><span class="text-xs text-red-400">拒絕原因</span><div class="text-sm text-red-600">${l.reject_reason}</div></div>` : ''}
  `;

  // Review action buttons depending on status
  const actEl = document.getElementById('loan-review-actions');
  actEl.innerHTML = '';
  actEl.classList.add('hidden');
  if (l.status === 'pending') {
    actEl.innerHTML = `
      <div class="flex gap-2">
        <button onclick="doLoanApprove()" class="flex-1 py-2.5 rounded-fju bg-green-500 text-white text-sm font-bold hover:bg-green-600"><i class="fas fa-check mr-1"></i>核准借用</button>
        <button onclick="doLoanReject()" class="flex-1 py-2.5 rounded-fju bg-red-500 text-white text-sm font-bold hover:bg-red-600"><i class="fas fa-times mr-1"></i>拒絕</button>
      </div>`;
    actEl.classList.remove('hidden');
  } else if (l.status === 'approved') {
    actEl.innerHTML = `
      <button onclick="doLoanPickup()" class="w-full py-2.5 rounded-fju bg-fju-blue text-white text-sm font-bold hover:bg-fju-blue/90"><i class="fas fa-box-open mr-1"></i>確認取件</button>`;
    actEl.classList.remove('hidden');
  } else if (l.status === 'picked_up') {
    actEl.innerHTML = `
      <button onclick="doLoanReturn()" class="w-full py-2.5 rounded-fju bg-yellow-400 text-fju-blue text-sm font-bold hover:bg-yellow-500"><i class="fas fa-undo mr-1"></i>確認歸還</button>`;
    actEl.classList.remove('hidden');
  }

  document.getElementById('loan-detail-modal').classList.remove('hidden');
}

function closeLoanDetailModal() { document.getElementById('loan-detail-modal').classList.add('hidden'); }

function doLoanApprove() {
  if (!confirm('確認核准此借用申請？')) return;
  fetch(`/api/equipment-loans/${currentLoanId}/approve`, {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ reviewer_id: 1 }),
  }).then(r => r.json()).then(res => {
    closeLoanDetailModal(); loadAdminLoans(); showToast(res.message || '已核准借用申請');
  });
}

function doLoanReject() {
  document.getElementById('loan-reject-reason').value = '';
  document.getElementById('loan-reject-error').classList.add('hidden');
  document.getElementById('loan-reject-modal').classList.remove('hidden');
}

function closeLoanRejectModal() { document.getElementById('loan-reject-modal').classList.add('hidden'); }

function confirmLoanReject() {
  const reason = document.getElementById('loan-reject-reason').value.trim();
  if (!reason) {
    document.getElementById('loan-reject-error').classList.remove('hidden');
    document.getElementById('loan-reject-reason').focus();
    return;
  }
  fetch(`/api/equipment-loans/${currentLoanId}/reject`, {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ reviewer_id: 1, reason }),
  }).then(r => r.json()).then(res => {
    closeLoanRejectModal(); closeLoanDetailModal(); loadAdminLoans(); showToast(res.message || '已拒絕');
  });
}

function doLoanPickup() {
  if (!confirm('確認借用人已完成取件？')) return;
  fetch(`/api/equipment-loans/${currentLoanId}/pickup`, {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({}),
  }).then(r => r.json()).then(res => {
    closeLoanDetailModal(); loadAdminLoans(); showToast(res.message || '取件完成');
  });
}

function doLoanReturn() {
  if (!confirm('確認全部器材已歸還？')) return;
  fetch(`/api/equipment-loans/${currentLoanId}/return`, {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({}),
  }).then(r => r.json()).then(res => {
    closeLoanDetailModal(); loadAdminLoans(); showToast(res.message || '歸還完成');
  });
}

let inventoryLoaded = false;
function toggleInventory() {
  const panel = document.getElementById('inventory-panel');
  panel.classList.toggle('hidden');
  if (!inventoryLoaded) { loadEquipment(); inventoryLoaded = true; }
}

// ─── Borrower cart & loan ────────────────────────────────────────────────
function toggleCart(id, name, certTypeId) {
  if (cart[id]) { delete cart[id]; } else { cart[id] = { id, name, certTypeId }; }
  updateCartBadge();
  renderEquipment();
}

function updateCartBadge() {
  const count = Object.keys(cart).length;
  const el = document.getElementById('cart-count');
  const btn = document.getElementById('multi-borrow-btn');
  if (el) el.textContent = count;
  if (btn) count > 0 ? btn.classList.remove('hidden') : btn.classList.add('hidden');
}

function openMultiBorrow() {
  const items = Object.values(cart);
  if (!items.length) return;
  document.getElementById('borrow-cart').innerHTML = items.map(i =>
    `<div class="flex items-center justify-between p-2 bg-fju-bg rounded-fju text-sm">
      <span class="font-medium text-fju-blue">${i.name}</span>
      ${i.certTypeId ? '<span class="text-xs text-orange-500">需證照</span>' : ''}
      <button onclick="toggleCart(${i.id},'${i.name.replace(/'/g,"\\'")}',${i.certTypeId||'null'})" class="text-red-400 text-xs">移除</button>
    </div>`
  ).join('');
  const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate()+1);
  document.getElementById('bw-return').value = tomorrow.toISOString().split('T')[0];
  document.getElementById('borrow-error').classList.add('hidden');
  document.getElementById('borrow-modal').classList.remove('hidden');
}

function closeBorrowModal() { document.getElementById('borrow-modal').classList.add('hidden'); }

function confirmBorrow() {
  const items = Object.values(cart);
  if (!items.length) return;
  const returnDate = document.getElementById('bw-return').value;
  if (!returnDate) { showBorrowError('請填寫預計歸還日'); return; }

  const payload = {
    borrower_id:          1,
    borrower_name:        document.getElementById('bw-name').value,
    expected_return_date: returnDate,
    purpose:              document.getElementById('bw-purpose').value,
    items:                items.map(i => ({ equipment_id: i.id, quantity: 1 })),
  };

  const aaSerial = document.getElementById('bw-aa-serial').value.trim();
  if (aaSerial) {
    fetch('/api/activity-applications?serial_no=' + encodeURIComponent(aaSerial))
      .then(r => r.json()).then(res => {
        const aa = res.data?.find?.(a => a.serial_no === aaSerial);
        if (aa) payload.activity_application_id = aa.id;
        submitLoan(payload);
      });
  } else {
    submitLoan(payload);
  }
}

function submitLoan(payload) {
  fetch('/api/equipment-loans', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify(payload),
  }).then(r => r.json()).then(res => {
    if (res.success) {
      closeBorrowModal();
      cart = {};
      updateCartBadge();
      loadEquipment();
      showToast('借用申請已送出！序號：' + res.serial_no);
    } else {
      showBorrowError(res.error || '送出失敗，請再試一次');
    }
  }).catch(() => showBorrowError('網路錯誤，請稍後再試'));
}

function returnSingleEq(id) {
  if (!confirm('確認歸還此器材？')) return;
  fetch('/api/equipment/return', {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ equipment_id: id }),
  }).then(r => r.json()).then(res => { showToast(res.message); loadEquipment(); });
}

function showBorrowError(msg) {
  const el = document.getElementById('borrow-error');
  el.textContent = msg; el.classList.remove('hidden');
}

// ─── Borrower: My loans ──────────────────────────────────────────────────
let myLoansLoaded = false;
function toggleMyLoans() {
  const panel = document.getElementById('my-loans-panel');
  panel.classList.toggle('hidden');
  if (!myLoansLoaded) { loadMyLoans(); myLoansLoaded = true; }
}

function loadMyLoans() {
  fetch('/api/equipment-loans?borrower_id=1').then(r => r.json()).then(res => {
    const loans = res.data || [];
    if (!loans.length) {
      document.getElementById('my-loans-table').innerHTML = '<div class="p-6 text-center text-gray-400">尚無借用紀錄</div>';
      return;
    }
    const statusLabel = s => ({ pending:'待審核', approved:'已核准', picked_up:'使用中', returned:'已歸還', rejected:'已拒絕', overdue:'逾期' }[s] || s);
    document.getElementById('my-loans-table').innerHTML =
      '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">序號</th><th class="p-4">器材清單</th><th class="p-4">歸還日</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>' +
      loans.map(l => {
        const names = (l.details||[]).map(d => d.equipment?.name || '').join('、') || '-';
        const canReturn = l.status === 'picked_up';
        return `<tr class="border-t border-gray-50 hover:bg-gray-50">
          <td class="p-4 text-xs text-gray-400 font-mono">${l.serial_no}</td>
          <td class="p-4 text-fju-blue font-medium">${names}</td>
          <td class="p-4 text-xs">${l.expected_return_date}</td>
          <td class="p-4">${loanStatusBadge(l.status)}</td>
          <td class="p-4">${canReturn ? `<button onclick="returnLoan(${l.id})" class="btn-blue px-3 py-1 text-xs">批次歸還</button>` : '-'}</td>
        </tr>`;
      }).join('') + '</tbody></table>';
  });
}

function returnLoan(id) {
  if (!confirm('確認批次歸還此申請的所有器材？')) return;
  fetch(`/api/equipment-loans/${id}/return`, {
    method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({}),
  }).then(r => r.json()).then(res => {
    showToast(res.message);
    myLoansLoaded = false;
    loadEquipment();
    loadMyLoans();
  });
}

// ─── Toast ───────────────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.createElement('div');
  t.className = 'fixed bottom-6 right-6 bg-fju-blue text-white px-5 py-3 rounded-fju shadow-lg text-sm z-50';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

// ─── Init ────────────────────────────────────────────────────────────────
if (IS_ADMIN) {
  loadAdminLoans();
} else {
  loadEquipment();
}
</script>
@endsection
