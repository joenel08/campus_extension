<?php
$routes['/extensionist/dashboard'] = ['controller' => 'Extensionist\\DashboardController', 'action' => 'index'];
$routes['/extensionist/forms']     = ['controller' => 'Extensionist\\FormsController', 'action' => 'index'];

$routes['/extensionist/submissions'] = ['controller' => 'Extensionist\\SubmissionController', 'action' => 'index'];
$routes['/extensionist/submissions/create'] = ['controller' => 'Extensionist\\SubmissionController', 'action' => 'create'];
$routes['/extensionist/submissions/edit'] = ['controller' => 'Extensionist\\SubmissionController', 'action' => 'edit'];
$routes['/extensionist/submissions/show'] = ['controller' => 'Extensionist\\SubmissionController', 'action' => 'show'];

$routes['/extensionist/report-data'] = ['controller' => 'Extensionist\\SubmissionController', 'action' => 'getReportData'];