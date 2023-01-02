<<<<<<< HEAD
<?php

class GroupControler{
    private $conn;
    public function __construct($conn){
		$this->conn = $conn;
	}

    //////////       CERATE GROUP    /////////////////// 

    public function createGroup($login_id,$groupName){
        $sql="INSERT INTO `groups`(`id`, `group_name`, `admin_user_id`) VALUES ('','$groupName',$login_id)";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

     //////////       DELETE GROUP    /////////////////// 

    public function deleteGroup($user_id,$group_id){
        $sql="SELECT  `admin_user_id` FROM `groups` WHERE id=$group_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row['admin_user_id']==$user_id){
            $sql1="DELETE FROM `groups` WHERE id=$group_id";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            return true;
        }
        else{
            return false;
        }
    }

     //////////       ADD MEMBER     /////////////////// 

    public function addUser($admin_id,$user_id,$group_id){
       $sql = "SELECT  `admin_user_id` FROM `groups` WHERE id=$group_id";
       $result = $this->conn->prepare($sql);
       $result->execute();
       $row = $result->fetch(PDO::FETCH_ASSOC);
       if($row['admin_user_id'] == $admin_id){
        $sql1 = "INSERT INTO `group_member`(`id`, `user_id`, `group_id`) VALUES ('',$user_id,$group_id)";
        $result1 = $this->conn->prepare($sql1);
        $result1->execute(); 
        return true;
       }
            
    }
    

    //////////       SEND MESSAGE    /////////////////// 

