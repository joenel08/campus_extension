<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Official.php';

class OfficialController extends \Controller
{
    private $officialModel;
    private $uploadDir = 'uploads/officials/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->officialModel = new \Official($pdo);

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // List all officials grouped by category
    public function index()
    {
        $officials = $this->officialModel->getAll();
        // Group by category for display
        $groups = [];
        foreach ($officials as $official) {
            $groups[$official['category']][] = $official;
        }
        $this->render('officials/index', ['groups' => $groups]);
    }

    // Show create form
    public function create()
    {
        $this->render('officials/create');
    }

    // Store new official
    public function store()
    {
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $category = $_POST['category'] ?? 'administrative';
        $status = $_POST['status'] ?? 'draft';
        $display_order = (int) ($_POST['display_order'] ?? 0);
        $image = '';

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        if ($name && $position && $email) {
            $this->officialModel->create($name, $position, $email, $category, $image, $status, $display_order);
            $_SESSION['success'] = 'Official added successfully.';
        } else {
            $_SESSION['error'] = 'Name, position, and email are required.';
        }
        header('Location: /admin/officials');
        exit;
    }

    // Show edit form
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $official = $this->officialModel->find($id);
        if (!$official) {
            $_SESSION['error'] = 'Official not found.';
            header('Location: /admin/officials');
            exit;
        }
        $this->render('officials/edit', ['official' => $official]);
    }

    // Update official
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $category = $_POST['category'] ?? 'administrative';
        $status = $_POST['status'] ?? 'draft';
        $display_order = (int) ($_POST['display_order'] ?? 0);

        $official = $this->officialModel->find($id);
        if (!$official) {
            $_SESSION['error'] = 'Official not found.';
            header('Location: /admin/officials');
            exit;
        }

        $image = $official['image'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($image && file_exists($image)) {
                unlink($image);
            }
            $image = $this->uploadImage($_FILES['image']);
        }

        if ($name && $position && $email) {
            $this->officialModel->update($id, $name, $position, $email, $category, $image, $status, $display_order);
            $_SESSION['success'] = 'Official updated successfully.';
        } else {
            $_SESSION['error'] = 'Name, position, and email are required.';
        }
        header('Location: /admin/officials');
        exit;
    }

    // Delete official
    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $official = $this->officialModel->find($id);
        if ($official) {
            if ($official['image'] && file_exists($official['image'])) {
                unlink($official['image']);
            }
            $this->officialModel->delete($id);
            $_SESSION['success'] = 'Official deleted.';
        } else {
            $_SESSION['error'] = 'Official not found.';
        }
        header('Location: /admin/officials');
        exit;
    }

    // Toggle status
    public function toggleStatus()
    {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'draft';
        $this->officialModel->toggleStatus($id, $status);
        $_SESSION['success'] = 'Status updated.';
        header('Location: /admin/officials');
        exit;
    }

    private function uploadImage($file)
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $destination = $this->uploadDir . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return $destination;
    }
}