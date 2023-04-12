<?php

namespace App\controllers\roles;

use App\models\roles\RoleModel;

class RoleController
{
    public function index(): void
    {
        $roleModel = new RoleModel();
        $roles = $roleModel->getAllRoles();
        include 'app/views/roles/index.php';
    }

    public function create(): void
    {
        include 'app/views/roles/create.php';
    }

    public function store()
    {
        if(isset($_POST['role_name']) && isset($_POST['role_description'])) {
            $roleName = $_POST['role_name'];
            $roleDescription = $_POST['role_description'];

            if (empty($roleName)) {
                echo 'RoleModel name is required';
                return;
            }

            $roleModel = new RoleModel();
            $roleModel->createRole($roleName, $roleDescription);
        }
        header("Location: index.php?page=roles");
    }

    public function edit($id): void
    {
        $roleModel = new RoleModel();
        $role = $roleModel->getRoleById($id);

        if(!$role) {
            echo 'RoleModel not find!';
            return;
        }
        include 'app/views/roles/edit.php';
    }

    public function update(): void
    {
        if(isset($_POST['id']) && isset($_POST['role_name']) && isset($_POST['role_description'])) {
            $id = trim($_POST['id']);
            $roleName = trim($_POST['role_name']);
            $roleDescription = trim($_POST['role_description']);

            if (empty($roleName)) {
                echo 'RoleModel name is required';
                return;
            }

            $roleModel = new RoleModel();
            $roleModel->updateRole($id, $roleName, $roleDescription);
        }
        header("Location: index.php?page=roles");
    }

    public function delete(): void
    {
        $roleModel = new RoleModel();
        $roleModel->deleteRole($_GET['id']);

        header("Location: index.php?page=users");
    }

}