<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$comment = new PostControler($conn1);
if(isset($_GET['user_id']) && isset($_GET['post_id']) && isset($_GET['comment'])){
    $result = $comment->comment($_GET['user_id'],$_GET['post_id'],$_GET['comment']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["You comment on this post"],
            "data" => [],
            "error" => []
        ));  
    }
}else{
    echo json_encode(array(
        "status" => false,
        "action" => [],
        "data" => [],
        "error" => []
    ));  
}