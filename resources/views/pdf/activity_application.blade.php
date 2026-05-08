<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; background: #fff; }
  .page { padding: 28px 32px; }

  /* Header */
  .header { border-bottom: 3px solid #1e3a8a; padding-bottom: 12px; margin-bottom: 18px; }
  .header-top { display: flex; justify-content: space-between; align-items: flex-start; }
  .school-name { font-size: 13px; font-weight: bold; color: #1e3a8a; }
  .form-title { font-size: 18px; font-weight: bold; color: #1e3a8a; text-align: center; margin: 8px 0 4px; }
  .serial-box { text-align: right; font-size: 10px; color: #64748b; }
  .serial-no { font-size: 14px; font-weight: bold; color: #1e3a8a; font-family: monospace; }

  /* Status badge */
  .status-badge { display: inline-block; padding: 2px 10px; border-radius: 4px; font-size: 10px; font-weight: bold; }
  .status-submitted { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
  .status-approved  { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
  .status-rejected  { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
  .status-returned  { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
  .status-draft     { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

  /* Sections */
  .section { margin-bottom: 14px; }
  .section-title { font-size: 11px; font-weight: bold; color: #fff; background: #1e3a8a; padding: 4px 8px; margin-bottom: 0; border-radius: 3px 3px 0 0; }
  .section-body { border: 1px solid #cbd5e1; border-top: none; border-radius: 0 0 3px 3px; padding: 0; }

  /* Table rows */
  .field-table { width: 100%; border-collapse: collapse; }
  .field-table td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
  .field-table tr:last-child td { border-bottom: none; }
  .field-label { width: 30%; color: #64748b; font-size: 10px; font-weight: bold; white-space: nowrap; }
  .field-value { color: #1e293b; }
  .field-value-bold { color: #1e3a8a; font-weight: bold; }

  /* Checkbox items */
  .checkbox-row { display: flex; gap: 20px; padding: 6px 8px; }
  .checkbox-item { display: flex; align-items: center; gap: 4px; }
  .checkbox { width: 12px; height: 12px; border: 1.5px solid #64748b; display: inline-block; border-radius: 2px; }
  .checkbox-label { font-size: 10px; color: #334155; }

  /* Purpose / description */
  .text-block { padding: 7px 8px; min-height: 48px; line-height: 1.6; }

  /* Signature section */
  .sig-table { width: 100%; border-collapse: collapse; }
  .sig-table td { padding: 8px; border: 1px solid #cbd5e1; text-align: center; width: 33.3%; }
  .sig-label { font-size: 9px; color: #64748b; margin-bottom: 28px; }
  .sig-line { border-top: 1px solid #94a3b8; margin-top: 4px; padding-top: 3px; font-size: 9px; color: #94a3b8; }

  /* Footer */
  .footer { margin-top: 18px; border-top: 1px solid #e2e8f0; padding-top: 6px; font-size: 9px; color: #94a3b8; display: flex; justify-content: space-between; }

  /* Watermark for non-approved */
  .watermark { position: fixed; top: 40%; left: 10%; font-size: 64px; color: rgba(239,68,68,0.08); transform: rotate(-30deg); font-weight: bold; z-index: -1; }
</style>
</head>
<body>
<div class="page">

  @if($app->status !== 'approved')
  <div class="watermark">{{ $app->status === 'rejected' ? '已拒絕' : ($app->status === 'returned' ? '已退件' : '審核中') }}</div>
  @endif

  {{-- Header --}}
  <div class="header">
    <div class="header-top">
      <div>
        <div class="school-name">輔仁大學 課外活動組</div>
        <div style="font-size:9px;color:#64748b;margin-top:2px;">Office of Student Activities, Fu Jen Catholic University</div>
      </div>
      <div class="serial-box">
        <div>申請序號</div>
        <div class="serial-no">{{ $app->serial_no }}</div>
        <div style="margin-top:3px;">
          @php
            $statusMap = ['draft'=>'草稿','submitted'=>'待審核','approved'=>'已核准','returned'=>'已退件','rejected'=>'已拒絕'];
            $statusClass = 'status-' . $app->status;
          @endphp
          <span class="status-badge {{ $statusClass }}">{{ $statusMap[$app->status] ?? $app->status }}</span>
        </div>
      </div>
    </div>
    <div class="form-title">課外活動申請單</div>
    <div style="text-align:center;font-size:9px;color:#64748b;">依據輔仁大學學生活動管理辦法辦理</div>
  </div>

  {{-- Basic Info --}}
  <div class="section">
    <div class="section-title">一、基本資料</div>
    <div class="section-body">
      <table class="field-table">
        <tr>
          <td class="field-label">活動名稱</td>
          <td class="field-value-bold" colspan="3">{{ $app->activity_name }}</td>
        </tr>
        <tr>
          <td class="field-label">活動日期</td>
          <td class="field-value">{{ $app->event_date }}</td>
          <td class="field-label">活動時間</td>
          <td class="field-value">{{ $app->start_time }} – {{ $app->end_time }}</td>
        </tr>
        <tr>
          <td class="field-label">預計人數</td>
          <td class="field-value">{{ $app->expected_participants }} 人</td>
          <td class="field-label">申請預算</td>
          <td class="field-value">$ {{ number_format($app->budget_requested, 0) }} 元</td>
        </tr>
        <tr>
          <td class="field-label">預計場地</td>
          <td class="field-value" colspan="3">{{ $app->venue_description ?: '（未填寫）' }}</td>
        </tr>
      </table>
    </div>
  </div>

  {{-- Applicant Info --}}
  <div class="section">
    <div class="section-title">二、申請人資料</div>
    <div class="section-body">
      <table class="field-table">
        <tr>
          <td class="field-label">申請人</td>
          <td class="field-value">{{ $app->applicant->name ?? '（未知）' }}</td>
          <td class="field-label">聯絡信箱</td>
          <td class="field-value">{{ $app->applicant->email ?? '—' }}</td>
        </tr>
        <tr>
          <td class="field-label">主辦單位</td>
          <td class="field-value">{{ $app->club_id ? '社團 #' . $app->club_id : '個人申請' }}</td>
          <td class="field-label">送出時間</td>
          <td class="field-value">{{ $app->created_at ? $app->created_at->format('Y-m-d H:i') : '—' }}</td>
        </tr>
      </table>
    </div>
  </div>

  {{-- Activity Description --}}
  <div class="section">
    <div class="section-title">三、活動目的與說明</div>
    <div class="section-body">
      <div class="text-block">{{ $app->purpose ?: '（申請人未填寫活動目的）' }}</div>
    </div>
  </div>

  {{-- Special Items --}}
  <div class="section">
    <div class="section-title">四、特殊項目申請（如有請勾選）</div>
    <div class="section-body">
      <div class="checkbox-row">
        <div class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">使用酒精（需安全說明）</span></div>
        <div class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">用火/煙火（需消防許可）</span></div>
        <div class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">設置攤位/帳棚</span></div>
        <div class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">對外公開宣傳</span></div>
        <div class="checkbox-item"><span class="checkbox"></span><span class="checkbox-label">其他（請說明）</span></div>
      </div>
    </div>
  </div>

  {{-- Review Result --}}
  @if(in_array($app->status, ['approved', 'rejected', 'returned']))
  <div class="section">
    <div class="section-title">五、審核結果</div>
    <div class="section-body">
      <table class="field-table">
        <tr>
          <td class="field-label">審核結果</td>
          <td class="field-value-bold"><span class="status-badge {{ $statusClass }}">{{ $statusMap[$app->status] }}</span></td>
          <td class="field-label">審核時間</td>
          <td class="field-value">{{ $app->reviewed_at ? \Carbon\Carbon::parse($app->reviewed_at)->format('Y-m-d H:i') : '—' }}</td>
        </tr>
        @if($app->reject_reason)
        <tr>
          <td class="field-label">退件/拒絕原因</td>
          <td class="field-value" colspan="3" style="color:#dc2626;">{{ $app->reject_reason }}</td>
        </tr>
        @endif
        <tr>
          <td class="field-label">審核人員</td>
          <td class="field-value" colspan="3">{{ $app->reviewer->name ?? '（課指組承辦人）' }}</td>
        </tr>
      </table>
    </div>
  </div>
  @endif

  {{-- Signatures --}}
  <div class="section">
    <div class="section-title">{{ in_array($app->status, ['approved','rejected','returned']) ? '六' : '五' }}、簽章欄</div>
    <div class="section-body">
      <table class="sig-table">
        <tr>
          <td>
            <div class="sig-label">申請人簽章</div>
            <div class="sig-line">{{ $app->applicant->name ?? '' }}</div>
          </td>
          <td>
            <div class="sig-label">指導老師 / 社團幹部</div>
            <div class="sig-line">&nbsp;</div>
          </td>
          <td>
            <div class="sig-label">課指組承辦人核章</div>
            <div class="sig-line">{{ $app->reviewer->name ?? '' }}</div>
          </td>
        </tr>
      </table>
    </div>
  </div>

  {{-- Footer --}}
  <div class="footer">
    <span>輔仁大學課外活動組器材與場地預約平台</span>
    <span>列印時間：{{ now()->format('Y-m-d H:i') }}</span>
    <span>序號：{{ $app->serial_no }}</span>
  </div>

</div>
</body>
</html>
