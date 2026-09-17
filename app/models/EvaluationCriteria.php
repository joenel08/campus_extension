<?php
class EvaluationCriteria
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getByGroup($group_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM eval_criteria WHERE group_id = ? ORDER BY display_order ASC");
        $stmt->execute([$group_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllWithGroups()
    {
        $stmt = $this->db->query("
            SELECT g.id as group_id, g.name as group_name, c.id as criteria_id, c.criteria_text, c.display_order
            FROM eval_groups g
            LEFT JOIN eval_criteria c ON g.id = c.group_id
            ORDER BY g.display_order ASC, c.display_order ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM eval_criteria WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($group_id, $criteria_text, $display_order = 0)
    {
        $stmt = $this->db->prepare("INSERT INTO eval_criteria (group_id, criteria_text, display_order) VALUES (?, ?, ?)");
        return $stmt->execute([$group_id, $criteria_text, $display_order]);
    }

    public function update($id, $group_id, $criteria_text, $display_order)
    {
        $stmt = $this->db->prepare("UPDATE eval_criteria SET group_id = ?, criteria_text = ?, display_order = ? WHERE id = ?");
        return $stmt->execute([$group_id, $criteria_text, $display_order, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM eval_criteria WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Get all criteria grouped by group for display
    public function getGrouped()
{
    // Fetch all groups
    $stmt = $this->db->query("SELECT * FROM eval_groups ORDER BY display_order ASC");
    $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $result = [];
    foreach ($groups as $group) {
        // Fetch criteria for this group
        $stmt = $this->db->prepare("SELECT * FROM eval_criteria WHERE group_id = ? ORDER BY display_order ASC");
        $stmt->execute([$group['id']]);
        $criteria = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result[] = [
            'group' => $group,
            'criteria' => $criteria,
        ];
    }
    return $result;
}

    public function getAllGroupsWithCriteria()
    {
        $stmt = $this->db->query("
        SELECT g.id as group_id, g.name as group_name, c.id as criteria_id, c.criteria_text, c.display_order
        FROM eval_groups g
        LEFT JOIN eval_criteria c ON g.id = c.group_id
        ORDER BY g.display_order ASC, c.display_order ASC
    ");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $groups = [];
        foreach ($results as $row) {
            if (!isset($groups[$row['group_id']])) {
                $groups[$row['group_id']] = [
                    'name' => $row['group_name'],
                    'criteria' => []
                ];
            }
            if ($row['criteria_id']) {
                $groups[$row['group_id']]['criteria'][] = [
                    'id' => $row['criteria_id'],
                    'text' => $row['criteria_text']
                ];
            }
        }
        return $groups;
    }
}
