-- ============================================================
-- FJU Smart Hub - 學生自治組織資料庫擴充
-- Source: 114學年度學生自治組織一覽表 (114.07.15)
-- 日間部：12院代會 + 53系學會 = 65個
-- 進修部：1院代會 + 18系學會 = 19個
-- 合計：84個
-- ============================================================

USE fju_club;

-- ============================================================
-- 資料表 3: College (學院)
-- 將學院獨立一張表，供院代會與系學會共用
-- ============================================================
CREATE TABLE College (
    id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL COMMENT '學院中文名稱',
    PRIMARY KEY (id),
    UNIQUE KEY uq_name (name)
) ENGINE = InnoDB COMMENT = '學院';

INSERT INTO
    College (name)
VALUES ('文學院'),
    ('藝術學院'),
    ('傳播學院'),
    ('教育學院'),
    ('理工學院'),
    ('外語學院'),
    ('民生學院'),
    ('法律學院'),
    ('管理學院'),
    ('社會科學院'),
    ('醫學院'),
    ('織品學院');

-- ============================================================
-- 資料表 4: Org_Type (自治組織類型)
-- 院代會 / 系學會 / 學程學會 / 全校性
-- ============================================================
CREATE TABLE Org_Type (
    id TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL COMMENT '例如：院代會、系學會、學程學會、全校性',
    PRIMARY KEY (id),
    UNIQUE KEY uq_name (name)
) ENGINE = InnoDB COMMENT = '自治組織類型';

INSERT INTO
    Org_Type (name)
VALUES ('全校性'),
    ('院代會'),
    ('系學會'),
    ('學程學會');

