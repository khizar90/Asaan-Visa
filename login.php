<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$login = new UserController($conn1);
if (isset($_POST['phone']) && isset($_POST['password'])){
    $result = $login->login($_POST['phone'],md5($_POST['password']));
    if($result == 10){
        echo json_encode(array(
            "status" => false,
            "action" => "Login failed",
            "data" => [],
            "error" => ["invalid passowrd"]
        ));
        
    }elseif($result==100){
        echo json_encode(array(
            "status" => false,
            "action" => "Login failed",
            "data" => [],
            "error" => ["Username not Register"]
        ));
    }elseif($result){
        echo json_encode(array(
            "status" => true,
            "action" => "Successfully Login!",
            "data" => [$result],
            "error" => []
        ));
           
    }
}else{
    echo json_encode(array(
        "status" => false,
        "action" => "Login failed",
        "data" => [],
        "error" => ["please provide phone and password"]
    ));
}

?>