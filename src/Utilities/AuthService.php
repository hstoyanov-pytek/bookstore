<?php

namespace App\Utilities;
use \App\Model\UserDetailsDTO;
use \App\Model\UserModel;
use \App\Model\RolesModel;

class AuthService
{
    public function __construct(
        private \App\Utilities\Database\Database $database
    ){}

    public function handleLogin(string $email, string $password, string &$role) : bool
    {
        if(empty($email) || empty($password)) return false;

        $whereParams = $this->database->createParams(cols: ['users.email'], alias:["usersEmail"], values: [$email], operators: ['=']);
        $selectParams = $this->database->createSelectParams(
            cols:[
                'users.id',
                'users.email',
                'users.password',
                'users.name',
                'roles.name'
            ],
            alias: [
                'user_id',
                'user_email',
                'user_password',
                'user_name',
                'user_role'
            ]
        );

        $existingUser = $this->database::$select
            ->select($selectParams)
            ->from("users")
            ->innerJoin("users_roles", "users_roles.users_id = users.id")
            ->innerJoin("roles", "users_roles.roles_id = roles.id")
            ->where($whereParams)
            ->getResult(UserDetailsDTO::class);

        if(empty($existingUser)) return false;

        $role = $existingUser->user_role;

        $passwordHash = $existingUser->user_password;

        if(password_verify($password, $passwordHash))
        {
            $this->ensureSessionUnique();
            $_SESSION['role'] = $existingUser->user_role;
            $_SESSION['userId'] = $existingUser->user_id;
            session_regenerate_id();
            return true;
        }

        return false;
    }

    public function handleLogout() : void
    {
        $this->ensureSessionUnique();
        unset($_SESSION['userId']);
        unset($_SESSION['role']);
        session_regenerate_id();
    }

    public function isLoggedIn() : bool
    {
        $this->ensureSessionUnique();
        return !empty($_SESSION['userId']);
    }

    public function ensureLoggedIn() : void
    {
        $isLoggedIn = $this->isLoggedIn();
        if(empty($isLoggedIn))
        {
            header("Location: /");
        }
    }

    public function ensureSessionUnique() : void
    {
        if(session_id() === "")
        {
            session_start();
        }
    }

    public function handleRegister(string $email, string $password, string $name, array &$errors) : bool
    {
        $name = trim($name);
        if(!preg_match('/^[a-zA-Z0-9\-]+$/', $name))
        {
            $errors[] = "Name contains invalid characters";
            return false;
        }

        $email = trim($email);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $errors[] = "Please enter a valid email!";
            return false;
        }

        if(mb_strlen($password) < 10)
        {
            $errors[] = "Password should be at least 10 characters!";
            return false;
        }

        $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

        $this->database::$insert
            ->insertInto("users", ["name", "email", "password"])
            ->values(array($name, $email, $hashedPwd))
            ->exec();

        $whereUser = $this->database->createParams(["email"],[$email],["="]);
        $registeredUser = $this->database::$select
            ->select()
            ->from("users")
            ->where($whereUser)
            ->getResult(UserModel::class);

        $whereRoles = $this->database->createParams(["name"],["user"],["="]);
        $role = $this->database::$select
            ->select()
            ->from("roles")
            ->where($whereRoles)
            ->getResult(RolesModel::class);

        $this->database::$insert
            ->insertInto("users_roles", ["users_id", "roles_id"])
            ->values(array($registeredUser->id, $role->id))
            ->exec();

        return true;
    }
}