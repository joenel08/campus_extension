<?php
class ExtensionistBaseController
{
    public function __construct()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'extensionist') {
            header('Location: /login');
            exit;
        }
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $role = 'extensionist';
        $content = __DIR__ . '/../../views/extensionist/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }
}