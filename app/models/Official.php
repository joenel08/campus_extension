<?php
class Official
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT o.*, c.abbreviation as college_abbr
            FROM officials o
            LEFT JOIN colleges c ON o.college_id = c.id
            ORDER BY o.category, o.display_order ASC, o.id ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT o.*, c.abbreviation as college_abbr
            FROM officials o
            LEFT JOIN colleges c ON o.college_id = c.id
            WHERE o.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countByCategory($category, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM officials WHERE category = ?";
        $params = [$category];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function create($name, $position, $category, $college_id, $image, $status, $display_order = 0)
    {
        $stmt = $this->db->prepare("
            INSERT INTO officials (name, position, category, college_id, image, status, display_order)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $position, $category, $college_id, $image, $status, $display_order]);
    }

    public function update($id, $name, $position, $category, $college_id, $image, $status, $display_order = 0)
    {
        if ($image) {
            $stmt = $this->db->prepare("
                UPDATE officials
                SET name = ?, position = ?, category = ?, college_id = ?, image = ?, status = ?, display_order = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $position, $category, $college_id, $image, $status, $display_order, $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE officials
                SET name = ?, position = ?, category = ?, college_id = ?, status = ?, display_order = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $position, $category, $college_id, $status, $display_order, $id]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM officials WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE officials SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function getPublishedGrouped()
    {
        $stmt = $this->db->query("
            SELECT o.*, c.abbreviation as college_abbr
            FROM officials o
            LEFT JOIN colleges c ON o.college_id = c.id
            WHERE o.status = 'published'
            ORDER BY o.category, o.display_order ASC, o.id ASC
        ");
        $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $groups = [];
        foreach ($officials as $official) {
            $groups[$official['category']][] = $official;
        }
        return $groups;
    }
}