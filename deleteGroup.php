<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POSt');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$deleteGroup = new GroupControler($conn1);
if(isset($_POST['user_id']) && isset($_POST['group_id'])){
    $result = $deleteGroup->deleteGroup($_POST['user_id'],$_POST['group_id']);
    if($result==true){
        echo json_encode(array(
            "status" => true,
            "action" => "Group deleted",
            "data" => [],
            "error" => []
        ));   
    }
    else{
        echo json_encode(array(
            "status" => false,
            "action" => "Group not deleted",
            "data" => [],
            "error" => ["You are not admin"]
        ));
    }
}