<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
    
    $SESS_DEVICE_ID = $_GET['u_id']; 
    $STH           = $DBH->prepare("select count(*) FROM tbl_blocking_option where employee_id =  ?");
	$result        = $STH->execute(array($SESS_DEVICE_ID));
	$count         = $STH->fetchColumn();    
    if($count == 0){
        sql($DBH,"INSERT INTO tbl_blocking_option (employee_id) VALUES (?);",array($SESS_DEVICE_ID));
    }
       
    $arr = array();
    
    $rows = sql($DBH, "SELECT settings_updated, settings_applied FROM tbl_blocking_option where employee_id = ?", array($SESS_DEVICE_ID), "rows");							
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
    
    $rows = sql($DBH, "SELECT url FROM tbl_block_websites where employee_id = ?", array($SESS_DEVICE_ID), "rows");							
    foreach($rows as $row){
        $arr['url'][] = $row['url'];        
    }
    
    $rows = sql($DBH, "SELECT blocking_option FROM tbl_blocking_option where employee_id = ?", array($SESS_DEVICE_ID), "rows");							
    foreach($rows as $row){
        $arr['blocking_option'] = $row['blocking_option'];        
    }
    
    sql($DBH, "UPDATE tbl_blocking_option set settings_applied = ? where employee_id = ?", array(time(),$SESS_DEVICE_ID), "rows");
      
    echo json_encode($arr, true);
?>