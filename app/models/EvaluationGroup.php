<?php
class EvaluationGroup
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM eval_groups ORDER BY display_order ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM eval_groups WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $display_order = 0)
    {
        $stmt = $this->db->prepare("INSERT INTO eval_groups (name, display_order) VALUES (?, ?)");
        return $stmt->execute([$name, $display_order]);
    }

    public function update($id, $name, $display_order)
    {
        $stmt = $this->db->prepare("UPDATE eval_groups SET name = ?, display_order = ? WHERE id = ?");
        return $stmt->execute([$name, $display_order, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM eval_groups WHERE id = ?");
        return $stmt->execute([$id]);
    }
}