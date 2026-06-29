<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = Config::get('db.host');
            $port = Config::get('db.port');
            $name = Config::get('db.name');
            $user = Config::get('db.user');
            $pass = Config::get('db.pass');

            $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

            try {
                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
                ]);
            } catch (PDOException $e) {
                error_log('DB connection failed: ' . $e->getMessage());
                http_response_code(500);
                die('Database connection failed. Please try again later.');
            }
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}
}
