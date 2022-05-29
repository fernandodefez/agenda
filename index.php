<?php

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

// Define routes
$router->get('/', function() {
    $db = new FernandoDefez\Agenda\App\Database();
    $db->connect();
});

$router->get('', '\FernandoDefez\Agenda\App\Http\Controllers\ContactController@index');

// Run it!
$router->run();