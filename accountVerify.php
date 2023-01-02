<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$reset = new UserController($conn1);
if(@$_POST['AccountVerifyotp']){
    $result = $reset->accountVerifyOtp($_POST['AccountVerifyotp']);
    if($result == 1){
        echo json_encode(array(
            "status" => true,
            "action" => "otp verified",
            "data" => [],
            "error"=> []
        ));
    }else{
        echo json_encode(array(
            "status" => false,
            "action" => "otp verifiaction failed",
            "data" => [],
            "error"=> ["wrong otp"]
        ));
    }

}