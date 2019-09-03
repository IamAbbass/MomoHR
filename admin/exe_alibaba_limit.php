<?php

    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');



	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
        redirect('index.php');
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
        redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
        redirect('index.php');
	}

    $id             = $_POST['id'];
    $limit_bytes    = $_POST['limit_bytes'];

    $STH        = $DBH->prepare("SELECT count(*) FROM tbl_alibaba_limits WHERE company_id = ?");
    $result     = $STH->execute(array($id));
    $count      = $STH->fetchColumn();
    if($count == 0){
      sql($DBH,"INSERT INTO tbl_alibaba_limits (company_id) VALUES (?)",array($id));
    }


    sql($DBH, "update tbl_alibaba_limits set limit_bytes = ? where company_id = ?", array($limit_bytes,$id), "rows");

    $rows = sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($id), "rows");
    foreach($rows as $row){
		$admin_name			= $row['fullname'];
    }
	$_SESSION['info'] = "<strong>Success: </strong> Alibaba Cloud limit is set to ".readableFileSize($limit_bytes)." for ".$admin_name;
	redirect('list_admins.php');

?>
