<?php

namespace Admin;

require_once __DIR__ . '/../../../models/Submission.php';

class DashboardController extends \Controller
{
    private $submissionModel;
    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->submissionModel = new \Submission($pdo);
    }

    public function index()
    {

        // Define status values (adjust to your database schema)
        $total   = $this->submissionModel->countAll();
        $ongoing = $this->submissionModel->countByStatus('ongoing');      // or 'in_progress'
        $completed = $this->submissionModel->countByStatus('completed'); // or 'submitted'

        $collegeStats = $this->submissionModel->getCollegeStats();
        $chartLabels = array_column($collegeStats, 'abbreviation');
        $chartData = array_column($collegeStats, 'total');

        // Pass data to the view
        $this->render('dashboard', [
            'total'     => $total,
            'ongoing'   => $ongoing,
            'completed' => $completed,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData),
        ]);
    }
}
