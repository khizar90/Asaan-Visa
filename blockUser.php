<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$blockUser = new UserController($conn1);
if(isset($_GET['login_id']) && isset($_GET['block_user_id'])){
    $result= $blockUser->blockUser($_GET['login_id'],$_GET['block_user_id']);
        if($result==1){
            echo json_encode(array(
                "status" => true,
                "action" => "Block User",
                "data" => [],
                "error" => []
            ));
        }
        if($result==0){
            echo json_encode(array(
                "status" => true,
                "action" => "User unblock ",
                "data" => [],
                "error" => []
            ));
        
    } 
}