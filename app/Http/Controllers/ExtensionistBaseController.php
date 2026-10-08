<?php
class ExtensionistBaseController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'extensionist') {
            header('Location: /login');
            exit;
        }

        // === ALWAYS SYNC ACADEMIC YEAR FROM DB ===
        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->query("SELECT * FROM academic_years WHERE is_current = TRUE LIMIT 1");
        $y = $stmt->fetch(PDO::FETCH_ASSOC);

        $currentYearId    = $y['id']         ?? null;
        $currentYearLabel = $y['year_label'] ?? 'N/A';

        // Update session if different
        if ($_SESSION['academic_year_id']    ?? null !== $currentYearId
         || $_SESSION['academic_year_label'] ?? null !== $currentYearLabel) {
            $_SESSION['academic_year_id']    = $currentYearId;
            $_SESSION['academic_year_label'] = $currentYearLabel;
        }

        // === UPDATE LAST ACTIVITY ===
        $stmt = $pdo->prepare("UPDATE users SET last_activity = NOW() WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $role = 'extensionist';

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