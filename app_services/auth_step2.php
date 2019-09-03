<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
    
	$input 			= $_GET['input']; //email || phone
	$pin_code 		= $_GET['pin_code'];
    $sim_serial     = $_GET['sim_serial'];
	$arr    		= array(); 
	
    if(strlen($input) > 0 && strlen($pin_code) > 0){        
        $STH 	 = $DBH->prepare("SELECT count(*) FROM tbl_login WHERE contact = ? AND status = ?");
        $result  = $STH->execute(array($input,"active"));
        $count	 = $STH->fetchColumn();        
        if($count == 1){			
            $rows = sql($DBH, "SELECT * FROM tbl_login where contact = ?", array($input), "rows");							
            foreach($rows as $row){
			    if($row['app_pin_code'] == $pin_code){	
					//login_success
                    $arr['input'] 	= $input;						
					$arr['id']		= $row['id'];
					$arr['name']	= $row['fullname'];
					$arr['success'] = true;
                    $arr['action']  = "login"; 
					$arr['error'] 	= "Welcome, ".$row['fullname']."!";
                    $arr["photo"]   =  video_to_photo($row['photo']);
                    $arr["app_pin_code"]= "true";
                    
                    $rows1 = sql($DBH, "SELECT * FROM tbl_company where id = ?", array($row['company_id']), "rows");							
                    foreach($rows1 as $row1){
                        $arr["office"]  =  $row1['name'];
                    } 
                                        
                    //save sim
                    sql($DBH, "UPDATE tbl_login set sim_serial = ? where contact = ?", array($sim_serial,$input), "rows");
                    
				}else{
					$arr['input'] 	= $input;
					$arr['success'] = false;
					$arr['error'] 	= "Invalid Pin Code!";
				}                
            }
        }else{
            $arr['input'] 	= $input;
			$arr['success'] = false;
			$arr['error'] 	= "No account found with this email/phone number!";
    	}
    }else{
		$arr['input'] 	= $input;
		$arr['success'] = false;
		$arr['error'] 	= "Please enter your pin code!";
	}
	
    die(json_encode($arr, true));
?>