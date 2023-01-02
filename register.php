<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$insertt = new UserController($conn1);
if( @$_POST['phone'] && @$_POST['full_name'] && @$_POST['password']){
    $result = $insertt->registration($_POST['phone'],$_POST['full_name'],md5($_POST['password']));
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => "Registered Successfully",
            "data" => [],
            "error"=> []
        ));
    }
}
else{
    echo json_encode(array(
        "status" => false,
        "action" => "Registeration failed",
        "data" => [],
        "error"=> ["Please provide name and password"]
    ));
}
?>