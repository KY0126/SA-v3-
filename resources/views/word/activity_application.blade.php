<!DOCTYPE html>
<html lang="zh-TW">
<head>
<meta charset="UTF-8">
<title>活動申請單</title>
<style>
  body { font-family: 'Microsoft JhengHei', '微軟正黑體', sans-serif; color: #000; font-size: 12pt; }
  table { width: 100%; border-collapse: collapse; }
  td, th { border: 1px solid #000; padding: 6px; vertical-align: top; }
  .no-border { border: none; }
  .title-row td { border: none; text-align: center; font-size: 16pt; font-weight: bold; padding: 12px 0; }
  .header-small { font-size: 10pt; }
  .section-title { font-weight: bold; background: #f2f2f2; }
  .wide { width: 40%; }
  .narrow { width: 15%; }
  .small-cell { font-size: 10pt; }
  .note { font-size: 10pt; line-height: 1.4; }
</style>
</head>
<body>
  <table>
    <tr class="title-row"><td colspan="8">輔仁大學學生自治組織與社團活動申請表</td></tr>
    <tr>
      <td class="small-cell">※單位代碼</td>
      <td colspan="3">{{ $app->unit_code ?? ' ' }}</td>
      <td class="small-cell">※核准流水編號</td>
      <td colspan="3">{{ $app->serial_no }}</td>
    </tr>
    <tr>
      <td class="small-cell">學校核定</td>
      <td colspan="3" style="height:40px;"></td>
      <td class="small-cell">課指組</td>
      <td colspan="2" style="height:40px;"></td>
      <td class="small-cell">軍訓室</td>
    </tr>
    <tr>
      <td class="small-cell">單位名稱</td>
      <td colspan="7">{{ $app->unit_name ?? $app->unit_code ?? ' ' }}</td>
    </tr>
    <tr>
      <td class="small-cell">活動名稱</td>
      <td colspan="7">{{ $app->activity_name }}</td>
    </tr>
    <tr>
      <td class="small-cell">活動負責人</td>
      <td>{{ $app->responsible_person ?? $app->applicant->name ?? ' ' }}</td>
      <td class="small-cell">系級</td>
      <td>{{ $app->department ?? $app->applicant->department ?? ' ' }}</td>
      <td class="small-cell">聯絡電話</td>
      <td>{{ $app->contact_phone ?? $app->applicant->phone ?? ' ' }}</td>
      <td class="small-cell">活動對象人數</td>
      <td>{{ $app->expected_participants ?? 0 }}</td>
    </tr>
    <tr>
      <td class="small-cell">工作人員人數</td>
      <td>{{ $app->staff_count ?? 0 }}</td>
      <td class="small-cell">活動起迄時間</td>
      <td colspan="5">{{ $app->event_date }} {{ $app->start_time }} ~ {{ $app->end_time }}</td>
    </tr>
    <tr>
      <td class="small-cell">場地名稱</td>
      <td colspan="3">{{ $app->venue_description ?: ' ' }}</td>
      <td class="small-cell">申請預算</td>
      <td colspan="3">{{ number_format($app->budget_requested ?? 0) }} 元</td>
    </tr>
    <tr>
      <td class="small-cell">活動目的</td>
      <td colspan="7">{{ $app->purpose ?: ' ' }}</td>
    </tr>
    <tr>
      <td class="section-title" colspan="8">備註</td>
    </tr>
    <tr>
      <td colspan="8" class="note">本表為系統自動產生之活動申請基本資料。請依實際申請程序補充完整簽章、場地核準與相關附件。</td>
    </tr>
  </table>
</body>
</html>