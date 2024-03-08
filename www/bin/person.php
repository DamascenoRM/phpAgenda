<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/bin/database.php');

class Person {
    private $db;

    function __construct() {
        $this->db = new MySQLConnection();
    }

    public function createPerson($name, $age) {
        try {
            $sql = "INSERT INTO persons (name, age) VALUES (?, ?)";
            $params = array($name, $age);
            $result = $this->db->executeQuery($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function getPersonById($id) {
        try {
            $sql = "SELECT * FROM persons WHERE id = ?";
            $params = array($id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function updatePerson($id, $name, $age) {
        try {
            $sql = "UPDATE persons SET name = ?, age = ? WHERE id = ?";
            $params = array($name, $age, $id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function deletePerson($id) {
        try {
            $sql = "DELETE FROM persons WHERE id = ?";
            $params = array($id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function getAllPersons() {
        try {
            $sql = "SELECT * FROM persons";
            $result = $this->db->executeQuery($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
}

?>
