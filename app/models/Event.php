<?php
class Event
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll($status = null)
    {
        $sql = "SELECT * FROM events ORDER BY event_date DESC";
        if ($status) {
            $sql = "SELECT * FROM events WHERE status = ? ORDER BY event_date DESC";
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($status ? [$status] : []);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $description, $image, $event_date, $status)
    {
        $stmt = $this->db->prepare("INSERT INTO events (title, description, image, event_date, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$title, $description, $image, $event_date, $status]);
    }

    public function update($id, $title, $description, $image, $event_date, $status)
    {
        if ($image) {
            $stmt = $this->db->prepare("UPDATE events SET title = ?, description = ?, image = ?, event_date = ?, status = ? WHERE id = ?");
            return $stmt->execute([$title, $description, $image, $event_date, $status, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE events SET title = ?, description = ?, event_date = ?, status = ? WHERE id = ?");
            return $stmt->execute([$title, $description, $event_date, $status, $id]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE events SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getPublished($limit = 4)
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE status = 'published' ORDER BY event_date ASC LIMIT ?");
        $stmt->bindParam(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
