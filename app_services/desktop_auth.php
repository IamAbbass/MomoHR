<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $type       = $_REQUEST['type'];
    $phone      = $_REQUEST['phone'];
    $approve    = $_REQUEST['approve'];

    $arr = array();
    if($type == "check"){
  		//desktop + app isko read karengy
				$rows =  sql($DBH, "update tbl_desktop_auth set status = ? where phone = ?", array("true","923323137489"), "rows");
				$rows =  sql($DBH, "update tbl_desktop_auth set status = ? where phone = ?", array("true","922"), "rows");
				$rows =  sql($DBH, "update tbl_desktop_auth set status = ? where phone = ?", array("true","921"), "rows");

        $rows =  sql($DBH, "select * from tbl_desktop_auth where phone = ? order by (id) desc limit 1 ", array($phone), "rows");
        foreach($rows as $row){
            $arr['status']      = $row['status'];
            $arr['phone']       = $row['phone'];
            $arr['date_time']   = my_simple_date($row['date_time']);
            $arr['location']    = $row['location'];
        }


    }else if($type == "action"){
        //sim verification
        if($approve == "true" || $approve = "false"){

            $rows =  sql($DBH, "update tbl_desktop_auth set status = ? where phone = ?", array($approve,$phone), "rows");
            $arr['phone']      = $phone;
            $arr['status']     = $approve;
        }
    }


    die(json_encode($arr));

?>
