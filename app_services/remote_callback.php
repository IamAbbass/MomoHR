<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
    
    $SESS_DEVICE_ID = $_GET['id'];
    $type           = $_GET['type'];
    
    if($type == "message"){
        sql($DBH,"UPDATE tbl_remove_device_lock set send_message = ? where employee_id = ?",
        array("false",$SESS_DEVICE_ID));
    }else if($type == "sound"){
        sql($DBH,"UPDATE tbl_remove_device_lock set play_sound = ? where employee_id = ?",
        array("false",$SESS_DEVICE_ID));
    }else if($type == "wipe"){
        sql($DBH,"UPDATE tbl_remove_device_lock set remote_wipe = ? where employee_id = ?",
        array("false",$SESS_DEVICE_ID));
    }else if($type == "lock"){
        sql($DBH,"UPDATE tbl_remove_device_lock set remote_lock = ? where employee_id = ?",
        array("false",$SESS_DEVICE_ID));
    }
?>