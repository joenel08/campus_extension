<?php
namespace Admin;

require_once __DIR__ . '/../../../models/News.php';

class NewsController extends \Controller
{
    private $newsModel;
    private $uploadDir = 'uploads/news/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->newsModel = new \News($pdo);

        // Create upload directory if not exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // List all news
    public function index()
    {
        $news = $this->newsModel->getAll();
        $this->render('news/index', ['news' => $news]);
    }

    // Show create form
    public function create()
    {
        $this->render('news/create');
    }

    // Store new news
    public function store()
    {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $image = '';

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        if ($title && $content) {
            $this->newsModel->create($title, $content, $image, $status);
            $_SESSION['success'] = 'News created successfully.';
        } else {
            $_SESSION['error'] = 'Title and content are required.';
        }
        header('Location: /admin/news');
        exit;
    }

    // Show edit form
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $news = $this->newsModel->find($id);
        if (!$news) {
            $_SESSION['error'] = 'News not found.';
            header('Location: /admin/news');
            exit;
        }
        $this->render('news/edit', ['news' => $news]);
    }

    // Update news
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $status = $_POST['status'] ?? 'draft';

        $news = $this->newsModel->find($id);
        if (!$news) {
            $_SESSION['error'] = 'News not found.';
            header('Location: /admin/news');
            exit;
        }

        $image = $news['image'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image
            if ($image && file_exists($image)) {
                unlink($image);
            }
            $image = $this->uploadImage($_FILES['image']);
        }

        if ($title && $content) {
            $this->newsModel->update($id, $title, $content, $image, $status);
            $_SESSION['success'] = 'News updated successfully.';
        } else {
            $_SESSION['error'] = 'Title and content are required.';
        }
        header('Location: /admin/news');
        exit;
    }

    // Delete news
    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $news = $this->newsModel->find($id);
        if ($news) {
            // Delete image
            if ($news['image'] && file_exists($news['image'])) {
                unlink($news['image']);
            }
            $this->newsModel->delete($id);
            $_SESSION['success'] = 'News deleted.';
        } else {
            $_SESSION['error'] = 'News not found.';
        }
        header('Location: /admin/news');
        exit;
    }

    // Toggle status (draft/published)
    public function toggleStatus()
    {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'draft';
        $this->newsModel->toggleStatus($id, $status);
        $_SESSION['success'] = 'Status updated.';
        header('Location: /admin/news');
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