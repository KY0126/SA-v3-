# 輔仁大學課指組 — 器材與場地預約平台 v3.2

> **Hono + Cloudflare Pages + D1 SQLite** 全端重構版  
> 114 學年度系統分析課程 SA 範本 1.2 實作

## Demo URL

| 環境 | URL |
|------|-----|
| **Sandbox Demo** | https://3000-ines7d5od0umg4mb1ae8b-b9b802c4.sandbox.novita.ai |
| **GitHub Repo** | https://github.com/KY0126/SA-v3 (branch: `branch2`) |

### Demo 帳號（快速登入）

| 角色 | Email | 密碼 |
|------|-------|------|
| 管理員 | `admin01@mail.fju.edu.tw` | demo123 |
| 社團幹部 A（王小明） | `s1100001@mail.fju.edu.tw` | demo123 |
| 社團幹部 B（李小花） | `s1100002@mail.fju.edu.tw` | demo123 |
| 學生 | `s1100003@mail.fju.edu.tw` | demo123 |
| 教授 | `prof01@mail.fju.edu.tw` | demo123 |
| 職員 | `staff01@mail.fju.edu.tw` | demo123 |

> 幹部 A（王小明）與幹部 B（李小花）用於模擬場地預約衝突協調場景

---

## 已完成功能清單

### Epic 1 — 帳號認證管理
- [x] 登入 / 登出（Demo 模式）
- [x] 登入 Tab 切換（登入 / 建立帳號 / 忘記密碼）
- [x] 註冊時選擇身份角色（學生 / 社團幹部 / 教授 / 職員）
- [x] Email 限制 `@mail.fju.edu.tw` / `@cloud.fju.edu.tw`
- [x] 帳號停權 / 解除停權
- [x] 管理員權限切換
- [x] 大頭貼選擇器（Emoji Avatar）
- [x] 個人中心（預約/借用/申訴/報修/違規紀錄）
- [x] 帳號到期日檢查

### Epic 2 — 設施管理
- [x] 場地清單（搜尋/篩選）
- [x] 新增場地（管理員）
- [x] 場地編輯 / 軟刪除
- [x] GIS 座標（GPS 經緯度）
- [x] 維修紀錄 API

### Epic 3 — 活動申請管理
- [x] 活動申請（自動流水編號 `AAYYYYNNNNNN`）
- [x] 核准 / 拒絕 / 取消
- [x] 活動 PDF 申請書生成（新開視窗列印）
- [x] AI 活動預審（風險評估）

### Epic 4 — 場地預約管理
- [x] 場地預約送出（時段衝突偵測）
- [x] 核准 / 拒絕 / 取消 / 歸還
- [x] **月曆行事曆視圖**（可翻月、今日標記、色彩區分核准/待審）
- [x] 場協大會登記 / 審核
- [x] 衝突協調聊天室

### Epic 5 — 器材借用管理
- [x] 器材清單（含操作證要求）
- [x] 多器材借用申請
- [x] 操作證驗證
- [x] 借用紀錄
- [x] 器材操作證類型管理

### Epic 6 — 報修管理
- [x] 報修提交（最少 10 字描述）
- [x] 管理員更新狀態（待處理 → 處理中 → 已完成）

### Epic 7 — 申訴管理
- [x] 停權申復 / 違規記點申復 / 其他檢舉
- [x] 核准 / 駁回（附說明）

### Epic 8 — 資訊查找與公告
- [x] 公告清單（含有效期篩選）
- [x] 新增 / 編輯 / 刪除公告

### Epic 9 — 違規記點與銷點
- [x] 單位違規點數一覽
- [x] 新增記點（自動更新單位累計分數）
- [x] 10 點自動停權
- [x] 勞動服務銷點申請與審核

### Epic 10 — 統計數據
- [x] 儀表板（場地/器材/使用者/預約/報修統計）
- [x] 場地使用率統計
- [x] 器材使用統計
- [x] **AI 學期總結評鑑報告**（Simpson 多樣性指數 + 報修/衝突/熱門時段/智慧建議）

