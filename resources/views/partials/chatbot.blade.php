<div class="chatbot-fab" onclick="toggleChatbot()" title="輔寶 AI 助理">🐕</div>
<div id="chatbot-panel" class="chatbot-panel">
  <div class="chatbot-header">
    <div class="w-8 h-8 rounded-full bg-fju-yellow flex items-center justify-center text-lg">🐕</div>
    <div><div class="font-bold text-sm">輔寶 AI 助理</div><div class="text-white/50 text-[10px]">FJU Smart Hub Navigator</div></div>
    <button onclick="toggleChatbot()" class="ml-auto text-white/50 hover:text-white"><i class="fas fa-times"></i></button>
  </div>
  <div class="chatbot-messages" id="chatbot-messages">
    <div class="flex gap-2 mb-3"><div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div><div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-600 shadow-sm max-w-[85%]">汪！你好～我是輔寶 🐾<br><br>今天想要問輔寶什麼事呢？我可以幫你：<br>📍 查詢場地資訊與預約<br>📅 活動申請與審核流程<br>📋 查詢法規與常見問題 FAQ<br>🤖 AI 衝突協商建議<br>🗺️ 校園無障礙導覽<br><br>試試問我：「場地衝突怎麼辦？」</div></div>
  </div>
  <div class="chatbot-quick-actions px-3 pb-2 flex gap-1 flex-wrap">
    <button onclick="askChatbot('場地衝突怎麼處理？')" class="px-2 py-1 rounded-full bg-fju-blue/10 text-fju-blue text-[10px] hover:bg-fju-blue/20 transition-colors">🏫 場地衝突</button>
    <button onclick="askChatbot('如何申請活動？')" class="px-2 py-1 rounded-full bg-fju-yellow/20 text-fju-yellow text-[10px] hover:bg-fju-yellow/30 transition-colors">📝 活動申請</button>
    <button onclick="askChatbot('社團評鑑相關規定？')" class="px-2 py-1 rounded-full bg-fju-green/10 text-fju-green text-[10px] hover:bg-fju-green/20 transition-colors">📋 社團評鑑</button>
    <button onclick="askChatbot('器材借用流程？')" class="px-2 py-1 rounded-full bg-purple-100 text-purple-600 text-[10px] hover:bg-purple-200 transition-colors">🔧 器材借用</button>
  </div>
  <div class="chatbot-input-area">
    <input type="text" id="chatbot-input" placeholder="輸入你的問題..." class="flex-1 px-3 py-2 rounded-fju bg-gray-50 border border-gray-200 text-xs outline-none focus:border-fju-blue" onkeypress="if(event.key==='Enter')sendChatMessage()" />
    <button onclick="sendChatMessage()" class="w-8 h-8 rounded-fju btn-yellow flex items-center justify-center"><i class="fas fa-paper-plane text-xs"></i></button>
  </div>
