<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$listUser = new UserController($conn1);
if(isset($_GET['id'])){
    $result = $listUser->listUsersWithoutBLockUsers($_GET['id']);
    if($result){
        echo json_encode(array(
            "status" => true,
            "action" => ["Users"],
            "data" => [$result],
            "error" => []
        ));
    }
}