-- ============================================================
-- 資料表 5: Student_Org (學生自治組織)
-- 日間部 + 進修部共 84 個
-- ============================================================
CREATE TABLE Student_Org (
    id SMALLINT UNSIGNED NOT NULL COMMENT '原始代號（001~302 日間部；202~237 進修部）',
    name VARCHAR(40) NOT NULL,
    org_type_id TINYINT UNSIGNED NOT NULL,
    college_id TINYINT UNSIGNED NULL COMMENT '所屬學院；全校性組織為 NULL',
    is_evening BIT NOT NULL DEFAULT 0 COMMENT '0=日間部 1=進修部',
    is_active BIT NOT NULL DEFAULT 1,
    PRIMARY KEY (id),
    KEY idx_college (college_id),
    KEY idx_org_type (org_type_id),
    KEY idx_is_evening (is_evening),
    CONSTRAINT fk_org_college FOREIGN KEY (college_id) REFERENCES College (id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_org_type FOREIGN KEY (org_type_id) REFERENCES Org_Type (id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE = InnoDB COMMENT = '114學年度學生自治組織';

-- ──────────────────────────────────────────────
-- 日間部 (is_evening = 0)
-- ──────────────────────────────────────────────

-- 全校性
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (1, '學生會', 1, NULL, 0),
    (2, '學生議會', 1, NULL, 0);
-- 注意：學生法庭無代號，暫不納入

-- 文學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (112, '文學院代會', 2, 1, 0),
    (8, '中文系學會', 3, 1, 0),
    (9, '歷史系學會', 3, 1, 0),
    (10, '哲學系學會', 3, 1, 0),
    (301, '社創學程學會', 4, 1, 0);

-- 藝術學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (113, '藝術學院代會', 2, 2, 0),
    (15, '音樂系學會', 3, 2, 0),
    (16, '應美系學會', 3, 2, 0),
    (105, '景觀系學會', 3, 2, 0);

-- 傳播學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (110, '傳播學院代會', 2, 3, 0),
    (130, '影傳系學會', 3, 3, 0),
    (133, '新傳系學會', 3, 3, 0),
    (134, '廣告系學會', 3, 3, 0);

-- 教育學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (108, '教育學院代會', 2, 4, 0),
    (11, '圖資系學會', 3, 4, 0),
    (14, '體育系學會', 3, 4, 0),
    (181, '領科學程學會', 4, 4, 0);

-- 理工學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (4, '理工學院代會', 2, 5, 0),
    (17, '數學系學會', 3, 5, 0),
    (18, '物理系學會', 3, 5, 0),
    (19, '化學系學會', 3, 5, 0),
    (20, '生科系學會', 3, 5, 0),
    (24, '電機系學會', 3, 5, 0),
    (25, '資工系學會', 3, 5, 0),
    (182, '醫資學程學會', 4, 5, 0),
    (197, '資安學程學會', 4, 5, 0);

-- 外語學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (5, '外語學院代會', 2, 6, 0),
    (26, '英文系學會', 3, 6, 0),
    (27, '德語系學會', 3, 6, 0),
    (28, '法文系學會', 3, 6, 0),
    (29, '西文系學會', 3, 6, 0),
    (30, '日文系學會', 3, 6, 0),
    (40, '義文系學會', 3, 6, 0),
    (302, '科創學程學會', 4, 6, 0);

-- 民生學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (120, '民生學院代會', 2, 7, 0),
    (12, '兒家系學會', 3, 7, 0),
    (21, '餐旅系學會', 3, 7, 0),
    (22, '食科系學會', 3, 7, 0),
    (145, '營養系學會', 3, 7, 0);

-- 法律學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (7, '法律學院代會', 2, 8, 0),
    (34, '法律系學會', 3, 8, 0),
    (111, '財法系學會', 3, 8, 0),
    (152, '學士後法律系學會', 3, 8, 0);

-- 管理學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (6, '管理學院代會', 2, 9, 0),
    (35, '企管系學會', 3, 9, 0),
    (36, '會計系學會', 3, 9, 0),
    (37, '統資系學會', 3, 9, 0),
    (38, '金企系學會', 3, 9, 0),
    (39, '資管系學會', 3, 9, 0);

-- 社會科學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (115, '社科學院代會', 2, 10, 0),
    (31, '社會學會', 3, 10, 0),
    (32, '社工系學會', 3, 10, 0),
    (33, '經濟系學會', 3, 10, 0),
    (121, '宗教系學會', 3, 10, 0),
    (13, '心理系學會', 3, 10, 0),
    (176, '天學學程學會', 4, 10, 0);

-- 醫學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (114, '醫學院代會', 2, 11, 0),
    (103, '公衛系學會', 3, 11, 0),
    (104, '護理系學會', 3, 11, 0),
    (122, '臨心系學會', 3, 11, 0),
    (102, '醫學系學會', 3, 11, 0),
    (135, '職治系學會', 3, 11, 0),
    (154, '呼治系學會', 3, 11, 0);

-- 織品學院
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (187, '織品學院代會', 2, 12, 0),
    (23, '織品系學會', 3, 12, 0);

-- ──────────────────────────────────────────────
-- 進修部 (is_evening = 1)
-- ──────────────────────────────────────────────
INSERT INTO
    Student_Org (
        id,
        name,
        org_type_id,
        college_id,
        is_evening
    )
VALUES (202, '進-學代會', 2, NULL, 1),
    (203, '進-中文系學會', 3, 1, 1),
    (204, '進-歷史系學會', 3, 1, 1),
    (206, '進-大傳學程學會', 4, 3, 1),
    (207, '進-運資學程學會', 4, NULL, 1),
    (208, '進-英文系學會', 3, 6, 1),
    (209, '進-日文系學會', 3, 6, 1),
    (211, '進-法律系學會', 3, 8, 1),
    (212, '進-經濟系學會', 3, 10, 1),
    (225, '進-餐旅系學會', 3, 7, 1),
    (226, '進-應美系學會', 3, 2, 1),
    (228, '進-文創學程學會', 4, NULL, 1),
    (230, '進-運管學程學會', 4, NULL, 1),
    (231, '進-商管學程學會', 4, NULL, 1),
    (232, '進-軟創學程學會', 4, NULL, 1),
    (233, '進-長照學程學會', 4, NULL, 1),
    (234, '進-文服學程學會', 4, NULL, 1),
    (236, '進-室設學程學會', 4, NULL, 1),
    (237, '進-服飾學程學會', 4, NULL, 1);

-- ============================================================
-- 驗證查詢
-- ============================================================

-- 1. 確認總筆數（應為 84）
-- SELECT COUNT(*) AS total FROM Student_Org;

-- 2. 日間部 / 進修部分別統計
-- SELECT is_evening, COUNT(*) AS cnt FROM Student_Org GROUP BY is_evening;

-- 3. 各學院自治組織數量
-- SELECT c.name AS college, COUNT(o.id) AS cnt
-- FROM College c
-- JOIN Student_Org o ON o.college_id = c.id
-- GROUP BY c.id, c.name
-- ORDER BY cnt DESC;

-- 4. 跨表查詢：列出醫學院所有自治組織
-- SELECT o.id, o.name, t.name AS type, o.is_evening
-- FROM Student_Org o
-- JOIN Org_Type t  ON t.id = o.org_type_id
-- JOIN College  c  ON c.id = o.college_id
-- WHERE c.name = '醫學院'
-- ORDER BY o.org_type_id, o.id;