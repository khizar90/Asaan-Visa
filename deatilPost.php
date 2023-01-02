<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$detailPost = new PostControler($conn1);
if(isset($_GET['post_id'])){
    $result = $detailPost->detailPost($_GET['post_id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["Post Detail"],
            "data" => [$result],
            "error" => []
        ));  
    }
}