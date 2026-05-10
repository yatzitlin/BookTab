<?php

class AdminController extends BaseController {

    // Hiển thị danh sách liên hệ
    public function contacts() {

        $contactModel = $this->loadModel('ContactModel');

        // Lấy toàn bộ dữ liệu từ bảng lien_he
        $contacts = $contactModel->getAll();

        // Trang hiện tại
        $admin_action = 'contact';

        // Load layout admin
        require_once "../app/views/admin/adminLayout.php";
    }

    public function updateContactStatus() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($id && $status) {
                $contactModel = $this->loadModel('ContactModel');
                $contactModel->updateStatus($id, $status);
            }
        }

        header("Location: index.php?page=admin&admin_action=contact");
        exit;
    }

    // Xóa liên hệ
    public function deleteContact() {

        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            $contactModel = $this->loadModel('ContactModel');

            $contactModel->delete($id);
        }

        header("Location: index.php?page=admin&admin_action=contact");
        exit;
    }
}

?>