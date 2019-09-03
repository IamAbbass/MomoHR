<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
	
	$STH 			= $DBH->prepare("select count(*) FROM tbl_message where msg_to = ? and mark_read_by_receiver = ?");
	$result 	   	= $STH->execute(array($SESS_ID,"false"));
	$message_count  = $STH->fetchColumn();
	
	$arr = array();
	$arr['message_count'] = $message_count;
	die(json_encode($arr));
?>