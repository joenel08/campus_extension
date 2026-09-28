<?php
class Proposal
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Get all proposals with college info
    public function getAll($academic_year_id = null)
    {
        $sql = "
            SELECT p.*, 
                   c.abbreviation as college_abbr,
                   ay.year_label as academic_year_label
            FROM proposals p
            LEFT JOIN colleges c ON p.college_id = c.id
            LEFT JOIN academic_years ay ON p.academic_year_id = ay.id
            WHERE 1=1
        ";
        $params = [];

        if ($academic_year_id) {
            $sql .= " AND p.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("
            SELECT p.*, 
                   c.abbreviation as college_abbr,
                   ay.year_label as academic_year_label
            FROM proposals p
            LEFT JOIN colleges c ON p.college_id = c.id
            LEFT JOIN academic_years ay ON p.academic_year_id = ay.id
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

   public function create($title, $college_id, $category, $status, $opening_date, $closing_date, $description, $file_path = null, $academic_year_id = null)
    {
        $stmt = $this->db->prepare("
            INSERT INTO proposals 
            (title, college_id, category, status, opening_date, closing_date, description, file_path, academic_year_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $title, $college_id, $category, $status,
            $opening_date, $closing_date, $description,
            $file_path, $academic_year_id
        ]);
    }

     public function update($id, $title, $college_id, $category, $status, $opening_date, $closing_date, $description, $file_path = null, $academic_year_id = null)
    {
        if ($file_path) {
            $stmt = $this->db->prepare("
                UPDATE proposals
                SET title = ?, college_id = ?, category = ?, status = ?, 
                    opening_date = ?, closing_date = ?, description = ?, 
                    file_path = ?, academic_year_id = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $title, $college_id, $category, $status,
                $opening_date, $closing_date, $description,
                $file_path, $academic_year_id, $id
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE proposals
                SET title = ?, college_id = ?, category = ?, status = ?, 
                    opening_date = ?, closing_date = ?, description = ?, 
                    academic_year_id = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $title, $college_id, $category, $status,
                $opening_date, $closing_date, $description,
                $academic_year_id, $id
            ]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM proposals WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE proposals SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }


    public function getOpenByCollege($college_id)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM proposals
        WHERE status = 'open' AND college_id = ?
        ORDER BY created_at DESC
    ");
        $stmt->execute([$college_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEvaluators($submission_id)
    {
        $stmt = $this->db->prepare("
        SELECT u.id, u.name, u.email
        FROM submission_evaluators se
        JOIN users u ON se.evaluator_id = u.id
        WHERE se.submission_id = ?
    ");
        $stmt->execute([$submission_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignEvaluator($submission_id, $evaluator_id)
    {
        $stmt = $this->db->prepare("INSERT IGNORE INTO submission_evaluators (submission_id, evaluator_id) VALUES (?, ?)");
        return $stmt->execute([$submission_id, $evaluator_id]);
    }

    public function removeEvaluator($proposal_id, $evaluator_id)
    {
        $stmt = $this->db->prepare("DELETE FROM proposal_evaluators WHERE proposal_id = ? AND evaluator_id = ?");
        return $stmt->execute([$proposal_id, $evaluator_id]);
    }
}
