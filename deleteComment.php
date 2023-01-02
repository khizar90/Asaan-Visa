<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$deletecomment = new PostControler($conn1);
if(isset($_GET['id'])){
    $result = $deletecomment->deleteComment($_GET['id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["Comment deleted"],
            "data" => [],
            "error" => []
        ));  
    }
}