    public function message($from_id,$to_group_id,$type,$message,$media){

        if($type=='text'){
            $sql = "INSERT INTO grouup_chat (`id`, `from_user_id`, `group_id`, `type`, `message`, `media`) VALUES ('',$from_id,$to_group_id,'$type','$message','$media')";
            $result = $this->conn->prepare($sql);
            $result->execute();
        }
        if($type=='image'){
            $img_name = $media['name'];
            $img_size = $media['size'];
            $tmp_name = $media['tmp_name'];
            $error = $media['error'];
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
                        $img_upload_path = 'chat/'.$new_img_name;
    
                        move_uploaded_file($tmp_name, $img_upload_path);
    
                        // Insert into Database
                    
                        $sql = "INSERT INTO grouup_chat (`id`, `from_user_id`, `group_id`, `type`, `message`, `media`) VALUES ('',$from_id,$to_group_id,'$type','$message','$new_img_name')";
                        $result = $this->conn->prepare($sql);
                        $result->execute();
                    }
                }
            }           
        }
        if($type=='audio'){
            $audio_name = $media['name'];
            $tmp_name = $media['tmp_name'];
            $error = $media['error'];
            if($error === 0) {
                $audio_ex = pathinfo($audio_name, PATHINFO_EXTENSION);
                $audio_ex_lc = strtolower($audio_ex);
                $allowed_exs = array('3gp', 'aa', 'aac', 'aax','act','mp3','m4a','m4b','m4p','wav','rf64');

                if (in_array($audio_ex_lc, $allowed_exs)) {
                    $new_audio_name = uniqid("audio-", true). '.'.$audio_ex_lc;
                    $audio_upload_path = 'chat/'.$new_audio_name;
                    move_uploaded_file($tmp_name, $audio_upload_path);
                    // Now let's Insert the auido path into database
                    $sql = "INSERT INTO grouup_chat (`id`, `from_user_id`, `group_id`, `type`, `message`, `media`) VALUES ('',$from_id,$to_group_id,'$type','$message','$new_audio_name')";
                    $result = $this->conn->prepare($sql);
                    $result->execute();
                }
            }
        }
    }

     //////////       DELETE GROUP MESSAGE    /////////////////// 

    public function deleteMessage($user_id,$msg_id){
        $sql= "DELETE FROM `grouup_chat` WHERE id=$msg_id and from_user_id=$user_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

     //////////       GROUP LIST    /////////////////// 

    public function groupList($user_id){
        $sql = "SELECT `id`, `group_name` FROM `groups` WHERE admin_user_id=$user_id ORDER BY id DESC";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while($row = $result->fetch(PDO::FETCH_ASSOC)){            
            $arr[]= $row;
            $obj->group=$arr;
        }
        return $obj;

    }

    //////////       GROUP CONVERSATION    /////////////////// 

    public function groupConversation($from_id,$group_id){

        $sql = "SELECT `id`,`message`, `media`,`from_user_id` FROM `grouup_chat` WHERE group_id=$group_id ORDER by id DESC";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while($row =$result->fetch(PDO::FETCH_ASSOC)){
            $checkid = $row['from_user_id'];
            $msg_id = $row['id'];
            $sql1 = "SELECT  `full_name` FROM `assanvisa` WHERE id = $checkid";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            $row1 = $result1->fetch(PDO::FETCH_ASSOC);
            $sql2 = "SELECT read_by_user_id FROM `read` WHERE msg_id = $msg_id  order by id desc";
            $result2 = $this->conn->prepare($sql2);
            $result2->execute();
            
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            $idddd = $row2['read_by_user_id'];
            $sql3 = "SELECT `id`, `full_name` FROM `assanvisa` WHERE id=$idddd";
            $result3 = $this->conn->prepare($sql3);
            $result3->execute();
            $row3 = $result3->fetch(PDO::FETCH_ASSOC);
            
            if($row3 == null){
                $row3 = array();
                $row['full_name'] = $row1['full_name'];
                $row['reeady by'] = $row3;
                $arr[] = $row;
                $obj=$arr;
            }else{
                $row['full_name'] = $row1['full_name'];
                $row['reeady by'] = $row3;
                $arr[] = $row;
                $obj=$arr;
            }
        }
        return $obj;
        
    }

    //////////       GROUP INBOX LIST    /////////////////// 

    public function groupInboxList($user_id){
        $sql = "SELECT DISTINCT group_id FROM `grouup_chat` WHERE from_user_id =$user_id ORDER BY id DESC limit 1000";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while( $row = $result->fetch(PDO::FETCH_ASSOC)){
            $checkid = $row['group_id'];

            $sql1 = "SELECT  `group_name` FROM `groups` WHERE id=$checkid";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            $row1 = $result1->fetch(PDO::FETCH_ASSOC);

            $sql2 = "SELECT `message`, `media`,`id`,`type` FROM `grouup_chat` WHERE group_id= $checkid ORDER BY id DESC LIMIT 1";
            $result2 = $this->conn->prepare($sql2);
            $result2->execute();
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            $idd = $row2['id'];

            $sql3 = "SELECT `from_user_id` FROM `grouup_chat` WHERE id= $idd";
            $result3 = $this->conn->prepare($sql3);
            $result3->execute();
            $row3 = $result3->fetch(PDO::FETCH_ASSOC);



            $row1['group_id'] = $checkid;
            $row1['from_user_id'] = $row3['from_user_id'];
            $row1['message'] = $row2['message'];
            $row1['media'] = $row2['media'];
            $row1['type'] = $row2['type'];
            $arr[] = $row1;
            $obj->group = $arr;


        }
        return $obj;
    }

    //////////       READ GROUP MESSAGE   /////////////////// 

    public function read($from_user_id,$msg_id,$read_by_user_id){
        $sql1 = "SELECT * FROM `read` WHERE msg_id = $msg_id";
        $result1 = $this->conn->prepare($sql1);
        $result1->execute();

        $sql = "INSERT INTO `read`(`id`, `msg_id`, `from_user_id`, `read_by_user_id`) VALUES ('',$msg_id,$from_user_id,$read_by_user_id)";
        $result = $this->conn->prepare($sql);
        $result->execute();


        return true;
    }

=======
<?php

class GroupControler{
    private $conn;
    public function __construct($conn){
		$this->conn = $conn;
	}

    //////////       CERATE GROUP    /////////////////// 

    public function createGroup($login_id,$groupName){
        $sql="INSERT INTO `groups`(`id`, `group_name`, `admin_user_id`) VALUES ('','$groupName',$login_id)";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

     //////////       DELETE GROUP    /////////////////// 

    public function deleteGroup($user_id,$group_id){
        $sql="SELECT  `admin_user_id` FROM `groups` WHERE id=$group_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row['admin_user_id']==$user_id){
            $sql1="DELETE FROM `groups` WHERE id=$group_id";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            return true;
        }
        else{
            return false;
        }
    }

     //////////       ADD MEMBER     /////////////////// 

    public function addUser($admin_id,$user_id,$group_id){
       $sql = "SELECT  `admin_user_id` FROM `groups` WHERE id=$group_id";
       $result = $this->conn->prepare($sql);
       $result->execute();
       $row = $result->fetch(PDO::FETCH_ASSOC);
       if($row['admin_user_id'] == $admin_id){
        $sql1 = "INSERT INTO `group_member`(`id`, `user_id`, `group_id`) VALUES ('',$user_id,$group_id)";
        $result1 = $this->conn->prepare($sql1);
        $result1->execute(); 
        return true;
       }
            
    }
    

    //////////       SEND MESSAGE    /////////////////// 

    public function message($from_id,$to_group_id,$type,$message,$media){

        if($type=='text'){
            $sql = "INSERT INTO grouup_chat (`id`, `from_user_id`, `group_id`, `type`, `message`, `media`) VALUES ('',$from_id,$to_group_id,'$type','$message','$media')";
            $result = $this->conn->prepare($sql);
            $result->execute();
        }
        if($type=='image'){
            $img_name = $media['name'];
            $img_size = $media['size'];
            $tmp_name = $media['tmp_name'];
            $error = $media['error'];
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
                        $img_upload_path = 'chat/'.$new_img_name;
    
                        move_uploaded_file($tmp_name, $img_upload_path);
    
                        // Insert into Database
                    
                        $sql = "INSERT INTO grouup_chat (`id`, `from_user_id`, `group_id`, `type`, `message`, `media`) VALUES ('',$from_id,$to_group_id,'$type','$message','$new_img_name')";
                        $result = $this->conn->prepare($sql);
                        $result->execute();
                    }
                }
            }           
        }
        if($type=='audio'){
            $audio_name = $media['name'];
            $tmp_name = $media['tmp_name'];
            $error = $media['error'];
            if($error === 0) {
                $audio_ex = pathinfo($audio_name, PATHINFO_EXTENSION);
                $audio_ex_lc = strtolower($audio_ex);
                $allowed_exs = array('3gp', 'aa', 'aac', 'aax','act','mp3','m4a','m4b','m4p','wav','rf64');

                if (in_array($audio_ex_lc, $allowed_exs)) {
                    $new_audio_name = uniqid("audio-", true). '.'.$audio_ex_lc;
                    $audio_upload_path = 'chat/'.$new_audio_name;
                    move_uploaded_file($tmp_name, $audio_upload_path);
                    // Now let's Insert the auido path into database
                    $sql = "INSERT INTO grouup_chat (`id`, `from_user_id`, `group_id`, `type`, `message`, `media`) VALUES ('',$from_id,$to_group_id,'$type','$message','$new_audio_name')";
                    $result = $this->conn->prepare($sql);
                    $result->execute();
                }
            }
        }
    }

     //////////       DELETE GROUP MESSAGE    /////////////////// 

    public function deleteMessage($user_id,$msg_id){
        $sql= "DELETE FROM `grouup_chat` WHERE id=$msg_id and from_user_id=$user_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

     //////////       GROUP LIST    /////////////////// 

    public function groupList($user_id){
        $sql = "SELECT `id`, `group_name` FROM `groups` WHERE admin_user_id=$user_id ORDER BY id DESC";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while($row = $result->fetch(PDO::FETCH_ASSOC)){            
            $arr[]= $row;
            $obj->group=$arr;
        }
        return $obj;

    }

    //////////       GROUP CONVERSATION    /////////////////// 

    public function groupConversation($from_id,$group_id){

        $sql = "SELECT `id`,`message`, `media`,`from_user_id` FROM `grouup_chat` WHERE group_id=$group_id ORDER by id DESC";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while($row =$result->fetch(PDO::FETCH_ASSOC)){
            $checkid = $row['from_user_id'];
            $msg_id = $row['id'];
            $sql1 = "SELECT  `full_name` FROM `assanvisa` WHERE id = $checkid";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            $row1 = $result1->fetch(PDO::FETCH_ASSOC);
            $sql2 = "SELECT read_by_user_id FROM `read` WHERE msg_id = $msg_id  order by id desc";
            $result2 = $this->conn->prepare($sql2);
            $result2->execute();
            
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            $idddd = $row2['read_by_user_id'];
            $sql3 = "SELECT `id`, `full_name` FROM `assanvisa` WHERE id=$idddd";
            $result3 = $this->conn->prepare($sql3);
            $result3->execute();
            $row3 = $result3->fetch(PDO::FETCH_ASSOC);
            
            if($row3 == null){
                $row3 = array();
                $row['full_name'] = $row1['full_name'];
                $row['reeady by'] = $row3;
                $arr[] = $row;
                $obj=$arr;
            }else{
                $row['full_name'] = $row1['full_name'];
                $row['reeady by'] = $row3;
                $arr[] = $row;
                $obj=$arr;
            }
        }
        return $obj;
        
    }

    //////////       GROUP INBOX LIST    /////////////////// 

    public function groupInboxList($user_id){
        $sql = "SELECT DISTINCT group_id FROM `grouup_chat` WHERE from_user_id =$user_id ORDER BY id DESC limit 1000";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while( $row = $result->fetch(PDO::FETCH_ASSOC)){
            $checkid = $row['group_id'];

            $sql1 = "SELECT  `group_name` FROM `groups` WHERE id=$checkid";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            $row1 = $result1->fetch(PDO::FETCH_ASSOC);

            $sql2 = "SELECT `message`, `media`,`id`,`type` FROM `grouup_chat` WHERE group_id= $checkid ORDER BY id DESC LIMIT 1";
            $result2 = $this->conn->prepare($sql2);
            $result2->execute();
            $row2 = $result2->fetch(PDO::FETCH_ASSOC);
            $idd = $row2['id'];

            $sql3 = "SELECT `from_user_id` FROM `grouup_chat` WHERE id= $idd";
            $result3 = $this->conn->prepare($sql3);
            $result3->execute();
            $row3 = $result3->fetch(PDO::FETCH_ASSOC);



            $row1['group_id'] = $checkid;
            $row1['from_user_id'] = $row3['from_user_id'];
            $row1['message'] = $row2['message'];
            $row1['media'] = $row2['media'];
            $row1['type'] = $row2['type'];
            $arr[] = $row1;
            $obj->group = $arr;


        }
        return $obj;
    }

    //////////       READ GROUP MESSAGE   /////////////////// 

    public function read($from_user_id,$msg_id,$read_by_user_id){
        $sql1 = "SELECT * FROM `read` WHERE msg_id = $msg_id";
        $result1 = $this->conn->prepare($sql1);
        $result1->execute();

        $sql = "INSERT INTO `read`(`id`, `msg_id`, `from_user_id`, `read_by_user_id`) VALUES ('',$msg_id,$from_user_id,$read_by_user_id)";
        $result = $this->conn->prepare($sql);
        $result->execute();


        return true;
    }

>>>>>>> 5f41641773000299fc57585eabf1d7a9dee626f1
}