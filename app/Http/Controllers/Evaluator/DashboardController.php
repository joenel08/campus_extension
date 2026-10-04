<?php

namespace Evaluator;

require_once __DIR__ . '/../../../models/Evaluation.php';
require_once __DIR__ . '/../../../models/ProgressReport.php';
require_once __DIR__ . '/../../../models/TerminalReport.php';

class DashboardController extends \EvaluatorBaseController
{
    private $evaluationModel;
    private $progressReportModel;
    private $terminalReportModel;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;
        $this->evaluationModel = new \Evaluation($pdo);
        $this->progressReportModel = new \ProgressReport($pdo);
        $this->terminalReportModel = new \TerminalReport($pdo);
    }

    // ===== DASHBOARD (Profile + Stats) =====
    public function index()
    {
        $evaluator_id = $_SESSION['user_id'];

        // Fetch current user
        $stmt = $this->db->prepare("SELECT id, name, email, profile_picture FROM users WHERE id = ?");
        $stmt->execute([$evaluator_id]);
        $currentUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        // === STATS ===
        $totalAssigned = $this->evaluationModel->countAssigned($evaluator_id);
        $ongoing       = $this->evaluationModel->countOngoing($evaluator_id);
        $completed     = $this->evaluationModel->countCompleted($evaluator_id);

        $this->render('dashboard', [
            'currentUser'   => $currentUser,
            'totalAssigned' => $totalAssigned,
            'ongoing'       => $ongoing,
            'completed'     => $completed,
        ]);
    }

    // ===== EVALUATIONS PAGE (Assigned table) =====
    public function evaluations()
    {
        $filter = $_GET['filter'] ?? null;
        $evaluator_id = $_SESSION['user_id'];

        // Fetch current user
        $stmt = $this->db->prepare("SELECT id, name, email, profile_picture FROM users WHERE id = ?");
        $stmt->execute([$evaluator_id]);
        $currentUser = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Get assigned proposals
        $assigned = $this->evaluationModel->getAssignedProposals($evaluator_id);

        $grouped = [];
        foreach ($assigned as $row) {
            $sid = $row['submission_id'];
            $vote = $this->evaluationModel->getBySubmissionAndEvaluator($sid, $evaluator_id, 'proposal');

            $progress = $this->progressReportModel->getBySubmission($sid);
            foreach ($progress as &$pr) {
                $v = $this->evaluationModel->getBySubmissionAndEvaluator($pr['id'], $evaluator_id, 'progress');
                $pr['evaluated'] = $v ? true : false;
                $pr['vote']      = $v ? $v['vote'] : null;
            }
            unset($pr);

            $terminal = $this->terminalReportModel->getBySubmission($sid);
            if ($terminal) {
                $v = $this->evaluationModel->getBySubmissionAndEvaluator($terminal['id'], $evaluator_id, 'terminal');
                $terminal['evaluated'] = $v ? true : false;
                $terminal['vote']      = $v ? $v['vote'] : null;
            }

            $grouped[] = [
                'submission_id'       => $sid,
                'proposal_id'         => $row['proposal_id'],
                'proposal_title'      => $row['proposal_title'],
                'extensionist_name'   => $row['extensionist_name'],
                'submission_status'   => $row['submission_status'],
                'proposal_evaluated'  => $vote ? true : false,
                'proposal_vote'       => $vote ? $vote['vote'] : null,
                'progress_reports'    => $progress,
                'terminal_report'     => $terminal,
            ];
        }

        // === APPLY FILTER AFTER $grouped IS FULLY BUILT ===
        if ($filter === 'ongoing') {
            $grouped = array_values(array_filter($grouped, function ($g) {
                $hasProgress = !empty($g['progress_reports']);
                $hasTerminal = !empty($g['terminal_report']);
                return $hasProgress && !$hasTerminal;
            }));
        } elseif ($filter === 'completed') {
            $grouped = array_values(array_filter($grouped, function ($g) {
                return !empty($g['terminal_report']);
            }));
        }

        $this->render('evaluations', [
            'grouped'     => $grouped,
            'currentUser' => $currentUser,
            'filter'      => $filter,
        ]);
    }

    public function updateProfile()
    {
        $user_id = $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $profile_picture = null;

        if (!$name || !$email) {
            $_SESSION['profile_error'] = 'Name and email are required.';
            header('Location: /evaluator/dashboard');
            exit;
        }

        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/profiles/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $destination = $uploadDir . $filename;
            move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination);
            $profile_picture = $destination;
        }

        $sql = "UPDATE users SET name = ?, email = ?";
        $params = [$name, $email];

        if ($password) {
            $sql .= ", password = ?";
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }
        if ($profile_picture) {
            $sql .= ", profile_picture = ?";
            $params[] = $profile_picture;
        }
        $sql .= " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $this->db->prepare($sql);
        if ($stmt->execute($params)) {
            $_SESSION['user_name']  = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['profile_error'] = 'Failed to update profile.';
        }
        header('Location: /evaluator/dashboard');
        exit;
    }
}