<?php

namespace App\models;

class AuthUser
{
    private $db;

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

    public function register($username, $email, $password): bool
    {
        $created_at = date('Y-m-d H:i:s');
        $password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password, created_at) VALUES (:username, :email, :password, :created_at)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('username', $username);
            $stmt->bindParam('email', $email);
            $stmt->bindParam('password', $password);
            $stmt->bindParam('created_at', $created_at);

            return $stmt->execute();
        } catch (\PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM users WHERE email=:email LIMIT 1";

            $stmt= $this->db->prepare($query);
            $stmt->bindParam('email', $email);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if($user && password_verify($password, $user['password'])) {
                return $user;
            }

            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function findByEmail($email)
    {
        try {
            $query = "SELECT * FROM users WHERE email=:email LIMIT 1";

            $stmt= $this->db->prepare($query);
            $stmt->bindParam('email', $email);
            $stmt->execute();

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $user ? $user : false;

        } catch (\PDOException $e) {
            return false;
        }
    }


}