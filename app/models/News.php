<?php
class News
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll($status = null)
    {
        $sql = "SELECT * FROM news ORDER BY created_at DESC";
        if ($status) {
            $sql = "SELECT * FROM news WHERE status = ? ORDER BY created_at DESC";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($status ? [$status] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM news WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $content, $image, $status)
    {
        $stmt = $this->db->prepare("INSERT INTO news (title, content, image, status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$title, $content, $image, $status]);
    }

    public function update($id, $title, $content, $image, $status)
    {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE news SET title = ?, content = ?, image = ?, status = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $image, $status, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE news SET title = ?, content = ?, status = ? WHERE id = ?");
            return $stmt->execute([$title, $content, $status, $id]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE news SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getPublished($limit = 4)
    {
        $stmt = $this->db->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT ?");
        $stmt->bindParam(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
}
