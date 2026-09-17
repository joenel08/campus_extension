<?php
class Controller
{
    public function __construct()
    {
        // Check admin session for all admin controllers
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /login');
            exit;
        }
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $role = 'admin';
        $content = __DIR__ . '/../../views/admin/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }
}