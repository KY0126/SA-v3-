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
        @if(in_array($role ?? 'student', ['admin'])) 器材借用管理 @else 設備借用 @endif
      </h2>
      <p class="text-xs text-gray-400 mt-0.5">
        @if(in_array($role ?? 'student', ['admin'])) 審核借用申請、確認取件與歸還 @else 選取器材加入清單，批次送出借用申請 @endif
      </p>
    </div>
    {{-- Only borrowers see multi-borrow button --}}
    @if(!in_array($role ?? 'student', ['admin']))
    <button onclick="openMultiBorrow()" id="multi-borrow-btn" class="hidden btn-yellow px-4 py-2 text-sm">
      <i class="fas fa-cart-plus mr-1"></i>批次借用 (<span id="cart-count">0</span>)
    </button>
    @endif
  </div>

  @if(in_array($role ?? 'student', ['admin']))
  {{-- ===== ADMIN / OFFICER VIEW: Loan Application Management ===== --}}

  {{-- View Mode Tabs --}}
  <div class="flex gap-2 flex-wrap items-center">
    <button onclick="setAdminView('list')" id="adm-tab-list" class="adm-tab px-4 py-2 rounded-fju text-sm bg-fju-blue text-white font-medium">借用申請列表</button>
    <button onclick="setAdminView('rank')" id="adm-tab-rank" class="adm-tab px-4 py-2 rounded-fju text-sm bg-gray-100 text-gray-500 font-medium"><i class="fas fa-trophy mr-1 text-fju-yellow"></i>熱門借用</button>
    <button onclick="setAdminView('history')" id="adm-tab-history" class="adm-tab px-4 py-2 rounded-fju text-sm bg-gray-100 text-gray-500 font-medium"><i class="fas fa-history mr-1"></i>過往紀錄</button>
    <button onclick="openFlowModal()" class="ml-auto px-4 py-2 rounded-fju text-sm bg-fju-blue/10 text-fju-blue font-medium hover:bg-fju-blue/20 transition-colors"><i class="fas fa-book-open mr-1"></i>借用流程</button>
    <button onclick="openPenaltyModal()" class="px-4 py-2 rounded-fju text-sm bg-red-50 text-red-600 font-medium hover:bg-red-100 transition-colors"><i class="fas fa-exclamation-triangle mr-1"></i>違規記點</button>
  </div>

  {{-- Status filter tabs for loans --}}
  <div id="adm-loan-section">
    <div class="flex gap-2 flex-wrap">
      @foreach(['all'=>'全部','pending'=>'待審核','approved'=>'已核准','picked_up'=>'使用中','returned'=>'已歸還','rejected'=>'已拒絕'] as $k=>$v)
      <button onclick="filterLoans('{{$k}}')" class="loan-filter px-4 py-2 rounded-fju text-sm {{ $k==='all'?'bg-fju-blue text-white':'bg-gray-100 text-gray-500' }}" data-f="{{$k}}">{{$v}}</button>
      @endforeach
    </div>
    {{-- Loan list table --}}
    <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
      <div id="loan-admin-list"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
    </div>
  </div>

  {{-- Admin Ranking View --}}
  <div id="adm-rank-view" class="hidden bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-trophy mr-2 text-fju-yellow"></i>熱門器材借用排行榜（近 3 個月）</h3></div>
    <div id="adm-rank-body" class="p-4 space-y-2"></div>
  </div>

  {{-- Admin History View --}}
  <div id="adm-history-view" class="hidden bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-history mr-2 text-fju-yellow"></i>過往借用紀錄</h3></div>
    <div id="adm-history-body"></div>
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

  {{-- View Mode Tabs --}}
  <div class="flex gap-2 flex-wrap items-center">
    <button onclick="setEqView('list')" id="eq-tab-list" class="eq-tab px-4 py-2 rounded-fju text-sm bg-fju-blue text-white font-medium">原始清單</button>
    <button onclick="setEqView('rank')" id="eq-tab-rank" class="eq-tab px-4 py-2 rounded-fju text-sm bg-gray-100 text-gray-500 font-medium"><i class="fas fa-trophy mr-1 text-fju-yellow"></i>熱門借用</button>
    <button onclick="setEqView('history')" id="eq-tab-history" class="eq-tab px-4 py-2 rounded-fju text-sm bg-gray-100 text-gray-500 font-medium"><i class="fas fa-history mr-1"></i>過往紀錄</button>
    <button onclick="openFlowModal()" class="ml-auto px-4 py-2 rounded-fju text-sm bg-fju-blue/10 text-fju-blue font-medium hover:bg-fju-blue/20 transition-colors"><i class="fas fa-book-open mr-1"></i>借用流程</button>
    <button onclick="openPenaltyModal()" class="px-4 py-2 rounded-fju text-sm bg-red-50 text-red-600 font-medium hover:bg-red-100 transition-colors"><i class="fas fa-exclamation-triangle mr-1"></i>違規記點</button>
  </div>

  <div id="eq-filter-bar" class="flex gap-2 flex-wrap items-center">
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

  <div id="eq-main-list" class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div id="equipment-table"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- Ranking View --}}
  <div id="eq-rank-view" class="hidden bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-trophy mr-2 text-fju-yellow"></i>熱門器材借用排行榜（近 3 個月）</h3></div>
    <div id="eq-rank-body" class="p-4 space-y-2"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin mr-2"></i>載入中...</div></div>
  </div>

  {{-- History View --}}
  <div id="eq-history-view" class="hidden bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-history mr-2 text-fju-yellow"></i>過往借用紀錄</h3></div>
    <div id="eq-history-body"></div>
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
  <div class="text-xs text-gray-500 mb-4">
    <span class="font-semibold">提醒：</span>批次借用前請先閱讀
    <button type="button" onclick="openFlowModal()" class="underline text-fju-blue">借用流程</button>
    及
    <button type="button" onclick="openPenaltyModal()" class="underline text-fju-blue">違規記點</button>。
  </div>
    <div class="space-y-3">
      <div>
        <label class="text-xs text-gray-400">單位代碼 <span class="text-red-400">*</span></label>
        <div class="relative">
          <input id="uc-input-bw" type="text" placeholder="輸入代碼或名稱篩選..." autocomplete="off"
            class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <input id="uc-val-bw" type="hidden">
          <div id="uc-drop-bw" class="hidden absolute z-50 w-full bg-white border border-gray-200 rounded-fju shadow-lg max-h-48 overflow-y-auto"></div>
        </div>
      </div>
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

