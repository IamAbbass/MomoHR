<?php
    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');

    $invalid_device_xml    = $xml->select_device->invalid_device;
    $cannot_find_xml       = $xml->select_device->cannot_find;
    $access_denied_xml     = $xml->select_device->access_denied;
    $no_access_xml         = $xml->select_device->no_access;


	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}


	if($_GET['id']){
		$id	= $_GET['id'];

		if($SESS_ACCESS_LEVEL == "root admin"){
			$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ?");
			$result  		= $STH->execute(array($id));
			$count_valid	= $STH->fetchColumn();

			if($count_valid == 1){
				$rows = sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($id), "rows");
				foreach($rows as $row){
					$_SESSION['SESS_DEVICE_ID']		= $row['id'];
					$_SESSION['SESS_DEVICE_NAME']	= $row['fullname'];
				}
				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$_SESSION['msg'] = "<strong>$invalid_device_xml </strong> $cannot_find_xml";
				redirect('index.php');
			}
		}else if($SESS_ACCESS_LEVEL == "admin"){
			$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ? and company_id = ?");
			$result  		= $STH->execute(array($id,$SESS_COMPANY_ID));
			$count_valid	= $STH->fetchColumn();

			//($SESS_ID == $id) => admin selecting himself
			if($count_valid == 1 || ($SESS_ID == $id)){
				$rows = sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($id), "rows");
				foreach($rows as $row){
					$_SESSION['SESS_DEVICE_ID']		= $row['id'];
					$_SESSION['SESS_DEVICE_NAME']	= $row['fullname'];
				}


				redirect($_SERVER['HTTP_REFERER']);
			}else{
				$_SESSION['msg'] = "<strong>$invalid_device_xml </strong> $cannot_find_xml";
				redirect('index.php');
			}

		}else if($SESS_ACCESS_LEVEL == "user"){
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}

?>
