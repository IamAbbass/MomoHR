<?php
	require_once('class_function/session.php');
	require_once('class_function/error.php');
	require_once('class_function/dbconfig.php');
	require_once('class_function/function.php');
	require_once('class_function/language.php');

    $this_email         = $xml->signup_screen->this_email;
    $this_phone         = $xml->signup_screen->this_phone;
    $sorry_but          = $xml->signup_screen->sorry_but;
    $already_exists     = $xml->signup_screen->already_exists;
    $please_try_later   = $xml->signup_screen->please_try_later;
    $success            = $xml->signup_screen->success;
    $you_signed_up      = $xml->signup_screen->you_signed_up;
    $exclamation_mark   = $xml->login_screen->exclamation_mark;


	if(isset($_POST['signup']))
    {

        try {
			// INSERT RECORD


			$fullname	   = strip_tags($_POST['fullname']);
			$email	       = strip_tags($_POST['email']);
			$contact	   = strip_tags($_POST['country_code']).strip_tags($_POST['contact']);
            $dob	       = strip_tags($_POST['dob']);
            //$company       = strip_tags($_POST['company_name']);
            $company_name  = strip_tags($_POST['company_name']);


			$address	 = "";//strip_tags($_POST['address']);
			$city	     = "";//strip_tags($_POST['city']);
			$country	 = "";//strip_tags($_POST['country']);
			$pass		 = strip_tags($_POST['password']);
			$md5_token   = md5($login_token);
			$password 	 = md5(md5(md5($md5_token) . md5($pass)) . md5($md5_token));

			$STH 			= $DBH->prepare("select count(*) FROM tbl_login where email =  ?");
			$result 	   	= $STH->execute(array($email));
			$count_email	= $STH->fetchColumn();


			$STH 			= $DBH->prepare("select count(*) FROM tbl_login where contact =  ?");
			$result 	   	= $STH->execute(array($contact));
			$count_contact	= $STH->fetchColumn();

			if($count_email == 1){
				$_SESSION['msg'] = "<strong> $sorry_but </strong> $this_email $email <strong>$already_exists</strong>!";
				redirect('index.php');
			}else if($count_contact == 1){
				$_SESSION['msg'] = "<strong>$sorry_but </strong> $this_phone $contact <strong>$already_exists</strong>!";
				redirect('index.php');
			}else{
                sql($DBH,"INSERT INTO tbl_company (name,date_time)
				VALUES (?,?)",
				array($company_name, time()),"rows");
   	            $company_id     = $DBH->lastInsertId();
				$STH            = $DBH->prepare("SELECT count(*) FROM tbl_company WHERE id = ?");
				$result         = $STH->execute(array($company_id));
				$count          = $STH->fetchColumn();

				sql($DBH,"INSERT INTO tbl_login (fullname, dob, email, contact, address, city, country, password, pass_token, hash, date_time, access_level,company_id)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
				array($fullname, $dob, $email, $contact, $address, $city, $country, $password, $md5_token, unique_md5(), time(), "admin",$company_id));

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

				$_SESSION['info'] = "<strong>$success</strong> $you_signed_up $exclamation_mark";
				redirect('index.php');
			}

		}
		catch(PDOException $e) {
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
			$_SESSION['msg'] = "<strong>$sorry_but </strong> $please_try_later";
			redirect('index.php');

		}
	}else{
		redirect('index.php');
	}
?>
