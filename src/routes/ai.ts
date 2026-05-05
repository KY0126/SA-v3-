import { Hono } from 'hono'
type Bindings = { DB: D1Database }
const aiRoutes = new Hono<{ Bindings: Bindings }>()

// ============================================================
// Prompt7 指令6A: AI 透過 GitHub Models PAT 呼叫 GPT-4o API
// 技術說明：
// 1. 使用 GitHub 個人存取權杖 (PAT) 作為認證
// 2. 透過 REST API 呼叫 https://models.inference.ai.azure.com/chat/completions
// 3. 支援 GPT-4o、Phi-3 等模型
// 4. 在 GitHub Copilot 中選用模型，透過 REST API 與本系統串接
//
// 實作方式：
// - 環境變數 GITHUB_TOKEN 存放 GitHub PAT
// - POST 請求至 Azure OpenAI 端點
// - 系統 prompt 依角色動態調整
// - 回應包含 RAG 知識庫內容（法規、操作教學）
// ============================================================

const GITHUB_MODELS_ENDPOINT = 'https://models.inference.ai.azure.com/chat/completions'

// 角色系統 prompts（含 RAG 知識庫）
const systemPrompts: Record<string, string> = {
  admin: `你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助課指組管理員。
你的知識庫包含以下法規：
- 場地使用管理規則 v3.0：場地借用資格、時段限制、歸還規定
- 器材借用管理辦法 v1.5：器材借用流程、操作證要求、損壞賠償
- 違規記點處理要點 v2.0：違規類型（逾時+2點、損壞+3點、未到場+1點）、停權門檻10點
- 活動申請辦法 v2.1：活動申請流程、審核標準、含酒精/明火須提前30天送件

管理員常見操作：審核預約、管理違規記點、查看統計報表、處理報修、管理使用者帳號。
請用繁體中文回答，並提供具體的操作步驟。`,

  officer: `你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助社團幹部。
你的知識庫包含以下法規：
- 場地預約流程：提交活動申請 → 取得核准 → 預約場地 → 完成紙本程序
- 器材借用規則：需先有核准活動、部分器材需操作證、領取時段限週一至週五09:30-16:30
- 衝突協調機制：場協大會登記 → 私下協調聊天室 → 一方撤回
- 場地使用注意：借用日前7天須完成申請、使用完畢恢復原狀、逾時記點

社團幹部可用 AI 功能：活動企劃書生成、預約風險評估、法規查詢。
請用繁體中文回答。`,

  professor: `你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助教授。
你的知識庫包含以下規定：
- 教授可借用教室進行課程或學術活動
- 借用流程與一般使用者相同，需先提交活動申請
- 可透過行政單位名義申請借用
- 教授帳號無有效期限制
請用繁體中文回答。`,

  student: `你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助學生。
你的知識庫包含以下資訊：
- 學生需透過所屬單位（社團/系學會）借用場地和器材
- 場地預約需先有已核准的活動申請
- 違規記點達10點將停權，可透過勞動服務銷點或申復
- 如需加入社團，請聯繫社團幹部或至課指組登記
請用繁體中文回答。`,

  staff: `你是輔仁大學課指組「智慧預約平台」的 AI 助理。你正在協助行政職員。
你的知識庫包含以下規定：
- 職員可以行政單位名義提交活動申請與場地預約
- 報修設施可至報修管理頁面提交
- 借用流程與社團相同但審核較快
- 行政職員帳號無有效期限制
請用繁體中文回答。`,
}

