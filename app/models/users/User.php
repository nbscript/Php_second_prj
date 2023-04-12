<?php

namespace App\models\users;

use App\models\Database;

class User
{
    private \PDO $db;

    /**
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        try {
            $this->db->query("SELECT 1 FROM `users` LIMIT 1");
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

        $userTableQuery= "CREATE TABLE IF NOT EXISTS `users` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `username` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `email_verification` TINYINT(1) DEFAULT 0,
            `password` VARCHAR(255) NOT NULL,
            `is_admin` TINYINT(1) NOT NULL DEFAULT 0,
            `role` INT(11) NOT NULL DEFAULT 1,
            `is_active` TINYINT(1) NOT NULL DEFAULT 1,
            `last_login` TIMESTAMP NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`role`) REFERENCES `roles`(`id`)
            )";
        try {
            $this->db->exec($roleTableQuery);
            $this->db->exec($userTableQuery);
            return true;
        }
        catch (\PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function readAll()
    {
        try {
            $result = $this->db->query("SELECT * FROM `users`");
            $users = [];
            while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
            return $users;
        }
        catch (\PDOException $e) {
            return false;
        }
    }

    public function create($data): bool
    {
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];

        $created_at = date('Y-m-d H:i:s');

        $query = "INSERT INTO users (username, email, password, role, created_at) VALUES (:username,:email,:password,:role, :createdAt)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username, \PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, \PDO::PARAM_INT);
            $stmt->bindParam(':createdAt', $created_at, \PDO::PARAM_STR);
            //var_dump($stmt);
            $stmt->execute();
            return true;
        }
        catch (\PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function delete($id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bindParam(1, $id, \PDO::PARAM_STR);

            $stmt->execute();
            return true;
        }
        catch (\PDOException $e) {
            return false;
        }
    }

    public function read($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id= ?");
            $stmt->bindParam(1, $id, \PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }
    catch (\PDOException $e) {
        return false;
    }
    }

    public function update($id, $data)
    {
        $username = $data['username'];
        $email = $data['email'];
        $admin = (isset($data['admin']) && $data['admin'] != 0) ? 1 : 0;
        $role = $data['role'];


        try {
            $stmt = $this->db->prepare("UPDATE users SET username = :username, email =:email, is_admin = :admin, role = :role WHERE id = :id");
            $stmt->bindParam('username', $username, \PDO::PARAM_STR);
            $stmt->bindParam('admin', $admin, \PDO::PARAM_BOOL);
            $stmt->bindParam('id', $id, \PDO::PARAM_STR);
            $stmt->bindParam('email', $email, \PDO::PARAM_STR);
            $stmt->bindParam('role', $role, \PDO::PARAM_STR);

            $stmt->execute();
            return true;
        }
        catch (\PDOException $e) {
            return false;
        }
    }

}