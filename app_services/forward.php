<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
    
    //validation required here: sending message to who ?
       
    
    $ch_id          = $_GET['ch_id']; 
    $msg_id         = $_GET['msg_id'];    
    $u_id           = $_GET['u_id'];
    $arr 			= array();    
    
    if($SESS_ID != ""){
        $msg_from   = $SESS_ID;
    }else{
        $msg_from   = $u_id;
    }
    
    $msg_to     	= $ch_id; 
	
	$rows_messages = sql($DBH, "SELECT * FROM tbl_message where id = ?", array($msg_id), "rows");
	foreach($rows_messages as $row){
		$message_type	= $row['msg_type'];
		$message		= $row['msg_text'];
		$message_url	= $row['msg_url']; 
    }	
		
    sql($DBH, "insert  into tbl_message(msg_from,msg_to,msg_type,msg_text,msg_url,sent)
    values (?,?,?,?,?,?)",
    array($msg_from,$msg_to,$message_type,$message,$message_url,time()), "rows"); 
        
    $arr['status']  = "SUCCESS";
    //$arr['id']      = $DBH->lastInsertId();
    //$arr['msg_i']   = $msg_i;
    
    die(json_encode($arr, true));

?>