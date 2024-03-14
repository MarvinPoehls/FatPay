<?php

use Src\Config;
use Src\Database;
use Src\Controller\TransactionController;
use Src\TransactionGateway;

require "vendor/autoload.php";

set_error_handler("\Src\ErrorHandler::handleError");
set_exception_handler("\Src\ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$config = new Config();

$database = new Database($config::HOST, $config::USER, $config::PASSWORD, $config::DATABASE);

$gateway = new TransactionGateway($database);

$controller = new TransactionController($gateway);

$controller->processRequest();