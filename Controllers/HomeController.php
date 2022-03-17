<?php

namespace app\Controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\Models\LoginModel;
use app\Models\RegisterModel;

class HomeController extends Controller
{

    public function renderView($params = [])
    {
/*        if ($_SESSION['isLoggedIn'])
        {
            return $this->render('main', $params);
        }*/
        return $this->render('home', $params);
    }

    public function register(Request $request)
    {
        $register = new RegisterModel();
        $register->register($request->getBody());
    }

    public function login(Request $request)
    {
        $login = new LoginModel();
        if($login->logIn($request->getBody()))
        {
            return "login";
        }
        else
        {
            $this->renderView(array(
                'error' => '<span style = "color:#ff0000">Błędny login lub hasło!</span>'
            ));
        }
    }
}