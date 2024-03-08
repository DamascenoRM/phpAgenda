<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');
require_once(ROOT_PATH . '/bin/database.php');
require_once(ROOT_PATH . '/bin/user.php');
require_once(ROOT_PATH . '/bin/client.php');
require_once(ROOT_PATH . '/bin/person.php');

// Função para retornar erros em formato JSON
function sendResponse($status, $data)
{
    header("Content-Type: application/json");
    http_response_code($status);
    echo json_encode($data);
    exit();
}

// Verificando o método da requisição
$method = $_SERVER['REQUEST_METHOD'];

// Inicializando objetos das classes
$client = new User();
$person = new Person();
$client = new Client();

// Rotas para a API REST
switch ($method) {
    case 'GET':
        // Obtendo todos os usuários
        if (isset($_GET['users'])) {
            sendResponse(200, $client->getAllUsers());
        }
        // Obtendo todos as pessoas
        elseif (isset($_GET['persons'])) {
            sendResponse(200, $person->getAllPersons());
        }
        // Obtendo todos os clientes
        elseif (isset($_GET['clients'])) {
            sendResponse(200, $client->getAllClients());
        }
        break;

    case 'POST':
        // Criando um usuário
        if (isset($_POST['create_user'])) {
            $result = $client->createUser($_POST['person_id'], $_POST['username'], $_POST['email']);
            if ($result) {
                sendResponse(201, array("message" => "User created successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to create user"));
            }
        }
        // Criando uma pessoa
        elseif (isset($_POST['create_person'])) {
            $result = $person->createPerson($_POST['name'], $_POST['age']);
            if ($result) {
                sendResponse(201, array("message" => "Person created successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to create person"));
            }
        }
        // Criando um cliente
        elseif (isset($_POST['create_client'])) {
            $result = $client->createClient($_POST['person_id'], $_POST['phone']);
            if ($result) {
                sendResponse(201, array("message" => "Client created successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to create client"));
            }
        }
        break;

    case 'PUT':
        // Atualizando usuário
        parse_str(file_get_contents("php://input"), $_PUT);
        if (isset($_PUT['update_user'])) {
            $result = $client->updateUserByPersonId($_PUT['person_id'], $_PUT['username'], $_PUT['email']);
            if ($result) {
                sendResponse(200, array("message" => "User updated successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to update user"));
            }
        }
        // Atualizando pessoa
        elseif (isset($_PUT['update_person'])) {
            $result = $person->updatePerson($_PUT['id'], $_PUT['name'], $_PUT['age']);
            if ($result) {
                sendResponse(200, array("message" => "Person updated successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to update person"));
            }
        }
        // Atualizando cliente
        elseif (isset($_PUT['update_client'])) {
            $result = $client->updateClientByPersonId($_PUT['person_id'], $_PUT['phone']);
            if ($result) {
                sendResponse(200, array("message" => "Client updated successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to update client"));
            }
        }
        break;

    case 'DELETE':
        // Deletando usuário
        parse_str(file_get_contents("php://input"), $_DELETE);
        if (isset($_DELETE['delete_user'])) {
            $result = $client->deleteUserByPersonId($_DELETE['person_id']);
            if ($result) {
                sendResponse(200, array("message" => "User deleted successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to delete user"));
            }
        }
        // Deletando pessoa
        elseif (isset($_DELETE['delete_person'])) {
            $result = $person->deletePerson($_DELETE['id']);
            if ($result) {
                sendResponse(200, array("message" => "Person deleted successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to delete person"));
            }
        }
        // Deletando cliente
        elseif (isset($_DELETE['delete_client'])) {
            $result = $client->deleteClientByPersonId($_DELETE['person_id']);
            if ($result) {
                sendResponse(200, array("message" => "Client deleted successfully"));
            } else {
                sendResponse(500, array("message" => "Failed to delete client"));
            }
        }
        break;

    default:
        sendResponse(405, array("message" => "Method Not Allowed"));
        break;
}