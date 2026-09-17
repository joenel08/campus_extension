<?php

namespace Admin;

require_once __DIR__ . '/../../../models/User.php';
require_once __DIR__ . '/../../../models/College.php';

class AccountsController extends \Controller
{

    private $userModel;
    private $collegeModel;
    public function __construct()
    {
        parent::__construct(); // session & role check
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->userModel = new \User($pdo);
        $this->collegeModel = new \College($pdo);
    }
    public function index()
    {
        $users = $this->userModel->getAll();
        $colleges = $this->collegeModel->getAll(); // for the "add evaluator" form dropdown
        $this->render('accounts', ['users' => $users, 'colleges' => $colleges]);
    }

    public function store()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'extensionist';
        $college_id = ($role !== 'admin') ? ($_POST['college_id'] ?? null) : null;
        // Admin-created accounts are automatically approved
        $status = 'approved';

        if ($name && $email && $password) {
            if ($this->userModel->create($name, $email, $password, $role, $college_id, $status)) {
                $_SESSION['success'] = 'Account created successfully.';
            } else {
                $_SESSION['error'] = 'Failed to create account. Email may already exist.';
            }
        } else {
            $_SESSION['error'] = 'All fields are required.';
        }
        header('Location: /admin/accounts');
        exit;
    }
    public function update()
    {
        $id = $_POST['id'] ?? 0;
        // Prevent admin from changing their own role to non-admin
        if ($id == $_SESSION['user_id'] && $_POST['role'] !== 'admin') {
            $_SESSION['error'] = 'You cannot change your own role to non-admin.';
            header('Location: /admin/accounts');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = $_POST['role'] ?? 'extensionist';
        $college_id = ($role !== 'admin') ? ($_POST['college_id'] ?? null) : null;
        $status = $_POST['status'] ?? 'pending';
        $password = $_POST['password'] ?? null;

        if ($id && $name && $email) {
            if (!empty($password)) {
                $this->userModel->update($id, $name, $email, $role, $college_id, $status, $password);
            } else {
                $this->userModel->update($id, $name, $email, $role, $college_id, $status);
            }
            $_SESSION['success'] = 'Account updated successfully.';
        } else {
            $_SESSION['error'] = 'Invalid data.';
        }
        header('Location: /admin/accounts');
        exit;
    }

    public function approve()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->userModel->approve($id);
            $_SESSION['success'] = 'Account approved.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/accounts');
        exit;
    }

    // Decline user
    public function decline()
    {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $this->userModel->decline($id);
            $_SESSION['success'] = 'Account declined.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/accounts');
        exit;
    }

    // Delete user
    public function delete()
    {
        $id = $_POST['id'] ?? 0;


        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'You cannot delete your own account.';
            header('Location: /admin/accounts');
            exit;
        }
        if ($id) {
            $this->userModel->delete($id);
            $_SESSION['success'] = 'Account deleted.';
        } else {
            $_SESSION['error'] = 'Invalid ID.';
        }
        header('Location: /admin/accounts');
        exit;
    }
}
