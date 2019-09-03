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
    $new_admin_create_xml   = $xml->add_new_user->new_admin_create;
    $colon_xml              = $xml->add_new_user->colon;


	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}

	try {
		$fullname    = strip_tags($_POST['fullname']);
		$email	     = strip_tags($_POST['email']);
		$contact	 	 = strip_tags($_POST['country_code'].$_POST['contact']);

        $dob	       = strip_tags($_POST['dob']);
        $company_name  = strip_tags($_POST['company_name']);
		$pass		   = strip_tags($_POST['password']);

        if(strlen($pass) == 0){
            $pass		   = date("dmY",strtotime($dob));
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
			$_SESSION['msg'] = "<strong>$sorry_but_xml </strong> $this_email_xml '$email' <strong>$already_exists_xml</strong>!";
			redirect($go_back);
		}else if($count_contact == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml </strong> $this_phone_xml '$contact' <strong>$already_exists_xml</strong>!";
			redirect($go_back);
		}else{

			sql($DBH,"INSERT INTO tbl_company (name,date_time) VALUES (?,?)",
			array($company_name, time()),"rows");
			$company_id     = $DBH->lastInsertId();


			sql($DBH,"INSERT INTO tbl_login (fullname, dob, company_id, email, contact, hash, password, pass_token, date_time, access_level)
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
			array($fullname, strtotime($dob), $company_id, $email, $contact, unique_md5(), $password, $md5_token, time(), "admin"));

            //manage access
			$id = $DBH->lastInsertId();
			$STH        = $DBH->prepare("SELECT count(*) FROM tbl_manage_access WHERE id = ?");
			$result     = $STH->execute(array($id));
			$count      = $STH->fetchColumn();
			if($count == 0){
				sql($DBH,"INSERT INTO tbl_manage_access (id) VALUES (?)",array($id));
				$rows = sql($DBH, "SELECT * FROM tbl_manage_access_default", array(), "rows");
				$vendor_permissions = $rows[0];
				foreach($vendor_permissions as $perm_code => $value){
					sql($DBH,"UPDATE tbl_manage_access set $perm_code = ? where id = ?",array($value,$id));
				}
			}

            //cloud limit
        $STH        = $DBH->prepare("SELECT count(*) FROM tbl_alibaba_limits WHERE company_id = ?");
				$result     = $STH->execute(array($company_id));
				$count      = $STH->fetchColumn();
				if($count == 0){
					sql($DBH,"INSERT INTO tbl_alibaba_limits (company_id) VALUES (?)",array($company_id));
				}



			$_SESSION['info'] = "<strong>$success_xml</strong>$colon_xml $new_admin_create_xml";
			redirect("list_admins.php");
		}

	}
	catch(PDOException $e) {
		file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
		$_SESSION['msg'] = "<strong>$sorry_but_xml </strong> $please_try_later_xml";
		redirect($go_back);

	}

?>
