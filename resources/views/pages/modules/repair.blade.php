@extends('layouts.shell')
@section('title', '報修管理')
@php $activePage = 'repair'; @endphp
@section('content')
<div class="space-y-6">
  <div class="flex items-center justify-between flex-wrap gap-2">
    <h2 class="font-bold text-fju-blue text-lg"><i class="fas fa-wrench mr-2 text-fju-yellow"></i>報修管理</h2>
    <div class="flex gap-2">
      <select id="rep-sort" onchange="renderRepairs()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="newest">最新優先</option>
        <option value="oldest">最舊優先</option>
        <option value="status">依狀態</option>
      </select>
      <select id="rep-filter" onchange="renderRepairs()" class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs">
        <option value="all">全部狀態</option>
        <option value="pending">待處理</option>
        <option value="processing">處理中</option>
        <option value="completed">已完成</option>
      </select>
      <input id="rep-search" oninput="renderRepairs()" placeholder="搜尋..." class="px-3 py-1.5 rounded-fju border border-gray-200 text-xs w-32">
      <button onclick="document.getElementById('rep-modal').classList.remove('hidden')" class="btn-yellow px-4 py-2 text-sm"><i class="fas fa-plus mr-1"></i>新增報修</button>
    </div>
  </div>
  <div id="repair-list"><div class="p-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin"></i></div></div>
  <div id="rep-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-fju-lg w-full max-w-lg mx-4 shadow-xl">
      <div class="p-5 border-b border-gray-100">
        <h3 class="font-bold text-fju-blue text-lg">新增報修</h3>
      </div>
      <div class="max-h-[70vh] overflow-y-auto">
        <div class="px-5 pt-4 pb-2">
          <p class="text-sm font-semibold text-fju-blue">輔仁大學課外活動指導組-場地/器材報修單</p>
          <p class="text-xs text-gray-600 mt-1">為了讓大家的課外活動空間與器材維持良好使用狀態，並讓承辦人員方便管理器材及場地損壞情況，請大家依照以下原則填寫報修表單。</p>
          <div class="my-3 border-t border-dashed border-gray-200"></div>
          <div class="text-xs text-gray-600 space-y-2">
            <p class="font-semibold text-gray-700">◀原則說明▶</p>
            <p>本表單僅受理『 課指組 』所購之器材，若屬於自行購買，課指組無法提供維修。</p>
            <div class="border-t border-dashed border-gray-200"></div>
            <p class="font-semibold text-gray-700">◀表單說明▶</p>
            <p>一、此報修單受理空間僅包含：社辦空間（中美堂、仁愛學苑、焯炤館）；公共空間（進修部地下教室、文開樓地下室、真善美聖廣場、潛水艇的天空）</p>
            <p>二、此報修單受理範圍如下：建築（壁癌、天花板、木作、窗戶）；電器（插座、電燈、冷氣、音響、投影機）；水類（飲水機、廁所、漏水、積水）</p>
            <p>三、將需維修的物品、器材或場地詳細撰寫清楚，以提升維修速度。</p>
            <p>四、多項物品或場地損壞，請一個項目填寫一次表單。</p>
            <p>五、課指組收到表單後，會盡快安排時間查看損壞項目，並於狀態欄即時更新。</p>
            <p>六、報修問題經查證為同學不正當使用造成物品損壞，所需修繕費用須由學生支付。</p>
            <div class="border-t border-dashed border-gray-200"></div>
            <p class="font-semibold text-gray-700">◀報修流程▶</p>
            <p>一、填寫基本資料（提案人／項目問題回報）</p>
            <p>二、故障檢測與確認</p>
            <p>三、費用確認</p>
            <p>四、派工維修</p>
            <p>五、回報紀錄</p>
            <p>六、後續驗收</p>
            <div class="border-t border-dashed border-gray-200"></div>
            <p class="font-semibold text-gray-700">◀聯絡方式▶</p>
            <p>負責單位：輔仁大學-學生事務處-課外活動指導組</p>
            <p>聯絡電話：02-2905-2233 或 02-2905-2279</p>
            <p>附註：若有緊急修繕／特殊需求／報修逾五日未處理等問題，可聯絡各家輔導助教。</p>
          </div>
        </div>
        <form id="rep-form" class="space-y-3 px-5 pb-5">
        <div class="space-y-2">
          <p class="text-xs text-gray-400">基本資料（* 必填）</p>
          <label class="block text-xs text-gray-500">電子郵件*</label>
          <input id="rp-email" name="email" type="email" required placeholder="name@example.com" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <label class="flex items-start gap-2 text-xs text-gray-500">
            <input id="rp-consent" name="consent" type="checkbox" required class="mt-1">
            <span>我同意本表單蒐集之個人資料，僅限於輔大課指組使用，非經當事人同意，不轉作其他用途，並遵循本校資料保存與安全控管辦理。*</span>
          </label>
          <label class="block text-xs text-gray-500">姓名*</label>
          <input id="rp-name" name="reporter_name" required placeholder="請輸入姓名" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <label class="block text-xs text-gray-500">學號／職員編號*</label>
          <input id="rp-identifier" name="reporter_identifier" required placeholder="例如：410123456" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <label class="block text-xs text-gray-500">聯絡電話*</label>
          <input id="rp-phone" name="phone" required placeholder="例如：0912-345-678" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <label class="block text-xs text-gray-500">單位（社團／學會）*</label>
          <input id="rp-unit" name="unit" required placeholder="請輸入單位" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
        </div>
        <div class="space-y-2">
          <p class="text-xs text-gray-400">報修資訊（* 必填）</p>
          <div class="space-y-1 text-xs text-gray-600">
            <p class="text-gray-500">報修類別（單選）*</p>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="建築（壁癌）" required>建築（壁癌）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="建築（木作）" required>建築（木作）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="建築（天花板）" required>建築（天花板）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="建築（窗戶）" required>建築（窗戶）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="電器（插座）" required>電器（插座）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="電器（電燈）" required>電器（電燈）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="電器（冷氣）" required>電器（冷氣）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="電器（音響／投影機）" required>電器（音響／投影機）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="水類（飲水機）" required>水類（飲水機）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="水類（給水／排水／積水／漏水）" required>水類（給水／排水／積水／漏水）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="水類（馬桶／洗手台／水龍頭）" required>水類（馬桶／洗手台／水龍頭）</label>
            <label class="flex items-center gap-2"><input type="radio" name="category" value="其他" required>其他（自行填寫）</label>
          </div>
          <label class="block text-xs text-gray-500">其他類別（當選其他時必填）</label>
          <input id="rp-category-other" name="category_other" placeholder="例如：門禁系統" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm hidden">
          <label class="block text-xs text-gray-500">維修項目*（格式：地點-教室名稱-維修項目名稱）</label>
          <input id="rp-repair-item" name="repair_item" required placeholder="例如：仁愛學苑-101教室-窗型冷氣壞" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm">
          <label class="block text-xs text-gray-500">損壞情況說明*(盡可能詳細說明)</label>
          <textarea id="rp-damage" name="damage_description" required placeholder="請描述故障現象與發生時間" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
          <label class="block text-xs text-gray-500">維修項目所在位置*(盡可能詳細說明)</label>
          <textarea id="rp-location" name="location" required placeholder="例如：仁愛學苑 1F 走廊靠右側" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
          <div class="space-y-1">
            <label class="block text-xs text-gray-500">圖片上傳*（PDF 或圖片，1-5 檔，單檔 10MB 內）</label>
            <label class="block text-xs text-gray-500">備註：盡可能清楚拍攝每個角度，包含周邊環境</label>
            <div class="flex items-center gap-2">
              <label for="rp-attachments" class="inline-flex items-center gap-2 px-3 py-2 rounded-fju border border-fju-blue/20 bg-fju-blue/5 text-fju-blue text-xs hover:bg-fju-blue/10 cursor-pointer">
                <i class="fas fa-upload"></i>
                選擇檔案
              </label>
              <span id="rp-attachments-name" class="text-xs text-gray-400">尚未選擇檔案</span>
            </div>
            <input id="rp-attachments" name="attachments[]" type="file" multiple accept=".pdf,image/*" required class="sr-only">
          </div>
        </div>
        <div class="space-y-2">
          <label class="block text-xs text-gray-500">其他問題（選填）</label>
          <textarea id="rp-notes" name="other_notes" placeholder="可自由填寫" class="w-full px-4 py-2 rounded-fju border border-gray-200 text-sm"></textarea>
        </div>
          <div class="flex gap-2 mt-4">
            <button type="submit" class="flex-1 btn-yellow py-2">送出</button>
            <button type="button" onclick="document.getElementById('rep-modal').classList.add('hidden')" class="flex-1 py-2 rounded-fju border border-gray-200 text-sm text-gray-500">取消</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
