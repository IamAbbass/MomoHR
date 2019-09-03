<?php

    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');

    $access_denied_xml       = $xml->del_fence->access_denied;
    $no_access_xml           = $xml->del_fence->no_access;
    $success_xml             = $xml->del_fence->success;
    $location_del_xml        = $xml->del_fence->location_del;

	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_geo_fencing'] != "true"){
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

	$location_id	= ($_GET['location_id']);
	if($SESS_ACCESS_LEVEL == "root admin"){
		$rows = sql($DBH, "delete from tbl_geo_fence where id = ?",array($location_id), "rows");
		$_SESSION['info'] = "<strong>$success_xml </strong> $location_del_xml";
		redirect($go_back);
	}else if($SESS_ACCESS_LEVEL == "admin"){

		$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_geo_fence where id = ? AND company_id = ?");
		$result  		= $STH->execute(array($location_id,$SESS_COMPANY_ID));
		$real_admin		= $STH->fetchColumn();

		if($real_admin == 1){
			$rows = sql($DBH, "delete from tbl_geo_fence where id = ?",array($location_id), "rows");
			$_SESSION['info'] = "<strong>$success_xml </strong> $location_del_xml";
			redirect($go_back);
		}else{
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}
	}
?>
