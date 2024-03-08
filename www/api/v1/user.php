<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');
require_once(ROOT_PATH . '/bin/database.php');
require_once(ROOT_PATH . '/bin/user.php');

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
$user = new User();

// Rotas para a API REST
switch ($method) {
    case 'GET':
        // Obtendo todos os usuários
        if (isset($_GET['id'])) {
            $userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if ($userId === false || $userId === null) {
                sendResponse(400, array("message" => "Invalid ID"));
                return;
            }
            $result = $user->getUserById($userId);
            if ($result === null || $result == false) {
                sendResponse(404, array("message" => "User Not Found"));
            } else {
                sendResponse(200, $result);
            }
        } else {
            sendResponse(200, $user->getAllUsers());
        }
        break;

    case 'POST':
        // Criando um usuário
        $postData = json_decode(file_get_contents('php://input'), true);
        if (!isset($_POST['person_id'], $_POST['username'], $_POST['email'])) {
            sendResponse(400, array("message" => "Missing parameters"));
        }

        $result = $user->createUser($_POST['person_id'], $_POST['username'], $_POST['email']);
        if ($result) {
            $userdata = $user->getUserById($result); // Assume-se que o método createPerson retorne o ID da nova pessoa
            sendResponse(201, array("message" => "User created successfully", "data" => $userdata));
        } else {
            sendResponse(500, array("message" => "Failed to create user"));
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
        if (!isset($_PUT['person_id'], $_PUT['username'], $_PUT['email'])) {
            sendResponse(422, array("message" => "person_id, username and email parameters are required"));
            return;
        }

        $username = strval(filter_var($_PUT['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $email = strval(filter_var($_PUT['email'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $person_id = filter_var($_PUT['person_id'], FILTER_VALIDATE_INT);
        if ($username === false || $email === false || $person_id === false || $person_id === null) {
            sendResponse(422, array("message" => "Invalid person_id, username and email parameter"));
            return;
        }

        $result = $user->updateUserById($id, $person_id, $username, $email);
        if ($result) {
            $userdata = $user->getUserById($id);
            sendResponse(200, array("message" => "User updated successfully", "data" => $userdata));
        } else {
            sendResponse(500, array("message" => "Failed to update user"));
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
            $userdata = $user->getUserById($id); // Assume-se que o método createPerson retorne o ID da nova pessoa
            $result = $user->deleteUserById($id);
            if ($result) {
                sendResponse(200, array("message" => "User deleted successfully", "data" => $userdata));
            } else {
                sendResponse(500, array("message" => "Failed to delete user"));
            }
            break;
}
?>
