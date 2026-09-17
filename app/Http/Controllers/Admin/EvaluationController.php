<?php
namespace Admin;

require_once __DIR__ . '/../../../models/EvaluationGroup.php';
require_once __DIR__ . '/../../../models/EvaluationCriteria.php';

class EvaluationController extends \Controller
{
    private $groupModel;
    private $criteriaModel;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->groupModel = new \EvaluationGroup($pdo);
        $this->criteriaModel = new \EvaluationCriteria($pdo);
    }

    // List all groups and criteria
    public function index()
    {
        $groups = $this->groupModel->getAll();
        $groupedCriteria = $this->criteriaModel->getGrouped();
        $this->render('evaluation/index', ['groups' => $groups, 'groupedCriteria' => $groupedCriteria]);
    }

    // Show create group form
    public function createGroup()
    {
        $this->render('evaluation/create_group');
    }

    // Store new group
    public function storeGroup()
    {
        $name = trim($_POST['name'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        if ($name) {
            $this->groupModel->create($name, $display_order);
            $_SESSION['success'] = 'Group added.';
        } else {
            $_SESSION['error'] = 'Group name is required.';
        }
        header('Location: /admin/evaluation');
        exit;
    }

    // Show edit group form
    public function editGroup()
    {
        $id = $_GET['id'] ?? 0;
        $group = $this->groupModel->find($id);
        if (!$group) {
            $_SESSION['error'] = 'Group not found.';
            header('Location: /admin/evaluation');
            exit;
        }
        $this->render('evaluation/edit_group', ['group' => $group]);
    }

    // Update group
    public function updateGroup()
    {
        $id = $_POST['id'] ?? 0;
        $name = trim($_POST['name'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        if ($id && $name) {
            $this->groupModel->update($id, $name, $display_order);
            $_SESSION['success'] = 'Group updated.';
        } else {
            $_SESSION['error'] = 'Invalid data.';
        }
        header('Location: /admin/evaluation');
        exit;
    }

    // Delete group (cascades to criteria)
    public function deleteGroup()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->groupModel->delete($id);
            $_SESSION['success'] = 'Group deleted.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/evaluation');
        exit;
    }

    // Show create criteria form (for a specific group)
    public function createCriteria()
    {
        $group_id = $_GET['group_id'] ?? 0;
        $groups = $this->groupModel->getAll();
        $this->render('evaluation/create_criteria', ['groups' => $groups, 'selected_group' => $group_id]);
    }

    // Store new criteria
    public function storeCriteria()
    {
        $group_id = $_POST['group_id'] ?? 0;
        $criteria_text = trim($_POST['criteria_text'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        if ($group_id && $criteria_text) {
            $this->criteriaModel->create($group_id, $criteria_text, $display_order);
            $_SESSION['success'] = 'Criteria added.';
        } else {
            $_SESSION['error'] = 'All fields are required.';
        }
        header('Location: /admin/evaluation');
        exit;
    }

    // Show edit criteria form
    public function editCriteria()
    {
        $id = $_GET['id'] ?? 0;
        $criteria = $this->criteriaModel->find($id);
        if (!$criteria) {
            $_SESSION['error'] = 'Criteria not found.';
            header('Location: /admin/evaluation');
            exit;
        }
        $groups = $this->groupModel->getAll();
        $this->render('evaluation/edit_criteria', ['criteria' => $criteria, 'groups' => $groups]);
    }

    // Update criteria
    public function updateCriteria()
    {
        $id = $_POST['id'] ?? 0;
        $group_id = $_POST['group_id'] ?? 0;
        $criteria_text = trim($_POST['criteria_text'] ?? '');
        $display_order = (int) ($_POST['display_order'] ?? 0);
        if ($id && $group_id && $criteria_text) {
            $this->criteriaModel->update($id, $group_id, $criteria_text, $display_order);
            $_SESSION['success'] = 'Criteria updated.';
        } else {
            $_SESSION['error'] = 'Invalid data.';
        }
        header('Location: /admin/evaluation');
        exit;
    }

    // Delete criteria
    public function deleteCriteria()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->criteriaModel->delete($id);
            $_SESSION['success'] = 'Criteria deleted.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/evaluation');
        exit;
    }
}