<?php  
    session_start();    
    if(!isset($_SESSION['SESS_ID']) || (trim($_SESSION['SESS_ID']) == '')){			
        //$_SESSION["looking_for"] = "https://saegroup.us/saegroup/res".$_SERVER['REQUEST_URI'];
        //logout check admin or vendor or affiliate
        redirect('../index.php');
	}
	
    $perm = array();    
    if($SESS_ACCESS_LEVEL == "admin"){ 
		
		$rows = sql($DBH, "SELECT * FROM tbl_manage_access where id = ?", array($SESS_ID), "rows");							
        $vendor_permissions = $rows[0];
        foreach($vendor_permissions as $perm_code => $value){
            $perm_name  = permission_name($array_perm,$array_perm_text,$perm_code);                                    
            if($perm_name != null){
                $perm["$perm_code"] = $value;
            }
        }
		
    }
?>