{{-- Borrowing Flow Modal --}}
<div id="flow-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center" onclick="if(event.target===this)closeFlowModal()">
  <div class="bg-white rounded-fju-lg w-full max-w-2xl mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 sticky top-0 bg-white z-10">
      <h3 class="font-bold text-fju-blue text-lg"><i class="fas fa-book-open mr-2 text-fju-yellow"></i>借用流程</h3>
      <button onclick="closeFlowModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none"><i class="fas fa-times"></i></button>
    </div>
    <div class="p-6 space-y-4">

      {{-- Step 1 --}}
      <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg hover:border-fju-yellow/40 transition-colors">
        <div class="shrink-0 w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">1</div>
        <div>
          <div class="font-bold text-fju-blue text-sm mb-1">考取器材證</div>
          <div class="text-xs text-gray-500 space-y-0.5">
            <div><i class="fas fa-clock mr-1 text-fju-yellow"></i>使用期限</div>
            <div class="pl-4">・社團學會：<span class="font-medium text-fju-blue">一年</span></div>
            <div class="pl-4">・行政單位：<span class="font-medium text-fju-blue">兩年</span></div>
          </div>
        </div>
      </div>

      <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down text-lg"></i></div>

      {{-- Step 2 --}}
      <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg hover:border-fju-yellow/40 transition-colors">
        <div class="shrink-0 w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">2</div>
        <div>
          <div class="font-bold text-fju-blue text-sm mb-1">填寫器材申請表</div>
          <div class="text-xs text-gray-500 space-y-0.5">
            <div><i class="fas fa-download mr-1 text-fju-yellow"></i>至課指組網站下載器材借用申請表</div>
            <div><i class="fas fa-stamp mr-1 text-fju-yellow"></i>由 <span class="font-medium text-fju-blue">【輔導助教】</span> 或 <span class="font-medium text-fju-blue">【行政單位主管】</span> 蓋章核可</div>
          </div>
        </div>
      </div>

      <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down text-lg"></i></div>

      {{-- Step 3 --}}
      <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg hover:border-fju-yellow/40 transition-colors">
        <div class="shrink-0 w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">3</div>
        <div>
          <div class="font-bold text-fju-blue text-sm mb-1">至課指組預約</div>
          <div class="text-xs text-gray-500 space-y-1">
            <div><i class="fas fa-calendar-alt mr-1 text-fju-yellow"></i>預約時間：最早於領取前 <span class="font-medium text-fju-blue">30 日</span>，最晚於領取前 <span class="font-medium text-fju-blue">4 個工作天</span></div>
            <div class="pl-4 text-gray-400">※ 星期五領器材，<span class="text-fju-blue font-medium">星期一</span>為最後預約時間</div>
            <div class="pl-4 text-gray-400">※ 星期一領器材，<span class="text-fju-blue font-medium">上星期二</span>為最後預約時間</div>
          </div>
        </div>
      </div>

      <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down text-lg"></i></div>

      {{-- Step 4 --}}
      <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg hover:border-fju-yellow/40 transition-colors">
        <div class="shrink-0 w-10 h-10 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">4</div>
        <div>
          <div class="font-bold text-fju-blue text-sm mb-1">領取器材</div>
          <div class="text-xs text-gray-500 space-y-1">
            <div><i class="fas fa-id-card mr-1 text-fju-yellow"></i>領取器材需攜帶：</div>
            <div class="pl-4">・<span class="font-medium text-fju-blue">器材證</span>（限本人）</div>
            <div class="pl-4">・<span class="font-medium text-fju-blue">器材申請表（黃單）</span></div>
            <div class="mt-1 p-2 rounded-fju bg-fju-green/10 border border-fju-green/20 text-fju-green"><i class="fas fa-check-circle mr-1"></i>確認器材品項、數量、狀況無問題後，即可領取</div>
          </div>
        </div>
      </div>

      <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down text-lg"></i></div>

      {{-- Step 5 --}}
      <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg hover:border-fju-yellow/40 transition-colors">
        <div class="shrink-0 w-10 h-10 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue font-black text-sm">5</div>
        <div>
          <div class="font-bold text-fju-blue text-sm mb-1">歸還器材</div>
          <div class="text-xs text-gray-500 space-y-1">
            <div><i class="fas fa-undo mr-1 text-fju-yellow"></i>工讀生確認器材品項、數量、狀況無問題後，即可歸還</div>
            <div class="pl-4">並取回 <span class="font-medium text-fju-blue">器材證</span>、<span class="font-medium text-fju-blue">器材申請表</span></div>
          </div>
        </div>
      </div>

    </div>
    <div class="px-6 pb-5">
      <button onclick="closeFlowModal()" class="w-full py-2.5 rounded-fju bg-fju-blue text-white text-sm font-bold hover:bg-fju-blue/90 transition-colors">我知道了</button>
    </div>
  </div>
