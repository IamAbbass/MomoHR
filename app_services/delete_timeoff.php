<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
    
    $SESS_DEVICE_ID = $_GET['u_id']; 
    $id             = $_GET['id'];
    
    sql($DBH, "UPDATE tbl_time_off SET STATUS = ?, update_date_time = ? WHERE id = ? AND employee_id = ?", 
    array("deleted",time(),$id,$SESS_DEVICE_ID), "rows");							
?>