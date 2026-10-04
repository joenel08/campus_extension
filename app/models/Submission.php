<?php
class Submission
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // ==================== GET ALL BY USER (with academic year filter) ====================
    public function getAllByUser($user_id, $academic_year_id = null)
    {
        $sql = "
        SELECT s.*, 
               p.title as proposal_title,
               ay.year_label as academic_year_label
        FROM submissions s
        LEFT JOIN proposals p ON s.proposal_id = p.id
        LEFT JOIN academic_years ay ON s.academic_year_id = ay.id
        WHERE s.user_id = ?
    ";
        $params = [$user_id];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // ==================== FIND ====================
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

    // ==================== CREATE (with academic year) ====================
    public function create($user_id, $proposal_id, $report_type, $form_data, $status = 'draft', $academic_year_id = null)
    {
        $stmt = $this->db->prepare("
            INSERT INTO submissions (user_id, proposal_id, report_type, form_data, status, academic_year_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $user_id,
            $proposal_id,
            $report_type,
            json_encode($form_data),
            $status,
            $academic_year_id
        ]);
    }

    // ==================== UPDATE ====================
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

    // ==================== UPDATE STATUS ====================
    public function updateStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE submissions SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // ==================== DELETE ====================
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM submissions WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ==================== FIND BY USER AND PROPOSAL ====================
    public function findByUserAndProposal($user_id, $proposal_id, $academic_year_id = null)
    {
        $sql = "SELECT * FROM submissions WHERE user_id = ? AND proposal_id = ? AND report_type = 'proposal'";
        $params = [$user_id, $proposal_id];

        if ($academic_year_id) {
            $sql .= " AND academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $sql .= " LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==================== GET ALL WITH FILTERS (admin monitoring) ====================
    public function getAllWithFilters($filters = [])
    {
        $sql = "SELECT s.*, 
                   u.name as extensionist_name, 
                   p.title as proposal_title, 
                   c.abbreviation as college_abbr,
                   ay.year_label as academic_year_label,
                   (SELECT GROUP_CONCAT(DISTINCT u2.name SEPARATOR ', ')
                    FROM submission_evaluators se
                    JOIN users u2 ON se.evaluator_id = u2.id
                    WHERE se.submission_id = s.id) as evaluators
            FROM submissions s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN proposals p ON s.proposal_id = p.id
            LEFT JOIN colleges c ON u.college_id = c.id
            LEFT JOIN academic_years ay ON s.academic_year_id = ay.id
            WHERE s.report_type = 'proposal'";
        $params = [];

        // --- special status filters ---
        if (!empty($filters['status']) && $filters['status'] === 'ongoing') {
            $sql .= " AND EXISTS (
                SELECT 1 FROM progress_reports pr 
                WHERE pr.submission_id = s.id
              )
              AND NOT EXISTS (
                SELECT 1 FROM terminal_reports tr 
                WHERE tr.submission_id = s.id
              )";
        } elseif (!empty($filters['status']) && $filters['status'] === 'completed') {
            $sql .= " AND EXISTS (
                SELECT 1 FROM terminal_reports tr 
                WHERE tr.submission_id = s.id
              )";
        } elseif (!empty($filters['status'])) {
            $sql .= " AND s.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['college_id'])) {
            $sql .= " AND u.college_id = ?";
            $params[] = $filters['college_id'];
        }
        if (!empty($filters['academic_year_id'])) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $filters['academic_year_id'];
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
    // ==================== UPDATE ADMIN STATUS ====================
    public function updateAdminStatus($id, $status, $remarks = null)
    {
        $stmt = $this->db->prepare("SELECT form_data FROM submissions WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return false;

        $form_data = json_decode($row['form_data'], true);
        $form_data['admin_remarks'] = $remarks;

        $stmt = $this->db->prepare("UPDATE submissions SET status = ?, form_data = ? WHERE id = ?");
        return $stmt->execute([$status, json_encode($form_data), $id]);
    }

    // ==================== COUNTS (admin dashboard) ====================
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

    // ==================== COLLEGE STATS (with academic year) ====================
    public function getCollegeStats($academic_year_id = null)
    {
        $sql = "
            SELECT c.abbreviation, COUNT(s.id) as total
            FROM submissions s
            LEFT JOIN users u ON s.user_id = u.id
            LEFT JOIN colleges c ON u.college_id = c.id
            WHERE s.report_type = 'proposal' AND s.status = 'approved'
        ";
        $params = [];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $sql .= " GROUP BY c.id ORDER BY total DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== APPROVED PROPOSALS ====================
    public function getApprovedProposals($user_id, $academic_year_id = null)
    {
        $sql = "
            SELECT s.*, p.title as proposal_title 
            FROM submissions s
            LEFT JOIN proposals p ON s.proposal_id = p.id
            WHERE s.user_id = ? AND s.report_type = 'proposal' AND s.status = 'approved'
        ";
        $params = [$user_id];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==================== HAS TERMINAL REPORT ====================
    public function hasTerminalReport($user_id, $proposal_id)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM submissions 
            WHERE user_id = ? AND proposal_id = ? AND report_type = 'terminal'
        ");
        $stmt->execute([$user_id, $proposal_id]);
        return $stmt->fetchColumn() > 0;
    }

    // ==================== REPORTS BY PROPOSAL ====================
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

    // ==================== ADMIN DASHBOARD COUNTS (with academic year) ====================
    public function countTotalProposals($academic_year_id = null)
    {
        $sql = "SELECT COUNT(*) FROM submissions WHERE report_type = 'proposal'";
        $params = [];

        if ($academic_year_id) {
            $sql .= " AND academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }




    // ==================== USER COUNTS (with academic year) ====================
    public function countProposalsByUser($user_id, $academic_year_id = null)
    {
        $sql = "SELECT COUNT(*) FROM submissions WHERE user_id = ? AND report_type = 'proposal'";
        $params = [$user_id];

        if ($academic_year_id) {
            $sql .= " AND academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function countPendingByUser($user_id, $academic_year_id = null)
    {
        $sql = "
            SELECT COUNT(*) FROM submissions 
            WHERE user_id = ? 
              AND report_type = 'proposal'
              AND status IN ('draft', 'submitted', 'pending_evaluation', 'under_evaluation', 'revision')
        ";
        $params = [$user_id];

        if ($academic_year_id) {
            $sql .= " AND academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function countCompletedByUser($user_id, $academic_year_id = null)
    {
        $sql = "
            SELECT COUNT(DISTINCT s.id)
            FROM submissions s
            JOIN terminal_reports tr ON tr.submission_id = s.id
            WHERE s.user_id = ?
              AND s.report_type = 'proposal'
              AND tr.status = 'approved'
        ";
        $params = [$user_id];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // ==================== FILTERED BY USER (with academic year) ====================
    public function getFilteredByUser($user_id, $filter, $academic_year_id = null)
    {
        $sql = "
        SELECT s.*, 
               p.title as proposal_title,
               ay.year_label as academic_year_label
        FROM submissions s
        LEFT JOIN proposals p ON s.proposal_id = p.id
        LEFT JOIN academic_years ay ON s.academic_year_id = ay.id
        WHERE s.user_id = ? AND s.report_type = 'proposal'
    ";
        $params = [$user_id];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        if ($filter === 'pending') {
            $sql .= " AND s.status IN ('draft', 'submitted', 'pending_evaluation', 'under_evaluation', 'revision')";
        } elseif ($filter === 'completed') {
            $sql .= " AND s.id IN (
            SELECT tr.submission_id FROM terminal_reports tr 
            WHERE tr.status = 'approved'
        )";
        }

        $sql .= " ORDER BY s.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // On-going = has progress report(s) AND no terminal report yet
    public function countOngoing($academic_year_id = null)
    {
        $sql = "
        SELECT COUNT(DISTINCT s.id)
        FROM submissions s
        WHERE s.report_type = 'proposal'
          AND EXISTS (
              SELECT 1 FROM progress_reports pr 
              WHERE pr.submission_id = s.id
          )
          AND NOT EXISTS (
              SELECT 1 FROM terminal_reports tr 
              WHERE tr.submission_id = s.id
          )
    ";
        $params = [];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    // Completed = has terminal report (regardless of progress)
    public function countCompleted($academic_year_id = null)
    {
        $sql = "
        SELECT COUNT(DISTINCT s.id)
        FROM submissions s
        WHERE s.report_type = 'proposal'
          AND EXISTS (
              SELECT 1 FROM terminal_reports tr 
              WHERE tr.submission_id = s.id
          )
    ";
        $params = [];

        if ($academic_year_id) {
            $sql .= " AND s.academic_year_id = ?";
            $params[] = $academic_year_id;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
}
