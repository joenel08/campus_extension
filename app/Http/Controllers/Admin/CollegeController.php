<?php
namespace Admin;

require_once __DIR__ . '/../../../models/College.php';

class CollegeController extends \Controller
{
    private $collegeModel;

    public function __construct()
    {
        parent::__construct(); // session & role check
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->collegeModel = new \College($pdo);
    }

    public function index()
    {
        $colleges = $this->collegeModel->getAll();
        $this->render('colleges', ['colleges' => $colleges]);
    }

    public function store()
    {
        $abbreviation = trim($_POST['abbreviation'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($abbreviation && $description) {
            $this->collegeModel->create($abbreviation, $description);
            $_SESSION['success'] = 'College added successfully.';
        } else {
            $_SESSION['error'] = 'All fields are required.';
        }
        header('Location: /admin/colleges');
        exit;
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $abbreviation = trim($_POST['abbreviation'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($id && $abbreviation && $description) {
            $this->collegeModel->update($id, $abbreviation, $description);
            $_SESSION['success'] = 'College updated successfully.';
        } else {
            $_SESSION['error'] = 'All fields are required.';
        }
        header('Location: /admin/colleges');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->collegeModel->delete($id);
            $_SESSION['success'] = 'College deleted successfully.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/colleges');
        exit;
    }
}