### AI / RAG 功能
- [x] AI 聊天助理（支援 GitHub Models GPT-4o，含 RAG fallback）
- [x] 角色專屬 System Prompt（5 種角色）
- [x] 關鍵字 RAG 知識庫（場地預約/器材借用/衝突協調/違規/申訴/報修/活動申請/場協大會/勞動服務/操作證）
- [x] AI 活動預審（風險等級 + 法規引用）
- [x] AI 學期評鑑報告生成
- [x] 活動申請 PDF 資料 API

### Prompt 7 專項指令
- [x] 7-1: 保留借用者/課指組兩種身份登入
- [x] 7-2: 借用者細分為社團幹部/教授/職員
- [x] 7-3: 修正隱藏錯誤（btoa Unicode、路由衝突等）
- [x] 7-4: 選用 Hono + Cloudflare 架構
- [x] 7-6A: AI 改用 GitHub Models PAT（含 fallback 至本地 RAG）
- [x] 7-6B: 各角色頁面 FAQ 區塊
- [x] 7-7A: 移除活動牆
- [x] 7-9A: AI 資訊概覽與法規查詢整合至 FAQ 單一頁面
- [x] 7-9B: 登入按鈕下方新增忘記密碼/建立帳號 Tab 切換
- [x] 7-9C: 註冊時選身分（社團幹部/教授/職員/學生）
- [x] 7-9E: 進入頁面後選擇大頭貼

---

## API 端點一覽

### Auth（認證）
| Method | Path | 說明 |
|--------|------|------|
| POST | `/api/auth/login` | 登入 |
| POST | `/api/auth/register` | 註冊 |
| POST | `/api/auth/forgot-password` | 忘記密碼 |
| POST | `/api/auth/reset-password` | 重設密碼 |

### Facilities（場地）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/facilities` | 場地清單（?q= 搜尋） |
| GET | `/api/facilities/:id` | 場地詳情 |
| GET | `/api/facilities/:id/calendar` | 場地月曆（?start=&end=） |
| GET | `/api/facilities/:id/maintenance` | 維修紀錄 |
| POST | `/api/facilities` | 新增場地 |
| PUT | `/api/facilities/:id` | 編輯場地 |
| DELETE | `/api/facilities/:id` | 停用場地 |

### Equipment（器材）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/equipment` | 器材清單 |
| GET | `/api/equipment/:id` | 器材詳情 |
| GET | `/api/equipment/cert-types` | 操作證類型 |
| POST | `/api/equipment` | 新增器材 |
| POST | `/api/equipment/loans` | 借用器材 |
| GET | `/api/equipment/loans/list` | 借用紀錄 |
| GET | `/api/equipment/loans/:id/details` | 借用明細 |

### Activities（活動申請）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/activities` | 活動清單（?status=&unitId=） |
| GET | `/api/activities/:id` | 活動詳情 |
| POST | `/api/activities` | 新增活動申請 |
| PATCH | `/api/activities/:id/approve` | 核准活動 |
| PATCH | `/api/activities/:id/reject` | 拒絕活動 |
| PATCH | `/api/activities/:id/cancel` | 取消活動 |

### Venue Bookings（場地預約）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/venue-bookings` | 預約清單（?status=&userId=&facId=） |
| GET | `/api/venue-bookings/pending` | 待審核預約 |
| POST | `/api/venue-bookings` | 送出預約（含衝突偵測） |
| PATCH | `/api/venue-bookings/:id/approve` | 核准 |
| PATCH | `/api/venue-bookings/:id/reject` | 拒絕 |
| PATCH | `/api/venue-bookings/:id/cancel` | 取消 |
| PATCH | `/api/venue-bookings/:id/return` | 歸還 |

