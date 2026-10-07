<?php
class DetailedProposal
{
    private $db;

    public function __construct($pdo) { $this->db = $pdo; }

    public function create($submission_id, $user_id, $attachment, $status = 'draft', $academic_year_id = null)
    {
        $stmt = $this->db->prepare("
            INSERT INTO detailed_proposals
                (submission_id, user_id, academic_year_id, attachment, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$submission_id, $user_id, $academic_year_id, $attachment, $status]);
        return (int)$this->db->lastInsertId();
    }

    public function find($id, $user_id = null)
    {
        $sql = "SELECT * FROM detailed_proposals WHERE id = ?";
        $params = [$id];
        if ($user_id) { $sql .= " AND user_id = ?"; $params[] = $user_id; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findBySubmission($submission_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM detailed_proposals WHERE submission_id = ? LIMIT 1");
        $stmt->execute([$submission_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function hasDetailedProposal($submission_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM detailed_proposals WHERE submission_id = ?");
        $stmt->execute([$submission_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function update($id, $attachment, $status = null)
    {
        if ($status) {
            $stmt = $this->db->prepare("UPDATE detailed_proposals SET attachment = ?, status = ? WHERE id = ?");
            return $stmt->execute([$attachment, $status, $id]);
        }
        $stmt = $this->db->prepare("UPDATE detailed_proposals SET attachment = ? WHERE id = ?");
        return $stmt->execute([$attachment, $id]);
    }

    public function updateAdminStatus($id, $status, $remarks = null)
    {
        $stmt = $this->db->prepare("UPDATE detailed_proposals SET status = ?, admin_remarks = ? WHERE id = ?");
        return $stmt->execute([$status, $remarks, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM detailed_proposals WHERE id = ?");
        return $stmt->execute([$id]);
    }
}