<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
    
    $success_xml         = $xml->exe_schedule_trigger->success;
    $access_level_xml    = $xml->exe_schedule_trigger->access_level;
    $access_denied_xml   = $xml->exe_schedule_trigger->access_denied;
    $no_access_xml       = $xml->exe_schedule_trigger->no_access;
    
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}
    	
	$id 				= $_POST['id'];
	$perm 				= implode($_POST['perm'],",");
	$new_value 			= $_POST['new_value'];
	$trigger_time 		= strtotime($_POST['trigger_time']);
	
	
	sql($DBH, "INSERT into tbl_schedule_access 
	(admin_id,perm,new_value,trigger_time)
	values
	(?,?,?,?)", 
	array($id,$perm,$new_value,$trigger_time), "rows");	 
	
	
	$_SESSION['info'] = "<strong>$success_xml </strong> $access_level_xml";
	redirect($_SERVER['HTTP_REFERER']);
	exit;
	
?>