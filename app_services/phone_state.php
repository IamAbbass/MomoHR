<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $SESS_DEVICE_ID = $_GET['u_id']; 
    $state          = $_GET['state']; 
    $gps            = $_GET['gps']; 
    $date_time      = time();
    
    $rows = sql($DBH, "insert into tbl_phone_state (employee_id,state,gps,date_time) values (?,?,?,?)",
    array($SESS_DEVICE_ID,$state,$gps,$date_time), "rows");
    


?>