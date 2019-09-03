<?php    
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');	
    

    
    if (isset($_GET["id"])) {
        $id     = $_GET["id"];
        $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($id), "rows");
		foreach ($rows as $row) {
			$mode				= $row['mode'];
        }     
        if ($mode == "advance") {
            $new_status	= "basic";
        }else{
            $new_status	= "advance";
        }
        if(strlen($mode) == 0){
            $arr['error'] = "Please try later";
        }else{
            $arr['error'] = "";
       }        
        
        $rows=sql($DBH, "UPDATE tbl_login SET mode = ? WHERE id = ?", array($new_status,$id), "rows");
            
        if($new_status == "advance"){
    		$arr['new_status']      = $new_status;
    		$arr['new_html']		= "<i class='fa fa-mobile'></i> Basic";
            $arr['id']              = $id;
    	}else{
    	    $arr['new_status']      = $new_status;
    		$arr['new_html']		= "<i class='fa fa-mobile'></i> Advance";
            $arr['id']              = $id;
    	}

        die(json_encode($arr, true));	
        
    }
    
    
	
?>