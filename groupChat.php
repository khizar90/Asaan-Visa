<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\GroupControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$chat = new GroupControler($conn1);

if(isset($_POST['form_id']) && isset($_POST['to_group_id']) && isset($_POST['type']) && isset($_POST['message']) || isset($_FILES['media'])){
    if($_POST['type']=='text'){
        $result = $chat->message($_POST['form_id'],$_POST['to_group_id'],$_POST['type'],$_POST['message'],'');
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
    if($_POST['type']=='image'){
        $result = $chat->message($_POST['form_id'],$_POST['to_group_id'],$_POST['type'],$_POST['message'],$_FILES['media']);
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
    if($_POST['type']=='audio'){
        $result = $chat->message($_POST['form_id'],$_POST['to_group_id'],$_POST['type'],$_POST['message'],$_FILES['media']);
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
}



