<?php

namespace Admin;

require_once __DIR__ . '/../../../models/Submission.php';

class DashboardController extends \Controller
{
    private $submissionModel;
    private $db;
    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;
        $this->submissionModel = new \Submission($pdo);
    }
   public function index()
{
    $academic_year_id = $_SESSION['academic_year_id'] ?? null;

    // Fetch current user data
    $stmt = $this->db->prepare("SELECT id, name, email, profile_picture FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $currentUser = $stmt->fetch(\PDO::FETCH_ASSOC);

   $total     = $this->submissionModel->countTotalProposals($academic_year_id);
    $ongoing   = $this->submissionModel->countOngoing($academic_year_id);
    $completed = $this->submissionModel->countCompleted($academic_year_id);

    $collegeStats = $this->submissionModel->getCollegeStats($academic_year_id);
    $chartLabels = array_column($collegeStats, 'abbreviation');
    $chartData = array_column($collegeStats, 'total');

    $this->render('dashboard', [
        'total'       => $total,
        'ongoing'     => $ongoing,
        'completed'   => $completed,
        'chartLabels' => json_encode($chartLabels),
        'chartData'   => json_encode($chartData),
        'currentUser' => $currentUser, 
    ]);
}

    public function updateProfile()
{
    $user_id = $_SESSION['user_id'];
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $profile_picture = null;

    if (!$name || !$email) {
        $_SESSION['profile_error'] = 'Name and email are required.';
        header('Location: /admin/dashboard');
        exit;
    }

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profiles/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . uniqid() . '.' . $ext;
        $destination = $uploadDir . $filename;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $destination);
        $profile_picture = $destination;
    }

    // Build update query
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
        // Sync session
        $_SESSION['user_name']  = $name;
        $_SESSION['user_email'] = $email;

        $_SESSION['success'] = 'Profile updated successfully.';
    } else {
        $_SESSION['profile_error'] = 'Failed to update profile.';
    }

    header('Location: /admin/dashboard');
    exit;
}
}
