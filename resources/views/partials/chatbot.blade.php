<div class="chatbot-fab" onclick="toggleChatbot()" title="AI 導覽助理 - 輔寶">🐕</div>
<div id="chatbot-panel" class="chatbot-panel">
  <div class="chatbot-header">
    <div class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-lg">🐕</div>
    <div><div class="font-bold text-sm">輔寶 AI 助理</div><div class="text-white/50 text-[10px]">FJU Smart Hub Navigator</div></div>
    <button onclick="toggleChatbot()" class="ml-auto text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
  </div>
  <div class="chatbot-messages" id="chatbot-messages">
    <div class="flex gap-2 mb-3"><div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div><div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-600 shadow-sm max-w-[85%]">汪！你好～我是輔寶 🐾<br><br>今天想要問輔寶甚麼事呢？我可以幫你：<br>📍 查詢場地資訊<br>📅 預約場地/設備<br>📋 查詢法規與表單<br>🗺️ 校園無障礙導覽</div></div>
  </div>
  <div class="chatbot-input-area">
    <input type="text" id="chatbot-input" placeholder="輸入你的問題..." class="flex-1 px-3 py-2 rounded-fju bg-gray-50 border border-gray-200 text-xs outline-none focus:border-fju-blue" onkeypress="if(event.key==='Enter')sendChatMessage()" />
    <button onclick="sendChatMessage()" class="w-8 h-8 rounded-fju btn-yellow flex items-center justify-center"><i class="fas fa-paper-plane text-xs"></i></button>
  </div>
</div>
<script>
function toggleChatbot(){document.getElementById('chatbot-panel').classList.toggle('active')}
function sendChatMessage(){var i=document.getElementById('chatbot-input'),m=i.value.trim();if(!m)return;var c=document.getElementById('chatbot-messages');c.innerHTML+='<div class="flex gap-2 mb-3 justify-end"><div class="bg-fju-blue text-white rounded-fju rounded-tr-none p-3 text-xs max-w-[85%]">'+m+'</div></div>';i.value='';setTimeout(function(){var r=['汪！讓我幫你查查看～ 📝','好的！根據校園法規，這個場地需要提前3天申請喔 🏫','淨心堂目前可以預約，要幫你查詢空閒時段嗎？','你可以到「場地預約」頁面進行線上申請，AI 會自動幫你檢查文件是否完整 ✅','校園無障礙設施包括坡道、電梯和無障礙廁所，分布在醫學大樓、理工綜合教室等處 ♿'];c.innerHTML+='<div class="flex gap-2 mb-3"><div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div><div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-600 shadow-sm max-w-[85%]">'+r[Math.floor(Math.random()*r.length)]+'</div></div>';c.scrollTop=c.scrollHeight},800);c.scrollTop=c.scrollHeight}
</script>
