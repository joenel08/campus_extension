<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Event.php';

class EventsController extends \Controller
{
    private $eventModel;
    private $uploadDir = 'uploads/events/';

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->eventModel = new \Event($pdo);

        // Create upload directory if not exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }

    // List all events
    public function index()
    {
        $events = $this->eventModel->getAll();
        $this->render('events/index', ['events' => $events]);
    }

    // Show create form
    public function create()
    {
        $this->render('events/create');
    }

    // Store new event
    public function store()
    {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $image = '';

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadImage($_FILES['image']);
        }

        if ($title && $description && $event_date) {
            $this->eventModel->create($title, $description, $image, $event_date, $status);
            $_SESSION['success'] = 'Event created successfully.';
        } else {
            $_SESSION['error'] = 'Title, description, and date are required.';
        }
        header('Location: /admin/events');
        exit;
    }

    // Show edit form
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $event = $this->eventModel->find($id);
        if (!$event) {
            $_SESSION['error'] = 'Event not found.';
            header('Location: /admin/events');
            exit;
        }
        $this->render('events/edit', ['event' => $event]);
    }

    // Update event
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = $_POST['event_date'] ?? '';
        $status = $_POST['status'] ?? 'draft';

        $event = $this->eventModel->find($id);
        if (!$event) {
            $_SESSION['error'] = 'Event not found.';
            header('Location: /admin/events');
            exit;
        }

        $image = $event['image'];
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image
            if ($image && file_exists($image)) {
                unlink($image);
            }
            $image = $this->uploadImage($_FILES['image']);
        }

        if ($title && $description && $event_date) {
            $this->eventModel->update($id, $title, $description, $image, $event_date, $status);
            $_SESSION['success'] = 'Event updated successfully.';
        } else {
            $_SESSION['error'] = 'Title, description, and date are required.';
        }
        header('Location: /admin/events');
        exit;
    }

    // Delete event
    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $event = $this->eventModel->find($id);
        if ($event) {
            // Delete image
            if ($event['image'] && file_exists($event['image'])) {
                unlink($event['image']);
            }
            $this->eventModel->delete($id);
            $_SESSION['success'] = 'Event deleted.';
        } else {
            $_SESSION['error'] = 'Event not found.';
        }
        header('Location: /admin/events');
        exit;
    }

    // Toggle status (draft/published)
    public function toggleStatus()
    {
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'draft';
        $this->eventModel->toggleStatus($id, $status);
        $_SESSION['success'] = 'Status updated.';
        header('Location: /admin/events');
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