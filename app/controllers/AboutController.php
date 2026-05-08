<?php

require_once __DIR__ . '/BaseController.php';

class AboutController extends BaseController {
    private $aboutModel;

    public function __construct($dbConnection) {
        parent::__construct($dbConnection);
        $this->aboutModel = $this->loadModel('AboutModel');
    }

    public function getActiveSections() {
        return $this->aboutModel->getActiveSections();
    }
}

