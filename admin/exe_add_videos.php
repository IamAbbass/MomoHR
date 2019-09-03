<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
    
    
    $access_denied_xml        = $xml->exe_add_videos->access_denied;
    $no_access_xml            = $xml->exe_add_videos->no_access;
    $success_xml              = $xml->exe_add_videos->success;
    $colon_xml                = $xml->exe_add_videos->colon;
    $added_video_xml          = $xml->exe_add_videos->added_video;
    $sorry_but_xml            = $xml->exe_add_videos->sorry_but;
    $please_try_later_xml     = $xml->exe_add_videos->please_try_later;
    

	//die(json_encode($_REQUEST));
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
		if($_POST['pid']){
			$SESS_ID = $_POST['pid'];
		}		
	}else if($SESS_ACCESS_LEVEL == "admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}
	
	try { 

             if(file_exists($_FILES["file"]["tmp_name"])){
              $file_name      = $_FILES["file"]["name"];
              $file_tmp_name  = $_FILES["file"]["tmp_name"];
              $file_type      = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
              if($file_type == "mp4" || $file_type == "mp3" || $file_type == "wma"){        
                  $rand_name    = md5(uniqid(rand(), true));
                  $location     = "videos/$rand_name.$file_type";
                   move_uploaded_file($file_tmp_name,$location);
             }

           }
           
            $title           = strip_tags($_POST['title']);
                                          		  
			sql($DBH,"INSERT INTO tbl_videos(title,video,time)
			VALUES (?, ?, ?)",
			array($title,$rand_name,time()));
   
			$_SESSION['info'] = "<strong>$success_xml</strong>$colon_xml $added_video_xml";				
			redirect('videos.php');

		
	}
	catch(PDOException $e) { 
		file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File			
		$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $please_try_later_xml";
		redirect($go_back);	
		
	}
	
?>