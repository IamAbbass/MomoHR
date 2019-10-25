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
if($_GET){
  $id = $_GET['id'];
  //die(json_encode($id));
  $row = sql($DBH,"delete from tbl_add_project where id=?",array($id),"rows");
  if(!isset($row)){
    $_SESSION['info']= "Project has been Deleted";
    }
  else{
    $_SESSION['msg']= "Something Wrong! Project isn't Deleted Try Again";
  }
  redirect("projects.php");

}
$row_id = $_POST['id'];
$project_name = $_POST['proj_name'];
if($row_id and $project_name){
//die(json_encode($_POST));
$row = sql($DBH,"Update tbl_add_project set project_name=?, date_time=? where id=?",array($project_name,time(),$row_id),"rows");
if(!isset($row)){
  $_SESSION['info']= "Project has been Updated";
  }
else{
  $_SESSION['msg']= "Something Wrong! Project isn't Updated Try Again";
}
redirect("projects.php");
}
//die(json_encode($_SESSION));
  ?>
