<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$inbox = new GroupControler($conn1);
if(isset($_GET['user_id'])){
    $result = $inbox->groupInboxList($_GET['user_id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => "Group",
            "data" => [$result],
            "error" => []
        ));   
    }
}