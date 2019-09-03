<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
	
	//$input 	= json_encode($_REQUEST, true);
    //sql($DBH, "INSERT into tbl_debug_app (data) values (?)", array($input), "rows");				
    
    $rows = sql($DBH, "SELECT * FROM tbl_debug_app", array(), "rows");							
    foreach($rows as $row){
		echo $row['data'];
    }
            
?>

