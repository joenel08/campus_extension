<?php
$routes['/evaluator/dashboard'] = ['controller' => 'Evaluator\\DashboardController', 'action' => 'index'];
$routes['/evaluator/evaluations'] = ['controller' => 'Evaluator\\EvaluationController', 'action' => 'index'];
$routes['/evaluator/evaluate'] = ['controller' => 'Evaluator\\EvaluationController', 'action' => 'evaluate'];