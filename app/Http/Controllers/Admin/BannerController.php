<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Banner.php';

class BannerController extends \Controller
{
    private $bannerModel;
    private $uploadDir = 'uploads/banner/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->bannerModel = new \Banner($pdo);

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index()
    {
        $banner = $this->bannerModel->get();
        $this->render('banner/index', ['banner' => $banner]);
    }

    public function update()
    {
        $type = $_POST['type'] ?? 'image';
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $media_path = '';

        // Handle file upload if provided
        if ($_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm'];
            if (!in_array($_FILES['media']['type'], $allowed)) {
                $_SESSION['error'] = 'Invalid file type. Only images and MP4/WebM videos allowed.';
                header('Location: /admin/banner');
                exit;
            }
            $extension = pathinfo($_FILES['media']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_banner.' . $extension;
            $destination = $this->uploadDir . $filename;
            move_uploaded_file($_FILES['media']['tmp_name'], $destination);
            $media_path = $destination;
        } else {
            // Keep existing media if no new file uploaded
            $existing = $this->bannerModel->get();
            $media_path = $existing['media_path'] ?? '';
        }

        if ($media_path) {
            $this->bannerModel->update($type, $media_path, $title, $subtitle);
            $_SESSION['success'] = 'Banner updated successfully.';
        } else {
            $_SESSION['error'] = 'Please select a file or keep existing.';
        }
        header('Location: /admin/banner');
        exit;
    }
}