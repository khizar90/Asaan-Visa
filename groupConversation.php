<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$conversation = new GroupControler($conn1);
if(isset($_GET['user_id']) && isset($_GET['group_id'])){
    $result = $conversation->groupConversation($_GET['user_id'],$_GET['group_id']);
    if($result==true){
        echo json_encode(array(
            "status" => true,
            "action" => "Group chat",
            "data" => [$result],
            "error" => []
        ));   
    }
}