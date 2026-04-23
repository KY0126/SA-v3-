@extends('layouts.shell')
@section('title', '常見問題 FAQ')
@php $activePage = 'faq'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-question-circle mr-2 text-fju-yellow"></i>常見問題 FAQ</h2>
    <div class="flex gap-2">
      <select id="faq-sort" onchange="renderFAQ()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="cat">依分類</option><option value="pop">熱門優先</option>
      </select>
      <input id="faq-search" oninput="renderFAQ()" placeholder="搜尋問題..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-48">
    </div>
  </div>

  {{-- Category Filters --}}
  <div class="flex gap-2 flex-wrap" id="faq-cats">
    <button onclick="filterFAQCat('all')" class="faq-cat px-3 py-1 rounded-fju bg-fju-blue text-white text-xs" data-cat="all">全部</button>
    <button onclick="filterFAQCat('evaluation')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="evaluation">社團評鑑</button>
    <button onclick="filterFAQCat('admin')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="admin">行政手續</button>
    <button onclick="filterFAQCat('venue')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="venue">場地借用</button>
    <button onclick="filterFAQCat('booth')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="booth">攤位申請</button>
    <button onclick="filterFAQCat('subsidy')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="subsidy">補助核銷</button>
    <button onclick="filterFAQCat('safety')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="safety">安全規範</button>
    <button onclick="filterFAQCat('equipment')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="equipment">器材借用</button>
    <button onclick="filterFAQCat('cadre')" class="faq-cat px-3 py-1 rounded-fju bg-gray-100 text-gray-500 text-xs" data-cat="cadre">幹部交接</button>
  </div>

  {{-- FAQ List --}}
  <div id="faq-list" class="space-y-3"></div>
</div>

