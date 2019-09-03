<?php
	header("Access-Control-Allow-Origin: *");
	
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');	 
    
    //add security
    
    $ch_id          = $_GET['ch_id']; 
    $group_id       = $_GET['group_id'];
    
    $rows_groups = sql($DBH, "select * from tbl_groups where admin_id = ? and id = ?",array($SESS_ID,$group_id), "rows"); 
    foreach($rows_groups as $row){        
        echo $row['participant']."<bR>";
        $participant = explode(",",$row['participant']);  
        if(in_array($ch_id,$participant)){
            //in group
            
            $index          = array_search($ch_id,$participant);
            unset($participant[$index]);   
            
            $participant = implode(",",$participant);                       
            sql($DBH, "update tbl_groups set participant = ? where id = ?",
            array($participant,$group_id), "rows");
        }else{
            //not in group 
        }
    }
?>