### Venue Coordination（場協大會）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/venue-coordination` | 登記清單（?semester=&status=） |
| GET | `/api/venue-coordination/:id` | 登記詳情 |
| POST | `/api/venue-coordination` | 新增登記 |
| PATCH | `/api/venue-coordination/:id/approve` | 核准 |
| PATCH | `/api/venue-coordination/:id/reject` | 駁回 |

### Conflicts（衝突協調）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/conflicts` | 衝突清單 |
| GET | `/api/conflicts/:id` | 衝突詳情（含聊天紀錄） |
| POST | `/api/conflicts` | 建立衝突案件 |
| POST | `/api/conflicts/:id/messages` | 發送聊天訊息 |
| PATCH | `/api/conflicts/:id/resolve` | 標記解決 |
| PATCH | `/api/conflicts/:id/fail` | 標記失敗 |

### Repairs（報修）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/repairs` | 報修清單 |
| GET | `/api/repairs/:id` | 報修詳情 |
| POST | `/api/repairs` | 提交報修 |
| PUT | `/api/repairs/:id` | 更新狀態 |

### Appeals（申訴）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/appeals` | 申訴清單 |
| POST | `/api/appeals` | 提交申訴 |
| PATCH | `/api/appeals/:id/approve` | 核准 |
| PATCH | `/api/appeals/:id/reject` | 駁回 |

### Users（使用者管理）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/users` | 使用者清單 |
| GET | `/api/users/:id` | 使用者詳情 |
| GET | `/api/users/:id/profile` | 個人中心（預約/借用/申訴/報修/違規） |
| PUT | `/api/users/:id` | 更新資料 |
| PATCH | `/api/users/:id/avatar` | 更新大頭貼 |
| PATCH | `/api/users/:id/suspend` | 停權 |
| PATCH | `/api/users/:id/unsuspend` | 解除停權 |
| PATCH | `/api/users/:id/toggle-admin` | 切換管理員 |

### Violations & Labor（違規記點 / 勞動服務）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/violations` | 記點紀錄 |
| GET | `/api/violations/unit-points` | 單位點數一覽 |
| POST | `/api/violations` | 新增記點 |
| GET | `/api/labor` | 勞動服務清單 |
| POST | `/api/labor` | 申請銷點 |
| PATCH | `/api/labor/:id/approve` | 核准銷點 |
| PATCH | `/api/labor/:id/reject` | 駁回銷點 |