let allRepairs=[];
function loadRepairs(){fetch('/api/repairs').then(r=>r.json()).then(res=>{allRepairs=res.data;renderRepairs()})}
function renderRepairs(){
  let data=[...allRepairs];
  const search=(document.getElementById('rep-search')?.value||'').toLowerCase();
  const filter=document.getElementById('rep-filter')?.value||'all';
  const sort=document.getElementById('rep-sort')?.value||'newest';
  if(search) data=data.filter(r=>(r.repair_item||r.target||'').toLowerCase().includes(search)||(r.damage_description||r.description||'').toLowerCase().includes(search)||(r.code||'').toLowerCase().includes(search));
  if(filter!=='all') data=data.filter(r=>r.status===filter);
  if(sort==='newest') data.sort((a,b)=>b.id-a.id);
  else if(sort==='oldest') data.sort((a,b)=>a.id-b.id);
  else if(sort==='status'){const o={pending:0,processing:1,completed:2};data.sort((a,b)=>(o[a.status]||0)-(o[b.status]||0))}
  document.getElementById('repair-list').innerHTML='<div class="bg-white rounded-fju-lg shadow-sm border border-gray-100 overflow-hidden"><table class="w-full text-sm"><thead class="bg-gray-50"><tr class="text-left text-xs text-gray-400"><th class="p-4">編號</th><th class="p-4">對象</th><th class="p-4">描述</th><th class="p-4">狀態</th><th class="p-4">指派</th><th class="p-4">操作</th></tr></thead><tbody>'+data.map(r=>'<tr class="border-t hover:bg-gray-50"><td class="p-4 text-xs text-gray-400">'+r.code+'</td><td class="p-4 font-medium text-fju-blue">'+(r.repair_item||r.target)+'</td><td class="p-4 text-xs text-gray-500">'+(r.damage_description||r.description)+'</td><td class="p-4"><span class="px-2 py-1 rounded-fju text-xs '+(r.status==='completed'?'bg-fju-green/10 text-fju-green':r.status==='processing'?'bg-fju-yellow/20 text-fju-yellow':'bg-gray-100 text-gray-500')+'">'+r.status+'</span></td><td class="p-4 text-xs">'+(r.assignee||'未指派')+'</td><td class="p-4"><button onclick="delRepair('+r.id+')" class="text-fju-red text-xs"><i class="fas fa-trash"></i></button></td></tr>').join('')+'</tbody></table></div>';
}
loadRepairs();
function toggleCategoryOther(){
  const otherInput=document.getElementById('rp-category-other');
  const checked=document.querySelector('input[name="category"]:checked');
  const show=checked && checked.value==='其他';
  otherInput.classList.toggle('hidden', !show);
  otherInput.required=!!show;
}
document.querySelectorAll('input[name="category"]').forEach(el=>el.addEventListener('change', toggleCategoryOther));
document.getElementById('rp-attachments').addEventListener('change', (e)=>{
  const files=[...e.target.files].map(f=>f.name);
  const label=document.getElementById('rp-attachments-name');
  label.textContent=files.length?files.join(', '):'尚未選擇檔案';
});
function addRepair(){
  const form=document.getElementById('rep-form');
  const data=new FormData(form);
  const files=document.getElementById('rp-attachments').files;
  if(!form.reportValidity()) return;
  if(!document.getElementById('rp-consent').checked){alert('請勾選同意個資蒐集聲明');return;}
  if(files.length<1||files.length>5){alert('請上傳 1-5 個檔案');return;}
  for(const file of files){
    if(file.size>10*1024*1024){alert('單檔大小不得超過 10MB');return;}
  }
  fetch('/api/repairs',{method:'POST',headers:{'Accept':'application/json'},body:data}).then(async r=>{
    const res=await r.json();
    if(!r.ok){
      const msg=res?.message||Object.values(res?.errors||{})[0]?.[0]||'送出失敗';
      alert(msg);
      return;
    }
    alert('報修單 '+res.tracking_code+' 已建立');
    form.reset();
    toggleCategoryOther();
    document.getElementById('rep-modal').classList.add('hidden');
    loadRepairs();
  });
}
const repForm=document.getElementById('rep-form');
repForm.addEventListener('submit', (e)=>{
  e.preventDefault();
  addRepair();
});
function delRepair(id){if(!confirm('確定刪除？'))return;fetch('/api/repairs/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}).then(()=>loadRepairs())}
</script>
@endsection
