<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$following = new UserController($conn1);
if(isset($_GET['id'])){
    $result = $following->followingList($_GET['id']);
    echo json_encode(array(
        "status" => true,
        "action" => "following",
        "data" => [$result],
        "error" => []
    ));   
}