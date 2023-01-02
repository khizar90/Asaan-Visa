<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$reset = new UserController($conn1);
if(@$_POST['phone'] && @$_POST['newpassword'] && @$_POST['confirmpassword']){
    $result = $reset->newPassword($_POST['phone'],md5($_POST['newpassword']),md5($_POST['confirmpassword']));
    if($result==1){
        echo json_encode(array(
            "status" => true,
            "action" => "Password changed",
            "data" => [],
            "error" => []
        ));
    }
    if($result==0){
        echo json_encode(array(
            "status" => false,
            "action" => "Error updating password",
            "data" => [],
            "error" => ["Password not match"]
        ));
    }    
}