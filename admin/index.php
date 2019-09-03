<?php
    require_once('../class_function/session.php');
    require_once('../class_function/error.php');
    require_once('../class_function/dbconfig.php');
    require_once('../class_function/function.php');
    require_once('../class_function/language.php');
    require_once('../class_function/validate.php');
    require_once('../page/title.php');
    require_once('../page/meta.php');
    require_once('../page/header.php');
    require_once('../page/menu.php');
    require_once('../page/footer.php');

    $dashboard          = $xml->dashboard->dashboard;
    $root_admin         = $xml->dashboard->root_admin;
    $admin              = $xml->dashboard->admin;
    $user               = $xml->dashboard->user;
    $welcome            = $xml->dashboard->welcome;
    $view_profile       = $xml->dashboard->view_profile;
    $platform_xml       = $xml->dashboard->platform;
    $version_xml        = $xml->dashboard->version;
    $manufacturer_xml   = $xml->dashboard->manufacturer;
    $model_xml          = $xml->dashboard->model;
    $serial_xml         = $xml->dashboard->serial;
    $uuid_xml           = $xml->dashboard->uuid;
    $never_synced       = $xml->dashboard->never_synced;
    $last_synced_xml    = $xml->dashboard->last_synced;
    $virtual_xml        = $xml->dashboard->virtual;
    $cordova_xml        = $xml->dashboard->cordova;

    $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($SESS_DEVICE_ID), "rows");
  	$sim_serial = " <small>Not Logged In Yet</small>";
  	foreach($rows as $row){
  		$sim_serial		= $row['sim_serial'];
  	}

  	$rows = sql($DBH, "SELECT * FROM tbl_device_info where login_id = ?", array($SESS_DEVICE_ID), "rows");
  	$sync_date_time = " <small>$never_synced</small>";
  	foreach($rows as $row){
  		$sync_data			= json_decode($row['data'],true);
  		$sync_date_time		= my_simple_date($row['date_time']);
  	}

  	$platform 		= $sync_data['platform'];
  	$version 		= $sync_data['version'];
  	$uuid 			= $sync_data['uuid'];
  	$cordova 		= $sync_data['cordova'];
  	$model 			= $sync_data['model'];
  	$manufacturer 	= $sync_data['manufacturer'];
  	$isVirtual 		= $sync_data['isVirtual'];
  	$serial 		= $sync_data['serial'];

  	if(strlen($platform) == 0)		{$platform = "<small>$platform_xml - </small>";}
  	if(strlen($version) == 0)		{$version = "<small>$version_xml</small>";}
  	if(strlen($uuid) == 0)			{$uuid = "<small>$uuid_xml - </small>";}
  	if(strlen($cordova) == 0)		{$cordova = "<small>$cordova_xml - </small>";}
  	if(strlen($model) == 0)			{$model = "<small>$model_xml</small>";}
  	if(strlen($manufacturer) == 0)	{$manufacturer = "<small>$manufacturer_xml - </small>";}
  	if(strlen($isVirtual) == 0)		{$isVirtual = "<small>$virtual_xml - </small>";}
  	if(strlen($serial) == 0)		{$serial = "<small>$serial_xml - </small>";}

    $last_90_days = time()-(60*60*24*90);

    $snapshot = array();

    $snapshot['absent'] = array();//done
    $snapshot['present'] = array();//done
    $snapshot['late'] = array();//done 10am (>10:15am)
    $snapshot['vacation'] = array();//done
    $snapshot['sick'] = array();//done

    //time off table
    $rows = sql($DBH,'select * from tbl_time_off where company_id = ? and time_off_from_date > ? and status = ?',
    array($SESS_COMPANY_ID,$last_90_days,"approved"),'rows');
    foreach($rows as $row){
        $from = $row['time_off_from_date'];
        $to   = $row['time_off_to_date'];
        if($from == $to){
          if($from == strtotime(date("d-M-Y"))){
            if($row['time_off_policy'] == "sick"){
              if(!in_array($row['employee_id'],$snapshot['sick']) ){
                $snapshot['sick'][] = $row['employee_id'];
              }
            }else if($row['time_off_policy'] == "vacation"){
              if(!in_array($row['employee_id'],$snapshot['vacation']) ){
                $snapshot['vacation'][] = $row['employee_id'];
              }
            }
          }
        }else{
          $days_count = (($to-$from)/86400)+1;
          $vacation_days = $from;
          for($i=0; $i<$days_count; $i++){
              if($vacation_days == strtotime(date("d-M-Y"))){
                if($row['time_off_policy'] == "sick"){
                  if(!in_array($row['employee_id'],$snapshot['sick']) ){
                    $snapshot['sick'][] = $row['employee_id'];
                  }
                }else if($row['time_off_policy'] == "vacation"){
                  if(!in_array($row['employee_id'],$snapshot['vacation']) ){
                    $snapshot['vacation'][] = $row['employee_id'];
                  }
                }                
              }
              $vacation_days += 86400; //next day
          }
        }
    }
    
    
    
    
    //start
    $employees_arr = array();
    $rows = sql($DBH, "SELECT * FROM tbl_login where company_id = ?",array($SESS_COMPANY_ID), "rows");
    foreach($rows as $row){
        $employees_arr[] = $row['id'];
    }
    
    $start_att  = strtotime(date("d-m-Y",time()));
    $end_att    = strtotime(date("d-m-Y",time()))+86399;
    
    foreach($employees_arr as $e){
        
        $STH 			= $DBH->prepare("SELECT count(*) FROM tbl_attendance where employee_id = ? and date_time >= ? and date_time <= ?");
        $result 	   	= $STH->execute(array($e,$start_att,$end_att));
        $count	     	= $STH->fetchColumn();
        if($count >= 1){    
            $snapshot['present'][] = $e;
            
            $check_late = sql($DBH,'SELECT * FROM tbl_attendance where employee_id = ? and date_time >= ? and date_time <= ?',array($e,$start_att,$end_att),'rows');
            foreach ($check_late as $row) {
                //echo $row['date_time']." > ".($start_att+(10*(60*60))+(60*15))." <br>";
                if($row['date_time'] > ($start_att+(10*(60*60))+(60*15))){
                     $snapshot['late'][] = $e;
                }                
            }
        }else{
            $snapshot['absent'][] = $e;
        }
    }
    
    //die(json_encode($snapshot, true));
    
    
    

    function get_emp_salary_hisotry($employee_id)
    {
        global $DBH;
        $datArray = array($employee_id);
        $result =  sql($DBH,'select * from tbl_salary where employee_id  = ? ORDER BY id ASC ',$datArray,'rows');
        return $result;
    }

    function get_pv_advance_salary($employee_id,$month){
        global $DBH;
        $dataArray = array($employee_id,"1");
        $data = sql($DBH,'select * from tbl_advance_salary where employee_id = ? and status = ?',$dataArray,'rows');
        $total_advance_salary = '';
        foreach ($data as $key => $value) {
            $ad_salary_date = date('m',$value['date']);
            if ($ad_salary_date == $month) {
                $total_advance_salary += $value['amount'];
            }
        }
        return $total_advance_salary;
    }


    function get_emp_payment(){

        global $DBH,$SESS_COMPANY_ID;
        $admin_emp      = get_employees_by_company();
        $curr_month     = date('m');
        $emp_over_due   = array();
        $main_arr       = array();

        foreach ($admin_emp as $key => $value) {
          $emp_h_s        =  get_emp_salary_hisotry($value['id']);
          //$emp_h_s_month  = date('M',$emp_h_s[0]['salary_month']);          
          $emp_h_s_month  = date('m',time());
          
          $emp_info =  get_employee_profile_info($value['id']);
          $emp_over_due['employee_id']          = $value['id'];
          $emp_over_due['basic_salary']         = $emp_info[0]['basic_salary'];
          $emp_over_due['salary_month']         = $emp_h_s_month;
          $emp_over_due['total_advance_salary'] = get_pv_advance_salary($value['id'],$emp_h_s_month);
          $emp_over_due['paid_salary']          = get_paid_salary($value['id']);
          $main_arr[]                           = $emp_over_due;
        }
        return $main_arr;
    }


    function get_old_employees(){
        $result = get_employees_by_company();
        $old_emp = [];
        foreach ($result as $key => $value) {
             $emp_reg_date = date('m',$value['date_time']);
             $emp_id = $value['id'];
             if (date('m') > $emp_reg_date) {
                $old_emp[] = $value;
             }
        }
        return $old_emp;
    }
    
