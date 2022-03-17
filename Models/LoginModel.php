<?php

namespace app\Models;

use app\core\Controller;

class LoginModel
{

    public function validate(Array $loginData)
    {
        print_r($loginData);
    }

    public function logIn(Array $loginData)
    {
        $database = new Database();
        $loginData['email'] = htmlentities($loginData['email']);
        $user = $database->findUserWithEmail($loginData['email']);
        if ($user === false)
        {
            return false;
        }
        else
        {
            if(password_verify($loginData['password'], $user->getPassword()))
            {
                session_start();
                $_SESSION['user'] = $user;
                $_SESSION['isLoggedIn'] = true;
                return true;
            }
            else
            {
                return false;
            }
        }
    }

}