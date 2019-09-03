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

    $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where employee_id = ? and (date_time > ? or status_date_time > ?)",
    array($SESS_DEVICE_ID,$timestamp,$timestamp), "rows");
    foreach($rows as $row){
        $arr["expense"][$i]['id']                  = $row['id'];
        $arr["expense"][$i]['title']               = $row['title'];
        $arr["expense"][$i]['amount']              = $row['amount'];
        $arr["expense"][$i]['attachment']          = $row['attachment'];
        $arr["expense"][$i]['date_time']           = $row['date_time'];
        $arr["expense"][$i]['status']              = $row['status'];
        $arr["expense"][$i]['status_date_time']    = $row['status_date_time'];
        $arr["expense"][$i]['reason']              = $row['reason'];

        if($row['date_time'] >= $timestamp_new){
            $timestamp_new = $row['date_time'];
        }
        if($row['status_date_time'] >= $timestamp_new){
            $timestamp_new = $row['status_date_time'];
        }
        $i++;
    }
    $arr['timestamp_new'] = $timestamp_new;
    echo json_encode($arr, true);
?>
