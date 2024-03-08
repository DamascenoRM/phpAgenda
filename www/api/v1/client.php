<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');
require_once(ROOT_PATH . '/bin/database.php');
require_once(ROOT_PATH . '/bin/client.php');

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
$client = new Client();

// Rotas para a API REST
switch ($method) {
    case 'GET':
        // Obtendo todos os usuários
        if (isset($_GET['id'])) {
            $clientId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($clientId === false || $clientId === null) {
                sendResponse(400, array("message" => "Invalid ID"));
                return;
            }
            $result = $client->getClientById($clientId);
            if ($result === null || $result == false) {
                sendResponse(404, array("message" => "Client Not Found"));
            } else {
                sendResponse(200, $result);
            }
        } else {
            sendResponse(200, $client->getAllClients());
        }
        break;

    case 'POST':
        // Criando um usuário
        $postData = json_decode(file_get_contents('php://input'), true);
        if (!isset($_POST['person_id'], $_POST['phone'])) {
            sendResponse(400, array("message" => "Missing parameters"));
        }

        $result = $client->createClient($_POST['person_id'], $_POST['phone']);
        if ($result) {
            $clientdata = $client->getClientById($result); // Assume-se que o método createPerson retorne o ID da nova pessoa
            sendResponse(201, array("message" => "Client created successfully", "data" => $clientdata));
        } else {
            sendResponse(500, array("message" => "Failed to create client"));
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
        #print_r ("$_PUT -> $putData");
        if (!isset($_PUT['person_id'], $_PUT['phone'])) {
            sendResponse(422, array("message" => "person_id, clientname and email parameters are required"));
            return;
        }

        $clientphone = strval(filter_var($_PUT['phone'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $person_id = filter_var($_PUT['person_id'], FILTER_VALIDATE_INT);
        if ($clientphone === false || $person_id === false || $person_id === null) {
            sendResponse(422, array("message" => "Invalid person_id or phone parameter"));
            return;
        }

        $result = $client->updateClientById($id, $person_id, $clientphone);
        if ($result) {
            $clientdata = $client->getClientById($id);
            sendResponse(200, array("message" => "Client updated successfully", "data" => $clientdata));
        } else {
            sendResponse(500, array("message" => "Failed to update client"));
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
            $clientdata = $client->getClientById($id); // Assume-se que o método createPerson retorne o ID da nova pessoa
            $result = $client->deleteClientById($id);
            if ($result) {
                sendResponse(200, array("message" => "Client deleted successfully", "data" => $clientdata));
            } else {
                sendResponse(500, array("message" => "Failed to delete client"));
            }
            break;
}
?>
