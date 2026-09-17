<?php
namespace Extensionist;

require_once __DIR__ . '/../../../models/Proposal.php';
require_once __DIR__ . '/../../../models/Submission.php';

class DashboardController extends \ExtensionistBaseController
{
    private $proposalModel;
    private $submissionModel;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->proposalModel = new \Proposal($pdo);
        $this->submissionModel = new \Submission($pdo);
    }

    public function index()
    {
        $user_id = $_SESSION['user_id'];
        $college_id = $_SESSION['college_id'] ?? null; // you need to store college_id in session during login

        // Fetch open proposals for user's college
        $proposals = [];
        if ($college_id) {
            $proposals = $this->proposalModel->getOpenByCollege($college_id);
        }

        // Stats
        $submissions = $this->submissionModel->getAllByUser($user_id);
        $total = count($submissions);
        $pending = 0;
        $completed = 0;
        foreach ($submissions as $s) {
            if ($s['status'] === 'submitted') $pending++;
            if ($s['status'] === 'approved') $completed++;
        }

        $this->render('dashboard', [
            'proposals' => $proposals,
            'total' => $total,
            'pending' => $pending,
            'completed' => $completed
        ]);
    }
}