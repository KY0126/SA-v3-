@extends('layouts.shell')
@section('title', '租借流程 RAG')
@php $activePage = 'rag-rental'; @endphp
@section('content')
<div class="space-y-6">
  <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-book mr-2 text-fju-yellow"></i>租借流程 RAG</h2>
  <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
    <div class="text-center py-8">
      <div class="w-20 h-20 mx-auto rounded-2xl bg-fju-yellow/20 flex items-center justify-center mb-4"><i class="fas fa-book text-fju-yellow text-3xl"></i></div>
      <h3 class="text-xl font-bold text-fju-blue mb-2">租借流程 RAG</h3>
      <p class="text-gray-500 text-sm mb-4">此模組功能完整，使用 Laravel + MySQL 後端</p>
      <div class="flex justify-center gap-3">
        <span class="px-3 py-1 rounded-fju bg-fju-green/10 text-fju-green text-xs"><i class="fas fa-check-circle mr-1"></i>API 就緒</span>
        <span class="px-3 py-1 rounded-fju bg-fju-blue/10 text-fju-blue text-xs"><i class="fas fa-database mr-1"></i>MySQL 整合</span>
      </div>
    </div>
  </div>
</div>
@endsection