</div>

{{-- Penalty Points Modal --}}
<div id="penalty-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center" onclick="if(event.target===this)closePenaltyModal()">
  <div class="bg-white rounded-fju-lg w-full max-w-2xl mx-4 shadow-2xl max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between px-6 py-4 border-b border-red-100 sticky top-0 bg-white z-10">
      <h3 class="font-bold text-red-600 text-lg"><i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>違規記點規則</h3>
      <button onclick="closePenaltyModal()" class="text-gray-400 hover:text-gray-600 text-xl leading-none"><i class="fas fa-times"></i></button>
    </div>
    <div class="p-6 space-y-5">
      {{-- Basic Rules --}}
      <div class="p-4 rounded-fju-lg bg-red-50 border border-red-200">
        <div class="font-bold text-red-700 text-sm mb-2"><i class="fas fa-info-circle mr-1"></i>基本規則</div>
        <ul class="text-xs text-red-600 space-y-1.5">
          <li><i class="fas fa-dot-circle mr-1 text-red-400"></i>違規記點採<span class="font-bold">累計制</span>，以「<span class="font-bold">單位</span>」為記點對象</li>
          <li><i class="fas fa-dot-circle mr-1 text-red-400"></i>違規點數持續累積，不因幹部更替、社長交接或學年度變更而歸零</li>
          <li><i class="fas fa-dot-circle mr-1 text-red-400"></i>累計達 <span class="font-black text-red-700 text-base">3 點</span>時，將<span class="font-bold">停止該單位器材借用權</span></li>
        </ul>
      </div>

      {{-- Point Clearance --}}
      <div>
        <div class="font-bold text-fju-blue text-sm mb-2"><i class="fas fa-eraser mr-1 text-fju-yellow"></i>消點方式</div>
        <div class="p-4 rounded-fju-lg bg-fju-bg border border-gray-100 space-y-2 text-xs text-gray-600">
          <div><i class="fas fa-arrow-right mr-1 text-fju-yellow"></i>須至課指組向外場工讀生預約時間進行「<span class="font-bold text-fju-blue">勞動服務</span>」</div>
          <div><i class="fas fa-arrow-right mr-1 text-fju-yellow"></i>違規點數 <span class="font-bold text-fju-blue">1 點</span> 對應 <span class="font-bold text-fju-blue">4 小時</span>勞動服務，須一次完成，不得分次進行</div>
          <div class="pt-1">
            <div class="font-medium text-gray-500 mb-1.5">勞動服務最多可 8 人同時進行，計算方式：</div>
            <div class="grid grid-cols-2 gap-1.5 pl-2">
              <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-fju border border-gray-100"><span class="font-bold text-fju-blue w-8">1 人</span><i class="fas fa-arrow-right text-gray-300 text-xs"></i><span class="ml-1">4 小時／人</span></div>
              <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-fju border border-gray-100"><span class="font-bold text-fju-blue w-8">2 人</span><i class="fas fa-arrow-right text-gray-300 text-xs"></i><span class="ml-1">2 小時／人</span></div>
              <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-fju border border-gray-100"><span class="font-bold text-fju-blue w-8">4 人</span><i class="fas fa-arrow-right text-gray-300 text-xs"></i><span class="ml-1">1 小時／人</span></div>
              <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-fju border border-gray-100"><span class="font-bold text-fju-blue w-8">8 人</span><i class="fas fa-arrow-right text-gray-300 text-xs"></i><span class="ml-1">0.5 小時／人</span></div>
            </div>
          </div>
          <div class="mt-2 p-2 rounded-fju bg-fju-yellow/10 border border-fju-yellow/30 text-fju-blue font-medium"><i class="fas fa-exclamation-circle mr-1 text-fju-yellow"></i>※ 如臨時預約被記 3 點，需在領取器材前將違規點數消至低於 3 點</div>
        </div>
      </div>

      {{-- Point Standards --}}
      <div>
        <div class="font-bold text-fju-blue text-sm mb-2"><i class="fas fa-list mr-1 text-fju-yellow"></i>記點標準</div>
        <div class="space-y-2">
          <div class="flex items-start gap-3 p-3 rounded-fju bg-fju-bg border border-gray-100">
            <span class="shrink-0 px-2.5 py-1 rounded-full bg-yellow-100 text-yellow-700 font-black text-xs">1 點</span>
            <div class="text-xs text-gray-600 space-y-1">
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>器材逾期領取或逾期歸還</div>
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>未於預約時間領取器材，且未事先向課指組取消預約</div>
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>器材歸還時電力完全耗盡</div>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-fju bg-fju-bg border border-orange-100">
            <span class="shrink-0 px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 font-black text-xs">2 點</span>
            <div class="text-xs text-gray-600">
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>領取或歸還器材時，器材證持有人未親自到場</div>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-fju bg-fju-bg border border-red-100">
            <span class="shrink-0 px-2.5 py-1 rounded-full bg-red-100 text-red-700 font-black text-xs">3 點</span>
            <div class="text-xs text-gray-600">
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>未於規定時間內辦理器材預約（臨時預約）</div>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-fju bg-fju-bg border border-gray-200">
            <span class="shrink-0 px-2.5 py-1 rounded-full bg-gray-200 text-gray-600 font-black text-xs">其他</span>
            <div class="text-xs text-gray-600 space-y-1">
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>器材如有損壞或遺失，須依規定照價賠償，並視情節另行記點</div>
              <div><i class="fas fa-minus mr-1 text-gray-300"></i>器材超過兩日未領取或未歸還，且未事先通知課指組，將<span class="font-bold text-red-600">註銷該申請人器材證</span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="px-6 pb-5">
      <button onclick="closePenaltyModal()" class="w-full py-2.5 rounded-fju bg-fju-blue text-white text-sm font-bold hover:bg-fju-blue/90 transition-colors">我知道了</button>
    </div>
  </div>
