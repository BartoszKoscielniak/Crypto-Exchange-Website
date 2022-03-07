<?php

const URL_SUBFOLDER = 'Crypto-Exchange';

use app\core\Application;
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application(dirname(__DIR__));

$app->router->get('/main', function(){
   return 'Hello World';
});

$app->router->get('/', 'home');

$app->router->post('/', function(){
    return "handling submitted data";
});

$app->run();