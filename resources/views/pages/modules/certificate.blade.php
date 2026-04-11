@extends('layouts.shell')
@section('title', '幹部證書自動化')
@php $activePage = 'certificate'; @endphp
@section('content')
<div class="space-y-6">
  <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-award mr-2 text-fju-yellow"></i>幹部證書自動化</h2>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="text-center py-8">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-fju-yellow/20 flex items-center justify-center mb-4"><i class="fas fa-award text-fju-yellow text-3xl"></i></div>
      <h3 class="text-xl font-bold text-fju-blue mb-2">幹部證書自動化</h3>
      <p class="text-gray-500 text-sm mb-4">此模組功能完整，使用 Laravel + MySQL 後端</p>
      <div class="flex justify-center gap-3">
        <span class="px-3 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>API 就緒</span>
        <span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs"><i class="fas fa-database mr-1"></i>MySQL 整合</span>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-award mr-2 text-fju-yellow"></i>產生幹部證書</h3>
    <div class="space-y-3">
      <input id="ct-name" value="王大明" placeholder="姓名" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <input id="ct-club" value="攝影社" placeholder="社團" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <input id="ct-pos" value="副社長" placeholder="職位" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
      <button onclick="genCert()" class="btn-yellow px-6 py-2 text-sm w-full"><i class="fas fa-certificate mr-1"></i>產生證書</button>
    </div>
    <div id="cert-result" class="hidden mt-4 p-4 rounded-fju bg-fju-bg text-sm"></div>
  </div>
  <script>
  function genCert(){fetch('/api/certificates/generate',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({name:document.getElementById('ct-name').value,club:document.getElementById('ct-club').value,position:document.getElementById('ct-pos').value,term:'114學年度第一學期'})}).then(r=>r.json()).then(res=>{const b=document.getElementById('cert-result');b.classList.remove('hidden');b.innerHTML='<div class="text-center"><div class="text-2xl font-black text-fju-blue mb-2">'+res.certificate_code+'</div><p class="text-xs text-gray-500">數位簽章：'+res.digital_signature+'</p><p class="text-xs text-fju-green mt-1"><i class="fas fa-check-circle mr-1"></i>證書已產生</p></div>'})}
  </script>
</div>
@endsection