</div>

{{-- First-time Guide Modal (2-page walkthrough, no outside-click close) --}}
<div id="guide-modal" class="hidden fixed inset-0 bg-black/70 z-[70] flex items-center justify-center">
  <div class="bg-white rounded-fju-lg w-full max-w-2xl mx-4 shadow-2xl max-h-[90vh] overflow-hidden flex flex-col">
    {{-- Header --}}
    <div class="px-6 py-4 bg-fju-blue flex items-center justify-between shrink-0">
      <div>
        <div class="text-white/60 text-xs mb-0.5">首次借用導引</div>
        <h3 class="font-bold text-white text-base" id="guide-title">借用流程</h3>
      </div>
      <div class="flex items-center gap-2">
        <div id="guide-dot-1" class="w-2 h-2 rounded-full bg-white transition-all"></div>
        <div id="guide-dot-2" class="w-2 h-2 rounded-full bg-white/30 transition-all"></div>
      </div>
    </div>
    {{-- Body --}}
    <div class="overflow-y-auto flex-1 p-6">
      {{-- Page 1: Flow --}}
      <div id="guide-page-1" class="space-y-4">
        <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg">
          <div class="shrink-0 w-9 h-9 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">1</div>
          <div><div class="font-bold text-fju-blue text-sm mb-1">考取器材證</div><div class="text-xs text-gray-500">・社團學會：<b class="text-fju-blue">一年</b>　・行政單位：<b class="text-fju-blue">兩年</b></div></div>
        </div>
        <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down"></i></div>
        <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg">
          <div class="shrink-0 w-9 h-9 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">2</div>
          <div><div class="font-bold text-fju-blue text-sm mb-1">填寫器材申請表</div><div class="text-xs text-gray-500 space-y-0.5"><div>・至課指組網站下載器材借用申請表</div><div>・由 <b class="text-fju-blue">【輔導助教】</b> 或 <b class="text-fju-blue">【行政單位主管】</b> 蓋章核可</div></div></div>
        </div>
        <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down"></i></div>
        <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg">
          <div class="shrink-0 w-9 h-9 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">3</div>
          <div><div class="font-bold text-fju-blue text-sm mb-1">至課指組預約</div><div class="text-xs text-gray-500 space-y-0.5"><div>・最早領取前 <b class="text-fju-blue">30 日</b>，最晚領取前 <b class="text-fju-blue">4 個工作天</b></div><div class="text-gray-400">※ 星期五領：星期一最後預約　※ 星期一領：上週二最後預約</div></div></div>
        </div>
        <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down"></i></div>
        <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg">
          <div class="shrink-0 w-9 h-9 rounded-full bg-fju-blue flex items-center justify-center text-white font-black text-sm">4</div>
          <div><div class="font-bold text-fju-blue text-sm mb-1">領取器材</div><div class="text-xs text-gray-500 space-y-0.5"><div>・攜帶 <b class="text-fju-blue">器材證（限本人）</b> 及 <b class="text-fju-blue">器材申請表（黃單）</b></div><div class="text-fju-green font-medium">確認品項、數量、狀況無問題後即可領取</div></div></div>
        </div>
        <div class="flex justify-center text-gray-300"><i class="fas fa-chevron-down"></i></div>
        <div class="flex gap-4 p-4 rounded-fju-lg border border-gray-100 bg-fju-bg">
          <div class="shrink-0 w-9 h-9 rounded-full bg-fju-yellow flex items-center justify-center text-fju-blue font-black text-sm">5</div>
          <div><div class="font-bold text-fju-blue text-sm mb-1">歸還器材</div><div class="text-xs text-gray-500">・工讀生確認品項、數量、狀況無問題後歸還，並取回 <b class="text-fju-blue">器材證</b> 與 <b class="text-fju-blue">申請表</b></div></div>
        </div>
      </div>
      {{-- Page 2: Penalty --}}
      <div id="guide-page-2" class="hidden space-y-4">
        <div class="p-4 rounded-fju-lg bg-red-50 border border-red-200">
          <div class="font-bold text-red-700 text-sm mb-2"><i class="fas fa-info-circle mr-1"></i>基本規則</div>
          <ul class="text-xs text-red-600 space-y-1">
            <li>・違規記點採<b>累計制</b>，以「<b>單位</b>」為記點對象，不因幹部更替或學年度歸零</li>
            <li>・累計達 <b class="text-base">3 點</b>時，<b>停止該單位器材借用權</b></li>
          </ul>
        </div>
        <div class="p-4 rounded-fju-lg bg-fju-bg border border-gray-100">
          <div class="font-bold text-fju-blue text-sm mb-2">消點：勞動服務</div>
          <div class="text-xs text-gray-500 space-y-1">
            <div>・1 點 = 4 小時勞動服務，須一次完成，不得分次進行</div>
            <div class="grid grid-cols-4 gap-1 mt-1.5">
              <div class="text-center bg-white rounded-fju border border-gray-100 p-1.5"><div class="font-bold text-fju-blue">1人</div><div class="text-gray-400">4h/人</div></div>
              <div class="text-center bg-white rounded-fju border border-gray-100 p-1.5"><div class="font-bold text-fju-blue">2人</div><div class="text-gray-400">2h/人</div></div>
              <div class="text-center bg-white rounded-fju border border-gray-100 p-1.5"><div class="font-bold text-fju-blue">4人</div><div class="text-gray-400">1h/人</div></div>
              <div class="text-center bg-white rounded-fju border border-gray-100 p-1.5"><div class="font-bold text-fju-blue">8人</div><div class="text-gray-400">0.5h/人</div></div>
            </div>
          </div>
        </div>
        <div class="space-y-2">
          <div class="flex items-start gap-3 p-3 rounded-fju bg-yellow-50 border border-yellow-200">
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-black text-xs">1點</span>
            <div class="text-xs text-gray-600">逾期領取或歸還 ／ 未事先取消預約即未領取 ／ 歸還時電力完全耗盡</div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-fju bg-orange-50 border border-orange-200">
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-black text-xs">2點</span>
            <div class="text-xs text-gray-600">領取或歸還時，器材證持有人未親自到場</div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-fju bg-red-50 border border-red-200">
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-black text-xs">3點</span>
            <div class="text-xs text-gray-600">未於規定時間內辦理器材預約（臨時預約）</div>
          </div>
          <div class="flex items-start gap-3 p-3 rounded-fju bg-gray-50 border border-gray-200">
            <span class="shrink-0 px-2 py-0.5 rounded-full bg-gray-200 text-gray-600 font-black text-xs">其他</span>
            <div class="text-xs text-gray-600">損壞或遺失依價賠償並另行記點 ／ 超過兩日未領取或未歸還且未通知，將<b class="text-red-600">註銷器材證</b></div>
          </div>
        </div>
      </div>
    </div>
    {{-- Footer --}}
    <div class="px-6 py-4 border-t border-gray-100 shrink-0 flex gap-2">
      <button id="guide-prev-btn" onclick="guideNav(-1)" class="hidden px-5 py-2.5 rounded-fju border border-gray-200 text-sm text-gray-500 hover:bg-gray-50">← 上一頁</button>
      <div class="flex-1"></div>
      <button id="guide-next-btn" onclick="guideNav(1)" class="px-5 py-2.5 rounded-fju bg-fju-blue text-white text-sm font-bold hover:bg-fju-blue/90">下一頁 →</button>
      <button id="guide-done-btn" onclick="completeGuide()" class="hidden px-5 py-2.5 rounded-fju bg-fju-green text-white text-sm font-bold hover:bg-fju-green/90"><i class="fas fa-check mr-1"></i>我已詳閱以上內容，開始借用</button>
    </div>
  </div>
