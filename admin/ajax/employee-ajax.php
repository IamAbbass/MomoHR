<?php

    require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');
	require_once('../../class_function/validate.php');
	require_once('../../class_function/language.php');


    function get_documents($document_id)
    {
    	global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_documents where id = ?",  array($document_id), "rows");
        return $data;
    }
     function delete_document($document_id)
    {
    	global $DBH;
		$dataArray =  array('0',$document_id);
		$data =   sql($DBH, "UPDATE tbl_documents set status = ?  where id =? ",$dataArray, "rows");
    	 if ($data) {
    	 	return true;
    	 }
    }

    function delete_compensation($compensation_id)
    {
    	global $DBH;
		$dataArray =  array('0',$compensation_id);
		$data =   sql($DBH, "UPDATE tbl_compensation set status = ?  where id =? ",$dataArray, "rows");
    	 if ($data) {
    	 	return true;
    	 }
    }
    
    function delete_advance($advance_id)
    {
    	global $DBH;
		$dataArray =  array('0',$advance_id);
		$data =   sql($DBH, "UPDATE tbl_advance_salary set status = ?  where id =? ",$dataArray, "rows");
    	 if ($data) {
    	 	return true;
    	 }
    }
    
    

    function get_employee_compensation($compensation_id)
    {
    	global $DBH;
		$dataArray =  array($compensation_id,'1');
		 $data =  sql($DBH, "SELECT * FROM tbl_compensation where id = ? and status = ?",  $dataArray, "rows");
    	 if ($data) {
    	 	return $data;
    	 }
    }

    function get_assets_history($assets_id)
    {
        global $DBH;
        $dataArray = array($assets_id);
        //$data = sql($DBH,'SELECT * FROM tbl_assets_history LEFT JOIN tbl_assets ON tbl_assets_history.`assets_id` = tbl_assets.id WHERE tbl_assets_history.`assets_id` = ? ',$dataArray,'rows');
        $data = sql($DBH,'SELECT * FROM tbl_assets_history where assets_id = ?',$dataArray,'rows');
        return $data;
    }

    function get_assets_info($assets_id)
    {
    	global $DBH;
        $dataArray = array($assets_id);
        $data = sql($DBH,'select * from tbl_assets where id = ? ',$dataArray,'rows');
        return $data;
    }

    function update_time_off_status($time_off_id)
    {
    	global $DBH;
    	if (isset($_POST['status_approve'])) {
	    	$dataArray = array('approved',time(),$time_off_id);
	    	sql($DBH,'Update tbl_time_off set status = ?, update_date_time = ? where id = ? ',$dataArray,'rows');
	    	return "approve";
    	}
    	else if (isset($_POST['status_decline'])) {
    		$dataArray = array('declined',time(),$time_off_id);
	    	sql($DBH,'Update tbl_time_off set status = ?, update_date_time = ? where id = ? ',$dataArray,'rows');
	    	return "decline";
    	}
    	else if (isset($_POST['status_pending'])) {
    		$dataArray = array('pending',time(),$time_off_id);
	    	sql($DBH,'Update tbl_time_off set status = ?, update_date_time = ? where id = ? ',$dataArray,'rows');
	    	return "pending";
    	}
    }




  //  	function get_employee_profile_info($employee_id)
  //  	{
  //  		global $DBH;
		// $dataArray =array($employee_id);
  //   	$result =  sql($DBH,'select * from tbl_employee_profile WHERE employee_id = ? ',$dataArray,'rows');
  //   	return $result;
  //  	}


	if (isset($_POST['update_document'])) {
		$document_id = $_POST['document_id'];
		echo json_encode(get_documents($document_id));
	}

	if (isset($_POST['delete_document'])) {
		$result =  delete_document($_POST['document_id']);
		if ($result) {
			json_encode(true);
		}
		else{
			json_encode(false);
		}
	}

  //assets
    if ($_GET['asset_action'] == "give" || $_GET['asset_action'] == "return") {
        $assets_id          = $_GET['assets_id'];
        $comment            = $_GET['comment'];
        $asset_user_id      = $_GET['asset_user_id'];
        
        if($_GET['asset_action'] == "give"){
            $write_status = 1;
        }else{
            $write_status = 0;
        }
        
        sql($DBH,"INSERT INTO tbl_assets_history (assets_id,emp_id,date,action,comment) VALUES (?,?,?,?,?)",
        array($assets_id,$asset_user_id,time(),$write_status,$comment),'rows');
    
        sql($DBH, "update tbl_assets set status=? where id = ?;",array($write_status,$assets_id), "rows");
        sql($DBH, "update tbl_assets set employee_id=? where id = ?;",array($asset_user_id,$assets_id), "rows");
    }
    
    
    

	if (isset($_POST['delete_compensation'])) {
		$result =  delete_compensation($_POST['compensation_id']);
		echo json_encode(true);
	}


	if (isset($_POST['delete_advance'])) {
		$result =  delete_advance($_POST['advance_id']);
		echo json_encode(true);
	}

	if (isset($_POST['update_compensation'])) {
		$result = get_employee_compensation($_POST['compensation_id']);
		echo json_encode($result);
	}



	if (isset($_POST['get_assets_history'])) {
		$assets_id  = $_POST['assets_id'];
		$result     = get_assets_history($assets_id);
		?>

		<table class="table table-borderd table-striped table-hover" id="assets_table_history">
			<thead>
				<tr>
                  <td>Sno</td>
                  <td>Action</td>
                  <td>User</td>
                  <td>Date Time</td>
                  <td>Comment</td>
                </tr>
			</thead>
			<tbody>
				<?php
				    $i = 1;
                    foreach ($result as $key => $value) {
                    if ($value['action'] == 0) {
                    	$date = '<span class="">'.date("d-M-Y H:i:s A",$value['date']).'</span>';
                    	$action = '<span class="">Returned</span>';
                    }
                    else{
                    	$date = '<span class="">'.date("d-M-Y H:i:s A",$value['date']).'</span>';
                    	$action = '<span class="">Given</span>';
                    }
                    
                    $fullname = "";
                    $rows_fullname = sql($DBH, "SELECT fullname FROM tbl_login where id = ?",array($value['emp_id']), "rows");
                    foreach($rows_fullname as $row_fullname){
                        $fullname = $row_fullname['0'];
                    }
				?>
				<tr>
                    <td><?= $i++ ?></td>                    
                    <td><?= $action ?></td>
                    <td><a target="_blank" href='employee-profile.php?id=<?= $value['emp_id'] ?>'><?= $fullname ?></a></td>
                    <td><?= $date ?></td>
                    <td><?= $value['comment'] ?></td>
				</tr>
				<?php
					}
				?>
			</tbody>
		</table>

		<?php
	}

	if (isset($_POST['time_off_update_request'])) {
		$time_off_id = $_POST['time_off_id'];
		echo update_time_off_status($time_off_id);
	}

	if (isset($_POST['get_advance_salary'])) {
		$advance_salary_id = $_POST['salary_id'];
		 $data =  get_advance_salary($advance_salary_id);
		 $data[0]['date'] = date('m/d/Y',$data[0]['date']);
		 echo json_encode($data);
	}

	if (isset($_POST['salary'])) {
		$employee_id   = $_POST['employee_id'];

    $rows = sql($DBH,'select basic_salary from tbl_employee_profile where employee_id = ?',array($employee_id),'rows');
    foreach($rows as $row){
      $basic_salary = $row['basic_salary'];
    }

    $rows = sql($DBH,'select sum(amount) as advance_salary from tbl_advance_salary where employee_id = ? and status = ?',array($employee_id,"1"),'rows');
    foreach($rows as $row){
      $advance_salary = $row['advance_salary'];
    }

    //due salary
    $salary = $basic_salary - $advance_salary;

    $arr = array();

    if($salary > 0){
      sql($DBH,'INSERT INTO tbl_salary (company_id, employee_id,salary_month,salary,date_time) VALUES
      (?,?,?,?,?);',
      array($SESS_COMPANY_ID,$employee_id,strtotime(date("d-M-Y")),$salary,time()),'rows');

      $arr['status']    = true;
      $arr['msg']       = "$".$salary." Paid!";
    }else{
      $arr['status']    = false;
      $arr['msg']       = "Advance Already Paid!";
    }

		die(json_encode($arr));
	}
 ?>
