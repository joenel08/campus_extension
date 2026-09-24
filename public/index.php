<?php
session_start();
date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader
spl_autoload_register(function ($class) {
    $class = ltrim($class, '\\');
    $class = str_replace('\\', '/', $class);

    $file = __DIR__ . '/../app/Http/Controllers/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    $file = __DIR__ . '/../app/models/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }

    return false;
});

// Load routes
$routes = [];
require __DIR__ . '/../routes/web.php';

// Load role routes if logged in
if (isset($_SESSION['user_role'])) {
    $role = $_SESSION['user_role'];
    $roleFile = __DIR__ . '/../routes/' . $role . '.php';
    if (file_exists($roleFile)) {
        require $roleFile;
    }
}

// --- NEW: Handle POST requests ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    $url = rtrim($url, '/');

    if ($url === '/login') {
        $controller = new AuthController();
        $controller->login();
        exit;
    }

    if ($url === '/register') {
        $controller = new AuthController();
        $controller->register();
        exit;
    }
    if ($url === '/verify-otp') {
        $controller = new AuthController();
        $controller->verifyOtp();
        exit;
    }

    if ($url === '/notifications/mark-all-read') {
        $config = require __DIR__ . '/../config/database.php';
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        require_once __DIR__ . '/../app/models/Notification.php';
        $notifModel = new Notification($pdo);
        $notifModel->markAllRead($_SESSION['user_id']);
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
        exit;
    }

    // Map POST URLs to controller actions
    $postRoutes = [


        '/admin/submitpaper/store' => ['SubmitPaperController', 'store'],
        '/admin/banner/store'     => ['BannerController', 'store'],

        '/admin/about/store'      => ['AboutController', 'store'],
        '/admin/crest/store'      => ['CrestController', 'store'],


        '/admin/colleges/store'   => ['Admin\\CollegeController', 'store'],
        '/admin/colleges/update'  => ['Admin\\CollegeController', 'update'],
        '/admin/colleges/delete'  => ['Admin\\CollegeController', 'delete'],

        '/admin/accounts/store'   => ['Admin\\AccountsController', 'store'],
        '/admin/accounts/update'  => ['Admin\\AccountsController', 'update'],
        '/admin/accounts/approve' => ['Admin\\AccountsController', 'approve'],
        '/admin/accounts/decline' => ['Admin\\AccountsController', 'decline'],
        '/admin/accounts/delete'  => ['Admin\\AccountsController', 'delete'],

        '/admin/news/store'   => ['Admin\\NewsController', 'store'],
        '/admin/news/update'  => ['Admin\\NewsController', 'update'],
        '/admin/news/delete'  => ['Admin\\NewsController', 'delete'],
        '/admin/news/toggle'  => ['Admin\\NewsController', 'toggleStatus'],

        '/admin/events/store'   => ['Admin\\EventsController', 'store'],
        '/admin/events/update'  => ['Admin\\EventsController', 'update'],
        '/admin/events/delete'  => ['Admin\\EventsController', 'delete'],
        '/admin/events/toggle'  => ['Admin\\EventsController', 'toggleStatus'],

        '/admin/officials/store'   => ['Admin\\OfficialController', 'store'],
        '/admin/officials/update'  => ['Admin\\OfficialController', 'update'],
        '/admin/officials/delete'  => ['Admin\\OfficialController', 'delete'],
        '/admin/officials/toggle'  => ['Admin\\OfficialController', 'toggleStatus'],

        '/admin/about/update' => ['Admin\\AboutController', 'update'],

        '/admin/banner/update' => ['Admin\\BannerController', 'update'],

        '/admin/evaluation/store-group'   => ['Admin\\EvaluationController', 'storeGroup'],
        '/admin/evaluation/update-group'  => ['Admin\\EvaluationController', 'updateGroup'],
        '/admin/evaluation/delete-group'  => ['Admin\\EvaluationController', 'deleteGroup'],
        '/admin/evaluation/store-criteria' => ['Admin\\EvaluationController', 'storeCriteria'],
        '/admin/evaluation/update-criteria' => ['Admin\\EvaluationController', 'updateCriteria'],
        '/admin/evaluation/delete-criteria' => ['Admin\\EvaluationController', 'deleteCriteria'],

        '/admin/proposal/store'   => ['Admin\\ProposalController', 'store'],
        '/admin/proposal/update'  => ['Admin\\ProposalController', 'update'],
        '/admin/proposal/delete'  => ['Admin\\ProposalController', 'delete'],
        '/admin/proposal/toggle'  => ['Admin\\ProposalController', 'toggleStatus'],

        '/admin/crest/store'   => ['Admin\\CrestController', 'store'],
        '/admin/crest/update'  => ['Admin\\CrestController', 'update'],
        '/admin/crest/delete'  => ['Admin\\CrestController', 'delete'],
        '/admin/crest/toggle'  => ['Admin\\CrestController', 'toggleStatus'],

        '/admin/monitoring/approve' => ['Admin\\MonitoringController', 'approve'],
        '/admin/monitoring/revise'  => ['Admin\\MonitoringController', 'revise'],
        '/admin/monitoring/decline' => ['Admin\\MonitoringController', 'decline'],

        '/admin/monitoring/assign-evaluators' => ['Admin\\MonitoringController', 'assignEvaluatorStore'],
        '/evaluator/save-evaluation' => ['Evaluator\\EvaluationController', 'saveEvaluation'],

        '/extensionist/submissions/store' => ['Extensionist\\SubmissionController', 'store'],
        '/extensionist/submissions/update' => ['Extensionist\\SubmissionController', 'update'],
        '/extensionist/submissions/delete' => ['Extensionist\\SubmissionController', 'delete'],
    ];

    if (isset($postRoutes[$url])) {
        list($controllerName, $action) = $postRoutes[$url];
        $controller = new $controllerName();
        $controller->$action();
        exit;
    } else {
        // Handle other POSTs or show 404
        http_response_code(404);
        echo "404 - POST route not found";
        exit;
    }
}

// --- GET router using REQUEST_URI ---
$requestUri = $_SERVER['REQUEST_URI'];
$url = strtok($requestUri, '?');  // remove query string
$url = rtrim($url, '/');
$url = $url ?: '/';

if (isset($routes[$url])) {
    $route = $routes[$url];
    $controllerName = $route['controller'];
    $action = $route['action'];
    $controller = new $controllerName();
    $controller->$action();
} else {
    http_response_code(404);
    echo "404 - Page not found";
}
