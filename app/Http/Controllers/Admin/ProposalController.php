<?php

namespace Admin;

require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/College.php';
require_once __DIR__ . '/../../../models/Notification.php';

class ProposalController extends \Controller
{
    private $proposalModel;
    private $collegeModel;
    private $notificationModel;   // <-- ADD
    private $db;                  // <-- ADD
    private $uploadDir = 'uploads/proposals/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->db = $pdo;                                        // <-- STORE
        $this->proposalModel = new \Proposal($pdo);
        $this->collegeModel = new \College($pdo);
        $this->notificationModel = new \Notification($pdo);      // <-- INSTANTIATE

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    public function index()
    {
        $proposals = $this->proposalModel->getAll();
        $this->render('proposals/index', ['proposals' => $proposals]);
    }

    public function create()
    {
        $colleges = $this->collegeModel->getAll();
        $this->render('proposals/create', ['colleges' => $colleges]);
    }

    public function store()
    {
        $title = trim($_POST['title'] ?? '');
        $college_id = $_POST['college_id'] ?? null;
        $category = $_POST['category'] ?? 'internally_funded';
        $status = $_POST['status'] ?? 'open';
        $opening_date = $_POST['opening_date'] ?? '';
        $closing_date = $_POST['closing_date'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $file_path = '';

        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
            $file_path = $this->uploadFile($_FILES['file_path']);
        }

        if ($title && $opening_date && $closing_date && $college_id) {
            $this->proposalModel->create($title, $college_id, $category, $status, $opening_date, $closing_date, $description, $file_path);
            $_SESSION['success'] = 'Proposal published successfully.';

            // Send notifications to extensionists in that college
            $extensionistIds = $this->notificationModel->getUsersByRoleAndCollege('extensionist', $college_id);
            $this->notificationModel->createBulk(
                $extensionistIds,
                'call_for_proposal',
                'New Call for Proposal',
                'A new proposal call "' . $title . '" is now open. Deadline: ' . $closing_date,
                '/extensionist/submissions'
            );
        } else {
            $_SESSION['error'] = 'Title, college, opening date, and closing date are required.';
        }

        header('Location: /admin/proposal');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            $_SESSION['error'] = 'Proposal not found.';
            header('Location: /admin/proposal');
            exit;
        }
        $colleges = $this->collegeModel->getAll();
        $this->render('proposals/edit', ['proposal' => $proposal, 'colleges' => $colleges]);
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $title = trim($_POST['title'] ?? '');
        $college_id = $_POST['college_id'] ?? null;
        $category = $_POST['category'] ?? 'internally_funded';
        $status = $_POST['status'] ?? 'open';
        $opening_date = $_POST['opening_date'] ?? '';
        $closing_date = $_POST['closing_date'] ?? '';
        $description = trim($_POST['description'] ?? '');

        $proposal = $this->proposalModel->find($id);
        if (!$proposal) {
            $_SESSION['error'] = 'Proposal not found.';
            header('Location: /admin/proposal');
            exit;
        }

        $file_path = $proposal['file_path'];
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] === UPLOAD_ERR_OK) {
            if ($file_path && file_exists($file_path)) {
                unlink($file_path);
            }
            $file_path = $this->uploadFile($_FILES['file_path']);
        }

        if ($title && $opening_date && $closing_date && $college_id) {
            $this->proposalModel->update($id, $title, $college_id, $category, $status, $opening_date, $closing_date, $description, $file_path);
            $_SESSION['success'] = 'Proposal updated successfully.';

            // Notify extensionists if the call is re-opened
            if ($status === 'open') {
                $extensionistIds = $this->notificationModel->getUsersByRoleAndCollege('extensionist', $college_id);
                $this->notificationModel->createBulk(
                    $extensionistIds,
                    'call_for_proposal',
                    'Call for Proposal Updated',
                    'The call "' . $title . '" has been updated. Deadline: ' . $closing_date,
                    '/extensionist/submissions'
                );
            }
        } else {
            $_SESSION['error'] = 'Title, college, opening date, and closing date are required.';
        }
        header('Location: /admin/proposal');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $proposal = $this->proposalModel->find($id);
        if ($proposal) {
            if ($proposal['file_path'] && file_exists($proposal['file_path'])) {
                unlink($proposal['file_path']);
            }
            $this->proposalModel->delete($id);
            $_SESSION['success'] = 'Proposal deleted.';
        } else {
            $_SESSION['error'] = 'Proposal not found.';
        }
        header('Location: /admin/proposal');
        exit;
    }

    public function toggleStatus()
    {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'open';
        $this->proposalModel->toggleStatus($id, $status);
        $_SESSION['success'] = 'Status updated.';

        // Notify extensionists if the call is now open
        if ($status === 'open') {
            $proposal = $this->proposalModel->find($id);
            if ($proposal) {
                $extensionistIds = $this->notificationModel->getUsersByRoleAndCollege('extensionist', $proposal['college_id']);
                $this->notificationModel->createBulk(
                    $extensionistIds,
                    'call_for_proposal',
                    'Call for Proposal is Now Open',
                    'The call "' . $proposal['title'] . '" is now open for submissions.',
                    '/extensionist/submissions'
                );
            }
        }

        header('Location: /admin/proposal');
        exit;
    }

    private function uploadFile($file)
    {
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $destination = $this->uploadDir . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return $destination;
    }
}