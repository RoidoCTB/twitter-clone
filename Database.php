<?php

class Database {
    private static $instance = null; // Singleton instance
    private $connection;             // PDO connection
    
    private $host = 'localhost';
    private $dbName = 'user';
    private $username = 'root';
    private $password = '';

    private function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Throw exceptions on errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Fetch as associative arrays by default
            ]);
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage()); // Stop if connection fails
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database(); // Create instance if none exists
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection; // Return PDO connection
    }
}
?>

