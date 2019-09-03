<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $SESS_DEVICE_ID = $_GET['u_id'];
    $timestamp      = $_GET['ts'];
    $arr = array();
    $i=0;

    $timestamp_new  = $timestamp;

    $date_old = null;

    $rows = sql($DBH, "SELECT * FROM tbl_locations where employee_id = ? and date_time > ?", 
    array($SESS_DEVICE_ID,$timestamp), "rows");
    foreach($rows as $row){

        $date_new = date("d-M-Y",$row['date_time']);

        if($date_new != $date_old) {
            $arr["location"][$i]['id'] = $row['id'];
            $arr["location"][$i]['data'] = $row['data'];
            $arr["location"][$i]['date'] = my_simple_date($row['date_time']);
            $arr["location"][$i]['status'] = "check_in"; //check_out OR day_off
        }

        //old date
        $date_old = date("d-M-Y",$row['date_time']);

        if($row['date_time'] >= $timestamp_new){
            $timestamp_new = $row['date_time'];
        }
        $i++;
    }
    $arr['timestamp_new'] = $timestamp_new;
    echo json_encode($arr, true);
?>
