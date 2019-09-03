<?php
    header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');


	function get_emp_time_off_json($employee_id,$timestamp){
		global $DBH;
		$dataArray = array($employee_id,$timestamp,$timestamp);
		$rows = sql($DBH,"select * from tbl_time_off where employee_id = ? and (date_time > ? OR update_date_time > ?)",
        $dataArray,'rows');

        $arr    = array();

        $arr['timestamp_new'] = $timestamp;
        $i      = 0;

        foreach($rows as $row){
            $arr['timeoff'][$i]['id']                      = $row['id'];
            //$arr['timeoff'][$i]['parent_id']               = $row['parent_id'];
            //$arr['timeoff'][$i]['employee_id']             = $row['employee_id'];
            $arr['timeoff'][$i]['time_off_policy']         = $row['time_off_policy'];
            $arr['timeoff'][$i]['comment']                 = $row['comment'];
            $arr['timeoff'][$i]['status']                  = $row['status'];



            if($row['time_off_from_date'] == $row['time_off_to_date']){
                $arr['timeoff'][$i]['time_off']  = date('d-M-Y', $row['time_off_from_date']);
            }else{
                $days_count = (($row['time_off_to_date']-$row['time_off_from_date'])/86400)+1;
                $arr['timeoff'][$i]['time_off']  = date('d-M-Y', $row['time_off_from_date'])." to ".date('d-M-Y', $row['time_off_to_date'])." <b>($days_count days)</b>";
            }

            if($row['date_time'] >= $arr['timestamp_new']){
                $arr['timestamp_new'] = $row['date_time'];
            }
            if($row['update_date_time'] >= $arr['timestamp_new']){
                $arr['timestamp_new'] = $row['update_date_time'];
            }



            $i++;
        }
		return $arr;
	}




	function get_employee_parent_id($employee_id){
		global $DBH;
		$dataArray = array($employee_id,'user');
		$result = sql($DBH,'select * from tbl_login where id = ? and access_level = ? ',$dataArray,'rows');
		return $result[0]['parent_id'];
	}

	if (isset($_GET['timeoff'])) {
		$employee_id      = $_GET['u_id'];
        $timestamp        = $_GET['ts'];
		echo json_encode(get_emp_time_off_json($employee_id,$timestamp));
	}


	if (isset($_GET['timeoff_add'])) {
        $employee_id          = $_GET['u_id'];
        $comment              = $_GET['comment'];
        $time_off_policy      = $_GET['policy'];
        $leave_from           = strtotime($_GET['leave_from']);
        $leave_to             = strtotime($_GET['leave_to']);
        $parent_id            = get_employee_parent_id($employee_id);

        $arr = array();
        if("a" == "a"){//all good
            $company_id = find_company_id($employee_id);

            $result =  sql($DBH,"INSERT into tbl_time_off
            (company_id,employee_id,time_off_from_date,time_off_to_date,time_off_policy,comment,date_time)
            VALUES (?,?,?,?,?,?,?)",array($company_id,$employee_id,$leave_from,$leave_to,$time_off_policy,$comment,time()),
            "rows");
            $arr['status'] = true;
            $arr['error']  = "Time off requested from ".date("d-M-Y",$leave_from)." to ".date("d-M-Y",$leave_to);
		    }else{
            $arr['status'] = false;
            $arr['error']  = "Please try again!";
		    }
        echo json_encode($arr, true);
	}else if (isset($_GET['dayoff_add'])) {
        $employee_id          = $_GET['u_id'];
        $comment              = "Day Off";
        $time_off_policy      = "vacation";
        $leave_from           = time();
        $leave_to             = time();


        $arr = array();

        $STH 			= $DBH->prepare("select count(*) FROM tbl_time_off where
        employee_id = ? and
        time_off_from_date > ?");
        //off from and to are same for day off
        $result 	   	= $STH->execute(array($employee_id,strtotime(date("d-M-Y",$leave_from))));
        $count	  	  = $STH->fetchColumn();

        if($count == 0){//all good
            $company_id = find_company_id($employee_id);
            $result =  sql($DBH,"INSERT into tbl_time_off
            (company_id,employee_id,time_off_from_date,time_off_to_date,time_off_policy,comment,date_time)
            VALUES (?,?,?,?,?,?,?)",array($company_id,$employee_id,$leave_from,$leave_to,$time_off_policy,$comment,time()),
            "rows");
            $arr['status']  = true;
            $arr['error']   = "Day off request sent for ".date("d-M-Y");
    		}else{
            $arr['status']  = false;
            $arr['error']   = "Request already sent for ".date("d-M-Y");
    		}
        echo json_encode($arr, true);
	}




 ?>
