<?php    
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');
    
    
    $no_access_xml        = $xml->active_inactive->no_access;
    $blocked_xml          = $xml->active_inactive->blocked;
    $unblock_xml          = $xml->active_inactive->unblock;
    $please_try_xml       = $xml->active_inactive->please_try;
  
	
	//$page = basename($_SERVER['HTTP_REFERER']);
	$type 			= $_GET['type'];	
	$id 			= $_GET['id'];
	
	if($type == "user"){		
		$arr 		= array();
		$table		= "tbl_login";
		
		$allow_execute 	= true;
        /*		
		if(strlen($id ) > 0){			
			if($SESS_ACCESS_LEVEL == "root admin"){
				$allow_execute = true;
			}else if($SESS_ACCESS_LEVEL == "admin"){
				$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ? and parent_id = ?");
				$result  		= $STH->execute(array($id,$SESS_ID));
				$admins_user 	= $STH->fetchColumn();			
				if($admins_user == 1){
					$allow_execute = true;
				}
			}else if($SESS_ACCESS_LEVEL == "user"){
				$allow_execute = false;
			}else{
				$allow_execute = false;
			}		
		}else{
			$allow_execute = false;
		}
		*/
        	
		if($allow_execute == true){			
			active_inactive($DBH,$table,$id); //die auto
		}else{			
			$arr['error'] 			= "$no_access_xml";	
			die(json_encode($arr, true));
		}			
	}else if($type == "app"){		
		$package_name = $_GET['package_name'];
        $arr 		= array();
		$table		= "tbl_app_blocking";
		
		$allow_execute 	= true;	
        
        /*	
		if(strlen($SESS_DEVICE_ID) > 0){			
			if($SESS_ACCESS_LEVEL == "root admin"){
				$allow_execute = true;
			}else if($SESS_ACCESS_LEVEL == "admin"){
				$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ? and parent_id = ?");
				$result  		= $STH->execute(array($SESS_DEVICE_ID,$SESS_ID));
				$admins_user 	= $STH->fetchColumn();			
				if($admins_user == 1){
					$allow_execute = true;
				}
			}else if($SESS_ACCESS_LEVEL == "user"){
				$allow_execute = false;
			}else{
				$allow_execute = false;
			}		
		}else{
			$allow_execute = false;
		}
        */
				
		if($allow_execute == true){	            
            $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_app_blocking where employee_id = ? and package_name = ?");
			$result  		= $STH->execute(array($SESS_DEVICE_ID,$package_name));
			$app_blocked 	= $STH->fetchColumn();		
            	
			if($app_blocked == 1){
			     sql($DBH, "delete from tbl_app_blocking where employee_id = ? and package_name = ?", array($SESS_DEVICE_ID,$package_name), "count");
                //unblock
                $new_status = "active";
            }else{
                sql($DBH, "insert into tbl_app_blocking (employee_id, package_name) values (?,?)", array($SESS_DEVICE_ID,$package_name), "count");
                //block
                $new_status = "inactive";
            }
            
    		if($new_status == "active"){
				$arr['new_class']		= "btn-success";
				$arr['new_html']		= "<i class='fa fa-unlock'></i> $unblock_xml";
			}else if($new_status == "inactive"){
				$arr['new_class']		= "btn-danger";
				$arr['new_html']		= "<i class='fa fa-lock'></i> $blocked_xml";
			}else{
    			$arr['error']			= "$please_try_xml";				
    		}
    		die(json_encode($arr, true));
            
            
            
		}else{			
			$arr['error'] 			= "$no_access_xml";	
			die(json_encode($arr, true));
		}			
	}
	
	
?>