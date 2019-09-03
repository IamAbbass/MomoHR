<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
	
	//die(json_encode($_REQUEST));
	
	$data = json_decode($_GET['data']);
	$type = $_GET['type'];
    
    $u_id           = $_GET['u_id'];    
    if(strlen($SESS_ID) == 0){
        $SESS_ID   = $u_id;
    }
	
	if($type == "message"){
		foreach($data as $key => $value){
			$value = explode(":",$value);
			$m_id 		= $value[0];
			$status 	= $value[1];		
			
            
            $rows = sql($DBH, "SELECT msg_audience FROM tbl_message WHERE id = ?",array($m_id), "rows");
            foreach($rows as $row){
                $msg_audience = $row['msg_audience'];
            }
            if($msg_audience == "group"){            
                $rows = sql($DBH, "SELECT group_delivered,group_seen FROM tbl_message WHERE id = ?",array($m_id), "rows");
                foreach($rows as $row){
                    $group_delivered    = $row['group_delivered'];
                    $group_seen         = $row['group_seen'];
                }
                $group_delivered    = explode(",",$group_delivered);      
                $group_seen         = explode(",",$group_seen);   
                
                
                if($status == "delivered"){
				    //read + delivered
    				if(!in_array($SESS_ID,$group_delivered)){
                        //not delivered          
                        $group_delivered[] = $SESS_ID;
                        $group_delivered = implode(",",$group_delivered);            
                        sql($DBH, "update tbl_message set group_delivered = ?, delivered = ? where id = ?",
                        array($group_delivered,time(),$m_id), "rows");
                    }
                    		
    			}else if($status == "seen"){
    				//seen + delivered    				
                    if(!in_array($SESS_ID,$group_delivered)){
                        //not delivered          
                        $group_delivered[] = $SESS_ID;
                        $group_delivered = implode(",",$group_delivered);            
                        sql($DBH, "update tbl_message set group_delivered = ?, delivered = ? where id = ?",
                        array($group_delivered,time(),$m_id), "rows");
                    }                    
                    if(!in_array($SESS_ID,$group_seen)){
                        //not seen              
                        $group_seen[] = $SESS_ID;
                        $group_seen = implode(",",$group_seen);            
                        sql($DBH, "update tbl_message set group_seen = ?, seen = ? where id = ?",
                        array($group_seen,time(),$m_id), "rows");
                    }                
    			}else if($status == "read"){
    				//read
    				//sql($DBH, "update tbl_message set mark_read_by_receiver = ? where msg_to = ? and id = ?",
    				//array("true",$SESS_ID,$m_id), "rows");
    			}else if($status == "unread"){
    				//unread
    				//sql($DBH, "update tbl_message set mark_read_by_receiver = ? where msg_to = ? and id = ?",
    				//array("false",$SESS_ID,$m_id), "rows");
    			}                
            }else{ //individual
                if($status == "delivered"){
				//read + delivered
    				sql($DBH, "update tbl_message set delivered = ? where msg_to = ? and id = ?",
    				array(time(),$SESS_ID,$m_id), "rows");				
    			}else if($status == "seen"){
    				//seen + delivered    				
                    $rows_messages = sql($DBH, "SELECT delivered FROM tbl_message where id = ?", array($m_id), "rows");
                	foreach($rows_messages as $row){
                		if($row['delivered'] == 0){
                			sql($DBH, "update tbl_message set mark_read_by_receiver = ?, delivered = ? where msg_to = ? and id = ?",
    				        array("true",time(),$SESS_ID,$m_id), "rows");                      
                		}
                    } 
                    sql($DBH, "update tbl_message set mark_read_by_receiver = ?, seen = ? where msg_to = ? and id = ?",
    				array("true",time(),$SESS_ID,$m_id), "rows");
    			}else if($status == "read"){
    				//read
    				sql($DBH, "update tbl_message set mark_read_by_receiver = ? where msg_to = ? and id = ?",
    				array("true",$SESS_ID,$m_id), "rows");
    			}else if($status == "unread"){
    				//unread
    				sql($DBH, "update tbl_message set mark_read_by_receiver = ? where msg_to = ? and id = ?",
    				array("false",$SESS_ID,$m_id), "rows");
    			}
            }						
		}
	}else if($type == "chat"){
		foreach($data as $key => $value){
			$value = explode(":",$value);
			$ch_id 		= $value[0];
			$status 	= $value[1];		
			
			if($status == "delivered"){
				//read + delivered
				sql($DBH, "update tbl_message set mark_read_by_receiver = ?, delivered = ? where msg_to = ? and msg_from = ?",
				array("true",time(),$SESS_ID,$ch_id), "rows");				
			}else if($status == "seen"){
				//read + delivered
				sql($DBH, "update tbl_message set mark_read_by_receiver = ?, seen = ? where msg_to = ? and msg_from = ?",
				array("true",time(),$SESS_ID,$ch_id), "rows");
			}else if($status == "read"){
				//read
				sql($DBH, "update tbl_message set mark_read_by_receiver = ? where msg_to = ? and msg_from = ?",
				array("true",$SESS_ID,$ch_id), "rows");
			}else if($status == "unread"){
				//unread
				sql($DBH, "update tbl_message set mark_read_by_receiver = ? where msg_to = ? and msg_from = ?",
				array("false",$SESS_ID,$ch_id), "rows");
			}			
		}
	}
?>