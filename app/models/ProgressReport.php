<?php
class ProgressReport
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getBySubmission($submission_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM progress_reports WHERE submission_id = ? ORDER BY created_at DESC");
        $stmt->execute([$submission_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM progress_reports WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($submission_id, $user_id, $data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO progress_reports 
            (submission_id, user_id, report_date, accomplishments, issues, next_plan, attachment, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $submission_id,
            $user_id,
            $data['report_date'],
            $data['accomplishments'],
            $data['issues'],
            $data['next_plan'],
            $data['attachment'] ?? null,
            $data['status'] ?? 'draft'
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE progress_reports
            SET report_date = ?, accomplishments = ?, issues = ?, next_plan = ?, attachment = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['report_date'],
            $data['accomplishments'],
            $data['issues'],
            $data['next_plan'],
            $data['attachment'] ?? null,
            $data['status'] ?? 'draft',
            $id
        ]);
    }

    public function updateStatus($id, $status, $comments = null)
    {
        $stmt = $this->db->prepare("UPDATE progress_reports SET status = ?, evaluator_comments = ? WHERE id = ?");
        return $stmt->execute([$status, $comments, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM progress_reports WHERE id = ?");
        return $stmt->execute([$id]);
    }
}