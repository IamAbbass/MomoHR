<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
    //alibaba start
    require_once('../class_function/alibaba_cloud/autoload.php');
    use OSS\OssClient;
    use OSS\Core\OssException;
    //alibaba end
    
    $id         = $_REQUEST['profile_id'];
    $global_id  = $_REQUEST['global_id'];
    
    $arr = array();
    
    
    //same
    if($global_id == 4 || $global_id == 5){//login id
        $id = $global_id;//profile
    }
    
    
 
    if($_REQUEST['edit_profile']== 'true' || $_REQUEST['edit_profile'] == 'true'){
        
            
        $key = $_REQUEST['key'];
        $value = $_REQUEST['value'];
      
        if($key == 'fathers_name'){
            $value =$value;
        }       
        else if ($key == 'mothers_name'){
            $value = $value ;
        }
        else if ($key == 'email'){
            $value = $value ;
        }
       // else if ($key == 'password'){
       //     $value = $value ;
       // }
        else if ($key == 'birth_place'){
            $value = $value ;
        }
        else if ($key == 'gender'){
            $value = $value ;
        }
        else if ($key == 'dob'){
            $value = strtotime($value) ;
        }
        else if ($key == 'nationlaity'){
            $value = $value ;
        }
        else if ($key == 'nrc'){
            $value = $value ;
        }
        else if ($key == 'religion'){
            $value = $value ;
        }
        else if ($key == 'address'){
            $value = $value ;
        }
        else if ($key == 'city'){
            $value = $value ;
        }
        else if ($key == 'state'){
            $value = $value ;
        }
        else if ($key == 'work_phone'){
            $value = $value ;
        }
        else if ($key == 'personal_phone'){
            $value = $value ;
        }
        else if ($key == 'education'){
            $value = $value ;
        }
        else if ($key == 'job_description'){
            $value = $value ;
        }
   //       print_r($_POST['edit_profile']);
    //die();
    
    
    //print_r($_FILES['file']['name']);
   // die();
        

        
         else if($_REQUEST['profile_pic'] == 'true'){
            
          
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $object = "attachments/".$_FILES['file']['name'];
         
           
            try{
                $result = $ossClient->uploadFile($bucket, $object, $_FILES['file']['tmp_name']);
    
                $date_time      = time($result['date']);
                $url            = $result['info']['url'];
                $size_upload    = $result['info']['size_upload'];
                $primary_ip     = $result['info']['primary_ip'];
                $content_type   = $result['oss-requestheaders']['Content-Type'];
    
                sql($DBH, "insert into tbl_alibaba
                (admin_id,url,content_type,size_upload,primary_ip,date_time)
                values (?,?,?,?,?,?);",
                array($SESS_ID,$url,$content_type,$size_upload,$primary_ip,$date_time), "rows");
    
                $arr['error']   = "";
                $arr['url']     = $url;
                $arr['size']    = $size_upload;
                    
                   
                  $rowss = sql($DBH, "UPDATE tbl_login set photo = ? where id = ?", array($url,$id),"count"); 
                  $array = array();
                //echo $rowss;
                if($rowss == "1"){
                     $array['success'] = true;
                     $array['msg'] = "Video successfully updated!";
                }
                else{
                    $array['error'] = false;
                    $array['msg'] = "Please try again!";
                }
            
                
               die(json_encode($array));
    
            } catch(OssException $e) {
                $arr['error']   = print_r($e->getMessage(),true);
                $arr['url']     = $url;
                die(json_encode($arr));
            }
           
           
        }
        
        if($key == "email"){
            if(strlen($value) > 0){
        		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where email = ? and id != ?");
        		$result 	   	= $STH->execute(array($value,$id));
        		$count_email	= $STH->fetchColumn();
            }else{
                $count_email    = 0; //to clear the check
            }
                
            if($count_email == 1){
                $arr['success']     = false;
                $arr['msg']         = "Email already exists!";
                $arr['key']         = $key;
                $rows = sql($DBH, "SELECT email FROM tbl_login where id = ?", array($id), "rows");
                foreach($rows as $row){            
                    $arr['old_val']        = $row['email'];
                }   
                
                                
                die(json_encode($arr));
            }else{
                $row = sql($DBH, "UPDATE tbl_login set $key = ? where id = ?", array($value,$id),"count");
                $rows = sql($DBH, "UPDATE tbl_employee_profile set $key = ? where employee_id = ?", array($value,$id), "count");   
                if($rows == "0" || $row == "0" || $rows == "0"){
                    $arr['success'] = true;
                    $arr['msg'] = "Profile successfully updated!";
                }else if($rows == "1" || $row == "1" || $rows == "1"){
                    $arr['success'] = true;
                    $arr['msg'] = "Profile successfully updated!";
                }else{            
                    $arr['success'] = false;
                    $arr['msg'] = "Please try again!";
                }
                die(json_encode($arr));
            }
            
        }else{
            $row = sql($DBH, "UPDATE tbl_login set $key = ? where id = ?", array($value,$id),"count");
            $rows = sql($DBH, "UPDATE tbl_employee_profile set $key = ? where employee_id = ?", array($value,$id), "count");   
            if($rows == "0" || $row == "0" || $rows == "0"){
                $arr['success'] = true;
                $arr['msg'] = "Profile successfully updated!";
            }else if($rows == "1" || $row == "1" || $rows == "1"){
                $arr['success'] = true;
                $arr['msg'] = "Profile successfully updated!";
            }else{            
                $arr['success'] = false;
                $arr['msg'] = "Please try again!";
            }
            die(json_encode($arr));
        }                
    }else{
        $rows = sql($DBH, "SELECT access_level FROM tbl_login where id = ?", array($global_id), "rows");
        foreach($rows as $row){
            $access_level = $row['access_level'];
        }
        $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($id), "rows");
        foreach($rows as $row){
            
            $arr['name']        = $row['fullname'];
            
            $ext = strtolower(substr($row['photo'],strrpos($row['photo'],".")+1,strlen($row['photo'])));            
            
            if(in_array($ext,$image_ext)){
                $arr['dp_type']  = "image";
                $arr['photo']    = $row['photo'];                
            }else if(in_array($ext,$video_ext)){ 
                $arr['dp_type']  = "video";
                $arr['photo']    = $row['photo'].$video_thumb_100;
            }
            
            $arr['contact']     = $row['contact'];
            $arr['hired']       = my_simple_date($row['date_time']);
            $arr['birthday']    = my_simple_date($row['dob']);
            
            // editable
            $arr['email']          = $row['email'];
            $arr['address']        = $row['address'];
            $arr['city']           = $row['city'];
            $arr['country']        = $row['country'];
            //editabe
            
            
            
            
            
            $count_signup = sql($DBH, "SELECT * FROM tbl_login_log WHERE login_id = ?", array($id), "rows");
                   
            if(count($count_signup) > 0){
                if(time()-$row['update_at'] > 60){
                    $status = "inactive";
                    
                    if(ceil((time()-$row['update_at'])/60) <= 59){
                        $arr['last_active'] = "Active ".ceil((time()-$row['update_at'])/60)." mins ago";
                    }else{
                        $arr['last_active'] = "Active ".my_simple_date($row['update_at']);
                    }  
                }else{
                    $status = "active";
                    $arr['last_active'] = "Active now";     
                }
            }else{
                $status = "signout";
                $arr['last_active'] = "Signout";     
            }
            
                  
            
            $arr["login_status"]     = $status;
        
        
        }
		$rows = sql($DBH, "SELECT * FROM tbl_employee_profile where employee_id = ?", array($id), "rows");
        foreach($rows as $row){
            
            //editable
            if($_REQUEST['own'] == "true"){          
                $arr['gender']                      = $row['gender'];
                $arr['nationality']                 = $row['nationality'];
                $arr['nrc']                         = $row['nrc'];
                $arr['religion']                    = $row['religion'];
                $arr['fathers_name']                = $row['fathers_name'];
                $arr['mothers_name']                = $row['mothers_name'];
                $arr['address']                     = $row['address'];
                $arr['city']                        = $row['city'];
                $arr['state']                       = $row['state'];
                $arr['work_phone']                  = $row['work_phone'];
                $arr['personal_phone']              = $row['personal_phone'];
                $arr['employment_start_date']       = $row['employment_start_date'];
                $arr['education']                   = $row['education'];
                $arr['birth_place']                 = $row['birth_place'];
            }
            //editable       
            

			$arr['job_description']        = $row['job_description'];
			if($access_level == "admin"){
				$arr['salary']       = "$".$row['basic_salary'];
				$arr['nrc']          = $row['nrc'];
			}
        }

		if($access_level == "admin"){
			$rows = sql($DBH, "SELECT sum(amount) as advance FROM tbl_advance_salary where employee_id = ?", array($id), "rows");
    	    foreach($rows as $row){
		      $arr['advance']        = "$".$row['advance'];
    	    }
            
            //$arr['late_days']   = "3 Late"; //only admin
		}
        
        
        $arr['google_map']    = "false";
        if($access_level == "admin" || $_REQUEST['own'] == "true"){   
            
            
            $rows 		= sql($DBH, "SELECT * FROM tbl_locations where employee_id = ? order by (id) DESC limit 1", array($id), "rows");
	        foreach($rows as $row){
	          $polygon_json		= $row['data'];              
              $polygon_json 	= json_decode($polygon_json, true);
              
              $latitude     = $polygon_json['coords']['latitude'];
              $longitude    = $polygon_json['coords']['longitude'];
              $accuracy     = $polygon_json['coords']['accuracy'];
              
              $accuracy = mt_rand(1, 2);
              
              
              $arr['location']    = array($latitude,$longitude,$accuracy); //only admin
              $arr['google_map']    = "true";
              
    	    }
        }
        
        
        
        

		

		

		/*
		Status: (Check In, Vacation, Leave of Absence)
		Check In: It will show a click icon with the time of check in next to it (admin)
		Vacation: This will show a vacation icon
		Leave of Absence: This will show a sick icon
		*/


        die(json_encode($arr));       
    }
    

?>
