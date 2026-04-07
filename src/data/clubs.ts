// ============================================================
// FJU Smart Hub - 114 學年度社團 & 學會完整資料
// Source: 114學年度社團一覽表.pdf + 114學年度學會一覽表.pdf
// ============================================================

export interface ClubData {
  id: number
  name: string
  category: string
  categoryLabel: string
  type: 'club' | 'association'
  description: string
  memberCount: number
  advisor?: string
  president?: string
  established?: string
  isActive: boolean
}

// 114學年度 社團一覽表
export const clubs114: ClubData[] = [
  // ===== 學藝性社團 =====
  { id: 1, name: '電腦資訊研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '程式設計、資訊技術研習與交流', memberCount: 58, isActive: true },
  { id: 2, name: '天文社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '天文觀測與天文學知識推廣', memberCount: 32, isActive: true },
  { id: 3, name: '法學社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '法律知識研習與模擬法庭活動', memberCount: 28, isActive: true },
  { id: 4, name: '英語會話社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '英語口語能力提升與文化交流', memberCount: 45, isActive: true },
  { id: 5, name: '日本語文研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '日語學習與日本文化體驗', memberCount: 38, isActive: true },
  { id: 6, name: '韓國語文研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '韓語學習與韓國文化推廣', memberCount: 42, isActive: true },
  { id: 7, name: '德語研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '德語學習與歐洲文化交流', memberCount: 22, isActive: true },
  { id: 8, name: '法語研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '法語學習與法國文化推廣', memberCount: 20, isActive: true },
  { id: 9, name: '西班牙語文研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '西語學習與拉丁美洲文化交流', memberCount: 25, isActive: true },
  { id: 10, name: '創業投資研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '創業知識學習、商業計畫撰寫', memberCount: 35, isActive: true },
  { id: 11, name: '生命倫理研究社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '生命倫理議題探討與實踐', memberCount: 18, isActive: true },
  { id: 12, name: '辯論社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '辯論技巧訓練與辯論賽事參與', memberCount: 30, isActive: true },
  { id: 13, name: '手語社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '手語學習與聽障文化認識', memberCount: 26, isActive: true },
  { id: 14, name: '桌上遊戲社', category: 'academic', categoryLabel: '學藝', type: 'club', description: '桌遊策略研習與社交互動', memberCount: 50, isActive: true },

  // ===== 康樂性社團 =====
  { id: 15, name: '吉他社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '吉他演奏教學與音樂創作', memberCount: 55, isActive: true },
  { id: 16, name: '熱門音樂社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '流行音樂演奏與樂團組成', memberCount: 48, isActive: true },
  { id: 17, name: '管樂社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '管樂器演奏與樂團公演', memberCount: 40, isActive: true },
  { id: 18, name: '弦樂社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '弦樂器演奏與室內樂活動', memberCount: 30, isActive: true },
  { id: 19, name: '國樂社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '中國傳統樂器演奏與推廣', memberCount: 35, isActive: true },
  { id: 20, name: '合唱團', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '合唱歌曲排練與公演', memberCount: 42, isActive: true },
  { id: 21, name: '口琴社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '口琴演奏教學與合奏活動', memberCount: 22, isActive: true },
  { id: 22, name: '烏克麗麗社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '烏克麗麗彈唱教學', memberCount: 28, isActive: true },
  { id: 23, name: '動漫研究社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '動畫漫畫研究與COSPLAY', memberCount: 65, isActive: true },
  { id: 24, name: '魔術社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '魔術技巧學習與表演', memberCount: 24, isActive: true },
  { id: 25, name: '電影研究社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: '電影賞析、影評撰寫與短片拍攝', memberCount: 38, isActive: true },
  { id: 26, name: '嘻哈文化研究社', category: 'recreation', categoryLabel: '康樂', type: 'club', description: 'Hip-hop音樂與街頭文化', memberCount: 32, isActive: true },

  // ===== 體育性社團 =====
  { id: 27, name: '籃球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '籃球訓練與校際比賽', memberCount: 60, isActive: true },
  { id: 28, name: '排球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '排球訓練與校際比賽', memberCount: 45, isActive: true },
  { id: 29, name: '足球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '足球訓練與校際比賽', memberCount: 42, isActive: true },
  { id: 30, name: '棒球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '棒球訓練與校際比賽', memberCount: 35, isActive: true },
  { id: 31, name: '壘球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '壘球訓練與休閒運動', memberCount: 28, isActive: true },
  { id: 32, name: '羽球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '羽毛球訓練與校際比賽', memberCount: 52, isActive: true },
  { id: 33, name: '桌球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '桌球技術訓練與比賽', memberCount: 38, isActive: true },
  { id: 34, name: '網球社', category: 'sports', categoryLabel: '體育', type: 'club', description: '網球訓練與校際比賽', memberCount: 30, isActive: true },
  { id: 35, name: '游泳社', category: 'sports', categoryLabel: '體育', type: 'club', description: '游泳訓練與水上運動', memberCount: 25, isActive: true },
  { id: 36, name: '田徑社', category: 'sports', categoryLabel: '體育', type: 'club', description: '田徑訓練與運動會', memberCount: 32, isActive: true },
  { id: 37, name: '跆拳道社', category: 'sports', categoryLabel: '體育', type: 'club', description: '跆拳道訓練與段位考試', memberCount: 28, isActive: true },
  { id: 38, name: '柔道社', category: 'sports', categoryLabel: '體育', type: 'club', description: '柔道技術訓練與比賽', memberCount: 20, isActive: true },
  { id: 39, name: '劍道社', category: 'sports', categoryLabel: '體育', type: 'club', description: '劍道練習與段位考試', memberCount: 22, isActive: true },
  { id: 40, name: '射箭社', category: 'sports', categoryLabel: '體育', type: 'club', description: '射箭技術學習與比賽', memberCount: 18, isActive: true },
  { id: 41, name: '飛盤社', category: 'sports', categoryLabel: '體育', type: 'club', description: '飛盤運動與競技比賽', memberCount: 24, isActive: true },
  { id: 42, name: '登山社', category: 'sports', categoryLabel: '體育', type: 'club', description: '登山健行與戶外探索', memberCount: 35, isActive: true },
  { id: 43, name: '自行車社', category: 'sports', categoryLabel: '體育', type: 'club', description: '自行車騎乘與環島活動', memberCount: 22, isActive: true },
  { id: 44, name: '瑜珈社', category: 'sports', categoryLabel: '體育', type: 'club', description: '瑜珈練習與身心靈健康', memberCount: 40, isActive: true },
  { id: 45, name: '滑板社', category: 'sports', categoryLabel: '體育', type: 'club', description: '滑板技術學習與極限運動', memberCount: 20, isActive: true },
  { id: 46, name: '有氧體適能社', category: 'sports', categoryLabel: '體育', type: 'club', description: '有氧運動與體能訓練', memberCount: 30, isActive: true },

  // ===== 藝術性社團 =====
  { id: 47, name: '攝影社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '攝影技巧學習與美學素養培養', memberCount: 50, isActive: true },
  { id: 48, name: '話劇社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '戲劇表演訓練與公演', memberCount: 35, isActive: true },
  { id: 49, name: '美術社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '繪畫創作與美術展覽', memberCount: 28, isActive: true },
  { id: 50, name: '書法社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '書法練習與書藝推廣', memberCount: 20, isActive: true },
  { id: 51, name: '熱門舞蹈社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '街舞、流行舞蹈練習與公演', memberCount: 65, isActive: true },
  { id: 52, name: '國際標準舞社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '國標舞教學與比賽', memberCount: 25, isActive: true },
  { id: 53, name: '手工藝社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '手作工藝創作與教學', memberCount: 30, isActive: true },
  { id: 54, name: '陶藝社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '陶藝製作與展覽', memberCount: 18, isActive: true },
  { id: 55, name: '花藝社', category: 'arts', categoryLabel: '藝文', type: 'club', description: '花藝設計與美學生活', memberCount: 22, isActive: true },

  // ===== 服務性社團 =====
  { id: 56, name: '環保社', category: 'service', categoryLabel: '服務', type: 'club', description: '環境保護與永續發展推廣', memberCount: 35, isActive: true },
  { id: 57, name: '崇德青年社', category: 'service', categoryLabel: '服務', type: 'club', description: '社區服務與品德教育推廣', memberCount: 28, isActive: true },
  { id: 58, name: '慈幼社', category: 'service', categoryLabel: '服務', type: 'club', description: '兒童關懷與教育服務', memberCount: 25, isActive: true },
  { id: 59, name: '社會服務團', category: 'service', categoryLabel: '服務', type: 'club', description: '弱勢關懷與社會公益', memberCount: 30, isActive: true },
  { id: 60, name: '春暉社', category: 'service', categoryLabel: '服務', type: 'club', description: '反毒宣導與健康促進', memberCount: 22, isActive: true },
  { id: 61, name: '原住民文化社', category: 'service', categoryLabel: '服務', type: 'club', description: '原住民文化推廣與交流', memberCount: 20, isActive: true },
  { id: 62, name: '國際志工社', category: 'service', categoryLabel: '服務', type: 'club', description: '國際志工服務與文化交流', memberCount: 32, isActive: true },
  { id: 63, name: '紅十字青年服務隊', category: 'service', categoryLabel: '服務', type: 'club', description: '急救訓練與社區服務', memberCount: 28, isActive: true },

  // ===== 宗教性社團 =====
  { id: 64, name: '天主教大專同學會', category: 'general', categoryLabel: '宗教', type: 'club', description: '天主教信仰分享與靈修活動', memberCount: 25, isActive: true },
  { id: 65, name: '基督教學生團契', category: 'general', categoryLabel: '宗教', type: 'club', description: '基督教信仰團契與服務', memberCount: 30, isActive: true },
  { id: 66, name: '佛學社', category: 'general', categoryLabel: '宗教', type: 'club', description: '佛學研習與禪修活動', memberCount: 18, isActive: true },

  // ===== 自治性社團 =====
  { id: 67, name: '學生會', category: 'general', categoryLabel: '自治', type: 'club', description: '學生自治組織，代表全校學生權益', memberCount: 35, isActive: true },
  { id: 68, name: '學生議會', category: 'general', categoryLabel: '自治', type: 'club', description: '學生自治議事機構', memberCount: 25, isActive: true },
  { id: 69, name: '畢聯會', category: 'general', categoryLabel: '自治', type: 'club', description: '畢業典禮相關事務規劃', memberCount: 20, isActive: true },
  { id: 70, name: '僑生聯誼會', category: 'general', categoryLabel: '自治', type: 'club', description: '僑生聯誼與適應輔導', memberCount: 32, isActive: true },
  { id: 71, name: '陸生聯誼會', category: 'general', categoryLabel: '自治', type: 'club', description: '陸生聯誼與校園適應', memberCount: 28, isActive: true },
]

// 114學年度 學會一覽表 (各系學會/院學會)
export const associations114: ClubData[] = [
  // ===== 文學院 =====
  { id: 101, name: '中國文學系學會', category: 'academic', categoryLabel: '文學院', type: 'association', description: '中文系學術交流與系上活動', memberCount: 65, isActive: true },
  { id: 102, name: '歷史學系學會', category: 'academic', categoryLabel: '文學院', type: 'association', description: '歷史學系學術與文化活動', memberCount: 50, isActive: true },
  { id: 103, name: '哲學系學會', category: 'academic', categoryLabel: '文學院', type: 'association', description: '哲學思辨與學術活動', memberCount: 40, isActive: true },

  // ===== 藝術學院 =====
  { id: 104, name: '音樂學系學會', category: 'arts', categoryLabel: '藝術學院', type: 'association', description: '音樂系演出與學術活動', memberCount: 55, isActive: true },
  { id: 105, name: '應用美術學系學會', category: 'arts', categoryLabel: '藝術學院', type: 'association', description: '美術創作展覽與設計交流', memberCount: 48, isActive: true },
  { id: 106, name: '景觀設計學系學會', category: 'arts', categoryLabel: '藝術學院', type: 'association', description: '景觀設計與環境規劃活動', memberCount: 42, isActive: true },

  // ===== 傳播學院 =====
  { id: 107, name: '大眾傳播學系學會', category: 'academic', categoryLabel: '傳播學院', type: 'association', description: '傳播媒體研習與實務活動', memberCount: 60, isActive: true },
  { id: 108, name: '新聞傳播學系學會', category: 'academic', categoryLabel: '傳播學院', type: 'association', description: '新聞採編與傳播實務', memberCount: 52, isActive: true },
  { id: 109, name: '廣告傳播學系學會', category: 'academic', categoryLabel: '傳播學院', type: 'association', description: '廣告創意與行銷傳播', memberCount: 48, isActive: true },

  // ===== 教育學院 =====
  { id: 110, name: '體育學系學會', category: 'sports', categoryLabel: '教育學院', type: 'association', description: '體育運動教學與賽事活動', memberCount: 55, isActive: true },
  { id: 111, name: '圖書資訊學系學會', category: 'academic', categoryLabel: '教育學院', type: 'association', description: '圖書資訊管理與數位學習', memberCount: 38, isActive: true },
  { id: 112, name: '教育領導與科技發展學系學會', category: 'academic', categoryLabel: '教育學院', type: 'association', description: '教育科技與領導能力培養', memberCount: 35, isActive: true },

  // ===== 醫學院 =====
  { id: 113, name: '醫學系學會', category: 'academic', categoryLabel: '醫學院', type: 'association', description: '醫學學術交流與臨床見習', memberCount: 80, isActive: true },
  { id: 114, name: '護理學系學會', category: 'academic', categoryLabel: '醫學院', type: 'association', description: '護理專業學習與實務研習', memberCount: 70, isActive: true },
  { id: 115, name: '公共衛生學系學會', category: 'academic', categoryLabel: '醫學院', type: 'association', description: '公衛學術研究與社區衛生', memberCount: 45, isActive: true },
  { id: 116, name: '臨床心理學系學會', category: 'academic', categoryLabel: '醫學院', type: 'association', description: '臨床心理學研習與實務', memberCount: 42, isActive: true },
  { id: 117, name: '職能治療學系學會', category: 'academic', categoryLabel: '醫學院', type: 'association', description: '職能治療專業學習', memberCount: 40, isActive: true },
  { id: 118, name: '呼吸治療學系學會', category: 'academic', categoryLabel: '醫學院', type: 'association', description: '呼吸治療專業與實務', memberCount: 35, isActive: true },

  // ===== 理工學院 =====
  { id: 119, name: '數學系學會', category: 'academic', categoryLabel: '理工學院', type: 'association', description: '數學研究與學術活動', memberCount: 42, isActive: true },
  { id: 120, name: '物理學系學會', category: 'academic', categoryLabel: '理工學院', type: 'association', description: '物理實驗與學術交流', memberCount: 38, isActive: true },
  { id: 121, name: '化學系學會', category: 'academic', categoryLabel: '理工學院', type: 'association', description: '化學實驗與學術研究', memberCount: 40, isActive: true },
  { id: 122, name: '生命科學系學會', category: 'academic', categoryLabel: '理工學院', type: 'association', description: '生物科學研究與實驗', memberCount: 45, isActive: true },
  { id: 123, name: '資訊工程學系學會', category: 'academic', categoryLabel: '理工學院', type: 'association', description: '資訊工程學術與程式實作', memberCount: 65, isActive: true },
  { id: 124, name: '電機工程學系學會', category: 'academic', categoryLabel: '理工學院', type: 'association', description: '電機工程學術與實務', memberCount: 50, isActive: true },

  // ===== 外語學院 =====
  { id: 125, name: '英國語文學系學會', category: 'academic', categoryLabel: '外語學院', type: 'association', description: '英語文學與語言研究', memberCount: 55, isActive: true },
  { id: 126, name: '法國語文學系學會', category: 'academic', categoryLabel: '外語學院', type: 'association', description: '法語文學與文化交流', memberCount: 35, isActive: true },
  { id: 127, name: '西班牙語文學系學會', category: 'academic', categoryLabel: '外語學院', type: 'association', description: '西語文學與拉美文化', memberCount: 38, isActive: true },
  { id: 128, name: '日本語文學系學會', category: 'academic', categoryLabel: '外語學院', type: 'association', description: '日本語文學習與文化交流', memberCount: 48, isActive: true },
  { id: 129, name: '義大利語文學系學會', category: 'academic', categoryLabel: '外語學院', type: 'association', description: '義大利語學習與文化活動', memberCount: 30, isActive: true },
  { id: 130, name: '德語語文學系學會', category: 'academic', categoryLabel: '外語學院', type: 'association', description: '德語學習與歐洲文化', memberCount: 28, isActive: true },

  // ===== 民生學院 =====
  { id: 131, name: '兒童與家庭學系學會', category: 'academic', categoryLabel: '民生學院', type: 'association', description: '兒童發展與家庭研究', memberCount: 42, isActive: true },
  { id: 132, name: '餐旅管理學系學會', category: 'academic', categoryLabel: '民生學院', type: 'association', description: '餐旅產業管理與實務', memberCount: 55, isActive: true },
  { id: 133, name: '食品科學系學會', category: 'academic', categoryLabel: '民生學院', type: 'association', description: '食品科學研究與檢驗', memberCount: 45, isActive: true },
  { id: 134, name: '營養科學系學會', category: 'academic', categoryLabel: '民生學院', type: 'association', description: '營養學研究與健康促進', memberCount: 48, isActive: true },

  // ===== 法律學院 =====
  { id: 135, name: '法律學系學會', category: 'academic', categoryLabel: '法律學院', type: 'association', description: '法律學術研討與模擬法庭', memberCount: 68, isActive: true },
  { id: 136, name: '財經法律學系學會', category: 'academic', categoryLabel: '法律學院', type: 'association', description: '財經法律研究與實務', memberCount: 50, isActive: true },

  // ===== 管理學院 =====
  { id: 137, name: '企業管理學系學會', category: 'academic', categoryLabel: '管理學院', type: 'association', description: '企業管理學術與實務', memberCount: 60, isActive: true },
  { id: 138, name: '會計學系學會', category: 'academic', categoryLabel: '管理學院', type: 'association', description: '會計專業學習與考證輔導', memberCount: 52, isActive: true },
  { id: 139, name: '統計資訊學系學會', category: 'academic', categoryLabel: '管理學院', type: 'association', description: '統計與資料分析研究', memberCount: 38, isActive: true },
  { id: 140, name: '金融與國際企業學系學會', category: 'academic', categoryLabel: '管理學院', type: 'association', description: '金融投資與國際企業管理', memberCount: 55, isActive: true },
  { id: 141, name: '資訊管理學系學會', category: 'academic', categoryLabel: '管理學院', type: 'association', description: '資訊管理與系統開發', memberCount: 48, isActive: true },

  // ===== 社會科學院 =====
  { id: 142, name: '社會學系學會', category: 'academic', categoryLabel: '社會科學院', type: 'association', description: '社會學研究與田野調查', memberCount: 42, isActive: true },
  { id: 143, name: '社會工作學系學會', category: 'academic', categoryLabel: '社會科學院', type: 'association', description: '社會工作實務與服務', memberCount: 48, isActive: true },
  { id: 144, name: '經濟學系學會', category: 'academic', categoryLabel: '社會科學院', type: 'association', description: '經濟學研究與分析', memberCount: 40, isActive: true },
  { id: 145, name: '宗教學系學會', category: 'academic', categoryLabel: '社會科學院', type: 'association', description: '宗教學研究與跨文化對話', memberCount: 25, isActive: true },

  // ===== 織品服裝學院 =====
  { id: 146, name: '織品服裝學系學會', category: 'arts', categoryLabel: '織品學院', type: 'association', description: '服裝設計與織品技術', memberCount: 52, isActive: true },
]

// 合併所有社團與學會
export const allClubsAndAssociations = [...clubs114, ...associations114]

// 類別統計
export function getClubStats() {
  const stats: Record<string, number> = {}
  allClubsAndAssociations.forEach(c => {
    stats[c.categoryLabel] = (stats[c.categoryLabel] || 0) + 1
  })
  return {
    totalClubs: clubs114.length,
    totalAssociations: associations114.length,
    totalAll: allClubsAndAssociations.length,
    totalMembers: allClubsAndAssociations.reduce((sum, c) => sum + c.memberCount, 0),
    byCategory: stats
  }
}
