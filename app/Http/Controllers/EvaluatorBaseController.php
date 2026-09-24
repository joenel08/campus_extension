<?php
class EvaluatorBaseController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'evaluator') {
            header('Location: /login');
            exit;
        }
    }

    // protected function render($view, $data = [])
    // {
    //     extract($data);
    //     $role = 'evaluator';
    //     $content = __DIR__ . '/../../views/evaluator/' . $view . '.php';
    //     require __DIR__ . '/../../views/layouts/app.php';
    // }

    protected function render($view, $data = [])
{
    extract($data);
    $role = 'evaluator';

    // Load notifications for topbar
    require_once __DIR__ . '/../../models/Notification.php';
    $config = require __DIR__ . '/../../../config/database.php';
    $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
    $notifModel = new Notification($pdo);
    $notifications = $notifModel->getByUser($_SESSION['user_id'], 10);
    $unreadCount = $notifModel->countUnread($_SESSION['user_id']);

    $content = __DIR__ . '/../../views/' . $role . '/' . $view . '.php';
    require __DIR__ . '/../../views/layouts/app.php';
}
}