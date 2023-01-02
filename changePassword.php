<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$change = new UserController($conn1);
if(@$_POST['phone'] && @$_POST['oldpassword'] && @$_POST['newpassword'] && @$_POST['confirmpassword']){
    $result= $change->changePassword($_POST['phone'],md5($_POST['oldpassword']),md5($_POST['newpassword']),md5($_POST['confirmpassword']));
    if($result==1){
        echo json_encode(array(
            "status" => true,
            "action" => "Password change",
            "data" => [],
            "error" => []
        ));
    }
    if($result==0){
        echo json_encode(array(
            "status" => false,
            "action" => "Password not change",
            "data" => [],
            "error" => ["password not match"]
        ));
    }
    if($result==2){
        echo json_encode(array(
            "status" => false,
            "action" => "Password not change",
            "data" => [],
            "error" => ["old password is incorrect"]
        ));
    }
}
else{
    echo json_encode(array(
        "status" => false,
        "action" => "invalid",
        "data" => [],
        "error" => ["pleaase fill all field"]
    ));
}