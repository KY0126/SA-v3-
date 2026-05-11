@extends('layouts.shell')
@section('title', '活動申請')
@php $activePage = 'activity-application'; @endphp
@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="flex items-center justify-between flex-wrap gap-2">
    <div>
      <h2 class="font-bold text-fju-blue text-xl"><i class="fas fa-file-alt mr-2 text-fju-yellow"></i>活動申請</h2>
      <p class="text-xs text-gray-400 mt-0.5">送出後可關聯場地預約與器材借用，已核准申請序號可供其他模組引用</p>
    </div>
    @if(!in_array($role ?? 'student', ['admin']))
    <button onclick="openNewForm()" class="btn-yellow px-5 py-2.5 text-sm"><i class="fas fa-plus mr-2"></i>新增申請</button>
    @endif
  </div>

  {{-- Status filter tabs --}}
  <div class="flex gap-2 flex-wrap">
    @foreach(['all'=>'全部','submitted'=>'待審核','approved'=>'已核准','returned'=>'已退件','rejected'=>'已拒絕'] as $k=>$v)
    <button onclick="filterApps('{{$k}}')" class="aa-filter px-4 py-2 rounded-fju text-sm {{ $k==='all'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500' }}" data-f="{{$k}}">{{$v}}</button>
    @endforeach
  </div>

  {{-- List --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div id="aa-list">
      <div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div>
    </div>
  </div>

</div>

{{-- New/Edit application modal --}}
<div id="aa-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue text-lg" id="aa-modal-title"><i class="fas fa-file-alt mr-2 text-fju-yellow"></i>新增活動申請</h3>
      <button onclick="closeAaModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <div class="space-y-3">
      <div>
        <label class="text-xs text-gray-400">單位代碼 <span class="text-red-400">*</span></label>
        <div class="relative">
          <input id="uc-input-aa" type="text" placeholder="輸入代碼或名稱篩選..." autocomplete="off"
            class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <input id="uc-val-aa" type="hidden">
          <div id="uc-drop-aa" class="hidden absolute z-50 w-full bg-white border border-gray-200 rounded-fju shadow-lg max-h-48 overflow-y-auto"></div>
        </div>
      </div>
      <div>
        <label class="text-xs text-gray-400">活動名稱 <span class="text-red-400">*</span></label>
        <input id="aa-name" type="text" placeholder="例：2026 春季社團博覽會" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400">活動負責人</label>
          <input id="aa-responsible" type="text" placeholder="例：王小明" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
        <div>
          <label class="text-xs text-gray-400">系級</label>
          <input id="aa-department" type="text" placeholder="例：資工三甲" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400">聯絡電話</label>
          <input id="aa-phone" type="text" placeholder="例：0912-345-678" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
        <div>
          <label class="text-xs text-gray-400">工作人員人數</label>
          <input id="aa-staff" type="number" value="0" min="0" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400">活動日期 <span class="text-red-400">*</span></label>
          <input id="aa-date" type="date" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
        <div>
          <label class="text-xs text-gray-400">預計人數</label>
          <input id="aa-ppl" type="number" value="30" min="1" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400">開始時間 <span class="text-red-400">*</span></label>
          <input id="aa-start" type="time" value="09:00" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
        <div>
          <label class="text-xs text-gray-400">結束時間 <span class="text-red-400">*</span></label>
          <input id="aa-end" type="time" value="17:00" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
      </div>
      <div>
        <label class="text-xs text-gray-400">預計場地（說明）</label>
        <input id="aa-venue-desc" type="text" placeholder="例：SF134 研討室" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="text-xs text-gray-400">申請預算（元）</label>
          <input id="aa-budget" type="number" value="0" min="0" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
        <div>
          <label class="text-xs text-gray-400">單位名稱</label>
          <input id="aa-unit-name" type="text" placeholder="例：XX社" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
      </div>
      <div>
        <label class="text-xs text-gray-400">活動目的</label>
        <textarea id="aa-purpose" rows="3" placeholder="說明本次活動的目的..." class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm resize-none"></textarea>
      </div>
    </div>
    <div id="aa-error" class="hidden mt-3 p-3 rounded-fju bg-red-50 text-red-600 text-sm"></div>
    <div class="flex gap-2 mt-4">
      <button onclick="submitAaForm()" class="flex-1 btn-yellow py-2.5 text-sm"><i class="fas fa-paper-plane mr-1"></i>送出申請</button>
      <button onclick="closeAaModal()" class="flex-1 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
    </div>
  </div>
</div>

{{-- Detail/Review modal --}}
<div id="aa-detail-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-search mr-2 text-fju-yellow"></i>申請詳情</h3>
      <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <div id="aa-detail-body" class="space-y-3 text-sm"></div>
    <div id="aa-pdf-btn" class="mt-3 hidden">
      <a id="aa-pdf-link" href="#" target="_blank"
        class="flex items-center justify-center gap-2 w-full py-2 rounded-fju border border-fju-blue text-fju-blue text-sm hover:bg-fju-blue hover:text-white transition-all">
        <i class="fas fa-file-word"></i>下載申請單 Word 黃單
      </a>
    </div>
    <div id="aa-review-actions" class="flex gap-2 mt-4 hidden">
      <button onclick="doApprove()" class="flex-1 py-2.5 rounded-fju bg-green-500 text-white text-sm font-bold hover:bg-green-600"><i class="fas fa-check mr-1"></i>核准</button>
      <button onclick="doReturn()" class="flex-1 py-2.5 rounded-fju bg-yellow-400 text-fju-blue text-sm font-bold hover:bg-yellow-500"><i class="fas fa-undo mr-1"></i>退件</button>
      <button onclick="doReject()" class="flex-1 py-2.5 rounded-fju bg-red-500 text-white text-sm font-bold hover:bg-red-600"><i class="fas fa-times mr-1"></i>拒絕</button>
    </div>
  </div>
</div>

{{-- Reject reason modal (Task 1: forced non-empty input) --}}
<div id="aa-reject-modal" class="hidden fixed inset-0 bg-black/50 z-[60] flex items-center justify-center">
  <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-bold text-red-600 text-lg"><i class="fas fa-times-circle mr-2"></i>拒絕活動申請</h3>
      <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
    </div>
    <p class="text-sm text-gray-500 mb-3">拒絕原因為必填，申請人將收到通知。</p>
    <textarea id="aa-reject-reason" rows="4" placeholder="請輸入拒絕原因..."
      class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm resize-none focus:border-red-400 outline-none"></textarea>
    <div id="aa-reject-error" class="hidden mt-2 text-red-500 text-xs">
      <i class="fas fa-exclamation-circle mr-1"></i>拒絕原因不可空白
    </div>
    <div class="flex gap-2 mt-4">
      <button onclick="confirmReject()" class="flex-1 py-2.5 rounded-fju bg-red-500 text-white text-sm font-bold hover:bg-red-600">
        <i class="fas fa-times mr-1"></i>確認拒絕
      </button>
      <button onclick="closeRejectModal()" class="flex-1 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
    </div>
  </div>
</div>

<script>
  const IS_ADMIN = '{{ $role ?? "student" }}' === 'admin';
  let allApps = [],
    currentAaFilter = 'all',
    currentDetailId = null;

  // Global status badge helper (used in list and detail)
  function statusBadge(s) {
    return ({
      draft: '<span class="px-2 py-1 rounded-fju text-xs bg-gray-100 text-gray-500">草稿</span>',
      submitted: '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#fef9c3;color:#a16207;">待審核</span>',
      approved: '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#dcfce7;color:#15803d;">已核准</span>',
      returned: '<span class="px-2 py-1 rounded-fju text-xs bg-yellow-100 text-yellow-700">已退件</span>',
      rejected: '<span class="px-2 py-1 rounded-fju text-xs font-bold" style="background:#fee2e2;color:#dc2626;">已拒絕</span>',
    })[s] || `<span class="text-xs text-gray-400">${s}</span>`;
  }

  function loadApps() {
    fetch('/api/activity-applications').then(r => r.json()).then(res => {
      allApps = res.data || [];
      renderApps();
    }).catch(err => {
      console.error('API Error:', err);
      document.getElementById('aa-list').innerHTML = '<div class="p-8 text-center text-red-400">載入失敗，請重新整理頁面</div>';
    });
  }

  function renderApps() {
    let data = currentAaFilter === 'all' ? [...allApps] : allApps.filter(a => a.status === currentAaFilter);
    if (!data.length) {
      document.getElementById('aa-list').innerHTML = '<div class="p-8 text-center text-gray-400">目前沒有符合的申請</div>';
      return;
    }

    document.getElementById('aa-list').innerHTML =
      '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400">' +
      '<th class="p-4">序號</th><th class="p-4">活動名稱</th><th class="p-4">日期</th><th class="p-4">人數</th><th class="p-4">狀態</th><th class="p-4">操作</th></tr></thead><tbody>' +
      data.map(a => `<tr class="border-t border-gray-50 hover:bg-gray-50">
      <td class="p-4 text-xs text-gray-400">${a.serial_no}</td>
      <td class="p-4 font-medium text-fju-blue">${a.activity_name}</td>
      <td class="p-4 text-xs">${a.event_date} ${a.start_time}–${a.end_time}</td>
      <td class="p-4 text-xs">${a.expected_participants} 人</td>
      <td class="p-4">${statusBadge(a.status)}</td>
      <td class="p-4 flex flex-wrap gap-1">
        <button onclick="openDetail(${a.id})" class="btn-blue px-3 py-1 text-xs">詳情</button>
        <button onclick="downloadAaWord(${a.id})" class="px-3 py-1 rounded-fju border border-gray-200 text-xs text-gray-600 hover:bg-gray-100">下載 Word 黃單</button>
        ${a.status === 'approved' ? `<button onclick="copySerial('${a.serial_no}')" class="btn-yellow px-3 py-1 text-xs" title="複製序號供場地預約/器材借用使用"><i class="fas fa-copy mr-1"></i>複製序號</button>` : ''}
      </td>
    </tr>`).join('') + '</tbody></table>';
  }

  function filterApps(f) {
    currentAaFilter = f;
    document.querySelectorAll('.aa-filter').forEach(b => {
      b.classList.remove('bg-fju-blue', 'text-white');
      b.classList.add('bg-gray-100', 'text-gray-500');
    });
    const btn = document.querySelector(`.aa-filter[data-f="${f}"]`);
    btn?.classList.add('bg-fju-blue', 'text-white');
    btn?.classList.remove('bg-gray-100', 'text-gray-500');
    renderApps();
  }

  function openNewForm() {
    if (IS_ADMIN) return;
    currentDetailId = null;
    document.getElementById('aa-modal-title').innerHTML = '<i class="fas fa-file-alt mr-2 text-fju-yellow"></i>新增活動申請';
    document.getElementById('aa-name').value = '';
    document.getElementById('aa-responsible').value = '';
    document.getElementById('aa-department').value = '';
    document.getElementById('aa-phone').value = '';
    document.getElementById('aa-staff').value = '0';
    document.getElementById('aa-purpose').value = '';
    document.getElementById('aa-venue-desc').value = '';
    document.getElementById('aa-budget').value = '0';
    document.getElementById('aa-unit-name').value = '';
    document.getElementById('aa-ppl').value = '30';
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('aa-date').value = tomorrow.toISOString().split('T')[0];
    document.getElementById('aa-error').classList.add('hidden');
    resetUnitCombobox('aa');
    initUnitCombobox('aa', 'aa-unit-name');
    document.getElementById('aa-modal').classList.remove('hidden');
  }

  function closeAaModal() {
    document.getElementById('aa-modal').classList.add('hidden');
  }

  function submitAaForm() {
    const name = document.getElementById('aa-name').value.trim();
    const date = document.getElementById('aa-date').value;
    const start = document.getElementById('aa-start').value;
    const end = document.getElementById('aa-end').value;
    const unitCode = getUnitCode('aa');
    if (!unitCode) {
      showAaError('請選擇單位代碼');
      return;
    }
    if (!name || !date || !start || !end) {
      showAaError('請填寫必填欄位（活動名稱、日期、時間）');
      return;
    }
    const payload = {
      applicant_id: 1,
      unit_code: unitCode,
      unit_name: document.getElementById('aa-unit-name').value,
      responsible_person: document.getElementById('aa-responsible').value,
      department: document.getElementById('aa-department').value,
      contact_phone: document.getElementById('aa-phone').value,
      activity_name: name,
      event_date: date,
      start_time: start,
      end_time: end,
      venue_description: document.getElementById('aa-venue-desc').value,
      expected_participants: parseInt(document.getElementById('aa-ppl').value) || 0,
      staff_count: parseInt(document.getElementById('aa-staff').value) || 0,
      budget_requested: parseFloat(document.getElementById('aa-budget').value) || 0,
      purpose: document.getElementById('aa-purpose').value,
    };
    fetch('/api/activity-applications', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload),
    }).then(r => r.json()).then(res => {
      if (res.success) {
        closeAaModal();
        loadApps();
        showToast('活動申請已送出！序號：' + res.serial_no);
      } else {
        showAaError(res.message || res.error || '送出失敗');
      }
    }).catch(() => showAaError('網路錯誤，請稍後再試'));
  }

  function openDetail(id) {
    currentDetailId = id;
    const a = allApps.find(x => x.id === id);
    if (!a) return;

    document.getElementById('aa-detail-body').innerHTML = `
    <div class="p-3 rounded-fju bg-fju-bg space-y-1">
      <div class="flex items-center justify-between">
        <span class="text-xs text-gray-400">序號</span>
        <span class="font-mono font-bold text-fju-blue">${a.serial_no}</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-xs text-gray-400">狀態</span>
        ${statusBadge(a.status)}
      </div>
    </div>
    <div><span class="text-xs text-gray-400">活動名稱</span><div class="font-bold text-fju-blue">${a.activity_name}</div></div>
    <div class="grid grid-cols-2 gap-2 text-xs text-gray-500">
      <div><i class="fas fa-calendar mr-1 text-fju-yellow"></i>${a.event_date}</div>
      <div><i class="fas fa-clock mr-1 text-fju-yellow"></i>${a.start_time} – ${a.end_time}</div>
      <div><i class="fas fa-users mr-1 text-fju-yellow"></i>${a.expected_participants} 人</div>
      <div><i class="fas fa-coins mr-1 text-fju-yellow"></i>預算 $${parseInt(a.budget_requested).toLocaleString()}</div>
    </div>
    ${a.venue_description ? `<div><span class="text-xs text-gray-400">預計場地</span><div class="text-sm">${a.venue_description}</div></div>` : ''}
    ${a.purpose ? `<div><span class="text-xs text-gray-400">活動目的</span><div class="text-sm">${a.purpose}</div></div>` : ''}
    ${a.reject_reason ? `<div class="p-3 rounded-fju bg-red-50 border border-red-100"><span class="text-xs text-red-400">退件/拒絕原因</span><div class="text-sm text-red-600">${a.reject_reason}</div></div>` : ''}
    ${a.cancellation_status === 'pending' ? `<div class="p-3 rounded-fju bg-yellow-50 border border-yellow-200"><span class="text-xs text-yellow-600">✉️ 待審核的取消申請</span><div class="text-sm text-yellow-700 mt-1">${a.cancellation_reason || '申請人要求取消'}</div></div>` : ''}
  `;

    // PDF button — opens the static blank form for the user to fill in
    const pdfBtn = document.getElementById('aa-pdf-btn');
    const pdfLink = document.getElementById('aa-pdf-link');
    pdfLink.href = '/activity-applications/' + id + '/word';
    pdfLink.setAttribute('target', '_blank');
    pdfLink.removeAttribute('download');
    pdfBtn.classList.remove('hidden');

    // Show review actions for admin only on submitted applications
    const role = '{{ $role ?? "student" }}';
    const canReview = (role === 'admin') && a.status === 'submitted';
    const hasCancellationPending = (role === 'admin') && a.status === 'approved' && a.cancellation_status === 'pending';
    const isApplicant = (a.applicant_id === 1); // 假設目前使用者id是1，實際應由後端提供
    const canRequestCancel = (role !== 'admin') && (a.status === 'approved' || a.status === 'submitted' || a.status === 'draft') && isApplicant;

    // 處理取消審核狀態
    if (hasCancellationPending) {
      document.getElementById('aa-review-actions').innerHTML = `
        <button onclick="doApproveCancellation()" class="flex-1 py-2.5 rounded-fju bg-green-500 text-white text-sm font-bold hover:bg-green-600"><i class="fas fa-check mr-1"></i>批准取消</button>
        <button onclick="doRejectCancellation()" class="flex-1 py-2.5 rounded-fju bg-red-500 text-white text-sm font-bold hover:bg-red-600"><i class="fas fa-times mr-1"></i>拒絕取消</button>
      `;
      document.getElementById('aa-review-actions').classList.remove('hidden');
    } else if (canReview) {
      document.getElementById('aa-review-actions').innerHTML = `
        <button onclick="doApprove()" class="flex-1 py-2.5 rounded-fju bg-green-500 text-white text-sm font-bold hover:bg-green-600"><i class="fas fa-check mr-1"></i>核准</button>
        <button onclick="doReturn()" class="flex-1 py-2.5 rounded-fju bg-yellow-400 text-fju-blue text-sm font-bold hover:bg-yellow-500"><i class="fas fa-undo mr-1"></i>退件</button>
        <button onclick="doReject()" class="flex-1 py-2.5 rounded-fju bg-red-500 text-white text-sm font-bold hover:bg-red-600"><i class="fas fa-times mr-1"></i>拒絕</button>
      `;
      document.getElementById('aa-review-actions').classList.remove('hidden');
    } else if (canRequestCancel) {
      // 申請人可取消待審核或已核准的申請
      if (a.status === 'approved' && !a.cancellation_status) {
        document.getElementById('aa-review-actions').innerHTML = `
          <button onclick="doRequestCancellation()" class="flex-1 py-2.5 rounded-fju bg-orange-500 text-white text-sm font-bold hover:bg-orange-600"><i class="fas fa-trash mr-1"></i>申請取消</button>
        `;
        document.getElementById('aa-review-actions').classList.remove('hidden');
      } else if (['submitted', 'draft'].includes(a.status)) {
        // 待審核或草稿可直接取消
        document.getElementById('aa-review-actions').innerHTML = `
          <button onclick="doRequestCancellation()" class="flex-1 py-2.5 rounded-fju bg-orange-500 text-white text-sm font-bold hover:bg-orange-600"><i class="fas fa-trash mr-1"></i>取消申請</button>
        `;
        document.getElementById('aa-review-actions').classList.remove('hidden');
      } else {
        document.getElementById('aa-review-actions').classList.add('hidden');
      }
    } else {
      document.getElementById('aa-review-actions').classList.add('hidden');
    }
  }

  function closeDetailModal() {
    document.getElementById('aa-detail-modal').classList.add('hidden');
  }

  function downloadAaWord(id) {
    window.open('/activity-applications/' + id + '/word', '_blank');
  }

  function doApprove() {
    if (!confirm('確認核准此活動申請？')) return;
    fetch(`/api/activity-applications/${currentDetailId}/approve`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        reviewer_id: 1
      }),
    }).then(r => r.json()).then(res => {
      closeDetailModal();
      loadApps();
      showToast(res.message || '已核准');
    });
  }

  function doReturn() {
    const reason = prompt('退件原因（將通知申請人修改）：');
    if (reason === null) return;
    fetch(`/api/activity-applications/${currentDetailId}/return`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        reviewer_id: 1,
        reason
      }),
    }).then(r => r.json()).then(res => {
      closeDetailModal();
      loadApps();
      showToast(res.message || '已退件');
    });
  }

  // Task 1: Reject now uses a modal requiring non-empty reason
  function doReject() {
    document.getElementById('aa-reject-reason').value = '';
    document.getElementById('aa-reject-error').classList.add('hidden');
    document.getElementById('aa-reject-modal').classList.remove('hidden');
  }

  function closeRejectModal() {
    document.getElementById('aa-reject-modal').classList.add('hidden');
  }

  function confirmReject() {
    const reason = document.getElementById('aa-reject-reason').value.trim();
    if (!reason) {
      document.getElementById('aa-reject-error').classList.remove('hidden');
      document.getElementById('aa-reject-reason').focus();
      return;
    }
    document.getElementById('aa-reject-error').classList.add('hidden');
    fetch(`/api/activity-applications/${currentDetailId}/reject`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        reviewer_id: 1,
        reason
      }),
    }).then(r => r.json()).then(res => {
      closeRejectModal();
      closeDetailModal();
      loadApps();
      showToast(res.message || '已拒絕');
    });
  }

  function doApproveCancellation() {
    if (!confirm('確認批准此取消申請？')) return;
    fetch(`/api/activity-applications/${currentDetailId}/approve-cancellation`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({})
    }).then(r => r.json()).then(res => {
      if (res.success || res.message) {
        closeDetailModal();
        loadApps();
        showToast(res.message || '取消申請已批准');
      } else {
        showToast(res.error || '批准失敗');
      }
    });
  }

  function doRejectCancellation() {
    const reason = prompt('拒絕取消的原因：');
    if (reason === null) return;
    fetch(`/api/activity-applications/${currentDetailId}/reject-cancellation`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        reason
      })
    }).then(r => r.json()).then(res => {
      if (res.success || res.message) {
        closeDetailModal();
        loadApps();
        showToast(res.message || '取消申請已拒絕');
      } else {
        showToast(res.error || '拒絕失敗');
      }
    });
  }

  function doRequestCancellation() {
    const reason = prompt('申請取消的原因（可選）：') || '申請人要求取消';
    if (reason === null) return;
    const a = allApps.find(x => x.id === currentDetailId);
    if (!a) return;

    // 待審核或草稿 → 直接取消（無需理由）
    if (['submitted', 'draft'].includes(a.status)) {
      reason = '';
    }

    fetch(`/api/activity-applications/${currentDetailId}/request-cancellation`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        reason
      })
    }).then(r => r.json()).then(res => {
      if (res.success || res.message) {
        closeDetailModal();
        loadApps();
        showToast(res.message || '取消申請已提交');
      } else {
        showToast(res.error || '提交失敗');
      }
    });
  }

  function copySerial(serial) {
    navigator.clipboard?.writeText(serial).then(() => showToast('已複製序號：' + serial));
  }

  function showAaError(msg) {
    const el = document.getElementById('aa-error');
    el.textContent = msg;
    el.classList.remove('hidden');
  }

  function showToast(msg) {
    const t = document.createElement('div');
    t.className = 'fixed bottom-6 right-6 bg-fju-blue text-white px-5 py-3 rounded-fju shadow-lg text-sm z-50';
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 3500);
  }

  loadApps();
</script>
@endsection