<script>
let faqCat='all';
const faqData=[
  // === 社團評鑑 ===
  {cat:'evaluation',q:'社團評鑑的評分項目與比重是什麼？',a:'評分分為三大項：<b>行政程序與平時表現 (25%)</b> — 由輔導助教評分，含活動申請核銷、會議出席率、社辦管理；<b>共同性評分 (30%)</b> — 含組織運作 (15%) 與資源管理 (15%)，以雲端電子資料呈現；<b>社團活動績效 (45%)</b> — 含規劃與執行 (25%，電子資料) 及績效與特色 (20%，簡報複評)。',pop:95},
  {cat:'evaluation',q:'評鑑不及格會怎樣？',a:'若連續兩年不及格，將<b>取消社辦使用權</b>，並<b>限制場地與器材借用</b>。連續3年成績低於60分者，得由課指組簽請解散。',pop:92},
  {cat:'evaluation',q:'評鑑流程為何？',a:'報名評鑑 → 助教初評 → 公佈初評名單 → 繳交電子複評檔案與簡報 → 簡報複評 (5月上旬) → 成績公告 → 頒獎與交接。通過初評後須於期限內將電子資料網址上傳。',pop:88},
  {cat:'evaluation',q:'簡報複評的時間限制？',a:'日間部系學會為 <b>4.5~5.5 分鐘</b>；社團、進修部及院代會為 <b>6~7 分鐘</b>。所有單位（含未入選複評者）均須派員觀摩並繳回「學習單」，否則撤銷獲獎資格。',pop:85},
  {cat:'evaluation',q:'評鑑獎金有多少？核銷有什麼限制？',a:'特優 <b>5,000 元</b>、優等 <b>3,000 元</b>、績優 <b>1,000 元</b>。獎勵金僅能支應影印費、餐費、文具三類。餐費核銷需附「用餐明細」，名稱必須註明為「社團評鑑會議」。',pop:90},
  {cat:'evaluation',q:'如何讓評審加分？（特優秘籍）',a:'善用 PDF 超連結方便閱覽、加入浮水印、結合 SDGs 永續目標、分析回饋表單而非僅放截圖。會議記錄需具備「四寶」（通知單、簽到表、議程、紀錄），財務需專人專戶管理。',pop:87},

  // === 行政手續 ===
  {cat:'admin',q:'活動申請的送件時限？',a:'一般校內活動需於 <b>7 天前</b> 送件；涉及酒精、明火、攤位販售或校外活動則須於 <b>1 個月前</b> 檢附企畫書送件。全校性大活動需30個工作天前申請。',pop:93},
  {cat:'admin',q:'新社團如何成立？',a:'每年<b>3月</b>向課外組提出申請，核准後需召開成立大會並送交紀錄與章程備查。社團分為學術、體能等六大類。',pop:75},
  {cat:'admin',q:'年度登記何時辦理？',a:'每學年 <b>6 月 1 日至 6 月 15 日</b> 須辦理登記，逾時未登記視同解散。負責人須在校註冊上課達半年以上。',pop:80},
  {cat:'admin',q:'負責人證書如何補發？',a:'填寫「自治組織或社團負責人證書補發換發申請表」，透過原始證書影本、原始名冊查證或輔導助教證明三擇一方式核對。若原證書遺失需親自切結簽名。',pop:60},

  // === 場地借用 ===
  {cat:'venue',q:'焯炤館（學生活動中心）的開放時間？',a:'上課期間：週一至週五 <b>08:00-22:00</b>。寒暑假依學校作息開放。例假日原則不開放，若需活動須於<b>7個工作日前</b>申請。',pop:88},
  {cat:'venue',q:'場地預約後不使用會怎樣？',a:'未依規定取消者將列入<b>違規記錄</b>，直接影響次學期場地電腦預約的權利。',pop:82},
  {cat:'venue',q:'例假日場地借用費用？',a:'校內社團場地費 <b>200元/次</b>，並需負擔最低工資工讀金。超過預定時間5分鐘即以半小時採計超時費。',pop:78},
  {cat:'venue',q:'「潛水艇的天空」場地收費？',a:'場地租借費 <b>250元/小時</b>，保證金一般1,000元（含飲食3,000元）。設備費：音訊250元/次、視訊200元/次、燈光200元/次。',pop:70},

  // === 攤位申請 ===
  {cat:'booth',q:'一般攤位與特別攤位的差異和費用？',a:'一般攤位（非銷售）：每攤每日 <b>250元</b>，申請不限次數。特別攤位（銷售）：每攤每日 <b>750元</b>，每學期限申請1次。食品攤位另加收300元/日人力處理費。',pop:85},
  {cat:'booth',q:'食品攤位有什麼限制？',a:'嚴禁使用<b>明火、油炸設備及瓦斯鋼瓶</b>；配膳人員應戴口罩；生熟食刀具砧板須分開；廢水廢油不得排入排水孔。需通過14項自主檢核。',pop:80},
  {cat:'booth',q:'攤位設置在哪裡？',a:'中央走道編號1-20號攤位；聖美善真廣場限設5攤。需注意保留盲人專用道。上課時段禁止使用擴音設備。',pop:72},

  // === 補助核銷 ===
  {cat:'subsidy',q:'營隊餐費上限是多少？',a:'多天營隊第一天餐費上限 <b>280元</b>，第二天起上限 <b>340元</b>（含早餐60元），且需附上包含用餐時間與餐別的明細表。',pop:86},
  {cat:'subsidy',q:'結案需要哪些文件？',a:'需區分<b>校內成果報告書</b>（電子檔+紙本兩份，含經費明細與支出憑證）和<b>基金會成果報告書</b>（依各基金會格式，需活動照片10張、心得、滿意度分析）。',pop:83},
  {cat:'subsidy',q:'遊覽車費用核銷規定？',a:'超過一萬元需附<b>1家估價單</b>，超過三萬元需<b>3家估價單</b>。搭乘火車需檢附鐵路票價查詢表及車票正、影本。',pop:68},
  {cat:'subsidy',q:'住宿費補助上限？',a:'每人每晚住宿補助上限為 <b>新臺幣 650 元</b>，需列出住宿地點、出席人員名單、發票金額與實際核銷金額。',pop:65},

  // === 安全規範 ===
  {cat:'safety',q:'明火表演（火舞）的安全規定？',a:'維安人員至少<b>3名</b>；表演區與觀眾保持<b>5公尺</b>安全距離，5公尺高淨空區域；嚴禁人造纖維，必須穿<b>100%純棉</b>衣褲；僅限使用煤油。事故需24小時內公開道歉。',pop:75},
  {cat:'safety',q:'辦理酒精飲品活動的規定？',a:'須於活動<b>前1個月</b>申請，酒精濃度建議不超過5%。嚴禁供應未滿18歲者，需簽署《理性飲酒同意書》。活動結束翌日須將同意書送至課指組備查。違規者一年內不得再辦理。',pop:73},

  // === 器材借用 ===
  {cat:'equipment',q:'器材借用的完整流程？',a:'<b>五步驟：</b>1. 考取器材認證卡（考試合格）→ 2. 填寫申請表（助教核可）→ 3. 預約（領取前30日至4個工作天）→ 4. 領取（持器材證與黃單）→ 5. 歸還（確認無損取回證件）。',pop:90},
  {cat:'equipment',q:'違規記點規則？',a:'以單位為對象，累計制不歸零。1點：逾期領取/歸還、沒領沒取消、電力耗盡。2點：臨時預約。3點：損壞/遺失。累計3點停止借用權，消點需預約勞動服務（1點=4小時）。',pop:85},
  {cat:'equipment',q:'推車如何借用？',a:'僅限<b>現場借用</b>（不提供預約），需備有效證件及保證金100元。限借1小時內歸還，最晚須於16:30前歸還。',pop:55},

  // === 幹部交接 ===
  {cat:'cadre',q:'改選交接需要哪些文件？',a:'四大附件：附件1 <b>改選紀錄表</b>（選舉方式、得票數）、附件2 <b>移交清冊總表</b>（宗旨、章程、財務）、附件3 <b>財產清冊</b>（器材逐項清點）、附件4 <b>帳戶印鑑變更</b>（郵局帳戶變更）。',pop:82},
  {cat:'cadre',q:'財務管理的「三人分管」原則？',a:'存摺、團體印鑑、負責人小章須由<b>3名不同專人</b>分別保管，不得集中於同一人。須定時揭露簽章完整的月財務報表進行公開徵信。',pop:78},
];

