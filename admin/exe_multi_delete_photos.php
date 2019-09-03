<?php
    
    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
	
    $access_denied_xml        = $xml->exe_multi_del->access_denied;
    $no_access_xml            = $xml->exe_multi_del->no_access;
    
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_photos'] != "true"){
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}	
	
    if(isset($_POST["id"]))
      {
        foreach($_POST["id"] as $id)
         {
          
           sql($DBH, "delete from tbl_photos where id = ?" ,array($id), "rows");
           
        }
       }
    


?>