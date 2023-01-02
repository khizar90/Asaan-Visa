<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$post = new PostControler($conn1);
$result = $post->showPost();
echo json_encode(array(
    "status" => true,
    "action" => ["Recent post"],
    "data" => [$result],
    "error" => []
));  
