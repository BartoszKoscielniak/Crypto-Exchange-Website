<?php

namespace app\core;

class Controller
{
    public static function render($view, $params = []){


        return Application::$app->router->renderView($view, $params);

    }
}