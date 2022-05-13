<?php
ob_start();
session_start();
define('ABSPATH','https://'.$_SERVER['HTTP_HOST']);
define('ABSDIR',dirname(__FILE__));
$is_ajax = 'xmlhttprequest' == strtolower( isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] :  '' );
if(!$is_ajax) include 'template/header.php';

$controller = 'user';
if(isset($_SESSION["userSessionData"])){
    $controller = "index";
}

// Todo esta lógica hara el papel de un FrontController
if(!isset($_REQUEST['c']))
{
    require_once "controllers/{$controller}_controller.php";
    $controller = ucwords($controller) . 'Controller';
    $controller = new $controller;
    $controller->index();
}
else
{
    // Obtenemos el controlador que queremos cargar
    $controller = "user";
    $action = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'index';
    $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : false;

    if(isset($_SESSION["userSessionData"])){
        $controller = strtolower($_REQUEST['c']);
    }

    // Instanciamos el controlador
    require_once "controllers/{$controller}_controller.php";
    $controller = ucwords($controller) . 'Controller';


    // Llama la accion
    if (method_exists($controller, $action)
        && is_callable(array($controller, $action))) {
        $controller = new $controller;
        if($id) {
            $controller->{$action}($id);
        }else {
            $controller->{$action}();
        }
    }else{
        require_once "controllers/index_controller.php";
        $controller = new IndexController();
        $controller->notFound();
    }
}
if(!$is_ajax) include 'template/footer.php';
ob_end_flush();