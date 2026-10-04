<?php

namespace Extensionist;

require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/Submission.php';
require_once __DIR__ . '/../../../models/Evaluation.php';


class DashboardController extends \ExtensionistBaseController
{
    private $proposalModel;
    private $submissionModel;
    private $evaluationModel;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->proposalModel = new \Proposal($pdo);
        $this->db = $pdo;
        $this->submissionModel = new \Submission($pdo);
        $this->evaluationModel = new \Evaluation($pdo);

    }



    //     public function index()
    // {
    //     $user_id = $_SESSION['user_id'];
    //     $college_id = $_SESSION['college_id'] ?? null;

    //     // Open proposals for user's college
    //     $proposals = [];
    //     if ($college_id) {
    //         $proposals = $this->proposalModel->getOpenByCollege($college_id);
    //     }

    //     // === USER-SPECIFIC STATS ===
    //     // Total = all proposal submissions by this user
    //     $total = $this->submissionModel->countProposalsByUser($user_id);

    //     // Pending = proposals in any non-final state (submitted, pending_evaluation, under_evaluation, revision)
    //     $pending = $this->submissionModel->countPendingByUser($user_id);

    //     // Completed = proposals with an APPROVED terminal report
    //     $completed = $this->submissionModel->countCompletedByUser($user_id);

    //     $this->render('dashboard', [
    //         'proposals' => $proposals,
    //         'total'     => $total,
    //         'pending'   => $pending,
    //         'completed' => $completed,
    //     ]);
    // }


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
    public function updateProfile()
    {
        $user_id = $_SESSION['user_id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $profile_picture = null;

        if (!$name || !$email) {
            $_SESSION['profile_error'] = 'Name and email are required.';
            header('Location: /extensionist/dashboard');
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
        header('Location: /extensionist/dashboard');
        exit;
    }
}
