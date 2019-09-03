<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
	
    $access_denied_xml       = $xml->exe_admin_access->access_denied;
    $no_access_xml           = $xml->exe_admin_access->no_access;
    $failed_xml              = $xml->exe_admin_access->failed;
    $admin_not_found_xml     = $xml->exe_admin_access->admin_not_found;
    $cannot_update_user_xml  = $xml->exe_admin_access->cannot_update_user;
    $perm_updated_xml        = $xml->exe_admin_access->perm_updated;
    $for_admin_xml           = $xml->exe_admin_access->for_admin;
	
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
        
    
    if($_POST['id'] && strlen($_POST['id']) > 0){        
        $id = $_POST['id'];        
        $STH        = $DBH->prepare("SELECT count(*) FROM tbl_login where access_level = ? AND id = ?");
        $result     = $STH->execute(array("admin",$id));
        $count      = $STH->fetchColumn();
        if($count == 0){
            $_SESSION['msg'] = "$failed_xml $admin_not_found_xml";
            redirect($_SERVER['HTTP_REFERER']);
        } 
    }else{        
        $_SESSION['msg'] = "$failed_xml $cannot_update_user_xml";
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    $old_arr = array();
    $rows = sql($DBH, "SELECT * FROM tbl_manage_access where id = ?", array($id), "rows");                                            
    $vendor_permissions = $rows[0];
    foreach($vendor_permissions as $perm_code => $value){
        $perm_name  = permission_name($array_perm,$array_perm_text,$perm_code);                                    
        if($perm_name != null){            
            $old_arr[$perm_code] = $value;
        }
    }
	
    foreach($array_perm as $perm){        
        $value      = $_POST[$perm];
        if($value != "true"){$value = "false";}
        $old_value  = $old_arr[$perm];
        if($old_value != "true"){$old_value = "false";}
        
        if($value != $old_value){
            sql($DBH, "UPDATE tbl_manage_access set $perm = ? where id = ?", array($value,$id), "rows");
		} 
    }    
     
	  
    
    $_SESSION['info'] = "<b>$perm_updated_xml,</b> $for_admin_xml $id";
    redirect("list_admins.php");
    exit;
?>

