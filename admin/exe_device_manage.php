<?php
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');

    $access_denied_xml        = $xml->exe_device_manage->access_denied;
    $no_access_xml            = $xml->exe_device_manage->no_access;
    $device_setting_xml       = $xml->exe_device_manage->device_setting;
    $success_xml              = $xml->exe_device_manage->success;
    
    $SESS_DEVICE_ID = $_POST['u_id'];

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

    //apply settings for data
    foreach($_POST['interval_time'] as $key => $value){
        sql($DBH, "UPDATE tbl_data_interval_time set $key = ? where employee_id = ?", array($value,$SESS_DEVICE_ID), "rows");
    }
    //update updated time stamp set current time
    sql($DBH, "UPDATE tbl_data_interval_time set settings_updated = ? where employee_id = ?", array(time(),$SESS_DEVICE_ID), "rows");

    //apply settings for time
    foreach($_POST['network_option'] as $key => $value){
        sql($DBH, "UPDATE tbl_data_interval_options set $key = ? where employee_id = ?", array($value,$SESS_DEVICE_ID), "rows");
    }

    //update updated time stamp set current time
    sql($DBH, "UPDATE tbl_data_interval_options set settings_updated = ? where employee_id = ?", array(time(),$SESS_DEVICE_ID), "rows");

    //set settings_applied = zero: because the mobile app updates the settings and updates the time interval: admin can see when the settings were applied in mobile app
    sql($DBH, "UPDATE tbl_data_interval_time set settings_applied = ? where employee_id = ?", array(0,$SESS_DEVICE_ID), "rows");
    sql($DBH, "UPDATE tbl_data_interval_options set settings_applied = ? where employee_id = ?", array(0,$SESS_DEVICE_ID), "rows");

    $_SESSION['info'] = "<b>$success_xml</b> $device_setting_xml";
    redirect("employee-profile.php?id=$SESS_DEVICE_ID#tab_device");
    exit;
?>