function filterFAQCat(cat){
  faqCat=cat;
  document.querySelectorAll('.faq-cat').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector(`.faq-cat[data-cat="${cat}"]`)?.classList.add('bg-fju-blue','text-white');
  document.querySelector(`.faq-cat[data-cat="${cat}"]`)?.classList.remove('bg-gray-100','text-gray-500');
  renderFAQ();
}

function renderFAQ(){
  let data=faqCat==='all'?[...faqData]:faqData.filter(f=>f.cat===faqCat);
  const search=(document.getElementById('faq-search')?.value||'').toLowerCase();
  if(search) data=data.filter(f=>f.q.toLowerCase().includes(search)||f.a.toLowerCase().includes(search));
  const sort=document.getElementById('faq-sort')?.value||'cat';
  if(sort==='pop') data.sort((a,b)=>b.pop-a.pop);
  const catLabels={evaluation:'社團評鑑',admin:'行政手續',venue:'場地借用',booth:'攤位申請',subsidy:'補助核銷',safety:'安全規範',equipment:'器材借用',cadre:'幹部交接'};
  const catColors={evaluation:'fju-blue',admin:'fju-yellow',venue:'fju-green',booth:'purple-600',subsidy:'fju-blue',safety:'fju-red',equipment:'fju-yellow',cadre:'fju-green'};

  document.getElementById('faq-list').innerHTML=data.length===0?'<div class="p-8 text-center text-gray-400">無符合的問題</div>':data.map((f,i)=>`
    <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
      <button onclick="toggleFAQ(${i})" class="w-full px-5 py-4 flex items-center justify-between text-left hover:bg-gray-50 transition-all">
        <div class="flex items-center gap-3 flex-1">
          <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-${catColors[f.cat]||'fju-blue'}/10 text-${catColors[f.cat]||'fju-blue'}">${catLabels[f.cat]||f.cat}</span>
          <span class="font-medium text-fju-blue text-sm">${f.q}</span>
        </div>
        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" id="faq-icon-${i}"></i>
      </button>
      <div id="faq-body-${i}" class="hidden px-5 pb-4">
        <div class="p-4 rounded-fju bg-fju-bg text-sm text-gray-600 leading-relaxed">${f.a}</div>
      </div>
    </div>
  `).join('');
}

function toggleFAQ(i){
  const body=document.getElementById('faq-body-'+i);
  const icon=document.getElementById('faq-icon-'+i);
  body.classList.toggle('hidden');
  icon.classList.toggle('rotate-180');
}

renderFAQ();
</script>
@endsection
