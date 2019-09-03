<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
	
    $id 			= $_GET['id'];
    $new            = $_GET['new'];
    $old            = $_GET['old'];
    
    $arr = array();
    
    $rows = sql($DBH, "SELECT app_pin_code FROM tbl_login where id = ?", array($id), "rows");							
    foreach($rows as $row){                   
        if($row['app_pin_code'] == $old){
            if(strlen($new) == 4){                
                $rows = sql($DBH, "UPDATE tbl_login set app_pin_code = ? where id = ?", array($new,$id), "rows");            
                $arr['error'] 	= "Pin code saved, you will be asked this pin code every time you login!";
                $arr['status'] 	= true;
            }else{
                $arr['error'] 	= "Please enter a 4-Digit pin code!";
                $arr['status'] 	= false;
            }            
        }else{
            $arr['error'] 	= "You have entered an invalid old pin code!";
            $arr['status'] 	= false;
        }
   }
   die(json_encode($arr));
?>