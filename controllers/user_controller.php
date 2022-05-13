<?php
include './models/User.php';

class UserController
{
    private $model;

    public function __construct(){
        $this->model = new User();
    }

    public function index(){
        $email = "";
        $password = "";
        $errorMessage = "";
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            if(empty($email) || empty($password)){
                $errorMessage = "ERROR: FALTAN DATOS";
            }else {
                $userData = $this->model->getOne($email,"*","email", "password = '{$password}'");
                if (!$userData) {
                    $errorMessage = "ERROR: COMBINACIÓN ERRÓNEA DE USUARIO/CONTRASEÑA";
                }else if($userData->activated === "0"){
                    $errorMessage = "ERROR: USUARIO NO ACTIVADO";
                }else{
                    $_SESSION["userSessionData"] = $userData;
                    header("Location: ".ABSPATH);
                }
            }
        }
        require_once './views/user/index.phtml';
    }

    public function register(){
        $name = "";
        $email = "";
        $password = "";
        $errorMessage = "";
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            if(empty($name) || empty($email) || empty($password)){
                $errorMessage = "ERROR: FALTAN DATOS";
            }else {
                $userData = $this->model->getOne($email, "*","email");
                if ($userData) {
                    $errorMessage = "ERROR: ESTE CORREO '{$email}' YA SE ENCUENTRA REGISTRADO";
                }else{
                    $this->model->store(['name' => $name, 'email' => $email, 'password' => $password]);
                    header("Location: ".ABSPATH. "/user/register");
                }
            }
        }
        require_once './views/user/register.phtml';
    }

    public function enable($id){
        $user = null;
        $message = "";
        if(!empty($id)){
            $action = $_POST['action'];
            $user = $this->model->getOne($id);
            if($user){
                $this->model->update($id,['activated'=>$action]);
                $act = $action == "1" ? "Aceptado" : "Rechazado";
                $message = "Usuario ha sido {$act} con éxito";
            }else{
                $message = "Usuario no Existe";
            }
        }
        header('Content-Type: application/json');
        echo "{\"message\":\"{$message}\"}";
    }

    public function logout(){
        unset($_SESSION["userSessionData"]);
        header("Location: ".ABSPATH);
    }
}