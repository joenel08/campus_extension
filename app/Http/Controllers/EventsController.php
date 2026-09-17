<?php
class EventsController
{
    public function index()
    {
        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        require_once __DIR__ . '/../../models/Event.php';
        $eventModel = new Event($pdo);
        $allEvents = $eventModel->getPublished();

        require __DIR__ . '/../../views/partials/header.php';
        require __DIR__ . '/../../views/events/index.php';
        require __DIR__ . '/../../views/partials/footer.php';
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: /events');
            exit;
        }

        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        require_once __DIR__ . '/../../models/Event.php';
        $eventModel = new Event($pdo);
        $eventItem = $eventModel->find($id);

        if (!$eventItem || $eventItem['status'] !== 'published') {
            http_response_code(404);
            echo "Event not found.";
            exit;
        }

        require __DIR__ . '/../../views/partials/header.php';
        require __DIR__ . '/../../views/events/show.php';
        require __DIR__ . '/../../views/partials/footer.php';
    }
}