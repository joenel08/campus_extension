
<?php
$routes['/admin/dashboard']     = ['controller' => 'Admin\\DashboardController', 'action' => 'index'];
$routes['/admin/accounts']      = ['controller' => 'Admin\\AccountsController', 'action' => 'index'];
$routes['/admin/accounts/create'] = ['controller' => 'Admin\\AccountsController', 'action' => 'create'];
$routes['/admin/accounts/edit']  = ['controller' => 'Admin\\AccountsController', 'action' => 'edit'];
$routes['/admin/submitpaper']   = ['controller' => 'Admin\\SubmitPaperController', 'action' => 'index'];
$routes['/admin/banner']        = ['controller' => 'Admin\\BannerController', 'action' => 'index'];


$routes['/admin/colleges'] = ['controller' => 'Admin\\CollegeController', 'action' => 'index'];

$routes['/admin/news']          = ['controller' => 'Admin\\NewsController', 'action' => 'index'];
$routes['/admin/news/create']   = ['controller' => 'Admin\\NewsController', 'action' => 'create'];
$routes['/admin/news/edit']     = ['controller' => 'Admin\\NewsController', 'action' => 'edit'];


$routes['/admin/events']        = ['controller' => 'Admin\\EventsController', 'action' => 'index'];
$routes['/admin/events/create'] = ['controller' => 'Admin\\EventsController', 'action' => 'create'];
$routes['/admin/events/edit']   = ['controller' => 'Admin\\EventsController', 'action' => 'edit'];


$routes['/admin/officials']        = ['controller' => 'Admin\\OfficialController', 'action' => 'index'];
$routes['/admin/officials/create'] = ['controller' => 'Admin\\OfficialController', 'action' => 'create'];
$routes['/admin/officials/edit']   = ['controller' => 'Admin\\OfficialController', 'action' => 'edit'];
$routes['/admin/about'] = ['controller' => 'Admin\\AboutController', 'action' => 'index'];

$routes['/admin/evaluation'] = ['controller' => 'Admin\\EvaluationController', 'action' => 'index'];
$routes['/admin/evaluation/create-group'] = ['controller' => 'Admin\\EvaluationController', 'action' => 'createGroup'];
$routes['/admin/evaluation/edit-group'] = ['controller' => 'Admin\\EvaluationController', 'action' => 'editGroup'];
$routes['/admin/evaluation/create-criteria'] = ['controller' => 'Admin\\EvaluationController', 'action' => 'createCriteria'];
$routes['/admin/evaluation/edit-criteria'] = ['controller' => 'Admin\\EvaluationController', 'action' => 'editCriteria'];

$routes['/admin/proposal']      = ['controller' => 'Admin\\ProposalController', 'action' => 'index'];
$routes['/admin/proposal/create'] = ['controller' => 'Admin\\ProposalController', 'action' => 'create'];
$routes['/admin/proposal/edit'] = ['controller' => 'Admin\\ProposalController', 'action' => 'edit'];

$routes['/admin/crest'] = ['controller' => 'Admin\\CrestController', 'action' => 'index'];
$routes['/admin/crest/create'] = ['controller' => 'Admin\\CrestController', 'action' => 'create'];
$routes['/admin/crest/edit'] = ['controller' => 'Admin\\CrestController', 'action' => 'edit'];

$routes['/admin/monitoring'] = ['controller' => 'Admin\\MonitoringController', 'action' => 'index'];
// $routes['/extensionist/submissions/show'] = ['controller' => 'Extensionist\\SubmissionController', 'action' => 'show'];
$routes['/admin/submissions/show'] = ['controller' => 'Admin\\MonitoringController', 'action' => 'show'];

$routes['/admin/monitoring/assign-modal'] = ['controller' => 'Admin\\MonitoringController', 'action' => 'assignModal'];