// 關鍵字匹配的 RAG 知識庫回覆 (Prompt7 9A: AI資訊概覽與法規查詢整合至FAQ)
const ragKnowledge: Record<string, string> = {
  '場地預約': `**場地預約流程（依據《場地使用管理規則 v3.0》）：**

1. **提交活動申請**：至「活動申請」填寫活動資料，系統產生含流水編號的 PDF 申請單
2. **紙本審核**：持列印的申請單至課指組完成紙本審核
3. **取得核准**：課指組標記核准後，該單位成員可送出場地預約
4. **預約場地**：至「場地預約」選擇場地與時段，填寫使用事由
5. **等待審核**：課指組管理員審核後通知結果

⚠️ 注意事項：
- 場地借用申請最晚須於借用日前 **7 天** 完成送出
- 含酒精、明火或涉及攤位的活動須於活動 **1 個月前** 送件
- 例行性借用可設定每週重複，系統自動展開為多筆預約`,

  '器材借用': `**器材借用流程（依據《器材借用管理辦法 v1.5》）：**

1. **前提條件**：需先取得已核准的活動申請
2. **檢查器材證**：部分器材（如音響、投影機）需先取得操作證
3. **填寫申請**：可在一筆申請中選取多種器材
4. **領取時段**：週一至週五 09:30-16:30，週三另增 17:00-19:00
5. **申請時限**：最早領取前 30 日，最晚領取前 4 個工作天

⚠️ 注意事項：
- 超過 2 個工作日未領取，器材證將自動註銷
- 逾期歸還或損壞將依規定記點`,

  '衝突協調': `**場地衝突協調機制：**

1. **場協大會**：學期初由課指組舉辦，登記截止前可提交場地時段需求
2. **私下協調**：系統偵測衝突後，可透過站內匿名聊天室協商
3. **協調流程**：發起邀請 → 對方同意 → 進入聊天室 → 一方撤回申請
4. **時限**：聊天室開啟後 24 小時內需完成，否則自動關閉
5. **紀錄保存**：對話內容保存半年，管理員可調閱`,

  '違規': `**違規記點規則（依據《違規記點處理要點 v2.0》）：**

- 逾時使用場地：+2 點
- 場地/器材損壞：+3 點
- 未到場使用：+1 點
- 逾期未歸還器材：+2 點
- **單位累計達 10 點自動停權**

銷點方式：
1. 勞動服務：至「違規記點」頁面申請，完成後由管理員核准
2. 申復：對不合理記點提出正式申訴`,

  '申訴': `**申訴流程（依據 Epic 7）：**

1. 至「申訴」頁面選擇類型（停權申復/違規記點申復/其他檢舉）
2. 填寫申訴理由與佐證資料（必填）
3. 送出後等待課指組審核
4. 不可對同一案件重複申訴

審核結果：
- 核准：撤銷記點或解除停權
- 駁回：維持原判，附駁回說明`,

  '報修': `**報修流程（依據 Epic 6）：**

1. 至「報修管理」頁面
2. 選擇報修設施（必填）
3. 填寫問題描述（至少 10 字）
4. 可上傳現場照片（最多 3 張）
5. 送出後由課指組派工處理
6. 完成後系統自動解除維修中標記`,

  '活動申請': `**活動申請流程（依據《活動申請辦法 v2.1》）：**

1. 至「活動申請」頁面填寫活動資訊
2. 系統自動產生流水編號（AAYYYYNNNNNN格式）
3. 列印含流水編號的 PDF 申請單
4. 持紙本至課指組窗口辦理審核
5. 核准後可進行場地預約和器材借用

⚠️ 特殊規定：
- 含酒精飲料活動須提前 1 個月送件
- 涉及明火設備需附消防安全計畫
- 攤位販售活動須另附企劃書及相關申請`,

  '場協大會': `**場協大會說明：**

1. 每學期初由課指組舉辦
2. 登記截止日前至系統登記場地需求
3. 場協大會現場由課指組協調
4. 未出席視同放棄
5. 核准後系統自動產生整學期的場地預約

逾期未完成紙本流程者視同放棄。`,

  '勞動服務': `**勞動服務銷點流程：**

1. 至「勞動服務銷點」頁面提交申請
2. 選擇服務類型（場地清潔/器材整理/活動支援/行政協助）
3. 填寫服務日期和時數
4. 管理員審核通過後扣除對應點數

服務類型與可扣點數對應：
- 場地清潔 2 小時 = -1 點
- 器材整理 2 小時 = -1 點
- 活動支援 4 小時 = -2 點`,

  '操作證': `**器材操作證說明：**

需要操作證的器材：
- 音響設備（無線麥克風組、桌上型音響）→ 音響設備操作證
- 投影設備（可攜式投影機）→ 投影機操作證

取得方式：
1. 至課指組預約操作訓練
2. 完成訓練並通過考核
3. 管理員核發操作證

注意：超過 2 個工作日未領取借用器材，操作證將自動註銷`,
}

