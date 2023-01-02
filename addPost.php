<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: POST');
require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$post = new PostControler($conn1);
if (isset($_POST['id']) && isset($_FILES['media']) && isset($_POST['type']) && isset($_POST['title']) && isset($_POST['description'])){
    if($_POST['type']=='image'){
        $result= $post->addPost(($_POST['id']),$_FILES['media'],$_POST['type'],$_POST['title'],$_POST['description']);
        if($result){
            echo json_encode(array(
            "status" => true,
            "action" => "Post uploaded",
            "data" => ['type'=>'Image','media'=>[$_FILES['media']],'user_id' =>$_POST['id']],
            "error"=> []
            ));
        }
    }
    if($_POST['type']=='audio'){
        $result= $post->addPost(isset($_POST['id']),$_FILES['media'],$_POST['type'],$_POST['title'],$_POST['description']);
        if($result){
            echo json_encode(array(
            "status" => true,
            "action" => "Post uploaded",
            "data" => ['type'=>'Audio','media'=>[$_FILES['media']],'user_id' =>1],
            "error"=> []
            
        ));
    }
    }
    if($_POST['type']=='images'){
        $result= $post->addPost(isset($_POST['id']),$_FILES['media'],$_POST['type'],$_POST['title'],$_POST['description']);
        if($result){
            echo json_encode(array(
            "status" => true,
            "action" => "Post uploaded",
            "data" => ['type'=>'Multi images','media'=>[$_FILES['media']],'user_id' =>1],
            "error"=> []
            
        ));
    }
    }
}