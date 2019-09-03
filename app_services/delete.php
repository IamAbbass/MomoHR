<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
    	
    //delete message
    
    //validation required here: sending message to who ?
    
    $del_id        = $_GET['del_id'];
    $message        = htmlspecialchars($_GET['message']);    
        
    $u_id           = $_GET['u_id'];    
    if(strlen($SESS_ID) == 0){
        $SESS_ID   = $u_id;
    }  
    
    $rows_reply = sql($DBH, "SELECT sent,msg_from FROM tbl_message where id = ?", array($del_id), "rows");
    foreach($rows_reply as $row_reply){  
        $msg_from   = $row_reply['msg_from'];
        $sent_time  = $row_reply['sent'];
    }
    
    $arr = array();
    if($msg_from != $SESS_ID){
        $arr['error'] = "Can not delete this message!";
    }else if(time()-$sent_time > 3600){
        $arr['error'] = "You can only delete message within 60 mins!";        
    }else{        
        sql($DBH, "update tbl_message set deleted = ?, deleted_time = ? where id = ? and msg_from = ?",
        array("true",time(),$del_id,$SESS_ID), "rows"); 
        
        $arr['error']   =   "false";
        $arr['del_id']  =   $del_id;
        $arr['message'] =   $message;
    }
    
    die(json_encode($arr));
    
    
    
?>