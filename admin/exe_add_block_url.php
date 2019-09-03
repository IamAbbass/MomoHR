<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
    
    
    $sorry_but_xml          = $xml->exe_add_block_url->sorry_but;
    $this_email_xml         = $xml->exe_add_block_url->this_email;
    $this_phone_xml         = $xml->exe_add_block_url->this_phone;
    $added_url_xml          = $xml->exe_add_block_url->added_url;
    $already_exists_xml     = $xml->exe_add_block_url->already_exists;
    $please_try_later_xml   = $xml->exe_add_block_url->please_try_later;
    $success_xml            = $xml->exe_add_block_url->success;
    $access_denied_xml      = $xml->exe_add_block_url->access_denied;
    $no_access_xml          = $xml->exe_add_block_url->no_access;
    $colon_xml              = $xml->exe_add_block_url->colon;

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
	
    
	$url            = strip_tags($_POST['url']);
   	
	$STH 			= $DBH->prepare("select count(*) FROM tbl_login where email =  ?");
	$result 	   	= $STH->execute(array($email));
	$count_email	= $STH->fetchColumn();			
	
	$STH 			= $DBH->prepare("select count(*) FROM tbl_login where contact =  ?");
	$result 	   	= $STH->execute(array($contact));
	$count_contact	= $STH->fetchColumn();
	
	if($count_email == 1){
		$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $this_email_xml '$email' <strong>$already_exists_xml</strong>!";
		redirect($go_back);				
	}else if($count_contact == 1){
		$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $this_phone_xml '$contact' <strong>$already_exists_xml</strong>!";
		redirect($go_back);				
	}else{
		sql($DBH,"INSERT INTO tbl_block_websites(device_id,url,date_time)
		VALUES (?, ?, ?)",array($SESS_DEVICE_ID,$url,time()));
        
        sql($DBH, "UPDATE tbl_blocking_option set settings_updated = ?, settings_applied = ? where device_id = ?", array(time(),0,$SESS_DEVICE_ID), "rows");
        
		$_SESSION['info'] = "<strong>$success_xml</strong>$colon_xml $added_url_xml";				
		redirect('block_websites.php');
	}
        
	
?>