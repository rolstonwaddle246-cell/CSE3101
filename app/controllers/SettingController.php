<?php
require_once __DIR__ . '/../models/Setting.php';
require_once __DIR__ . '/Controller.php';

class SettingController extends Controller {

    private $settingModel;

    public function __construct() {
        $this->settingModel = new Setting();
    }

    // Fetch value (optional)
    public function get($key) {
        return $this->settingModel->get($key);
    }

    // Update value
    public function update($key, $value) {
        file_put_contents('debug.txt', "$key = $value\n", FILE_APPEND); // log
        $this->settingModel->set($key, $value);
        echo json_encode(['success' => true]);
        exit;
    }
}
