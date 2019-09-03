<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	
    
    $group_id = $_GET['g_id'];
    
    
    
    $arr = array();
    
    //$rows_groups = sql($DBH, "select * from tbl_groups where admin_id = ? and id = ?",array($SESS_ID,$group_id), "rows"); 
    $rows_groups = sql($DBH, "select * from tbl_groups where id = ?",array($group_id), "rows"); 
    
    
    foreach($rows_groups as $row){
        $arr['name']               = $row['name'];
        $arr['picture']            = $row['picture'];
        $arr['description']        = $row['description'];
        $arr['participant']        = array();
        $participant = explode(",",$row['participant']);
        $i=0;
        foreach($participant as $participant_id){              
            $rows2 = sql($DBH, "SELECT id,fullname,photo FROM tbl_login where id = ?",  array($participant_id), "rows");  
    		foreach($rows2 as $row2){
                $arr['participant'][$i]["id"]        = $row2['id'];
    			$arr['participant'][$i]["name"]      = $row2['fullname'];
                $arr['participant'][$i]["picture"]   = $row2['photo'];
                $i++;
    		}
        }
    }
    
      
    echo json_encode($arr, true);
?>