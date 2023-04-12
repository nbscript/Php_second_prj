<?php

namespace App\models\pages;

use App\models\Database;

class PageModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        try {
            $this->db->query("SELECT 1 FROM `pages` LIMIT 1");
        }
        catch (\PDOException $e) {
            $this->createTable();
        }
    }

    public function createTable(): bool
    {
        $pageTableQuery = "CREATE TABLE IF NOT EXISTS `pages` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `slug` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        try {
            $this->db->exec($pageTableQuery);
            return true;
        }
        catch (\PDOException $e) {
            var_dump($e);
            return false;
        }
    }

    public function getAllPages()
    {
        try {
            $result = $this->db->prepare("SELECT * FROM `pages`");
            $result->execute();
            return $result->fetchAll(\PDO::FETCH_ASSOC);
        }
        catch (\PDOException $e) {
            return false;
        }
    }

    public function getPageById($id)
    {
        try {
            $result = $this->db->prepare("SELECT * FROM `pages` WHERE id = ?");
            $result->execute([$id]);
            $page = $result->fetch(\PDO::FETCH_ASSOC);
            return $page ? $page : false;
        }
        catch (\PDOException $e) {
            return false;
        }
    }

    public function createPage($title, $slug ): bool
    {
        $query = "INSERT INTO pages (title, slug) VALUES (:title, :slug)";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('title', $title);
            $stmt->bindParam('slug', $slug);

            return $stmt->execute();
        } catch (\PDOException $e) {
            //var_dump($e);
            return false;
        }
    }

    public function updatePage($id, $title, $slug ): bool
    {
        $query = "UPDATE pages SET title = :title, slug = :slug WHERE id = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam('title', $title);
            $stmt->bindParam('slug', $slug);
            $stmt->bindParam('id', $id);

            return $stmt->execute();
        } catch (\PDOException $e) {
            //var_dump($e);
            return false;
        }
    }

    public function deletePage($id): bool
    {
        $query = "DELETE FROM pages WHERE id=?";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }
}