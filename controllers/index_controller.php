<?php
include_once './models/User.php';
include_once './models/News.php';

class IndexController
{
    private $model;
    private $news;

    public function __construct(){
        $this->model = new User();
        $this->news = new News();
    }

    public function index(){
        $title = "";
        $press = "";
        $date = "";
        $errorMessage = "";
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST["title"];
            $press = $_POST["press"];
            $date = $_POST["date"];
            if(empty($title) || empty($press) || empty($date)){
                $errorMessage = "ERROR: FALTAN DATOS";
            }else {
                $file = "/uploads/" .basename($_FILES['file']['name']);
                if (!move_uploaded_file($_FILES['file']['tmp_name'], ABSDIR . $file)) {
                    $errorMessage = "ERROR: ARCHIVO NO VÁLIDO";
                }
                $this->news->store(['title' => $title, 'press' => $press, 'date' => $date, 'file' => $file]);
                header("Location: ".ABSPATH);
            }
        }
        $users = $this->model->getAll();
        $news = $this->news->getAll();
        require_once './views/index/index.phtml';
    }
    public function notFound(){
        require_once './views/index/404.phtml';
    }
    public function news($id){
        $new = null;
        if(!empty($id)){
            $new = $this->news->getOne($id);
        }
        header('Content-Type: application/json');
        echo json_encode($new);
    }

}