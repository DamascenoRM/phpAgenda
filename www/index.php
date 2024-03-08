<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
require_once(ROOT_PATH . '/config.php');
require_once(ROOT_PATH . '/bin/database.php');


require("/root/.composer/vendor/autoload.php");

$openapi = \OpenApi\Generator::scan([ROOT_PATH]);

header('Content-Type: application/x-yaml');
echo $openapi->toYaml();