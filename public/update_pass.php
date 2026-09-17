<?php
$config = require __DIR__ . '/../config/database.php';
$pdo = new PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
$stmt->execute([$newHash, 'admin@isu.edu']);
echo "Hash updated: " . $newHash;
?>