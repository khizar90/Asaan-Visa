<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\UserControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$message = new UserController($conn1);

if(isset($_POST['form_id']) && isset($_POST['to_id']) && isset($_POST['type']) && isset($_POST['message']) || isset($_FILES['media'])){
    if($_POST['type']=='text'){
        $result = $message->message($_POST['form_id'],$_POST['to_id'],$_POST['type'],$_POST['message'],'');
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
    if($_POST['type']=='image'){
        $result = $message->message($_POST['form_id'],$_POST['to_id'],$_POST['type'],$_POST['message'],$_FILES['media']);
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
    if($_POST['type']=='audio'){
        $result = $message->message($_POST['form_id'],$_POST['to_id'],$_POST['type'],$_POST['message'],$_FILES['media']);
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
    if($_POST['type']=='images'){
        $result = $message->message($_POST['form_id'],$_POST['to_id'],$_POST['type'],$_POST['message'],$_FILES['media']);
        echo json_encode(array(
            "status" => true,
            "action" => "Message send",
            "data" => [],
            "error" => []
        ));
    }
}



