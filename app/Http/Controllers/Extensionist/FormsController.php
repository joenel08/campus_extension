<?php

namespace Extensionist;

require_once __DIR__ . '/../../../models/Template.php';

class FormsController extends \ExtensionistBaseController
{
    private $templateModel;

    public function __construct()
    {
        parent::__construct();
        $config = require __DIR__ . '/../../../../config/database.php';
        $pdo = new \PDO("mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}", $config['username'], $config['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->templateModel = new \Template($pdo);
    }

    public function index()
    {
        $templates = $this->templateModel->getActive();

        $this->render('forms', [
            'templates' => $templates,
        ]);
    }
}