<?php

include "autoload.php";

session_start();

saveRequestParamsToSession(['redirect', 'cancelRedirect', 'id']);

$controllerName = "Checkout";
 if (isset($_REQUEST['controller'])) {
     $controllerName = $_REQUEST['controller'];
 }

handleLogin($controllerName);

$controllerName .= "Controller";
$controller = new $controllerName();

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    if (method_exists($controller, $action)) {
        $controller->$action();
    }
}

 try {
     $controller->render();
 } catch (Exception $exception) {
     $errorMessage = $exception->getMessage();
     include "views/error.php";
     exit;
 }

 $view = $controller->getView();
 $viewPath = "views/".$view.".php";

 if (!file_exists($viewPath)) {
     $errorMessage = "Template not found!";
     include "views/error.php";
     exit;
 }

 include "views/header.php";
 include $viewPath;
 include "views/footer.php";

 function handleLogin($controller)
 {
     $maxLoggedInTimeInSeconds = 300;

     if (!isset($_SESSION["lastLogin"])) {
         $_SESSION["lastLogin"] = time() - $maxLoggedInTimeInSeconds;
     }

     $timeSinceLastLoginInSeconds = time() - $_SESSION["lastLogin"];

     if (($timeSinceLastLoginInSeconds > $maxLoggedInTimeInSeconds || !isset($_SESSION["lastLogin"])) && $controller != "login") {
        header("Location: ".$_SERVER['PHP_SELF']."?controller=login");
         exit;
     }
 }

 function saveRequestParamsToSession($params)
 {
     foreach ($params as $param) {
         if (isset($_REQUEST[$param])) {
             $_SESSION[$param] = $_REQUEST[$param];
         }
     }
 }