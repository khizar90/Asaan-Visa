<?php

class PostControler{
    private $conn;
    public function __construct($conn){
		$this->conn = $conn;
	}

     //////////       ADD POST    /////////////////// 

     public function addPost($user_id,$post,$type,$title,$description){
        if($type=='image'){
            $img_name = $post['name'];
            $img_size = $post['size'];
            $tmp_name = $post['tmp_name'];
            $error = $post['error'];
            if($error === 0) {
                if ($img_size > 2000000) {
                    echo json_encode(array(
                        "status" => false,
                        "action" => "Post not uploaded",
                        "data" => [],
                        "error" => ["this file is to large"]
                    ));
                }else{
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                    $img_ex_lc = strtolower($img_ex);
    
                    $allowed_exs = array("jpg", "jpeg", "png"); 
    
                    if(in_array($img_ex_lc, $allowed_exs)) {
                        $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                        $img_upload_path = 'uploads/'.$new_img_name;
    
                        move_uploaded_file($tmp_name, $img_upload_path);
    
                        // Insert into Database
                    
                        $sql = "INSERT INTO `posts`(`user_id`, `type`, `media`,`title`,`description`) VALUES ($user_id,'image','$new_img_name','$title','$description')";
                        $result= $this->conn->prepare($sql);
                        $result->execute();
                        return true;
                    }
                    else{
                        return false;
                    }
                }
            }
            
        }
        if($type=='audio'){
                $audio_name = $post['name'];
                $tmp_name = $post['tmp_name'];
                $error = $post['error'];
                if($error === 0) {
                    $audio_ex = pathinfo($audio_name, PATHINFO_EXTENSION);
                    $audio_ex_lc = strtolower($audio_ex);
                    $allowed_exs = array('3gp', 'aa', 'aac', 'aax','act','mp3','m4a','m4b','m4p','wav','rf64');

                    if (in_array($audio_ex_lc, $allowed_exs)) {
                        $new_audio_name = uniqid("audio-", true). '.'.$audio_ex_lc;
                        $audio_upload_path = 'uploads/'.$new_audio_name;
                        move_uploaded_file($tmp_name, $audio_upload_path);
                        // Now let's Insert the auido path into database
                        $sql = "INSERT INTO `posts`(`user_id`, `type`, `media`,`title`,`description`) VALUES ($user_id,'audio','$new_audio_name','$title','$description')";
                        $result= $this->conn->prepare($sql);
                        $result->execute();
                        return true;
                    }else{
                        return false;       
                   }
               
            
            }
        }
        if($type=='images'){
                $targetDir = "uploads/"; 
                $fileName =$_FILES['media']['name'];
                $newfileName = serialize($fileName);
            
                if(!empty($newfileName)){ 
                    $arr =[];
                    foreach($_FILES['media']['name'] as $key=>$val){
                        $targetPath = $targetDir .$val;
                        move_uploaded_file($_FILES['media']['tmp_name'][$key],$targetPath);
                        $arr[] = $_FILES['media']['name'][$key];
                    }
                    $data = json_encode($arr);
                    $sql= "INSERT INTO `posts`(`user_id`, `type`, `media`,`title`,`description`) VALUES ($user_id,'Multi images','$data','$title','$description')";
                    $result= $this->conn->prepare($sql);
                    $result->execute();
                    return true;
                }
                else{
                    return false;
                }
            
            
        }
    }




    //////////       RECENT POST    /////////////////// 