</div>

<script>
const IS_ADMIN = {{ in_array($role ?? 'student', ['admin']) ? 'true' : 'false' }};
let allEquipment = [], currentFilter = 'all', cart = {};
let allLoans = [], currentLoanFilter = 'all', currentLoanId = null;
let currentEqView = 'list', allLoansPublic = null;

function setEqView(v) {
  currentEqView = v;
  document.querySelectorAll('.eq-tab').forEach(b => {
    b.classList.remove('bg-fju-blue','text-white'); b.classList.add('bg-gray-100','text-gray-500');
  });
  const tab = document.getElementById('eq-tab-'+v);
  tab?.classList.add('bg-fju-blue','text-white'); tab?.classList.remove('bg-gray-100','text-gray-500');
  document.getElementById('eq-filter-bar')?.classList.toggle('hidden', v !== 'list');
  document.getElementById('eq-main-list')?.classList.toggle('hidden', v !== 'list');
  document.getElementById('eq-rank-view')?.classList.toggle('hidden', v !== 'rank');
  document.getElementById('eq-history-view')?.classList.toggle('hidden', v !== 'history');
  if (v === 'rank' || v === 'history') {
    loadAllLoansIfNeeded(() => { if (v==='rank') renderEqRanking(); else renderEqHistory(); });
  }
}

