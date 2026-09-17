<?php
class TerminalReport
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getBySubmission($submission_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM terminal_reports WHERE submission_id = ? LIMIT 1");
        $stmt->execute([$submission_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM terminal_reports WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($submission_id, $user_id, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO terminal_reports
            (submission_id, user_id, completion_date, overall_status, final_summary, lessons_learned, recommendations, attachment, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $submission_id,
            $user_id,
            $data['completion_date'],
            $data['overall_status'],
            $data['final_summary'],
            $data['lessons_learned'],
            $data['recommendations'],
            $data['attachment'] ?? null,
            $data['status'] ?? 'draft'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE terminal_reports
            SET completion_date = ?, overall_status = ?, final_summary = ?, lessons_learned = ?, recommendations = ?, attachment = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['completion_date'],
            $data['overall_status'],
            $data['final_summary'],
            $data['lessons_learned'],
            $data['recommendations'],
            $data['attachment'] ?? null,
            $data['status'] ?? 'draft',
            $id
        ]);
    }

    public function updateStatus($id, $status, $comments = null)
    {
        $stmt = $this->db->prepare("UPDATE terminal_reports SET status = ?, evaluator_comments = ? WHERE id = ?");
        return $stmt->execute([$status, $comments, $id]);
    }

    public function hasTerminalReport($submission_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM terminal_reports WHERE submission_id = ?");
        $stmt->execute([$submission_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM terminal_reports WHERE id = ?");
        return $stmt->execute([$id]);
    }
}