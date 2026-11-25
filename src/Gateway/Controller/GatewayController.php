<?php

namespace App\Gateway\Controller;

class GatewayController extends BaseGatewayController
{
    public function __construct(
        \App\Utilities\AuthService $authService
    ){
        parent::__construct($authService);
    }

    public function login() : void
    {
        $errors = array();

        if($this->authService->isLoggedIn())
        {
            header("Location: /");
            return;
        }

        if (!empty($_POST))
        {
            $email = @(string)($_POST['email']) ?? "";
            $password = @(string)($_POST['password']) ?? "";
            $role = "";

            if($this->authService->handleLogin($email, $password, $role))
            {
                match($role)
                {
                    'admin' => header("Location: /admin"),
                    'editor'=> header("Location: /admin"),
                    default => header("Location: /") // covering both user and whatever shady role shows up
                };

                return;
            }
            $errors[] = "A field was incorrect, try again";
        }

        $this->render("login", [
            'errors' => $errors
        ]);
    }

    public function Logout()
    {
        $this->authService->handleLogout();
         header("Location: /");
         die();
    }

    public function register() : void
    {
        $errors = array();
        if($this->authService->isLoggedIn())
        {
            header("Location: /");
            return;
        }

        if(!empty($_POST))
        {
            if(empty($_POST['TOS']))
            {
                $errors[] = "You have to agree with the TOS to register!";
            }
            else
            {
                $email = @(string)($_POST['email']) ?? "";
                $password = @(string)($_POST['password']) ?? "";
                $name = @(string)($_POST['name']) ?? "";

                if($this->authService->handleRegister($email, $password, $name, $errors))
                {
                    header("Location: /login");
                    return;
                }
            }
        }

        $this->render("register", [
            'errors' => $errors
        ]);
    }
}