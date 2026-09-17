<?php
namespace Admin;

require_once __DIR__ . '/../../../models/Setting.php';

class AboutController extends \Controller
{
    private $settingModel;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->settingModel = new \Setting($pdo);
    }

    // Show the about management form
    public function index()
    {
        $keys = [
            'about_description',
            'vision',
            'mission',
            'objectives',
            'services',
            'contact_email',
            'contact_phone',
            'contact_address'
        ];
        $data = $this->settingModel->getMany($keys);

        // Decode JSON arrays for display
        $data['objectives'] = isset($data['objectives']) ? json_decode($data['objectives'], true) : [];
        $data['services'] = isset($data['services']) ? json_decode($data['services'], true) : [];

        $this->render('about/index', $data);
    }

    // Update all settings
    public function update()
    {
        $fields = [
            'about_description',
            'vision',
            'mission',
            'objectives',
            'services',
            'contact_email',
            'contact_phone',
            'contact_address'
        ];

        foreach ($fields as $field) {
            $value = $_POST[$field] ?? '';
            if (in_array($field, ['objectives', 'services'])) {
                // Convert from textarea lines to array, then JSON
                $lines = array_filter(array_map('trim', explode("\n", $value)), 'strlen');
                $value = json_encode(array_values($lines));
            }
            $this->settingModel->set($field, $value);
        }

        $_SESSION['success'] = 'About section updated successfully.';
        header('Location: /admin/about');
        exit;
    }
}