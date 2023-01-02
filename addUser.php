<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$add = new GroupControler($conn1);
if(isset($_POST['admin_id'])  && isset($_POST['user_id']) && isset($_POST['group_id'])){
    $result = $add->addUser($_POST['admin_id'],$_POST['user_id'],$_POST['group_id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["Memeber added"],
            "data" => [],
            "error" => []
        ));
    }
    else{
        echo json_encode(array(
            "status" => false,
            "action" => ["Memeber not added"],
            "data" => [],
            "error" => ["You are not admin"]
        ));
    }
}