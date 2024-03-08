<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');
require_once(ROOT_PATH . '/bin/database.php');

$connection = new MySQLConnection();
if ($connection) {
    echo "Conexão com o MySQL estabelecida com sucesso!<br>";

    // Exemplo de consulta de teste
    $sql = "SELECT * FROM sua_tabela LIMIT 1";
    $result = $connection->executeQuery($sql);

    // Verifique se a consulta foi bem-sucedida
    if ($result) {
        echo "Consulta executada com sucesso!<br>";
        $row = $result->fetch(PDO::FETCH_ASSOC);
        var_dump($row); // Exibir os resultados da consulta
    } else {
        echo "Erro ao executar consulta.";
    }
} else {
    echo "Erro ao estabelecer conexão com o MySQL.";
}


echo phpinfo();
?>