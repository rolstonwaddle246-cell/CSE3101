<?php
require_once __DIR__ . '/../models/Announcement.php';
require_once __DIR__ . '/Controller.php';

class AnnouncementController extends Controller {

    private $announcementModel;

    public function __construct() {
        $this->announcementModel = new Announcement();
    }

    public function index() {
        $announcements = $this->announcementModel->getAll();
        $this->view('admin_dashboard', ['announcements' => $announcements]);
    }

    // Handle new announcement post
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_announcement'])) {
            $text = trim($_POST['announcement']);
            
            if (!empty($text)) {
                $this->announcementModel->create($text);
            }

            header("Location: index.php?action=admin_dashboard");
            exit();
        }
    }

    public function delete($id) {
        $this->announcementModel->delete($id);
        header("Location: index.php?action=admin_dashboard");
        exit();
    }

    public function edit($id, $text) {
        $this->announcementModel->update($id, $text);
        header("Location: index.php?action=admin_dashboard");
        exit();
    }

}
?>