    public function showPost(){
        $sql="SELECT * FROM `posts` ORDER BY post_id DESC";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj = new stdClass();
    
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $user_id = $row['user_id'];
                $sql1= "SELECT `id`, `full_name`, `phone` FROM `assanvisa` WHERE id=$user_id";
                $result1 = $this->conn->prepare($sql1);
                $result1->execute();
                    $row1 = $result1->fetch(PDO::FETCH_ASSOC);
                    $row['user']=$row1;
                    $arr[]=$row;
                    $obj->Post=$arr;
                }
                 return $obj;
    }

    //////////       LIKE AND DISLIKE POSTS    /////////////////// 

    public function likeDislike($login_id,$post_id,$type){
        if($type=='like'){
            $sql="SELECT `type` FROM `likeordislike` WHERE post_id=$post_id and user_id=$login_id";
            $result = $this->conn->prepare($sql);
            $result->execute();
            
            if($result->fetchColumn()){
                $row = $result->fetch(PDO::FETCH_ASSOC);
                if($row['type']=='dislike'){
                    $sqli="UPDATE `likeordislike` SET `type`='like' WHERE post_id=$post_id and user_id=$login_id";
                    $result1 = $this->conn->prepare($sqli);
                    $result1->execute();
                    return 1;
                }else{
                    $sqli="DELETE FROM `likeordislike` WHERE post_id=$post_id and user_id=$login_id";
                    $result1 = $this->conn->prepare($sqli);
                    $result1->execute();
                    return 2;
                }
                
            }else{
                $sqli="INSERT INTO `likeordislike`(`type`, `post_id`, `user_id`) VALUES ('$type',$post_id,$login_id)";
                $result1 = $this->conn->prepare($sqli);
                $result1->execute();
                return 3;
            }
        }
        if($type=='dislike'){
            $sql="SELECT `type` FROM `likeordislike` WHERE post_id=$post_id and user_id=$login_id";
            $result = $this->conn->prepare($sql);
            $result->execute();
            
            if($result->fetchColumn()){
                $row1 = $result->fetch(PDO::FETCH_ASSOC);
                if($row1['type']=='like'){
                    $sqli="UPDATE `likeordislike` SET `type`='dislike' WHERE post_id=$post_id and user_id=$login_id";
                    $result = $this->conn->prepare($sqli);
                    $result->execute(); 
                    return 4;
                }else{
                    $sqli="DELETE FROM `likeordislike` WHERE post_id=$post_id and user_id=$login_id";
                    $result = $this->conn->prepare($sqli);
                    $result->execute();
                    return 5;
                }
                
            }else{
                $sqli="INSERT INTO `likeordislike`(`type`, `post_id`, `user_id`) VALUES ('$type',$post_id,$login_id)";
                $result = $this->conn->prepare($sqli);
                $result->execute();
                return 6;
            }
        }
    }


    //////////       COMMENT ON POST    /////////////////// 

    public function comment($login_id,$post_id,$comment){
        $sql="INSERT INTO `comment`(`user_id`, `post_id`, `comment`) VALUES ($login_id,$post_id,'$comment')";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

    //////////       EDIT COMMENT    /////////////////// 

    public function editComment($comment_id,$comment){
        $sql="UPDATE `comment` SET `comment`='$comment' WHERE id=$comment_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }


    //////////       DELETE COMMENT    /////////////////// 

    public function deleteComment($comment_id){
        $sql="DELETE FROM `comment` WHERE id=$comment_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

    //////////       DETAIL OF POST    /////////////////// 

    public function detailPost($post_id){

        $sql1="SELECT * FROM `posts` WHERE post_id=$post_id";
        $result = $this->conn->prepare($sql1);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);

        $sql="SELECT * FROM `likeordislike` WHERE post_id=$post_id and type='like'";
        $result1 = $this->conn->prepare($sql);
        $result1->execute();

        $like =$result1->rowCount();
       

        $sql2="SELECT * FROM `likeordislike` WHERE post_id=$post_id and type='dislike'";
        $result2 = $this->conn->prepare($sql2);
        $result2->execute();
        $dislike = $result2->rowCount();
        

        $sql3="SELECT * FROM `comment` WHERE post_id=$post_id";
        $result3= $this->conn->prepare($sql3);
        $result3->execute();
        $comment =$result3->rowCount();

        while($row1 = $result3->fetch(PDO::FETCH_ASSOC)){
            $comnt[]= $row1;
        }
        $obj= new stdClass();
        $row['likes']= $like;
        $row['dislikes']= $dislike;
        $row['comment']= $comment;
        $row['list']= $comnt;
        $obj->post=$row;
        return $obj;

        
    }
    
}