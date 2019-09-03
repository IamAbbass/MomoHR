<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $SESS_DEVICE_ID = $_GET['u_id'];

    $STH 			         = $DBH->prepare("select count(*) FROM tbl_remove_device_lock where employee_id =  ?");
	$result 	   	         = $STH->execute(array($SESS_DEVICE_ID));
	$count_data_interval	 = $STH->fetchColumn();
    if($count_data_interval == 0){
        sql($DBH,"INSERT INTO tbl_remove_device_lock (employee_id) VALUES (?);",array($SESS_DEVICE_ID));
    }

    $arr = array();
    $rows = sql($DBH, "SELECT settings_updated, settings_applied FROM tbl_remove_device_lock where employee_id = ?", array($SESS_DEVICE_ID), "rows");
    foreach($rows as $row){
        $settings_updated   = $row['settings_updated'];
        $settings_applied   = $row['settings_applied'];//>0 means applied
        if($settings_applied > 0 && $_GET['new'] == "true"){
            die(""); //no new data
        }
        if($settings_updated > 0){
            $arr["last_updated"] = my_simple_date($settings_updated);
        }else{
            $arr["last_updated"] = "Never Updated";
        }
    }


    $rows = sql($DBH, "SELECT * FROM tbl_remove_device_lock where employee_id = ?", array($SESS_DEVICE_ID), "rows");
    foreach($rows as $row){
        $arr['send_message']    = $row['send_message'];
        $arr['message_title']   = $row['message_title'];
        $arr['message_body']    = $row['message_body'];
        $arr['play_sound']      = $row['play_sound'];
        $arr['remote_lock']     = $row['remote_lock'];
        $arr['remote_wipe']     = $row['remote_wipe'];
    }

    sql($DBH, "UPDATE tbl_remove_device_lock set settings_applied = ? where employee_id = ?", array(time(),$SESS_DEVICE_ID), "rows");


    die(json_encode($arr));
?>
