<?php
class College
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM colleges ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM colleges WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($abbreviation, $description)
    {
        $stmt = $this->db->prepare("INSERT INTO colleges (abbreviation, description) VALUES (?, ?)");
        return $stmt->execute([$abbreviation, $description]);
    }

    public function update($id, $abbreviation, $description)
    {
        $stmt = $this->db->prepare("UPDATE colleges SET abbreviation = ?, description = ? WHERE id = ?");
        return $stmt->execute([$abbreviation, $description, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM colleges WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
