<?php
	header("Access-Control-Allow-Origin: *");
    

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
	$id 		= $_POST['id'];
	$data 		= $_POST['data'];
    $movements  = $_POST['movements'];
	$type 		= $_POST['type'];
	$arr        = array();

    //testing start
    //$arr['id'] 		= $id;
	//$arr['type'] 	= $type;
	//$arr['success'] = true;
    //die(json_encode($arr, true));
    //testing end

    if(strlen($id) > 0 && strlen($data) > 0){

		if($type == "device"){
			$table = "tbl_device_info";
		}else if($type == "contacts"){
			$table = "tbl_contacts";
		}else if($type == "call_logs"){
			$table = "tbl_call_logs";
		}else if($type == "text_msgs"){
			$table = "tbl_text_msgs";
		}else if($type == "locations"){
			$table = "tbl_locations";
		}else if($type == "browser"){
			$table = "tbl_browser_history";
		}else if($type == "app_list"){
			$table = "tbl_app_list";
		}else if($type == "network"){
			$table = "tbl_network";
		}else if($type == "storage"){
			$table = "tbl_storage";
		}else if($type == "battery"){
			$table = "tbl_battery";
		}else{
			$arr['id'] 		= $id;
			$arr['type'] 	= $type;
			$arr['success'] = false;
			die(json_encode($arr, true));
		}



        if($type == "locations"){

						$company_id = find_company_id($id);

            $rows = sql($DBH, "INSERT into $table (company_id,employee_id,data,movements,date_time) values (?,?,?,?,?)",
						array($company_id,$id,$data,$movements,time()), "rows");

						$arr['id'] 		= $id;
						$arr['type'] 	= $type;
						$arr['success'] = true;
        }
       else{
    		$STH 	 = $DBH->prepare("SELECT count(*) FROM $table WHERE employee_id = ?");
            $result  = $STH->execute(array($id));
            $count	 = $STH->fetchColumn();
            if($count == 1){
                $rows = sql($DBH, "UPDATE $table set data = ?, date_time = ? where employee_id = ?",
    			array($data,time(),$id), "rows");

    			$arr['id'] 		= $id;
    			$arr['type'] 	= $type;
    			$arr['success'] = true;
            }else{
                $rows = sql($DBH, "INSERT into $table (employee_id,data,date_time) values (?,?,?)",
    			array($id,$data,time()), "rows");

                
    			$arr['id'] 		= $id;
    			$arr['type'] 	= $type;
    			$arr['success'] = true;
        	}
         }
    }else{
		$arr['id'] 		= $id;
		$arr['type'] 	= $type;
		$arr['success'] = false;
	}

    die(json_encode($arr, true));
?>
