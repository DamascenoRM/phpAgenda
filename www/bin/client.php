<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/bin/database.php');

class Client {
    private $db;

    function __construct() {
        $this->db = new MySQLConnection();
    }

    public function createClient($personId, $phone) {
        try {
            $sql = "INSERT INTO clients (person_id, phone) VALUES (?, ?)";
            $params = array($personId, $phone);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function getClientByPersonId($personId) {
        try {
            $sql = "SELECT * FROM clients WHERE person_id = ?";
            $params = array($personId);
            $result = $this->db->executeQuery($sql, $params);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function updateClientByPersonId($personId, $phone) {
        try {
            $sql = "UPDATE clients SET phone = ? WHERE person_id = ?";
            $params = array($phone, $personId);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }

    public function deleteClientByPersonId($personId) {
        try {
            $sql = "DELETE FROM clients WHERE person_id = ?";
            $params = array($personId);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
    public function getClientById($id) {
        try {
            $sql = "SELECT * FROM clients WHERE id = ?";
            $params = array($id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
    public function getAllClients() {
        try {
            $sql = "SELECT * FROM clients";
            $result = $this->db->executeQuery($sql);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
    public function updateClientById($id, $personId, $phone) {
        try {
            $sql = "UPDATE clients SET person_id = ?, phone = ? WHERE id = ?";
            $params = array($personId, $phone, $id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
    public function deleteClientById($id) {
        try {
            $sql = "DELETE FROM clients WHERE id = ?";
            $params = array($id);
            $result = $this->db->executeQuery($sql, $params);
            return $result->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error as needed
            return false;
        }
    }
}
?>