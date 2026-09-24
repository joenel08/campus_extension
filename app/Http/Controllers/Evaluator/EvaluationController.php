<?php

namespace Evaluator;

require_once __DIR__ . '/../../../models/Submission.php';
require_once __DIR__ . '/../../../models/EvaluatorRating.php';
require_once __DIR__ . '/../../../models/EvaluatorVote.php';
require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/EvaluationGroup.php';
require_once __DIR__ . '/../../../models/EvaluationCriteria.php';
require_once __DIR__ . '/../../../models/Notification.php'; 

class EvaluationController extends \EvaluatorBaseController
{
    private $submissionModel;
    private $ratingModel;
    private $voteModel;
    private $proposalModel;
    private $groupModel;
    private $criteriaModel;
    private $db;
    private $notificationModel;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $pdo = new \PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;
        $this->submissionModel = new \Submission($pdo);
        $this->ratingModel = new \EvaluatorRating($pdo);
        $this->voteModel = new \EvaluatorVote($pdo);
        $this->proposalModel = new \Proposal($pdo);
        $this->groupModel = new \EvaluationGroup($pdo);
        $this->criteriaModel = new \EvaluationCriteria($pdo);
        $this->notificationModel = new \Notification($pdo);
    }

    // Evaluator dashboard – list assigned proposals pending evaluation
    public function dashboard()
    {
        $evaluator_id = $_SESSION['user_id'];
        // Get all proposals assigned to this evaluator where status is 'pending_evaluation'
        $stmt = $this->db->prepare("
            SELECT s.*, p.title as proposal_title, u.name as extensionist_name
            FROM submissions s
            JOIN proposals p ON s.proposal_id = p.id
            JOIN users u ON s.user_id = u.id
            JOIN proposal_evaluators pe ON p.id = pe.proposal_id
            WHERE pe.evaluator_id = ?
            AND s.report_type = 'proposal'
            AND s.status IN ('pending_evaluation', 'under_evaluation')
            ORDER BY s.created_at DESC
        ");
        $stmt->execute([$evaluator_id]);
        $assignments = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Check if evaluator has already voted on each
        foreach ($assignments as &$a) {
            $vote = $this->voteModel->getByEvaluatorAndSubmission($evaluator_id, $a['id']);
            $a['voted'] = $vote ? true : false;
            $a['vote'] = $vote ? $vote['vote'] : null;
        }

        $this->render('evaluator/dashboard', ['assignments' => $assignments]);
    }
   public function evaluate()
{
    $id = $_GET['id'] ?? 0;
    $type = $_GET['type'] ?? 'proposal';
    $evaluator_id = $_SESSION['user_id'];

    if (!$id) {
        $_SESSION['error'] = 'Invalid ID.';
        header('Location: /evaluator/dashboard');
        exit;
    }

    if ($type === 'proposal') {
        $stmt = $this->db->prepare("
            SELECT s.*, p.title as proposal_title, u.name as extensionist_name
            FROM submissions s
            JOIN proposals p ON s.proposal_id = p.id
            JOIN users u ON s.user_id = u.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $submission = $stmt->fetch(\PDO::FETCH_ASSOC);
        $form_data = json_decode($submission['form_data'] ?? '{}', true);
    } elseif ($type === 'progress') {
        $stmt = $this->db->prepare("
            SELECT pr.*, s.proposal_id, s.id as parent_submission_id, 
                   p.title as proposal_title, u.name as extensionist_name
            FROM progress_reports pr
            JOIN submissions s ON pr.submission_id = s.id
            JOIN proposals p ON s.proposal_id = p.id
            JOIN users u ON pr.user_id = u.id
            WHERE pr.id = ?
        ");
        $stmt->execute([$id]);
        $submission = $stmt->fetch(\PDO::FETCH_ASSOC);
        $form_data = []; // no JSON, fields are in $submission
    } else {
        $stmt = $this->db->prepare("
            SELECT tr.*, s.proposal_id, s.id as parent_submission_id,
                   p.title as proposal_title, u.name as extensionist_name
            FROM terminal_reports tr
            JOIN submissions s ON tr.submission_id = s.id
            JOIN proposals p ON s.proposal_id = p.id
            JOIN users u ON tr.user_id = u.id
            WHERE tr.id = ?
        ");
        $stmt->execute([$id]);
        $submission = $stmt->fetch(\PDO::FETCH_ASSOC);
        $form_data = []; // no JSON
    }

    if (!$submission) {
        $_SESSION['error'] = 'Report not found.';
        header('Location: /evaluator/dashboard');
        exit;
    }

    // Get existing vote
    $vote = $this->voteModel->getBySubmissionAndEvaluator($id, $evaluator_id, $type);
    $selectedVote = $vote ? $vote['vote'] : null;
    $comments = $vote ? $vote['comments'] : '';

    // Criteria (only for proposals)
    $groupedCriteria = [];
    $ratingMap = [];
    if ($type === 'proposal') {
        $groupedCriteria = $this->criteriaModel->getGrouped();
        $ratings = $this->ratingModel->getByEvaluatorAndSubmission($evaluator_id, $id, 'proposal');
        foreach ($ratings as $r) {
            $ratingMap[$r['criterion_id']] = $r['rating'];
        }
    }

    $this->render('evaluate', [
        'submission'        => $submission,
        'form_data'         => $form_data,
        'proposal_title'    => $submission['proposal_title'] ?? 'N/A',
        'extensionist_name' => $submission['extensionist_name'] ?? 'N/A',
        'groupedCriteria'   => $groupedCriteria,
        'ratingMap'         => $ratingMap,
        'selectedVote'      => $selectedVote,
        'comments'          => $comments,
        'submission_id'     => $id,
        'report_type'       => $type,
        'readonly'          => false,
    ]);
}
    public function saveEvaluation()
    {
        $submission_id = $_POST['submission_id'] ?? 0;
        $report_type = $_POST['report_type'] ?? 'proposal';
        $evaluator_id = $_SESSION['user_id'];
        $ratings = $_POST['ratings'] ?? [];
        $vote = $_POST['vote'] ?? '';
        $comments = trim($_POST['comments'] ?? '');

        if (!$submission_id || !$vote) {
            $_SESSION['error'] = 'Missing required fields.';
            header('Location: /evaluator/dashboard');
            exit;
        }

        if (!in_array($vote, ['approve', 'revision', 'decline'])) {
            $_SESSION['error'] = 'Invalid vote.';
            header('Location: /evaluator/evaluate?id=' . $submission_id . '&type=' . $report_type);
            exit;
        }

        // Save ratings (only for proposals)
        if ($report_type === 'proposal' && !empty($ratings)) {
            foreach ($ratings as $criterion_id => $rating) {
                if ($rating >= 1 && $rating <= 5) {
                    $this->ratingModel->saveRating($evaluator_id, $submission_id, $report_type, $criterion_id, $rating);
                }
            }
        }

        // Save vote
        $this->voteModel->saveVote($evaluator_id, $submission_id, $report_type, $vote, $comments);
// === NOTIFY ADMINS + EXTENSIONIST ===
        if ($report_type === 'proposal') {
            $submission = $this->submissionModel->find($submission_id);
            if ($submission) {
                // Notify extensionist
                $this->notificationModel->create(
                    $submission['user_id'],
                    'evaluation_submitted',
                    'Your Proposal Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }
        } elseif ($report_type === 'progress') {
            $stmt = $this->db->prepare("SELECT user_id, submission_id FROM progress_reports WHERE id = ?");
            $stmt->execute([$submission_id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row) {
                $this->notificationModel->create(
                    $row['user_id'],
                    'evaluation_submitted',
                    'Your Progress Report Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }
        } elseif ($report_type === 'terminal') {
            $stmt = $this->db->prepare("SELECT user_id, submission_id FROM terminal_reports WHERE id = ?");
            $stmt->execute([$submission_id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row) {
                $this->notificationModel->create(
                    $row['user_id'],
                    'evaluation_submitted',
                    'Your Terminal Report Has Been Evaluated',
                    'Evaluator ' . ($_SESSION['user_name'] ?? 'Unknown') . ' voted: ' . ucfirst($vote),
                    '/extensionist/submissions'
                );
            }
        }

        // Notify admins
        $adminIds = $this->notificationModel->getAdmins();
        $this->notificationModel->createBulk(
            $adminIds,
            'evaluation_submitted',
            'Evaluation Submitted',
            ($_SESSION['user_name'] ?? 'Unknown') . ' evaluated a ' . $report_type . ' report (vote: ' . ucfirst($vote) . ').',
            '/admin/monitoring'
        );
        // Check if all assigned evaluators have voted for THIS report
        $assigned = $this->getAssignedEvaluatorsForReport($submission_id, $report_type);
        $assigned_ids = array_column($assigned, 'id');
        $total = count($assigned_ids);

        $voted = 0;
        foreach ($assigned_ids as $aid) {
            $v = $this->voteModel->getBySubmissionAndEvaluator($submission_id, $aid, $report_type);
            if ($v) $voted++;
        }

        // If all evaluators have voted, compute majority and update status
        if ($total > 0 && $voted >= $total) {
            $majority = $this->voteModel->getMajorityVote($submission_id, $report_type);

            $statusMap = [
                'approve' => 'approved',
                'revision' => 'revision',
                'decline' => 'rejected'
            ];
            $newStatus = $statusMap[$majority] ?? null;

            if ($newStatus) {
                if ($report_type === 'proposal') {
                    // Update submissions table
                    $stmt = $this->db->prepare("UPDATE submissions SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);
                } elseif ($report_type === 'progress') {
                    $stmt = $this->db->prepare("UPDATE progress_reports SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);
                } elseif ($report_type === 'terminal') {
                    $stmt = $this->db->prepare("UPDATE terminal_reports SET status = ? WHERE id = ?");
                    $stmt->execute([$newStatus, $submission_id]);
                }
            }
        }

        $_SESSION['success'] = 'Evaluation submitted successfully.';
        header('Location: /evaluator/dashboard');
        exit;
    }

    // Helper: get evaluators assigned to a report
    private function getAssignedEvaluatorsForReport($submission_id, $report_type)
    {
        if ($report_type === 'proposal') {
            // Evaluators assigned via submission_evaluators
            $stmt = $this->db->prepare("
            SELECT u.id FROM submission_evaluators se
            JOIN users u ON se.evaluator_id = u.id
            WHERE se.submission_id = ?
        ");
            $stmt->execute([$submission_id]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        // For progress/terminal, use the parent submission's evaluators
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
