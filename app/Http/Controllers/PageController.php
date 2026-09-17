<?php
class PageController
{
    public function landing()
    {
        // Load DB connection
        $config = require __DIR__ . '/../../../config/database.php';
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}",
            $config['username'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // ---- Models ----
        require_once __DIR__ . '/../../models/Setting.php';
        require_once __DIR__ . '/../../models/News.php';
        require_once __DIR__ . '/../../models/Event.php';
        require_once __DIR__ . '/../../models/Official.php';
        require_once __DIR__ . '/../../models/Banner.php';
        require_once __DIR__ . '/../../models/Publication.php';
        require_once __DIR__ . '/../controllers/CrestController.php';

        $settingModel  = new Setting($pdo);
        $newsModel     = new News($pdo);
        $eventModel    = new Event($pdo);
        $officialModel = new Official($pdo);
        $bannerModel   = new Banner($pdo);

        // ---- Banner ----
        $banner = $bannerModel->get();
        if ($banner) {
            $banner_type = $banner['type'];
            $banner_url  = $banner['media_path'];
        } else {
            $banner_type = 'image';
            $banner_url  = '/images/default-banner.jpg';
        }

        // ---- News (latest 4 published) ----
        $news = $newsModel->getPublished(4);

        // ---- Events (upcoming 4 published) ----
        $events = $eventModel->getPublished(4);

        // ---- About settings ----
        $about = $settingModel->getMany([
            'about_description',
            'banner',
            'vision',
            'mission',
            'objectives',
            'services',
            'contact_email',
            'contact_phone',
            'contact_address',
        ]);
        $about['objectives'] = json_decode($about['objectives'] ?? '[]', true);
        $about['services']   = json_decode($about['services'] ?? '[]', true);

        // ---- Officials grouped by category ----
        $officials = $officialModel->getPublishedGrouped();

        // ---- Publications for CREST section ----
        $crestController    = new CrestController($pdo);
        $publicationsByYear = $crestController->getGroupedByYear();

        // ---- Render ----
        require __DIR__ . '/../../views/landing.php';
    }
}