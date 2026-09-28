<?php
namespace Admin;

require_once __DIR__ . '/../../../models/AcademicYear.php';

class AcademicYearController extends \Controller
{
    private $academicYearModel;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;
        $this->academicYearModel = new \AcademicYear($pdo);
    }

    public function index()
    {
        $years = $this->academicYearModel->getAll();
        $this->render('academic_years/index', ['years' => $years]);
    }

    public function store()
    {
        $year_label = trim($_POST['year_label'] ?? '');
        $start_date = $_POST['start_date'] ?? '';
        $end_date   = $_POST['end_date'] ?? '';

        if ($year_label && $start_date && $end_date) {
            $this->academicYearModel->create($year_label, $start_date, $end_date);
            $_SESSION['success'] = 'Academic year added.';
        } else {
            $_SESSION['error'] = 'All fields are required.';
        }
        header('Location: /admin/academic-years');
        exit;
    }

    public function update()
    {
        $id = $_POST['id'] ?? 0;
        $year_label = trim($_POST['year_label'] ?? '');
        $start_date = $_POST['start_date'] ?? '';
        $end_date   = $_POST['end_date'] ?? '';

        if ($id && $year_label && $start_date && $end_date) {
            $this->academicYearModel->update($id, $year_label, $start_date, $end_date);
            $_SESSION['success'] = 'Academic year updated.';
        } else {
            $_SESSION['error'] = 'All fields are required.';
        }
        header('Location: /admin/academic-years');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->academicYearModel->delete($id);
            $_SESSION['success'] = 'Academic year deleted.';
        }
        header('Location: /admin/academic-years');
        exit;
    }

    public function setCurrent()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->academicYearModel->setCurrent($id);
            $year = $this->academicYearModel->find($id);
            $_SESSION['academic_year_id'] = $year['id'];
            $_SESSION['academic_year_label'] = $year['year_label'];
            $_SESSION['success'] = 'Current academic year set to: ' . $year['year_label'];
        }
        header('Location: /admin/academic-years');
        exit;
    }
}