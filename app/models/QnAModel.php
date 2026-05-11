<?php

class QnAModel {
    private $conn;
    private $tableExistsCache = [];

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    private function tableExists($tableName) {
        if (isset($this->tableExistsCache[$tableName])) {
            return $this->tableExistsCache[$tableName];
        }

        $sql = "SELECT COUNT(*) AS total
                FROM information_schema.TABLES
                WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tableName]);
        $exists = ((int) (($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0)) > 0;
        $this->tableExistsCache[$tableName] = $exists;

        return $exists;
    }

    public function getAllCategories() {
        $sql = "SELECT ma_loai, ten_loai, so_thu_tu FROM loai_cau_hoi ORDER BY so_thu_tu ASC, ten_loai ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionsWithAnswers($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;

        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.ma_loai, lch.ten_loai,
                       nd.ho_va_ten_dem, nd.ten, ch.ngay_tao,
                       ctr.ma_cau_tra_loi, ctr.noi_dung AS cau_tra_loi, ctr.ngay_dang,
                       ngu_ad.ho_va_ten_dem AS admin_ho_va_ten_dem, ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd ON nd.userid = ch.userid
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                LEFT JOIN administrator adm2 ON adm2.userid = ctr.administrator_userid
                LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm2.userid
                WHERE ch.trang_thai = 'da_tra_loi' AND ch.is_faq = 'No'";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY ch.ngay_tao DESC LIMIT " . (int)$itemsPerPage . " OFFSET " . (int)$offset;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countQuestionsWithAnswers($categoryId = 0) {
        $sql = "SELECT COUNT(DISTINCT ch.ma_cau_hoi) as total
                FROM cau_hoi ch
                WHERE ch.trang_thai = 'da_tra_loi' AND ch.is_faq = 'No'";
        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)(($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }

    public function getUnansweredQuestions($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;

        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.ma_loai, lch.ten_loai,
                       nd.ho_va_ten_dem, nd.ten, ch.ngay_tao,
                       NULL AS ma_cau_tra_loi, NULL AS cau_tra_loi, NULL AS ngay_dang,
                       NULL AS admin_ho_va_ten_dem, NULL AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd ON nd.userid = ch.userid
                WHERE ch.trang_thai = 'chua_tra_loi' AND ch.is_faq = 'No'";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY ch.ngay_tao DESC LIMIT " . (int)$itemsPerPage . " OFFSET " . (int)$offset;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countUnansweredQuestions($categoryId = 0) {
        $sql = "SELECT COUNT(ch.ma_cau_hoi) as total
                FROM cau_hoi ch
                WHERE ch.trang_thai = 'chua_tra_loi' AND ch.is_faq = 'No'";
        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)(($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }


    public function getPublicFaqItems($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;

        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.ma_loai, lch.ten_loai, ch.ngay_tao,
                       ctr.ma_cau_tra_loi, ctr.noi_dung AS cau_tra_loi, ctr.ngay_dang,
                       ngu_ad.ho_va_ten_dem AS admin_ho_va_ten_dem, ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                LEFT JOIN administrator adm2 ON adm2.userid = ctr.administrator_userid
                LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm2.userid
                WHERE ch.trang_thai != 'da_an' AND ch.is_faq = 'Yes'";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY ch.ngay_tao DESC LIMIT " . (int)$itemsPerPage . " OFFSET " . (int)$offset;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPublicFaqItems($categoryId = 0) {
        $sql = "SELECT COUNT(DISTINCT ch.ma_cau_hoi) as total
                FROM cau_hoi ch
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                WHERE ch.trang_thai != 'da_an' AND ch.is_faq = 'Yes'";
        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)(($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }

    public function getMyQuestions($userId, $page = 1, $perPage = 10) {
        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.trang_thai, ch.ma_loai,
                       lch.ten_loai, ch.ngay_tao,
                       ctr.ma_cau_tra_loi, ctr.noi_dung AS cau_tra_loi, ctr.ngay_dang,
                       ngu_ad.ho_va_ten_dem AS admin_ho_va_ten_dem, ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                LEFT JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                LEFT JOIN administrator adm ON adm.userid = ctr.administrator_userid
                LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm.userid
                WHERE ch.userid = ? AND ch.is_faq = 'No'
                ORDER BY ch.ngay_tao DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $userId, PDO::PARAM_INT);
        $stmt->bindValue(2, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countMyQuestions($userId) {
        $sql = "SELECT COUNT(ma_cau_hoi) as total FROM cau_hoi WHERE userid = ? AND is_faq = 'No'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$userId]);
        return (int)(($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }

    public function createQuestion($tenCauHoi, $maLoai, $userId, $isFaq = 'No') {
        $sql = "INSERT INTO cau_hoi (ten_cau_hoi, trang_thai, is_faq, ma_loai, userid)
                VALUES (?, 'cho_duyet', ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$tenCauHoi, $isFaq === 'Yes' ? 'Yes' : 'No', $maLoai, $userId]);
        if ($success) {
            return (int)$this->conn->lastInsertId();
        }
        return false;
    }

    public function createImage($filePath, $fileName = null) {
        $sql = "INSERT INTO anh (url_anh, ten_file) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$filePath, $fileName]);
        return $success ? (int)$this->conn->lastInsertId() : false;
    }

    public function linkImageToQuestion($cauHoiId, $anhId, $sortOrder = 0) {
        $sql = "INSERT INTO anh_cau_hoi (ma_cau_hoi, ma_anh, so_thu_tu) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cauHoiId, $anhId, $sortOrder]);
    }

    public function getImagesForQuestions(array $cauHoiIds) {
        if (empty($cauHoiIds)) return [];
        $placeholders = implode(',', array_fill(0, count($cauHoiIds), '?'));
        $sql = "SELECT acq.ma_cau_hoi, a.ma_anh, a.url_anh, a.ten_file, acq.so_thu_tu
                FROM anh_cau_hoi acq
                INNER JOIN anh a ON a.ma_anh = acq.ma_anh
                WHERE acq.ma_cau_hoi IN ($placeholders)
                ORDER BY acq.so_thu_tu ASC, a.ma_anh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($cauHoiIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $map = [];
        foreach ($rows as $r) {
            $id = $r['ma_cau_hoi'];
            if (!isset($map[$id])) $map[$id] = [];
            $map[$id][] = $r;
        }
        return $map;
    }

    public function getAllQuestionsAdmin($page = 1, $perPage = 15, $categoryId = 0) {
        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.trang_thai, ch.ma_loai,
                       lch.ten_loai,
                       nd.ho_va_ten_dem AS user_ho_ten_dem, nd.ten AS user_ten,
                       ch.ngay_tao
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd ON nd.userid = ch.userid
                WHERE ch.is_faq = 'No'";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }

        $sql .= " ORDER BY ch.ngay_tao DESC
                  LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllQuestionsAdmin($categoryId = 0) {
        $sql = "SELECT COUNT(ch.ma_cau_hoi) as total
                FROM cau_hoi ch
                WHERE ch.is_faq = 'No'";
        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)(($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }

    public function getQuestionDetailAdmin($id) {
        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.trang_thai, ch.is_faq, ch.ma_loai,
                       COALESCE(lch.ten_loai, '(Chủ đề đã xóa)') AS ten_loai,
                       nd.ho_va_ten_dem AS user_ho_ten_dem, nd.ten AS user_ten, nd.userid AS user_id,
                       ch.ngay_tao,
                       ctr.ma_cau_tra_loi, ctr.noi_dung AS cau_tra_loi, ctr.ngay_dang AS ngay_tra_loi,
                       ctr.administrator_userid,
                       ngu_ad.ho_va_ten_dem AS admin_ho_ten_dem, ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                LEFT JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                LEFT JOIN nguoi_dung nd ON nd.userid = ch.userid
                LEFT JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                LEFT JOIN administrator adm ON adm.userid = ctr.administrator_userid
                LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm.userid
                WHERE ch.ma_cau_hoi = ?
                GROUP BY ch.ma_cau_hoi";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getFaqItemsAdmin($page = 1, $perPage = 15, $categoryId = 0) {
        $page = max(1, (int)$page);
        $perPage = max(1, (int)$perPage);
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT ch.ma_cau_hoi, ch.ten_cau_hoi, ch.trang_thai, ch.ma_loai,
                       lch.ten_loai, ch.ngay_tao,
                       nd_asker.ho_va_ten_dem AS user_ho_ten_dem, nd_asker.ten AS user_ten,
                       ctr.ma_cau_tra_loi, ctr.noi_dung AS cau_tra_loi, ctr.ngay_dang,
                       ngu_ad.ho_va_ten_dem AS admin_ho_ten_dem, ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd_asker ON nd_asker.userid = ch.userid
                LEFT JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                LEFT JOIN administrator adm2 ON adm2.userid = ctr.administrator_userid
                LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm2.userid
                WHERE ch.is_faq = 'Yes'";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY ch.ngay_tao DESC LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countFaqItemsAdmin($categoryId = 0) {
        $sql = "SELECT COUNT(ch.ma_cau_hoi) as total
                FROM cau_hoi ch
                WHERE ch.is_faq = 'Yes'";
        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return (int)(($stmt->fetch(PDO::FETCH_ASSOC))['total'] ?? 0);
    }

    public function createAnswer($cauHoiId, $adminUserId, $noiDung) {
        $sql = "INSERT INTO cau_tra_loi (ma_cau_hoi, administrator_userid, noi_dung) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$cauHoiId, $adminUserId, $noiDung]);
        if ($success) {
            return (int)$this->conn->lastInsertId();
        }
        return false;
    }

    public function updateAnswer($cauTraLoiId, $noiDung) {
        $sql = "UPDATE cau_tra_loi SET noi_dung = ?, ngay_dang = NOW() WHERE ma_cau_tra_loi = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$noiDung, $cauTraLoiId]);
    }

    public function questionHasAnswer($cauHoiId) {
        $sql = "SELECT COUNT(*) FROM cau_tra_loi WHERE ma_cau_hoi = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$cauHoiId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function deleteAnswer($cauTraLoiId) {
        $sql = "DELETE FROM cau_tra_loi WHERE ma_cau_tra_loi = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cauTraLoiId]);
    }

    public function deleteQuestion($cauHoiId) {
        $sql = "DELETE FROM cau_hoi WHERE ma_cau_hoi = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cauHoiId]);
    }

    public function updateQuestionStatus($id, $trangThai) {
        $allowed = ['cho_duyet', 'chua_tra_loi', 'da_tra_loi', 'da_an'];
        if (!in_array($trangThai, $allowed)) return false;
        $sql = "UPDATE cau_hoi SET trang_thai = ? WHERE ma_cau_hoi = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$trangThai, $id]);
    }

    public function getCategoryById($id) {
        $sql = "SELECT ma_loai, ten_loai, so_thu_tu FROM loai_cau_hoi WHERE ma_loai = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getCategoryByName($tenLoai) {
        $sql = "SELECT ma_loai, ten_loai, so_thu_tu FROM loai_cau_hoi WHERE ten_loai = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$tenLoai]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createCategory($tenLoai, $soThuTu = 0) {
        if ($soThuTu <= 0) {
            $sql = "SELECT COALESCE(MAX(so_thu_tu), 0) + 1000 AS next_pos FROM loai_cau_hoi";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $soThuTu = (int)(($stmt->fetch(PDO::FETCH_ASSOC))['next_pos'] ?? 1000);
        }
        $sql = "INSERT INTO loai_cau_hoi (ten_loai, so_thu_tu) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tenLoai, $soThuTu]);
    }

    public function updateCategory($id, $tenLoai, $soThuTu) {
        $sql = "UPDATE loai_cau_hoi SET ten_loai = ?, so_thu_tu = ? WHERE ma_loai = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$tenLoai, $soThuTu, $id]);
    }

    public function deleteCategory($id) {
        $sql = "DELETE FROM loai_cau_hoi WHERE ma_loai = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function reorderCategories(array $order) {
        $sql = "UPDATE loai_cau_hoi SET so_thu_tu = ? WHERE ma_loai = ?";
        $stmt = $this->conn->prepare($sql);
        foreach ($order as $index => $id) {
            $stmt->execute([($index + 1) * 1000, (int)$id]);
        }
    }
}
