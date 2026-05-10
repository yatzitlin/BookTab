<?php

require_once __DIR__ . '/BaseController.php';

class AboutController extends BaseController {
    private $thongTinModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->thongTinModel = $this->loadModel('ThongTinModel');
    }

    public function getAboutPage() {
        $about = $this->thongTinModel->getFirstByLoai('about');
        if (!$about) return null;
        $about['noi_dung'] = $this->thongTinModel->getFirstNoiDung('about');
        return $about;
    }

    public function getAboutForEdit() {
        return $this->getAboutPage();
    }

    public function updateAboutPage($noiDung) {
        $about = $this->thongTinModel->getFirstByLoai('about');
        if (!$about) {
            return ['error' => 'Không tìm thấy nội dung Giới thiệu.'];
        }
        $this->thongTinModel->replaceChiTiet($about['ma_thong_tin'], [['noi_dung' => $noiDung]]);
        return ['success' => true];
    }
}