</div>
<script>
const faqKB = {
  '場地': [
    {q:'場地衝突怎麼處理？',a:'當您的預約與他人衝突時，系統會自動偵測並顯示紅字提示。您可以選擇：\n1️⃣ 若在場協大會時間內，可走場協大會流程\n2️⃣ 若超過場協時間，進入私下協調（多人即時聊天）\n3️⃣ AI 會自動提供三個替代方案（含信心度評分）\n\n協調結束後 AI 會生成對話紀錄 PDF，雙方確認後自動更新行事曆。'},
    {q:'場地預約流程是什麼？',a:'場地預約採三志願制：\n1️⃣ 選擇場地後，依志願排序填寫三個時段\n2️⃣ 系統依先搶先贏原則自動配對\n3️⃣ 若有衝突，顯示紅字提醒並進入協調\n4️⃣ 優先權：L1 大型活動/課程 > L2 校方行政 > L3 社團\n5️⃣ 核准後自動發送 Outlook 通知'},
    {q:'取消預約會扣分嗎？',a:'依據撤銷階梯算法：\n✅ 活動前 7 天取消：不扣分\n⚠️ 活動前 3 天取消：扣 5 分\n❌ 活動前 24 小時取消：扣 20 分\n🚫 活動前 2 小時取消：視同惡意放鳥，重扣積分並進入停權審核'},
  ],
  '活動': [
    {q:'如何申請活動？',a:'活動申請流程：\n1️⃣ 前往「活動申請」頁面填寫活動資訊\n2️⃣ 上傳企劃書 PDF（可用 AI 企劃生成器自動產出）\n3️⃣ 系統自動進行 AI 預審（比對 8 項法規）\n4️⃣ 預審通過後送交課指組審核\n5️⃣ 核准後自動通知並顯示在行事曆上'},
    {q:'AI 預審檢查什麼？',a:'AI 預審引擎三層防護：\n🔍 第一層：規則引擎自動過濾（人數、預算）\n📚 第二層：RAG 語義比對 8 項校內法規\n🤖 第三層：GPT-4 深度分析\n\n審查結果包含風險等級、信心度評分、違規項目和修改建議。平均 1.3 秒完成全部檢查。'},
  ],
  '社團': [
    {q:'社團評鑑相關規定？',a:'社團評鑑重點：\n📋 評鑑項目：社團運作、活動企劃、財務管理、社員參與度\n📅 評鑑時程：每學期末進行\n⭐ 評鑑等級：特優、優等、甲等、乙等\n💰 優秀社團可獲額外補助經費\n📝 需繳交：社團成果報告、財務報表、活動照片'},
    {q:'如何成立新社團？',a:'新社團成立流程：\n1️⃣ 徵集 30 人以上連署\n2️⃣ 撰寫社團章程\n3️⃣ 向課指組提交申請表\n4️⃣ 經學生事務委員會審議通過\n5️⃣ 核准後正式成立'},
  ],
  '器材': [
    {q:'器材借用流程？',a:'器材借用流程：\n1️⃣ 前往「設備借用」頁面選擇器材\n2️⃣ 需先確認是否持有有效器材證\n3️⃣ 填寫借用人資訊與預計歸還日期\n4️⃣ 系統自動檢查器材證是否為本人\n5️⃣ 逾期歸還將自動扣分並發送通知\n\n⚠️ 器材證過期需重新申請！'},
  ],
  '衝突': [
    {q:'衝突協商怎麼進行？',a:'當您遇到場地衝突需要協商時：\n1️⃣ 我（輔寶 AI）會先提供 3 個替代方案\n2️⃣ 如需進一步討論，可開啟多方私下協商（即時聊天）\n3️⃣ 協商結束後，AI 自動整理對話紀錄 PDF\n4️⃣ 雙方確認後，自動更新行事曆\n5️⃣ 對話紀錄和變更資訊會通知課指組\n\n💡 每個爭議單一社團僅有 4 小時忙碌豁免權'},
    {q:'忙碌按鈕可以一直按嗎？',a:'不行喔！忙碌按鈕有嚴格規則：\n📌 每個預約爭議，單一社團僅有 4 小時忙碌豁免權\n📌 系統可連動學校課表驗證\n📌 若幹部無課卻點忙碌，AI 會標註「疑似誠信異常」\n📌 異常行為會自動調低該社團的仲裁權重'},
  ],
};

function toggleChatbot(){document.getElementById('chatbot-panel').classList.toggle('active')}

function askChatbot(q){
  document.getElementById('chatbot-input').value = q;
  sendChatMessage();
}

function sendChatMessage(){
  var input=document.getElementById('chatbot-input'),msg=input.value.trim();
  if(!msg)return;
  var container=document.getElementById('chatbot-messages');
  // User bubble
  container.innerHTML+='<div class="flex gap-2 mb-3 justify-end"><div class="bg-fju-blue text-white rounded-fju rounded-tr-none p-3 text-xs max-w-[85%]">'+msg+'</div></div>';
  input.value='';
  container.scrollTop=container.scrollHeight;
  // Show typing indicator
  container.innerHTML+='<div id="typing-indicator" class="flex gap-2 mb-3"><div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div><div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-400 shadow-sm"><i class="fas fa-spinner fa-spin mr-1"></i>輔寶思考中...</div></div>';
  container.scrollTop=container.scrollHeight;
  setTimeout(function(){
    var indicator=document.getElementById('typing-indicator');
    if(indicator) indicator.remove();
    var reply = matchFAQ(msg);
    container.innerHTML+='<div class="flex gap-2 mb-3"><div class="w-7 h-7 rounded-full bg-fju-yellow flex items-center justify-center shrink-0 text-sm">🐕</div><div class="bg-white rounded-fju rounded-tl-none p-3 text-xs text-gray-600 shadow-sm max-w-[85%]" style="white-space:pre-line">'+reply+'</div></div>';
    container.scrollTop=container.scrollHeight;
  },1000);
}