function loadAllLoansIfNeeded(cb) {
  if (allLoansPublic !== null) { cb(); return; }
  fetch('/api/equipment-loans').then(r=>r.json()).then(res => { allLoansPublic = res.data||[]; cb(); });
}

function renderEqRanking() {
  const cutoff = new Date(); cutoff.setMonth(cutoff.getMonth()-3);
  const cutoffStr = cutoff.toISOString().split('T')[0];
  const counts = {}, eqNames = {};
  allLoansPublic.forEach(loan => {
    const d = loan.borrow_date || (loan.created_at||'').split('T')[0] || '';
    if (d && d < cutoffStr) return;
    (loan.details||[]).forEach(detail => {
      const id = detail.equipment_id;
      const name = detail.equipment?.name || '設備 #'+id;
      counts[id] = (counts[id]||0) + (detail.quantity||1);
      eqNames[id] = name;
    });
  });
  const ranked = Object.keys(counts).map(k=>({id:k,name:eqNames[k],count:counts[k]})).sort((a,b)=>b.count-a.count).slice(0,10);
  const el = document.getElementById('eq-rank-body');
  if (!ranked.length) { el.innerHTML='<div class="p-8 text-center text-gray-400">近 3 個月內暫無借用紀錄</div>'; return; }
  const medals=['🥇','🥈','🥉'];
  const medalBg=['bg-yellow-50 border-yellow-200','bg-gray-50 border-gray-300','bg-orange-50 border-orange-200'];
  el.innerHTML = ranked.map((item,i)=>{
    const cls = i<3 ? medalBg[i] : 'bg-white border-gray-100';
    const rank = i<3 ? `<span class="text-2xl">${medals[i]}</span>` : `<span class="text-xl font-black text-gray-300">#${i+1}</span>`;
    return `<div class="flex items-center justify-between p-4 border rounded-fju ${cls}">
      <div class="flex items-center gap-4">
        <div class="w-12 text-center shrink-0">${rank}</div>
        <div><div class="font-bold text-fju-blue">${item.name}</div><div class="text-xs text-gray-400">設備 ID ${item.id}</div></div>
      </div>
      <div class="text-right shrink-0">
        <div class="text-2xl font-black text-fju-blue">${item.count}</div>
        <div class="text-xs text-gray-400">次借用</div>
      </div>
    </div>`;
  }).join('');
}