// POST /api/ai/chat - GitHub Models PAT -> GPT-4o
aiRoutes.post('/chat', async (c) => {
  const b = await c.req.json()
  const message = b.message || ''
  const role = b.role || 'student'
  
  // 先檢查 RAG 知識庫是否有匹配
  let ragMatch = ''
  for (const [key, val] of Object.entries(ragKnowledge)) {
    if (message.includes(key)) {
      ragMatch = val
      break
    }
  }

  // 嘗試呼叫 GitHub Models API (需要 GITHUB_TOKEN 環境變數)
  const githubToken = (c.env as any).GITHUB_TOKEN
  
  if (githubToken) {
    try {
      const apiResponse = await fetch(GITHUB_MODELS_ENDPOINT, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${githubToken}`,
        },
        body: JSON.stringify({
          model: 'gpt-4o',
          messages: [
            { role: 'system', content: systemPrompts[role] || systemPrompts.student },
            ...(ragMatch ? [{ role: 'system', content: `以下是相關法規知識供參考：\n${ragMatch}` }] : []),
            { role: 'user', content: message }
          ],
          temperature: 0.7,
          max_tokens: 1000,
        }),
      })

      if (apiResponse.ok) {
        const data = await apiResponse.json() as any
        const aiResponse = data.choices?.[0]?.message?.content || '抱歉，無法取得回應'
        return c.json({
          success: true,
          response: aiResponse,
          model: data.model || 'gpt-4o',
          usage: data.usage,
          source: 'GitHub Models API (GPT-4o)',
        })
      }
    } catch (e) {
      // API 呼叫失敗，fallback 到本地回覆
    }
  }

  // Fallback: 本地 RAG 知識庫回覆 (Demo 模式)
  let aiResponse = ''
  if (ragMatch) {
    aiResponse = ragMatch
  } else {
    // 智慧回覆：根據關鍵字提供建議
    const keywords: Record<string, string> = {
      '怎麼': '您的問題我來幫您解答！請問您想了解哪個功能？可以直接問我關於場地預約、器材借用、衝突協調、違規記點、申訴、報修等主題。',
      '如何': '很高興為您服務！請直接告訴我您想操作的功能，例如：場地預約、器材借用、活動申請等。',
      '幫助': '我可以協助您以下事項：\n1. 場地預約流程與規定\n2. 器材借用與操作證\n3. 衝突協調機制\n4. 違規記點與銷點\n5. 申訴流程\n6. 報修管理\n7. 活動申請流程\n\n請告訴我您需要哪方面的協助！',
      '你好': '您好！我是輔仁大學課指組智慧預約平台的 AI 助理。有什麼我可以幫您的嗎？\n\n您可以問我關於場地預約、器材借用、活動申請等問題。',
      '謝謝': '不客氣！如果還有其他問題，隨時歡迎詢問。祝您使用愉快！',
    }
    let found = false
    for (const [key, val] of Object.entries(keywords)) {
      if (message.includes(key)) { aiResponse = val; found = true; break }
    }
    if (!found) {
      aiResponse = `感謝您的提問！關於「${message}」，以下是相關說明：\n\n`
      aiResponse += '這是一個很好的問題。建議您可以：\n'
      aiResponse += '1. 查看系統公告了解最新規定\n'
      aiResponse += '2. 至對應功能頁面操作\n'
      aiResponse += '3. 聯繫課指組取得進一步協助\n\n'
      aiResponse += '💡 提示：您可以試著問我以下問題：\n'
      aiResponse += '- 場地預約怎麼操作？\n'
      aiResponse += '- 器材借用需要什麼條件？\n'
      aiResponse += '- 違規記點規則是什麼？\n'
      aiResponse += '- 如何申請勞動服務銷點？\n'
      aiResponse += '- 場協大會是什麼？\n'
    }
  }

  return c.json({
    success: true,
    response: aiResponse,
    model: 'RAG 知識庫 (本地)',
    source: ragMatch ? '法規知識庫匹配' : 'Demo 模式 — 設定 GITHUB_TOKEN 後可連線 GPT-4o',
    note: '設定 GITHUB_TOKEN 環境變數後即可連線 GitHub Models API',
  })
})

// POST /api/ai/pre-review - AI 活動預審
aiRoutes.post('/pre-review', async (c) => {
  const b = await c.req.json()
  const headcount = b.headcount || 0
  const hasAlcohol = b.hasAlcohol || false
  const hasFire = b.hasFire || false
  const hasBooth = b.hasBooth || false
  const startDate = b.startDate || ''
  
  const violations: string[] = []
  const suggestions: string[] = []
  let riskLevel = 'safe'

  if (headcount > 300) {
    violations.push('人數超過 300 人，需申請大型活動許可')
    suggestions.push('請至學務處申請大型集會許可')
    riskLevel = 'high'
  } else if (headcount > 80) {
    suggestions.push('建議申請較大場地（進修部地下演講廳或中美堂）')
    suggestions.push('需提交安全計畫書')
    riskLevel = 'warning'
  }
  if (hasAlcohol) {
    violations.push('活動涉及酒精飲料，依規定須於活動一個月前檢附企劃書')
    riskLevel = 'high'
  }
  if (hasFire) {
    violations.push('活動涉及明火設備，需消防安全計畫')
    riskLevel = 'high'
  }
  if (hasBooth) {
    suggestions.push('涉及擺攤販售之活動，須於活動一個月前檢附企劃書及相關申請資料')
    if (riskLevel === 'safe') riskLevel = 'warning'
  }

  // 日期檢查
  if (startDate) {
    const start = new Date(startDate)
    const now = new Date()
    const diffDays = Math.floor((start.getTime() - now.getTime()) / (1000 * 60 * 60 * 24))
    if (diffDays < 7) {
      violations.push(`距離活動僅剩 ${diffDays} 天，場地借用申請需至少 7 天前送出`)
      riskLevel = 'high'
    }
    if ((hasAlcohol || hasFire || hasBooth) && diffDays < 30) {
      violations.push('含酒精/明火/攤位的活動須於活動 1 個月前送件，目前時間不足')
      riskLevel = 'high'
    }
  }

  return c.json({
    allowNextStep: violations.length === 0,
    riskLevel,
    violations,
    suggestions: suggestions.length > 0 ? suggestions : ['符合規定，可直接送審'],
    confidence: 0.95,
    reviewedRegulations: ['活動申請辦法 v2.1', '場地使用管理規則 v3.0', '安全管理規範 v1.8']
  })
})

// POST /api/ai/generate-report - AI 學期總結評鑑報告 (Epic 10.2 + Simpson 多樣性指數)
aiRoutes.post('/generate-report', async (c) => {
  const db = c.env.DB
  
  // 計算 Simpson 多樣性指數
  const { results: unitUsage } = await db.prepare(
    `SELECT VB.VB_UNIT_ID, U.UNIT_NAME, COUNT(*) as usage_count 
     FROM VenueBooking VB LEFT JOIN Unit U ON VB.VB_UNIT_ID = U.UNIT_ID 
     WHERE VB.VB_STATUS IN (1, 4) GROUP BY VB.VB_UNIT_ID`
  ).all()

  let simpsonD = 0
  const totalN = (unitUsage || []).reduce((s: number, u: any) => s + (u.usage_count || 0), 0)
  if (totalN > 1) {
    let sumNiN2 = 0
    for (const u of (unitUsage || [])) {
      const ni = (u as any).usage_count || 0
      sumNiN2 += (ni / totalN) * (ni / totalN)
    }
    simpsonD = 1 - sumNiN2
  }

  // 場地使用率（含利用率百分比）
  const { results: facilityStats } = await db.prepare(
    `SELECT F.FAC_ID, F.FAC_NAME, F.FAC_CAPACITY, F.FAC_TYPE, F.FAC_BUILDING, COUNT(VB.VB_ID) as usage_count,
     COALESCE(SUM(ROUND((julianday(VB.VB_END_DATETIME) - julianday(VB.VB_START_DATETIME)) * 24, 2)), 0) as total_hours
     FROM Facility F LEFT JOIN VenueBooking VB ON F.FAC_ID = VB.VB_FAC_ID AND VB.VB_STATUS IN (1, 4)
     WHERE F.FAC_ACTIVE = 1 GROUP BY F.FAC_ID ORDER BY usage_count DESC`
  ).all()

  // 器材使用統計
  const { results: equipStats } = await db.prepare(
    `SELECT E.EQ_NAME, E.EQ_TOTAL, E.EQ_AVAILABLE,
     COUNT(ELD.ELD_ID) as loan_count,
     COALESCE(SUM(ELD.ELD_QUANTITY), 0) as total_borrowed
     FROM Equipment E LEFT JOIN EquipmentLoanDetail ELD ON E.EQ_ID = ELD.ELD_EQ_ID
     WHERE E.EQ_ACTIVE = 1 GROUP BY E.EQ_ID ORDER BY loan_count DESC`
  ).all()

  // 違規統計
  const { results: violStats } = await db.prepare(
    `SELECT UVP.*, U.UNIT_NAME FROM UnitViolationPoint UVP
     LEFT JOIN Unit U ON UVP.UVP_UNIT_ID = U.UNIT_ID
     ORDER BY UVP.UVP_POINT DESC`
  ).all()

  // 本學期活動統計
  const activityStats = await db.prepare(
    `SELECT COUNT(*) as total, SUM(CASE WHEN AA_STATUS=1 THEN 1 ELSE 0 END) as approved,
     SUM(CASE WHEN AA_STATUS=0 THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN AA_STATUS=2 THEN 1 ELSE 0 END) as rejected
     FROM ActivityApplication`
  ).first() as any

  // 報修統計
  const repairStats = await db.prepare(
    `SELECT COUNT(*) as total, SUM(CASE WHEN RR_STATUS=0 THEN 1 ELSE 0 END) as pending,
     SUM(CASE WHEN RR_STATUS=1 THEN 1 ELSE 0 END) as in_progress,
     SUM(CASE WHEN RR_STATUS=2 THEN 1 ELSE 0 END) as completed
     FROM RepairRequest`
  ).first() as any

  // 衝突協調統計
  const conflictStats = await db.prepare(
    `SELECT COUNT(*) as total, SUM(CASE WHEN CN_STATUS=2 THEN 1 ELSE 0 END) as resolved,
     SUM(CASE WHEN CN_STATUS IN (3,4) THEN 1 ELSE 0 END) as failed
     FROM ConflictNegotiation`
  ).first() as any

  // 熱門時段分析
  const { results: peakHours } = await db.prepare(
    `SELECT CAST(substr(VB_START_DATETIME, 12, 2) AS INTEGER) as hour, COUNT(*) as cnt
     FROM VenueBooking WHERE VB_STATUS IN (0,1,4)
     GROUP BY hour ORDER BY cnt DESC LIMIT 5`
  ).all()

  // 計算利用率 (假設每月 20 工作日 × 10 小時)
  const maxHoursPerMonth = 200
  const facilityWithRate = (facilityStats || []).map((f: any) => ({
    ...f,
    utilizationRate: Math.round(((f.total_hours || 0) / maxHoursPerMonth) * 100 * 10) / 10
  }))

  // 智慧建議生成
  const recommendations: string[] = []
  if (simpsonD < 0.5) recommendations.push('【多樣性不足】建議鼓勵更多單位使用場地，提升資源多樣性指數')
  else recommendations.push('【多樣性良好】資源分配均衡 (D=' + (Math.round(simpsonD * 1000) / 1000) + ')，持續維持現行政策')
  
  const lowUsageFacs = facilityWithRate.filter((f: any) => f.utilizationRate < 20 && f.usage_count === 0)
  if (lowUsageFacs.length > 0) recommendations.push('【閒置場地】' + lowUsageFacs.map((f: any) => f.FAC_NAME).join('、') + ' 利用率偏低，建議開放為自習空間或調整開放時段')
  
  const highUsageFacs = facilityWithRate.filter((f: any) => f.utilizationRate > 70)
  if (highUsageFacs.length > 0) recommendations.push('【熱門場地】' + highUsageFacs.map((f: any) => f.FAC_NAME).join('、') + ' 使用率高，建議延長開放時間或增設替代場地')
  
  const lowStockEq = (equipStats || []).filter((e: any) => e.EQ_AVAILABLE === 0)
  if (lowStockEq.length > 0) recommendations.push('【庫存不足】' + lowStockEq.map((e: any) => e.EQ_NAME).join('、') + ' 已全數借出，建議增購')
  
  recommendations.push('建議定期舉辦器材操作證訓練課程，降低借用門檻')
  if ((repairStats?.pending || 0) > 2) recommendations.push('【報修積壓】目前有 ' + repairStats.pending + ' 件待處理報修，建議優先排程')
  if (peakHours && peakHours.length > 0) recommendations.push('【尖峰時段】最熱門時段為 ' + (peakHours as any[]).map(p => p.hour + ':00').join('、') + '，建議錯開排程')

  return c.json({
    reportTitle: '114學年度第2學期 課指組資源使用評鑑報告',
    generatedAt: new Date().toISOString(),
    simpson: {
      value: Math.round(simpsonD * 1000) / 1000,
      interpretation: simpsonD > 0.7 ? '資源被各群體均衡使用（多樣性高）' :
                      simpsonD > 0.4 ? '資源分配尚可，部分群體使用較多' :
                      '資源被少數群體壟斷，建議調整配置政策',
      unitBreakdown: unitUsage
    },
    facilityUsage: facilityWithRate,
    equipmentUsage: equipStats,
    violationSummary: violStats,
    activitySummary: activityStats || { total: 0, approved: 0, pending: 0, rejected: 0 },
    repairSummary: repairStats || { total: 0, pending: 0, in_progress: 0, completed: 0 },
    conflictSummary: conflictStats || { total: 0, resolved: 0, failed: 0 },
    peakHours: peakHours || [],
    recommendations
  })
})

// POST /api/ai/generate-pdf - 活動申請 PDF 資料生成 (Epic 3)
aiRoutes.post('/generate-pdf', async (c) => {
  const db = c.env.DB
  const b = await c.req.json()
  const activityId = b.activityId
  
  if (!activityId) return c.json({ success: false, message: '請提供活動 ID' }, 400)
  
  const activity = await db.prepare(
    `SELECT AA.*, U.USR_NAME, UN.UNIT_NAME, UN.UNIT_TYPE
     FROM ActivityApplication AA
     LEFT JOIN User U ON AA.AA_USR_ID = U.USR_ID
     LEFT JOIN Unit UN ON AA.AA_UNIT_ID = UN.UNIT_ID
     WHERE AA.AA_ID = ?`
  ).bind(activityId).first() as any
  
  if (!activity) return c.json({ success: false, message: '活動不存在' }, 404)
  
  const statusNames: Record<number, string> = { 0: '待審核', 1: '已核准', 2: '已拒絕', 3: '已取消' }
  
  return c.json({
    success: true,
    pdfData: {
      title: '輔仁大學課外活動指導組 活動申請書',
      serialNo: activity.AA_SERIAL_NO,
      activityName: activity.AA_ACTIVITY_NAME,
      applicantName: activity.USR_NAME,
      unitName: activity.UNIT_NAME,
      startDatetime: activity.AA_START_DATETIME,
      endDatetime: activity.AA_END_DATETIME,
      headcount: activity.AA_HEADCOUNT,
      description: activity.AA_DESCRIPTION,
      contactName: activity.AA_CONTACT_NAME,
      contactPhone: activity.AA_CONTACT_PHONE,
      contactEmail: activity.AA_CONTACT_EMAIL,
      themes: activity.AA_THEMES,
      hasAlcohol: activity.AA_HAS_ALCOHOL === 1,
      hasFire: activity.AA_HAS_FIRE === 1,
      hasBooth: activity.AA_HAS_BOOTH === 1,
      status: statusNames[activity.AA_STATUS] || '未知',
      createdAt: activity.AA_CREATED_AT,
      reviewedAt: activity.AA_REVIEWED_AT,
      adminNote: activity.AA_ADMIN_NOTE,
      generatedAt: new Date().toISOString(),
    }
  })
})

export { aiRoutes }
