<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');

    $sorry_but_xml          = $xml->add_new_user->sorry_but;
    $this_email_xml         = $xml->add_new_user->this_email;
    $this_phone_xml         = $xml->add_new_user->this_phone;
    $already_exists_xml     = $xml->add_new_user->already_exists;
    $please_try_later_xml   = $xml->add_new_user->please_try_later;
    $success_xml            = $xml->add_new_user->success;
    $you_signed_up_xml      = $xml->add_new_user->you_signed_up;
    $access_denied_xml      = $xml->add_new_user->access_denied;
    $no_access_xml          = $xml->add_new_user->no_access;
    $colon_xml              = $xml->add_new_user->colon;

    function update_employee($EMP_ID){
        global $DBH;
        $dataArray = array(
            $_POST['birth_place'],
            $_POST['gender'],
            $_POST['nationality'],$_POST['nrc'],
            $_POST['religion'],$_POST['fathers_name'],$_POST['mothers_name'],
            $_POST['address'],$_POST['city'],$_POST['state'],
            $_POST['work_phone'],$_POST['personal_phone'],time(),
            $_POST['education'],$_POST['job_description'],$_POST['basic_salary'],$_POST['bonus'],
            $EMP_ID
        );

        $data =   sql($DBH, "UPDATE tbl_employee_profile set
            birth_place = ?,
            gender = ?,
            nationality = ?,nrc =?,
            religion=?,fathers_name = ?,mothers_name = ?,
            address=?,city=?,state=?,
            work_phone = ?,
            personal_phone = ?,employment_start_date =?,
            education =?,job_description =?,basic_salary =?,bonus =  ? where employee_id =? ",$dataArray, "rows");
        return $data;
    }


	//die(json_encode($_REQUEST));

	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
		if($_POST['pid']){
			$SESS_COMPANY_ID = $_POST['pid'];
		}
	}else if($SESS_ACCESS_LEVEL == "admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}

	try {
		$fullname    	 = strip_tags($_POST['fullname']);
		$email	     	 = strip_tags($_POST['email']);
		$contact	 	 = strip_tags($_POST['country_code'].$_POST['contact']);

        $dob	       = strip_tags($_POST['dob']);
        //$company_name  = strip_tags($_POST['company_name']);

		$pass		 = strip_tags($_POST['password']);
        if(strlen($pass) == 0){
            $pass = "123";
        }

		$md5_token   = md5($login_token);
		$password 	 = md5(md5(md5($md5_token) . md5($pass)) . md5($md5_token));

        if(strlen($email) > 0){
    		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where email =  ?");
    		$result 	   	= $STH->execute(array($email));
    		$count_email	= $STH->fetchColumn();
        }else{
            $count_email    = 0; //to clear the check
        }

		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where contact =  ?");
		$result 	   	= $STH->execute(array($contact));
		$count_contact	= $STH->fetchColumn();

		if($count_email == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $this_email_xml '$email' <strong>$already_exists_xml</strong>!";
			redirect($go_back);
		}else if($count_contact == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $this_phone_xml '$contact' <strong>$already_exists_xml</strong>!";
			redirect($go_back);
		}else{
            sql($DBH,"INSERT INTO tbl_login (fullname, dob, email, contact, address, city, country, password, pass_token, hash, date_time, access_level,company_id)
		      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
			  array($fullname, $dob, $email, $contact, $address, $city, $country, $password, $md5_token, unique_md5(), time(), $_POST['access_level'],$SESS_COMPANY_ID),"rows");
        	/*sql($DBH,"INSERT INTO tbl_login (fullname, dob, email, contact, hash, password, pass_token, date_time, access_level, comapny_id)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
			array($fullname, $dob, $email, $contact, unique_md5(), $password, $md5_token, time(), "user", $SESS_ID));*/
            $EMP_ID = $DBH->lastInsertId();


            $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_options where employee_id =  ?");
        	$result 	   	         = $STH->execute(array($EMP_ID));
        	$count_data_options	     = $STH->fetchColumn();
            if($count_data_options == 0){
                sql($DBH,"INSERT INTO tbl_data_interval_options (employee_id) VALUES (?);",array($EMP_ID),"rows");
            }

            $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_time where employee_id =  ?");
        	$result 	   	         = $STH->execute(array($EMP_ID));
        	$count_data_interval	 = $STH->fetchColumn();
            if($count_data_interval == 0){
                sql($DBH,"INSERT INTO tbl_data_interval_time (employee_id) VALUES (?);",array($EMP_ID),"rows");
            }

            $STH 			         = $DBH->prepare("select count(*) FROM tbl_remove_device_lock where employee_id =  ?");
        	$result 	   	         = $STH->execute(array($EMP_ID));
        	$count_data_interval	 = $STH->fetchColumn();
            if($count_data_interval == 0){
                sql($DBH,"INSERT INTO tbl_remove_device_lock (employee_id) VALUES (?);",array($EMP_ID),"rows");
            }

            $STH 			         = $DBH->prepare("select count(*) FROM tbl_screenshot_settings where employee_id =  ?");
        	$result 	   	         = $STH->execute(array($EMP_ID));
        	$count_profile	        = $STH->fetchColumn();
            if($count_profile == 0){
                sql($DBH, "insert into tbl_screenshot_settings (employee_id,date_time) values (?,?);",array($EMP_ID,time()), "rows");
            }


            $STH 			         = $DBH->prepare("select count(*) FROM tbl_vacation_settings where employee_id =  ?");
        	$result 	   	         = $STH->execute(array($EMP_ID));
        	$count_profile	        = $STH->fetchColumn();
            if($count_profile == 0){
                sql($DBH, "insert into tbl_vacation_settings (employee_id) values (?);",array($EMP_ID), "rows");
            }

            $STH 			         = $DBH->prepare("select count(*) FROM tbl_employee_profile where employee_id =  ?");
        	$result 	   	         = $STH->execute(array($EMP_ID));
        	$count_profile	        = $STH->fetchColumn();
            if($count_profile == 0){
                sql($DBH, "insert into tbl_employee_profile (employee_id) values (?);",array($EMP_ID), "rows");
            }

            //profile start
            update_employee($EMP_ID);
            //profile end

			$_SESSION['info'] = "<strong>$success_xml</strong>$colon_xml $you_signed_up_xml";
			redirect("company.php");
		}

	}
	catch(PDOException $e) {
		file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
		$_SESSION['msg'] = "<strong>$sorry_but_xml, </strong> $please_try_later_xml";
		redirect($go_back);

	}

?>
