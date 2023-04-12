<?php

namespace App\controllers\auth;

use App\models\AuthUser;

class AuthController
{
    public function register()
    {
        include 'app/views/users/register.php';
    }

    public function store()
    {
        if(isset($_POST['username']) && isset($_POST['email'])
            && isset($_POST['password']) && isset($_POST['confirm_password']))
        {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            if( empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                echo 'All field are required';
                return ;
            }
            if ($password !== $confirm_password) {
                echo 'Password do not match';
                return false;
            }

            $userModel = new AuthUser();
            if(!$userModel->register($username, $email, $password)) {
                var_dump($userModel);
                die();
            }
        }
        header("Location: index.php?page=login");
    }

    public function login(): void
    {
        include 'app/views/users/login.php';
    }

    public function authenticate()
    {
        $authModel = new AuthUser();

        if(isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $remember = isset($_POST['remember']) ? $_POST['remember'] : '';
            var_dump($password);
            var_dump(password_hash($password, PASSWORD_DEFAULT));

            $user = $authModel->findByEmail($email);
            var_dump($user);
            var_dump($user['password']);

            if($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];

                if($remember == 'on') {
                    setcookie('user_email', $email, time() + 60 * 60 , '/');
                    setcookie('user_password', $password, time() + 60 * 60 , '/');
                }
                header('location: index.php');
            } else {
                echo "Invalid email or password";
            }
        }
    }
    public function logout(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: index.php');
    }
}