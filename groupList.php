<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POSt');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$list = new GroupControler($conn1);
if(isset($_POST['id'])){
    $result = $list->groupList($_POST['id']);
    if($result==true){
        echo json_encode(array(
            "status" => true,
            "action" => "Group List",
            "data" => [$result],
            "error" => []
        ));   
    }
}