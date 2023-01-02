<?php
class UserController{
    public $otp = 123456;
    public $verifyOtp =123456;
	private $conn;

	public function __construct($conn){
		$this->conn = $conn;
	}


    //////////       VRIFY PHONE    /////////////////// 

    public function phone($phone){

        $dublicate = "SELECT * FROM assanvisa WHERE phone='$phone'";
        $sql = $this->conn->prepare($dublicate);
        $sql->execute();
        if($sql->fetchColumn()>0){
            return true;
        }
    }



    ///////////////   VERIFY OTP    //////////////////
    
    public function otpverified($otpp){
        if($otpp == $this->otp){
            return 1;
        }
    }


    //////////    REGISTER ACCOUNT    /////////////////// 

    public function registration($phone,$full_name, $password){

        $query ="INSERT INTO assanvisa VALUES ('','$full_name','$phone','$password')";
        $result = $this->conn->prepare($query);
        $result->execute();
        
        return true;
    }


    //////////       LOGIN    /////////////////// 


    public function login($phone,$password){
        $sql = $this->conn->prepare("SELECT * FROM `assanvisa` WHERE phone='$phone'");
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        if($sql->fetchColumn()==0){
            if($password == $result['password']){
                    $obj= new stdClass();
                    $obj->id=$result['id'];
                    $obj->name=$result['full_name'];
                    $obj->phone=$result['phone'];

                return $obj;
            }else{
                return 10;
            }
        }
        else{
            return  100;
        }
    }
    public function idUser(){
        return $this->id;
    }

    
    //////////       CHANGE PASSOWRD    /////////////////// 

