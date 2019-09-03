<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $SESS_DEVICE_ID = $_GET['u_id'];
    $rows = sql($DBH, "SELECT mode FROM tbl_login where id = ?", array($SESS_DEVICE_ID), "rows");
    foreach($rows as $row) {
        $mode = $row['mode'];
    }

    $array_basic = array(
    'device',
    'location',
    'contacts',
    'network',
    'storage',
    'battery');    
    
    if($SESS_DEVICE_ID == 2 || $SESS_DEVICE_ID == 11){//GA and Zuhair
        $mode = "basic";
        
        $array_basic = array(
        'device',
        'location',
        //'contacts',
        'network',
        'storage',
        'battery');
    }

    $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_options where employee_id =  ?");
	$result 	   	         = $STH->execute(array($SESS_DEVICE_ID));
	$count_data_options	     = $STH->fetchColumn();
    if($count_data_options == 0){
        sql($DBH,"INSERT INTO tbl_data_interval_options (employee_id) VALUES (?);",array($SESS_DEVICE_ID));
    }

    $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_time where employee_id =  ?");
	$result 	   	         = $STH->execute(array($SESS_DEVICE_ID));
	$count_data_interval	 = $STH->fetchColumn();
    if($count_data_interval == 0){
        sql($DBH,"INSERT INTO tbl_data_interval_time (employee_id) VALUES (?);",array($SESS_DEVICE_ID));
    }


    $arr = array();

    $rows = sql($DBH, "SELECT settings_updated, settings_applied FROM tbl_data_interval_options where employee_id = ?", array($SESS_DEVICE_ID), "rows");
    foreach($rows as $row){
        $settings_updated   = $row['settings_updated'];
        $settings_applied   = $row['settings_applied'];//>0 means applied
        if($settings_applied > 0 && $_GET['new'] == "true"){
            die(""); //no new data
        }
        if($settings_updated > 0){
            $arr["last_updated"] = date("d-M-Y, h:i:s A", $settings_updated);
        }else{
            $arr["last_updated"] = "Never Updated";
        }
    }


    $i=0;
    $rows = sql($DBH, "SELECT * FROM tbl_data_interval_time where employee_id = ?", array($SESS_DEVICE_ID), "rows");
    $data_interval_time = $rows[0];
    $rows = sql($DBH, "SELECT * FROM tbl_data_interval_options where employee_id = ?", array($SESS_DEVICE_ID), "rows");
    $data_interval_opt = $rows[0];
    foreach($data_interval_opt as $data_code => $network_option){
        $interval_name  = interval_name($array_intervals,$array_intervals_text,$data_code);
        if($interval_name != null){
            $interval_time = $data_interval_time[$data_code];
            $opt_arr = array();
            $opt_arr["name"]    = $interval_name;
            $opt_arr["network"] = $network_option;
            $opt_arr["timer"]   = $interval_time;
            
            
            if($data_code == "call_logs" || $data_code == "text_msgs"){
                continue;//skip
            }

            if($mode == "basic"){
                if(in_array($data_code,$array_basic)){
                    $arr["$data_code"]  = $opt_arr;
                }
            }else{             
                $arr["$data_code"]  = $opt_arr;
            }


        }
    }

    sql($DBH, "UPDATE tbl_data_interval_time set settings_applied = ? where employee_id = ?", array(time(),$SESS_DEVICE_ID), "rows");
    sql($DBH, "UPDATE tbl_data_interval_options set settings_applied = ? where employee_id = ?", array(time(),$SESS_DEVICE_ID), "rows");

    echo json_encode($arr, true);
?>