function matchFAQ(query){
  var q = query.toLowerCase();
  var bestMatch = null, bestScore = 0;
  for(var cat in faqKB){
    faqKB[cat].forEach(function(item){
      var score = 0;
      var keywords = item.q.toLowerCase().split(/[\s？！，。、]/);
      keywords.forEach(function(kw){
        if(kw.length > 1 && q.includes(kw)) score += 2;
      });
      // Check category match
      if(q.includes(cat.toLowerCase())) score += 3;
      if(score > bestScore){ bestScore = score; bestMatch = item; }
    });
  }
  // Also check for specific keywords
  if(q.includes('衝突') || q.includes('協商') || q.includes('協調')){
    var conflictFaqs = faqKB['衝突'] || faqKB['場地'];
    if(conflictFaqs && conflictFaqs.length > 0){
      var matched = conflictFaqs.find(function(f){ return f.q.includes('衝突') || f.q.includes('協商'); });
      if(matched) return '🐾 ' + matched.a + '\n\n💡 需要我幫你開啟協商嗎？可以前往「場地預約」頁面查看詳情。';
    }
  }
  if(q.includes('預約') || q.includes('場地')){
    var venueFaqs = faqKB['場地'];
    if(venueFaqs) {
      var matched = venueFaqs.find(function(f){ return f.q.includes('預約') || f.q.includes('流程'); });
      if(matched) return '🐾 ' + matched.a;
    }
  }
  if(q.includes('取消') || q.includes('撤銷') || q.includes('扣分')){
    var cancelFaq = (faqKB['場地']||[]).find(function(f){ return f.q.includes('取消'); });
    if(cancelFaq) return '🐾 ' + cancelFaq.a;
  }
  if(q.includes('活動') || q.includes('申請')){
    var actFaqs = faqKB['活動'];
    if(actFaqs){
      var matched = actFaqs.find(function(f){ return f.q.includes('申請'); });
      if(matched) return '🐾 ' + matched.a;
    }
  }
  if(q.includes('評鑑') || q.includes('社團')){
    var clubFaqs = faqKB['社團'];
    if(clubFaqs){
      var matched = clubFaqs.find(function(f){ return f.q.includes('評鑑'); });
      if(matched) return '🐾 ' + matched.a;
    }
  }
  if(q.includes('器材') || q.includes('借用') || q.includes('設備')){
    var eqFaqs = faqKB['器材'];
    if(eqFaqs) return '🐾 ' + eqFaqs[0].a;
  }
  if(q.includes('預審') || q.includes('AI')){
    var aiFaqs = faqKB['活動'];
    if(aiFaqs){
      var matched = aiFaqs.find(function(f){ return f.q.includes('預審'); });
      if(matched) return '🐾 ' + matched.a;
    }
  }
  if(q.includes('忙碌') || q.includes('拖延')){
    var busyFaq = (faqKB['衝突']||[]).find(function(f){ return f.q.includes('忙碌'); });
    if(busyFaq) return '🐾 ' + busyFaq.a;
  }
  if(bestMatch && bestScore >= 3) return '🐾 ' + bestMatch.a;
  // Default fallback responses with helpful links
  var defaults = [
    '汪！讓我幫你找找看～ 📝\n\n您可以試試以下問題：\n• 場地衝突怎麼處理？\n• 如何申請活動？\n• 社團評鑑規定\n• 器材借用流程\n• 取消預約扣分規則',
    '根據校園法規，場地需要提前 3 天申請喔 🏫\n要我幫你查詢更多資訊嗎？',
    '你可以到「場地預約」頁面進行線上申請，AI 會自動檢查文件是否完整 ✅',
    '校園無障礙設施包括坡道、電梯和無障礙廁所，分佈在各大樓 ♿',
  ];
  return defaults[Math.floor(Math.random()*defaults.length)];
}
</script>
