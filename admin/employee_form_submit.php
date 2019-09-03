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

	require_once('../class_function/alibaba_cloud/autoload.php');
	use OSS\OssClient;
    use OSS\Core\OssException;

	/****************************** EMPLOYEE ************************************/

    function update_employee_photo($employee_id,$file)
    {
        global $DBH,$accessKeyId,$accessKeySecret,$endpoint,$bucket;
        if ($_FILES['img']['size'] !== 0) {
             $arr= array();
            if($_FILES["img"]["tmp_name"]){
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
                $object = "attachments/".$_FILES['img']['name'];
                try{
                    $result = $ossClient->uploadFile($bucket, $object, $_FILES['img']['tmp_name']);
                    $file_url            = $result['info']['url'];
                    $dataArray =  array($file_url,$employee_id);
                    $data =   sql($DBH, "UPDATE tbl_login set photo = ?  where id =? ",$dataArray, "rows");
                    return $data;
                    // die(json_encode($arr));
                } catch(OssException $e) {
                    $arr['error']   = print_r($e->getMessage(),true);
                    die(json_encode($arr));
                }
            }
        }
    }

    if (isset($_POST['btn_update_profile'])) {
    	$employee_id    = $_POST['employee_id'];
        $employee_name  = $_POST['fullname'];
        $contact        = $_POST['country_code'].$_POST['contact'];
        $email          = $_POST['email'];
        $dob            = strtotime($_POST['dob']);

        //duplicate email
        if(strlen($email) > 0){
    		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where email = ? and id != ?");
    		$result 	   	= $STH->execute(array($email,$employee_id));
    		$count_email	= $STH->fetchColumn();
        }else{
            $count_email    = 0; //to clear the check
        }

        //duplicate phone
		$STH 			= $DBH->prepare("select count(*) FROM tbl_login where contact = ? and id != ?");
		$result 	   	= $STH->execute(array($contact,$employee_id));
		$count_contact	= $STH->fetchColumn();

        //validate duplicate
        if($count_email == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml </strong> $this_email_xml '$email' <strong>$already_exists_xml</strong>!";
			redirect("employee-profile.php?id=".$employee_id);
		}else if($count_contact == 1){
			$_SESSION['msg'] = "<strong>$sorry_but_xml </strong> $this_phone_xml '$contact' <strong>$already_exists_xml</strong>!";
			redirect("employee-profile.php?id=".$employee_id);
		}else{

            //password logic
            $pass         = strip_tags($_POST['password']);
            if(strlen($pass) > 0){
                $md5_token    = md5($login_token);
                $password     = md5(md5(md5($md5_token) . md5($pass)) . md5($md5_token));
                sql($DBH,'UPDATE tbl_login set password = ?, pass_token = ?
                where id = ? ',array($password,$md5_token,$employee_id),'rows');
            }

            //profile update
            $dataArray = array($_POST['birth_place'],$_POST['gender'],
                $_POST['nationality'],$_POST['nrc'],$_POST['religion'],$_POST['fathers_name'],
                $_POST['mothers_name'],$_POST['address'],$_POST['city'],$_POST['state'],
                $_POST['work_phone'],$_POST['personal_phone'],
                $_POST['education'],$_POST['job_description'],$_POST['basic_salary'],$_POST['bonus'],
                $employee_id);

            $data =   sql($DBH, "UPDATE tbl_employee_profile set birth_place = ?,
                gender = ?,nationality = ?,nrc =?,
                religion=?,fathers_name = ?,mothers_name = ?,
                address=?,city=?,state=?,work_phone = ?,
                personal_phone = ?,
                education =?,job_description =?,basic_salary =?,bonus =  ? where employee_id =? ",$dataArray, "rows");
            
            /*
            echo "
            UPDATE tbl_employee_profile set birth_place = '',
                gender = '',nationality = '',nrc ='',
                religion='',fathers_name = '',mothers_name = '',
                address='',city='',state='',work_phone = '',
                personal_phone = '',
                education ='',job_description ='',basic_salary ='',bonus = '' where employee_id = ''";
                die("");
              */  
                
            
            //update login table
            sql($DBH,'UPDATE tbl_login set fullname = ?, email = ?, contact = ?, dob = ? , update_at = ?
            where id = ? ',array($employee_name,$email,$contact,$dob,time(),$employee_id),'rows');


            $_SESSION['info'] = "Profile updated successfully";
            redirect("employee-profile.php?id=".$employee_id);
        }
    }


    /*************************** DOCUMENTS *********************************/
     function add_employee_document($employee_id,$file_url,$file_name,$notes)
	    {
	        global $DBH;
	        $dataArray = array($employee_id,$file_name,$notes,$file_url,strtotime(date('m/d/Y H:i:s')),'1');
	        $data =  sql($DBH, "insert into tbl_documents (employee_id,filename,notes,file_path,uploaded_at,status) values (?,?,?,?,?,?);",$dataArray, "rows");
	        if ($data) {
	        	return true;
	        }

	    }
	    function update_documents($document_id,$employee_id)
	    {
	    	global $DBH,$accessKeyId,$accessKeySecret,$endpoint,$bucket;
	    	if ($_FILES['file_edited_document']['size'] !== 0) {
	    		$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

	            $object = "attachments/".$_FILES['file_edited_document']['name'];

	            try{

	                $result = $ossClient->uploadFile($bucket, $object, $_FILES['file_edited_document']['tmp_name']);
	                $file_url            = $result['info']['url'];
	                // die(json_encode($arr));
	            } catch(OssException $e) {
	                $arr['error']   = print_r($e->getMessage(),true);
	                die(json_encode($arr));
	            }
	    	}
	    	else{
	    		$file_url = $_POST['txt_file_pervious_path'];
	    	}
	    	// echo $file_url;
	    	$dataArray =  array($_POST['txt_edited_document_file_name'],$_POST['txt_edited_document_notes'],strtotime(date('m/d/Y H:i:s')),$file_url,$document_id);
	    	 sql($DBH, "UPDATE tbl_documents set filename = ?,notes = ?,updated_at =?, file_path = ?  where id =? ",$dataArray, "rows");
	    	 header("Location: employee-profile.php?id=".$employee_id.'#tab_documents');
	    	  $_SESSION['info'] = 'Document updated successfully';

	    }
    if (isset($_POST['btn_add_document'])) {

       $file_name = $_POST['txt_document_file_name'];
       $notes = $_POST['txt_document_notes'];
       $employee_id = $_POST['employee_id'];
       $admin_id = $_SESSION['SESS_ID'];
       // print_r($_POST);
         $arr= array();
        if($_FILES["file_document"]["tmp_name"]){

            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            $object = "attachments/".$_FILES['file_document']['name'];

            try{

                $result = $ossClient->uploadFile($bucket, $object, $_FILES['file_document']['tmp_name']);
                $file_url            = $result['info']['url'];
                // print_r($file_url);
                $result =  add_employee_document($employee_id,$file_url,$admin_id,$file_name,$notes);
                header('Location: employee-profile.php?id='.$employee_id.'#tab_documents');
                 $_SESSION['info'] = 'Document added successfully';
                // die(json_encode($arr));
            } catch(OssException $e) {
                $arr['error']   = print_r($e->getMessage(),true);
                die(json_encode($arr));
            }

        }

    }

    if (isset($_POST['btn_edit_document'])) {
    	$document_id = $_POST['txt_file_document_id'];
    	$employee_id = $_POST['employee_id'];
    	$result = update_documents($document_id,$employee_id);
      }


     /**************** ASSETS *******************/
    function add_employee_assets($employee_id,$admin_id)
    {
        global $DBH, $SESS_COMPANY_ID;

        $assets_name = $_POST['txt_assets_name'];
				$assets_category = $_POST['txt_assets_category'];
				$assets_textarea = $_POST['txt_assets_textarea'];
				$assets_serial = $_POST['txt_assets_serial'];
				$assets_worth	= $_POST['assets_worth'];

				if(strlen($_POST['txt_assets_given_date']) > 0){
					$assets_given_date = strtotime($_POST['txt_assets_given_date']);
				}else{
					$assets_given_date = time();
				}


        $dataArray = array ($SESS_COMPANY_ID,$admin_id,$employee_id,$assets_name,$assets_worth,$assets_category,$assets_serial);
        $data =  sql($DBH, "insert into tbl_assets (company_id,admin_id,employee_id,assets_name,worth,category,serial)
				values (?,?,?,?,?,?,?);",$dataArray, "rows");


				$assets_id  = $DBH->lastInsertId();
		    $comment    = $assets_textarea;
		    sql($DBH,"INSERT INTO tbl_assets_history (emp_id,assets_id,date,action,comment) VALUES (?,?,?,?,?)",
		    array($employee_id,$assets_id,time(),1,$comment),'rows');

				header('Location: employee-profile.php?id='.$employee_id.'#tab_assets');
        $_SESSION['info'] = 'Assets added successfully';
    }

    if (isset($_POST['btn_add_assets'])) {
    	$admin_id = $_SESSION['SESS_ID'];
    	$employee_id = $_POST['employee_id'];
	    $result =  add_employee_assets($employee_id,$admin_id);
	    // print_r($_POST);
    }


     /**************** MANAGE VACATIONS AND LEAVE *******************/
	function update_vacations($vacations)
	{
		global $DBH;

		$dataArray = array('99999999999');
		$data = sql($DBH,'SELECT * FROM tbl_leaves WHERE id <> ?',$dataArray,'rows');

		$dataArray = array($vacations);
		if (count($data) > 0) {
			sql($DBH,'UPDATE tbl_leaves set vacations= ? ',$dataArray,'rows');
		}
		else{
			sql($DBH,'INSERT INTO tbl_leaves (vacations) VALUES(?) ',$dataArray,'rows');
		}
	}
	function update_sicks($sick_days)
	{
		global $DBH;

		$dataArray = array('99999999999');
		$data = sql($DBH,'SELECT * FROM tbl_leaves WHERE id <> ?',$dataArray,'rows');

		$dataArray = array($sick_days);
		if (count($data) > 0) {
			sql($DBH,'UPDATE tbl_leaves set sick_days= ? ',$dataArray,'rows');
		}
		else{
			sql($DBH,'INSERT INTO tbl_leaves (sick_days) VALUES(?) ',$dataArray,'rows');
		}
	}

	if (isset($_POST['btn_update_vacations'])) {
		update_vacations($_POST['txt_vacations']);
		header('Location: leaves.php');
        $_SESSION['info'] = 'Vacations updated successfully';
	}

	if (isset($_POST['btn_update_sick'])) {
		update_sicks($_POST['txt_sick']);
		header('Location: leaves.php');
        $_SESSION['info'] = 'Sick days updated successfully';
	}


    /************ TIME OFF BY ADMIN*****************/



     if (isset($_POST['btn_add_time_off_admin'])) {
         $employee_id =$_POST['employee_id'];
         $comment =$_POST['txt_time_off_comment'];
         $time_off_from_date = strtotime($_POST['txt_time_off_from_date']);
         $time_off_to_date = strtotime($_POST['txt_time_off_to_date']);
         $time_off_policy = $_POST['txt_time_off_policy'];
         //$admin_id = $_SESSION['SESS_ID'];


				 $dataArray = array($SESS_COMPANY_ID,$SESS_ID,$employee_id,$time_off_from_date,$time_off_to_date,$time_off_policy,$comment,time(),time());
         sql($DBH,'INSERT INTO tbl_time_off (company_id,admin_id,employee_id,time_off_from_date,time_off_to_date,time_off_policy,comment,date_time,update_date_time) VALUES(?,?,?,?,?,?,?,?,?)',$dataArray,'rows');



				 $_SESSION['info'] = 'Time Off added successfully';
				 header('Location: employee-profile.php?id='.$employee_id.'#tab_time_off');

     }


     if (isset($_POST['btn_add_advance_salary'])) {
         $employee_id 		= $_POST['employee_id'];
         $admin_id 				= $_SESSION['SESS_ID'];
         $date 						= strtotime($_POST['txt_advance_salary_date']);
         $amount 					= $_POST['txt_advance_salary_amount'];



				 //start
		     $rows = sql($DBH,'select basic_salary from tbl_employee_profile where employee_id = ?',array($employee_id),'rows');
		     foreach($rows as $row){
		       $basic_salary = $row['basic_salary'];
		     }
		     $rows = sql($DBH,'select sum(amount) as advance_salary from tbl_advance_salary where employee_id = ?',array($employee_id),'rows');
		     foreach($rows as $row){
		       $advance_salary = $row['advance_salary'];
		     }

             $rows = sql($DBH,'select sum(salary) as paid_salary from tbl_salary where employee_id = ?',array($employee_id),'rows');
		     foreach($rows as $row){
		       $paid_salary = $row['paid_salary'];
		     }
             
		     $remaining_salary = $basic_salary - $advance_salary - $paid_salary;
				 //end

				 if($basic_salary == 0){
					 $_SESSION['msg'] = "Please add basic salary for this employee first!";
				 }else if($amount > $remaining_salary){
					 $_SESSION['msg'] = "Basic salary limit reached, you can not add advance salary for this month!";
				 }else{
					 sql($DBH,'insert into tbl_advance_salary (company_id,admin_id,employee_id,date,amount) values(?,?,?,?,?) ',
					 array($SESS_COMPANY_ID,$admin_id,$employee_id,$date,$amount),'rows');

	                   $_SESSION['info'] = "Advance Salary Added Successfully";
				 }


				 header('Location: employee-profile.php?id='.$employee_id.'#tab_advance_salary');
     }


     // ADVANCE SALARY
     function update_advance_salary($advance_salary_id)
        {
            global $DBH;

            $amount = $_POST['txt_edit_advance_salary_amount'];
            $date = strtotime($_POST['txt_edit_advance_salary_date']);
            $dataArray =array($date,$amount,$advance_salary_id);

            sql($DBH,'UPDATE tbl_advance_salary set `date` = ? , `amount` = ? WHERE id = ? ',$dataArray,'rows');
					}

    if (isset($_POST['btn_edit_advance_salary'])) {
        // print_r($_POST);
        $employee_id = $_POST['employee_id'];
        $advance_salary_id =  $_POST['txt_advance_salary_id'];
        
        //can not update advance salary
        
        //update_advance_salary($advance_salary_id);
        // update_advance_salary($advance_salary_id);
        header('Location: employee-profile.php?id='.$employee_id.'#tab_advance_salary');
        //  $_SESSION['info'] = 'Advance salary updated successfully';
    }

    // EMPLOYEE COMPENSATION
     function add_compensation($employee_id,$admin_id)
    {
        global $DBH, $SESS_COMPANY_ID;
        $full_name =    $_POST['txt_compensation_full_name'];
        $amount     =   $_POST['txt_compensation_amount'];
        $category  =    $_POST['txt_compensation_category'];

        $dataArray = array($SESS_COMPANY_ID,$admin_id,$employee_id,$full_name,$amount,$category,time(),'1');
        $data =  sql($DBH, "insert into tbl_compensation (company_id,admin_id,employee_id,full_name,amount,category,date,status)
				values (?,?,?,?,?,?,?,?);",$dataArray, "rows");
        if ($data) {
            return true;
         }
    }

    function update_employee_compensation($compensation_id)
    {
        global $DBH;
        $full_name =  $_POST['txt_edit_compensation_full_name'];
        $amount =  $_POST['txt_edit_compensation_amount'];
        $category =  $_POST['txt_edit_compensation_category'];

        $dataArray = array($full_name,$amount,$category,$compensation_id);
        $data = sql($DBH,'UPDATE tbl_compensation set full_name = ?, amount =?, category = ? where id = ? ',$dataArray,'rows');
        if ($data) {
            return true;
        }
    }

    if (isset($_POST['btn_edit_compensation'])) {

        $result =  update_employee_compensation($_POST['txt_edit_compensation_id']);
        $employee_id = $_POST['employee_id'];
        header('Location: employee-profile.php?id='.$employee_id.'#tab_compensation');
         $_SESSION['info'] = 'Compensation updated successfully';
    }

    if (isset($_POST['btn_add_compensation'])) {
        $admin_id = $_SESSION['SESS_ID'];
        $employee_id = $_POST['employee_id'];
        $result = add_compensation($employee_id,$admin_id);
         header('Location: employee-profile.php?id='.$employee_id.'#tab_compensation');
         $_SESSION['info'] = 'Compensation added successfully';
    }
 ?>
