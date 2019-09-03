<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
    $arr = array();
    $i=0;
    $rows = sql($DBH, "SELECT * FROM country ", array(), "rows");
    foreach($rows as $row){
        $arr[$i]['name'] = $row['nicename'];
        $arr[$i]['code'] = $row['phonecode']; 
        $i++;
    }
    
    die(json_encode($arr));
    
    ?>