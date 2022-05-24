<?php

require __DIR__ . "/../vendor/autoload.php";

use CoffeeCode\Router\Router;

$route = new Router(CONF_URL_API_BASE, ":");

//users
$route->namespace('Source\App\Api\User');
$route->group(null);
$route->get('/user', 'Users:getUsers');

//posts
$route->dispatch();


//error
if($route->error()){
    header('Content-type: application/json; charset=UTF-8');
    http_response_code(404);

    echo json_encode([
        'erros' => [
            "type" => "end_point_not_found",
            "message" => "Não foi possível processar a requisição"
        ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}



