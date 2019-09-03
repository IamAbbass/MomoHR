<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
    /*
    require_once('../class_function/vendor/autoload.php');
    use Twilio\Rest\Client;
    
    $client = new Client($account_sid, $auth_token);
    */                     
                                

	$input 			= $_GET['input']; //phone
    $sim_serial_1   = $_GET['sim_serial_1'];
    $sim_serial_2   = $_GET['sim_serial_2'];
    $desktop        = $_GET['desktop'];
    $location       = json_encode($_GET['location']);
   /*
    $latitude       = $_GET['latitude'];
    $longitude      = $_GET['longitude'];
    $accuracy       = $_GET['accuracy'];
   */
     
   // $location =  "Latitude: ".$latitude.","."Longitude: ".$longitude.","."Accuracy: ".$accuracy;

    
    $SMS_verification = true; //login with sms


	$arr    		= array();
    if(strlen($input) > 0){
        $STH 	 = $DBH->prepare("SELECT count(*) FROM tbl_login WHERE contact = ? AND status = ?");
        $result  = $STH->execute(array($input,"active"));
        $count	 = $STH->fetchColumn();
        if($count == 1){
            $rows = sql($DBH, "SELECT * FROM tbl_login where contact = ?", array($input), "rows");
            foreach($rows as $row){
                if($desktop == "true"){

                    $arr['input']          = $input;
					$arr['id']		= $row['id'];
					$arr['name']	= $row['fullname'];
					$arr['success']        = true;
                    $arr['action']         = "app_approval";
					$arr["app_pin_code"]   = "false";
                    
                    
                    $arr["photo"]   =  video_to_photo($row['photo']);
                    
                    $rows1 = sql($DBH, "SELECT * FROM tbl_company where id = ?", array($row['company_id']), "rows");
                    foreach($rows1 as $row1){
                        $arr["office"]  =  $row1['name'];
                    }
                    
                    sql($DBH, " INSERT INTO tbl_login_log (login_id,date_time) VALUES (?,?)", 
                    array($row['id'],time()), "rows");
                    
                   
                    
     
  
                    /*if(strlen($row['app_pin_code']) == 4){
                        $arr['error'] 	= "Please enter your pin code!";
                    }else{
                        $arr['error'] 	       = "Hey ".$row['fullname'].", let's setup a pin code for additional security!";
                    }*/
                    
                    
                    $employee_id = $row['id'];
                    $rows3 = sql($DBH, "SELECT data FROM tbl_device_info where employee_id = ?", array($employee_id), "rows");
                    foreach($rows3 as $row3){
                        $device_json = json_decode($row3['data'],true);                        
                        $device_name = $device_json['manufacturer']." ".$device_json['model']." (".$device_json['platform']." ". $device_json['version'].")";
                    }
                    
                    $arr['error'] 	= "Hey ".$row['fullname'].", MomoHR sent a notification to your <b>$device_name</b>. Tap yes on the notfication to continue";
                                        
                    $row =  sql($DBH, "insert into tbl_desktop_auth
                    (phone,date_time,location) values (?,?,?);", 
                    array($input,time(),$location), "rows");

                }else{ //mobile app
                    if(strlen($row['sim_serial']) > 0){//existing customer
                        if(($row['sim_serial']  == $sim_serial_1) || ($row['sim_serial']  == $sim_serial_2)){
        				    //sim serial matched that means user has the valid sim card
                            //if(strlen($row['app_pin_code']) < 4){
                           		//login_success
                                $arr['input'] 	= $input;
            					$arr['id']		= $row['id'];
            					$arr['name']	= $row['fullname'];
            					$arr['success'] = true;
                                $arr['action']  = "login";
            					$arr['error'] 	= "Welcome, ".$row['fullname']."!";
                                $arr["photo"]   =  video_to_photo($row['photo']);
                                $arr["app_pin_code"]= "false";

                                $rows1 = sql($DBH, "SELECT * FROM tbl_company where id = ?", array($row['company_id']), "rows");
                                foreach($rows1 as $row1){
                                    $arr["office"]  =  $row1['name'];
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
                        }else{ 
                            
                            if($SMS_verification == true){
                                $arr['input'] 	= $input;
                            	$arr['success'] = true;
                                $arr['action']  = "pin_code";//"sms_auth";
                            	$arr['error'] 	= "Sim card not matched, please enter verification code sent to $input.";
                                
                                /*
                                $code = rand(1,9).rand(0,9).rand(0,9).rand(0,9);
                                sql($DBH, "UPDATE tbl_login set app_pin_code = ? where contact = ?", array($code,$input), "rows");
                                   
    
                                $message = "$code is your verification code";
                                $test = $client->messages->create($input,
                                    array(
                                        'from' => $twilio_number,
                                        'body' => $message
                                    )
                                );*/
                            
                            }else{                                
                                $arr['input'] 	= $input;
                                $arr['id']		= $row['id'];
                                $arr['name']	= $row['fullname'];
                                $arr['success'] = true;
                                $arr['action']  = "login";
                                $arr['error'] 	= "Welcome, ".$row['fullname']."!";
                                $arr["photo"]   =  video_to_photo($row['photo']);
                                $arr["app_pin_code"]= "false";
    
                                $rows1 = sql($DBH, "SELECT * FROM tbl_company where id = ?", array($row['company_id']), "rows");
                                foreach($rows1 as $row1){
                                    $arr["office"]  =  $row1['name'];
                                } 
                                
                                sql($DBH, " INSERT INTO tbl_login_log (login_id,date_time) VALUES (?,?)", 
                                array($row['id'],time()), "rows");
                            }
                                                                            
                        }
                    }else{ //new customer
                        if($SMS_verification == true){
                            $arr['input'] 	= $input;
                        	$arr['success'] = true;
                            $arr['action']  = "pin_code";//"sms_auth";
                        	$arr['error'] 	= "Waiting for SMS verification code sent to $input.";
                            
                            /*
                            $code = rand(1,9).rand(0,9).rand(0,9).rand(0,9);
                            sql($DBH, "UPDATE tbl_login set app_pin_code = ? where contact = ?", array($code,$input), "rows");
                            */
                            
                            //$message = "$code is your verification code";
                            
                            /*
                            $test = $client->messages->create($input,
                                array(
                                    'from' => $twilio_number,
                                    'body' => $message
                                )
                            );*/
                            
                            
                        }else{
                            $arr['input'] 	= $input;
                            $arr['id']		= $row['id'];
                            $arr['name']	= $row['fullname'];
                            $arr['success'] = true;
                            $arr['action']  = "login";
                            $arr['error'] 	= "Welcome, ".$row['fullname']."!";
                            $arr["photo"]   =  video_to_photo($row['photo']);
                            $arr["app_pin_code"]= "false";
    
                            $rows1 = sql($DBH, "SELECT * FROM tbl_company where id = ?", array($row['company_id']), "rows");
                            foreach($rows1 as $row1){
                                $arr["office"]  =  $row1['name'];
                            }
                            
                            sql($DBH, " INSERT INTO tbl_login_log (login_id,date_time) VALUES (?,?)", 
                            array($row['id'],time()), "rows");
                        }                       
                        
                    }
                }
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
