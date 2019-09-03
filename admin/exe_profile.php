<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php'); 
	require_once('../class_function/language.php');
	
	$sorry_but_xml            = $xml->exe_profile->sorry_but;
    $this_phone_xml           = $xml->exe_profile->this_phone;
    $this_email_xml           = $xml->exe_profile->this_email;
    $already_exists_xml       = $xml->exe_profile->already_exists;
    $profile_updated_xml      = $xml->exe_profile->profile_updated;
    $email_updated_xml        = $xml->exe_profile->email_updated;
    $pass_updated_xml         = $xml->exe_profile->pass_updated;
    $pass_not_matched_xml     = $xml->exe_profile->pass_not_matched;
    $pass_again_xml           = $xml->exe_profile->pass_again;
    $invalid_pass_xml         = $xml->exe_profile->invalid_pass;
    $picture_update_xml       = $xml->exe_profile->picture_update;
    $access_denied_xml        = $xml->exe_profile->access_denied;
    $no_access_xml            = $xml->exe_profile->no_access;
    
	
	$allow_view_3rd_profile = false;
	if($_GET['id']){		
		if($SESS_ACCESS_LEVEL == "root admin"){
			$profile_id = $_GET['id'];
		}else if($SESS_ACCESS_LEVEL == "admin"){			
			$id = $_GET['id'];			
			$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ? and parent_id = ?;");
			$result  		= $STH->execute(array($id,$SESS_ID));
			$admins_user 	= $STH->fetchColumn();			
			if($admins_user == 1){
				$profile_id = $_GET['id'];
			}else{
				$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
				redirect('index.php');
			}
		}else if($SESS_ACCESS_LEVEL == "user"){
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}else{
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}		
	}else{
		$profile_id = $SESS_ID;//variables from session
	}
	
    
    
	$form = $_GET['form'];
	
	if($form == "profile"){	    
		$name 	        = stripslashes($_POST['name']);
		$contact 		= stripslashes($_POST['contact']);
		$address 		= stripslashes($_POST['address']);
        $city 	        = stripslashes($_POST['city']);
		$country 		= stripslashes($_POST['country']);	
		
		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where contact = ? and id != ?");
		$result 	   	= $STH->execute(array($contact,$profile_id));
		$count_contact	= $STH->fetchColumn();
		
		if($count_contact == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $this_phone_xml '$contact' $already_exists_xml";
			if($profile_id != $SESS_ID){			
				header("location: profile.php?id=".$profile_id);
			}else{
				header("location: profile.php");
			}
			exit;			
		}else{			
			sql($DBH, "UPDATE tbl_login SET fullname = ?, contact =?, address = ?, 
			city = ?,country = ?, update_at = ? WHERE id = ?", array($name,$contact,$address,$city,$country,time(),$profile_id));
			
			$_SESSION['info'] = "$profile_updated_xml";
			if($profile_id != $SESS_ID){
				header("location: profile.php?id=".$profile_id);
			}else{
				
				$_SESSION['SESS_FULLNAME']	= $name;
				$_SESSION['SESS_CONTACT']	= $contact;
				$_SESSION['SESS_ADDRESS']	= $address;
				$_SESSION['SESS_CITY']	    = $city;
				$_SESSION['SESS_COUNTRY']	= $country;
				
				header("location: profile.php");
			}
			exit;
		}		
		
	}else if($form == "email"){
		$email 			= stripslashes($_POST['email']);
		$username 		= stripslashes($_POST['username']);
		
		
		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where email = ? and id != ?");
		$result 	   	= $STH->execute(array($email,$profile_id));
		$count_email	= $STH->fetchColumn();
		
		if($count_email == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $this_email_xml '$email' $already_exists_xml";
			if($profile_id != $SESS_ID){			
				header("location: profile.php?id=".$profile_id);
			}else{
				header("location: profile.php");
			}
			exit;			
		}else{			
			
			sql($DBH, "UPDATE tbl_login SET email = ?, update_at = ? WHERE id = ?", array($email,time(),$profile_id));
			$_SESSION['info']	 = "$email_updated_xml";
			if($profile_id != $SESS_ID){			
				header("location: profile.php?id=".$profile_id);
			}else{
				
				$_SESSION['SESS_EMAIL']	     = $email;
				
				header("location: profile.php");
			}
			exit;
		}
	}else if($form == "password"){
		$old_password 		= strip_tags($_POST["old_password"]);
        
        $new_password		= strip_tags($_POST["new_password"]);
		$confirm_password	= strip_tags($_POST["confirm_password"]);
        
        
        $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($profile_id), "rows");							
        foreach($rows as $row){                                                                
          $password         = $row['password'];
          $pass_token       = $row['pass_token'];          
        }
       
        if($password == md5(md5(md5($pass_token) . md5($old_password)) . md5($pass_token))){
            
            if($new_password == $confirm_password){
    			$set_password = md5(md5(md5($pass_token) . md5($new_password)) . md5($pass_token));
    			sql($DBH, "UPDATE tbl_login SET password = ? WHERE id = ?",
    			array($set_password, $profile_id));
                
                
				$_SESSION['info']	 = "$pass_updated_xml";
				if($profile_id != $SESS_ID){			
					header("location: profile.php?id=".$profile_id);
				}else{
					header("location: profile.php");
				}
				exit;	
            }else{
                $_SESSION['msg']	 = "<strong>$pass_not_matched_xml </strong> $pass_again_xml";
				if($profile_id != $SESS_ID){			
					header("location: profile.php?id=".$profile_id);
				}else{
					header("location: profile.php");
				}
				exit;
            }
        }else{
            $_SESSION['msg']	 = "$invalid_pass_xml </strong> $pass_again_xml";
			if($profile_id != $SESS_ID){			
				header("location: profile.php?id=".$profile_id);
			}else{
				header("location: profile.php");
			}
			exit;
        }
	}else if($form == "pic"){
		$name = $_FILES["pic"]["name"];
		if(strlen($name) > 0){			
			$rand_name	= md5(uniqid(rand(), true)).".".pathinfo($name, PATHINFO_EXTENSION);
			$tmp_name 	= $_FILES["pic"]["tmp_name"];
			$location 	= "upload/dp/$rand_name";
			move_uploaded_file($tmp_name,$location);
			
			
			
            sql($DBH, "UPDATE tbl_login SET photo = ?, update_at = ? WHERE id = ?",
			array($website_url."admin/".$location,time(),$profile_id));
			
			$_SESSION['info']	 = "$picture_update_xml";
			if($profile_id != $SESS_ID){			
				header("location: profile.php?id=".$profile_id);
			}else{
				$_SESSION['SESS_PHOTO']		= $location;
				header("location: profile.php");
			}
			exit;
		}else{
		  header ("location: profile.php");
		  exit();
		}
	}else{
		header("location: profile.php");
		exit;
	}
?>


