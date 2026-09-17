<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "1. Autoloader loading...<br>";

// Include the autoloader from public/index.php (or create a simpler one)
require_once __DIR__ . '/../app/Http/Controllers/AuthController.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/models/OtpVerification.php';
require_once __DIR__ . '/../app/models/College.php';
require_once __DIR__ . '/../config/database.php';

echo "2. Files included.<br>";

$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "3. Database connected.<br>";

$auth = new AuthController();
echo "4. AuthController instantiated.<br>";

$auth->showRegisterForm();
echo "5. showRegisterForm called.";