function renderEqHistory() {
  const sorted = [...allLoansPublic].sort((a,b)=>{
    const da=a.borrow_date||(a.created_at||'').split('T')[0]||'';
    const db=b.borrow_date||(b.created_at||'').split('T')[0]||'';
    return db.localeCompare(da)||(b.id-a.id);
  });
  const el = document.getElementById('eq-history-body');
  if (!sorted.length) { el.innerHTML='<div class="p-8 text-center text-gray-400">暫無歷史紀錄</div>'; return; }
  el.innerHTML = '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">借用人</th><th class="p-4">器材</th><th class="p-4">借用日期</th><th class="p-4">狀態</th></tr></thead><tbody>'+
    sorted.map(l=>{
      const names = (l.details||[]).map(d=>d.equipment?.name||'').filter(Boolean).join('、')||'—';
      return `<tr class="border-t border-gray-50 hover:bg-gray-50">
        <td class="p-4 font-medium text-fju-blue">${l.borrower?.name||l.borrower_id||'—'}</td>
        <td class="p-4 text-xs text-gray-600 max-w-[200px] truncate" title="${names}">${names}</td>
        <td class="p-4 text-xs text-gray-500">${l.borrow_date||'—'}</td>
        <td class="p-4">${loanStatusBadge(l.status)}</td>
      </tr>`;
    }).join('')+'</tbody></table>';
}

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
    const safeName = e.name.replace(/'/g,"\\'").replace(/"/g,'&quot;');
    return `<button onclick="toggleCart(${e.id},'${safeName}',${e.cert_type_id||'null'})"
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
  if (!cart[id] && !localStorage.getItem('eq_guide_done')) {
    openGuideModal({ id, name, certTypeId });
    return;
  }
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
  _pendingBorrowModal = true;
  openGuideModal();
}

function showBorrowModal() {
  const items = Object.values(cart);
  document.getElementById('borrow-cart').innerHTML = items.map(i =>
    `<div class="flex items-center justify-between p-2 bg-fju-bg rounded-fju text-sm">
      <span class="font-medium text-fju-blue">${i.name}</span>
      ${i.certTypeId ? '<span class="text-xs text-orange-500">需證照</span>' : ''}
      <button onclick="toggleCart(${i.id},'${i.name.replace(/'/g,"\\'").replace(/"/g,'&quot;')}',${i.certTypeId||'null'})" class="text-red-400 text-xs">移除</button>
    </div>`
  ).join('');
  const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate()+1);
  document.getElementById('bw-return').value = tomorrow.toISOString().split('T')[0];
  document.getElementById('borrow-error').classList.add('hidden');
  resetUnitCombobox('bw');
  initUnitCombobox('bw');
  document.getElementById('borrow-modal').classList.remove('hidden');
}

function closeBorrowModal() { document.getElementById('borrow-modal').classList.add('hidden'); }

function confirmBorrow() {
  const items = Object.values(cart);
  if (!items.length) return;
  const unitCode = getUnitCode('bw');
  if (!unitCode) { showBorrowError('請選擇單位代碼'); return; }
  const returnDate = document.getElementById('bw-return').value;
  if (!returnDate) { showBorrowError('請填寫預計歸還日'); return; }

  const payload = {
    borrower_id:          1,
    unit_code:            unitCode,
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

// ─── Flow / Penalty / Guide Modals ───────────────────────────────────────
function openFlowModal() { document.getElementById('flow-modal').classList.remove('hidden'); }
function closeFlowModal() { document.getElementById('flow-modal').classList.add('hidden'); }
function openPenaltyModal() { document.getElementById('penalty-modal').classList.remove('hidden'); }
function closePenaltyModal() { document.getElementById('penalty-modal').classList.add('hidden'); }

let _pendingCartAction = null, _pendingBorrowModal = false, _guidePage = 1;

function openGuideModal(pendingAction) {
  _pendingCartAction = pendingAction;
  _pendingBorrowModal = pendingAction === undefined ? _pendingBorrowModal : false;
  _guidePage = 1;
  document.getElementById('guide-page-1').classList.remove('hidden');
  document.getElementById('guide-page-2').classList.add('hidden');
  document.getElementById('guide-title').textContent = '借用流程';
  document.getElementById('guide-dot-1').className = 'w-2 h-2 rounded-full bg-white transition-all';
  document.getElementById('guide-dot-2').className = 'w-2 h-2 rounded-full bg-white/30 transition-all';
  document.getElementById('guide-prev-btn').classList.add('hidden');
  document.getElementById('guide-next-btn').classList.remove('hidden');
  document.getElementById('guide-done-btn').classList.add('hidden');
  document.getElementById('guide-modal').classList.remove('hidden');
}

function showBorrowModal() {
  const items = Object.values(cart);
  document.getElementById('borrow-cart').innerHTML = items.map(i =>
    `<div class="flex items-center justify-between p-2 bg-fju-bg rounded-fju text-sm">
      <span class="font-medium text-fju-blue">${i.name}</span>
      ${i.certTypeId ? '<span class="text-xs text-orange-500">需證照</span>' : ''}
      <button onclick="toggleCart(${i.id},'${i.name.replace(/'/g,"\\'").replace(/"/g,'&quot;')}',${i.certTypeId||'null'})" class="text-red-400 text-xs">移除</button>
    </div>`
  ).join('');
  const tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate()+1);
  document.getElementById('bw-return').value = tomorrow.toISOString().split('T')[0];
  document.getElementById('borrow-error').classList.add('hidden');
  resetUnitCombobox('bw');
  initUnitCombobox('bw');
  document.getElementById('borrow-modal').classList.remove('hidden');
}

function guideNav(dir) {
  _guidePage += dir;
  const p1 = document.getElementById('guide-page-1');
  const p2 = document.getElementById('guide-page-2');
  if (_guidePage === 1) {
    p1.classList.remove('hidden'); p2.classList.add('hidden');
    document.getElementById('guide-title').textContent = '借用流程';
    document.getElementById('guide-dot-1').className = 'w-2 h-2 rounded-full bg-white transition-all';
    document.getElementById('guide-dot-2').className = 'w-2 h-2 rounded-full bg-white/30 transition-all';
    document.getElementById('guide-prev-btn').classList.add('hidden');
    document.getElementById('guide-next-btn').classList.remove('hidden');
    document.getElementById('guide-done-btn').classList.add('hidden');
  } else {
    p1.classList.add('hidden'); p2.classList.remove('hidden');
    document.getElementById('guide-title').textContent = '違規記點規則';
    document.getElementById('guide-dot-1').className = 'w-2 h-2 rounded-full bg-white/30 transition-all';
    document.getElementById('guide-dot-2').className = 'w-2 h-2 rounded-full bg-white transition-all';
    document.getElementById('guide-prev-btn').classList.remove('hidden');
    document.getElementById('guide-next-btn').classList.add('hidden');
    document.getElementById('guide-done-btn').classList.remove('hidden');
  }
}

function completeGuide() {
  localStorage.setItem('eq_guide_done', '1');
  document.getElementById('guide-modal').classList.add('hidden');
  if (_pendingCartAction) {
    const { id, name, certTypeId } = _pendingCartAction;
    cart[id] = { id, name, certTypeId };
    _pendingCartAction = null;
    updateCartBadge();
    renderEquipment();
  }
  if (_pendingBorrowModal) {
    _pendingBorrowModal = false;
    showBorrowModal();
  }
}

// ─── Toast ───────────────────────────────────────────────────────────────
function showToast(msg) {
  const t = document.createElement('div');
  t.className = 'fixed bottom-6 right-6 bg-fju-blue text-white px-5 py-3 rounded-fju shadow-lg text-sm z-50';
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3500);
}

// ─── Admin view tabs ─────────────────────────────────────────────────────
function setAdminView(v) {
  document.querySelectorAll('.adm-tab').forEach(b => {
    b.classList.remove('bg-fju-blue','text-white'); b.classList.add('bg-gray-100','text-gray-500');
  });
  const tab = document.getElementById('adm-tab-'+v);
  tab?.classList.add('bg-fju-blue','text-white'); tab?.classList.remove('bg-gray-100','text-gray-500');
  document.getElementById('adm-loan-section')?.classList.toggle('hidden', v !== 'list');
  document.getElementById('adm-rank-view')?.classList.toggle('hidden', v !== 'rank');
  document.getElementById('adm-history-view')?.classList.toggle('hidden', v !== 'history');
  if (v === 'rank') renderAdminRanking();
  if (v === 'history') renderAdminHistory();
}

function renderAdminRanking() {
  const cutoff = new Date(); cutoff.setMonth(cutoff.getMonth()-3);
  const cutoffStr = cutoff.toISOString().split('T')[0];
  const counts = {}, eqNames = {};
  allLoans.forEach(loan => {
    const d = loan.borrow_date || (loan.created_at||'').split('T')[0] || '';
    if (d && d < cutoffStr) return;
    (loan.details||[]).forEach(detail => {
      const id = detail.equipment_id;
      const name = detail.equipment?.name || '設備 #'+id;
      counts[id] = (counts[id]||0) + (detail.quantity||1);
      eqNames[id] = name;
    });
  });
  const ranked = Object.keys(counts).map(k=>({id:k,name:eqNames[k],count:counts[k]})).sort((a,b)=>b.count-a.count).slice(0,10);
  const el = document.getElementById('adm-rank-body');
  if (!ranked.length) { el.innerHTML='<div class="p-8 text-center text-gray-400">近 3 個月內暫無借用紀錄</div>'; return; }
  const medals=['🥇','🥈','🥉'];
  const medalBg=['bg-yellow-50 border-yellow-200','bg-gray-50 border-gray-300','bg-orange-50 border-orange-200'];
  el.innerHTML = ranked.map((item,i)=>{
    const cls = i<3 ? medalBg[i] : 'bg-white border-gray-100';
    const rank = i<3 ? `<span class="text-2xl">${medals[i]}</span>` : `<span class="text-xl font-black text-gray-300">#${i+1}</span>`;
    return `<div class="flex items-center justify-between p-4 border rounded-fju ${cls}">
      <div class="flex items-center gap-4">
        <div class="w-12 text-center shrink-0">${rank}</div>
        <div><div class="font-bold text-fju-blue">${item.name}</div><div class="text-xs text-gray-400">設備 ID ${item.id}</div></div>
      </div>
      <div class="text-right shrink-0">
        <div class="text-2xl font-black text-fju-blue">${item.count}</div>
        <div class="text-xs text-gray-400">次借用</div>
      </div>
    </div>`;
  }).join('');
}

