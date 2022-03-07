<?php

namespace app\core;

class Application
{
    public static string $ROOT_DIR;
    public Response $response;
    public Router $router;
    public Request $request;
    public static Application $app;

    public function __construct($rootPath)
    {
        self::$app = $this;
        self::$ROOT_DIR = $rootPath;
        $this->response = new Response();
        $this->request = new Request();
        $this->router =  new Router($this->request, $this->response);
    }

    public function run(){
        echo $this->router->resolve();
    }

}