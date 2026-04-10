// ============================================================
// FJU Smart Hub - 114 學年度社團完整資料
// Source: 114學年度學生社團一覽表 (115.03.06)
// Total: 82個社團
// ============================================================

export interface ClubData {
  id: number
  name: string
  category: 'academic' | 'recreation' | 'service' | 'sports' | 'arts' | 'music'
  categoryLabel: string
  type: 'club'
  description: string
  memberCount: number
  advisor?: string
  president?: string
  established?: string
  isActive: boolean
}

// 114學年度 社團一覽表 (共82個)
export const clubs114: ClubData[] = [

  // ===== (A) 學術性社團 — 19個 =====
  { id: 49,  name: '健言社',           category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '健康與語言素養提升',           memberCount: 28, isActive: true },
  { id: 50,  name: '大千社',           category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '藝術與文化研習交流',           memberCount: 25, isActive: true },
  { id: 51,  name: '天文社',           category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '天文觀測與天文學知識推廣',     memberCount: 32, isActive: true },
  { id: 53,  name: '中華醫藥研習社',   category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '中醫藥學習與傳統醫學研究',     memberCount: 24, isActive: true },
  { id: 54,  name: '國際經濟商管學生會', category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '國際商務與經濟學術交流',     memberCount: 30, isActive: true },
  { id: 56,  name: '占星塔羅社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '占星學與塔羅牌文化研究',       memberCount: 22, isActive: true },
  { id: 58,  name: '信望愛社',         category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '靈性探索與信仰交流',           memberCount: 26, isActive: true },
  { id: 99,  name: '淨仁社',           category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '淨土念佛與法學研習',           memberCount: 20, isActive: true },
  { id: 140, name: '學園團契社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '基督信仰與學術交流團契',       memberCount: 28, isActive: true },
  { id: 141, name: '禪學社',           category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '禪學研習與禪修實踐',           memberCount: 25, isActive: true },
  { id: 142, name: '聖經研究社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '聖經研究與信仰交流',           memberCount: 24, isActive: true },
  { id: 159, name: '教育學程學會',     category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '教育專業學習與教學研討',       memberCount: 32, isActive: true },
  { id: 161, name: '福智青年社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '佛教智慧與福德實踐',           memberCount: 30, isActive: true },
  { id: 174, name: '性別研究社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '性別議題研究與跨文化對話',     memberCount: 26, isActive: true },
  { id: 191, name: '永續影響力大使社', category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '永續發展與社會影響力',         memberCount: 28, isActive: true },
  { id: 192, name: '創新創業社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '創業知識與商業創新',           memberCount: 30, isActive: true },
  { id: 196, name: '租稅研究社',       category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '租稅法律與財經研究',           memberCount: 22, isActive: true },
  { id: 229, name: '光鹽社',           category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '社會關懷與志願服務',           memberCount: 26, isActive: true },
  { id: 401, name: '金融投資研究社',   category: 'academic', categoryLabel: 'A-學術性', type: 'club', description: '金融投資與財務管理',           memberCount: 28, isActive: true },

  // ===== (B) 休閒聯誼性社團 — 14個 =====
  { id: 42,  name: '僑生聯誼會',           category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '僑生聯誼與校園適應輔導', memberCount: 32, isActive: true },
  { id: 43,  name: '高中校友聯合總會',     category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '校友聯誼與校園發展',     memberCount: 28, isActive: true },
  { id: 60,  name: '轉學生聯誼會',         category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '轉學生適應與校園聯誼',   memberCount: 25, isActive: true },
  { id: 76,  name: '野營社',               category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '戶外野營與露營活動',     memberCount: 30, isActive: true },
  { id: 80,  name: '魔術社',               category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '魔術技巧學習與表演',     memberCount: 24, isActive: true },
  { id: 82,  name: '棋藝社',               category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '圍棋、象棋等棋藝研習',   memberCount: 20, isActive: true },
  { id: 83,  name: '飲料調製社',           category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '調酒與飲料製作藝術',     memberCount: 22, isActive: true },
  { id: 129, name: '努瑪社',               category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '數學遊戲與邏輯思維活動', memberCount: 18, isActive: true },
  { id: 163, name: '國際菁英學生會',       category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '國際交流與菁英培養',     memberCount: 26, isActive: true },
  { id: 168, name: '桌上遊戲社',           category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '桌遊策略研習與社交互動', memberCount: 35, isActive: true },
  { id: 184, name: '電子競技社',           category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '電競遊戲與賽事參與',     memberCount: 40, isActive: true },
  { id: 185, name: '二輪社',               category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '機車文化與安全騎乘',     memberCount: 28, isActive: true },
  { id: 193, name: '咖啡研究社',           category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '咖啡知識與品鑑藝術',     memberCount: 23, isActive: true },
  { id: 198, name: '韓國流行文化研究社',   category: 'recreation', categoryLabel: 'B-休閒聯誼', type: 'club', description: '韓流文化與語言學習',     memberCount: 38, isActive: true },

  // ===== (C) 服務性社團 — 8個 =====
  { id: 97,  name: '同舟共濟服務社',   category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '社區服務與弱勢關懷', memberCount: 32, isActive: true },
  { id: 98,  name: '醒新愛愛服務社',   category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '愛心服務與社會公益', memberCount: 28, isActive: true },
  { id: 100, name: '急救康輔社',       category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '急救訓練與校園服務', memberCount: 35, isActive: true },
  { id: 101, name: '崇德志工服務社',   category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '志工服務與品德教育', memberCount: 30, isActive: true },
  { id: 116, name: '基層文化服務社',   category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '文化推廣與基層服務', memberCount: 26, isActive: true },
  { id: 126, name: '慈濟青年社',       category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '慈濟志工與社會服務', memberCount: 32, isActive: true },
  { id: 148, name: '繪本服務學習社',   category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '兒童教育與繪本推廣', memberCount: 25, isActive: true },
  { id: 189, name: '勵德青少年服務社', category: 'service', categoryLabel: 'C-服務性', type: 'club', description: '青少年關懷與輔導',   memberCount: 24, isActive: true },

  // ===== (D) 體能性社團 — 20個 =====
  { id: 75,  name: '登山社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '登山健行與戶外探索',         memberCount: 35, isActive: true },
  { id: 84,  name: '國術社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '中國功夫與傳統武術',         memberCount: 28, isActive: true },
  { id: 86,  name: '跆拳道社',   category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '跆拳道訓練與段位考試',       memberCount: 30, isActive: true },
  { id: 87,  name: '柔道社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '柔道技術訓練與比賽',         memberCount: 22, isActive: true },
  { id: 88,  name: '劍道社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '劍道練習與段位考試',         memberCount: 20, isActive: true },
  { id: 89,  name: '擊劍社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '現代擊劍與比賽訓練',         memberCount: 18, isActive: true },
  { id: 90,  name: '羽球社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '羽毛球訓練與校際比賽',       memberCount: 52, isActive: true },
  { id: 91,  name: '桌球社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '桌球技術訓練與比賽',         memberCount: 38, isActive: true },
  { id: 92,  name: '網球社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '網球訓練與校際比賽',         memberCount: 32, isActive: true },
  { id: 93,  name: '射箭社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '射箭技術學習與比賽',         memberCount: 19, isActive: true },
  { id: 118, name: '同心救生社', category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '救生員訓練與水上安全',       memberCount: 25, isActive: true },
  { id: 131, name: '空手道社',   category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '空手道訓練與動作紀律',       memberCount: 27, isActive: true },
  { id: 136, name: '黑輪社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '街頭輪滑與滑板運動',         memberCount: 21, isActive: true },
  { id: 166, name: '合氣道社',   category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '合氣道武術與身心修養',       memberCount: 23, isActive: true },
  { id: 172, name: '歐洲劍術社', category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '西洋劍術與歐洲武術',         memberCount: 16, isActive: true },
  { id: 188, name: '撞球社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '撞球技巧與競技比賽',         memberCount: 24, isActive: true },
  { id: 190, name: 'Kali武術社', category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '菲律賓Kali武術訓練',         memberCount: 17, isActive: true },
  { id: 199, name: '自由潛水社', category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '自由潛水與水中運動',         memberCount: 20, isActive: true },
  { id: 402, name: '跑步社',     category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '慢跑與馬拉松訓練',           memberCount: 28, isActive: true },
  { id: 403, name: '袋棍球社',   category: 'sports', categoryLabel: 'D-體能性', type: 'club', description: '袋棍球運動與團隊合作',       memberCount: 22, isActive: true },

  // ===== (E) 藝術性社團 — 12個 =====
  { id: 64,  name: '書法社',         category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '書法練習與書藝推廣',         memberCount: 20, isActive: true },
  { id: 66,  name: '攝影社',         category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '攝影技巧學習與美學素養',     memberCount: 35, isActive: true },
  { id: 67,  name: '熱舞社',         category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '街舞與流行舞蹈表演',         memberCount: 45, isActive: true },
  { id: 70,  name: '戲劇社',         category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '話劇表演訓練與舞台呈現',     memberCount: 32, isActive: true },
  { id: 72,  name: '國際標準舞蹈社', category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '國標舞教學與競技表演',       memberCount: 28, isActive: true },
  { id: 81,  name: '廣播演藝社',     category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '廣播主持與聲音表演藝術',     memberCount: 26, isActive: true },
  { id: 132, name: '動漫電玩研習社', category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '動漫與電玩文化研究',         memberCount: 48, isActive: true },
  { id: 157, name: '影片創作社',     category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '影視製作與創意呈現',         memberCount: 30, isActive: true },
  { id: 171, name: '弓道社',         category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '和式弓道與傳統射藝',         memberCount: 18, isActive: true },
  { id: 178, name: '光火藝術社',     category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '當代藝術與創意表現',         memberCount: 22, isActive: true },
  { id: 179, name: '民俗體育社',     category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '民俗運動與傳統體藝',         memberCount: 25, isActive: true },
  { id: 194, name: '生活花藝設計社', category: 'arts', categoryLabel: 'E-藝術性', type: 'club', description: '花藝設計與美學生活',         memberCount: 24, isActive: true },

  // ===== (F) 音樂性社團 — 9個 =====
  { id: 61,  name: '國樂社',             category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '中國傳統樂器演奏與推廣', memberCount: 32, isActive: true },
  { id: 68,  name: '管弦樂社',           category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '管弦樂演奏與樂團表演',   memberCount: 38, isActive: true },
  { id: 71,  name: '民謠吉他社',         category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '吉他演奏與民謠音樂',     memberCount: 40, isActive: true },
  { id: 74,  name: '搖滾音樂研究社',     category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '搖滾樂隊與音樂創作',     memberCount: 28, isActive: true },
  { id: 123, name: '鋼琴社',             category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '鋼琴教學與音樂演奏',     memberCount: 35, isActive: true },
  { id: 124, name: '數位音樂創作研習社', category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '電子音樂製作與編曲',     memberCount: 26, isActive: true },
  { id: 167, name: '烏克麗麗社',         category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '烏克麗麗彈唱教學',       memberCount: 30, isActive: true },
  { id: 186, name: '嘻哈文化社',         category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: 'Hip-hop音樂與街頭文化', memberCount: 32, isActive: true },
  { id: 223, name: '爵士鋼琴社',         category: 'music', categoryLabel: 'F-音樂性', type: 'club', description: '爵士樂與即興音樂演奏',   memberCount: 24, isActive: true },
]

// ── 輔助函式 ────────────────────────────────────────────────

/** 依類別篩選 */
export function getClubsByCategory(
  category: ClubData['category']
): ClubData[] {
  return clubs114.filter(c => c.category === category)
}

/** 統計資訊 */
export function getClubStats() {
  const byCategory: Record<string, number> = {}
  clubs114.forEach(c => {
    byCategory[c.categoryLabel] = (byCategory[c.categoryLabel] || 0) + 1
  })
  return {
    total: clubs114.length,                                          // 82
    totalMembers: clubs114.reduce((sum, c) => sum + c.memberCount, 0),
    byCategory,
  }
}
