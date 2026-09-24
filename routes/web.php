<?php

$routes['/'] = ['controller' => 'PageController', 'action' => 'landing'];
$routes['/login'] = ['controller' => 'AuthController', 'action' => 'showLoginForm'];
$routes['/register'] = ['controller' => 'AuthController', 'action' => 'showRegisterForm'];
$routes['/logout'] = ['controller' => 'AuthController', 'action' => 'logout'];
$routes['/verify-otp'] = ['controller' => 'AuthController', 'action' => 'showVerifyOtp'];

$routes['/news'] = ['controller' => 'NewsController', 'action' => 'index'];
$routes['/news/show'] = ['controller' => 'NewsController', 'action' => 'show'];
$routes['/events'] = ['controller' => 'EventsController', 'action' => 'index'];
$routes['/events/show'] = ['controller' => 'EventsController', 'action' => 'show'];

$postRoutes['/notifications/mark-all-read'] = ['NotificationController', 'markAllRead'];