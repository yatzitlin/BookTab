<?php
require_once '../app/models/CompanyContactModel.php';

class CompanyContactController extends BaseController
{
    private $contactModel;

    public function __construct($dbConnection)
    {
        parent::__construct($dbConnection);

        // dùng BaseController::loadModel()
        $this->contactModel = $this->loadModel('CompanyContactModel');
    }

    // Hiển thị contact (public hoặc admin xem)
    public function index()
    {
        $contact = $this->contactModel->getContactInfo();

        $this->view('contact/index', [
            'contact' => $contact
        ]);
    }

    // Form chỉnh sửa (admin)
    public function edit()
    {
        $contact = $this->contactModel->getContactInfo();

        $this->view('admin/contact_edit', [
            'contact' => $contact
        ]);
    }

    // Xử lý update
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $phone   = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $email   = $_POST['email'] ?? '';

            $result = $this->contactModel->updateContactInfo($phone, $address, $email);

            if ($result) {
                $_SESSION['success'] = "Cập nhật thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }

            header("Location: index.php?controller=contact&action=edit");
            exit;
        }
    }
}
?>