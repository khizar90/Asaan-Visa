<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$delete = new UserController($conn1);
if(@$_POST['phone'] && @$_POST['password'] && @$_POST['confirmpassword']){
    $result = $delete->deleteAccount($_POST['phone'],md5($_POST['password']),md5($_POST['confirmpassword']));
    if($result==1){
        echo json_encode(array(
            "status" => true,
            "action" => "Account Deleted",
            "data" => [],
            "error" => []
        ));  
    }
    if($result==2){
        echo json_encode(array(
            "status" => false,
            "action" => "Account not Deleted",
            "data" => [],
            "error" => ["Incorect password"]
        ));  
    }
}