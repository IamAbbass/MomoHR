<?php    
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');	
    
    $del_user_xml   = $xml->del_user->no_access;
	
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
            //delete user		
    		sql($DBH, "delete from tbl_login where id = ?", array($id), "count");
		}else{			
			$arr['error'] 			= "$del_user_xml";	
			die(json_encode($arr, true));
		}			
	}    
	
?>