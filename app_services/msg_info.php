<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
    
    //validation required here: sending message to who ?
    
    $msg_id         = $_GET['msg_id'];    
    $arr 			= array();
	
	$rows_messages = sql($DBH, "SELECT * FROM tbl_message where id = ?", array($msg_id), "rows");
	foreach($rows_messages as $row){
		if($row['sent'] > 0){
			$arr['sent']		= my_simple_date_for_ch($row['sent']);
		}else{
			$arr['sent']		= "not sent yet"; 
		}
		
		if($row['delivered'] > 0){
			$arr['delivered']	= my_simple_date_for_ch($row['delivered']);
		}else{
			$arr['delivered']		= "not delivered yet"; 
		}
		
		if($row['seen'] > 0){
			$arr['seen']		= my_simple_date_for_ch($row['seen']); 
		}else{
			$arr['seen']		= "not seen yet"; 
		}
    }	
    
    die(json_encode($arr, true));

?>