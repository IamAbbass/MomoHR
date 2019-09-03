<?php
    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
    require_once('../../class_function/language.php');

	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}

	$expense_id     = $_GET['expense_id'];
    $action         = $_GET['action'];
	$reason			= $_GET['reason'];


    $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where id = ?",
    array($expense_id), "rows");
	  foreach($rows as $row){
        $old_status 		= $row['status'];
    }

    $arr = array();
    if($old_status == "pending"){
        if($action == "approve"){
            $arr['expense_id']  = $expense_id;
            $arr['old_status']  = $old_status;
            $arr['new_status']  = "Approved";
            $arr['date_time']   = date("M d, Y h:i A",time());
            $arr['error']       = "false";

            $rows = sql($DBH, "update tbl_expense_refund set status = ?, status_date_time = ? where id = ?",
            array("approved",time(),$expense_id), "rows");

        }else if($action == "reject"){
            $arr['expense_id']  = $expense_id;
            $arr['old_status']  = $old_status;
            $arr['new_status']  = "Rejected";
            $arr['date_time']   = date("M d, Y h:i A",time());
            $arr['error']       = "false";
            $arr['reason']		= $reason;

            $rows = sql($DBH, "update tbl_expense_refund set status = ?, reason = ?, status_date_time = ? where id = ?",
            array("rejected",$reason,time(),$expense_id), "rows");

        }else{
            $arr['expense_id']      = $expense_id;
            $arr['error']           = "Can not change status directly from $old_status to $action";
        }
    }else if($old_status == "approved"){
        if($action == "paid"){
            $arr['expense_id']      = $expense_id;
            $arr['old_status']      = $old_status;
            $arr['new_status']      = "Pay";
            $arr['date_time']       = date("M d, Y h:i A",time());
            $arr['error']           = "false";

            $rows = sql($DBH, "update tbl_expense_refund set status = ?, status_date_time = ? where id = ?",
            array("paid",time(),$expense_id), "rows");

        }else{
            $arr['expense_id']      = $expense_id;
            $arr['error']           = "Can not change status directly from $old_status to $action";
        }
    }else if($old_status == "rejected"){
        $arr['expense_id']      = $expense_id;
        $arr['error']           = "Can not change status directly from $old_status to $action";
    }else if($old_status == "paid"){
        $arr['expense_id']      = $expense_id;
        $arr['error']           = "Can not change status directly from $old_status to $action";
    }else{
        $arr['expense_id']      = $expense_id;
        $arr['error']           = "Please try again";
    }

    die(json_encode($arr));
