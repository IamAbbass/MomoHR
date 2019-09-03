<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

	$input 			= $_GET['input']; //phone
	$sim_serial 	= $_GET['sim_serial'];
    $sim_info       = $_GET['sim_info'];

	$arr    		= array();

    if(strlen($input) > 0 && strlen($sim_serial) > 0){

        $STH 	 = $DBH->prepare("SELECT count(*) FROM tbl_login WHERE contact = ? AND status = ?");
        $result  = $STH->execute(array($input,"active"));
        $count	 = $STH->fetchColumn();
        if($count == 1){

            $rows = sql($DBH, "SELECT * FROM tbl_login where contact = ?", array($input), "rows");
            foreach($rows as $row){
                //if(strlen($row['sim_serial']) == 0){//new customer

                    //if($row['app_pin_code'] == 0 || strlen($row['app_pin_code'])  == 0){
                        //login_success
                        $arr['input'] 	= $input;
    					$arr['id']		= $row['id'];
    					$arr['name']	= $row['fullname'];
    					$arr['success'] = true;
                        $arr['action']  = "login";
    					$arr['error'] 	= "Welcome, ".$row['fullname']."!";
                        $arr["photo"]   =  $row['photo'];
                        $arr["app_pin_code"]= "false";

                        if($row['access_level'] == "user"){
                            $rows1 = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($row['parent_id']), "rows");
                            foreach($rows1 as $row1){
                                $arr["office"]  =  $row1['fullname']."'s Office";
                            }
                        }else{
                            $rows1 = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($row['id']), "rows");
                            foreach($rows1 as $row1){
                                $arr["office"]  =  $row1['fullname']."'s Office";
                            }
                        }
                        
                        sql($DBH, " INSERT INTO tbl_login_log (login_id,date_time) VALUES (?,?)", 
                        array($row['id'],time()), "rows");
                    //}else{
    				/*
                    	$arr['input'] 	= $input;
    					$arr['success'] = true;
                        $arr['action']  = "pin_code";
    					$arr['error'] 	= "Please enter your pin code!";
    				*/
                    //}

                    //save all sim data
                    if(strlen($sim_info) > 0){
                        $STH 	 = $DBH->prepare("SELECT count(*) FROM tbl_sim_info WHERE employee_id = ?");
                        $result  = $STH->execute(array($row['id']));
                        $count	 = $STH->fetchColumn();
                        if($count == 1){
                            $rows = sql($DBH, "UPDATE tbl_sim_info set data = ?, date_time = ? where employee_id = ?",
                			array($sim_info,time(),$row['id']), "rows");
                        }else{
                            $rows = sql($DBH, "INSERT into tbl_sim_info (employee_id,data,date_time) values (?,?,?)", 
                			array($row['id'],$sim_info,time()), "rows");
                    	}
                    }

                    //save sim serial
                    sql($DBH, "UPDATE tbl_login set sim_serial = ? where contact = ?", array($sim_serial,$input), "rows");

                //}
            }
        }else{
            $arr['input'] 	= $input;
			$arr['success'] = false;
			$arr['error'] 	= "No account belongs with this phone number.";
    	}
    }else{
		$arr['input'] 	= $input;
		$arr['success'] = false;
		$arr['error'] 	= "Please enter your phone number!";
	}


    die(json_encode($arr, true));
?>
