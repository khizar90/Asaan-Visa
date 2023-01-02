<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');

require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$phone = new UserController($conn1);
$otp=123456;
if (@$_POST['phone']){
    $result = $phone->phone($_POST['phone']);
    if($result){
        echo json_encode(array(
            "status" => false,
            "action" => "Phone already exists",
            "data" => [],
            "error"=> ["phone alreday exist"]
        ));
    }
    else{
        echo json_encode(array(
            "status" => true,
            "action" => "Phone verified",
            "data" => ['otp'=>$otp],
            "error"=> []
        ));
    }
}else{
    echo json_encode(array(
        "status" => false,
        "action" => "Validation errors",
        "data" => [],
        "error"=> ["please provide phone number"]
    ));
}



?>