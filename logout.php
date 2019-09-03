<?php
	require_once('class_function/session.php');
	require_once('class_function/error.php');
	require_once('class_function/dbconfig.php');
	require_once('class_function/function.php');
	require_once('class_function/language.php');
	
    try {
        
        logout('index.php');
        
        session_unset();
        session_destroy();
		
	}
	catch(PDOException $e) { 
		file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
		redirect('index.php?error=failed');
	}
?>