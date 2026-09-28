<?php
class Submission
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAllByUser($user_id)
    {
        $stmt = $this->db->prepare("
        SELECT s.*, p.title as proposal_title
        FROM submissions s
        LEFT JOIN proposals p ON s.proposal_id = p.id
        WHERE s.user_id = ?
        ORDER BY s.created_at DESC
    ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id, $user_id = null)
    {
        $sql = "SELECT * FROM submissions WHERE id = ?";
        $params = [$id];
        if ($user_id) {
            $sql .= " AND user_id = ?";
            $params[] = $user_id;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($user_id, $proposal_id, $report_type, $form_data, $status = 'draft')
    {
        $stmt = $this->db->prepare("
            INSERT INTO submissions (user_id, proposal_id, report_type, form_data, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $proposal_id, $report_type, json_encode($form_data), $status]);
    }

    public function update($id, $form_data, $status = null)
    {
        if ($status) {
            $stmt = $this->db->prepare("UPDATE submissions SET form_data = ?, status = ? WHERE id = ?");
            return $stmt->execute([json_encode($form_data), $status, $id]);
        } else {
            $stmt = $this->db->prepare("UPDATE submissions SET form_data = ? WHERE id = ?");
            return $stmt->execute([json_encode($form_data), $id]);
        }
    }

    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE submissions SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }



    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM submissions WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function findByUserAndProposal($user_id, $proposal_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM submissions WHERE user_id = ? AND proposal_id = ? AND report_type = 'proposal' LIMIT 1");
        $stmt->execute([$user_id, $proposal_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAllWithFilters($filters = [])
    {
        $sql = "SELECT s.*, 
                   u.name as extensionist_name, 
                   p.title as proposal_title, 
                   c.abbreviation as college_abbr,
                   (SELECT GROUP_CONCAT(DISTINCT u2.name SEPARATOR ', ')
                    FROM submission_evaluators se
                    JOIN users u2 ON se.evaluator_id = u2.id
                    WHERE se.submission_id = s.id) as evaluators
            FROM submissions s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN proposals p ON s.proposal_id = p.id
            LEFT JOIN colleges c ON u.college_id = c.id
            WHERE s.report_type = 'proposal'";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND s.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['college_id'])) {
            $sql .= " AND u.college_id = ?";
            $params[] = $filters['college_id'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(s.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(s.created_at) <= ?";
            $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateAdminStatus($id, $status, $remarks = null)
    {
        // First, get current form_data to update remarks if needed
        $stmt = $this->db->prepare("SELECT form_data FROM submissions WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        $form_data = json_decode($row['form_data'], true);
        $form_data['admin_remarks'] = $remarks;

        $stmt = $this->db->prepare("UPDATE submissions SET status = ?, form_data = ? WHERE id = ?");
        return $stmt->execute([$status, json_encode($form_data), $id]);
    }


    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM submissions");
        return $stmt->fetchColumn();
    }

    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM submissions WHERE status = ?");
        $stmt->execute([$status]);
        return $stmt->fetchColumn();
    }

    public function getCollegeStats()
    {
        $stmt = $this->db->query("
        SELECT c.abbreviation, COUNT(s.id) as total
        FROM submissions s
        LEFT JOIN users u ON s.user_id = u.id
        LEFT JOIN colleges c ON u.college_id = c.id
        WHERE s.report_type = 'proposal' AND s.status = 'approved'
        GROUP BY c.id
        ORDER BY total DESC
    ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getApprovedProposals($user_id)
    {
        $stmt = $this->db->prepare("
        SELECT s.*, p.title as proposal_title 
        FROM submissions s
        LEFT JOIN proposals p ON s.proposal_id = p.id
        WHERE s.user_id = ? AND s.report_type = 'proposal' AND s.status = 'approved'
        ORDER BY s.created_at DESC
    ");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function hasTerminalReport($user_id, $proposal_id)
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM submissions 
        WHERE user_id = ? AND proposal_id = ? AND report_type = 'terminal'
    ");
        $stmt->execute([$user_id, $proposal_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getReportsByProposal($proposal_id, $report_type, $limit = null)
    {
        $sql = "SELECT * FROM submissions WHERE proposal_id = ? AND report_type = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$proposal_id, $report_type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total proposal submissions
    public function countTotalProposals()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM submissions WHERE report_type = 'proposal'");
        return $stmt->fetchColumn();
    }

    // Completed = proposals with an APPROVED terminal report
    public function countCompleted()
    {
        $stmt = $this->db->query("
        SELECT COUNT(DISTINCT s.id)
        FROM submissions s
        JOIN terminal_reports tr ON tr.submission_id = s.id
        WHERE s.report_type = 'proposal' 
          AND tr.status = 'approved'
    ");
        return $stmt->fetchColumn();
    }

    // On-going = approved proposals without an approved terminal report
    public function countOngoing()
    {
        $stmt = $this->db->query("
        SELECT COUNT(DISTINCT s.id)
        FROM submissions s
        WHERE s.report_type = 'proposal'
          AND s.status = 'approved'
          AND NOT EXISTS (
              SELECT 1 FROM terminal_reports tr 
              WHERE tr.submission_id = s.id AND tr.status = 'approved'
          )
    ");
        return $stmt->fetchColumn();
    }

    // Count proposal submissions for a specific user
public function countProposalsByUser($user_id)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM submissions 
        WHERE user_id = ? AND report_type = 'proposal'
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

// Count pending proposals for a user (not yet finalized)
public function countPendingByUser($user_id)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(*) FROM submissions 
        WHERE user_id = ? 
          AND report_type = 'proposal'
          AND status IN ('draft', 'submitted', 'pending_evaluation', 'under_evaluation', 'revision')
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

// Count completed (approved terminal report exists)
public function countCompletedByUser($user_id)
{
    $stmt = $this->db->prepare("
        SELECT COUNT(DISTINCT s.id)
        FROM submissions s
        JOIN terminal_reports tr ON tr.submission_id = s.id
        WHERE s.user_id = ?
          AND s.report_type = 'proposal'
          AND tr.status = 'approved'
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

// Get filtered submissions for a user
public function getFilteredByUser($user_id, $filter)
{
    $baseSelect = "
        SELECT s.*, p.title as proposal_title
        FROM submissions s
        LEFT JOIN proposals p ON s.proposal_id = p.id
        WHERE s.user_id = ? AND s.report_type = 'proposal'
    ";
    
    if ($filter === 'pending') {
        $stmt = $this->db->prepare($baseSelect . "
            AND s.status IN ('draft', 'submitted', 'pending_evaluation', 'under_evaluation', 'revision')
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$user_id]);
    } elseif ($filter === 'completed') {
        $stmt = $this->db->prepare($baseSelect . "
            AND s.id IN (
                SELECT tr.submission_id FROM terminal_reports tr 
                WHERE tr.status = 'approved'
            )
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$user_id]);
    } else {
        $stmt = $this->db->prepare($baseSelect . " ORDER BY s.created_at DESC");
        $stmt->execute([$user_id]);
    }
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
