<?php

namespace App\models;

use PDO;

final class Database
{
    private static ?self $instance = null;

    public PDO $connection;

    public function __construct()
    {
        $config = require_once __DIR__ . '/../../config.php';
        $db_host     = DB_HOST;
        $db_username = DB_USER;
        $db_password = DB_PASS;
        $db_name     = DB_NAME;
        $db_charset  = DB_CHARSET;
        try {
            $this->connection = new PDO("mysql:host=$db_host; dbname=$db_name;
                charset=$db_charset", $db_username, $db_password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (\PDOException $exception) {
            echo "Connection failed: " . $exception->getMessage();
        }
    }

    public static function getInstance(): ?self
    {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

}