<?php
// routes/evaluator.php
$routes['/evaluator/dashboard']   = ['controller' => 'Evaluator\\DashboardController', 'action' => 'index'];
$routes['/evaluator/evaluations'] = ['controller' => 'Evaluator\\DashboardController', 'action' => 'evaluations'];
$routes['/evaluator/evaluate']    = ['controller' => 'Evaluator\\EvaluationController', 'action' => 'evaluate'];