    public function changePassword($phone,$oldPassword,$newPassword,$confirmPassword){
        $sql="SELECT  `password` FROM `assanvisa` WHERE phone='$phone'";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC); 
        if($row['password'] == $oldPassword){
            if($newPassword == $confirmPassword){
                $sql1="UPDATE `assanvisa` SET `password`='$newPassword' WHERE phone='$phone'";
                $result = $this->conn->prepare($sql1);
                $result->execute();
                return 1;
            }else{
                return 0;
            }
        }
        else{
            return 2;
        }
        
    }


    //////////       DELETE ACCOUNT    /////////////////// 

    public function deleteAccount($phone,$password,$confirmPassword){
        $sql="SELECT  `password` FROM `assanvisa` WHERE phone='$phone'";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $row = $result->fetch(PDO::FETCH_ASSOC);
        if($row['password']==$password && $row['password']==$confirmPassword){
                $sql1="DELETE FROM `assanvisa` WHERE phone='$phone'";
                $result = $this->conn->prepare($sql1);
                $result->execute();
                return 1;
        }else{
            return 2;
        }
        
    }


    //////////      PHONE VERIFY FOR RESET PASSWORD     /////////////////// 

    public function verifyPhone($phone){
        $sql = "SELECT * FROM assanvisa WHERE phone='$phone'";
        $result = $this->conn->prepare($sql);
        $result->execute();
        if($result->fetchColumn()>0){
            return 1;
        }else{
            return 2;
        }
    }


    //////////       VRIFY OTP FOR RESET PASSWORD    /////////////////// 

    public function accountVerifyOtp($accountVerifyOtp){
        if($accountVerifyOtp == $this->verifyOtp){
            return 1;  
        }else{
            return 0;
        }
    }

    //////////      PASSWORD RESET    /////////////////// 

    public function newPassword($phone,$newPassword,$confirmPassword){
        $sql = "SELECT * FROM assanvisa WHERE phone='$phone'";
        $result = $this->conn->prepare($sql);
        $result->execute();
        if($result->fetchColumn()>0){
            if($newPassword == $confirmPassword){
                $sql1="UPDATE `assanvisa` SET `password`='$newPassword' WHERE phone='$phone'";
                $result = $this->conn->prepare($sql1);
                $result->execute();
                return 1;
            }else{
                return 0;
            }   
        }else{
            return 2;
        }
    }

        //////////       USER LIST    /////////////////// 

        public function userList($id){
            $sql= "SELECT `id`, `full_name`, `phone` FROM `assanvisa` WHERE id!=$id";
            $result = $this->conn->prepare($sql);
            $result->execute();
            $obj= new stdClass();

                while($row = $result->fetch(PDO::FETCH_ASSOC)){
                    $cehckid=$row['id']; 
                    $sqli1 ="SELECT * FROM `followusers` WHERE sender_id=$id and reciever_id=$cehckid";
                    $result1 = $this->conn->prepare($sqli1);
                    $result1->execute();
                    if($result1->fetchcolumn()>0){
                        $row['follow']= true;
                        $name[]=$row;
                        $obj->user=$name;
                    }else{
                        $row['follow']= false;
                        $name[]=$row;
                        $obj->user=$name;
                    }                                          
                 
                 
            }
            return $obj;
            
        }
    
    
        //////////       FOLLOW USER    /////////////////// 
    
        public function followUser($sender,$reciever){
            $sql="SELECT * FROM followusers where sender_id=$sender AND reciever_id=$reciever";
            $result = $this->conn->prepare($sql);
            $result->execute();
            if($result->fetchColumn()>0){
                $query ="DELETE FROM followusers where sender_id=$sender AND reciever_id=$reciever";
                $result1 = $this->conn->prepare($query);
                $result1->execute();
                return 0;
            }else{
                $query="INSERT INTO `followusers`(`sender_id`, `reciever_id`) VALUES ($sender,$reciever)";
                $result1 = $this->conn->prepare($query);
                $result1->execute();
                return 1;
            }
            
        }


    //////////       FOLLOWING LIST    /////////////////// 

    public function followingList($id){
        $sql ="SELECT `reciever_id` FROM `followusers` WHERE sender_id=$id";
        $result = $this->conn->prepare($sql);
        $result->execute();

            while($row= $result->fetch(PDO::FETCH_ASSOC)){
                $follwoingId = $row['reciever_id'];
                $sql1="SELECT `id`, `full_name`, `phone` FROM `assanvisa` WHERE id=$follwoingId";
                $result1 = $this->conn->prepare($sql1);
                $result1->execute();
                $row1=$result1->fetch(PDO::FETCH_ASSOC);
                print_r($row1);
                $obj[]=$row1; 
        
           
        } return $obj;
        
    }

    //////////       FOLLOWER LIST    /////////////////// 

    public function followerList($id){
        $sql ="SELECT `sender_id` FROM `followusers` WHERE reciever_id=$id";
        $result = $this->conn->prepare($sql);
        $result->execute();
            while($row= $result->fetch(PDO::FETCH_ASSOC)){
                $follwoingId = $row['sender_id'];
                $sql1="SELECT `id`, `full_name`, `phone` FROM `assanvisa` WHERE id=$follwoingId";
                $result1 = $this->conn->prepare($sql1);
                $result1->execute();
                $row1=$result1->fetch(PDO::FETCH_ASSOC);
                $obj[]=$row1; 
            
        }
        return $obj;
        
    }


    //////////       BLOCK USER    /////////////////// 

    public function blockUser($user_id,$blocke_user_id){
        $sql = "SELECT * FROM `blockuser` WHERE user_id=$user_id and blocked_user_id=$blocke_user_id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        if($result->fetchColumn()>0){
            $sql1="DELETE FROM `blockuser` WHERE user_id=$user_id and blocked_user_id=$blocke_user_id";
            $result = $this->conn->prepare($sql1);
            $result->execute();
            return 0;
        }
        else{
            $sql1="INSERT INTO `blockuser`(`user_id`, `blocked_user_id`) VALUES ($user_id,$blocke_user_id)";
            $result = $this->conn->prepare($sql1);
            $result->execute();
            return 1;
        }
    }

    //////////       LIST USER WITHOUT BLOCKED    /////////////////// 

    public function listUsersWithoutBLockUsers($id){
        $sql="SELECT `id`, `full_name`, `phone` FROM `assanvisa` WHERE id!=$id";
        $result = $this->conn->prepare($sql);
        $result->execute();
            $obj= new stdClass();
            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $checkId=$row['id'];
                $sqli="SELECT * FROM `blockuser` WHERE user_id=$id and blocked_user_id=$checkId  or user_id=$checkId and blocked_user_id=$id";
                $result1 = $this->conn->prepare($sqli);
                $result1->execute();
                if($result1->fetchColumn()==0){
                    $arr1[]=$row;
                    $obj->users=$arr1;
                }
            
            
        }
        return $obj;
    }

    //////////       LIST OF BLOCK USER    /////////////////// 

    public function blockList($id){
        $sql="SELECT   `blocked_user_id` FROM `blockuser` WHERE user_id=$id";
        $result = $this->conn->prepare($sql);
        $result->execute();

            while($row = $result->fetch(PDO::FETCH_ASSOC)){
                $checkId=$row['blocked_user_id'];
                $sqli="SELECT `id`, `full_name`, `phone` FROM `assanvisa` WHERE id=$checkId";
                $result1 = $this->conn->prepare($sqli);
                $result1->execute();
                $row1 = $result1->fetch(PDO::FETCH_ASSOC);
                $obj= new stdClass();
                $arr[]=$row1;
                $obj->users=$arr; 
            
            
           
        }
        return $obj;
    }

    //////////       SEND MESSAGE    /////////////////// 

    public function message($from_id,$to_id,$type,$message,$media){
        
        $sql1 = "SELECT * FROM `chat` WHERE form_user_id =$to_id and to_user_id=$from_id";
        $result1 = $this->conn->prepare($sql1);
        $result1->execute();

        if($result1->fetchColumn()>0){
            $sql2 = "UPDATE `chat` SET `read`=1 WHERE form_user_id =$to_id and to_user_id=$from_id";
            $result2 = $this->conn->prepare($sql2);
            $result2->execute(); 
        }

        $read = 0;
        if($type=='text'){
            $sql = "INSERT INTO `chat`(`form_user_id`, `to_user_id`,`type`, `message`,`media`,`read`) VALUES ($from_id,$to_id,'$type','$message','',$read)";
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
                    
                        $sql = "INSERT INTO `chat`(`form_user_id`, `to_user_id`,`type`, `message`,`media`,`read`) VALUES ($from_id,$to_id,'$type','$message','$new_img_name',$read)";
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
                    $sql = "INSERT INTO `chat`(`form_user_id`, `to_user_id`,`type`, `message`,`media`,`read`) VALUES ($from_id,$to_id,'$type','$message','$new_audio_name',$read)";
                    $result = $this->conn->prepare($sql);
                    $result->execute();

                }
            }
        }
        if($type=='images'){
            $targetDir = "chat/"; 
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
                $sql = "INSERT INTO `chat`(`form_user_id`, `to_user_id`,`type`, `message`,`media`,`read`) VALUES ($from_id,$to_id,'$type','$message','$data',$read)";
                $result = $this->conn->prepare($sql);
                $result->execute(); 
            }
        }
    }

    //////////       DELETE MESSAGE    /////////////////// 

    public function deleteMessage($id){
        $sql="DELETE FROM `chat` WHERE id=$id";
        $result = $this->conn->prepare($sql);
        $result->execute();
        return true;
    }

    //////////       INBOX   ///////////////////

    public function inbox($id){
        $limit = 1000;
        $sql = "SELECT DISTINCT `to_user_id` FROM `chat` WHERE form_user_id= $id  ORDER BY id DESC LIMIT $limit";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();


        while($row =$result->fetch(PDO::FETCH_ASSOC)){

            $checkId = $row['to_user_id'];

            $sql1 = "SELECT `full_name` FROM `assanvisa` WHERE id=$checkId";
            $result1 = $this->conn->prepare($sql1);
            $result1->execute();
            $row2 =$result1->fetch(PDO::FETCH_ASSOC);


            $sql2 = "SELECT `message`,`media`,`id`,`type`,`read` FROM `chat` WHERE form_user_id=$id AND to_user_id=$checkId or form_user_id=$checkId AND to_user_id=$id ORDER BY id DESC LIMIT 1";
            $result2 = $this->conn->prepare($sql2);
            $result2->execute();
            $row3 = $result2->fetch(PDO::FETCH_ASSOC);

            $iddd = $row3['id'];

            $sql3 = "SELECT `form_user_id`,`to_user_id` FROM `chat` WHERE id=$iddd";
            $result3 = $this->conn->prepare($sql3);
            $result3->execute();
            $row4 =$result3->fetch(PDO::FETCH_ASSOC);

                $row2['form_user_id'] = $row4['form_user_id'];
                $row2['to_user_id']= $row4['to_user_id'];
                $row2['message']=$row3['message'];
                $row2['media']=$row3['media'];
                $row2['type']=$row3['type'];
                $row2['read']=$row3['read'];
                $arr[] = $row2;
                $obj->inbox=$arr;

        }
        return $obj;
        
    }

    //////////       CONVERSATION    ///////////////////

    public function conversation($from_id,$to_id){
        
        $sql = "SELECT `id`,`message`,`media`,`read` FROM `chat` WHERE form_user_id=$from_id AND to_user_id=$to_id or form_user_id=$to_id AND to_user_id=$from_id ORDER BY id DESC";
        $result = $this->conn->prepare($sql);
        $result->execute();
        $obj =  new stdClass();
        while($row =$result->fetch(PDO::FETCH_ASSOC)){
            $arr[] = $row;
            $obj->chat=$arr;
            
        }
        return $obj;
    }   
}





