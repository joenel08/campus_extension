<?php
class EvaluatorVote
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }




    public function saveVote($evaluator_id, $submission_id, $report_type, $vote, $comments = null)
    {
        $stmt = $this->db->prepare("
        INSERT INTO evaluator_votes (evaluator_id, submission_id, report_type, vote, comments)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE vote = ?, comments = ?
    ");
        return $stmt->execute([$evaluator_id, $submission_id, $report_type, $vote, $comments, $vote, $comments]);
    }

    public function getByEvaluatorAndSubmission($evaluator_id, $submission_id, $report_type = 'proposal')
    {
        $stmt = $this->db->prepare("SELECT * FROM evaluator_votes WHERE evaluator_id = ? AND submission_id = ? AND report_type = ?");
        $stmt->execute([$evaluator_id, $submission_id, $report_type]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getVotesForSubmission($submission_id, $report_type = 'proposal')
{
    $stmt = $this->db->prepare("
        SELECT ev.*, u.name as evaluator_name
        FROM evaluator_votes ev
        JOIN users u ON ev.evaluator_id = u.id
        WHERE ev.submission_id = ? AND ev.report_type = ?
        ORDER BY ev.submitted_at DESC
    ");
    $stmt->execute([$submission_id, $report_type]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function countVotes($submission_id)
    {
        $stmt = $this->db->prepare("
            SELECT vote, COUNT(*) as count
            FROM evaluator_votes
            WHERE submission_id = ?
            GROUP BY vote
        ");
        $stmt->execute([$submission_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function getMajorityVote($submission_id, $report_type = 'proposal')
{
    $stmt = $this->db->prepare("
        SELECT vote, COUNT(*) as count
        FROM evaluator_votes
        WHERE submission_id = ? AND report_type = ?
        GROUP BY vote
        ORDER BY count DESC
        LIMIT 1
    ");
    $stmt->execute([$submission_id, $report_type]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['vote'] : null;
}
    public function getBySubmissionAndEvaluator($submission_id, $evaluator_id, $report_type = 'proposal')
    {
        $stmt = $this->db->prepare("
        SELECT * FROM evaluator_votes 
        WHERE submission_id = ? AND evaluator_id = ? AND report_type = ?
    ");
        $stmt->execute([$submission_id, $evaluator_id, $report_type]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
