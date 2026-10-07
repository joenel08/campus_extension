<?php

namespace Evaluator;

require_once __DIR__ . '/../../../models/Submission.php';
require_once __DIR__ . '/../../../models/EvaluatorRating.php';
require_once __DIR__ . '/../../../models/EvaluatorVote.php';
require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/EvaluationGroup.php';
require_once __DIR__ . '/../../../models/EvaluationCriteria.php';
require_once __DIR__ . '/../../../models/Notification.php';
require_once __DIR__ . '/../../../models/DetailedProposal.php';

class EvaluationController extends \EvaluatorBaseController
{
    private $submissionModel;
    private $ratingModel;
    private $voteModel;
    private $proposalModel;
    private $groupModel;
    private $criteriaModel;
    private $notificationModel;
    private $detailedProposalModel;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $pdo = new \PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->db = $pdo;
        $this->submissionModel       = new \Submission($pdo);
        $this->ratingModel           = new \EvaluatorRating($pdo);
        $this->voteModel             = new \EvaluatorVote($pdo);
        $this->proposalModel         = new \Proposal($pdo);
        $this->groupModel            = new \EvaluationGroup($pdo);
        $this->criteriaModel         = new \EvaluationCriteria($pdo);
        $this->notificationModel     = new \Notification($pdo);
        $this->detailedProposalModel = new \DetailedProposal($pdo);
    }

    // ============ DASHBOARD ============
    public function dashboard()
    {
        $evaluator_id = $_SESSION['user_id'];

        // All proposal submissions this evaluator is assigned to
        $stmt = $this->db->prepare("
            SELECT s.id AS submission_id,
                   s.proposal_id,
                   s.academic_year_id,
                   s.status AS proposal_status,
                   p.title  AS proposal_title,
                   u.name   AS extensionist_name
            FROM submission_evaluators se
            JOIN submissions s ON se.submission_id = s.id
            JOIN proposals   p ON s.proposal_id = p.id
            JOIN users       u ON s.user_id = u.id
            WHERE se.evaluator_id = ?
              AND s.report_type = 'proposal'
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$evaluator_id]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $grouped = [];

        foreach ($rows as $r) {
            $sid = $r['submission_id'];

            // DETAILED PROPOSAL for this submission
            $dp = $this->detailedProposalModel->findBySubmission($sid);

            // Evaluator's own vote on the detailed proposal (if any)
            $dpVote = null;
            if ($dp) {
                $dpVote = $this->voteModel->getBySubmissionAndEvaluator($dp['id'], $evaluator_id, 'detailed_proposal');
            }

            $grouped[$sid] = [
                'submission_id'     => $sid,
                'proposal_title'    => $r['proposal_title'],
                'extensionist_name' => $r['extensionist_name'],
                'detailed_proposal' => $dp,
                'detailed_vote'     => $dpVote,
                'progress_reports'  => [],
                'terminal_report'   => null,
            ];

            // PROGRESS REPORTS + this evaluator's vote on each
            $prStmt = $this->db->prepare("
                SELECT * FROM progress_reports
                WHERE submission_id = ?
                ORDER BY created_at ASC
            ");
            $prStmt->execute([$sid]);
            foreach ($prStmt->fetchAll(\PDO::FETCH_ASSOC) as $pr) {
                $v = $this->voteModel->getBySubmissionAndEvaluator($pr['id'], $evaluator_id, 'progress');
                $pr['evaluated'] = (bool)$v;
                $pr['vote']      = $v['vote'] ?? null;
                $grouped[$sid]['progress_reports'][] = $pr;
            }

            // TERMINAL REPORT
            $trStmt = $this->db->prepare("SELECT * FROM terminal_reports WHERE submission_id = ? LIMIT 1");
            $trStmt->execute([$sid]);
            $tr = $trStmt->fetch(\PDO::FETCH_ASSOC);
            if ($tr) {
                $v = $this->voteModel->getBySubmissionAndEvaluator($tr['id'], $evaluator_id, 'terminal');
                $tr['evaluated'] = (bool)$v;
                $tr['vote']      = $v['vote'] ?? null;
                $grouped[$sid]['terminal_report'] = $tr;
            }
        }

        $this->render('evaluator/evaluations', ['grouped' => $grouped]);
    }

    // ============ EVALUATE FORM ============
    public function evaluate()
    {
        $id           = $_GET['id'] ?? 0;
        $type         = $_GET['type'] ?? 'proposal';
        $evaluator_id = $_SESSION['user_id'];

        if (!$id) {
            $_SESSION['error'] = 'Invalid ID.';
            header('Location: /evaluator/dashboard');
            exit;
        }

        $parentFormData = null;

        if ($type === 'proposal') {
            $stmt = $this->db->prepare("
                SELECT s.*, p.title AS proposal_title, u.name AS extensionist_name
                FROM submissions s
                JOIN proposals p ON s.proposal_id = p.id
                JOIN users     u ON s.user_id = u.id
                WHERE s.id = ?
            ");
            $stmt->execute([$id]);
            $submission = $stmt->fetch(\PDO::FETCH_ASSOC);
            $form_data  = json_decode($submission['form_data'] ?? '{}', true);

        } elseif ($type === 'detailed_proposal') {
            $stmt = $this->db->prepare("
                SELECT dp.*,
                       dp.id            AS detailed_proposal_id,
                       dp.submission_id AS parent_submission_id,
                       dp.attachment    AS attachment,
                       s.proposal_id,
                       s.form_data      AS parent_form_data,
                       p.title          AS proposal_title,
                       u.name           AS extensionist_name
                FROM detailed_proposals dp
                JOIN submissions s ON dp.submission_id = s.id
                JOIN proposals   p ON s.proposal_id = p.id
                JOIN users       u ON dp.user_id = u.id
                WHERE dp.id = ?
            ");
            $stmt->execute([$id]);
            $submission = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$submission) {
                $_SESSION['error'] = 'Detailed proposal not found.';
                header('Location: /evaluator/dashboard');
                exit;
            }

            $form_data      = ['attachment' => $submission['attachment']];
            $parentFormData = json_decode($submission['parent_form_data'] ?? '{}', true);

        } elseif ($type === 'progress') {
            $stmt = $this->db->prepare("
                SELECT pr.*,
                       s.proposal_id,
                       s.id        AS parent_submission_id,
                       s.form_data AS parent_form_data,
                       p.title     AS proposal_title,
                       u.name      AS extensionist_name
                FROM progress_reports pr
                JOIN submissions s ON pr.submission_id = s.id
                JOIN proposals   p ON s.proposal_id = p.id
                JOIN users       u ON pr.user_id = u.id
                WHERE pr.id = ?
            ");
            $stmt->execute([$id]);
            $submission     = $stmt->fetch(\PDO::FETCH_ASSOC);
            $form_data      = [];
            $parentFormData = json_decode($submission['parent_form_data'] ?? '{}', true);

        } else { // terminal
            $stmt = $this->db->prepare("
                SELECT tr.*,
                       s.proposal_id,
                       s.id        AS parent_submission_id,
                       s.form_data AS parent_form_data,
                       p.title     AS proposal_title,
                       u.name      AS extensionist_name
                FROM terminal_reports tr
                JOIN submissions s ON tr.submission_id = s.id
                JOIN proposals   p ON s.proposal_id = p.id
                JOIN users       u ON tr.user_id = u.id
                WHERE tr.id = ?
            ");
            $stmt->execute([$id]);
            $submission     = $stmt->fetch(\PDO::FETCH_ASSOC);
            $form_data      = [];
            $parentFormData = json_decode($submission['parent_form_data'] ?? '{}', true);
        }

        if (!$submission) {
            $_SESSION['error'] = 'Report not found.';
            header('Location: /evaluator/dashboard');
            exit;
        }

        // Existing vote
        $vote         = $this->voteModel->getBySubmissionAndEvaluator($id, $evaluator_id, $type);
        $selectedVote = $vote ? $vote['vote'] : null;
        $comments     = $vote ? $vote['comments'] : '';

        // Readonly if finalized
        $readonly = in_array($submission['status'], ['approved', 'revision', 'rejected'], true);

        $this->render('evaluate', [
            'submission'        => $submission,
            'form_data'         => $form_data,
            'parentFormData'    => $parentFormData,
            'proposal_title'    => $submission['proposal_title'] ?? 'N/A',
            'extensionist_name' => $submission['extensionist_name'] ?? 'N/A',
            'selectedVote'      => $selectedVote,
            'comments'          => $comments,
            'submission_id'     => $id,
            'report_type'       => $type,
            'readonly'          => $readonly,
        ]);
    }

    // ============ SAVE EVALUATION ============
    public function saveEvaluation()
    {
        $submission_id = $_POST['submission_id'] ?? 0;
        $report_type   = $_POST['report_type'] ?? 'proposal';
        $evaluator_id  = $_SESSION['user_id'];
        $vote          = $_POST['vote'] ?? '';
        $comments      = trim($_POST['comments'] ?? '');

        if (!$submission_id || !$vote) {
            $_SESSION['error'] = 'Missing required fields.';
            header('Location: /evaluator/dashboard');
            exit;
        }

        if (!in_array($vote, ['approve', 'revision', 'decline'], true)) {
            $_SESSION['error'] = 'Invalid vote.';
            header('Location: /evaluator/evaluate?id=' . $submission_id . '&type=' . $report_type);
            exit;
        }

        // Save vote
        $this->voteModel->saveVote($evaluator_id, $submission_id, $report_type, $vote, $comments);

        // === NOTIFY EXTENSIONIST ===
        if ($report_type === 'proposal') {
            $submission = $this->submissionModel->find($submission_id);
            if ($submission) {
                $this->notificationModel->create(
                    $submission['user_id'],
                    'evaluation_submitted',
                    'Your Proposal Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }

        } elseif ($report_type === 'detailed_proposal') {
            $stmt = $this->db->prepare("SELECT user_id FROM detailed_proposals WHERE id = ?");
            $stmt->execute([$submission_id]);
            $uid = $stmt->fetchColumn();
            if ($uid) {
                $this->notificationModel->create(
                    $uid,
                    'evaluation_submitted',
                    'Your Detailed Proposal Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }

        } elseif ($report_type === 'progress') {
            $stmt = $this->db->prepare("SELECT user_id FROM progress_reports WHERE id = ?");
            $stmt->execute([$submission_id]);
            $uid = $stmt->fetchColumn();
            if ($uid) {
                $this->notificationModel->create(
                    $uid,
                    'evaluation_submitted',
                    'Your Progress Report Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }

        } else { // terminal
            $stmt = $this->db->prepare("SELECT user_id FROM terminal_reports WHERE id = ?");
            $stmt->execute([$submission_id]);
            $uid = $stmt->fetchColumn();
            if ($uid) {
                $this->notificationModel->create(
                    $uid,
                    'evaluation_submitted',
                    'Your Terminal Report Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }
        }

        // === NOTIFY ADMINS ===
        $adminIds = $this->notificationModel->getAdmins();
        $this->notificationModel->createBulk(
            $adminIds,
            'evaluation_submitted',
            'Evaluation Submitted',
            ($_SESSION['user_name'] ?? 'Unknown') . ' evaluated a ' . $report_type . ' report (vote: ' . ucfirst($vote) . ').',
            '/admin/monitoring'
        );

        // === MAJORITY VOTE CHECK ===
        $assigned     = $this->getAssignedEvaluatorsForReport($submission_id, $report_type);
        $assigned_ids = array_column($assigned, 'id');
        $total        = count($assigned_ids);

        $voted = 0;
        foreach ($assigned_ids as $aid) {
            $v = $this->voteModel->getBySubmissionAndEvaluator($submission_id, $aid, $report_type);
            if ($v) $voted++;
        }

        if ($total > 0 && $voted >= $total) {
            $majority = $this->voteModel->getMajorityVote($submission_id, $report_type);

            $statusMap = [
                'approve'  => 'approved',
                'revision' => 'revision',
                'decline'  => 'rejected',
            ];
            $newStatus = $statusMap[$majority] ?? null;

            if ($newStatus) {
                if ($report_type === 'proposal') {
                    $stmt = $this->db->prepare("UPDATE submissions SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);

                } elseif ($report_type === 'detailed_proposal') {
                    $stmt = $this->db->prepare("UPDATE detailed_proposals SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);

                } elseif ($report_type === 'progress') {
                    $stmt = $this->db->prepare("UPDATE progress_reports SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);

                } else { // terminal
                    $stmt = $this->db->prepare("UPDATE terminal_reports SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);
                }
            }
        }

        $_SESSION['success'] = 'Evaluation submitted successfully.';
        header('Location: /evaluator/dashboard');
        exit;
    }

    // ============ HELPER: assigned evaluators for a report ============
    private function getAssignedEvaluatorsForReport($submission_id, $report_type)
    {
        if ($report_type === 'proposal') {
            $stmt = $this->db->prepare("
                SELECT u.id FROM submission_evaluators se
                JOIN users u ON se.evaluator_id = u.id
                WHERE se.submission_id = ?
            ");
            $stmt->execute([$submission_id]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        if ($report_type === 'detailed_proposal') {
            $stmt = $this->db->prepare("SELECT submission_id FROM detailed_proposals WHERE id = ?");
            $stmt->execute([$submission_id]);
            $parent_sid = $stmt->fetchColumn();
            if (!$parent_sid) return [];

            $stmt = $this->db->prepare("
                SELECT u.id FROM submission_evaluators se
                JOIN users u ON se.evaluator_id = u.id
                WHERE se.submission_id = ?
            ");
            $stmt->execute([$parent_sid]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }

        // progress / terminal — resolve parent from their table
        if ($report_type === 'progress') {
            $stmt = $this->db->prepare("SELECT submission_id FROM progress_reports WHERE id = ?");
            $stmt->execute([$submission_id]);
            $parent_sid = $stmt->fetchColumn();
        } else {
            $stmt = $this->db->prepare("SELECT submission_id FROM terminal_reports WHERE id = ?");
            $stmt->execute([$submission_id]);
            $parent_sid = $stmt->fetchColumn();
        }

        if (!$parent_sid) return [];

        $stmt = $this->db->prepare("
            SELECT u.id FROM submission_evaluators se
            JOIN users u ON se.evaluator_id = u.id
            WHERE se.submission_id = ?
        ");
        $stmt->execute([$parent_sid]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}