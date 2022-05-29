<?php

// Require composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

// Define routes
$router->get('/db', function() {
    $db = new FernandoDefez\Agenda\App\Database();
    $db->connect();
});

$router->get('/', 'FernandoDefez\Agenda\App\Http\Controller\ContactController@index');

$router->set404(function () {
    include("resources/views/404.php");
});

$router->mount('/api/v1', function() use ($router) {
    $router->get('/contacts', 'FernandoDefez\Agenda\App\Http\Controller\ContactController@show');
    $router->get('/contacts/{id}', 'FernandoDefez\Agenda\App\Http\Controller\ContactController@find');
    $router->post('/contacts', 'FernandoDefez\Agenda\App\Http\Controller\ContactController@store');
    $router->delete('/contacts', 'FernandoDefez\Agenda\App\Http\Controller\ContactController@destroy');
});

$router->set404('/api(/.*)?', function() {
    header('HTTP/1.1 404 Not Found');header('Content-Type: application/json');

    echo json_encode(
        [
            'status' => 404,
            'message' => "Endpoint not defined",
        ]
    );
});

// Run it!
$router->run();