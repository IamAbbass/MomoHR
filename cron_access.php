<?php
	require_once('class_function/session.php');
	require_once('class_function/error.php');
	require_once('class_function/dbconfig.php');
	require_once('class_function/function.php');	
	require_once('class_function/language.php');
    
    
    $access_xml           = $xml->cron_access->access;
    $has_xml              = $xml->cron_access->has;
    $for_your_store_xml   = $xml->cron_access->for_your;
    $allowed_xml          = $xml->cron_access->allowed;
    $not_allowed_xml      = $xml->cron_access->not_allowed;
    
	    
    
    $ids = array();
    
    $rows       = sql($DBH, "SELECT * FROM tbl_schedule_access where trigger_time > ? and trigger_time < ?", array(0,time()), "rows");    
    foreach($rows as $row)    {	
        $id             = $row['id'];
		$perm           = $row['perm'];
		$new_value      = $row['new_value'];
        $admin_id      	= $row['admin_id'];
                                                 
        $ids[] 			= $admin_id;       
        
        $old_arr = array();
        $rows1 = sql($DBH, "SELECT * FROM tbl_manage_access where id = ?", array($admin_id), "rows");                                            
        $vendor_permissions = $rows1[0];
        foreach($vendor_permissions as $perm_code => $value){
            $perm_name  = permission_name($array_perm,$array_perm_text,$perm_code);                                    
            if($perm_name != null){            
                $old_arr[$perm_code] = $value;
            }
        }
        
        $message = "";
        foreach($array_perm as $perm){
            if($new_value != "true"){$new_value = "false";}
			$old_value  = $old_arr[$perm];
            if($old_value != "true"){$old_value = "false";}  
			
            if($new_value != $old_value){
                //echo "UPDATE tbl_manage_access set $perm = '$new_value' where id = '$admin_id' <br>";
				
				sql($DBH, "UPDATE tbl_manage_access set $perm = ? where id = ?", array($new_value,$admin_id), "rows");                
                $perm_name  = ucwords(permission_name($array_perm,$array_perm_text,$perm));                  
                if($new_value == "true"){
                    $allow_block = "$allowed_xml";
                }else{
                    $allow_block = "$not_allowed_xml";
                }			
    			$message .= "$access_xml <kbd>$perm_name</kbd> $has_xml $allow_block $for_your_store_xml<Br>";             
            }
        } 
        
        sql($DBH, "delete from tbl_schedule_access where id = ?", array($id), "rows");
		
    }
    
    sql($DBH, "insert into tbl_debug_cron(page,time,ids) values (?,?,?);", array("cron_access",date("D d M Y h:i:s a"),implode(",",$ids)));
      
?>