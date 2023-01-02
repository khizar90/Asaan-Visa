<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POSt');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$read = new GroupControler($conn1);
if(isset($_POST['user_id'])  && isset($_POST['msg_id']) && isset($_POST['read_by_user_id']) ){
    $result = $read->read($_POST['user_id'],$_POST['msg_id'],$_POST['read_by_user_id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => "Data Inserted",
            "data" => [],
            "error" => []
        ));   
    }
}