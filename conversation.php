<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$conversation = new UserController($conn1);
if(isset($_GET['from_id']) && isset($_GET['to_id'])){
     $result = $conversation->conversation($_GET['from_id'],$_GET['to_id']);     
     if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["conversation"],
            "data" => [$result],
            "error" => []
        ));  
     }   
    
}
