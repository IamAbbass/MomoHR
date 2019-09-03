<?php    
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');
    
    $access_denied_xml        = $xml->del_schedule->access_denied;
    $no_access_xml            = $xml->del_schedule->no_access;
    $ok_xml                   = $xml->del_schedule->ok;
	
	
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
	
	$id = $_GET['id'];    
    $rows = sql($DBH, "DELETE FROM tbl_schedule_access where id = ?", array($id), "rows");	
	die("$ok_xml");