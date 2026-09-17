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

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->evaluationModel = new \Evaluation($pdo);
        $this->progressReportModel = new \ProgressReport($pdo);
        $this->terminalReportModel = new \TerminalReport($pdo);
    }

    public function index()
    {
        $evaluator_id = $_SESSION['user_id'];

        // Get all assigned proposal submissions
        $assigned = $this->evaluationModel->getAssignedProposals($evaluator_id);

        $grouped = [];
        foreach ($assigned as $row) {
            $sid = $row['submission_id'];

            // Fetch proposal vote for this evaluator
            $vote = $this->evaluationModel->getBySubmissionAndEvaluator($sid, $evaluator_id, 'proposal');

            // Fetch progress reports
            $progress = $this->progressReportModel->getBySubmission($sid);
            foreach ($progress as &$pr) {
                $v = $this->evaluationModel->getBySubmissionAndEvaluator($pr['id'], $evaluator_id, 'progress');
                $pr['evaluated'] = $v ? true : false;
                $pr['vote']      = $v ? $v['vote'] : null;
            }
            unset($pr);

            // Fetch terminal report
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

        $this->render('dashboard', ['grouped' => $grouped]);
    }
}