?>
<!DOCTYPE html>
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
        <?php
        	echo $title;
        	echo $meta;
        	echo $favicon;
        ?>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
         <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />

        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/themes/grey.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <style>
            .small_pic{
                width:100%;
                border-radius:100%;
            }
            .small_user_dp{
              width:35px;
              height:35px;
              border-radius: 100% !important;
              float:left;
            }
            .refresh_btn{
                padding: 22px !important;
                font-size: 18px !important;
            }
            .list-title{
            }
            .no-margin{
                margin: 0 !important;
            }
            .store_logo, .mt-element-list .list-news.ext-1 .list-thumb{
                width:60px !important;
                height:60px !important;
            }
            .mt-widget-1{
                border:none;
            }
            .employee-box__text {
                font-size: 13px;
                text-align: left;
                position: relative;
                top: -1px;
            }
            .employee-box__name {
                font-weight: 500;
                color: #4d4d4d;
                font-size: 13px;
            }
            .employee-box--with-description .employee-box__name {
                display: inline-block;
                margin-top: 3px;
            }
            .employee-box--with-description {
                align-items: flex-start;
            }
            .employee-box__thumb {
                width: 45px;
                height: 45px;
                border-radius: 23px !important;
                margin-right: 8px;
                flex: 0 0 45px;
            }
            .employee-box {
                text-decoration: none;
                display: flex;
                line-height: normal;
                align-items: center;
            }
            .btn-row-action{
                background: transparent;
                box-shadow: none;
                font-size: 14px;
                line-height: 35px;
                margin-bottom: 10px;
                padding: 0 0.5rem;
                transition: .1s ease-out;
                border: 0px;
            }
            .row-action{
                text-align: right;
                margin-top: 15px;
            }
            .task-main{
                max-height: 500px;
                overflow-y: auto;
            }
            .leave-color-blue{
                color: #337ab7;
            }
            .leave-color-orange{
                color: #e07f0a;
            }
            .salary-payment-header{
                color: #ff0000c9;
                border: 1px solid;
                padding: 12px;
                font-weight: bold;
                box-shadow: 0px 0px 5px red;
            }
            .present-employee-main ul{
                list-style: none;
                background: #36c6d3;
                color: white;
                padding: 0px;
            }
            .present-employee-main ul li:nth-child(odd){
                background-color: #2596a0
            }

            .present-employee-main ul li{
                padding: 8px;
                font-size: 18px;
            }
            .my_popover{
                color: #fff;
            }
            .present-employee-main ul li:hover{
                background: #2f353b96;

            }
            .my_popover strong{
              display: block;
              float: left;
              width: 42px;
              text-align: center;
            }

            .ajax-loading{
                position: absolute;
                width: 100%;
                height: 100%;
                background: #ffffff85;
                z-index: 99;
                justify-content: center;
                vertical-align: middle;
                align-items: center;
                display: flex;
                font-size: 25px;
                visibility: hidden;
            }
            button[disabled]{
                color: #c0c0c0 !important;
                cursor: not-allowed;
            }
            .table th, .table td, div{
                cursor:auto !important;
                vertical-align: middle !important;
            }
        </style>

	</head>

    <!-- END HEAD -->



    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">

        <div class="page-wrapper">

            <!-- BEGIN HEADER -->

