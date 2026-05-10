@extends('layouts.shell')
@section('title', '表單下載')
@php $activePage = 'form-download'; @endphp
@section('content')
<div class="space-y-6">

  {{-- Header --}}
  <div class="bg-white rounded-fju-lg p-5 shadow-sm border border-gray-100 flex items-start gap-3">
    <div class="w-10 h-10 rounded-fju bg-fju-blue flex items-center justify-center shrink-0">
      <i class="fas fa-download text-fju-yellow"></i>
    </div>
    <div>
      <div class="font-bold text-fju-blue text-base">表單下載</div>
      <div class="text-xs text-gray-500 mt-1">點擊下載按鈕可直接下載對應空白表單，填寫完成後依各表單說明繳交。</div>
    </div>
  </div>

  @php
    $downloadSections = [
      [
        'title' => '活動類',
        'icon' => 'fa-calendar-alt',
        'items' => [
          ['file'=>'活動申請表_黃單.pdf','title'=>'活動申請表（黃單）','desc'=>'輔仁大學學生自治組織與社團活動申請表，一般活動須於 7 天前送件'],
          ['file'=>'活動名冊 (1).xls','title'=>'活動名冊','desc'=>'活動名冊範本，可用於活動出席記錄與人員清單'],
          ['file'=>'車輛進出知會單1140729.docx','title'=>'車輛進出知會單','desc'=>'活動車輛進出校園通知表單'],
        ],
      ],
      [
        'title' => '場地類',
        'icon' => 'fa-map-marker-alt',
        'items' => [
          ['file'=>'總務處場地申請表 (2).doc','title'=>'總務處場地申請表','desc'=>'總務處場地借用申請表範例'],
          ['file'=>'旗幟插立申請表.docx','title'=>'旗幟插立申請表','desc'=>'旗幟插立申請相關表格'],
          ['file'=>'旗幟桿地圖.pdf','title'=>'旗幟桿地圖','desc'=>'旗幟桿位置圖'],
          ['file'=>'旗幟桿地圖 (1).pdf','title'=>'旗幟桿地圖（備用）','desc'=>'旗幟桿位置圖備份檔案'],
          ['file'=>'課指組場地收費一覽表.pdf','title'=>'課指組場地收費一覽表','desc'=>'場地收費標準表，供費用計算與核銷參考'],
        ],
      ],
      [
        'title' => '器材類',
        'icon' => 'fa-boxes-stacked',
        'items' => [
          ['file'=>'課指組_器材借用申請表.pdf','title'=>'課指組器材借用申請表','desc'=>'器材借用申請表，需輔導助教或單位主管核可'],
          ['file'=>'課指組 器材一覽表 115.02.01 (2).pdf','title'=>'課指組器材一覽表','desc'=>'課指組現有器材清單，可作器材借用與盤點參考'],
        ],
      ],
      [
        'title' => '核銷類',
        'icon' => 'fa-receipt',
        'items' => [
          ['file'=>'「交通費」明細表.docx','title'=>'交通費明細表','desc'=>'交通費核銷用明細表'],
          ['file'=>'「住宿費」明細表.docx','title'=>'住宿費明細表','desc'=>'住宿費核銷用明細表'],
          ['file'=>'「懦O」明細表.docx','title'=>'懦O 明細表','desc'=>'費用明細表格範本'],
          ['file'=>'「門票、場地費」明細表.docx','title'=>'門票、場地費明細表','desc'=>'門票與場地費用核銷明細表'],
          ['file'=>'個人領據.doc','title'=>'個人領據','desc'=>'個人領據填寫範本'],
          ['file'=>'單據報銷清單.doc','title'=>'單據報銷清單','desc'=>'報銷單據整理清單'],
          ['file'=>'支出分攤表.docx','title'=>'支出分攤表','desc'=>'費用分攤計算表'],
          ['file'=>'請款單.doc','title'=>'請款單','desc'=>'請款用表單'],
          ['file'=>'非合格收據證明單.docx','title'=>'非合格收據證明單','desc'=>'非合格收據證明書用表格'],
          ['file'=>'黏貼憑證用紙.doc','title'=>'黏貼憑證用紙','desc'=>'核銷憑證貼附格式說明'],
        ],
      ],
      [
        'title' => '臨時攤位申請',
        'icon' => 'fa-store',
        'items' => [
          ['file'=>'附表一、攤位圖冊.pdf','title'=>'附表一、攤位圖冊','desc'=>'攤位圖冊範本'],
          ['file'=>'附表二、一般臨時攤位申請表.docx','title'=>'附表二、一般臨時攤位申請表','desc'=>'一般臨時攤位申請表'],
          ['file'=>'附表二、一般臨時攤位申請表 (1).docx','title'=>'附表二、一般臨時攤位申請表（備份）','desc'=>'一般臨時攤位申請表備份檔案'],
          ['file'=>'附表三、特別臨時攤位申請表.docx','title'=>'附表三、特別臨時攤位申請表','desc'=>'特別臨時攤位申請表'],
          ['file'=>'附表四、臨時食品攤位衛生安全自主管理檢核表.docx','title'=>'附表四、臨時食品攤位衛生安全自主管理檢核表','desc'=>'臨時食品攤位衛生安全檢核表'],
        ],
      ],
      [
        'title' => '用火相關',
        'icon' => 'fa-fire',
        'items' => [
          ['file'=>'輔仁大學學生活動上火安全細則.docx','title'=>'上火安全細則','desc'=>'校內舉辦用火活動的安全細則'],
          ['file'=>'輔仁大學學生活動上火確認表 (光火藝術社範例).docx','title'=>'上火確認表範例','desc'=>'用火活動確認表範例檔案'],
        ],
      ],
      [
        'title' => '酒精飲品活動',
        'icon' => 'fa-wine-glass-alt',
        'items' => [
          ['file'=>'辦理提供酒精飲品活動辦理實施要點.pdf','title'=>'酒精飲品活動辦理實施要點','desc'=>'酒精飲品活動辦理要點說明'],
          ['file'=>'酒精飲品活動辦理須知.docx','title'=>'酒精飲品活動辦理須知','desc'=>'酒精飲品活動注意事項與申請須知'],
        ],
      ],
      [
        'title' => '成果相關表格',
        'icon' => 'fa-trophy',
        'items' => [
          ['file'=>'學生活動成果報告書.doc','title'=>'學生活動成果報告書','desc'=>'活動成果報告書範例，用於活動成果回報'],
        ],
      ],
      [
        'title' => '申請補發與特殊文件',
        'icon' => 'fa-id-card-alt',
        'items' => [
          ['file'=>'校內申請免徵娛樂稅證明文件.docx','title'=>'校內申請免徵娛樂稅證明文件','desc'=>'娛樂稅免徵申請證明文件'],
          ['file'=>'自治組織或社團負責人證書補發換發申請表.docx','title'=>'負責人證書補發申請表','desc'=>'負責人證書補發/換發申請表'],
        ],
      ],
      [
        'title' => '表單範例',
        'icon' => 'fa-file-alt',
        'items' => [
          ['file'=>'企畫書-服務隊.doc','title'=>'企畫書－服務隊','desc'=>'活動企畫書範例：服務隊'],
          ['file'=>'企畫書-週系列.doc','title'=>'企畫書－週系列','desc'=>'活動企畫書範例：週系列'],
          ['file'=>'企畫書-體育競賽.doc','title'=>'企畫書－體育競賽','desc'=>'活動企畫書範例：體育競賽'],
          ['file'=>'函稿-參賽公文.doc','title'=>'函稿－參賽公文','desc'=>'活動參賽公文範例'],
          ['file'=>'函稿-大專體總.doc','title'=>'函稿－大專體總','desc'=>'與大專體總往來函稿範例'],
          ['file'=>'函稿-服務隊行政協助.doc','title'=>'函稿－服務隊行政協助','desc'=>'服務隊行政協助函稿範例'],
          ['file'=>'函稿-民間團體經費.doc','title'=>'函稿－民間團體經費','desc'=>'民間團體經費申請函稿範例'],
          ['file'=>'函稿-門票優待.doc','title'=>'函稿－門票優待','desc'=>'門票優待申請函稿範例'],
          ['file'=>'報告書-經費取消(新).docx','title'=>'報告書－經費取消','desc'=>'經費取消報告書範例'],
          ['file'=>'報告書-經費轉移(新).docx','title'=>'報告書－經費轉移','desc'=>'經費轉移報告書範例'],
          ['file'=>'會議紀錄格式.doc','title'=>'會議紀錄格式','desc'=>'會議紀錄填寫格式範例'],
          ['file'=>'會議議程格式.doc','title'=>'會議議程格式','desc'=>'會議議程議程範例'],
          ['file'=>'開會通知單.doc','title'=>'開會通知單','desc'=>'開會通知單範例表格'],
          ['file'=>'紙本交接檔案docx格式.docx','title'=>'紙本交接檔案（DOCX）','desc'=>'紙本交接資料下載 DOCX 格式'],
          ['file'=>'紙本交接檔案odt格式.odt','title'=>'紙本交接檔案（ODT）','desc'=>'紙本交接資料下載 ODT 格式'],
        ],
      ],
      [
        'title' => '電子交接資料',
        'icon' => 'fa-file-code',
        'items' => [
          ['file'=>'Z423EW.htm','title'=>'電子交接資料上傳檔案格式連結','desc'=>'前往上傳檔案格式下載頁面'],
          ['file'=>'viewform.htm','title'=>'電子交接資料填寫網址連結','desc'=>'前往線上交接資料填寫頁面'],
        ],
      ],
    ];

    function getFileIcon($file) {
      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      switch ($ext) {
        case 'pdf': return 'fas fa-file-pdf text-red-500';
        case 'doc':
        case 'docx': return 'fas fa-file-word text-blue-500';
        case 'xls':
        case 'xlsx': return 'fas fa-file-excel text-green-500';
        case 'htm':
        case 'html': return 'fas fa-link text-yellow-500';
        default: return 'fas fa-file text-gray-500';
      }
    }
  @endphp

  @foreach($downloadSections as $section)
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100 bg-fju-blue/5 flex items-center gap-2">
      <i class="fas {{ $section['icon'] }} text-fju-yellow"></i>
      <h3 class="font-bold text-fju-blue text-sm">{{ $section['title'] }}</h3>
    </div>
    <div class="divide-y divide-gray-50">
      @foreach($section['items'] as $item)
      @php
        $ext = strtolower(pathinfo($item['file'], PATHINFO_EXTENSION));
        $isExternalLink = in_array($ext, ['htm','html']);
        $href = '/downloads/' . rawurlencode($item['file']);
        $iconClass = getFileIcon($item['file']);
      @endphp
      <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50 transition-colors">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-fju bg-red-50 flex items-center justify-center shrink-0">
            <i class="{{ $iconClass }} text-sm"></i>
          </div>
          <div>
            <div class="font-medium text-fju-blue text-sm">{{ $item['title'] }}</div>
            <div class="text-xs text-gray-400 mt-0.5">{{ $item['desc'] }}</div>
          </div>
        </div>
        <a href="{{ $href }}" @if($isExternalLink) target="_blank" rel="noopener" @else download="{{ $item['file'] }}" @endif
          class="shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-fju bg-fju-blue text-white text-xs font-medium hover:bg-fju-blue/90 transition-colors">
          <i class="fas fa-download"></i>{{ $isExternalLink ? ' 前往' : ' 下載' }}
        </a>
      </div>
      @endforeach
    </div>
  </div>
  @endforeach

  {{-- Note --}}
  <div class="p-4 rounded-fju-lg bg-fju-yellow/10 border border-fju-yellow/30 text-xs text-gray-600 flex items-start gap-2">
    <i class="fas fa-info-circle text-fju-yellow mt-0.5 shrink-0"></i>
    <div>
      如遇檔案無法下載，請聯絡課指組：<span class="font-medium text-fju-blue">02-2905-3085</span> 或至課指組辦公室索取紙本。
    </div>
  </div>

</div>
@endsection
