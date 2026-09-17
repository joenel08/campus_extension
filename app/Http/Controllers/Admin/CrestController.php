<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Publication.php';

class CrestController extends \Controller
{
    private $publicationModel;
    private $coverDir = 'uploads/crest/covers/';
    private $pdfDir = 'uploads/crest/pdfs/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->publicationModel = new \Publication($pdo);

        // Create directories if not exist
        if (!is_dir($this->coverDir)) mkdir($this->coverDir, 0777, true);
        if (!is_dir($this->pdfDir)) mkdir($this->pdfDir, 0777, true);
    }

    // List all publications
    public function index()
    {
        $publications = $this->publicationModel->getAll();
        $this->render('crest/index', ['publications' => $publications]);
    }

    // Show create form
    public function create()
    {
        $this->render('crest/create');
    }

    // Store new publication
    public function store()
    {
        $title = trim($_POST['title'] ?? '');
        $year = (int) ($_POST['year'] ?? date('Y'));
        $category = $_POST['category'] ?? 'research_journal';
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $cover_image = '';
        $pdf_file = '';

        if ($_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $cover_image = $this->uploadFile($_FILES['cover_image'], $this->coverDir);
        }
        if ($_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $pdf_file = $this->uploadFile($_FILES['pdf_file'], $this->pdfDir);
        }

        if ($title && $year) {
            $this->publicationModel->create($title, $year, $category, $cover_image, $pdf_file, $description, $status);
            $_SESSION['success'] = 'Publication added successfully.';
        } else {
            $_SESSION['error'] = 'Title and year are required.';
        }
        header('Location: /admin/crest');
        exit;
    }

    // Show edit form
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $publication = $this->publicationModel->find($id);
        if (!$publication) {
            $_SESSION['error'] = 'Publication not found.';
            header('Location: /admin/crest');
            exit;
        }
        $this->render('crest/edit', ['publication' => $publication]);
    }

    // Update publication
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $publication = $this->publicationModel->find($id);
        if (!$publication) {
            $_SESSION['error'] = 'Publication not found.';
            header('Location: /admin/crest');
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $year = (int) ($_POST['year'] ?? date('Y'));
        $category = $_POST['category'] ?? 'research_journal';
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'draft';

        $cover_image = $publication['cover_image'];
        $pdf_file = $publication['pdf_file'];

        // Delete old files if new ones are uploaded
        if ($_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            if ($cover_image && file_exists($cover_image)) unlink($cover_image);
            $cover_image = $this->uploadFile($_FILES['cover_image'], $this->coverDir);
        }
        if ($_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            if ($pdf_file && file_exists($pdf_file)) unlink($pdf_file);
            $pdf_file = $this->uploadFile($_FILES['pdf_file'], $this->pdfDir);
        }

        if ($title && $year) {
            $this->publicationModel->update($id, $title, $year, $category, $cover_image, $pdf_file, $description, $status);
            $_SESSION['success'] = 'Publication updated.';
        } else {
            $_SESSION['error'] = 'Title and year are required.';
        }
        header('Location: /admin/crest');
        exit;
    }

    // Delete publication
    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $publication = $this->publicationModel->find($id);
        if ($publication) {
            if ($publication['cover_image'] && file_exists($publication['cover_image'])) unlink($publication['cover_image']);
            if ($publication['pdf_file'] && file_exists($publication['pdf_file'])) unlink($publication['pdf_file']);
            $this->publicationModel->delete($id);
            $_SESSION['success'] = 'Publication deleted.';
        } else {
            $_SESSION['error'] = 'Publication not found.';
        }
        header('Location: /admin/crest');
        exit;
    }

    // Toggle status
    public function toggleStatus()
    {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'draft';
        $this->publicationModel->toggleStatus($id, $status);
        $_SESSION['success'] = 'Status updated.';
        header('Location: /admin/crest');
        exit;
    }

    private function uploadFile($file, $targetDir)
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $destination = $targetDir . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return $destination;
    }
}