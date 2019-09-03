<?php
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
	
    $access_denied_xml       = $xml->exe_default_access->access_denied;
    $no_access_xml           = $xml->exe_default_access->no_access;
    $success_xml             = $xml->exe_default_access->success;
    $default_perm_xml        = $xml->exe_default_access->default_perm;
	
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
	
	
	if($SESS_ACCESS_LEVEL != "root admin"){
        $_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
    }    
        
    foreach($array_perm as $perm){        
        $value      = $_POST[$perm];
        if($value != "true"){$value = "false";}        
        sql($DBH, "UPDATE tbl_manage_access_default set $perm = ?", array($value), "rows");
    }    
      
    
    $_SESSION['info'] = "<b>$success_xml </b> $default_perm_xml";
    redirect("default_access.php");
    exit;
	
?>