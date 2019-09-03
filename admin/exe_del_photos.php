<?php
    
    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
    
    $access_denied_xml        = $xml->exe_del_photo->access_denied;
    $no_access_xml            = $xml->exe_del_photo->no_access;
    $success_xml              = $xml->exe_del_photo->success;
    $del_photo_xml            = $xml->exe_del_photo->del_photo;
    
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_photos'] != "true"){
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
	
	$id	= ($_GET['id']);
	if($SESS_ACCESS_LEVEL == "root admin"){
		$rows = sql($DBH, "delete from tbl_photos where id = ?",array($id), "rows");  
		$_SESSION['info'] = "<strong>$success_xml </strong> $del_photo_xml";
		redirect($go_back);		
	}else if($SESS_ACCESS_LEVEL == "admin"){
		
		$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_photos where id = ? AND admin_id = ?");
		$result  		= $STH->execute(array($id,$SESS_ID));
		$real_admin		= $STH->fetchColumn();
		
		if($real_admin == 1){
			$rows = sql($DBH, "delete from tbl_photos where id = ?",array($id), "rows");  
			$_SESSION['info'] = "<strong>$success_xml </strong> $del_photo_xml";
			redirect($go_back);		
		}else{
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}		
	}
?>