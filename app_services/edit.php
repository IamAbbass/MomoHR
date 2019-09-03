<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
	    
    
    //validation required here: sending message to who ?
    
    $edit_id        = $_GET['edit_id'];
    $message        = htmlspecialchars($_GET['message']);    
        
    $u_id           = $_GET['u_id'];    
    if(strlen($SESS_ID) == 0){
        $SESS_ID   = $u_id;
    }  
    
    $rows_reply = sql($DBH, "SELECT sent,msg_from FROM tbl_message where id = ?", array($edit_id), "rows");
    foreach($rows_reply as $row_reply){  
        $msg_from   = $row_reply['msg_from'];
        $sent_time  = $row_reply['sent'];
    }
    
    $arr = array();
    if($msg_from != $SESS_ID){
        $arr['error'] = "Can not edit this message!";
    }else if(time()-$sent_time > 3600){
        $arr['error'] = "You can only edit message within 60 mins!";        
    }else{
        
        ///when the message is edited -> sent time is the edited time (edit time replaces the sent time)
        
        sql($DBH, "update tbl_message set msg_text = ?, edited = ?, edited_time = ? where id = ? and msg_from = ?",
        array($message,"true",time(),$edit_id,$SESS_ID), "rows"); 
        $arr['error']   =   "false";
        $arr['edit_id'] =   $edit_id;
        $arr['message'] =   $message;
    }
    
    die(json_encode($arr));
    
    
    
?>