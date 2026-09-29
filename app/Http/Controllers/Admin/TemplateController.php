<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Template.php';

class TemplateController extends \Controller
{
    private $templateModel;
    private $uploadDir = 'uploads/templates/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->templateModel = new \Template($pdo);

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index()
    {
        $templates = $this->templateModel->getAll();
        $this->render('templates/index', ['templates' => $templates]);
    }

    public function store()
    {
        $title         = trim($_POST['title'] ?? '');
        $template_code = trim($_POST['template_code'] ?? '');
        $description   = trim($_POST['description'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        $status        = $_POST['status'] ?? 'active';

        $attached_file = null;
        $file_size     = null;
        $file_type     = null;

        if (isset($_FILES['attached_file']) && $_FILES['attached_file']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->uploadFile($_FILES['attached_file']);
            if ($uploaded) {
                $attached_file = $uploaded['path'];
                $file_size     = $uploaded['size'];
                $file_type     = $uploaded['type'];
            }
        }

        if ($title && $template_code) {
            $this->templateModel->create(
                $title, $template_code, $description,
                $attached_file, $file_size, $file_type,
                $display_order, $status
            );
            $_SESSION['success'] = 'Template added successfully.';
        } else {
            $_SESSION['error'] = 'Title and Template Code are required.';
        }

        header('Location: /admin/templates');
        exit;
    }

    public function update()
    {
        $id            = $_POST['id'] ?? 0;
        $title         = trim($_POST['title'] ?? '');
        $template_code = trim($_POST['template_code'] ?? '');
        $description   = trim($_POST['description'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        $status        = $_POST['status'] ?? 'active';

        $template = $this->templateModel->find($id);
        if (!$template) {
            $_SESSION['error'] = 'Template not found.';
            header('Location: /admin/templates');
            exit;
        }

        $attached_file = null;
        $file_size     = null;
        $file_type     = null;

        if (isset($_FILES['attached_file']) && $_FILES['attached_file']['error'] === UPLOAD_ERR_OK) {
            // Delete old file
            if (!empty($template['attached_file']) && file_exists($template['attached_file'])) {
                unlink($template['attached_file']);
            }
            $uploaded = $this->uploadFile($_FILES['attached_file']);
            if ($uploaded) {
                $attached_file = $uploaded['path'];
                $file_size     = $uploaded['size'];
                $file_type     = $uploaded['type'];
            }
        }

        if ($title && $template_code) {
            $this->templateModel->update(
                $id, $title, $template_code, $description,
                $attached_file, $file_size, $file_type,
                $display_order, $status
            );
            $_SESSION['success'] = 'Template updated successfully.';
        } else {
            $_SESSION['error'] = 'Title and Template Code are required.';
        }

        header('Location: /admin/templates');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $template = $this->templateModel->find($id);
        if ($template) {
            if (!empty($template['attached_file']) && file_exists($template['attached_file'])) {
                unlink($template['attached_file']);
            }
            $this->templateModel->delete($id);
            $_SESSION['success'] = 'Template deleted.';
        } else {
            $_SESSION['error'] = 'Template not found.';
        }
        header('Location: /admin/templates');
        exit;
    }

    public function toggleStatus()
    {
        $id     = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'active';
        $this->templateModel->toggleStatus($id, $status);
        $_SESSION['success'] = 'Status updated.';
        header('Location: /admin/templates');
        exit;
    }

    private function uploadFile($file)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx'];

        if (!in_array($ext, $allowed)) {
            return null;
        }

        $filename = time() . '_' . uniqid() . '.' . $ext;
        $destination = $this->uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        // Human-readable file size
        $bytes = filesize($destination);
        $size = $this->formatSize($bytes);

        // File type label
        $typeMap = [
            'doc' => 'Word', 'docx' => 'Word',
            'xls' => 'Excel', 'xlsx' => 'Excel',
            'ppt' => 'PowerPoint', 'pptx' => 'PowerPoint',
            'pdf' => 'PDF',
        ];

        return [
            'path' => $destination,
            'size' => $size,
            'type' => $typeMap[$ext] ?? strtoupper($ext),
        ];
    }

    private function formatSize($bytes)
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' mb';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' kb';
        return $bytes . ' b';
    }
}