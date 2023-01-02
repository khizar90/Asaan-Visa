<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Orgin: *');
header('Access-Control-Allow-Methods: GET');

require 'function\Connection.php';
require  'function\PostControler.php';
$conn = new Connection();
$conn1 = $conn->get();
$likeDislike = new PostControler($conn1);

if(isset($_GET['user_id']) && isset($_GET['post_id']) && isset($_GET['type'])){
    $result = $likeDislike->likeDislike($_GET['user_id'],$_GET['post_id'],$_GET['type']);
    if($_GET['type']=='like')
    {
        if($result==1){
            echo json_encode(array(
                "status" => true,
                "action" => "You like this post",
                "data" => [],
                "error" => []
            ));
        }
        if($result==2){
            echo json_encode(array(
                "status" => true,
                "action" => "You unlike this post",
                "data" => [],
                "error" => []
            ));
        }
        if($result==3){
            echo json_encode(array(
                "status" => true,
                "action" => "You like this post",
                "data" => [],
                "error" => []
            ));
        }   
    }
    if($_GET['type']=='dislike')
    {
        if($result==4){
            echo json_encode(array(
                "status" => true,
                "action" => "You dislike the post",
                "data" => [],
                "error" => []
            ));
        }
        if($result==5){
            echo json_encode(array(
                "status" => true,
                "action" => "You remove dislike from the post ",
                "data" => [],
                "error" => []
            ));
        } 
        if($result==6){
            echo json_encode(array(
                "status" => true,
                "action" => "You dislike  the post ",
                "data" => [],
                "error" => []
            ));
        }   
    }
}