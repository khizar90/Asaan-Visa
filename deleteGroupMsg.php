<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$delmsg = new GroupControler($conn1);
if(isset($_GET['user_id']) && isset($_GET['message_id'])){
    $result = $delmsg->deleteMessage($_GET['user_id'],$_GET['message_id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => "Message deleted",
            "data" => [],
            "error" => []
        ));   
    }else{
        echo json_encode(array(
            "status" => false,
            "action" => "You can't deleted message",
            "data" => [],
            "error" => []
        ));
    }
}