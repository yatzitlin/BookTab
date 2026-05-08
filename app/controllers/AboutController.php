<?php

require_once __DIR__ . '/BaseController.php';

class AboutController extends BaseController {
    private $thongTinModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->thongTinModel = $this->loadModel('ThongTinModel');
    }

    public function getAboutPage() {
        return $this->thongTinModel->getFirstByLoai('about');
    }

    public function getAboutForEdit() {
        return $this->thongTinModel->getFirstByLoai('about');
    }

    public function updateAboutPage($noiDung) {
        $about = $this->thongTinModel->getFirstByLoai('about');
        if (!$about) {
            return ['error' => 'Không tìm thấy nội dung Giới thiệu.'];
        }
        $this->thongTinModel->updateNoiDung($about['ma_thong_tin'], $noiDung);
        return ['success' => true];
    }
}
