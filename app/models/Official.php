<?php
class Official
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll($category = null, $status = null)
    {
        $sql = "SELECT * FROM officials ORDER BY category, display_order ASC, id ASC";
        $params = [];
        if ($category && $status) {
            $sql = "SELECT * FROM officials WHERE category = ? AND status = ? ORDER BY display_order ASC, id ASC";
            $params = [$category, $status];
        } elseif ($category) {
            $sql = "SELECT * FROM officials WHERE category = ? ORDER BY display_order ASC, id ASC";
            $params = [$category];
        } elseif ($status) {
            $sql = "SELECT * FROM officials WHERE status = ? ORDER BY category, display_order ASC, id ASC";
            $params = [$status];
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM officials WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $position, $email, $category, $image, $status, $display_order = 0)
    {
        $stmt = $this->db->prepare("
            INSERT INTO officials (name, position, email, category, image, status, display_order)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $position, $email, $category, $image, $status, $display_order]);
    }

    public function update($id, $name, $position, $email, $category, $image, $status, $display_order = 0)
    {
        if ($image) {
            $stmt = $this->db->prepare("
                UPDATE officials
                SET name = ?, position = ?, email = ?, category = ?, image = ?, status = ?, display_order = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $position, $email, $category, $image, $status, $display_order, $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE officials
                SET name = ?, position = ?, email = ?, category = ?, status = ?, display_order = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $position, $email, $category, $status, $display_order, $id]);
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
        SELECT * FROM officials 
        WHERE status = 'published' 
        ORDER BY category, display_order ASC, id ASC
    ");
    $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $groups = [];
    foreach ($officials as $official) {
        $groups[$official['category']][] = $official;
    }
    return $groups;
}
}