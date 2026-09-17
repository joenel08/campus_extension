<?php
class Setting
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function get($key)
    {
        $stmt = $this->db->prepare("SELECT `value` FROM settings WHERE `key` = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : null;
    }

    public function set($key, $value)
    {
        $stmt = $this->db->prepare("INSERT INTO settings (`key`, `value`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `value` = ?");
        return $stmt->execute([$key, $value, $value]);
    }

    public function getMany($keys)
    {
        $placeholders = implode(',', array_fill(0, count($keys), '?'));
        $stmt = $this->db->prepare("SELECT `key`, `value` FROM settings WHERE `key` IN ($placeholders)");
        $stmt->execute($keys);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['key']] = $row['value'];
        }
        return $results;
    }
}