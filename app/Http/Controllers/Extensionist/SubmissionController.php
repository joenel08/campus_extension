<?php

namespace Extensionist;

require_once __DIR__ . '/../../../models/Submission.php';
require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/College.php';
require_once __DIR__ . '/../../../models/ProgressReport.php';
require_once __DIR__ . '/../../../models/TerminalReport.php';


class SubmissionController extends \ExtensionistBaseController
{
    private $submissionModel;
    private $collegeModel;
    private $proposalModel;
    private $progressReportModel;
    private $terminalReportModel;
    private $uploadDir = 'uploads/submissions/';
    private $db;



    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;  // <-- ADD THIS
        $this->submissionModel = new \Submission($pdo);
        $this->proposalModel = new \Proposal($pdo);
        $this->collegeModel = new \College($pdo);
        $this->progressReportModel = new \ProgressReport($pdo);
        $this->terminalReportModel = new \TerminalReport($pdo);
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }
    }
    private function handleFileUpload($file, $oldPath = null)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return $oldPath; // no new file, keep old
        }
        // Delete old file if exists
        if ($oldPath && file_exists($oldPath)) {
            unlink($oldPath);
        }
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $destination = $this->uploadDir . $filename;
        move_uploaded_file($file['tmp_name'], $destination);
        return $destination;
    }
    public function index()
    {
        $user_id = $_SESSION['user_id'];
        $college_id = $_SESSION['college_id'] ?? 0;

        // Get all proposal submissions for this user
        $submissions = $this->submissionModel->getAllByUser($user_id);

        // Group by proposal_id (or just by submission_id, since each proposal submission is unique)
        $grouped = [];
        foreach ($submissions as $s) {
            // We only want proposal submissions as the primary rows
            if ($s['report_type'] !== 'proposal') continue;

            $sid = $s['id'];
            $grouped[$sid] = [
                'proposal_submission' => $s,
                'proposal_title'      => $s['proposal_title'] ?? 'N/A',
                'progress_reports'    => $this->progressReportModel->getBySubmission($sid),
                'terminal_report'     => $this->terminalReportModel->getBySubmission($sid),
                'all_progress_approved' => true,
            ];

            // Check if all progress reports are approved
            foreach ($grouped[$sid]['progress_reports'] as $pr) {
                if ($pr['status'] !== 'approved') {
                    $grouped[$sid]['all_progress_approved'] = false;
                    break;
                }
            }
        }

        // For the "New Submission" modal (open proposals)
        $proposals = [];
        if ($college_id) {
            $all_proposals = $this->proposalModel->getOpenByCollege($college_id);
            $submitted_proposal_ids = array_column($submissions, 'proposal_id');
            $proposals = array_filter($all_proposals, function ($p) use ($submitted_proposal_ids) {
                return !in_array($p['id'], $submitted_proposal_ids);
            });
        }

        $this->render('submissions/index', [
            'grouped' => $grouped,
            'proposals' => $proposals,
        ]);
    }

    public function create()
    {
        $user_id = $_SESSION['user_id'];
        $submission_id = $_GET['submission_id'] ?? 0;   // <-- CHANGED
        $report_type = $_GET['type'] ?? 'proposal';

        if (!$submission_id) {
            header('Location: /extensionist/submissions');
            exit;
        }

        // Validation
        if ($report_type === 'proposal') {
            // For proposals, submission_id is the proposal catalog ID passed from the modal
            // (modal sends proposal_id from proposals table)
            $existing = $this->submissionModel->findByUserAndProposal($user_id, $submission_id);
            if ($existing) {
                $_SESSION['info'] = 'You already have a proposal submission for this call.';
                header('Location: /extensionist/submissions/edit?id=' . $existing['id']);
                exit;
            }
        } else {
            // For progress/terminal, verify the parent submission is approved
            $parent = $this->submissionModel->find($submission_id, $user_id);
            if (!$parent || $parent['status'] !== 'approved') {
                $_SESSION['error'] = 'You can only submit progress/terminal reports for approved proposals.';
                header('Location: /extensionist/submissions');
                exit;
            }

            if ($report_type === 'terminal' && $this->terminalReportModel->hasTerminalReport($submission_id)) {
                $_SESSION['error'] = 'A terminal report already exists for this proposal.';
                header('Location: /extensionist/submissions');
                exit;
            }
        }

        $college_abbr = $_SESSION['college_abbr'] ?? '';
        $colleges = $this->collegeModel->getAll();
        $user_name = $_SESSION['user_name'] ?? '';

        $this->render('submissions/create', [
            'colleges' => $colleges,
            'selected_submission_id' => $submission_id,  // <-- renamed
            'user_college' => $college_abbr,
            'user_name' => $user_name,
            'report_type' => $report_type,
            'form_data' => []
        ]);
    }
    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        $submission = $this->submissionModel->find($id, $user_id);
        if (!$submission) {
            $_SESSION['error'] = 'Submission not found.';
            header('Location: /extensionist/submissions');
            exit;
        }
        $form_data = json_decode($submission['form_data'], true);
        $college_abbr = $_SESSION['college_abbr'] ?? '';
        $colleges = $this->collegeModel->getAll();
        $user_name = $_SESSION['user_name'] ?? '';
        $submission = $this->submissionModel->find($id, $user_id);
        $form_data = json_decode($submission['form_data'], true);
        $this->render('submissions/edit', [
            'submission' => $submission,
            'form_data' => $form_data,
            'colleges' => $colleges,
            'user_college' => $college_abbr,
            'user_name' => $user_name,
        ]);
    }
    // Store new submission
    public function store()
    {
        $user_id = $_SESSION['user_id'];
        $submission_id = $_POST['submission_id'] ?? null;
        $report_type = $_POST['report_type'] ?? 'proposal';
        $status = $_POST['status'] ?? 'draft';

        // Handle file upload
        $attachment = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $attachment = $this->handleFileUpload($_FILES['attachment']);
        }

        if ($report_type === 'proposal') {
            // The submission_id here is actually the proposal catalog ID
            $form_data = $this->buildFormData($_POST, 'proposal');
            if ($attachment) $form_data['attachment'] = $attachment;

            $existing = $this->submissionModel->findByUserAndProposal($user_id, $submission_id);
            if ($existing) {
                $_SESSION['error'] = 'You already have a proposal submission for this call.';
                header('Location: /extensionist/submissions');
                exit;
            }

            $this->submissionModel->create($user_id, $submission_id, 'proposal', $form_data, $status);
        } elseif ($report_type === 'progress') {
            if (!$submission_id) {
                $_SESSION['error'] = 'Missing parent submission.';
                header('Location: /extensionist/submissions');
                exit;
            }
            $this->progressReportModel->create($submission_id, $user_id, [
                'report_date'     => $_POST['report_date'] ?? '',
                'accomplishments' => $_POST['accomplishments'] ?? '',
                'issues'          => $_POST['issues'] ?? '',
                'next_plan'       => $_POST['next_plan'] ?? '',
                'attachment'      => $attachment,
                'status'          => $status,
            ]);
        } elseif ($report_type === 'terminal') {
            if (!$submission_id) {
                $_SESSION['error'] = 'Missing parent submission.';
                header('Location: /extensionist/submissions');
                exit;
            }
            if ($this->terminalReportModel->hasTerminalReport($submission_id)) {
                $_SESSION['error'] = 'A terminal report already exists for this proposal.';
                header('Location: /extensionist/submissions');
                exit;
            }
            $this->terminalReportModel->create($submission_id, $user_id, [
                'completion_date'  => $_POST['completion_date'] ?? '',
                'overall_status'   => $_POST['overall_status'] ?? '',
                'final_summary'    => $_POST['final_summary'] ?? '',
                'lessons_learned'  => $_POST['lessons_learned'] ?? '',
                'recommendations'  => $_POST['recommendations'] ?? '',
                'attachment'       => $attachment,
                'status'           => $status,
            ]);
        }

        $_SESSION['success'] = 'Submission saved successfully.';
        header('Location: /extensionist/submissions');
        exit;
    }


    // Update submission
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        $submission = $this->submissionModel->find($id, $user_id);
        if (!$submission) {
            $_SESSION['error'] = 'Submission not found.';
            header('Location: /extensionist/submissions');
            exit;
        }

        $report_type = $submission['report_type'];
        $form_data = $this->buildFormData($_POST, $report_type);

        // Preserve existing attachment if no new file is uploaded
        $oldData = json_decode($submission['form_data'], true);
        if (isset($oldData['attachment'])) {
            $form_data['attachment'] = $oldData['attachment'];
        }

        // Handle file upload (replace if new file uploaded)
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $form_data['attachment'] = $this->handleFileUpload($_FILES['attachment'], $oldData['attachment'] ?? null);
        }

        $status = $_POST['status'] ?? 'draft';

        if ($this->submissionModel->update($id, $form_data, $status)) {
            $_SESSION['success'] = 'Submission updated.';
            header('Location: /extensionist/submissions');
            exit;
        } else {
            $_SESSION['error'] = 'Update failed.';
            header('Location: /extensionist/submissions/edit?id=' . $id);
            exit;
        }
    }
    // Delete submission
    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        $submission = $this->submissionModel->find($id, $user_id);
        if ($submission) {
            $this->submissionModel->delete($id);
            $_SESSION['success'] = 'Submission deleted.';
        } else {
            $_SESSION['error'] = 'Submission not found.';
        }
        header('Location: /extensionist/submissions');
        exit;
    }

    // Show single submission
    public function show()
    {
        $id = $_GET['id'] ?? 0;
        $user_id = $_SESSION['user_id'];
        $submission = $this->submissionModel->find($id, $user_id);
        if (!$submission) {
            $_SESSION['error'] = 'Submission not found.';
            header('Location: /extensionist/submissions');
            exit;
        }
        $form_data = json_decode($submission['form_data'], true);
        $this->render('submissions/show', ['submission' => $submission, 'form_data' => $form_data]);
    }

    private function buildFormData($post, $report_type)
    {
        $data = [];

        if ($report_type === 'proposal') {
            // Collect components from dynamic table
            $components = [];
            if (isset($post['component_title']) && is_array($post['component_title'])) {
                foreach ($post['component_title'] as $index => $title) {
                    $leader = $post['component_leader'][$index] ?? '';
                    if (!empty(trim($title))) {
                        $components[] = [
                            'title' => trim($title),
                            'leader' => trim($leader),
                        ];
                    }
                }
            }

            $data['basic_info'] = [
                'project_title'       => $post['project_title'] ?? '',
                'project_leader'      => $post['project_leader'] ?? '',
                'implementing_campus' => $post['implementing_campus'] ?? 'ISU',
                'lead_unit'           => $post['lead_unit'] ?? '',
                'cooperating_unit'    => $post['cooperating_unit'] ?? '',
                'project_site'        => $post['project_site'] ?? '',
                'cooperating_agencies' => $post['cooperating_agencies'] ?? '',
                'date_started'        => $post['date_started'] ?? '',
                'date_completed'      => $post['date_completed'] ?? '',
                'status'              => $post['project_status'] ?? 'New',
                'beneficiaries'       => $post['beneficiaries'] ?? '',
                'funding_agency'      => $post['funding_agency'] ?? '',
                'budget'              => $post['budget'] ?? 0,
            ];

            $data['budget_breakdown'] = [
                'year1_ps'   => $post['year1_ps'] ?? 0,
                'year1_mooe' => $post['year1_mooe'] ?? 0,
                'year1_co'   => $post['year1_co'] ?? 0,
                'year2_ps'   => $post['year2_ps'] ?? 0,
                'year2_mooe' => $post['year2_mooe'] ?? 0,
                'year2_co'   => $post['year2_co'] ?? 0,
                'year3_ps'   => $post['year3_ps'] ?? 0,
                'year3_mooe' => $post['year3_mooe'] ?? 0,
                'year3_co'   => $post['year3_co'] ?? 0,
            ];

            $data['components'] = $components;
        } elseif ($report_type === 'progress') {
            $data['progress_info'] = [
                'report_date'     => $post['report_date'] ?? '',
                'accomplishments' => $post['accomplishments'] ?? '',
                'issues'          => $post['issues'] ?? '',
                'next_plan'       => $post['next_plan'] ?? '',
            ];
        } elseif ($report_type === 'terminal') {
            $data['terminal_info'] = [
                'completion_date'  => $post['completion_date'] ?? '',
                'overall_status'   => $post['overall_status'] ?? '',
                'final_summary'    => $post['final_summary'] ?? '',
                'lessons_learned'  => $post['lessons_learned'] ?? '',
                'recommendations'  => $post['recommendations'] ?? '',
            ];
        }

        return $data;
    }

    public function getReportData()
    {
        $id = $_GET['id'] ?? 0;
        $type = $_GET['type'] ?? 'proposal';
        $user_id = $_SESSION['user_id'];

        if ($type === 'progress') {
            $stmt = $this->db->prepare("SELECT * FROM progress_reports WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
            $report = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$report) {
                echo '<p style="color:red;">Progress report not found.</p>';
                return;
            }

            $status = $report['status'];
?>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div><strong>Report Type:</strong> Progress</div>
                <div><strong>Status:</strong>
                    <span class="badge <?= $status === 'approved' ? 'badge-approved' : ($status === 'submitted' ? 'badge-approved' : 'badge-pending') ?>">
                        <?= ucfirst($status) ?>
                    </span>
                </div>
                <div><strong>Submitted:</strong> <?= date('M d, Y H:i', strtotime($report['created_at'])) ?></div>
                <div><strong>Report Date:</strong> <?= htmlspecialchars($report['report_date'] ?? 'N/A') ?></div>
                <div style="grid-column:1 / span 2;"><strong>Accomplishments:</strong> <?= nl2br(htmlspecialchars($report['accomplishments'] ?? 'N/A')) ?></div>
                <div style="grid-column:1 / span 2;"><strong>Issues:</strong> <?= nl2br(htmlspecialchars($report['issues'] ?? 'N/A')) ?></div>
                <div style="grid-column:1 / span 2;"><strong>Next Plan:</strong> <?= nl2br(htmlspecialchars($report['next_plan'] ?? 'N/A')) ?></div>

                <?php if (!empty($report['attachment'])): ?>
                    <div style="grid-column:1 / span 2; margin-top:10px;">
                        <strong>Attachment:</strong>
                        <a href="/<?= htmlspecialchars($report['attachment']) ?>" target="_blank" class="btn btn-sm btn-primary" style="margin-left:10px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($report['evaluator_comments'])): ?>
                    <div style="grid-column:1 / span 2; background:#fff3cd; padding:10px; border-radius:5px; margin-top:10px;">
                        <strong>Evaluator Comments:</strong> <?= nl2br(htmlspecialchars($report['evaluator_comments'])) ?>
                    </div>
                <?php endif; ?>

                <?php if ($status === 'rejected' || $status === 'revision'): ?>
                    <div style="grid-column:1 / span 2; background:#f8d7da; padding:10px; border-radius:5px; margin-top:10px;">
                        <strong>This report needs revision.</strong>
                        <a href="/extensionist/submissions/edit?id=<?= $id ?>&type=progress" class="btn btn-sm btn-warning" style="margin-top:5px;">
                            Edit &amp; Resubmit
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php
            return;
        }

        if ($type === 'terminal') {
            $stmt = $this->db->prepare("SELECT * FROM terminal_reports WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
            $report = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$report) {
                echo '<p style="color:red;">Terminal report not found.</p>';
                return;
            }

            $status = $report['status'];
        ?>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                <div><strong>Report Type:</strong> Terminal</div>
                <div><strong>Status:</strong>
                    <span class="badge <?= $status === 'approved' ? 'badge-approved' : ($status === 'submitted' ? 'badge-approved' : 'badge-pending') ?>">
                        <?= ucfirst($status) ?>
                    </span>
                </div>
                <div><strong>Submitted:</strong> <?= date('M d, Y H:i', strtotime($report['created_at'])) ?></div>
                <div><strong>Completion Date:</strong> <?= htmlspecialchars($report['completion_date'] ?? 'N/A') ?></div>
                <div><strong>Overall Status:</strong> <?= htmlspecialchars($report['overall_status'] ?? 'N/A') ?></div>
                <div style="grid-column:1 / span 2;"><strong>Final Summary:</strong> <?= nl2br(htmlspecialchars($report['final_summary'] ?? 'N/A')) ?></div>
                <div style="grid-column:1 / span 2;"><strong>Lessons Learned:</strong> <?= nl2br(htmlspecialchars($report['lessons_learned'] ?? 'N/A')) ?></div>
                <div style="grid-column:1 / span 2;"><strong>Recommendations:</strong> <?= nl2br(htmlspecialchars($report['recommendations'] ?? 'N/A')) ?></div>

                <?php if (!empty($report['attachment'])): ?>
                    <div style="grid-column:1 / span 2; margin-top:10px;">
                        <strong>Attachment:</strong>
                        <a href="/<?= htmlspecialchars($report['attachment']) ?>" target="_blank" class="btn btn-sm btn-primary" style="margin-left:10px;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (!empty($report['evaluator_comments'])): ?>
                    <div style="grid-column:1 / span 2; background:#fff3cd; padding:10px; border-radius:5px; margin-top:10px;">
                        <strong>Evaluator Comments:</strong> <?= nl2br(htmlspecialchars($report['evaluator_comments'])) ?>
                    </div>
                <?php endif; ?>

                <?php if ($status === 'rejected' || $status === 'revision'): ?>
                    <div style="grid-column:1 / span 2; background:#f8d7da; padding:10px; border-radius:5px; margin-top:10px;">
                        <strong>This report needs revision.</strong>
                        <a href="/extensionist/submissions/edit?id=<?= $id ?>&type=terminal" class="btn btn-sm btn-warning" style="margin-top:5px;">
                            Edit &amp; Resubmit
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        <?php
            return;
        }

        // Default: proposal (from submissions table)
        $submission = $this->submissionModel->find($id, $user_id);
        if (!$submission) {
            echo '<p style="color:red;">Report not found.</p>';
            return;
        }
        $form_data = json_decode($submission['form_data'], true);
        $comments = $submission['evaluator_comments'] ?? '';
        $status = $submission['status'];
        ?>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
            <div><strong>Report Type:</strong> <?= ucfirst($submission['report_type']) ?></div>
            <div><strong>Status:</strong>
                <span class="badge <?= $status === 'approved' ? 'badge-approved' : ($status === 'submitted' ? 'badge-approved' : 'badge-pending') ?>">
                    <?= ucfirst($status) ?>
                </span>
            </div>
            <div><strong>Submitted:</strong> <?= date('M d, Y H:i', strtotime($submission['created_at'])) ?></div>
            <div style="grid-column:1 / span 2;">
                <strong>Project Title:</strong> <?= htmlspecialchars($form_data['basic_info']['project_title'] ?? 'N/A') ?>
            </div>

            <?php if (!empty($form_data['attachment'])): ?>
                <div style="grid-column:1 / span 2; margin-top:10px;">
                    <strong>Attachment:</strong>
                    <a href="/<?= htmlspecialchars($form_data['attachment']) ?>" target="_blank" class="btn btn-sm btn-primary" style="margin-left:10px;">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($comments): ?>
                <div style="grid-column:1 / span 2; background:#fff3cd; padding:10px; border-radius:5px;">
                    <strong>Evaluator Comments:</strong> <?= nl2br(htmlspecialchars($comments)) ?>
                </div>
            <?php endif; ?>

            <?php if ($status === 'rejected' || $status === 'revision'): ?>
                <div style="grid-column:1 / span 2; background:#f8d7da; padding:10px; border-radius:5px; margin-top:10px;">
                    <strong>This report needs revision.</strong>
                    <a href="/extensionist/submissions/edit?id=<?= $id ?>" class="btn btn-sm btn-warning" style="display:inline-block; margin-top:5px;">
                        Edit &amp; Resubmit
                    </a>
                </div>
            <?php endif; ?>
        </div>
<?php
    }
}
