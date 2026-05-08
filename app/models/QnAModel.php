<?php

class QnAModel {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getAllCategories() {
        $sql = "SELECT ma_loai, ten_loai
                FROM loai_cau_hoi
                ORDER BY ten_loai ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getQuestionsWithAnswers($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        $sql = "SELECT ch.ma_cau_hoi,
                       ch.ten_cau_hoi,
                       ch.ma_loai,
                       lch.ten_loai,
                       nd.ho_va_ten_dem,
                       nd.ten,
                       ch.ngay_tao,
                          ctr.ma_cau_tra_loi,
                          ctr.noi_dung AS cau_tra_loi,
                          ctr.ngay_dang,
                          ngu_ad.ho_va_ten_dem AS admin_ho_va_ten_dem,
                          ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd ON nd.userid = ch.userid
                                LEFT JOIN administrator ad ON ad.userid = ch.userid
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                LEFT JOIN administrator adm2 ON adm2.userid = ctr.administrator_userid
                LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm2.userid
                                WHERE ch.is_active = 1
                                    AND ad.userid IS NULL";

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
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd ON nd.userid = ch.userid
                LEFT JOIN administrator ad ON ad.userid = ch.userid
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                WHERE ch.is_active = 1 AND ad.userid IS NULL";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    public function getPublicFaqItems($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        $sql = "SELECT ch.ma_cau_hoi,
                       ch.ten_cau_hoi,
                       ch.ma_loai,
                       lch.ten_loai,
                       ch.ngay_tao,
                  ctr.ma_cau_tra_loi,
                  ctr.noi_dung AS cau_tra_loi,
                  ctr.ngay_dang,
                  ngu_ad.ho_va_ten_dem AS admin_ho_va_ten_dem,
                  ngu_ad.ten AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN administrator ad ON ad.userid = ch.userid
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
              LEFT JOIN administrator adm2 ON adm2.userid = ctr.administrator_userid
              LEFT JOIN nguoi_dung ngu_ad ON ngu_ad.userid = adm2.userid
                WHERE ch.is_active = 1";

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
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN administrator ad ON ad.userid = ch.userid
                INNER JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                WHERE ch.is_active = 1";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    public function createQuestion($tenCauHoi, $maLoai, $userId) {
        $sql = "INSERT INTO cau_hoi (ten_cau_hoi, is_active, ma_loai, userid)
                VALUES (?, 0, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$tenCauHoi, $maLoai, $userId]);
        if ($success) {
            return (int)$this->conn->lastInsertId();
        }
        return false;
    }

    public function createImage($filePath, $fileName = null, $sortOrder = 0) {
        $sql = "INSERT INTO anh (url_anh, ten_file, so_thu_tu)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $success = $stmt->execute([$filePath, $fileName, $sortOrder]);
        if ($success) {
            return (int)$this->conn->lastInsertId();
        }
        return false;
    }

    public function linkImageToQuestion($cauHoiId, $anhId, $sortOrder = 0) {
        $sql = "INSERT INTO anh_cau_hoi (ma_cau_hoi, ma_anh, so_thu_tu)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cauHoiId, $anhId, $sortOrder]);
    }

    public function linkImageToAnswer($cauTraLoiId, $anhId, $sortOrder = 0) {
        $sql = "INSERT INTO anh_cau_tra_loi (ma_cau_tra_loi, ma_anh, so_thu_tu)
                VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$cauTraLoiId, $anhId, $sortOrder]);
    }

    public function getImagesForQuestions(array $cauHoiIds) {
        if (empty($cauHoiIds)) return [];
        $placeholders = implode(',', array_fill(0, count($cauHoiIds), '?'));
        $sql = "SELECT acq.ma_cau_hoi, a.ma_anh, a.url_anh, a.ten_file, COALESCE(acq.so_thu_tu, a.so_thu_tu) AS so_thu_tu
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

    public function getImagesForAnswers(array $cauTraLoiIds) {
        if (empty($cauTraLoiIds)) return [];
        $placeholders = implode(',', array_fill(0, count($cauTraLoiIds), '?'));
        $sql = "SELECT actl.ma_cau_tra_loi, a.ma_anh, a.url_anh, a.ten_file, COALESCE(actl.so_thu_tu, a.so_thu_tu) AS so_thu_tu
            FROM anh_cau_tra_loi actl
            INNER JOIN anh a ON a.ma_anh = actl.ma_anh
            WHERE actl.ma_cau_tra_loi IN ($placeholders)
            ORDER BY actl.so_thu_tu ASC, a.ma_anh ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($cauTraLoiIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $map = [];
        foreach ($rows as $r) {
            $id = $r['ma_cau_tra_loi'];
            if (!isset($map[$id])) $map[$id] = [];
            $map[$id][] = $r;
        }
        return $map;
    }

    public function getUnansweredQuestions($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        $offset = ($page - 1) * $itemsPerPage;
        $sql = "SELECT ch.ma_cau_hoi,
                       ch.ten_cau_hoi,
                       ch.ma_loai,
                       lch.ten_loai,
                       nd.ho_va_ten_dem,
                       nd.ten,
                       ch.ngay_tao,
                       NULL AS ma_cau_tra_loi,
                       NULL AS cau_tra_loi,
                       NULL AS ngay_dang,
                       NULL AS admin_ho_va_ten_dem,
                       NULL AS admin_ten
                FROM cau_hoi ch
                INNER JOIN loai_cau_hoi lch ON ch.ma_loai = lch.ma_loai
                INNER JOIN nguoi_dung nd ON nd.userid = ch.userid
                LEFT JOIN administrator ad ON ad.userid = ch.userid
                LEFT JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                WHERE ch.is_active = 1
                    AND ad.userid IS NULL
                    AND ctr.ma_cau_tra_loi IS NULL";

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
                LEFT JOIN administrator ad ON ad.userid = ch.userid
                LEFT JOIN cau_tra_loi ctr ON ctr.ma_cau_hoi = ch.ma_cau_hoi
                WHERE ch.is_active = 1 AND ad.userid IS NULL AND ctr.ma_cau_tra_loi IS NULL";

        $params = [];
        if ($categoryId > 0) {
            $sql .= " AND ch.ma_loai = ?";
            $params[] = $categoryId;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }
}
