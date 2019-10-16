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

$expense_title = $_POST['expense_title'];
$expense_amount = $_POST['expense_amount'];
$expense_project = $_POST['project_name'];
$expense_slip = "expense_slip/".$_FILES['userfile']['name'];
$expense_reason = $_POST['expense_reason'];

$uploaddir = 'expense_slip/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

echo "<p>";

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "File is valid, and was successfully uploaded.\n";
} else {
   echo "Upload failed";
}

echo "</p>";
echo '<pre>';
echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";




   //die(json_encode($expense_slip));


sql($DBH,"insert into tbl_expense_refund (company_id,employee_id,title,amount,attachment,project_id,date_time,status,reason,status_date_time) values(?,?,?,?,?,?,?,?,?,?)",array($company_id,$user_id,$expense_title,$expense_amount,$expense_slip,$expense_project,time(),"pending",$expense_reason,time()),"rows");

redirect("reports.php#tab_expense");
//die(json_encode($_SESSION));
  ?>
