<?php
class Banner
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function get()
    {
        $stmt = $this->db->query("SELECT * FROM banner LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($type, $media_path, $title, $subtitle)
    {
        $stmt = $this->db->prepare("UPDATE banner SET type = ?, media_path = ?, title = ?, subtitle = ? WHERE id = 1");
        return $stmt->execute([$type, $media_path, $title, $subtitle]);
    }
}