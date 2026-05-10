<?php
class HomeController extends BaseController {

    public function index() {
        $settingsModel = $this->loadModel('SettingsModel');

        global $view_content, $pageTitle;

        $view_content = '../app/views/pages/Home.php';
        $pageTitle = 'Trang chủ';

        $settings = $settingsModel->getAll();
    }
}
?>