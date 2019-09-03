<?php

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');

    $employee_id      = $_POST['employee_id'];

		if($_GET['type'] == "vacation"){
		    $allowed_sick           = $_POST['allowed_sick'];
            $allowed_vacation       = $_POST['allowed_vacation'];
            sql($DBH,"UPDATE tbl_vacation_settings set allowed_sick = ?, allowed_vacation = ? where employee_id = ?",
            array($allowed_sick,$allowed_vacation,$employee_id));
            $_SESSION['info'] = 'Vacation settings updated successfully';
	        header('Location: employee-profile.php?id='.$employee_id.'#tab_time_off');
		}else{
    		$ss_enable      = $_POST['ss_enable'];
        $ss_interval    = $_POST['ss_interval'];
        if($ss_enable != "true"){
            $ss_enable = "false";
        }



				$screenshot_settings = sql($DBH,"select count(*) from tbl_screenshot_settings where employee_id = ?", array($employee_id),"rows");
				
				if($screenshot_settings[0][0] == 0){

					sql($DBH,"insert into tbl_screenshot_settings (ss_enable,ss_interval,employee_id,date_time) values (?,?,?,?)",
	        array($ss_enable,$ss_interval,$employee_id,time()),"rows");
				}else {
					sql($DBH,"UPDATE tbl_screenshot_settings set ss_enable = ?, ss_interval = ?, date_time = ? where employee_id = ?",
	        array($ss_enable,$ss_interval,time(),$employee_id));
				}


        $_SESSION['info'] = 'Screenshot settings updated successfully';
				header('Location: employee-profile.php?id='.$employee_id.'#tab_screenshots');
		}

    exit;
?>
