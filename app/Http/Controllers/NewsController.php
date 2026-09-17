<?php
class NewsController
{
    public function index()
    {
        // Load database
        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        require_once __DIR__ . '/../../models/News.php';
        $newsModel = new News($pdo);
        $allNews = $newsModel->getPublished(); // all published

        require __DIR__ . '/../../views/partials/header.php';
        require __DIR__ . '/../../views/news/index.php';
        require __DIR__ . '/../../views/partials/footer.php';
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            header('Location: /news');
            exit;
        }

        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        require_once __DIR__ . '/../../models/News.php';
        $newsModel = new News($pdo);
        $newsItem = $newsModel->find($id);

        if (!$newsItem || $newsItem['status'] !== 'published') {
            http_response_code(404);
            echo "News not found.";
            exit;
        }

        require __DIR__ . '/../../views/partials/header.php';
        require __DIR__ . '/../../views/news/show.php';
        require __DIR__ . '/../../views/partials/footer.php';
    }
}