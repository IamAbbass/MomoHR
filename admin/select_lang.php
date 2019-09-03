<?php    
    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
    
    $lang_get = $_GET['lang'];		
	if (in_array($lang_get, $lang_arr)){		
		setcookie("lang", $lang_get, time() + (86400 * 30 * 30), "/"); // 86400 = 30 day
	}else{
		setcookie("lang", $lang_arr[0], time() + (86400 * 30 * 30), "/"); // 86400 = 30 day	
        echo "b";		} 
	header("location: index.php");
	exit;
?>



