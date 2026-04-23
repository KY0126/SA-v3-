@extends('layouts.shell')
@section('title', '數位證書')
@php $activePage = 'certificate'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-award mr-2 text-fju-yellow"></i>數位證書（獎狀格式）</h2>
    <select id="cert-sort" onchange="renderCertList()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
      <option value="newest">最新優先</option><option value="oldest">最舊優先</option><option value="name">依姓名</option>
    </select>
  </div>

  {{-- Generate Certificate --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-certificate mr-2 text-fju-yellow"></i>產生幹部服務證書</h3>
    <div class="grid md:grid-cols-2 gap-4">
      <div class="space-y-3">
        <input id="ct-name" value="王大明" placeholder="姓名" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <input id="ct-club" value="攝影社" placeholder="社團名稱" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <select id="ct-pos" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <option value="社長">社長</option><option value="副社長" selected>副社長</option><option value="財務">財務</option><option value="公關">公關</option><option value="活動">活動長</option><option value="幹部">幹部</option>
        </select>
        <input id="ct-term" value="114學年度第一學期" placeholder="任期" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <select id="ct-award" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <option value="service">幹部服務證書</option><option value="excellent">社團評鑑特優獎狀</option><option value="good">社團評鑑優等獎狀</option><option value="merit">社團評鑑績優獎狀</option>
        </select>
        <button onclick="genCert()" class="btn-yellow px-6 py-2 text-sm w-full"><i class="fas fa-certificate mr-1"></i>產生證書</button>
      </div>
      {{-- Preview --}}
      <div id="cert-preview" class="bg-fju-bg rounded-fju p-2 flex items-center justify-center min-h-[300px]">
        <p class="text-gray-400 text-sm">填寫左側資料後點擊「產生證書」即可預覽</p>
      </div>
    </div>
  </div>

  {{-- Certificate List --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-list mr-2 text-fju-yellow"></i>已產生的證書</h3></div>
    <div id="cert-list"></div>
  </div>
</div>

{{-- Print-only certificate --}}
<div id="cert-print-area" class="hidden print:block"></div>

<style>
@media print {
  body > *:not(#cert-print-area) { display:none!important; }
  #cert-print-area { display:block!important; }
}
.cert-frame {
  position:relative; background:#FFFEF5; border:3px double #B8860B;
  box-shadow:inset 0 0 0 8px #FFFEF5, inset 0 0 0 10px #B8860B;
  padding:40px 50px; text-align:center; font-family:'Noto Serif TC','serif';
}
.cert-frame::before { content:''; position:absolute; top:12px; left:12px; right:12px; bottom:12px; border:1px solid #DAA520; pointer-events:none; }
.cert-watermark { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); opacity:0.06; font-size:120px; color:#003153; pointer-events:none; }
</style>

<script>
let certList=[];

function genCert(){
  const name=document.getElementById('ct-name').value;
  const club=document.getElementById('ct-club').value;
  const pos=document.getElementById('ct-pos').value;
  const term=document.getElementById('ct-term').value;
  const awardType=document.getElementById('ct-award').value;

  fetch('/api/certificates/generate',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({name,club,position:pos,term})}).then(r=>r.json()).then(res=>{
    const cert={...res,name,club,position:pos,term,award_type:awardType};
    certList.unshift(cert);
    renderCertPreview(cert);
    renderCertList();
  });
}

function renderCertPreview(c){
  const awardLabels={service:'幹部服務證書',excellent:'社團評鑑特優獎狀',good:'社團評鑑優等獎狀',merit:'社團評鑑績優獎狀'};
  const awardLabel=awardLabels[c.award_type]||'幹部服務證書';
  const isEval=c.award_type!=='service';
  const prizeAmounts={excellent:'5,000',good:'3,000',merit:'1,000'};

  const html=`<div class="cert-frame" id="cert-canvas" style="width:100%;max-width:600px;">
    <div class="cert-watermark"><i class="fas fa-university"></i></div>
    <div style="margin-bottom:8px;"><img src="data:image/svg+xml,${encodeURIComponent('<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot;><circle cx=&quot;30&quot; cy=&quot;30&quot; r=&quot;28&quot; fill=&quot;%23003153&quot; stroke=&quot;%23DAA520&quot; stroke-width=&quot;2&quot;/><text x=&quot;30&quot; y=&quot;24&quot; text-anchor=&quot;middle&quot; fill=&quot;%23DAA520&quot; font-size=&quot;10&quot; font-weight=&quot;bold&quot;>FJU</text><text x=&quot;30&quot; y=&quot;38&quot; text-anchor=&quot;middle&quot; fill=&quot;%23fff&quot; font-size=&quot;8&quot;>輔仁大學</text></svg>')}" style="width:60px;height:60px;display:block;margin:0 auto;" alt="FJU"></div>
    <div style="font-size:12px;color:#003153;letter-spacing:3px;margin-bottom:4px;">天主教輔仁大學</div>
    <div style="font-size:10px;color:#666;margin-bottom:12px;">Fu Jen Catholic University</div>
    <div style="font-size:22px;font-weight:bold;color:#B8860B;letter-spacing:8px;margin-bottom:16px;">${isEval?'獎　狀':'證　書'}</div>
    <div style="font-size:14px;color:#333;line-height:2;text-align:left;padding:0 20px;">
      <span style="display:inline-block;text-indent:2em;">茲證明　<b style="font-size:18px;color:#003153;border-bottom:2px solid #003153;padding:0 8px;">${c.name}</b>　同學</span><br>
      <span style="display:inline-block;text-indent:2em;">擔任本校　<b style="color:#003153;">${c.club}</b>　${c.position}</span><br>
      <span style="display:inline-block;text-indent:2em;">任期：${c.term}</span><br>
      ${isEval?`<span style="display:inline-block;text-indent:2em;">榮獲 <b style="color:#B8860B;">${awardLabel}</b>，獎金新臺幣 ${prizeAmounts[c.award_type]||'0'} 元整</span><br>`:''}
      <span style="display:inline-block;text-indent:2em;">${isEval?'服務盡心，表現優異，殊堪嘉勉':'服務期間表現良好，特此證明'}</span>
    </div>
    <div style="margin-top:24px;display:flex;justify-content:space-between;align-items:flex-end;padding:0 20px;">
      <div style="text-align:center;"><div style="width:60px;height:60px;border:2px solid #C00;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#C00;font-size:10px;font-weight:bold;margin:0 auto;">課指組<br>印</div><div style="font-size:10px;color:#666;margin-top:4px;">課外活動指導組</div></div>
      <div style="text-align:center;"><div style="font-size:10px;color:#666;">中華民國 ${new Date().getFullYear()-1911} 年 ${new Date().getMonth()+1} 月 ${new Date().getDate()} 日</div></div>
      <div style="text-align:center;"><div style="width:60px;height:60px;border:2px solid #003153;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#003153;font-size:10px;font-weight:bold;margin:0 auto;">學務處<br>印</div><div style="font-size:10px;color:#666;margin-top:4px;">學生事務處</div></div>
    </div>
    <div style="margin-top:16px;padding-top:8px;border-top:1px dashed #DAA520;">
      <div style="font-size:9px;color:#999;">證書編號：${c.certificate_code} | 數位簽章：${c.digital_signature}</div>
      <div style="font-size:9px;color:#999;">驗證網址：${c.verification_url||'https://fju-smart-hub.pages.dev/verify/'+c.certificate_code}</div>
    </div>
  </div>`;
  document.getElementById('cert-preview').innerHTML=html;
}

function renderCertList(){
  let data=[...certList];
  const sort=document.getElementById('cert-sort')?.value||'newest';
  if(sort==='oldest') data.reverse();
  else if(sort==='name') data.sort((a,b)=>(a.name||'').localeCompare(b.name||''));
  const awardLabels={service:'幹部服務證書',excellent:'特優獎狀',good:'優等獎狀',merit:'績優獎狀'};
  document.getElementById('cert-list').innerHTML=data.length===0?'<div class="p-8 text-center text-gray-400">尚無證書</div>':data.map(c=>`
    <div class="px-5 py-3 border-b border-gray-50 flex items-center justify-between hover:bg-gray-50">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-fju bg-fju-yellow/10 flex items-center justify-center"><i class="fas fa-award text-fju-yellow"></i></div>
        <div>
          <div class="text-sm font-medium text-fju-blue">${c.name||'-'} — ${c.club||'-'} ${c.position||''}</div>
          <div class="text-[10px] text-gray-400">${c.certificate_code} · ${awardLabels[c.award_type]||'證書'} · ${c.term||''}</div>
        </div>
      </div>
      <div class="flex gap-1">
        <button onclick='renderCertPreview(${JSON.stringify(c).replace(/'/g,"\\'")})'  class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs hover:bg-fju-blue/20"><i class="fas fa-eye mr-1"></i>預覽</button>
        <button onclick="printCert()" class="px-3 py-1 rounded-fju bg-fju-yellow/10 text-fju-blue text-xs hover:bg-fju-yellow/20"><i class="fas fa-print mr-1"></i>列印</button>
        <button onclick="downloadCert()" class="px-3 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs hover:bg-fju-green/20"><i class="fas fa-download mr-1"></i>PDF</button>
      </div>
    </div>
  `).join('');
}

function printCert(){
  const canvas=document.getElementById('cert-canvas');
  if(!canvas){alert('請先產生證書');return;}
  const area=document.getElementById('cert-print-area');
  area.innerHTML=canvas.outerHTML;
  area.classList.remove('hidden');
  window.print();
  setTimeout(()=>area.classList.add('hidden'),1000);
}

function downloadCert(){
  alert('PDF 下載功能已啟用！在實際部署環境中，系統會使用 html2pdf.js 將證書轉為 PDF 檔案下載。目前請使用列印功能並選擇「另存為 PDF」。');
  printCert();
}

renderCertList();
</script>
@endsection
