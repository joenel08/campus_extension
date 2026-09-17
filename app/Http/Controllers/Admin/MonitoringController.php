<?php

namespace Admin;

require_once __DIR__ . '/../../../models/Submission.php';
require_once __DIR__ . '/../../../models/College.php';
require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/EvaluatorVote.php';
require_once __DIR__ . '/../../../models/EvaluatorRating.php';
require_once __DIR__ . '/../../../models/ProgressReport.php';
require_once __DIR__ . '/../../../models/TerminalReport.php';



class MonitoringController extends \Controller
{
    private $submissionModel;
    private $collegeModel;
    private $proposalModel;
    private $voteModel;
    private $ratingModel;
    private $progressReportModel;
    private $terminalReportModel;

    private $db; // store PDO connection

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $pdo = new \PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->db = $pdo; // store for later use
        $this->submissionModel = new \Submission($pdo);
        $this->collegeModel = new \College($pdo);
        $this->proposalModel = new \Proposal($pdo);
        $this->voteModel = new \EvaluatorVote($pdo);
        $this->progressReportModel = new \ProgressReport($pdo);
        $this->terminalReportModel = new \TerminalReport($pdo);
        $this->ratingModel = new \EvaluatorRating($pdo);
    }

    public function index()
    {
        $filters = [
            'status' => $_GET['status'] ?? null,
            'college_id' => $_GET['college_id'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null,
        ];
        $filters = array_filter($filters, function ($v) {
            return $v !== null && $v !== '';
        });

        // Fetch all proposal submissions with filters
        $submissions = $this->submissionModel->getAllWithFilters($filters);

        // Group by composite key: user_id + proposal_id (for filtering)
        $grouped = [];
        foreach ($submissions as $s) {
            $pid = $s['proposal_id'];
            $uid = $s['user_id'];
            if (!$pid) continue;
            $key = $uid . '_' . $pid;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'user_id'           => $uid,
                    'proposal_id'       => $pid,
                    'submission_id'     => $s['id'],
                    'proposal_title'    => $s['proposal_title'] ?? 'N/A',
                    'extensionist_name' => $s['extensionist_name'] ?? 'N/A',
                    'college_abbr'      => $s['college_abbr'] ?? 'N/A',
                    'proposal_submission' => $s,
                    'progress_reports'  => [],
                    'terminal_report'   => null,
                    'evaluators'        => $s['evaluators'] ?? 'None assigned',
                ];
            }

            // Fetch progress reports from new table
            $grouped[$key]['progress_reports'] = $this->progressReportModel->getBySubmission($s['id']);
            // Fetch terminal report from new table
            $grouped[$key]['terminal_report'] = $this->terminalReportModel->getBySubmission($s['id']);
        }

        $colleges = $this->collegeModel->getAll();

        $this->render('monitoring/index', [
            'grouped' => $grouped,
            'colleges' => $colleges,
            'filters' => $filters,
        ]);
    }

    public function approve()
    {
        $id = $_POST['id'] ?? 0;
        $remarks = trim($_POST['remarks'] ?? '');
        if ($id) {
            $this->submissionModel->updateAdminStatus($id, 'pending_evaluation', $remarks);
            $_SESSION['success'] = 'Proposal approved for evaluation.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/monitoring');
        exit;
    }
    public function revise()
    {
        $id = $_POST['id'] ?? 0;
        $remarks = trim($_POST['remarks'] ?? '');
        if ($id) {
            $this->submissionModel->updateAdminStatus($id, 'revision', $remarks);
            $_SESSION['success'] = 'Revision requested.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/monitoring');
        exit;
    }

    public function decline()
    {
        $id = $_POST['id'] ?? 0;
        $remarks = trim($_POST['remarks'] ?? '');
        if ($id) {
            $this->submissionModel->updateAdminStatus($id, 'rejected', $remarks);
            $_SESSION['success'] = 'Submission declined.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/monitoring');
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? 0;
        if (!$id) {
            $_SESSION['error'] = 'Invalid submission ID.';
            header('Location: /admin/monitoring');
            exit;
        }

        $submission = $this->submissionModel->find($id);
        if (!$submission) {
            $_SESSION['error'] = 'Submission not found.';
            header('Location: /admin/monitoring');
            exit;
        }

        $form_data = json_decode($submission['form_data'], true);

        // Fetch evaluator votes for this submission
        $votes = $this->voteModel->getVotesForSubmission($id);

        // Fetch evaluator ratings (only for proposal)
        $ratings = [];
        if ($submission['report_type'] === 'proposal') {
            $ratings = $this->ratingModel->getRatingsForSubmission($id);
        }

        // Group ratings by evaluator
        $groupedRatings = [];
        foreach ($ratings as $r) {
            $eid = $r['evaluator_id'];
            if (!isset($groupedRatings[$eid])) {
                $groupedRatings[$eid] = [
                    'evaluator_name' => $r['evaluator_name'],
                    'criteria' => []
                ];
            }
            $groupedRatings[$eid]['criteria'][] = [
                'criteria_text' => $r['criteria_text'],
                'rating' => $r['rating']
            ];
        }

        $this->render('monitoring/show', [
            'submission' => $submission,
            'form_data' => $form_data,
            'votes' => $votes,
            'groupedRatings' => $groupedRatings,
        ]);
    }
    public function assignModal()
    {
        $submission_id = $_GET['submission_id'] ?? 0;
        if (!$submission_id) {
            echo '<p style="color:red;">Invalid submission.</p>';
            return;
        }

        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE role = 'evaluator' ORDER BY name");
        $stmt->execute();
        $evaluators = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $assigned = $this->proposalModel->getEvaluators($submission_id);
        $assigned_ids = array_column($assigned, 'id');
?>
        <div>
            <h4 style="margin-bottom:10px;">Assign Evaluators to Proposal</h4>
            <form id="assignForm" method="POST" action="/admin/monitoring/assign-evaluators">
                <input type="hidden" name="submission_id" value="<?= $submission_id ?>">
                <div style="display:flex; flex-direction:column; gap:10px; margin:15px 0;">
                    <?php if (empty($evaluators)): ?>
                        <p style="color:#999;">No evaluators registered yet.</p>
                    <?php else: ?>
                        <?php foreach ($evaluators as $e): ?>
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="checkbox" name="evaluator_ids[]" value="<?= $e['id'] ?>" <?= in_array($e['id'], $assigned_ids) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($e['name']) ?> (<?= htmlspecialchars($e['email']) ?>)
                            </label>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div style="display:flex; gap:10px; margin-top:15px;">
                    <button type="submit" class="btn btn-primary">Save Assignments</button>
                    <button type="button" class="btn btn-secondary" onclick="closeAssignModal()">Cancel</button>
                </div>
            </form>
        </div>
<?php
    }
    public function assignEvaluatorStore()
    {
        $submission_id = $_POST['submission_id'] ?? 0;
        $evaluator_ids = $_POST['evaluator_ids'] ?? [];
        if ($submission_id && !empty($evaluator_ids)) {
            $stmt = $this->db->prepare("DELETE FROM submission_evaluators WHERE submission_id = ?");
            $stmt->execute([$submission_id]);
            foreach ($evaluator_ids as $eid) {
                $this->proposalModel->assignEvaluator($submission_id, $eid);
            }
            echo '<div class="alert alert-success">Evaluators assigned successfully.</div>';
        } else {
            echo '<div class="alert alert-error">No evaluators selected.</div>';
        }
        exit;
    }
}
