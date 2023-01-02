<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$userList = new UserController($conn1);
if(isset($_GET['id'])){
    $result= $userList->userList($_GET['id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => "All users",
            "data" => [$result],
            "error" => []
        ));
    
    }else{
        echo json_encode(array(
            "status" => false,
            "action" => "No  user available ",
            "data" => [],
            "error" => []
        ));  
    }
}else{
    echo json_encode(array(
        "status" => false,
        "action" => "No  user available ",
        "data" => [],
        "error" => ["yor are not login"]
    ));  
}