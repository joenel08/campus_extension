<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Official.php';
require_once __DIR__ . '/../../../models/College.php';

class OfficialController extends \Controller
{
    private $officialModel;
    private $collegeModel;
    private $uploadDir = 'uploads/officials/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->officialModel = new \Official($pdo);
        $this->collegeModel = new \College($pdo);

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index()
    {
        $officials = $this->officialModel->getAll();
        $groups = [];
        foreach ($officials as $official) {
            $groups[$official['category']][] = $official;
        }
        $this->render('officials/index', ['groups' => $groups]);
    }

    public function create()
    {
        $colleges = $this->collegeModel->getAll();
        $this->render('officials/create', ['colleges' => $colleges]);
    }

    public function store()
    {
        $name          = trim($_POST['name'] ?? '');
        $position      = trim($_POST['position'] ?? '');
        $category      = $_POST['category'] ?? 'staffs';
        $college_id    = ($category === 'college_coordinator') ? ($_POST['college_id'] ?? null) : null;
        $status        = $_POST['status'] ?? 'draft';
        $display_order = (int) ($_POST['display_order'] ?? 0);
        $image         = '';

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        // Validation
        if (!$name || !$position) {
            $_SESSION['error'] = 'Name and position are required.';
            header('Location: /admin/officials/create');
            exit;
        }

        // CEO uniqueness
        if ($category === 'ceo' && $this->officialModel->countByCategory('ceo') > 0) {
            $_SESSION['error'] = 'There is already a CEO. Only one is allowed.';
            header('Location: /admin/officials/create');
            exit;
        }

        // Director uniqueness
        if ($category === 'director' && $this->officialModel->countByCategory('director') > 0) {
            $_SESSION['error'] = 'There is already a Director. Only one is allowed.';
            header('Location: /admin/officials/create');
            exit;
        }

        // College Coordinator must have a college
        if ($category === 'college_coordinator' && !$college_id) {
            $_SESSION['error'] = 'College Coordinator must be assigned to a college.';
            header('Location: /admin/officials/create');
            exit;
        }

        $this->officialModel->create($name, $position, $category, $college_id, $image, $status, $display_order);
        $_SESSION['success'] = 'Official added successfully.';
        header('Location: /admin/officials');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $official = $this->officialModel->find($id);
        if (!$official) {
            $_SESSION['error'] = 'Official not found.';
            header('Location: /admin/officials');
            exit;
        }
        $colleges = $this->collegeModel->getAll();
        $this->render('officials/edit', ['official' => $official, 'colleges' => $colleges]);
    }

    public function update()
    {
        $id            = $_POST['id'] ?? 0;
        $name          = trim($_POST['name'] ?? '');
        $position      = trim($_POST['position'] ?? '');
    
        $category      = $_POST['category'] ?? 'staffs';
        $college_id    = ($category === 'college_coordinator') ? ($_POST['college_id'] ?? null) : null;
        $status        = $_POST['status'] ?? 'draft';
        $display_order = (int) ($_POST['display_order'] ?? 0);

        $official = $this->officialModel->find($id);
        if (!$official) {
            $_SESSION['error'] = 'Official not found.';
            header('Location: /admin/officials');
            exit;
        }

        if (!$name || !$position) {
            $_SESSION['error'] = 'Name and position are required.';
            header('Location: /admin/officials/edit?id=' . $id);
            exit;
        }

        if ($category === 'ceo' && $this->officialModel->countByCategory('ceo', $id) > 0) {
            $_SESSION['error'] = 'There is already a CEO. Only one is allowed.';
            header('Location: /admin/officials/edit?id=' . $id);
            exit;
        }

        if ($category === 'director' && $this->officialModel->countByCategory('director', $id) > 0) {
            $_SESSION['error'] = 'There is already a Director. Only one is allowed.';
            header('Location: /admin/officials/edit?id=' . $id);
            exit;
        }

        if ($category === 'college_coordinator' && !$college_id) {
            $_SESSION['error'] = 'College Coordinator must be assigned to a college.';
            header('Location: /admin/officials/edit?id=' . $id);
            exit;
        }

        $image = $official['image'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($image && file_exists($image)) {
                unlink($image);
            }
            $image = $this->uploadImage($_FILES['image']);
        }

        $this->officialModel->update($id, $name, $position, $category, $college_id, $image, $status, $display_order);
        $_SESSION['success'] = 'Official updated successfully.';
        header('Location: /admin/officials');
        exit;
    }

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