function renderAdminHistory() {
  const sorted = [...allLoans].sort((a,b)=>{
    const da=a.borrow_date||(a.created_at||'').split('T')[0]||'';
    const db=b.borrow_date||(b.created_at||'').split('T')[0]||'';
    return db.localeCompare(da)||(b.id-a.id);
  });
  const el = document.getElementById('adm-history-body');
  if (!sorted.length) { el.innerHTML='<div class="p-8 text-center text-gray-400">暫無歷史紀錄</div>'; return; }
  el.innerHTML = '<table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">借用人</th><th class="p-4">器材</th><th class="p-4">借用日期</th><th class="p-4">預計還</th><th class="p-4">狀態</th></tr></thead><tbody>'+
    sorted.map(l=>{
      const names = (l.details||[]).map(d=>d.equipment?.name||'').filter(Boolean).join('、')||'—';
      return `<tr class="border-t border-gray-50 hover:bg-gray-50">
        <td class="p-4 font-medium text-fju-blue">${l.borrower?.name||l.borrower_id||'—'}</td>
        <td class="p-4 text-xs text-gray-600 max-w-[200px] truncate" title="${names}">${names}</td>
        <td class="p-4 text-xs text-gray-500">${l.borrow_date||'—'}</td>
        <td class="p-4 text-xs text-gray-500">${l.expected_return_date||'—'}</td>
        <td class="p-4">${loanStatusBadge(l.status)}</td>
      </tr>`;
    }).join('')+'</tbody></table>';
}

// ─── Init ────────────────────────────────────────────────────────────────
if (IS_ADMIN) {
  loadAdminLoans();
} else {
  loadEquipment();
}
</script>
@endsection
