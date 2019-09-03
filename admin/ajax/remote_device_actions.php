<?php
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');

    $action = $_GET['action'];
    $SESS_DEVICE_ID = $_GET['u_id'];

    if($action == "play_sound"){
        sql($DBH, "UPDATE tbl_remove_device_lock set play_sound = ? where employee_id = ?",
        array("true",$SESS_DEVICE_ID), "rows");
        sql($DBH, "UPDATE tbl_remove_device_lock set settings_updated = ?, settings_applied = ? where employee_id = ?", array(time(),0,$SESS_DEVICE_ID), "rows");
        //die("playing");
    }else if($action == "stop_sound"){
        sql($DBH, "UPDATE tbl_remove_device_lock set play_sound = ? where employee_id = ?",
        array("false",$SESS_DEVICE_ID), "rows");
        sql($DBH, "UPDATE tbl_remove_device_lock set settings_updated = ?, settings_applied = ? where employee_id = ?", array(time(),0,$SESS_DEVICE_ID), "rows");
        //die("stopped");
    }else if($action == "send_message"){
        $title  = $_GET['title'];
        $body   = $_GET['body'];
        sql($DBH, "UPDATE tbl_remove_device_lock set send_message = ?, message_title = ?, message_body = ?
        where employee_id = ?",
        array("true",$title,$body,$SESS_DEVICE_ID), "rows");
        sql($DBH, "UPDATE tbl_remove_device_lock set settings_updated = ?, settings_applied = ? where employee_id = ?", array(time(),0,$SESS_DEVICE_ID), "rows");
    }else if($action == "remote_lock"){
        sql($DBH, "UPDATE tbl_remove_device_lock set remote_lock = ? where employee_id = ?", 
        array("true",$SESS_DEVICE_ID), "rows");
        sql($DBH, "UPDATE tbl_remove_device_lock set settings_updated = ?, settings_applied = ? where employee_id = ?", array(time(),0,$SESS_DEVICE_ID), "rows");
    }else if($action == "remote_wipe"){
        sql($DBH, "UPDATE tbl_remove_device_lock set remote_wipe = ? where employee_id = ?",
        array("true",$SESS_DEVICE_ID), "rows");
        sql($DBH, "UPDATE tbl_remove_device_lock set settings_updated = ?, settings_applied = ? where employee_id = ?", array(time(),0,$SESS_DEVICE_ID), "rows");
    }


?>
