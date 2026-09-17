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

    protected function render($view, $data = [])
    {
        extract($data);
        $role = 'evaluator';
        $content = __DIR__ . '/../../views/evaluator/' . $view . '.php';
        require __DIR__ . '/../../views/layouts/app.php';
    }
}