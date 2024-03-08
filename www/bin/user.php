<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/bin/database.php');

class User {
    private $db;

    function __construct() {
        $this->db = new MySQLConnection();
    }

    public function createUser($personId, $username, $email) {
        try {
            $sql = "INSERT INTO users (person_id, username, email) VALUES (?, ?, ?)";
            $params = array($personId, $username, $email);
            $result = $this->db->executeQuery($sql, $params);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
    public function getUserById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = ?";
            $params = array($id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function getUserByPersonId($personId) {
        try {
            $sql = "SELECT * FROM users WHERE person_id = ?";
            $params = array($personId);
            $result = $this->db->executeQuery($sql, $params);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function updateUserByPersonId($personId, $username, $email) {
        try {
            $sql = "UPDATE users SET username = ?, email = ? WHERE person_id = ?";
            $params = array($username, $email, $personId);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function updateUserById($id, $personId, $username, $email) {
        try {
            $sql = "UPDATE users SET person_id = ?, username = ?, email = ? WHERE id = ?";
            $params = array($personId, $username, $email, $id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function deleteUserByPersonId($personId) {
        try {
            $sql = "DELETE FROM users WHERE person_id = ?";
            $params = array($personId);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function deleteUserById($id) {
        try {
            $sql = "DELETE FROM users WHERE id = ?";
            $params = array($id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function getAllUsers() {
        try {
            $sql = "SELECT * FROM users";
            $result = $this->db->executeQuery($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
}
?>
