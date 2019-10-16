<?php
    require_once('../class_function/session.php');
  	require_once('../class_function/error.php');
  	require_once('../class_function/dbconfig.php');
  	require_once('../class_function/function.php');
  	require_once('../class_function/validate.php');
  	require_once('../class_function/language.php');


$user_id = $_SESSION['SESS_ID'];
$user_name= $_SESSION['SESS_FULLNAME'];
$user_email= $_SESSION['SESS_EMAIL'];
$user_type= $_SESSION['SESS_ACCESS_LEVEL'];
$company_id= $_SESSION['SESS_COMPANY_ID'];

$project_name = $_POST['proj_name'];


sql($DBH,"insert into tbl_add_project (project_name,company_id,user_id,date_time) values(?,?,?,?)",array($project_name,$company_id,$user_id,time()),"rows");

redirect("reports.php#tab_expense");
//die(json_encode($_SESSION));
  ?>
