@extends('layouts.shell')
@section('title', '法規查詢 (RAG)')
@php $activePage = 'rag-search'; @endphp
@section('content')
<div class="space-y-6">
  <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-gavel mr-2 text-fju-yellow"></i>法規查詢 (RAG)</h2>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="text-center py-8">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-fju-yellow/20 flex items-center justify-center mb-4"><i class="fas fa-gavel text-fju-yellow text-3xl"></i></div>
      <h3 class="text-xl font-bold text-fju-blue mb-2">法規查詢 (RAG)</h3>
      <p class="text-gray-500 text-sm mb-4">此模組功能完整，使用 Laravel + MySQL 後端</p>
      <div class="flex justify-center gap-3">
        <span class="px-3 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>API 就緒</span>
        <span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs"><i class="fas fa-database mr-1"></i>MySQL 整合</span>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-brain mr-2 text-fju-yellow"></i>AI 預審模擬</h3>
    <div class="space-y-3">
      <input type="number" id="ra-ppl" placeholder="預計參加人數" value="60" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <button onclick="doReview()" class="btn-yellow px-6 py-2 text-sm"><i class="fas fa-search mr-1"></i>進行 RAG 法規預審</button>
    </div>
    <div id="review-result" class="hidden mt-4 p-4 rounded-fju text-sm"></div>
  </div>
  <script>
  function doReview(){fetch('/api/ai/pre-review',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({participants:parseInt(document.getElementById('ra-ppl').value)})}).then(r=>r.json()).then(res=>{const b=document.getElementById('review-result');b.classList.remove('hidden');b.className='mt-4 p-4 rounded-fju text-sm '+(res.allow_next_step?'bg-fju-green/10':'bg-fju-red/10');b.innerHTML='<div class="font-bold mb-2">'+(res.allow_next_step?'✅ 通過':'❌ 未通過')+' (風險：'+res.risk_level+')</div><p>'+res.reasoning+'</p>'+(res.violations.length?'<div class="mt-2"><b>違規項目：</b>'+res.violations.join(', ')+'</div>':'')+(res.suggestions.length?'<div class="mt-2"><b>建議：</b><ul class="list-disc pl-4">'+res.suggestions.map(s=>'<li>'+s+'</li>').join('')+'</ul></div>':'')})}
  </script>
</div>
@endsection
