<?php

const URL_SUBFOLDER = 'Crypto-Exchange';

use app\core\Application;
use app\Controllers\HomeController;
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application(dirname(__DIR__));

$app->router->get('/main', function(){
   return 'Hello World';
});

$app->router->get('/', [HomeController::class, 'renderView']);
$app->router->get('/home', 'main');

$app->router->post('/', function(){
    return "handling submitted data";
});

$app->router->post('/register', [HomeController::class, 'register']);

$app->router->post('/login', [HomeController::class, 'login']);



$app->run();