<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	
	$ch_ids = explode(",",$_GET['ch_ids']);	
	$action = $_GET['action'];
    
    $u_id           = $_GET['u_id'];    
    if(strlen($SESS_ID) == 0){
        $SESS_ID   = $u_id;
    }
    
	foreach($ch_ids as $key => $ch_id){
		
		//unread = false;
		//read = true;
		
		if($action == "unread"){
			sql($DBH, "update tbl_message set mark_read_by_receiver = ?, mark_read_by_sender = ? 
			where 
			(msg_to = ? and msg_from = ?) OR (msg_to = ? and msg_from = ?)",
			array("false","false",$SESS_ID,$ch_id,$ch_id,$SESS_ID), "rows");				
		}else if($action == "read"){
			sql($DBH, "update tbl_message set mark_read_by_receiver = ?, mark_read_by_sender = ? 
			where 
			(msg_to = ? and msg_from = ?) OR (msg_to = ? and msg_from = ?)",
			array("true","true",$SESS_ID,$ch_id,$ch_id,$SESS_ID), "rows");
		}else if($action == "delete"){
			sql($DBH, "delete from tbl_message where (msg_to = ? and msg_from = ?) OR (msg_to = ? and msg_from = ?)",
			array($SESS_ID,$ch_id,$ch_id,$SESS_ID), "rows");
		}
	}
	
	
?>