### Stats & AI（統計 / AI）
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/stats/dashboard` | 儀表板統計 |
| GET | `/api/stats/facility-usage` | 場地使用統計 |
| GET | `/api/stats/equipment-usage` | 器材使用統計 |
| POST | `/api/ai/chat` | AI 聊天（RAG + GPT-4o） |
| POST | `/api/ai/pre-review` | AI 活動預審 |
| POST | `/api/ai/generate-report` | AI 學期評鑑報告 |
| POST | `/api/ai/generate-pdf` | 活動 PDF 資料 |

### Others
| Method | Path | 說明 |
|--------|------|------|
| GET | `/api/announcements` | 公告清單 |
| POST | `/api/announcements` | 新增公告 |
| GET | `/api/units` | 單位清單 |
| GET | `/api/units/:id` | 單位詳情（含成員） |
| GET | `/api/faq` | FAQ（含角色專屬問答 ?role=） |
| GET | `/api/health` | 健康檢查 |

---

## 資料架構

### 技術棧
- **Backend**: Hono Framework (TypeScript)
- **Runtime**: Cloudflare Workers / Pages
- **Database**: Cloudflare D1 (SQLite)
- **Frontend**: Vanilla JS SPA + Tailwind CSS (CDN) + Font Awesome
- **AI**: GitHub Models API (GPT-4o) + 本地 RAG 知識庫

### Database Schema（27 Tables）
1. **User** — 使用者
2. **Unit** — 單位（社團/學生會/行政單位）
3. **UnitMember** — 單位成員
4. **UnitViolationPoint** — 單位違規點數
5. **EquipmentCertType** — 器材操作證類型
6. **EquipmentCert** — 操作證
7. **Facility** — 場地
8. **FacilityMaintenanceLog** — 維修紀錄
9. **Equipment** — 器材
10. **ActivityApplication** — 活動申請
11. **VenueBooking** — 場地預約
12. **VenueBookingRecurrence** — 重複預約
13. **EquipmentLoan** — 器材借用
14. **EquipmentLoanDetail** — 借用明細
15. **EquipmentLoanRecurrence** — 重複借用
16. **EquipmentLoanRecurrenceDetail** — 重複借用明細
17. **ConflictNegotiation** — 衝突協調
18. **CoordinationMessage** — 協調訊息
19. **VenueCoordination** — 場協大會登記
20. **RepairRequest** — 報修
21. **RepairRequestPhoto** — 報修照片
22. **AppealCase** — 申訴
23. **LaborServiceApplication** — 勞動服務
24. **ViolationPointLog** — 違規記點日誌
25. **OperationLog** — 操作日誌
26. **Announcement** — 公告
27. **StatsSummary** — 統計摘要

### 角色權限矩陣

| 功能 | admin | officer | professor | student | staff |
|------|:-----:|:-------:|:---------:|:-------:|:-----:|
| 儀表板 | ✅ | ✅ | ✅ | ✅ | ✅ |
| 活動申請 | ✅ | ✅ | ✅ | ❌ | ✅ |
| 場地預約 | ✅ | ✅ | ✅ | ✅ | ✅ |
| 器材借用 | ✅ | ✅ | ✅ | ❌ | ✅ |
| 場協大會 | ✅ | ✅ | ❌ | ❌ | ❌ |
| 衝突協調 | ✅ | ✅ | ❌ | ❌ | ❌ |
| 報修管理 | ✅ | ✅ | ❌ | ✅ | ✅ |
| 申訴管理 | ✅ | ✅ | ❌ | ✅ | ❌ |
| FAQ / AI | ✅ | ✅ | ✅ | ✅ | ✅ |
| 統計報表 | ✅ | ❌ | ❌ | ❌ | ❌ |
| 使用者管理 | ✅ | ❌ | ❌ | ❌ | ❌ |
| 違規記點 | ✅ | ✅ | ❌ | ✅ | ❌ |
| 勞動服務 | ✅ | ✅ | ❌ | ✅ | ❌ |
| 單位管理 | ✅ | ✅ | ❌ | ❌ | ❌ |
| 個人中心 | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 本地開發

```bash
# 安裝依賴
npm install

# 初始化 DB
npx wrangler d1 migrations apply DB --local
npx wrangler d1 execute DB --local --file=./seed.sql

# 建置
npm run build

# 啟動開發伺服器
npx wrangler pages dev dist --d1=DB --local --ip 0.0.0.0 --port 3000
```

### 啟用 GitHub Models AI
```bash
# 在 .dev.vars 中設定
echo "GITHUB_TOKEN=your-github-pat" > .dev.vars
```
設定後 AI 聊天將使用 GPT-4o；未設定則使用本地 RAG 知識庫回覆。

---

## E2E 測試結果

- **測試端點數**: 131 個測試案例
- **HTTP 狀態碼**: 全部 API 回傳正確 HTTP Code（200/201/400/401/403/404/409）
- **覆蓋範圍**: Auth、Facilities、Equipment、Activities、VenueBookings、Repairs、Appeals、Announcements、Stats、Users、Violations、Labor、Units、Conflicts、VenueCoordination、FAQ、AI、Frontend SPA
- **已修復問題**: btoa Unicode、cert-types 路由衝突、DB binding 不匹配

---

## 部署狀態

- **平台**: Cloudflare Pages (Sandbox Demo)
- **框架**: Hono v4 + TypeScript
- **資料庫**: Cloudflare D1 (SQLite)
- **版本**: v3.2.0
- **最後更新**: 2026-05-05
