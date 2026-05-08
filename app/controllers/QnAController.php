<?php

require_once __DIR__ . '/BaseController.php';

class QnAController extends BaseController {
    private $qnaModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->qnaModel = $this->loadModel('QnAModel');
    }

    public function getCategories() {
        return $this->qnaModel->getAllCategories();
    }

    public function getQuestions($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        
        // Get total counts
        $unansweredCount = $this->qnaModel->countUnansweredQuestions($categoryId);
        $answeredCount = $this->qnaModel->countQuestionsWithAnswers($categoryId);
        $totalItems = $unansweredCount + $answeredCount;
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        // Fetch both unanswered and answered questions (fetch extra to account for merged sorting)
        $fetchSize = $itemsPerPage * 3; // Fetch 3x to ensure we have enough after merge/sort
        $unanswered = $this->qnaModel->getUnansweredQuestions($categoryId, 1, $fetchSize);
        $answered = $this->qnaModel->getQuestionsWithAnswers($categoryId, 1, $fetchSize);
        
        // Merge and sort by ngay_tao DESC (newest first)
        $items = array_merge($unanswered, $answered);
        usort($items, function($a, $b) {
            $timeA = strtotime($a['ngay_tao'] ?? 0);
            $timeB = strtotime($b['ngay_tao'] ?? 0);
            return $timeB - $timeA; // DESC order
        });
        
        // Apply pagination to sorted results
        $offset = ($page - 1) * $itemsPerPage;
        $paginatedItems = array_slice($items, $offset, $itemsPerPage);

        // attach images for questions and answers
        $questionIds = [];
        $answerIds = [];
        foreach ($paginatedItems as $it) {
            $questionIds[] = $it['ma_cau_hoi'];
            if (!empty($it['ma_cau_tra_loi'])) $answerIds[] = $it['ma_cau_tra_loi'];
        }

        $qImages = $this->qnaModel->getImagesForQuestions($questionIds);
        $aImages = $this->qnaModel->getImagesForAnswers($answerIds);

        foreach ($paginatedItems as &$it) {
            $qid = $it['ma_cau_hoi'];
            $aid = $it['ma_cau_tra_loi'] ?? null;
            $it['images'] = $qImages[$qid] ?? [];
            $it['answer_images'] = ($aid && isset($aImages[$aid])) ? $aImages[$aid] : [];
        }

        return [
            'items' => $paginatedItems,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    public function getFaqItems($categoryId = 0, $page = 1, $itemsPerPage = 10) {
        $page = max(1, (int)$page);
        $itemsPerPage = max(1, (int)$itemsPerPage);
        
        // Get total count of FAQ items (answered questions only)
        $totalItems = $this->qnaModel->countPublicFaqItems($categoryId);
        $totalPages = ceil($totalItems / $itemsPerPage);
        
        // For FAQ, only return answered items from getPublicFaqItems
        $items = $this->qnaModel->getPublicFaqItems($categoryId, $page, $itemsPerPage);

        $questionIds = [];
        $answerIds = [];
        foreach ($items as $it) {
            $questionIds[] = $it['ma_cau_hoi'];
            if (!empty($it['ma_cau_tra_loi'])) $answerIds[] = $it['ma_cau_tra_loi'];
        }

        $qImages = $this->qnaModel->getImagesForQuestions($questionIds);
        $aImages = $this->qnaModel->getImagesForAnswers($answerIds);

        foreach ($items as &$it) {
            $qid = $it['ma_cau_hoi'];
            $aid = $it['ma_cau_tra_loi'] ?? null;
            $it['images'] = $qImages[$qid] ?? [];
            $it['answer_images'] = ($aid && isset($aImages[$aid])) ? $aImages[$aid] : [];
        }

        return [
            'items' => $items,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage
        ];
    }

    public function createQuestion($tenCauHoi, $maLoai, $userId) {
        // Server-side validation (defense in depth)
        $tenCauHoi = trim($tenCauHoi);
        
        // Validate inputs
        if (empty($tenCauHoi) || strlen($tenCauHoi) < 10 || strlen($tenCauHoi) > 255) {
            return ['error' => 'Nội dung câu hỏi không hợp lệ (10-255 ký tự).'];
        }
        
        if ((int) $maLoai <= 0) {
            return ['error' => 'Chủ đề không hợp lệ.'];
        }

        if ((int) $userId <= 0) {
            return ['error' => 'Người dùng không hợp lệ.'];
        }

        // Attempt to create question
        $insertId = $this->qnaModel->createQuestion($tenCauHoi, $maLoai, $userId);
        if (!$insertId) {
            return ['error' => 'Không thể tạo câu hỏi. Vui lòng thử lại.'];
        }

        // Handle uploaded image files (input name: images[])
        if (!empty($_FILES['images']) && is_array($_FILES['images']['name'])) {
            $files = $_FILES['images'];
            $maxFiles = 5;
            $allowedExt = ['jpg','jpeg','png','webp'];
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $maxSize = 3 * 1024 * 1024; // 3MB per file

            // Use fixed upload path: public/upload/qna/questions
            $publicDir = dirname(__DIR__,2) . '/public/upload/qna/questions';
            if (!is_dir($publicDir)) {
                @mkdir($publicDir, 0755, true);
            }

            $uploadedCount = 0;
            for ($i = 0; $i < count($files['name']) && $uploadedCount < $maxFiles; $i++) {
                if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
                
                $origName = $files['name'][$i];
                $tmp = $files['tmp_name'][$i];
                $size = $files['size'][$i];

                // Validate file extension
                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                if (!in_array($ext, $allowedExt)) continue;
                
                // Validate file size
                if ($size > $maxSize) continue;

                // Validate MIME type
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $tmp);
                finfo_close($finfo);
                if (!in_array($mime, $allowedMimes)) continue;

                // Safe filename generation
                $safe = bin2hex(random_bytes(8)) . '_' . time() . '_' . $uploadedCount . '.' . $ext;
                $destPath = $publicDir . '/' . $safe;
                
                if (move_uploaded_file($tmp, $destPath)) {
                    $webPath = 'public/upload/qna/questions/' . $safe;
                    $imageId = $this->qnaModel->createImage($webPath, $origName, $uploadedCount);
                    if ($imageId) {
                        $this->qnaModel->linkImageToQuestion($insertId, $imageId, $uploadedCount);
                        $uploadedCount++;
                    }
                }
            }
        }

        return ['success' => true, 'id' => $insertId];
    }
}
