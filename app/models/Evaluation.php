<?php
class Evaluation
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Get all proposal submissions assigned to this evaluator
    public function getAssignedProposals($evaluator_id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                s.id as submission_id,
                s.proposal_id,
                s.status as submission_status,
                s.created_at,
                p.title as proposal_title,
                u.name as extensionist_name,
                u.id as extensionist_id
            FROM submission_evaluators se
            JOIN submissions s ON se.submission_id = s.id
            JOIN proposals p ON s.proposal_id = p.id
            JOIN users u ON s.user_id = u.id
            WHERE se.evaluator_id = ?
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$evaluator_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Check if evaluator already voted for a specific submission + report type
    public function getBySubmissionAndEvaluator($submission_id, $evaluator_id, $report_type = 'proposal')
    {
        $stmt = $this->db->prepare("
            SELECT * FROM evaluator_votes 
            WHERE submission_id = ? AND evaluator_id = ? AND report_type = ?
        ");
        $stmt->execute([$submission_id, $evaluator_id, $report_type]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Total proposals assigned to this evaluator
    public function countAssigned($evaluator_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT s.id)
        FROM submission_evaluators se
        JOIN submissions s ON se.submission_id = s.id
        WHERE se.evaluator_id = ?
          AND s.report_type = 'proposal'
    ");
        $stmt->execute([$evaluator_id]);
        return $stmt->fetchColumn();
    }

    // On-going = has progress report(s), NO terminal report
    public function countOngoing($evaluator_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT s.id)
        FROM submission_evaluators se
        JOIN submissions s ON se.submission_id = s.id
        WHERE se.evaluator_id = ?
          AND s.report_type = 'proposal'
          AND EXISTS (
              SELECT 1 FROM progress_reports pr 
              WHERE pr.submission_id = s.id
          )
          AND NOT EXISTS (
              SELECT 1 FROM terminal_reports tr 
              WHERE tr.submission_id = s.id
          )
    ");
        $stmt->execute([$evaluator_id]);
        return $stmt->fetchColumn();
    }

    // Completed = has terminal report
    public function countCompleted($evaluator_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT s.id)
        FROM submission_evaluators se
        JOIN submissions s ON se.submission_id = s.id
        WHERE se.evaluator_id = ?
          AND s.report_type = 'proposal'
          AND EXISTS (
              SELECT 1 FROM terminal_reports tr 
              WHERE tr.submission_id = s.id
          )
    ");
        $stmt->execute([$evaluator_id]);
        return $stmt->fetchColumn();
    }
}
