<?php

use FatPayApi\Config;
use FatPayApi\Database;
use FatPayApi\Controller\TransactionController;
use FatPayApi\TransactionGateway;

require "vendor/autoload.php";

set_error_handler("\FatPayApi\ErrorHandler::handleError");
set_exception_handler("\FatPayApi\ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$config = new Config();

$database = new Database($config::HOST, $config::USER, $config::PASSWORD, $config::DATABASE);

$gateway = new TransactionGateway($database);

$controller = new TransactionController($gateway);

$controller->processRequest();