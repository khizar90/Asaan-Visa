<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$createGroup = new GroupControler($conn1);
if(isset($_POST['user_id']) && isset($_POST['group_name'])){
    $result = $createGroup->createGroup($_POST['user_id'],$_POST['group_name']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["Group created"],
            "data" => [],
            "error" => []
        ));
    }
}