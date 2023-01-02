<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$delmsg = new UserController($conn1);
if(isset($_GET['message_id'])){
    $result = $delmsg->deleteMessage($_GET['message_id']);
    if($result==true){
        echo json_encode(array(
            "status" => true,
            "action" => "Message deleted",
            "data" => [],
            "error" => []
        ));   
    }
}