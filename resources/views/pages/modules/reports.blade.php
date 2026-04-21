@extends('layouts.shell')
@section('title', '系統架構與流程圖')
@php $activePage = 'reports'; @endphp
@section('content')
<div class="space-y-6">
  <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-chart-bar mr-2 text-fju-yellow"></i>FJU Smart Hub — 統計報表 & 系統架構</h2>

  {{-- Tabs --}}
  <div class="flex gap-2 flex-wrap">
    <button onclick="showTab('flowchart')" class="tab-btn px-4 py-2 rounded-fju bg-fju-blue text-white text-sm font-bold" data-tab="flowchart"><i class="fas fa-project-diagram mr-1"></i>三階段流程圖</button>
    <button onclick="showTab('architecture')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="architecture"><i class="fas fa-sitemap mr-1"></i>系統全域架構</button>
    <button onclick="showTab('swimlane')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="swimlane"><i class="fas fa-stream mr-1"></i>多角色泳道圖</button>
    <button onclick="showTab('dify')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="dify"><i class="fas fa-robot mr-1"></i>Dify AI RAG 流程</button>
    <button onclick="showTab('security')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="security"><i class="fas fa-shield-alt mr-1"></i>安全驗證生命週期</button>
    <button onclick="showTab('permissions')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="permissions"><i class="fas fa-user-shield mr-1"></i>權限矩陣</button>
    <button onclick="showTab('features')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="features"><i class="fas fa-list-check mr-1"></i>功能清單</button>
    <button onclick="showTab('rubric')" class="tab-btn px-4 py-2 rounded-fju bg-gray-100 text-gray-500 text-sm" data-tab="rubric"><i class="fas fa-award mr-1"></i>評分對照</button>
  </div>

  {{-- ============ Tab 1: Three-Stage Resource Scheduling ============ --}}
  <div id="tab-flowchart" class="tab-content">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-project-diagram mr-2 text-fju-yellow"></i>三階段資源調度狀態圖</h3>
      <p class="text-xs text-gray-500 mb-4">志願序演算法 (Level 1-3) → 衝突時 LINE 邀請及私下調解入口 → 最終 PDF 申請單與 TOTP QR Code 自動化路徑</p>
      <div class="min-w-[900px]">
        <svg viewBox="0 0 1200 750" xmlns="http://www.w3.org/2000/svg" class="w-full">
          <defs>
            <marker id="arrow" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#003153"/></marker>
            <marker id="arrow-y" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#DAA520"/></marker>
            <marker id="arrow-g" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#008000"/></marker>
            <marker id="arrow-r" viewBox="0 0 10 10" refX="10" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-auto"><path d="M 0 0 L 10 5 L 0 10 z" fill="#FF0000"/></marker>
            <linearGradient id="bgGrad1" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#003153;stop-opacity:0.08"/><stop offset="100%" style="stop-color:#003153;stop-opacity:0.02"/></linearGradient>
            <linearGradient id="bgGrad2" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#DAA520;stop-opacity:0.08"/><stop offset="100%" style="stop-color:#DAA520;stop-opacity:0.02"/></linearGradient>
            <linearGradient id="bgGrad3" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#008000;stop-opacity:0.08"/><stop offset="100%" style="stop-color:#008000;stop-opacity:0.02"/></linearGradient>
          </defs>
          {{-- Background regions --}}
          <rect x="20" y="50" width="330" height="680" rx="15" fill="url(#bgGrad1)" stroke="#003153" stroke-width="1" stroke-dasharray="4"/>
          <rect x="370" y="50" width="400" height="680" rx="15" fill="url(#bgGrad2)" stroke="#DAA520" stroke-width="1" stroke-dasharray="4"/>
          <rect x="790" y="50" width="380" height="680" rx="15" fill="url(#bgGrad3)" stroke="#008000" stroke-width="1" stroke-dasharray="4"/>

          {{-- Stage labels --}}
          <rect x="50" y="15" width="270" height="32" rx="16" fill="#003153"/><text x="185" y="36" text-anchor="middle" fill="white" font-size="13" font-weight="bold">第一階段：志願序配對 (Algorithm)</text>
          <rect x="420" y="15" width="300" height="32" rx="16" fill="#DAA520"/><text x="570" y="36" text-anchor="middle" fill="#003153" font-size="13" font-weight="bold">第二階段：自主協商 (Negotiation)</text>
          <rect x="830" y="15" width="300" height="32" rx="16" fill="#008000"/><text x="980" y="36" text-anchor="middle" fill="white" font-size="13" font-weight="bold">第三階段：官方核定 (Approval)</text>

          {{-- Stage 1 --}}
          <rect x="85" y="80" rx="12" width="200" height="45" fill="#003153" opacity="0.12" stroke="#003153" stroke-width="2"/><text x="185" y="107" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">使用者提交預約申請</text>
          <line x1="185" y1="125" x2="185" y2="155" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          <rect x="70" y="160" rx="12" width="230" height="55" fill="white" stroke="#003153" stroke-width="2"/><text x="185" y="182" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">AI 意圖過濾</text><text x="185" y="198" text-anchor="middle" fill="#666" font-size="9">Workers AI (Llama-3) 偵測惡意理由</text>
          <line x1="185" y1="215" x2="185" y2="245" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          <rect x="70" y="250" rx="12" width="230" height="55" fill="white" stroke="#003153" stroke-width="2"/><text x="185" y="272" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">志願序演算法</text><text x="185" y="288" text-anchor="middle" fill="#666" font-size="9">L1 校方 &gt; L2 幹部 &gt; L3 一般社團</text>
          <line x1="185" y1="305" x2="185" y2="340" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Decision diamond --}}
          <polygon points="185,345 270,385 185,425 100,385" fill="#FFE8B0" stroke="#DAA520" stroke-width="2"/>
          <text x="185" y="388" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">衝突?</text>

          {{-- No conflict → direct pass --}}
          <line x1="100" y1="385" x2="40" y2="385" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>
          <text x="70" y="378" text-anchor="middle" fill="#008000" font-size="10">無衝突</text>
          <rect x="40" y="440" rx="12" width="180" height="55" fill="#008000" opacity="0.12" stroke="#008000" stroke-width="2"/>
          <text x="130" y="462" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">✅ 預約確認</text>
          <text x="130" y="478" text-anchor="middle" fill="#666" font-size="9">L1 直接通過 / L2-L3 待核</text>
          <line x1="40" y1="385" x2="40" y2="440" stroke="#008000" stroke-width="1.5"/>
          <line x1="130" y1="495" x2="130" y2="530" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>
          <rect x="55" y="535" rx="12" width="150" height="45" fill="white" stroke="#008000" stroke-width="2"/><text x="130" y="555" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">📄 PDF 申請單</text><text x="130" y="568" text-anchor="middle" fill="#666" font-size="9">TOTP QR Code</text>

          {{-- Has conflict → Stage 2 --}}
          <line x1="270" y1="385" x2="390" y2="385" stroke="#DAA520" stroke-width="2.5" marker-end="url(#arrow-y)"/>
          <text x="330" y="378" text-anchor="middle" fill="#DAA520" font-size="10" font-weight="bold">有衝突</text>

          {{-- Stage 2 --}}
          <rect x="390" y="80" rx="12" width="220" height="45" fill="#DAA520" opacity="0.2" stroke="#DAA520" stroke-width="2"/><text x="500" y="107" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">📱 LINE Notify 通知雙方</text>
          <line x1="500" y1="125" x2="500" y2="160" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          <rect x="390" y="165" rx="12" width="220" height="50" fill="white" stroke="#DAA520" stroke-width="2"/><text x="500" y="185" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">🔗 提供聯絡人資訊</text><text x="500" y="200" text-anchor="middle" fill="#666" font-size="9">私下調解入口開啟</text>
          <line x1="500" y1="215" x2="500" y2="250" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          <rect x="390" y="255" rx="12" width="220" height="50" fill="white" stroke="#DAA520" stroke-width="2"/><text x="500" y="275" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">💬 即時協商對話</text><text x="500" y="290" text-anchor="middle" fill="#666" font-size="9">行事曆面板 → 協商對話框</text>
          <line x1="500" y1="305" x2="500" y2="340" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          <rect x="390" y="345" rx="12" width="100" height="40" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="440" y="362" text-anchor="middle" fill="#003153" font-size="9">📧 郵件通知</text><text x="440" y="375" text-anchor="middle" fill="#666" font-size="8">Outlook</text>
          <rect x="510" y="345" rx="12" width="100" height="40" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="560" y="362" text-anchor="middle" fill="#003153" font-size="9">💬 WebSocket</text><text x="560" y="375" text-anchor="middle" fill="#666" font-size="8">即時對話</text>
          <line x1="500" y1="385" x2="500" y2="420" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          {{-- Timer events --}}
          <polygon points="500,425 580,460 500,495 420,460" fill="#FFE8B0" stroke="#DAA520" stroke-width="2"/>
          <text x="500" y="457" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">⏱ 計時</text><text x="500" y="470" text-anchor="middle" fill="#003153" font-size="9">3/6 min</text>

          {{-- 3 min: AI intervention --}}
          <line x1="580" y1="460" x2="680" y2="460" stroke="#DAA520" stroke-width="1.5" marker-end="url(#arrow-y)"/>
          <text x="630" y="453" text-anchor="middle" fill="#DAA520" font-size="9">3 分鐘</text>
          <rect x="680" y="440" rx="8" width="80" height="40" fill="#DAA520" opacity="0.15" stroke="#DAA520" stroke-width="1.5"/><text x="720" y="457" text-anchor="middle" fill="#003153" font-size="9" font-weight="bold">🤖 GPT-4</text><text x="720" y="470" text-anchor="middle" fill="#003153" font-size="8">3建議方案</text>

          {{-- 6 min: forced close --}}
          <line x1="420" y1="460" x2="395" y2="530" stroke="#FF0000" stroke-width="1.5" stroke-dasharray="4" marker-end="url(#arrow-r)"/>
          <rect x="380" y="535" rx="8" width="110" height="50" fill="#FF0000" opacity="0.08" stroke="#FF0000" stroke-width="1.5"/><text x="435" y="555" text-anchor="middle" fill="#FF0000" font-size="9" font-weight="bold">⚠️ 6分鐘超時</text><text x="435" y="568" text-anchor="middle" fill="#FF0000" font-size="8">扣10分 + 紅光動畫</text><text x="435" y="580" text-anchor="middle" fill="#FF0000" font-size="8">強制關閉對話</text>

          {{-- Normal path → confirm --}}
          <line x1="500" y1="495" x2="500" y2="530" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>
          <rect x="500" y="535" rx="12" width="260" height="55" fill="#DAA520" opacity="0.15" stroke="#DAA520" stroke-width="2"/>
          <text x="630" y="557" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">✅ 「雙方完成協商，我要更動行事曆」</text>
          <text x="630" y="573" text-anchor="middle" fill="#666" font-size="9">甲方 ✓ + 乙方 ✓ → 更新 conflicts 表</text>

          {{-- auto-substitute path --}}
          <rect x="380" y="620" rx="8" width="120" height="35" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="440" y="635" text-anchor="middle" fill="#003153" font-size="9">🔄 一方撤回</text><text x="440" y="648" text-anchor="middle" fill="#666" font-size="8">自動依權重遞補</text>

          {{-- Both confirmed → Stage 3 --}}
          <line x1="760" y1="562" x2="810" y2="562" stroke="#008000" stroke-width="2.5" marker-end="url(#arrow-g)"/>
          <text x="785" y="555" text-anchor="middle" fill="#008000" font-size="9" font-weight="bold">確認</text>

          {{-- Stage 3 --}}
          <rect x="810" y="80" rx="12" width="230" height="55" fill="white" stroke="#008000" stroke-width="2"/><text x="925" y="100" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">📋 Gatekeeping 依賴檢查</text><text x="925" y="116" text-anchor="middle" fill="#666" font-size="9">活動核備編號 → 場地預約 → 器材借用</text>
          <line x1="925" y1="135" x2="925" y2="170" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>

          <rect x="810" y="175" rx="12" width="230" height="55" fill="white" stroke="#008000" stroke-width="2"/><text x="925" y="195" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">🔍 RAG 法規比對</text><text x="925" y="211" text-anchor="middle" fill="#666" font-size="9">Dify API Knowledge Base 檢索</text>
          <line x1="925" y1="230" x2="925" y2="265" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>

          <rect x="810" y="270" rx="12" width="230" height="55" fill="white" stroke="#008000" stroke-width="2"/><text x="925" y="290" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">👨‍💼 行政人員最終審核</text><text x="925" y="306" text-anchor="middle" fill="#666" font-size="9">課指組專員簽核</text>
          <line x1="925" y1="325" x2="925" y2="360" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>

          <rect x="830" y="365" rx="12" width="190" height="50" fill="#008000" opacity="0.15" stroke="#008000" stroke-width="2"/><text x="925" y="387" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">✅ 核准完成</text><text x="925" y="402" text-anchor="middle" fill="#666" font-size="9">進入監控狀態</text>
          <line x1="925" y1="415" x2="925" y2="450" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>

          <rect x="830" y="455" rx="12" width="190" height="45" fill="white" stroke="#008000" stroke-width="1.5"/><text x="925" y="475" text-anchor="middle" fill="#003153" font-size="10">📄 自動產出 PDF 申請單</text><text x="925" y="488" text-anchor="middle" fill="#666" font-size="9">🔑 TOTP QR Code</text>
          <line x1="925" y1="500" x2="925" y2="540" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/>

          <rect x="810" y="545" rx="12" width="230" height="50" fill="white" stroke="#003153" stroke-width="1.5"/><text x="925" y="565" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🔔 信用連動監控</text><text x="925" y="580" text-anchor="middle" fill="#666" font-size="8">違規 → 扣分 → &lt;60強制登出</text>

          {{-- System notification side --}}
          <rect x="810" y="630" rx="12" width="230" height="80" fill="white" stroke="#003153" stroke-width="1.5"/><text x="925" y="650" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">📋 多通路通知鏈</text><text x="925" y="667" text-anchor="middle" fill="#666" font-size="9">• LINE Notify 即時推播</text><text x="925" y="680" text-anchor="middle" fill="#666" font-size="9">• SMS 簡訊通知</text><text x="925" y="693" text-anchor="middle" fill="#666" font-size="9">• SMTP Email 收據</text><text x="925" y="706" text-anchor="middle" fill="#666" font-size="9">• 系統內通知 (鈴鐺)</text>
        </svg>
      </div>
    </div>
  </div>

  {{-- ============ Tab 2: System Architecture ============ --}}
  <div id="tab-architecture" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-sitemap mr-2 text-fju-yellow"></i>系統全域架構圖</h3>
      <p class="text-xs text-gray-500 mb-4">標示 Dify AI 中台、Cloudflare WAF/Turnstile/R2、Laravel API 串接、多通路通知鏈</p>
      <div class="min-w-[900px]">
        <svg viewBox="0 0 1100 800" xmlns="http://www.w3.org/2000/svg" class="w-full">
          {{-- Top: Edge Protection Layer --}}
          <rect x="50" y="20" width="1000" height="100" rx="15" fill="#003153" opacity="0.06" stroke="#003153" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="550" y="42" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">🛡️ 邊緣防護層 (Cloudflare)</text>
          <rect x="80" y="55" width="150" height="50" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="155" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🔥 WAF 防火牆</text><text x="155" y="92" text-anchor="middle" fill="#666" font-size="8">Skip Rules @fju.edu.tw</text>
          <rect x="270" y="55" width="150" height="50" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="345" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🤖 Turnstile</text><text x="345" y="92" text-anchor="middle" fill="#666" font-size="8">非互動驗證</text>
          <rect x="460" y="55" width="150" height="50" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="535" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">📦 R2 Storage</text><text x="535" y="92" text-anchor="middle" fill="#666" font-size="8">圖片/文件 + GPS 浮水印</text>
          <rect x="650" y="55" width="180" height="50" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="740" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">⚡ Workers AI</text><text x="740" y="92" text-anchor="middle" fill="#666" font-size="8">Llama-3 意圖過濾</text>
          <rect x="870" y="55" width="150" height="50" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="945" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🌐 CDN</text><text x="945" y="92" text-anchor="middle" fill="#666" font-size="8">全球邊緣節點</text>

          {{-- Frontend Layer --}}
          <rect x="50" y="150" width="1000" height="100" rx="15" fill="#DAA520" opacity="0.06" stroke="#DAA520" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="550" y="172" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">🖥️ 前端層 (Vue 3 + GSAP + Leaflet + Chart.js)</text>
          <rect x="80" y="185" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="150" y="207" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Landing Page</text><text x="150" y="222" text-anchor="middle" fill="#666" font-size="8">GSAP + ScrollTrigger</text>
          <rect x="250" y="185" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="320" y="207" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Dashboard</text><text x="320" y="222" text-anchor="middle" fill="#666" font-size="8">5角色專屬圖表</text>
          <rect x="420" y="185" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="490" y="207" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Campus Map</text><text x="490" y="222" text-anchor="middle" fill="#666" font-size="8">Leaflet + SVG</text>
          <rect x="590" y="185" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="660" y="207" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">10大模組頁面</text><text x="660" y="222" text-anchor="middle" fill="#666" font-size="8">vue-i18n 5語言</text>
          <rect x="760" y="185" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="830" y="207" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">PWA 離線支援</text><text x="830" y="222" text-anchor="middle" fill="#666" font-size="8">Service Worker</text>
          <rect x="930" y="185" width="100" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="980" y="207" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🐕 柴犬</text><text x="980" y="222" text-anchor="middle" fill="#666" font-size="8">AI FAB</text>

          {{-- API arrows --}}
          <line x1="550" y1="120" x2="550" y2="150" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/><text x="560" y="140" fill="#003153" font-size="8">HTTPS</text>
          <line x1="550" y1="250" x2="550" y2="290" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/><text x="565" y="275" fill="#003153" font-size="8">REST API / AJAX</text>

          {{-- Backend Layer --}}
          <rect x="50" y="290" width="1000" height="130" rx="15" fill="#003153" opacity="0.06" stroke="#003153" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="550" y="312" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">⚙️ 後端層 (PHP 8.2 + Laravel 12)</text>
          <rect x="80" y="325" width="130" height="80" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="145" y="347" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">路由層</text><text x="145" y="362" text-anchor="middle" fill="#666" font-size="8">api.php</text><text x="145" y="375" text-anchor="middle" fill="#666" font-size="8">web.php</text><text x="145" y="388" text-anchor="middle" fill="#666" font-size="8">30+ endpoints</text>
          <rect x="240" y="325" width="130" height="80" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="305" y="347" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Controllers</text><text x="305" y="362" text-anchor="middle" fill="#666" font-size="8">CrudController</text><text x="305" y="375" text-anchor="middle" fill="#666" font-size="8">ReservationCtrl</text><text x="305" y="388" text-anchor="middle" fill="#666" font-size="8">7 Controllers</text>
          <rect x="400" y="325" width="130" height="80" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="465" y="347" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Models</text><text x="465" y="362" text-anchor="middle" fill="#666" font-size="8">Eloquent ORM</text><text x="465" y="375" text-anchor="middle" fill="#666" font-size="8">21 Models</text><text x="465" y="388" text-anchor="middle" fill="#666" font-size="8">悲觀鎖 + 交易</text>
          <rect x="560" y="325" width="130" height="80" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="625" y="347" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Middleware</text><text x="625" y="362" text-anchor="middle" fill="#666" font-size="8">JWT Auth</text><text x="625" y="375" text-anchor="middle" fill="#666" font-size="8">CORS</text><text x="625" y="388" text-anchor="middle" fill="#666" font-size="8">Credit Check</text>
          <rect x="720" y="325" width="130" height="80" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="785" y="347" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Services</text><text x="785" y="362" text-anchor="middle" fill="#666" font-size="8">DifyService</text><text x="785" y="375" text-anchor="middle" fill="#666" font-size="8">CreditService</text><text x="785" y="388" text-anchor="middle" fill="#666" font-size="8">NotifyService</text>
          <rect x="880" y="325" width="140" height="80" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="950" y="347" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">GuzzleHTTP</text><text x="950" y="362" text-anchor="middle" fill="#666" font-size="8">→ Dify API</text><text x="950" y="375" text-anchor="middle" fill="#666" font-size="8">→ LINE Notify</text><text x="950" y="388" text-anchor="middle" fill="#666" font-size="8">→ Graph API</text>

          {{-- DB + Cache Layer --}}
          <line x1="550" y1="420" x2="550" y2="460" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>
          <rect x="50" y="460" width="480" height="100" rx="15" fill="#008000" opacity="0.06" stroke="#008000" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="290" y="482" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">💾 資料層</text>
          <rect x="80" y="495" width="140" height="50" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="150" y="517" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">MySQL 8.0</text><text x="150" y="532" text-anchor="middle" fill="#666" font-size="8">InnoDB + 21 Tables</text>
          <rect x="250" y="495" width="110" height="50" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="305" y="517" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Redis</text><text x="305" y="532" text-anchor="middle" fill="#666" font-size="8">計時 + 2FA</text>
          <rect x="390" y="495" width="120" height="50" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="450" y="517" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Migrations</text><text x="450" y="532" text-anchor="middle" fill="#666" font-size="8">Schema 版本控制</text>

          {{-- AI Platform --}}
          <rect x="570" y="460" width="480" height="100" rx="15" fill="#DAA520" opacity="0.06" stroke="#DAA520" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="810" y="482" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">🤖 Dify AI 中台</text>
          <rect x="590" y="495" width="130" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="655" y="517" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Knowledge Base</text><text x="655" y="532" text-anchor="middle" fill="#666" font-size="8">FJU 法規文件</text>
          <rect x="740" y="495" width="130" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="805" y="517" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">RAG 引擎</text><text x="805" y="532" text-anchor="middle" fill="#666" font-size="8">Pinecone 向量 DB</text>
          <rect x="890" y="495" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="960" y="517" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Workflow</text><text x="960" y="532" text-anchor="middle" fill="#666" font-size="8">預審 / 企劃 / 摘要</text>

          {{-- Notification Layer --}}
          <rect x="50" y="600" width="1000" height="90" rx="15" fill="#003153" opacity="0.06" stroke="#003153" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="550" y="622" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">📡 多通路通知鏈</text>
          <rect x="100" y="635" width="150" height="40" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="175" y="660" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">📱 LINE Notify</text>
          <rect x="290" y="635" width="150" height="40" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="365" y="660" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">📧 SMTP Email</text>
          <rect x="480" y="635" width="150" height="40" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="555" y="660" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">💬 SMS 簡訊</text>
          <rect x="670" y="635" width="150" height="40" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="745" y="660" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">🔔 系統推播</text>
          <rect x="860" y="635" width="160" height="40" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="940" y="660" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">📊 Graph API</text>
          <line x1="550" y1="560" x2="550" y2="600" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Module Architecture below --}}
          <text x="550" y="730" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">Docker 容器化部署 | Git 版本控制 | CI/CD 自動化 | E2E 測試</text>
          <rect x="150" y="745" width="800" height="3" rx="1" fill="#DAA520"/>
          <text x="550" y="770" text-anchor="middle" fill="#666" font-size="9">FJU Smart Hub v3.0 — Laravel 12 + MySQL 8.0 + Vue 3 + Dify AI + Cloudflare Edge</text>
        </svg>
      </div>
    </div>

    {{-- Module Architecture --}}
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 mt-4">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-cubes mr-2 text-fju-yellow"></i>模組架構圖</h3>
      <div class="grid md:grid-cols-4 gap-3">
        @foreach([
          ['前台模組','fas fa-desktop','bg-fju-blue/10 border-fju-blue/20',['Landing Page (GSAP動畫)','登入 (Google OAuth)','Dashboard (5角色)','校園地圖 (Leaflet+SVG)']],
          ['核心業務','fas fa-cogs','bg-fju-yellow/10 border-fju-yellow/20',['場地預約 (三階段)','設備借用+追蹤','活動牆 (CRUD+報名)','社團資訊 (102社團)','行事曆 (EvoCalendar)','衝突協調中心']],
          ['AI 智慧模組','fas fa-brain','bg-fju-green/10 border-fju-green/20',['AI 預審 (Dify RAG)','AI 企劃生成器','AI 申訴摘要','AI 協商建議 (GPT-4)','RAG 法規檢索']],
          ['管理與安全','fas fa-shield-alt','bg-purple-50 border-purple-200',['報修管理','申訴記錄','信用積分 (觀察者)','數位證書 (簽章)','2FA (TOTP/SMS)','通知系統 (多通路)','i18n (5語言)','E-Portfolio']]
        ] as $group)
        <div class="rounded-fju p-4 border {{ $group[2] }}">
          <div class="flex items-center gap-2 mb-3"><i class="{{ $group[1] }} text-fju-blue"></i><span class="font-bold text-fju-blue text-sm">{{ $group[0] }}</span></div>
          <ul class="space-y-1">@foreach($group[3] as $item)<li class="text-xs text-gray-600 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-fju-yellow"></span>{{ $item }}</li>@endforeach</ul>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ============ Tab 3: Swimlane Diagram ============ --}}
  <div id="tab-swimlane" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-stream mr-2 text-fju-yellow"></i>多角色全端功能泳道圖</h3>
      <p class="text-xs text-gray-500 mb-4">橫軸：操作階段（登入→搜尋→預約→審核→簽到）。縱軸：學生、社團幹部、教授、行政、系統管理員。含 AI 預審標記與專員最終審核。</p>
      <div class="min-w-[900px] overflow-x-auto">
        <table class="w-full text-xs border-collapse">
          <thead>
            <tr class="bg-fju-blue text-white">
              <th class="p-3 text-left w-28 border-r border-white/20">角色 / 階段</th>
              <th class="p-3 text-center border-r border-white/20">🔐 登入驗證</th>
              <th class="p-3 text-center border-r border-white/20">🔍 搜尋瀏覽</th>
              <th class="p-3 text-center border-r border-white/20">📅 預約申請</th>
              <th class="p-3 text-center border-r border-white/20">🤖 AI 預審</th>
              <th class="p-3 text-center border-r border-white/20">👨‍💼 審核核准</th>
              <th class="p-3 text-center">✅ 簽到使用</th>
            </tr>
          </thead>
          <tbody>
            @foreach([
              ['🎓 學生','bg-blue-50',
                'Google OAuth<br>@cloud.fju.edu.tw<br>hd 強制檢查',
                '校園地圖查詢<br>場地搜尋<br>社團瀏覽',
                '填寫志願序<br>L3 優先權<br>提交預約申請',
                '— (系統自動)',
                '— (等待結果)<br>收到通知',
                'TOTP QR Code<br>核銷簽到<br>活動回饋'
              ],
              ['👔 社團幹部','bg-yellow-50',
                'Google OAuth<br>2FA (TOTP/SMS)<br>PHPGangsta',
                '場地排程查詢<br>社員管理<br>預算查看',
                'L2 優先權<br>多時段志願<br>企劃書上傳',
                '接收 AI 標記<br>⚠️ 警示回應<br>修正後重送',
                '等待專員審核<br>衝突協商<br>接收核准通知',
                'TOTP 核銷<br>出席管理<br>經費核銷'
              ],
              ['👩‍🏫 指導教授','bg-green-50',
                'Google OAuth<br>2FA 強制啟用',
                '社團績效查詢<br>活動紀錄<br>風險監控',
                '— (審閱角色)',
                '查看 AI 報告<br>標記意見<br>建議修改',
                '教授簽核<br>風險評估<br>最終建議',
                '活動觀察<br>績效評分<br>輔導記錄'
              ],
              ['🏛️ 課指組行政','bg-purple-50',
                'Google OAuth<br>2FA + JWT<br>HttpOnly Cookie',
                '全域搜尋<br>跨社團查詢<br>統計儀表板',
                '— (核准角色)<br>L1 可直接通過',
                '📊 AI 稽核報告<br>Dify reasoning<br>違規標記檢視',
                '<b>最終核准/退回</b><br>法規比對<br>PDF 簽發',
                '場地監控<br>信用積分管理<br>事後稽核'
              ],
              ['🖥️ 系統管理員','bg-gray-50',
                'Google OAuth<br>2FA 強制<br>管理員密鑰',
                '系統日誌<br>API 監控<br>WAF 日誌',
                '— (維護角色)',
                'AI 模型參數<br>RAG 知識庫<br>更新維護',
                '系統設定<br>帳號管理<br>權限調整',
                '系統監控<br>效能調優<br>備份還原'
              ],
            ] as $row)
            <tr class="border-t border-gray-200 {{ $row[1] }}">
              <td class="p-3 font-bold text-fju-blue border-r border-gray-200 whitespace-nowrap">{{ $row[0] }}</td>
              @for($i=2; $i<=7; $i++)<td class="p-3 text-center border-r border-gray-100 leading-relaxed">{!! $row[$i] !!}</td>@endfor
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-4 p-3 bg-fju-yellow/10 rounded-fju text-xs text-gray-600">
        <b>AI 預審互動說明：</b>系統在「預約申請」後自動調用 Dify API 進行法規 RAG 比對。若 AI 標記 <span class="text-fju-red font-bold">⚠️ 高風險</span>，系統自動通知社團幹部與指導教授進行修正。所有 AI 稽核結果均記錄於 <code>ai_review_logs</code> 表中，並提供至行政人員作為最終審核依據。
      </div>
    </div>
  </div>

  {{-- ============ Tab 4: Dify AI RAG ============ --}}
  <div id="tab-dify" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-robot mr-2 text-fju-yellow"></i>Dify AI 智慧預審與 RAG 流程圖</h3>
      <p class="text-xs text-gray-500 mb-4">輸入(預約/企劃書) → GuzzleHTTP → Dify API → Knowledge Base RAG 法規檢索 → Reasoning JSON + 違規標記</p>
      <div class="min-w-[800px]">
        <svg viewBox="0 0 1000 650" xmlns="http://www.w3.org/2000/svg" class="w-full">
          {{-- Input --}}
          <rect x="50" y="40" width="200" height="70" rx="12" fill="#003153" opacity="0.1" stroke="#003153" stroke-width="2"/><text x="150" y="65" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">📝 使用者輸入</text><text x="150" y="82" text-anchor="middle" fill="#666" font-size="9">預約申請 / 企劃書 / 申訴</text><text x="150" y="95" text-anchor="middle" fill="#666" font-size="9">application_type + reason</text>
          <line x1="250" y1="75" x2="330" y2="75" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Laravel Processing --}}
          <rect x="330" y="30" width="200" height="90" rx="12" fill="white" stroke="#003153" stroke-width="2"/><text x="430" y="55" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">⚙️ Laravel 後端</text><text x="430" y="73" text-anchor="middle" fill="#666" font-size="9">1. 驗證 JWT Token</text><text x="430" y="86" text-anchor="middle" fill="#666" font-size="9">2. 組裝 AI 請求 JSON</text><text x="430" y="99" text-anchor="middle" fill="#666" font-size="9">3. GuzzleHTTP 呼叫</text>
          <line x1="530" y1="75" x2="610" y2="75" stroke="#DAA520" stroke-width="2.5" marker-end="url(#arrow-y)"/><text x="570" y="68" text-anchor="middle" fill="#DAA520" font-size="9" font-weight="bold">POST /v1/workflows/run</text>

          {{-- Dify API --}}
          <rect x="610" y="20" width="340" height="110" rx="15" fill="#DAA520" opacity="0.08" stroke="#DAA520" stroke-width="2"/>
          <text x="780" y="45" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">🤖 Dify AI 中台</text>
          <rect x="630" y="55" width="140" height="60" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="700" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Workflow Engine</text><text x="700" y="92" text-anchor="middle" fill="#666" font-size="8">task_id 追蹤</text><text x="700" y="104" text-anchor="middle" fill="#666" font-size="8">session 管理</text>
          <rect x="790" y="55" width="140" height="60" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="860" y="77" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">LLM (GPT-4)</text><text x="860" y="92" text-anchor="middle" fill="#666" font-size="8">推理引擎</text><text x="860" y="104" text-anchor="middle" fill="#666" font-size="8">多語言理解</text>
          <line x1="780" y1="130" x2="780" y2="170" stroke="#DAA520" stroke-width="2" marker-end="url(#arrow-y)"/>

          {{-- RAG Knowledge Base --}}
          <rect x="550" y="175" width="460" height="120" rx="15" fill="#008000" opacity="0.06" stroke="#008000" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="780" y="197" text-anchor="middle" fill="#003153" font-size="14" font-weight="bold">📚 RAG Knowledge Base</text>
          <rect x="570" y="210" width="130" height="70" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="635" y="232" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">Pinecone</text><text x="635" y="247" text-anchor="middle" fill="#666" font-size="8">向量資料庫</text><text x="635" y="262" text-anchor="middle" fill="#666" font-size="8">Embedding 索引</text>
          <rect x="720" y="210" width="130" height="70" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="785" y="232" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">FJU 法規庫</text><text x="785" y="247" text-anchor="middle" fill="#666" font-size="8">5大官方網址</text><text x="785" y="262" text-anchor="middle" fill="#666" font-size="8">活動/場地/器材規則</text>
          <rect x="870" y="210" width="120" height="70" rx="10" fill="white" stroke="#008000" stroke-width="1.5"/><text x="930" y="232" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">FAQ 知識庫</text><text x="930" y="247" text-anchor="middle" fill="#666" font-size="8">常見問題</text><text x="930" y="262" text-anchor="middle" fill="#666" font-size="8">表單下載</text>

          {{-- Output processing --}}
          <line x1="780" y1="295" x2="780" y2="340" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>
          <rect x="600" y="345" width="360" height="100" rx="12" fill="white" stroke="#003153" stroke-width="2"/>
          <text x="780" y="370" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">📊 AI 稽核結果 (Reasoning JSON)</text>
          <text x="780" y="390" text-anchor="middle" fill="#666" font-size="9">risk_level: "Medium" | "High" | "Low"</text>
          <text x="780" y="405" text-anchor="middle" fill="#666" font-size="9">reasoning: "超過容量...需安全計畫書"</text>
          <text x="780" y="420" text-anchor="middle" fill="#666" font-size="9">suggested_tags: ["人流過載預警","噪音疑慮"]</text>
          <text x="780" y="435" text-anchor="middle" fill="#666" font-size="9">allow_next_step: true/false | law_reference</text>

          {{-- Decision --}}
          <line x1="780" y1="445" x2="780" y2="480" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>
          <polygon points="780,485 860,520 780,555 700,520" fill="#FFE8B0" stroke="#DAA520" stroke-width="2"/>
          <text x="780" y="523" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">allow?</text>

          {{-- Pass --}}
          <line x1="860" y1="520" x2="950" y2="520" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/><text x="905" y="513" text-anchor="middle" fill="#008000" font-size="9">通過</text>
          <rect x="950" y="500" width="120" height="40" rx="10" fill="#008000" opacity="0.12" stroke="#008000" stroke-width="1.5"/><text x="1010" y="524" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">✅ 進入審核</text>

          {{-- Block --}}
          <line x1="700" y1="520" x2="600" y2="520" stroke="#FF0000" stroke-width="2" marker-end="url(#arrow-r)"/><text x="650" y="513" text-anchor="middle" fill="#FF0000" font-size="9">阻斷</text>
          <rect x="450" y="500" width="150" height="40" rx="10" fill="#FF0000" opacity="0.08" stroke="#FF0000" stroke-width="1.5"/><text x="525" y="524" text-anchor="middle" fill="#FF0000" font-size="10" font-weight="bold">⚠️ 退回 + 引導</text>

          {{-- Logging --}}
          <line x1="780" y1="555" x2="780" y2="590" stroke="#003153" stroke-width="1.5" marker-end="url(#arrow)"/>
          <rect x="650" y="595" width="260" height="40" rx="10" fill="white" stroke="#003153" stroke-width="1.5"/><text x="780" y="620" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">💾 ai_review_logs + credit_logs</text>

          {{-- Input JSON format --}}
          <rect x="50" y="160" width="400" height="160" rx="12" fill="white" stroke="#003153" stroke-width="1.5"/>
          <text x="250" y="182" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">📤 請求格式 (Laravel → Dify)</text>
          <text x="70" y="200" fill="#666" font-size="9" font-family="monospace">{</text>
          <text x="80" y="215" fill="#666" font-size="9" font-family="monospace">"inputs": {</text>
          <text x="95" y="230" fill="#666" font-size="9" font-family="monospace">"user_id": "112071000",</text>
          <text x="95" y="245" fill="#666" font-size="9" font-family="monospace">"role": "社團幹部",</text>
          <text x="95" y="260" fill="#666" font-size="9" font-family="monospace">"application_type": "場地預約",</text>
          <text x="95" y="275" fill="#666" font-size="9" font-family="monospace">"reason": "辦理電競大賽, 200人",</text>
          <text x="95" y="290" fill="#666" font-size="9" font-family="monospace">"attached_files": ["plan.pdf"]</text>
          <text x="80" y="305" fill="#666" font-size="9" font-family="monospace">}, "user": "session_id"</text>
          <text x="70" y="320" fill="#666" font-size="9" font-family="monospace">}</text>
        </svg>
      </div>
    </div>
  </div>

  {{-- ============ Tab 5: Security & Identity ============ --}}
  <div id="tab-security" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-shield-alt mr-2 text-fju-yellow"></i>安全驗證與身分生命週期圖</h3>
      <p class="text-xs text-gray-500 mb-4">Google OAuth (hd 檢查) → 2FA (PHPGangsta+Redis) → JWT → 觀察者模式 → 信用 &lt;60 強制 JWT 失效</p>
      <div class="min-w-[800px]">
        <svg viewBox="0 0 1000 600" xmlns="http://www.w3.org/2000/svg" class="w-full">
          {{-- Start --}}
          <circle cx="100" cy="60" r="30" fill="#003153" opacity="0.1" stroke="#003153" stroke-width="2"/><text x="100" y="64" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">使用者</text>
          <line x1="130" y1="60" x2="200" y2="60" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Google OAuth --}}
          <rect x="200" y="30" width="200" height="60" rx="12" fill="white" stroke="#003153" stroke-width="2"/><text x="300" y="52" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">🔐 Google OAuth 2.0</text><text x="300" y="70" text-anchor="middle" fill="#666" font-size="9">強制 hd = cloud.fju.edu.tw</text>
          <line x1="400" y1="60" x2="470" y2="60" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- hd check --}}
          <polygon points="520,30 590,60 520,90 450,60" fill="#FFE8B0" stroke="#DAA520" stroke-width="2"/>
          <text x="520" y="63" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">hd?</text>
          <line x1="520" y1="90" x2="520" y2="130" stroke="#FF0000" stroke-width="1.5" marker-end="url(#arrow-r)"/><text x="530" y="115" fill="#FF0000" font-size="9">非輔大</text>
          <rect x="460" y="130" width="120" height="35" rx="8" fill="#FF0000" opacity="0.1" stroke="#FF0000" stroke-width="1.5"/><text x="520" y="152" text-anchor="middle" fill="#FF0000" font-size="10" font-weight="bold">❌ 拒絕登入</text>

          <line x1="590" y1="60" x2="660" y2="60" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/><text x="625" y="53" fill="#008000" font-size="9">✓ 輔大</text>

          {{-- 2FA --}}
          <rect x="660" y="25" width="260" height="70" rx="12" fill="white" stroke="#DAA520" stroke-width="2"/>
          <text x="790" y="48" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">🔑 2FA 雙因子驗證</text>
          <text x="790" y="65" text-anchor="middle" fill="#666" font-size="9">TOTP (PHPGangsta/GoogleAuthenticator)</text>
          <text x="790" y="80" text-anchor="middle" fill="#666" font-size="9">SMS 驗證 | Redis 暫存 OTP (300s TTL)</text>
          <line x1="790" y1="95" x2="790" y2="140" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- JWT Generation --}}
          <rect x="660" y="145" width="260" height="60" rx="12" fill="#003153" opacity="0.1" stroke="#003153" stroke-width="2"/><text x="790" y="170" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">🎫 JWT Token 產出</text><text x="790" y="187" text-anchor="middle" fill="#666" font-size="9">HttpOnly Cookie | exp: 2h | role claim</text>
          <line x1="790" y1="205" x2="790" y2="250" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Active session --}}
          <rect x="600" y="255" width="380" height="70" rx="12" fill="#008000" opacity="0.08" stroke="#008000" stroke-width="2"/>
          <text x="790" y="280" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">✅ 活躍會話 (Active Session)</text>
          <text x="790" y="298" text-anchor="middle" fill="#666" font-size="9">每次 API 請求驗證 JWT | 觀察者模式監控信用分數</text>
          <line x1="790" y1="325" x2="790" y2="365" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>

          {{-- Observer Pattern --}}
          <rect x="550" y="370" width="480" height="100" rx="15" fill="#DAA520" opacity="0.06" stroke="#DAA520" stroke-width="1.5" stroke-dasharray="6"/>
          <text x="790" y="392" text-anchor="middle" fill="#003153" font-size="12" font-weight="bold">👁️ 觀察者模式 (Observer Pattern)</text>
          <rect x="570" y="405" width="140" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="640" y="425" text-anchor="middle" fill="#003153" font-size="9" font-weight="bold">CreditObserver</text><text x="640" y="440" text-anchor="middle" fill="#666" font-size="8">即時監聽 credit_score</text>
          <rect x="730" y="405" width="130" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="795" y="425" text-anchor="middle" fill="#003153" font-size="9" font-weight="bold">NotifyObserver</text><text x="795" y="440" text-anchor="middle" fill="#666" font-size="8">通知 + 紅點</text>
          <rect x="880" y="405" width="130" height="50" rx="10" fill="white" stroke="#DAA520" stroke-width="1.5"/><text x="945" y="425" text-anchor="middle" fill="#003153" font-size="9" font-weight="bold">AuditObserver</text><text x="945" y="440" text-anchor="middle" fill="#666" font-size="8">credit_logs 記錄</text>

          {{-- Score check --}}
          <line x1="790" y1="470" x2="790" y2="510" stroke="#003153" stroke-width="2" marker-end="url(#arrow)"/>
          <polygon points="790,515 870,545 790,575 710,545" fill="#FFE8B0" stroke="#DAA520" stroke-width="2"/>
          <text x="790" y="543" text-anchor="middle" fill="#003153" font-size="10" font-weight="bold">&lt; 60?</text>

          {{-- Normal --}}
          <line x1="870" y1="545" x2="960" y2="545" stroke="#008000" stroke-width="2" marker-end="url(#arrow-g)"/><text x="915" y="538" fill="#008000" font-size="9">≥ 60</text>
          <text x="980" y="548" fill="#008000" font-size="10" font-weight="bold">✅ 正常</text>

          {{-- Forced logout --}}
          <line x1="710" y1="545" x2="600" y2="545" stroke="#FF0000" stroke-width="2.5" marker-end="url(#arrow-r)"/><text x="655" y="538" fill="#FF0000" font-size="9">&lt; 60</text>
          <rect x="350" y="515" width="250" height="60" rx="12" fill="#FF0000" opacity="0.1" stroke="#FF0000" stroke-width="2"/>
          <text x="475" y="538" text-anchor="middle" fill="#FF0000" font-size="11" font-weight="bold">⚠️ 強制 JWT 失效</text>
          <text x="475" y="555" text-anchor="middle" fill="#FF0000" font-size="9">INVALIDATE_JWT | LINE + SMS 通知</text>
          <text x="475" y="568" text-anchor="middle" fill="#FF0000" font-size="9">🔒 停權 | 需管理員解鎖</text>

          {{-- Anti-repudiation --}}
          <rect x="50" y="250" width="350" height="130" rx="12" fill="white" stroke="#003153" stroke-width="1.5"/>
          <text x="225" y="272" text-anchor="middle" fill="#003153" font-size="11" font-weight="bold">🔏 防抵賴追蹤機制</text>
          <text x="70" y="295" fill="#666" font-size="9">• 通知含 Unique Token (track.php)</text>
          <text x="70" y="312" fill="#666" font-size="9">• 記錄時間戳 + IP + User-Agent</text>
          <text x="70" y="329" fill="#666" font-size="9">• 重要通知未讀 → 鎖定螢幕 10秒 Modal</text>
          <text x="70" y="346" fill="#666" font-size="9">• 所有扣分記入 credit_logs + notification_logs</text>
          <text x="70" y="363" fill="#666" font-size="9">• PWA 離線支援：地下室可讀取憑證</text>
        </svg>
      </div>
    </div>
  </div>

  {{-- ============ Tab 6: Permissions ============ --}}
  <div id="tab-permissions" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100 overflow-x-auto">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-user-shield mr-2 text-fju-yellow"></i>角色權限矩陣</h3>
      <table class="w-full text-sm min-w-[700px]">
        <thead class="bg-fju-blue text-white"><tr>
          <th class="p-3 text-left">功能模組</th>
          <th class="p-3 text-center">Admin<br><span class="text-[10px] text-white/60">課指組</span></th>
          <th class="p-3 text-center">Officer<br><span class="text-[10px] text-white/60">社團幹部</span></th>
          <th class="p-3 text-center">Professor<br><span class="text-[10px] text-white/60">指導教授</span></th>
          <th class="p-3 text-center">Student<br><span class="text-[10px] text-white/60">一般學生</span></th>
          <th class="p-3 text-center">IT<br><span class="text-[10px] text-white/60">資訊中心</span></th>
        </tr></thead>
        <tbody>
          @foreach([
            ['Dashboard 儀表板','✅ 全域','✅ 社團','✅ 指導','✅ 個人','✅ 系統'],
            ['場地預約 / 衝突協調','✅ 核准','✅ 申請','✅ 審閱','✅ 申請','🔧 維護'],
            ['設備借用','✅ 管理','✅ 借還','👁️ 瀏覽','✅ 借還','🔧 維護'],
            ['活動牆','✅ 管理','✅ CRUD','✅ 審閱','✅ 報名','🔧 維護'],
            ['社團資訊','✅ 管理','✅ 編輯','✅ 審閱','👁️ 瀏覽','🔧 維護'],
            ['AI 預審系統','✅ 管理','✅ 使用','✅ 審閱','👁️ 瀏覽','🔧 維護'],
            ['報修管理','✅ 指派','✅ 報修','👁️ 瀏覽','✅ 報修','✅ 處理'],
            ['申訴記錄','✅ 處理','✅ 申訴','✅ 審閱','✅ 申訴','🔧 維護'],
            ['信用積分','✅ 管理','👁️ 瀏覽','👁️ 瀏覽','👁️ 瀏覽','🔧 維護'],
            ['數位證書','✅ 簽發','✅ 申請','✅ 簽章','👁️ 瀏覽','🔧 維護'],
            ['統計報表','✅ 全域','✅ 社團','✅ 指導','👁️ 個人','✅ 系統'],
            ['系統管理 / 2FA','✅ 管理','❌','❌','❌','✅ 管理'],
            ['用戶管理','✅ CRUD','❌','❌','❌','✅ CRUD'],
            ['E-Portfolio','✅ 瀏覽','✅ 管理','✅ 審閱','✅ 編輯','🔧 維護'],
            ['時光膠囊','✅ 管理','✅ 建立','👁️ 瀏覽','👁️ 瀏覽','🔧 維護'],
            ['RAG 法規查詢','✅ 管理','✅ 查詢','✅ 查詢','✅ 查詢','🔧 維護'],
          ] as $row)
          <tr class="border-t border-gray-100 hover:bg-gray-50">
            <td class="p-3 font-medium text-fju-blue">{{ $row[0] }}</td>
            @for($i=1;$i<=5;$i++)<td class="p-3 text-center text-xs">{{ $row[$i] }}</td>@endfor
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- ============ Tab 7: Features ============ --}}
  <div id="tab-features" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-list-check mr-2 text-fju-yellow"></i>完整功能清單</h3>
      <div class="grid md:grid-cols-2 gap-4">
        @foreach([
          ['場地預約系統','✅',['三階段資源調度（志願序→協商→審核）','L1/L2/L3 優先權自動配對','AI 意圖過濾 (Workers AI Llama-3)','衝突偵測與自動建立衝突記錄','即時協商對話（WebSocket Chat）','郵件/LINE/SMS 多通路通知','AI 協商建議 (GPT-4)','雙方確認按鈕 → 更動行事曆','3分鐘/6分鐘超時規則 (Redis)','Gatekeeping 依賴阻斷機制','PDF 申請單自動產出','TOTP QR Code 簽到']],
          ['社團管理','✅',['社團 CRUD（新增/查詢/刪除）','8大分類篩選','關鍵字搜尋','成員統計','社團/學會分類','102社團真實數據']],
          ['活動牆','✅',['活動 CRUD','狀態篩選（全部/已核准/待審核）','活動報名/取消報名','AI 預審 + 企劃書生成']],
          ['設備借用','✅',['設備 CRUD','借用/歸還流程','LINE/SMS 到期提醒','狀態篩選（可借用/已借出/維修中）','關鍵字搜尋','信用連動扣分']],
          ['行事曆','✅',['行事曆事件 CRUD','EvoCalendar 視覺化','6種事件類型','右側滑入面板 (GSAP)','協商按鈕整合']],
          ['AI 智慧模組','✅',['AI 預審 (Dify RAG 法規比對)','AI 企劃書自動生成 (Workflow)','AI 申訴案件摘要 (WebSocket)','AI 衝突協商建議 (GPT-4)','信心指數評估','Pinecone 向量 DB']],
          ['衝突協調中心','✅',['衝突清單與狀態管理','即時對話功能 (Socket.io)','郵件 / LINE / SMS 通知','雙方確認按鈕機制','AI 建議方案 (3選項)','3/6分鐘計時器 + 紅光動畫','自動更新預約狀態','信用扣分連動']],
          ['報修管理','✅',['報修單 CRUD','追蹤碼自動產生','狀態管理 (待處理→處理中→完成)']],
          ['申訴記錄','✅',['申訴 CRUD','4種申訴類型','AI 摘要生成','情緒分析 + 緊急程度']],
          ['信用積分','✅',['積分查詢 API','扣分機制 (觀察者模式)','低於60分強制登出 (INVALIDATE_JWT)','積分紀錄 (credit_logs)','LINE + SMS 通知']],
          ['通知系統','✅',['多管道通知（系統/郵件/LINE/SMS）','已讀/未讀管理','衝突自動推播','防抵賴 Unique Token']],
          ['數位證書','✅',['自動生成證書碼','數位簽章 (SHA256)','驗證連結','幹部證書自動化']],
          ['多語言 i18n','✅',['繁體中文 (預設)','簡體中文','英文','日文','韓文','整個系統全域切換']],
          ['校園互動地圖','✅',['Leaflet.js 互動地圖','20+ 建築物標示','無障礙設施圖層','生活/交通圖層切換','GeoJSON 分區']],
          ['Dashboard 儀表板','✅',['Admin 6圖表 (折線/圓餅/Gauge/堆疊/雷達/漏斗)','Officer 5圖表 (簽到/留存/滿意度/水滴/傳承)','Professor 4圖表 (雷達/熱力/Spider/紅黃綠燈)','Student 4圖表 (履歷/推薦/Steppers/評價)','IT 4圖表 (熱力/延遲/WAF/R2)']],
          ['安全防護','✅',['Google OAuth (hd 檢查)','2FA (TOTP + SMS + PHPGangsta)','JWT (HttpOnly Cookie)','信用積分觀察者模式','Cloudflare WAF','Turnstile 驗證','R2 + GPS 浮水印','PWA 離線支援']],
          ['E-Portfolio','✅',['職能記錄與標籤','PDF 產出 (outbox_table 異步)','職能 Steppers']],
          ['時光膠囊','✅',['R2 文件封裝','跨屆傳承移交','密封/開啟狀態管理']],
          ['用戶管理','✅',['CRUD (姓名/學號/Outlook/手機/職位)','5角色權限管理','信用積分管理']],
          ['RAG 法規查詢','✅',['FJU 5大官方網址索引','法規全文檢索','FAQ 知識庫','智慧問答 (Dify)']],
        ] as $feature)
        <div class="rounded-fju p-4 border border-gray-100 card-hover">
          <div class="flex items-center gap-2 mb-2"><span class="text-sm">{{ $feature[1] }}</span><span class="font-bold text-fju-blue text-sm">{{ $feature[0] }}</span></div>
          <ul class="space-y-1">@foreach($feature[2] as $item)<li class="text-xs text-gray-500 flex items-center gap-1"><span class="w-1 h-1 rounded-full bg-fju-green"></span>{{ $item }}</li>@endforeach</ul>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- ============ Tab 8: Rubric ============ --}}
  <div id="tab-rubric" class="tab-content hidden">
    <div class="bg-white rounded-fju-lg p-6 shadow-sm border border-gray-100">
      <h3 class="font-bold text-fju-blue mb-4"><i class="fas fa-award mr-2 text-fju-yellow"></i>評分標準對照表</h3>
      <div class="space-y-4">
        @foreach([
          ['創新性 Innovation','20%','fas fa-lightbulb','bg-fju-yellow/10 border-fju-yellow/20',['三階段資源調度系統（志願序→AI協商→官方審核）','AI 預審與企劃書自動生成器（Dify RAG 整合）','衝突協調中心：即時對話 + 郵件通知 + 雙方確認','數位時光膠囊（R2 跨屆傳承）','無障礙校園互動地圖 (Leaflet + SVG)','Gatekeeping 預約阻斷機制','Workers AI (Llama-3) 意圖過濾','觀察者模式信用監控']],
          ['實用性 Practicality','50%','fas fa-tools','bg-fju-blue/5 border-fju-blue/10',['完整場地預約流程（衝突偵測→協商→核准→PDF簽發）','設備借用管理（借還、LINE/SMS 提醒、逾期扣分）','活動牆（CRUD + 報名 + AI 預審）','社團管理（102社團 + 8分類 + 搜尋）','行事曆管理（EvoCalendar + 協商整合）','報修管理（追蹤碼 + 狀態流轉）','申訴系統（AI 摘要 + 情緒分析）','信用積分（扣分 + <60 強制登出）','通知系統（LINE + SMS + Email + 推播）','數位證書自動化（SHA256 簽章）','用戶管理（5角色 CRUD）','RAG 法規查詢（5大官方索引）']],
          ['技術完善度 Technical','10%','fas fa-code','bg-fju-green/5 border-fju-green/10',['PHP 8.2 + Laravel 12 + MySQL 8.0 (InnoDB)','RESTful API 設計（30+ endpoints）','Eloquent ORM + Migration + Seeder','E2E 測試 (244 tests, 100% pass)','前後端分離（Blade + AJAX + Vue 3）','5角色權限矩陣','JWT + 2FA + 觀察者模式','Docker 容器化部署','悲觀鎖 + Transaction 併發安全','GuzzleHTTP + Dify API 串接']],
          ['UI/UX 友善度','10%','fas fa-palette','bg-purple-50 border-purple-200',['60-30-10 配色法則（天使白/聖母藍/梵蒂岡黃）','Tailwind CSS 響應式 Bento Grid','GSAP + ScrollTrigger 動畫','FontAwesome 6 圖標系統','Leaflet.js 互動地圖 (4圖層)','Chart.js 數據視覺化','5角色即時切換 Dashboard','柴犬吉祥物 AI FAB','Glassmorphism 毛玻璃效果','EvoCalendar 行事曆','五國語言 i18n 切換']],
          ['內容豐富度 Content','10%','fas fa-book','bg-orange-50 border-orange-200',['102 個真實社團數據','10 個校園場地','8 項活動資料','7 位使用者 (5角色)','10 項行事曆事件','20+ 校園建築標示','5 國語言 i18n','SDGs 對應（SDG4, SDG5, SDG10, SDG11, SDG16, SDG17）','5大 FJU 官方法規索引','完整 API 文件 (30+ endpoints)','5 種核心流程圖 (SVG)']],
        ] as $rubric)
        <div class="rounded-fju-lg p-5 border {{ $rubric[3] }}">
          <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-fju bg-fju-blue flex items-center justify-center text-white"><i class="{{ $rubric[2] }}"></i></div>
            <div><div class="font-bold text-fju-blue">{{ $rubric[0] }}</div><div class="text-fju-yellow font-bold">權重：{{ $rubric[1] }}</div></div>
          </div>
          <div class="grid md:grid-cols-2 gap-1">@foreach($rubric[4] as $item)<div class="text-xs text-gray-600 flex items-center gap-1.5"><i class="fas fa-check text-fju-green text-[10px]"></i>{{ $item }}</div>@endforeach</div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<script>
function showTab(tab){
  document.querySelectorAll('.tab-content').forEach(t=>t.classList.add('hidden'));
  document.getElementById('tab-'+tab)?.classList.remove('hidden');
  document.querySelectorAll('.tab-btn').forEach(b=>{b.classList.remove('bg-fju-blue','text-white');b.classList.add('bg-gray-100','text-gray-500')});
  document.querySelector(`.tab-btn[data-tab="${tab}"]`)?.classList.add('bg-fju-blue','text-white');
  document.querySelector(`.tab-btn[data-tab="${tab}"]`)?.classList.remove('bg-gray-100','text-gray-500');
}
</script>
@endsection
