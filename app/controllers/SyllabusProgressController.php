<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/SyllabusProgress.php';

class SyllabusProgressController extends Controller {
    private $model;

    public function __construct() {
        $this->model = new SyllabusProgress();
    }

    public function get($subject) {
        return $this->model->get($subject);
    }

    public function update($subject, $value) {
        $this->model->set($subject, $value);
        echo json_encode(['success' => true]);
        exit;
    }
}
