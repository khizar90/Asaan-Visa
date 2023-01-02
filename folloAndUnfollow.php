<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$follow = new UserController($conn1);
if(isset($_GET['sender']) && isset($_GET['reciever'])){
    $result = $follow->followUser($_GET['sender'],$_GET['reciever']);
    if($result==1){
        echo json_encode(array(
            "status" => true,
            "action" => "You follow",
            "data" => [],
            "error" => []
        ));   
    }else
    echo json_encode(array(
        "status" => true,
        "action" => "You unfollow",
        "data" => [],
        "error" => []
    ));   
}
