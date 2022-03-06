<?php

const URL_SUBFOLDER = 'Crypto-Exchange';

use app\core\Application;
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$app->router->get('/', function(){
   return 'Hello World';
});

$app->router->get('/contact', function(){
   return 'Contact';
});

$app->run();