<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');
require_once(ROOT_PATH . '/bin/database.php');
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
$person = new Person();

// Rotas para a API REST
switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $personId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($personId === false || $personId === null) {
                sendResponse(400, array("message" => "Invalid ID"));
                return;
            }
            $result = $person->getPersonById($personId);
            if ($result === null || $result == false) {
                sendResponse(404, array("message" => "Person Not Found"));
            } else {
                sendResponse(200, $result);
            }
        } else {
            sendResponse(200, $person->getAllPersons());
        }
        break;

    case 'POST':
        // Verifica se os parâmetros necessários foram fornecidos
        if (!isset($_POST['name']) || !isset($_POST['age'])) {
            sendResponse(400, array("message" => "Name and age parameters are required"));
            return;
        }

        // Extrai e sanitiza os parâmetros
        $name = strval(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);

        // Verifica se os parâmetros foram fornecidos corretamente
        if ($name === false || $age === false || $age === null) {
            sendResponse(400, array("message" => "Invalid name or age parameter"));
            return;
        }

        // Cria a pessoa
        $result = $person->createPerson($name, $age);

        if ($result) {
            $persondata = $person->getPersonById($result); // Assume-se que o método createPerson retorne o ID da nova pessoa
            sendResponse(201, array("message" => "Person created successfully", "data" => $persondata));
        } else {
            sendResponse(500, array("message" => "Failed to create person"));
        }
        break;

    case 'PUT':
        if (!isset($_GET['id'])) {
            sendResponse(400, array("message" => "ID parameter is required"));
            return;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            sendResponse(400, array("message" => "Invalid ID parameter"));
            return;
        }

        parse_str(file_get_contents("php://input"), $_PUT);
        if (!isset($_PUT['name'], $_PUT['age'])) {
            sendResponse(422, array("message" => "Name and age parameters are required"));
            return;
        }

        $name = strval(filter_var($_PUT['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $age = filter_var($_PUT['age'], FILTER_VALIDATE_INT);
        if ($name === false || $age === false || $age === null) {
            sendResponse(422, array("message" => "Invalid name or age parameter"));
            return;
        }

        $result = $person->updatePerson($id, $name, $age);
        if ($result) {
            $persondata = $person->getPersonById($id);
            sendResponse(200, array("message" => "Person updated successfully", "data" => $persondata));
        } else {
            sendResponse(500, array("message" => "Failed to update person"));
        }
        break;

    case 'DELETE':
        // Verifica se o parâmetro 'id' está presente na URL
        if (!isset($_GET['id'])) {
            sendResponse(400, array("message" => "ID parameter is required"));
            return;
        }
        
        // Obtém o ID da pessoa a ser excluída
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null) {
            sendResponse(400, array("message" => "Invalid ID parameter"));
            return;
        }
        
        // Chama o método para excluir a pessoa
        $persondata = $person->getPersonById($id); // Assume-se que o método createPerson retorne o ID da nova pessoa
        $result = $person->deletePerson($id);
        if ($result) {
            sendResponse(200, array("message" => "Person deleted successfully", "data" => $persondata));
        } else {
            sendResponse(500, array("message" => "Failed to delete person"));
        }
        break;
}