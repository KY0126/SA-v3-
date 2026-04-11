@extends('layouts.shell')
@section('title', 'AI 導覽助理')
@php $activePage = 'ai-guide'; @endphp
@section('content')
<div class="space-y-6">
  <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-robot mr-2 text-fju-yellow"></i>AI 導覽助理</h2>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="text-center py-8">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-fju-yellow/20 flex items-center justify-center mb-4"><i class="fas fa-robot text-fju-yellow text-3xl"></i></div>
      <h3 class="text-xl font-bold text-fju-blue mb-2">AI 導覽助理</h3>
      <p class="text-gray-500 text-sm mb-4">此模組功能完整，使用 Laravel + MySQL 後端</p>
      <div class="flex justify-center gap-3">
        <span class="px-3 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>API 就緒</span>
        <span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs"><i class="fas fa-database mr-1"></i>MySQL 整合</span>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-wand-magic-sparkles mr-2 text-fju-yellow"></i>AI 企劃生成器</h3>
    <div class="space-y-3">
      <input id="pg-title" placeholder="活動名稱" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <textarea id="pg-desc" placeholder="活動說明" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
      <div class="grid grid-cols-2 gap-3"><input type="number" id="pg-ppl" placeholder="預計人數" value="50" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"><input type="date" id="pg-date" class="px-4 py-2 rounded-fju border border-gray-200 text-sm"></div>
      <button onclick="genProposal()" class="btn-yellow px-6 py-2 text-sm w-full"><i class="fas fa-robot mr-1"></i>生成企劃書</button>
    </div>
    <div id="proposal-result" class="hidden mt-4 p-4 rounded-fju bg-fju-bg text-sm"></div>
  </div>
  <script>
  function genProposal(){fetch('/api/ai/generate-proposal',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({title:document.getElementById('pg-title').value,description:document.getElementById('pg-desc').value,participants:parseInt(document.getElementById('pg-ppl').value),date:document.getElementById('pg-date').value})}).then(r=>r.json()).then(res=>{const b=document.getElementById('proposal-result');b.classList.remove('hidden');b.innerHTML='<h4 class="font-bold text-fju-blue mb-2">'+res.title+'</h4><div class="space-y-1 text-xs text-gray-600">'+Object.values(res.sections).map(s=>Array.isArray(s)?s.join('<br>'):'<p>'+s+'</p>').join('')+'</div><div class="mt-3 p-2 rounded bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>AI 預審：'+res.ai_review.risk_level+' 風險 (信心度 '+res.ai_review.confidence+')</div>'})}
  </script>
</div>
@endsection
