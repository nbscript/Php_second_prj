<?php


namespace App\controllers\users;

use App\models\roles\RoleModel;
use App\models\users\User;

class UsersController
{
    public function index(): void
    {
        $userModel = new User();
        $users = $userModel->readAll();

        include 'app/views/users/index.php';
    }

    public function create(): void
    {
        include 'app/views/users/create.php';
    }

    public function store(): bool
    {
        if(isset($_POST['username']) && isset($_POST['email'])
            && isset($_POST['password']) && isset($_POST['confirm_password']))
            {
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                echo 'Password do not match';
                return false;
            }

            $userModel = new User();
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 1, //default role
            ];
            $userModel->create($data);
            header("Location: index.php?page=users");
        }
    }

    public function edit(): void
    {
        $userModel = new User();
        $user = $userModel->read($_GET['id']);

        $roleModel = new RoleModel();
        $roles = $roleModel->getAllRoles();

        include 'app/views/users/edit.php';
    }

    public function update(): void
    {
        $userModel = new User();
        $userModel->update($_GET['id'], $_POST);

        header("Location: index.php?page=users");
    }

    public function delete(): void
    {
        $userModel = new User();
        $userModel->delete($_GET['id']);

        header("Location: index.php?page=users");
    }

}