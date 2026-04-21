@extends('layouts.shell')
@section('title', '職能 E-Portfolio')
@php $activePage = 'e-portfolio'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-id-badge mr-2 text-fju-yellow"></i>職能 E-Portfolio</h2>
    <button onclick="document.getElementById('add-entry-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-xs"><i class="fas fa-plus mr-1"></i>新增記錄</button>
  </div>

  {{-- Profile Summary --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="flex items-start gap-6">
      <div class="w-24 h-24 rounded-2xl bg-fju-blue flex items-center justify-center shrink-0"><i class="fas fa-user-graduate text-white text-4xl"></i></div>
      <div class="flex-1">
        <h3 class="text-xl font-bold text-fju-blue">Demo User</h3>
        <p class="text-sm text-gray-500">攝影社 社長 | 114 學年度</p>
        <div class="flex flex-wrap gap-2 mt-3">
          <span class="px-3 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow text-xs font-medium"><i class="fas fa-star mr-1"></i>領導力 A</span>
          <span class="px-3 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-xs font-medium"><i class="fas fa-camera mr-1"></i>攝影專長</span>
          <span class="px-3 py-1 rounded-full bg-fju-green/10 text-fju-green text-xs font-medium"><i class="fas fa-users mr-1"></i>團隊合作</span>
          <span class="px-3 py-1 rounded-full bg-purple-100 text-purple-600 text-xs font-medium"><i class="fas fa-laptop-code mr-1"></i>數位技能</span>
        </div>
      </div>
      <button class="btn-blue px-4 py-2 text-xs shrink-0"><i class="fas fa-file-pdf mr-1"></i>匯出 PDF</button>
    </div>
  </div>

  {{-- Competency Radar --}}
  <div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-chart-line mr-2 text-fju-yellow"></i>職能雷達圖</h3>
      <canvas id="portfolio-radar" height="250"></canvas>
    </div>
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-tasks mr-2 text-fju-yellow"></i>職能 Steppers</h3>
      <div class="space-y-3">
        @foreach([['領導力',80,'bg-fju-blue'],['創意思考',70,'bg-fju-yellow'],['團隊合作',85,'bg-fju-green'],['溝通表達',75,'bg-purple-500'],['數位素養',90,'bg-fju-blue']] as $comp)
        <div>
          <div class="flex items-center justify-between mb-1"><span class="text-xs text-gray-600 font-medium">{{ $comp[0] }}</span><span class="text-xs text-fju-blue font-bold">{{ $comp[1] }}%</span></div>
          <div class="w-full bg-gray-100 rounded-full h-2"><div class="{{ $comp[2] }} rounded-full h-2 transition-all duration-1000" style="width:{{ $comp[1] }}%"></div></div>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Portfolio Entries --}}
  <div class="bg-white rounded-fju-lg shadow-sm border border-gray-100">
    <div class="px-5 py-3 border-b border-gray-100"><h3 class="font-bold text-fju-blue text-sm"><i class="fas fa-folder-open mr-2 text-fju-yellow"></i>歷程記錄</h3></div>
    <div class="divide-y divide-gray-50">
      @foreach([
        ['攝影社春季外拍活動策劃','2026/04/20','活動企劃','fas fa-camera','bg-fju-blue','領導、企劃、攝影'],
        ['SDGs 校園永續行動計畫','2026/03/15','服務學習','fas fa-leaf','bg-fju-green','永續發展、團隊合作'],
        ['全國攝影比賽 金獎','2026/02/28','競賽獲獎','fas fa-trophy','bg-fju-yellow','攝影技術、創意'],
        ['程式設計工作坊 助教','2026/01/10','教學助理','fas fa-laptop-code','bg-purple-500','教學、程式設計'],
        ['社團聯合成果展 總召','2025/12/20','大型活動','fas fa-star','bg-fju-blue','領導力、跨團合作'],
      ] as $entry)
      <div class="p-4 hover:bg-gray-50 transition-colors flex items-center gap-4">
        <div class="w-10 h-10 rounded-fju {{ $entry[4] }} flex items-center justify-center shrink-0"><i class="{{ $entry[3] }} text-white text-sm"></i></div>
        <div class="flex-1">
          <div class="font-bold text-fju-blue text-sm">{{ $entry[0] }}</div>
          <div class="flex items-center gap-2 mt-1"><span class="text-[10px] text-gray-400"><i class="fas fa-calendar mr-1"></i>{{ $entry[1] }}</span><span class="text-[10px] px-2 py-0.5 rounded-full bg-fju-blue/10 text-fju-blue">{{ $entry[2] }}</span></div>
          <div class="flex gap-1 mt-1">@foreach(explode('、',$entry[5]) as $tag)<span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">{{ $tag }}</span>@endforeach</div>
        </div>
        <button class="text-gray-400 hover:text-fju-blue"><i class="fas fa-ellipsis-v"></i></button>
      </div>
      @endforeach
    </div>
  </div>

  {{-- SDG Mapping --}}
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue text-sm mb-4"><i class="fas fa-globe mr-2 text-fju-yellow"></i>SDGs 永續目標對應</h3>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
      @foreach([['SDG4','優質教育',85,'#C5192D'],['SDG5','性別平等',60,'#FF3A21'],['SDG10','減少不平等',75,'#DD1367'],['SDG11','永續城市',90,'#FD9D24'],['SDG16','和平正義',55,'#00689D'],['SDG17','夥伴關係',70,'#19486A']] as $sdg)
      <div class="text-center p-3 rounded-fju border border-gray-100">
        <div class="w-10 h-10 mx-auto rounded-lg flex items-center justify-center mb-2 text-white font-bold text-xs" style="background:{{ $sdg[3] }}">{{ $sdg[0] }}</div>
        <div class="text-[10px] text-gray-500 mb-1">{{ $sdg[1] }}</div>
        <div class="text-sm font-bold text-fju-blue">{{ $sdg[2] }}%</div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- Add Entry Modal --}}
  <div id="add-entry-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg p-6 w-full max-w-md mx-4 shadow-2xl">
      <div class="flex items-center justify-between mb-4"><h3 class="font-bold text-fju-blue"><i class="fas fa-plus mr-2 text-fju-yellow"></i>新增歷程記錄</h3><button onclick="document.getElementById('add-entry-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button></div>
      <div class="space-y-3">
        <input type="text" placeholder="活動/經歷名稱" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <select class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"><option>活動企劃</option><option>服務學習</option><option>競賽獲獎</option><option>教學助理</option><option>實習經驗</option></select>
        <textarea rows="3" placeholder="描述..." class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
        <input type="text" placeholder="技能標籤（以頓號分隔）" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        <button class="w-full btn-yellow py-2.5 text-sm" onclick="alert('已新增！(outbox_table 異步處理中...)');document.getElementById('add-entry-modal').classList.add('hidden')"><i class="fas fa-save mr-1"></i>儲存記錄</button>
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  if(document.getElementById('portfolio-radar')){
    new Chart(document.getElementById('portfolio-radar'),{type:'radar',data:{labels:['領導力','創意思考','團隊合作','溝通表達','數位素養','問題解決'],datasets:[{label:'目前能力',data:[80,70,85,75,90,78],borderColor:'#003153',backgroundColor:'rgba(0,49,83,0.1)',pointBackgroundColor:'#DAA520'},{label:'目標',data:[90,85,90,85,95,88],borderColor:'#DAA520',backgroundColor:'rgba(218,165,32,0.05)',pointBackgroundColor:'#003153',borderDash:[5,5]}]},options:{responsive:true,plugins:{legend:{position:'bottom',labels:{font:{size:10}}}},scales:{r:{beginAtZero:true,max:100,ticks:{stepSize:20,font:{size:9}}}}}});
  }
});
</script>
@endsection
