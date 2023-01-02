<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$comment = new PostControler($conn1);
if(isset($_GET['id']) && isset($_GET['comment']) ){
    $result = $comment->editComment($_GET['id'],$_GET['comment']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["Comment updated"],
            "data" => [],
            "error" => []
        ));  
    }
}