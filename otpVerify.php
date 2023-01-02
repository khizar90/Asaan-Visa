<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$otp = new UserController($conn1);

if(@$_POST['phone'] && @$_POST['otpp']) {
    $result = $otp->otpverified($_POST['otpp']);
    if($result == 1){
        echo json_encode(array(
            "status" => true,
            "action" => "OTP verified",
            "data" => [],
            "error"=> []
            
        ));
    }else{
        echo json_encode(array(
            "status" => false,
            "action" => "OTP not verified",
            "data" => [],
            "error"=> ["Please enter correct OTP"]
            
        ));
       
    }
}else{
    echo json_encode(array(
        "status" => false,
        "action" => "Otp not verified",
        "data" => [],
        "error"=> ["please enter OTP and number"]
        
    ));
}
?>