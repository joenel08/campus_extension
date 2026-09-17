<?php
class EvaluatorRating
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function saveRating($evaluator_id, $submission_id, $report_type, $criterion_id, $rating)
    {
        $stmt = $this->db->prepare("
        INSERT INTO evaluator_ratings (evaluator_id, submission_id, report_type, criterion_id, rating)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE rating = ?
    ");
        return $stmt->execute([$evaluator_id, $submission_id, $report_type, $criterion_id, $rating, $rating]);
    }

    public function getRatingsForSubmission($submission_id, $report_type = 'proposal')
    {
        $stmt = $this->db->prepare("
        SELECT er.*, u.name as evaluator_name, ec.criteria_text
        FROM evaluator_ratings er
        JOIN users u ON er.evaluator_id = u.id
        JOIN eval_criteria ec ON er.criterion_id = ec.id
        WHERE er.submission_id = ? AND er.report_type = ?
        ORDER BY u.id, ec.display_order
    ");
        $stmt->execute([$submission_id, $report_type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByEvaluatorAndSubmission($evaluator_id, $submission_id)
    {
        $stmt = $this->db->prepare("
            SELECT er.*, ec.criteria_text 
            FROM evaluator_ratings er
            JOIN eval_criteria ec ON er.criterion_id = ec.id
            WHERE er.evaluator_id = ? AND er.submission_id = ?
        ");
        $stmt->execute([$evaluator_id, $submission_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function deleteRatings($evaluator_id, $submission_id)
    {
        $stmt = $this->db->prepare("DELETE FROM evaluator_ratings WHERE evaluator_id = ? AND submission_id = ?");
        return $stmt->execute([$evaluator_id, $submission_id]);
    }
}
