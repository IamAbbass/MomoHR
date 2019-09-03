<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
    
    //add security
    
    $name           = $_GET['name']; 
    $desc           = $_GET['desc']; 
    $group_id       = $_GET['group_id'];
    
    $rows_groups = sql($DBH, "select * from tbl_groups where admin_id = ? and id = ?",array($SESS_ID,$group_id), "rows"); 
    foreach($rows_groups as $row){        
        sql($DBH, "update tbl_groups set name=?, description=?, update_date_time=? where id = ?",
        array($name,$desc,time(),$group_id), "rows");
    }
?>