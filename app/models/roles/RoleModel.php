<?php

namespace App\models\roles;

use App\models\Database;

class RoleModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        try {
            $this->db->query("SELECT 1 FROM `roles` LIMIT 1");
        }
        catch (\PDOException $e) {
            $this->createTable();
        }
    }

    public function createTable(): bool
    {
        $roleTableQuery = "CREATE TABLE IF NOT EXISTS `roles` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `role_name` VARCHAR(255) NOT NULL,
            `role_description` TEXT
            )";

        try {
            $this->db->exec($roleTableQuery);
            return true;
        }
        catch (\PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function getAllRoles()
    {
        try {
            $result = $this->db->prepare("SELECT * FROM `roles`");
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            return false;
        }
    }

    public function getRoleById($id)
    {
        try {
            $result = $this->db->prepare("SELECT * FROM `roles` WHERE id = ?");
            $result->execute([$id]);
            $role = $result->fetch(\PDO::FETCH_ASSOC);
            return $role ? $role : false;
        }
        catch (\PDOException $e) {
            return false;
        }
    }

    public function createRole($rolename, $roleDescription ): bool
    {
        $query = "INSERT INTO roles (role_name, role_description) VALUES (:roleName, :roleDescription)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('roleName', $rolename);
            $stmt->bindParam('roleDescription', $roleDescription);

            return $stmt->execute();
        } catch (\PDOException $e) {
            //var_dump($e);
            return false;
        }
    }

    public function updateRole($id, $rolename, $roleDescription ): bool
    {
        $query = "UPDATE roles SET role_name = :roleName, role_description = :roleDescription WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('roleName', $rolename);
            $stmt->bindParam('roleDescription', $roleDescription);
            $stmt->bindParam('id', $id);

            return $stmt->execute();
        } catch (\PDOException $e) {
            //var_dump($e);
            return false;
        }
    }

    public function deleteRole($id)
    {
        $query = "DELETE FROM roles WHERE id=?";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

}