<?php

	echo $header;

?>

            <!-- END HEADER -->

            <!-- BEGIN HEADER & CONTENT DIVIDER -->

            <div class="clearfix"> </div>

            <!-- END HEADER & CONTENT DIVIDER -->

            <!-- BEGIN CONTAINER -->

            <div class="page-container">

                <!-- BEGIN SIDEBAR -->

<?php

	echo $menu;

?>

                <!-- END SIDEBAR -->

                <!-- BEGIN CONTENT -->

                <div class="page-content-wrapper">

                    <!-- BEGIN CONTENT BODY -->

                    <div class="page-content" style="background: #f9f9f9;">



                        <!-- BEGIN PAGE TITLE-->

                        <h1 class="page-title"> <i class="fa fa-tachometer"></i> <?php echo $dashboard; ?>

                            <small>
                                <span class="badge badge-success">
                                    <i style="margin:0;" class="fa fa-tag"></i>
                                    <?php echo ucfirst($SESS_ACCESS_LEVEL); ?>
                                </span>
                        	</small>

                        </h1>

                        <!-- END PAGE TITLE-->

                        <!-- END PAGE HEADER-->

                        <?php

                        $asd =  date('Y-m-d', strtotime(date('Y-m')." -1 month"));
                        if($_SESSION['msg']){
                             echo "<div class='remove_after_5 note note-info'><p>".$_SESSION['msg']."</p></div>";
                             unset($_SESSION['msg']);
                        }
                        ?>

                        <div class="row">

                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12 hidden">

                                <div class="portlet light bordered">

									<div class="portlet-title">

										<div class="caption">

											<i class="icon-microphone font-dark hide"></i>

											<span class="caption-subject font-dark"><?php echo $welcome; ?></span>

										</div>

									</div>

									<div class="portlet-body">

										<div class="row">

											<div class="col-md-12">

												<div class="mt-widget-1">

													<div style="margin:10px 0; border-radius: 0 !important;" class="mt-img">

														<a href="profile.php"><img class="small_pic" src="<?php echo $SESS_PHOTO; ?>"> </div></a>

													<div class="mt-body">

														<h3 class="mt-username"><?php echo $SESS_FULLNAME;  ?></h3>

														<p class="mt-user-title"><small><?php echo $SESS_EMAIL; ?></small></p>

														<a href="profile.php" class="btn btn-block btn-primary"><?php echo $view_profile; ?><a>

													</div>

												</div>

											</div>

										</div>

									</div>

								</div>

							</div>

                        <!-- MY TASK -->
                            <?php
                                $data_time_off = get_all_pending_time_off_history();
                            ?>

                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
                                        <div class="ajax-loading" id="time_off_ajax_loader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                                        <div class="portlet light bordered">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-thumb-tack"></i>
                                                    <span class="caption-subject font-dark">My Tasks (<?php echo count($data_time_off); ?>)</span>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="task-main">
                                                  <?php if (count($data_time_off) > 0 ) { ?>
                                                  <table class="table table-hover table-bordered table-striped" id="time_off_history_table" style="margin-bottom: 0px">
                                                       <thead>
                                                           <tr>
                                                               <th>Sno</th>
                                                               <th>Employee Name</th>
                                                               <th>Date</th>
                                                               <th>Days Count</th>
                                                               <th>Policy</th>
                                                               <th>Comment</th>
                                                               <th>Status</th>
                                                               <th>Action</th>
                                                           </tr>
                                                       </thead>
                                                   <tbody>
                                                       <?php
                                                           $i = 0;
                                                           foreach ($data_time_off as $key => $value) {
                                                             $i++;
                                                             $app_status = '';
                                                             $pen_status = '';
                                                             $dec_status = '';
                                                             $status_text = '';

                                                               if ($value['status'] == "approved") {
                                                                   $app_status = 'disabled';
                                                                   $status_text = 'Approved';
                                                               } else if ($value['status'] == "pending") {
                                                                   $pen_status = 'disabled';
                                                                   $status_text = 'Pending';
                                                               } else if ($value['status'] == "declined") {
                                                                   $dec_status = 'disabled';
                                                                   $status_text = 'Declined';
                                                               }

                                                               $days_count = (($value['time_off_to_date']-$value['time_off_from_date'])/86400)+1;
                                                        ?>
                                                      <tr>
                                                       <td><?= $i ?></td>
                                                       <td><?php echo get_employee_name($value['employee_id']); ?></td>
                                                       <td>
                                                          <?php
                                                            if($value['time_off_from_date'] == $value['time_off_to_date']){
                                                                echo date('d-M-Y', $value['time_off_from_date']);
                                                            }else{
                                                                echo date('d-M-Y', $value['time_off_from_date'])." to ".date('d-M-Y', $value['time_off_to_date']);
                                                            }
                                                          ?>
                                                        </td>
                                                       <td><?= $days_count ?></td>
                                                       <td><span class=""><?= ucfirst($value['time_off_policy']) ?></span></td>
                                                       <td><?= $value['comment'] ?></td>
                                                       <td><span class="" id="tf-status-<?=$value['id']?>"><?= $status_text ?></span></td>
                                                       <td><button id="btn-approve-<?= $value['id'] ?>" <?=  $app_status  ?> title="Approve" data-time-off-id="<?= $value['id'] ?>" class="btn btn-approve btn-xs btn-approve btn-success" type="button">
                                                           <i class="fa fa-check"></i>
                                                           Approve
                                                       </button>
                                                       <button id="btn-pending-<?= $value['id'] ?>" <?=  $pen_status  ?> title="Pending" class="btn btn-pending btn-xs btn-warning" type="button" data-time-off-id="<?= $value['id'] ?>" >
                                                           <i class="fa fa-clock-o"></i>
                                                           Pending
                                                       </button>
                                                       <button id="btn-decline-<?= $value['id'] ?>" <?=  $dec_status  ?> title="Decline" class="btn btn-decline btn-xs btn-danger" type="button" data-time-off-id="<?= $value['id'] ?>" >
                                                           <i class="fa fa-close"></i>
                                                           Decline
                                                       </button>
                                                     </td>
                                                   </tr>

                                                   <?php
                                                           }
                                                    ?>
                                                   </tbody>
                                                 </table>
                                               <?php }else{ ?>
                                                 <br>
                                                 <p class="text-center text-muted">
                                                 <i class="fa fa fa-search fa-3x"></i>
                                                 <br />
                                                 <br />
                                                 Nothing new for you.
                                                 <br />
                                                 </p>




                                               <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                            <!--dashboard start-->

                            <!-- OUT OF OFFICE -->

                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-users"></i>
                                            <span class="caption-subject font-dark"><b><?php echo date("d-M", time()) ?></b> Snapshot</span>
                                        </div>

                                    </div>

                                    <div class="portlet-body">
                                        <div class="present-employee-main">
                                            <ul>
                                                <?php
                                                  $absent_info = array();
                                                  foreach($snapshot['absent'] as $id){
                                                    $absent_info[] = get_employee_name_text($id);
                                                  }
                                                  $absent_info = implode(", ",$absent_info);
                                                  if(strlen($absent_info) == 0){
                                                    $absent_info = "Every one is at work today, cool!";
                                                  }
                                                ?>
                                                <li class="my_popover" data-toggle="popover" title="" data-container="body" data-content="<?php echo $absent_info; ?>">
                                                    <strong><?php echo count($snapshot['absent']); ?></strong>
                                                    <span>Absent</span>
                                                </li>
                                                <?php
                                                  $present_info = array();
                                                  foreach($snapshot['present'] as $id){
                                                    $present_info[] = get_employee_name_text($id);
                                                  }
                                                  $present_info = implode(", ",$present_info);
                                                  if(strlen($present_info) == 0){
                                                    $present_info = "No one is at work today!";
                                                  }
                                                ?>
                                                <li class="my_popover" data-toggle="popover" title="" data-container="body" data-content="<?php echo $present_info; ?>">
                                                    <strong><?php echo count($snapshot['present']); ?></strong>
                                                    <span>Present</span>
                                                </li>
                                                <?php
                                                  $late_info = array();
                                                  foreach($snapshot['late'] as $id){
                                                    $late_info[] = get_employee_name_text($id);
                                                  }
                                                  $late_info = implode(", ",$late_info);
                                                  if(strlen($late_info) == 0){
                                                    $late_info = "No one is late today";
                                                  }
                                                ?>
                                                <li class="my_popover" data-toggle="popover" title="" data-container="body" data-content="<?php echo $late_info; ?>">

                                                    <strong><?php echo count($snapshot['late']); ?></strong>
                                                    <span>Late <small>(After 10:15 AM)</small></span>
                                                </li>
                                                <?php
                                                  $vacation_info = array();
                                                  foreach($snapshot['vacation'] as $id){
                                                    $vacation_info[] = get_employee_name_text($id);
                                                  }
                                                  $vacation_info = implode(", ",$vacation_info);
                                                  if(strlen($vacation_info) == 0){
                                                    $vacation_info = "No one is at leave today!";
                                                  }
                                                ?>
                                                <li class="my_popover" data-toggle="popover" title="" data-container="body" data-content="<?php echo $vacation_info; ?>">
                                                    <strong><?php echo count($snapshot['vacation']); ?></strong>
                                                    <span>On Vacation</span>
                                                </li>
                                                <?php
                                                  $sick_info = array();
                                                  foreach($snapshot['sick'] as $id){
                                                    $sick_info[] = get_employee_name_text($id);
                                                  }
                                                  $sick_info = implode(", ",$sick_info);
                                                  if(strlen($sick_info) == 0){
                                                    $sick_info = "Hurray, no one is sick today!";
                                                  }
                                                ?>
                                                <li class="my_popover" data-toggle="popover" title="" data-container="body" data-content="<?php echo $sick_info; ?>">
                                                    <strong><?php echo count($snapshot['sick']); ?></strong>
                                                    <span>Sick</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>


                             <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <span class="caption-subject font-dark">
                                            <i class="fa fa-money"></i> Upcoming Salary</span>
                                    </div>
                                    <div class="portlet-body">
                                         <table id="overdue_dataTable" class="table dataTable table-hover table-bordered table-striped" style="margin-bottom: 0px">
                                            <thead>
                                                <tr>
                                                    <th>Sno</th>
                                                    <th>Employee Name</th>
                                                    <th>Advance salary</th>
                                                    <th>Month</th>
                                                    <th>Basic Salary</th>
                                                    <th>Salary Due</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                   $ps_salary =  get_emp_payment();
                                                   //yahan

                                                   $i = 1;
                                                  foreach ($ps_salary as $key => $value) {

                                                 ?>

                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td>
                                                      <a target='_blank' href='employee-profile.php?id=<?= $value['employee_id'] ?>'>
                                                        <img class='small_user_dp' src="<?= get_employee_photo($value['employee_id']) ?>" alt=''  />
                                                        <strong style='top: 6px; position: relative; left: 6px;'><?= get_employee_name_text($value['employee_id']) ?></strong>
                                                      </a>
                                                    </td>
                                                    <td>
                                                      <?php if($value['total_advance_salary'] > 0) echo "$".$value['total_advance_salary']; else { echo "$0";} ?>
                                                      <a target='_blank' href='employee-profile.php?id=<?= $value['employee_id'] ?>#tab_advance_salary'>
                                                        Details
                                                      </a>
                                                    </td>
                                                    <td><?= date('F',$value['salary_month']) ?></td>
                                                    <td><?php if($value['basic_salary'] > 0) echo "$".$value['basic_salary']; else { echo "$0";} ?></td>
                                                    <td>$<?= $value['basic_salary'] - $value['total_advance_salary'] ?></td>
                                                    <td>
                                                      <?php if($value['basic_salary'] <= 0){ ?>
                                                          <a href="employee-profile.php?id=<?= $value['employee_id'] ?>" class="btn btn-circle btn-sm btn-warning"><i class="fa fa-exclamation"></i> Add Basic Salary</a>
                                                      <?php }else{ ?>
                                                        <?php if($value['basic_salary'] - $value['total_advance_salary'] - $value['paid_salary'] > 0){ ?>
                                                          <button class="btn btn-circle btn-sm btn-primary btn-make-payment" data-employee-id="<?= $value['employee_id'] ?>"><i class="fa fa-money" ></i> Pay</button>
                                                        <?php }else{ ?>
                                                          <span class="text-success"><i class="fa fa-check"></i> Paid!</span>
                                                        <?php } ?>
                                                      <?php } ?>
                                                  </td>
                                              </tr>
                                              <?php
                                                  }

                                           ?>
                                          </tbody>
                                      </table>
                                    </div>
                                </div>
                            </div>
                            <!-- dasboard k 4 tasks yaha bajaygay hamza -->
                            <!-- TOTAL PRESENT AND ABSENT EMPPLOYEEE  -->


							<!--dashboard end-->
						</div>

                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
<?php
	echo $footer;
?>
            <!-- END FOOTER -->
        </div>
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script>
<script src="assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->

        <script>

                $(document).ready(function(){
                    setTimeout(function(){
                        $(".remove_after_5").slideUp();
                    },5000);
                });
                $('button.btn-approve').on('click',function(){
                    $time_off_id = $(this).attr('data-time-off-id');
                    $this = $(this);
                    $('#tf-status-'+$time_off_id).html("<i class='fa fa-spin fa-circle-o-notch'></i> Updating..");
                    $.ajax({
                        method:'POST',
                        url:'ajax/employee-ajax.php',
                        data:{time_off_update_request:true,status_approve:true,time_off_id:$time_off_id},
                        success:function(response){
                            $('#btn-approve-'+$time_off_id).attr('disabled','disabled');
                            $('#btn-pending-'+$time_off_id).removeAttr('disabled');
                            $('#btn-decline-'+$time_off_id).removeAttr('disabled');
                            $('#tf-status-'+$time_off_id).html('Approved');
                        }
                    });
                });

               $('button.btn-pending').on('click',function(){
                $time_off_id = $(this).attr('data-time-off-id');
                $this = $(this);
                $('#tf-status-'+$time_off_id).html("<i class='fa fa-spin fa-circle-o-notch'></i> Updating..");
                $.ajax({
                    method:'POST',
                    url:'ajax/employee-ajax.php',
                    data:{time_off_update_request:true,status_pending:true,time_off_id:$time_off_id},
                    success:function(response){
                        $('#btn-pending-'+$time_off_id).attr('disabled','disabled');
                        $('#btn-approve-'+$time_off_id).removeAttr('disabled');
                        $('#btn-decline-'+$time_off_id).removeAttr('disabled');
                        $('#tf-status-'+$time_off_id).html('Pending');
                    }
                })
               });
               $('button.btn-decline').on('click',function(){
                $time_off_id = $(this).attr('data-time-off-id');
                $this = $(this);
                $('#tf-status-'+$time_off_id).html("<i class='fa fa-spin fa-circle-o-notch'></i> Updating..");
                $.ajax({
                    method:'POST',
                    url:'ajax/employee-ajax.php',
                    data:{time_off_update_request:true,status_decline:true,time_off_id:$time_off_id},
                    success:function(response){
                        $('#btn-decline-'+$time_off_id).attr('disabled','disabled');
                        $('#btn-pending-'+$time_off_id).removeAttr('disabled');
                        $('#btn-approve-'+$time_off_id).removeAttr('disabled');
                        $('#tf-status-'+$time_off_id).html('Declined');
                    }
                })
               });

                $('.btn-make-payment').on('click',function() {
                  var x = confirm("Are you sure, you want to pay this salary?");
                  if(x){
                    var this_         = $(this);
                    var employee_id   = $(this_).attr('data-employee-id');

                    $(this_).html("<i class='fa fa-refresh fa-spin'></i> Please wait..");

                    $.ajax({
                        method:'POST',
                        url:'ajax/employee-ajax.php',
                        data:{salary:true,employee_id:employee_id},
                        success:function(response) {
                            response = $.parseJSON(response);

                            if(response.status == true){
                              $(this_).fadeOut(function(){
                                  $(this_).parent().html("<span class='text-success'><i class='fa fa-check'></i> "+response.msg+"</span>");
                              });
                            }else{
                              $(this_).fadeOut(function(){
                                $(this).parent().html("<span class='text-danger'><i class='fa fa-exclamation'></i> "+response.msg+"</span>");
                              });
                            }
                        }
                    });
                  }
                });


                $('[data-toggle="popover"]').popover({
                    trigger: 'hover',
                    placement: 'left'
                });
                // $('.dataTable').dataTable();
                $('#cm_dataTable').DataTable();
                $('#overdue_dataTable').DataTable();
        </script>
    </body>
</html>
