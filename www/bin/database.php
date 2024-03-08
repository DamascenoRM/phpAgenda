<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');

class MySQLConnection {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $database = DB_NAME;
    private $connection;

    public function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database}";
            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed -> $e");
        }
    }

    public function executeQuery($sql, $params = []) {
        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute($params);
            return $statement;
        } catch(PDOException $e) {
            echo "Error in query execution";
            $this->closeConnection();
            return false;
        }
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    private function closeConnection() {
        $this->connection = null;
    }

    public function __destruct() {
        $this->closeConnection();
    }
}

