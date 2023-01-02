<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$reset = new UserController($conn1);
if(@$_POST['phone']){
    $result = $reset->verifyPhone($_POST['phone']);
    if($result==1){
        echo json_encode(array(
            "status" => true,
            "action" => "Please enter reset pin",
            "data" => ['otp'=>123456],
            "error"=> []
        ));
    }else{
        echo json_encode(array(
            "status" => false,
            "action" => "Recover password failed",
            "data" => [],
            "error"=> ["phone not register"]
        ));
    }
}