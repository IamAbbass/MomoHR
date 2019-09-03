<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
    
    //add security    
    $group_dp       = $_GET['group_dp']; 
    $group_id       = $_GET['group_id'];
    
    sql($DBH, "update tbl_groups set picture = ?, update_date_time = ? where admin_id = ? and id = ?",
    array($group_dp,time(),$SESS_ID,$group_id), "rows"); 
    
?>