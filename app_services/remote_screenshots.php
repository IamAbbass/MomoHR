<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $SESS_DEVICE_ID = $_GET['u_id'];
    $timestamp      = $_GET['ts'];
    $arr = array();

    $timestamp_new  = $timestamp;
    
    $rows = sql($DBH, "SELECT access_level FROM tbl_login where id = ?", array($SESS_DEVICE_ID), "rows");
    foreach($rows as $row){
        $access_level = $row['access_level'];
    }
    if($access_level == "admin"){
        $arr["screenshot"][0]['enable']          = false;
        $arr["screenshot"][0]['interval']        = 86400;
    }else{
       $rows = sql($DBH, "SELECT * FROM tbl_screenshot_settings where employee_id = ? and date_time > ?",
        array($SESS_DEVICE_ID,$timestamp), "rows");
        foreach($rows as $row){
            $arr["screenshot"][0]['enable']          = $row['ss_enable'];
            $arr["screenshot"][0]['interval']        = $row['ss_interval'];
            if($row['date_time'] >= $timestamp_new){
                $timestamp_new = $row['date_time'];
            }
        } 
    }

		
    
    $arr['timestamp_new'] = $timestamp_new;
    echo json_encode($arr, true);
?>
