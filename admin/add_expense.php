<?php
    require_once('../class_function/session.php');
  	require_once('../class_function/error.php');
  	require_once('../class_function/dbconfig.php');
  	require_once('../class_function/function.php');
  	require_once('../class_function/validate.php');
  	require_once('../class_function/language.php');
  	require_once('../page/title.php');
  	require_once('../page/meta.php');
  	require_once('../page/header.php');
  	require_once('../page/menu.php');
    require_once('../page/footer.php');

    //alibaba start
    require_once('../class_function/alibaba_cloud/autoload.php');
    use OSS\OssClient;
    use OSS\Core\OssException;
    //alibaba end

    $tbl_attendance = array();
    $rows = sql($DBH, "SELECT * FROM tbl_attendance where company_id = ? order by (date_time) asc",
    array($SESS_COMPANY_ID), "rows");
    $i = 0;
    foreach($rows as $row){
        $e_id           = $row['employee_id'];
        $date_time      = date("D, d-M",$row['date_time']);
        $attachment     = $row['attachment'];
        //get_employee_name(2);

        $tbl_attendance[$date_time][$e_id]['attachment'] = $attachment;
        $tbl_attendance[$date_time][$e_id]['time'] = date("h:i:s A",$row['date_time']);

        /*
        <td>".get_employee_name($e_id)."</td>
        <td><a class='attachment' href='javascript:;' data-toggle='popover' title='' data-img='".$attachment."' data-original-title=''><img class='thumbnail_img' src='".$attachment."'></a></td>
        <td>".date("d-M-Y",$row['date_time'])."</td>
        <td>".date("h:i:s A",$row['date_time'])."</td>
        </tr>" ;*/

    }


    //die(json_encode($tbl_attendance));


    $users_contacts_xml          = $xml->contacts->users_contacts;
    $last_synced_xml             = $xml->contacts->last_synced;
    $never_synced_xml            = $xml->contacts->never_synced;
    $search_colon_xml            = $xml->contacts->search_colon;
    $display_name_xml            = $xml->contacts->display_name;
    $phone_number_xml            = $xml->contacts->phone_number;
    $no_data_xml                 = $xml->contacts->no_data;
    $display_name_xml            = $xml->contacts->display_name;
    $display_name_xml            = $xml->contacts->display_name;
    $no_number_xml               = $xml->contacts->no_number;
    $access_denied_xml           = $xml->contacts->access_denied;
    $no_access_xml               = $xml->contacts->no_access;


    //merge expense refund data
    $title_xml                  = $xml->attendance->title;
    $all_records_xml            = $xml->attendance->showing_all_records;
    $search_xml                 = $xml->attendance->search;
    $filter_xml                 = $xml->attendance->filter;
    $users_xml                  = $xml->attendance->users;
    $entries_xml                = $xml->attendance->entries;
    $search_colon_xml           = $xml->attendance->search_colon;
    $employee_xml               = $xml->attendance->employee;
    $action_xml                 = $xml->attendance->action;
    $location_xml               = $xml->attendance->location;
    $date_time_xml              = $xml->attendance->date_time;
    $no_data_xml                = $xml->attendance->no_data;
    $access_denied_xml          = $xml->attendance->access_denied;
    $no_access_xml              = $xml->attendance->no_access;
    $active_xml                 = $xml->attendance->active;
    $inactive_xml               = $xml->attendance->inactive;
    $block_unblock_xml          = $xml->attendance->block_unblock;
    $clear_filter_xml           = $xml->attendance->clear_filter;
    $show_records_xml           = $xml->attendance->showing_all_records_small;


    $users_location_xml          = $xml->location->users_location;
    $last_synced_xml             = $xml->location->last_synced;
    $never_synced_xml            = $xml->location->never_synced;
    $filter_xml                  = $xml->location->filter;
    $from_xml                    = $xml->location->from;
    $to_xml                      = $xml->location->to;
    $clear_xml                   = $xml->location->clear;
    $showing_last_hours_xml      = $xml->location->showing_last_hours;
    $never_xml                   = $xml->location->never;
    $locations_xml               = $xml->location->locations;
    $no_locations_xml            = $xml->location->no_locations;
    $access_denied_xml           = $xml->location->access_denied;
    $no_access_xml               = $xml->location->no_access;
    $of_xml                      = $xml->location->of;

	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_company'] != "true"){
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}

  //date filter
  $date_from  = $_GET['date_from'];
  $date_to    = $_GET['date_to'];

  if($date_from == ""){$date_from = date("Y-m-d", time());}
  if($date_to == ""){$date_to = date("Y-m-d", time());}


    //Geo Fence Code
    $polygons 	= array();
	$rows 		= sql($DBH, "SELECT * FROM tbl_geo_fence where company_id = ? ", array($SESS_COMPANY_ID), "rows");
	$sno 		= 0;

	foreach($rows as $row){

        $sno++;
		$id					= $row['id'];
		$name				= $row['name'];
		$polygon_json		= $row['polygon_json'];
		$date_time			= my_simple_date($row['date_time']);

		$points 			= array();
		$polygon_json 		= json_decode($polygon_json);
		$polygon			= array();
		$i = 0;
		foreach($polygon_json as $point){
			$point 					= explode(",",$point);
			$polygon[$i]["lat"] 	= $point[0];
			$polygon[$i]["lng"] 	= $point[1];
			$i++;
		}
		$polygons[$sno][0] = $id;
		$polygons[$sno][1] = $name;
		$polygons[$sno][2] = $polygon;
		$polygons[$sno][3] = $date_time;
	}
    //echo json_encode($polygons);
    //exit;


    $current_month_start_ts = strtotime(date("M-Y", time()))-1;


  	function get_employee_assets(){
        global $DBH,$SESS_COMPANY_ID;
        $data =  sql($DBH, "SELECT * FROM tbl_assets where company_id = ? ",  array($SESS_COMPANY_ID), "rows");
        return $data;
    }
    $employee_assets = get_employee_assets();

    //count given assets
    $rows = sql($DBH,'select count(*) as total_assets_given from tbl_assets where company_id = ? and status = ? ',array($SESS_COMPANY_ID,"1"),'rows');
    $total_assets_given = 0;
    foreach($rows as $row){
        $total_assets_given = $row['total_assets_given'];
    }

    //sum given / total
    $rows = sql($DBH,'select sum(worth) as total from tbl_assets where company_id = ?',array($SESS_COMPANY_ID),'rows');
    foreach($rows as $row){
        $total_assets_worth_total = $row['total'];
    }
    if($total_assets_worth_total == ""){
      $total_assets_worth_total = 0;
    }

    $rows = sql($DBH,'select sum(worth) as total from tbl_assets where company_id = ? and status = ? ',array($SESS_COMPANY_ID,"1"),'rows');
    foreach($rows as $row){
        $total_assets_worth_given = $row['total'];
    }
    if($total_assets_worth_given == ""){
      $total_assets_worth_given = 0;
    }

    $data_time_off =  get_all_time_off_history();

    //count aproved days
    $rows = sql($DBH,'select * from tbl_time_off where company_id = ?',array($SESS_COMPANY_ID),'rows');
    $pending_leave_count  = 0;

    $week                     = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");//for loop
    $sick_heatmap             = array();
    $vacation_heatmap         = array();
    $most_days_aways_employee = array();

    foreach($rows as $row){
        if($row['status'] == "pending"){
            $pending_leave_count += 1;
        }else if($row['status'] == "approved"){
            //count sick and vacation days
            $from = $row['time_off_from_date'];
            $to   = $row['time_off_to_date'];
            if($from == $to){
              if($row['time_off_policy'] == "sick"){
                $sick_heatmap[date("D",$from)]++;
              }else if($row['time_off_policy'] == "vacation"){
                $vacation_heatmap[date("D",$from)]++;
              }
              //count $most_days_aways_employee
              $most_days_aways_employee[$row['employee_id']] ++;
            }else{
              $days_count = (($to-$from)/86400)+1;
              $vacation_days = $from;
              for($i=0; $i<$days_count; $i++){
                  if($row['time_off_policy'] == "sick"){
                    $sick_heatmap[date("D",$vacation_days)]++;
                  }else if($row['time_off_policy'] == "vacation"){
                    $vacation_heatmap[date("D",$vacation_days)]++;
                  }
                  //count $most_days_aways_employee
                  $most_days_aways_employee[$row['employee_id']] ++;
                  $vacation_days += 86400; //next day
              }
            }
        }
    }
    //5. Compensation
    function get_employee_compensation()
    {
    	global $DBH, $SESS_COMPANY_ID;
      //die(json_encode($SESS_COMPANY_ID));


    	$dataArray =  array($SESS_COMPANY_ID,'1');
    	$data = sql($DBH,'select * from tbl_compensation where company_id = ? and status = ? ',$dataArray,'rows');
    	if ($data) {
    		return $data;
    	}
    }
    $compensation_data = get_employee_compensation();

    //count total
    $rows = sql($DBH,'select sum(amount) as total from tbl_compensation where company_id = ? and date > ? and status = ?',
    array($SESS_COMPANY_ID,$current_month_start_ts,"1"),'rows');
    foreach($rows as $row){
        $compensation_count = $row['total'];
    }


    //6. advance salary

    function get_emp_advance_salary(){
    	global $DBH,$SESS_COMPANY_ID;
    	$dataArray = array($SESS_COMPANY_ID);
    	$data = sql($DBH,'select * from tbl_advance_salary where company_id = ?',$dataArray,'rows');
    	return $data;
    }
    $advance_salary_data =  get_emp_advance_salary();

    //count sum
    //count total
    $rows = sql($DBH,'select sum(amount) as total from tbl_advance_salary where company_id = ? and date > ?',
    array($SESS_COMPANY_ID,$current_month_start_ts),'rows');
    $advance_salary_count = 0;
    foreach($rows as $row){
        $advance_salary_count = $row['total'];
    }


    //7. expense
    $rows = sql($DBH,'select sum(amount) as total from tbl_expense_refund where company_id = ? and status = ?',
    array($_SESSION['SESS_COMPANY_ID'],"pending"),'rows');
    foreach($rows as $row){
        $expense_total = $row['total'];
    }

    //die(json_encode($row['total']));
?>


<!DOCTYPE html>



<html lang="en">



    <!--<![endif]-->



    <!-- BEGIN HEAD -->



    <head>
         <style>
             .small_user_dp{
               width:35px;
               height:35px;
               border-radius: 100% !important;
               float:left;
             }

             .leave_bar{
               width:100%;
               display:block;
               border:1px solid #ddd;
               height:25px;
               background: #eee;
             }
             .leave_bar .progress_red, .leave_bar .progress_blue, .leave_bar .progress_orange{
                 height:24px;
             }
             .leave_bar span{
               font-size: 16px;
               display: block;
               position: relative;
               top: -20px;
               width: 100%;
               text-align: center;
               color: #000;
               font-weight: bold;
             }
             .leave_bar .progress_red{
                 background:#ed6b75;
             }
             .leave_bar .progress_blue{
                 background:#337ab7;
             }
             .leave_bar .progress_orange{
                 background:#F1C40F;
             }

             ul.nav.nav-tabs.tabs-left li a{
                 color: black;
                 margin:0 0 1px 0;
                 border-right:1px solid #aaa;
                 background-color: #fff !important;
             }
             ul.nav.nav-tabs.tabs-left li a:focus{
                 background-color: #ddd !important;
             }
             ul.nav.nav-tabs.tabs-left li a:focus, ul.nav.nav-tabs.tabs-left li.active a{
               border-left:1px solid #aaa;
               border-top:1px solid #aaa;
               border-bottom:1px solid #aaa;
               background-color: #fff !important;
               border-right:1px solid #fff;
               font-weight: bold;
               color:#31c7b2;
             }
             .left-tab-content-data{
                padding: 0 !important;
             }
             .right-tab-content-data{
                min-height: 476px !important;
                border-right:1px solid #aaa;
                border-top:1px solid #aaa;
                border-bottom:1px solid #aaa;
                padding:10px;
             }
             .tab-employee-img{
                border-right:1px solid #aaa;
             }
             ul.nav.nav-tabs.tabs-left li:last-child a{
             }


            .small_pic{
                width:100%;
                border-radius:100%;
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
      			.user-dp{
      				width:50px;
      				border-radius:100% !important;
      				padding:3px;
      				background:#eee;
      				border:1px solid #ccc;
      			}
            .no_padding{
                padding: 0 !important;
            }
            .tabs-left .badge{
              margin-top:0 !important;
            }
            .keyboard_movement, .mouse_movement{
                height:10px !important;
            }
            .keyboard_movement .progress-bar{
                background:#0f0;
            }
            .mouse_movement .progress-bar{
                background:#0f0;
            }

            .keyboard_movement i, .mouse_movement i{
                color:#000;
            }
        </style>
        <style>
            .popover{
                max-width: 500px;
            }
            .expense_status i{

			}
            .thumbnail_img{
                width: 50px;
                border: 1px solid #ddd;
                padding: 3px;
                border-radius: 5px !important;
                background: #fff;
            }
            .expense_status{
                cursor: pointer;
            }
            .action_opt[action='no_action']{
                color:#999;
            }
            .screenshot{
                width: 100%;
                border: 2px solid #666;
                border-radius: 5px !important;
                text-align: center;
                padding: 3px;
            }
            .thumb-image{
               float:left;
               width:50%;
               position:relative;
               padding:5px;
               height:200px;

            }
            .hvr{
              cursor:pointer;
            }
             .my-placeholder{
                 margin:0;
            }
            .view_picture{
                width: 100%;
            }
        </style>
        <style>
            .profile_image{
                width:100%;
            }
			.no_padding{
				padding:0;
			}
			select[name="country_code"]{
				border-right:none;
			}
			input[name="contact"]{
				border-left:none;
			}
        </style>


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
        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
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
			.user-dp{
				width:50px;
				border-radius:100% !important;
				padding:3px;
				background:#eee;
				border:1px solid #ccc;
			}
			.reportrange span{
				display:none;
			}
            .company-employee{
                margin: auto;
                display: block;
                color:black;
                margin-top: 20px
            }
            .company-employee:hover{
               background-color: #f9f9f9 !important;
               text-decoration: none;
               color: black
                /*display: inline-block;*
                /*padding: 15px;*/
            }
            .company-employee img{
                border-radius: 100% !important;
                width:130px;
            }
            .company-employee .name-text{
                font-size: 13px;
                font-weight: bold;
            }
            .company-employee .position-text{
                font-size: 13px
            }
            .employee-picture{
                position: relative;
            }
            .employee-picture img{
                border-radius: 100% !important;
            }
            .file-btn-main{
                background-color: #31c774;
                color: white;
                padding: 10px 11px;
                margin-bottom: 10px;
                cursor: pointer;
                border-radius: 100% !important;
                position: absolute;
                right: 0px;
                top: 0px;
                width: 35px;
                height: 35px;
            }
            .form-group.form-md-line-input{
                margin: 0 0 20px !important
            }
            .tab-employee-img{
                padding-bottom:5px;
            }
            .tab-employee-img img{
                border-radius: 100% !important;
                width: 60px;
                height: 60px;
                margin-right: 6px;
            }
            .tab-employee-img span{
                font-weight: bold;
            }
            input#file-input{
                display: none !important;
            }
            span.sick-orange{
                    color: #d5761b;
                    display: block;
            }
            span.sick-blue{
                color: #3a7dd8;
                display: block;
            }
            span.sick-green{
                color: #1A9211;
                display: block;
            }

             .document-upload > input
            {
                display: none;
            }

            .document-upload img
            {
                width: 80px;
                cursor: pointer;
            }
            .file-btn-documents{
                background-color: #31c774;
                color: white;
                padding: 8px 12px;
                margin-bottom: 10px;
                cursor: pointer;
            }
            .modal-loading {
			    background: white;
			    position: absolute;
			    z-index: 999;
			    height: 100%;
			    width: 100%;
			    justify-content: center;
			    vertical-align: middle;
			    display: flex;
			    align-items: center;
			}
			.modal-loading i{
			    display: inline-table;
				font-size: 35px;
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
                width: 50px;
                height: 50px;
                border-radius: 23px !important;
                margin-right: 8px;
                flex: 0 0 45px;
                position: relative;
                top: -15px;
            }
            .employee-box {
                text-decoration: none;
                display: flex;
                line-height: normal;
                align-items: center;
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
            .no_margin{
              margin:0 !important;
            }
            .fa img{
                width:15px;
            }
            .seperator_title_br{
                display:block
            }
            .seperator_title{
                display:block
            }
        </style>
	</head>

    <!-- END HEAD -->
    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white">
        <div class="page-wrapper">
<?php
	echo $header;
?>
    <div class="clearfix"> </div>
    <div class="page-container">
<?php
	echo $menu;
?>
                <!-- END SIDEBAR -->
                <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                  <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                      <!-- END PAGE HEADER-->
                        <?php
							if($_SESSION['msg']){
								$error = "<div class='note note-danger remove_after_5'><p>".$_SESSION['msg']."</p></div>";
								unset($_SESSION['msg']);
							}else if($_SESSION['info']){
								$error = "<div class='note note-success remove_after_5'><p>".$_SESSION['info']."</p></div>";
								unset($_SESSION['info']);
							}else{
								$error = "";
							}
							echo $error;
						?>

						<?php
						if (isset($success_msg)) {
						    echo "<div class='note note-success remove_after_5'><p>$success_ms</p></div>";
						}else if (isset($error_msg)) {
                            echo "<div class='note note-success remove_after_5'><p>$error_msg</p></div>";
						}

						?>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-2 col-sm-2 col-xs-2 left-tab-content-data">
                                            <div class="tab-employee-img">
                                              <h1 class="page-title" style="margin:0px; padding:10px;"> <i class="fa fa-file"></i> Reports</h1>
                                            </div>
                                            <ul style="border: none;" class="nav nav-tabs tabs-left">


                                                <li class="active">
                                                    <a href="#tab_assets" data-toggle="tab"><i class="fa fa-tags"></i> Assets
                                                      <?php if($total_assets_given > 0){echo "<span class='badge badge-info pull-right'>Given: ".$total_assets_given."</span>";}else{echo "<span class='badge badge-default pull-right'>Not given</span>";} ?>
                                                    </a>
                                                </li>



                                                <li>
                                                    <a href="#tab_time_off_overview" data-toggle="tab"><i class="fa fa-clock-o"></i> Time Off Overview</a>
                                                </li>
                                                <li>
                                                    <a href="#tab_time_off" data-toggle="tab"><i class="fa fa-clock-o"></i> Time Off Reports
                                                      <?php if($pending_leave_count>0){echo "<span class='badge badge-info pull-right'>Pending: ".$pending_leave_count."</span>";}else{} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_compensation" data-toggle="tab"><i class="fa fa-money"></i> Compensation
                                                      <?php if($compensation_count>0){echo "<span class='badge badge-info pull-right'>".ucfirst(date("M")).": $".$compensation_count."</span>";}else{echo "<span class='badge badge-default pull-right'>$0</span>";} ?>
                                                    </a>
                                                </li>
                                                 <li>
                                                    <a href="#tab_expense" data-toggle="tab"><i class="fa fa-money"></i>  Expense
                                                      <?php if($expense_total>0){echo "<span class='badge badge-info pull-right'>$".$expense_total."</span>";}else{} ?>
                                                    </a>
                                                </li>
                                                 <li>
                                                    <a href="#tab_screenshots" data-toggle="tab"><i class="fa fa-picture-o"></i>  Screenshots </a>
                                                </li>
                                                 <li>
                                                    <a href="#tab_attendance" data-toggle="tab"><i class="fa fa-clock-o"></i>  Attendance </a>
                                                </li>
                                                 <li>
                                                    <a class="render_map" href="#tab_locations" data-toggle="tab"><i class="fa fa-map-marker"></i>  Locations </a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="col-md-10 col-sm-10 col-xs-10 right-tab-content-data">

                                            <div class="tab-content">


                                                <div class="tab-pane active" id="tab_assets">
                                                  <div class="row">
                                                      <div class="col-md-12">
                                                        <h2 class="page-title no_margin"> <i class="fa fa-tags"></i> Assets
                                                          <small><?php if($total_assets_worth_total > 0)echo "$".$total_assets_worth_given." out of $".$total_assets_worth_total." assets given to staff"; ?></small>
                                                        </h2>
                                                      </div>
                                                  </div>
                                                  <br />


                                                  <?php

                                                    if(count($employee_assets) > 0){
                                                  ?>
                                                  <table class="table table-striped table-hover table-bordered dataTable table-assets"  id="sample_2">
                                                      <thead>
                                                          <tr>
                                                              <th>Sno</th>
                                                              <th>Employee</th>
                                                              <th>Asset Name</th>
                                                              <th>Category</th>
                                                              <th>Worth</th>
                                                              <th>Serial</th>
                                                              <th>Status</th>
                                                              <th>Action</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                        <?php

                                                        // print_r($employee_assets);
                                                        if (count($employee_assets) > 0) {
                                                          $i      = 0;

                                                          foreach ($employee_assets as $key => $value) {
                                                            $i++;
                                                         ?>
                                                          <tr>
                                                              <td><?= $i ?></td>
                                                              <td><?php echo get_employee_name($value['employee_id']); ?></td>
                                                              <td><?= $value['assets_name'] ?></td>
                                                              <td><?= $value['category'] ?></td>
                                                              <td><?php if($value['worth']>0){echo"$".$value['worth'];}else{ echo "<span class='badge badge-default'>No Defined<span>";} ?></td>
                                                              <td><?= $value['serial'] ?></td>
                                                              <td class='status' data-assets-id='<?= $value['id'] ?>'><?php if($value['status'] == 1){echo "Given";}else{echo "Returned";} ?></td>
                                                              <td>
                                                            <?php
                                                            if ($value['status'] == 0) {
                                                                  $visiblity_return = 'display:none';
                                                                  $visiblity_given = '';//show
                                                              }
                                                               else{
                                                                  $visiblity_return = '';//show
                                                                  $visiblity_given = 'display:none';


                                                                  $worth_given += $value['worth'];
                                                              }
                                                              $worth += $value['worth'];
                                                            ?>
                                                                  <a class="assests-return-btn btn btn-xs btn-success" data-emp-id="<?= $value['employee_id'] ?>" data-assets-id="<?= $value['id'] ?>" style="<?= $visiblity_return ?>"><i class="fa fa-reply"></i> Return</a>
                                                                  <a class="assests-give-btn btn btn-xs btn-success" data-emp-id="<?= $value['employee_id'] ?>" data-assets-id="<?= $value['id'] ?>" style="<?= $visiblity_given ?>"><i class="fa fa-reply"></i> Give</a>
                                                                  <a class="assests-history-btn btn btn-xs purple" data-emp-id="<?= $value['employee_id'] ?>" data-assets-id="<?= $value['id'] ?>" data-toggle="modal" href="#modal_assets_history"><i class="fa fa-history"></i> View history</a>
                                                              </td>
                                                          </tr>
                                                          <?php
                                                          }
                                                        }
                                                           ?>
                                                      </tbody>
                                                  </table>
                                                  <?php }else{ ?>
                                                    <p class="text-center text-muted">
                                                    <i class="fa fa fa-tags fa-3x"></i>
                                                    <br />
                                                    <br />
                                                    No assets added!
                                                    <br />
                                                    </p>
                                                 <?php } ?>

                                                </div>
                                                 <div class="tab-pane fade" id="tab_compensation">
                                                   <div class="row">
                                                       <div class="col-md-12">
                                                         <h2 class="page-title no_margin"> <i class="fa fa-money"></i> Compensation</h2>
                                                       </div>
                                                   </div>
                                                   <br /><br />

                                                   <?php if (count($compensation_data) > 0) { ?>


                                                      <table class="table table-striped table-hover table-bordered table-documents" id="compensation_table">
                                                        <thead>
                                                            <tr>
                                                              <th>Sno</th>
                                                              <th>Employee Name</th>
                                                              <th>Title</th>
                                                              <th>Category</th>
                                                              <th>Amount</th>
                                                              <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        	<?php
                                                        	  $i = 0;

                                                        		foreach ($compensation_data as $key => $value) {
                                                              $i++;

                                                        	 ?>

                                                            <tr id="rowCompensation<?= $value['id'] ?>">
                                                                <td><?= $i ?></td>
                                                                <td><?php echo get_employee_name($value['employee_id']); ?></td>
                                                                <td><?= $value['full_name'] ?></td>
                                                                <td><?= $value['category'] ?></td>
                                                                <td>$<?= $value['amount'] ?></td>
                                                                <td><?= date('d-M-Y',$value['date']) ?></td>
                                                            </tr>
                    	                                    <?php
                                                        	}
                    	                                    ?>

                                                        </tbody>
                                                    </table>

                                                  <?php }else{ ?>

                                                    <p class="text-center text-muted">
                                                    <i class="fa fa fa-usd fa-3x"></i>
                                                    <br />
                                                    <br />
                                                    No compensation added!
                                                    <br />
                                                    </p>

                                                  <?php } ?>
                                                </div>


                                                <div class="tab-pane fade" id="tab_expense">
                                                <div class="row">
                                                <div class="col-md-12">
                                                <h2 class="page-title no_margin"> <i class="fa fa-usd"></i> Expense</h2><hr>
                                                </div>

                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                      <h4> Add New Expense</h4>
                                                      <br>
                                                      <form class="" action="exe_add_expense.php" method="post" enctype="multipart/form-data">
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label> Expense Title: </label>
                                                            </div>
                                                            <div class="form_control col-md-8">
                                                              <input type="text" name="expense_title" value="">
                                                            </div>
                                                          </div>
                                                          <br>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label> Expense Amount: </label>
                                                            </div>
                                                            <div class="form_control col-md-8">
                                                              <input type="text" name="expense_amount" value="">
                                                            </div>
                                                          </div>
                                                          <br>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label> Expense Project: </label>
                                                            </div>
                                                            <div class="form_control col-md-8">
                                                              <select class="browser-default custom-select" name="project_name">
                                                                    <?php $menu = sql($DBH,"select * from tbl_add_project where company_id=?",array($_SESSION['SESS_COMPANY_ID']),"rows");
                                                                                foreach ($menu as $row) {
                                                                                      $value = $row;
                                                                                      echo "<option value='".$value['id']."'>".$value['project_name']."</option>";
                                                                                }
                                                                            ?>
                                                                    </select>


                                                            </div>
                                                          </div>
                                                          <br>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label> Expense Attachment: </label>
                                                            </div>
                                                            <div class="form_control col-md-8">
                                                              <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
                                                              <input type="file" name="userfile" value="">
                                                            </div>
                                                          </div>
                                                          <br>
                                                          <div class="row">
                                                            <div class="col-md-4">
                                                              <label> Expense Reason: </label>
                                                            </div>
                                                            <div class="form_control col-md-8">
                                                              <textarea name="expense_reason" rows="6" cols="50" required></textarea>
                                                            </div>
                                                          </div>
                                                            <br>
                                                            <div class="form_control col-md-12 text-center">
                                                              <input type="submit" class="btn btn-primary" name="btn_proj" value="Save">
                                                            </div>
                                                      </form>

                        						              </div>
                                                </div>


                                                </div>
                                                <div class="tab-pane fade" id="tab_screenshots">
                                                  <div class="row">
                                                      <div class="col-md-12">
                                                        <h2 class="page-title no_margin"> <i class="fa fa-picture-o"></i> Screenshots</h2>
                                                      </div>
                                                  </div>
                                                  <br />

                                                  <div class="row">
                                                    <form action="reports.php#tab_screenshots" class="form_screenshot_filter form-horizontal" method="GET">
                                                      <div style="padding-right:0;" class="col-md-2">
                                                          <?php
                                                            $rows = sql($DBH,"SELECT * FROM tbl_login where company_id = ? order by (date_time) desc",
                                                            array($SESS_COMPANY_ID),"rows");
                                                          ?>
                                                          <label>Screenshots:</label>
                                                          <select id="screenshot_user" name="u_id" class="form-control select2">
                                                              <option <?php if($_GET['u_id'] != "all"){ echo "selected";} ?> value='all'>All Users (<?php echo count($rows); ?>) </option>
                                                              <?php

                                                                  foreach($rows as $row){
                                                                      $id         = $row['id'];
                                                                      $full_name  = $row['fullname'];
                                                                      if($_GET['u_id'] == $id){
                                                                          echo "<option selected value='$id'>$full_name</option>";
                                                                      }else{
                                                                          echo "<option value='$id'>$full_name</option>";
                                                                      }

                                                                  }
                                                              ?>
                                                          </select>
                                                      </div>


                                                      <div class="col-md-8" style="padding-left:0;" >
                                                          <label>&nbsp;</label><br/>
                                                          <div class="form-body">
                                    												<input type="hidden" name="date_from" value="<?php echo $_GET['date_from']; ?>" />
                                    												<input type="hidden" name="date_to" value="<?php echo $_GET['date_to']; ?>" />
                                    												<div class="">
                                    													<div class="col-md-12">
                                    														<div class="reportrange btn default">
                                    															<i class="fa fa-calendar"></i> &nbsp;
                                    															<span> </span>
                                    															<b class="fa fa-angle-down"></b>
                                    														</div>
                                    													</div>
                                    												</div>
                                    											</div>
                                    							    </div>

                                                      <div class="col-md-2">
                                                        <label>&nbsp;</label><br/>
                                                        <button type="submit" class="screenshot_filter btn green"><i class="fa fa-filter"></i> Apply <?php echo $filter_xml; ?></button>
                                                      </div>
                                                      <div class="col-md-12">
                                                        <br />
                                                      </div>
                                                    </form>
                                                  </div>

                                                    <div class="row">
                                                        <div style="float: right; width: 100% !important;" class="col-md-12">
                                                          <?php

                                                            $date_from 	= $_GET['date_from'];
                                                						$date_to 	= $_GET['date_to'];
                                                						if(strlen($date_from) > 0 && strlen($date_to) > 0){
                                                							$show_date_from 	= strtotime($_GET['date_from'])-86400;
                                                							$show_date_to 		= strtotime($_GET['date_to']);
                                                						}else{
                                                						    $show_date_from 	= time()-86400;
                                                							$show_date_to 		= time();
                                                						}

                                                         if($_GET['u_id'] != "all" && $_GET['u_id'] != ""){//use selected
                                                             $tbl_screenshot = sql($DBH,"SELECT * FROM tbl_screenshot where company_id = ? and date_time > ? and date_time < ? and employee_id = ? order by (date_time) asc",
                                                            array($SESS_COMPANY_ID,$show_date_from,$show_date_to,$_GET['u_id']),"rows");
                                                         }else if($_GET['u_id'] == "all"){//all selected
                                                             $tbl_screenshot = sql($DBH,"SELECT * FROM tbl_screenshot where company_id = ? and date_time > ? and date_time < ? order by (date_time) asc",
                                                             array($SESS_COMPANY_ID,$show_date_from,$show_date_to),"rows");
                                                         }else{
                                                            $tbl_screenshot = sql($DBH,"SELECT * FROM tbl_screenshot where company_id = ? and date_time > ? and date_time < ? order by (date_time) asc",
                                                            array($SESS_COMPANY_ID,$show_date_from,$show_date_to),"rows");
                                                         }



                                                         if(count($tbl_screenshot) > 0){

                                                             $hrs_seperator_start = false;

                                                             foreach($tbl_screenshot as $row){
                                                                 $SEPERATE_hours = date("d-M-Y h A",$row['date_time']);

                                                                 if($SEPERATE_hours != $SEPERATE_hours_old){
                                                                     if($hrs_seperator_start == true){
                                                                         echo "<div class='clearfix'></div>";
                                                                         echo "</div>";
                                                                         $hrs_seperator_start = false;
                                                                     }

                                                                     echo "
                                                                     <div class='hrs_seperator note note-info'>
                                                                     <div class='col-md-12'>
                                                                     <h4 class='seperator_title'>$SEPERATE_hours</h4>
                                                                     </div>";
                                                                     $hrs_seperator_start = true;
                                                                 }

                                                                 $image  = $row['image'];
                                                                 $thumb  = $row['image'].$resize_w300;
                                                                 $hrs    = date("h:i A",$row['date_time']);

                                                                 echo '<div class="col-md-2">
                                                                 <img class="screenshot" src="'.$thumb.'" image="'.$image.'" alt="Screenshot at '.$hrs.'">


                                                                 <p class="my-placeholder pull-left"><strong>'.(get_employee_name($row['employee_id'])).'</strong></p>
                                                                 <p class="my-placeholder pull-right text-muted"><i class="fa fa-clock-o"></i> '.date("H:i a",$row['date_time']).'</p>


                                                                 <div class="row">
                                                                     <div class="col-md-6">
                                                                         <i class="fa fa-mouse-pointer"></i> Mouse:
                                                                         <div class="progress keyboard_movement">
                                                                             <div class="progress-bar" role="progressbar" aria-valuenow="25" style="width: 25%"  aria-valuemin="0" aria-valuemax="100"></div>
                                                                         </div>
                                                                     </div>
                                                                     <div class="col-md-6">
                                                                         <i class="fa fa-keyboard-o"></i> Keyboard:
                                                                         <div class="progress mouse_movement">
                                                                           <div class="progress-bar" role="progressbar" aria-valuenow="50" style="width: 50%"  aria-valuemin="0" aria-valuemax="100"></div>
                                                                         </div>
                                                                     </div>
                                                                   </div>
                                                                 </div>';

                                                                 $SEPERATE_hours_old = $SEPERATE_hours;
                                                             }

                                                             if($hrs_seperator_start == true){
                                                                 echo "<div class='clearfix'></div>";
                                                                 echo "</div>";
                                                                 $hrs_seperator_start = false;
                                                             }

                                             ?>
                                                        <?php }else{ ?>

                                                          <p class="text-center text-muted">
                                                          <i class="fa fa fa-picture-o fa-3x"></i>
                                                          <br />
                                                          <br />
                                                          No screenshots!
                                                          <br />
                                                          </p>

                                                        <?php } ?>

                                                        </div>


                                                         <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                                        <h4 class="modal-title"><?php echo $add_photos_xml; ?></h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form action="exe_add_photos.php" method="POST" enctype="multipart/form-data">
                                                                            <div class="row">
                        														<div class="col-md-12">
                        															<div class="form-group">
                        																<label class="control-label"><?php echo $title_colon_xml; ?> *</label>
                        																<input required type="text" name="title" class="form-control" />
                        															</div>
                        														</div>
                                                                             </div>

                        													   <div class="form-group mt-repeater">
                                                                                  <div id="wrapper" >
                                                                                     <label class="control-label"><?php echo $add_photos_colon_xml; ?> *</label>
                                                                                     <input id="fileUpload" name="file"  accept="image/*"  multiple="multiple" type="file" required />

                                                                                  </div>


                                                                              </div>
                          	                                                  <img id="image_upload_preview" class="my-placeholder" src="upload/placeholder.png" alt=""  /><br />


                                                                                <button type="submit" class="btn blue btn-md btn-fill blue btn-square" ><i class='fa fa-check'></i> <?php echo $save_xml; ?></button>
                        					                             </form>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo $close_xml; ?></button>

                                                                    </div>
                                                                </div>
                                                                <!-- /.modal-content -->
                                                            </div>
                                                                    <!-- /.modal-dialog -->
                                                        </div>
                                                    </div>
                                                 <div id="view_picture" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                                                    <div style="width: 90%" class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title"> Screenshot
                                                                    <button type="button" class="btn pull-right" data-dismiss="modal" aria-hidden="true">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <img src="" alt="" class="view_picture" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                </div>
                                  <div class="tab-pane fade" id="tab_attendance">
                                    <div class="row">
                                        <div class="col-md-12">
                                          <h2 class="page-title no_margin"> <i class="fa fa-clock-o"></i> Attendance </h2>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row"> <!-- portlet box green -->
            									<div class=""> <!-- portlet-body form -->
            										<!-- BEGIN FORM-->
            											<div class="form-body">
                                                            <form action="reports.php#tab_attendance" class="filter_attendance_filter form-horizontal" method="GET">

            												<br />
                                                            <input type="hidden" name="date_from" value="<?php echo $_GET['date_from']; ?>" />
            												<input type="hidden" name="date_to" value="<?php echo $_GET['date_to']; ?>" />
            												<div class="">
            													<div class="col-md-6">
            														<div class="reportrange btn default">
            															<i class="fa fa-calendar"></i> &nbsp;
            															<span> </span>
            															<b class="fa fa-angle-down"></b>
            														</div>
            													</div>
            													<div class="col-md-6 pull-right">
            														<button type="submit" class="attendance_filter btn green"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
            													</div>
            												</div>
                                                            </form>
            											</div>

            										<!-- END FORM-->
            									</div>
            								</div>
            							</div>
                                    </div>
                                    <br /><br />


                                    <?php
                                        $tbl_attendance = array();

                                        $rows = sql($DBH, "SELECT * FROM tbl_attendance where company_id = ?",
                                        array($SESS_COMPANY_ID), "rows");
                                        $i = 0;
                                        foreach($rows as $row){
                                            $e_id           = $row['employee_id'];
                                            $date_time      = date("D, d-M",$row['date_time']);
                                            $attachment     = $row['attachment'];
                                            //get_employee_name(2);

                                            $tbl_attendance[$date_time][$e_id]['attachment'] = $attachment;
                                            $tbl_attendance[$date_time][$e_id]['time'] = date("h:i:s A",$row['date_time']);
                                            $tbl_attendance[$date_time][$e_id]['timestamp'] = $row['date_time'];




                                        }
                                        ?>
                    										<table class="table table-striped table-bordered table-hover table-bordered" id="sample_2">
                    											<thead>
                    												<tr>
                                                                        <th>Name</th>
                                                                        <?php
                                                                            foreach($tbl_attendance as $date => $att_array){
                                                                                echo "<th>$date</th>";
                                                                            }
                                                                        ?>
                                                                    </tr>
                    											</thead>
               										           <tbody>
                                                               <tr>
                                                                    <th><?php echo get_employee_name(4); ?></th>
                                                                    <?php
                                                                        foreach($tbl_attendance as $date => $att_array){
                                                                            if(strlen($att_array[4]['time']) > 0){
                                                                                echo "<td>";
                                                                                $start_att  = strtotime(date("d-m-Y",strtotime($date)));
                                                                                if($att_array[4]['timestamp'] > ($start_att+(10*(60*60))+(60*15))){
                                                                                     echo "<span class='text-danger'>".$att_array[4]['time']."</span>";
                                                                                }else{
                                                                                     echo "<span class='text-success'>".$att_array[4]['time']."</span>";
                                                                                }
                                                                                echo "<a class='attachment' href='javascript:;' data-toggle='popover' title='' data-img='".$att_array[4]['attachment']."' data-original-title=''><img class='thumbnail_img' src='".$att_array[4]['attachment']."'></a>";
                                                                                echo "</td>";
                                                                            }else{
                                                                                echo "<td class='text-center'><br/><span class='text-muted'><i class='fa fa-2x fa-clock-o'></i><br/><br />Absent</span></td>";
                                                                            }
                                                                        }
                                                                    ?>
                                                               </tr>
                                                               <tr>
                                                                    <th><?php echo get_employee_name(5); ?></th>
                                                                    <?php
                                                                        foreach($tbl_attendance as $date => $att_array){
                                                                            if(strlen($att_array[5]['time']) > 0){
                                                                                echo "<td>";

                                                                                $start_att  = strtotime(date("d-m-Y",strtotime($date)));

                                                                                if($att_array[5]['timestamp'] > ($start_att+(10*(60*60))+(60*15))){
                                                                                     echo "<span class='text-danger'>".$att_array[5]['time']."</span>";
                                                                                }else{
                                                                                     echo "<span class='text-success'>".$att_array[5]['time']."</span>";
                                                                                }


                                                                                echo "<a class='attachment' href='javascript:;' data-toggle='popover' title='' data-img='".$att_array[5]['attachment']."' data-original-title=''><img class='thumbnail_img' src='".$att_array[5]['attachment']."'></a>";
                                                                                echo "</td>";
                                                                            }else{
                                                                                echo "<td class='text-center'><br/><span class='text-muted'><i class='fa fa-2x fa-clock-o'></i><br/><br/>Absent</span></td>";
                                                                            }
                                                                        }
                                                                    ?>

                                                               </tr>
                    											</tbody>
                    										</table>
                                  </div>
                                  <div class="tab-pane fade" id="tab_locations">
                                    <div class="row">
                                        <div class="col-md-12">
                                          <h2 class="page-title no_margin"> <i class="fa fa-map-marker"></i> Locations</h2>
                                        </div>
                                    </div>
                                    <br />


                                   <div class="row">
                                      <div class="col-md-12">
                                            <div class="portlet-body">
                                              <div id="map" style="width: 100%;">
                                                <?php
                                                  $date_from  = $_GET['date_from'];
                                                  $date_to  	= $_GET['date_to'];

                                                  if((strlen($date_from) > 0 && strlen($date_to) > 0)){
                                                    /*

                                                    $from_ts    = strtotime($date_from);
                                                    $to_ts      = strtotime($date_to);
                                                    $to_ts      = $to_ts+86400;

                                                    $rows = sql($DBH, "SELECT * FROM tbl_locations
                                                    where company_id = ? && (date_time >= ? AND date_time <= ? )",
                                                    array($SESS_COMPANY_ID,$from_ts,$to_ts), "rows");

                                                    if((strlen($date_from) > 0 && strlen($date_to) > 0)){
                                                      $filter_text = " $from_xml ".$date_from." $to_xml ".$date_to;
                                                    }
                                                    $filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_xml</a>";


                                                    $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
                                                    where company_id = ? && (date_time >= ? AND date_time <= ? )");
                                                    $result  		= $STH->execute(array($SESS_COMPANY_ID,$from_ts,$to_ts));
                                                    $count_total	= $STH->fetchColumn();
                                                    */
                                                  }else{
                                                    /*$date_from  = date("m/d/Y");
                                                    $date_to    = date("m/d/Y");
                                                    $from_ts    = strtotime($date_from);
                                                    $to_ts      = strtotime($date_to);

                                                    $from_ts    = $from_ts-86400;  //18-aug to (today is 19th)
                                                    $to_ts      = $to_ts+86400;    //20-aug



                                                    //$rows = sql($DBH, "SELECT * FROM tbl_locations
                                                    //where company_id = ? && (date_time >= ? AND date_time <= ? )",
                                                    //array($SESS_COMPANY_ID,$from_ts,$to_ts), "rows");



                                                    $rows0 = sql($DBH, "SELECT DISTINCT(employee_id) AS employee_id FROM tbl_locations
                                                    WHERE company_id = ?",
                                                    array($SESS_COMPANY_ID), "rows");



                                                    $rows = sql($DBH, "SELECT * FROM tbl_locations
                                                    where company_id = ? ",
                                                    array($SESS_COMPANY_ID), "rows");



                                                    $filter_text = "$showing_last_hours_xml"; //$showing_all_records_xml

                                                    $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
                                                    where company_id = ? && (date_time >= ? AND date_time <= ? )");
                                                    $result  		= $STH->execute(array($SESS_COMPANY_ID,$from_ts,$to_ts));
                                                    $count_total	= $STH->fetchColumn();
                                                    */
                                                  }

                                                  $locations = array();
                                                  $i=0;
                                                  $sync_date_time = "$never_xml";



                                                  $rows0 = sql($DBH, "SELECT DISTINCT(employee_id) FROM tbl_locations
                                                  where company_id = ? ",
                                                  array($SESS_COMPANY_ID), "rows");
                                                  foreach($rows0 as $row0){

                                                    $rows = sql($DBH, "SELECT * FROM tbl_locations
                                                    where employee_id = ? order by(date_time) desc limit 1",
                                                    array($row0['employee_id']), "rows");

                                                    foreach($rows as $row){
                                                      $sync_data			   = json_decode($row['data'],true);
                                                      $movements			   = $row['movements'];
                                                      $sync_date_time		 = date("d-M-Y h:i A",$row['date_time']);
                                                      $accuracy          = $sync_data['coords']['accuracy'];

                                                      if($accuracy <= 100){
                                                        $locations[$i]["date_time"] = $sync_date_time;
                                                        foreach($sync_data['coords'] as $key => $value){
                                                          $locations[$i][$key]        = $value;
                                                        }

                                                        $locations[$i]["icon"]       =  get_employee_photo($row['employee_id']);

                                                          if($movements == 0){
                                                            $movements_show= "<i class='fa'><img src='../img/sitting.png'/></i> Sitting</br>";
                                                          }else if($movements > 0 && $movements <= 10){
                                                            $movements_show= "<i class='fa'><img src='../img/walking.png'/></i> Walking</br>";
                                                          }else if($movements > 10){
                                                            $movements_show= "<i class='fa'><img src='../img/running.png'/></i> Running</br>";
                                                          }

                                                        $locations[$i]["data"]       =  "<i class='fa fa-user'></i> Name: ".get_employee_name($row['employee_id'])."<br>";
                                                        $locations[$i]["data"]       .= "<i class='fa fa-clock-o'></i> Date Time: ".$sync_date_time."</br>";
                                                        $locations[$i]["data"]       .= "<i class='fa fa-street-view'></i> Accuracy: ".round($accuracy)."m</br>";
                                                        $locations[$i]["data"]       .= $movements_show;

                                                        $i++;
                                                      }
                                                    }
                                                  }
                                                  if(count($count_total) == 0){
                                                    echo "<br /><br /><h1 class='page-title text-center text-danger'>$no_locations_xml</h1>";
                                                  }
                                                ?>
                                              </div>
                                            </div>
                                        </div>
                                      </div>
                                    </div>

                                                <div class="tab-pane fade" id="tab_time_off_overview">
                                                  <div class="row">
                                                      <div class="col-md-12">
                                                        <h2 class="page-title no_margin"> <i class="fa fa-tags"></i> Time Off Overview</h2>
                                                      </div>
                                                  </div>
                                                  <br />

                                                  <div class="row">
                                                    <div class="col-md-4">
                                                        <h4>Most Polular Sick Days</h4>
                                                        <table class='table table-bordered'>
                                                            <tr>
                                                              <th>Day</th>
                                                              <th>Absents Count</th>
                                                            </tr>

                                                            <?php
                                                              foreach($week as $day){
                                                                if(strlen($sick_heatmap[$day]) == 0){
                                                                  $sick_heatmap[$day] = 0;
                                                                }
                                                                echo "<tr>
                                                                  <th>$day</th>
                                                                  <td>".$sick_heatmap[$day]."</td>
                                                                </tr>";
                                                              }
                                                            ?>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h4>Most Polular Vacation Days</h4>
                                                        <table class='table table-bordered'>
                                                          <tr>
                                                            <th>Day</th>
                                                            <th>Absents Count</th>
                                                          </tr>
                                                          <?php
                                                            foreach($week as $day){
                                                              if(strlen($vacation_heatmap[$day]) == 0){
                                                                $vacation_heatmap[$day] = 0;
                                                              }
                                                              echo "<tr>
                                                                <th>$day</th>
                                                                <td>".$vacation_heatmap[$day]."</td>
                                                              </tr>";
                                                            }
                                                          ?>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h4>Most Days Away Employee</h4>
                                                        <table class='table table-bordered'>
                                                            <tr>
                                                              <th>Employee</th>
                                                              <th>Days Absent</th>
                                                            </tr>
                                                            <?php
                                                              $ii = 0;
                                                              foreach($most_days_aways_employee as $id => $absents){
                                                                $ii++;
                                                                echo "<tr>
                                                                  <th>
                                                                    <a target='_blank' href='employee-profile.php?id=$id#tab_time_off'>
                                                                      <img  class='small_user_dp' src=".get_employee_photo($id)." alt=''  />
                                                                      <strong style='top: 6px; position: relative; left: 6px;'>".get_employee_name_text($id)."</strong>
                                                                    </a>
                                                                  </th>
                                                                  <td style='vertical-align: middle;'>".$absents."</td>
                                                                </tr>";
                                                                if($ii >= 5){
                                                                  break;
                                                                }
                                                              }

                                                            ?>
                                                        </table>
                                                    </div>
                                                  </div>
                                                </div>

                                                 <div class="tab-pane fade" id="tab_time_off">
                                                   <div class="row">
                                                       <div class="col-md-12">
                                                         <h2 class="page-title no_margin"> <i class="fa fa-tags"></i> Time Off Reports</h2>
                                                       </div>
                                                   </div>
                                                   <br />





                                                            <?php if(count($data_time_off) > 0){ ?>

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

                                                         <p class="text-center text-muted">
                                                         <i class="fa fa fa-clock-o fa-3x"></i>
                                                         <br />
                                                         <br />
                                                         No time off history to show!
                                                         <br />
                                                         </p>

                                                       <?php } ?>







                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





                                                    <!-- profile end -->

                    							</div>



                                        </div>
                                    </div>
                                </div>
                                <!-- MODALS -->
                                <div class="modal fade" id="modal_compensation" tabindex="-1" role="basic" aria-hidden="true">
                                    <form  action="employee_form_submit.php" method="post" >
                                    <div class="modal-dialog">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Add Compensation</h4>
                                            </div>
                                            <div class="modal-body">

                                                  <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Compensation Title:</label>
                                                        <input required class="form-control" name="txt_compensation_full_name" placeholder="">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Amount:</label>
                                                        <input required type="number" class="form-control" name="txt_compensation_amount" id="form_control_1" value="0">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label>Category:</label>
                                                        <select required class="form-control" name="txt_compensation_category">
                                                            <option value="Travelling">Travelling</option>
                                                            <option value="Health">Health</option>
                                                            <option value="Extra Days">Extra Days</option>
                                                        </select>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">

                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_add_compensation" class="btn green">Add Compensation</button>
                                            </div>

                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                    </form>
                                </div>
                                <div class="modal fade" id="modal_assets" tabindex="-1" role="basic" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                      <form method="post" action="employee_form_submit.php">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                              <h4 class="modal-title">Add New Asset</h4>
                                          </div>
                                          <div class="modal-body">
                                              <div class="row">
                                                  <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label>Name: </label>
                                                          <input required class="form-control" name="txt_assets_name" />
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label>Category: </label>
                                                          <select class="form-control" name="txt_assets_category">
                                                              <option value="">Choose Category</option>
                                                              <option value="Computer">Computer</option>
                                                              <option value="Mobile">Mobile</option>
                                                          </select>
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label>Serial Number: </label>
                                                          <input class="form-control" name="txt_assets_serial" />
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label>Date Given: <small>(leave empty for current date & time)</small></label>
                                                          <input type="date" class="form-control" name="txt_assets_given_date" />
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label>Asset's Worth: </label>
                                                          <input type="number" class="form-control" name="assets_worth" />
                                                      </div>
                                                  </div>
                                                  <div class="col-md-6">
                                                      <div class="form-group">
                                                          <label>Notes: </label>
                                                          <textarea class="form-control" name="txt_assets_textarea"></textarea>
                                                      </div>
                                                  </div>

                                                  <div class="clearfix"></div>
                                              </div>
                                           </div>
                                          <div class="modal-footer">
                                              <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                              <button type="submit" name="btn_add_assets" class="btn green">Add Asset</button>
                                          </div>
                                      </div>
                                      </form>
                                      <!-- /.modal-content -->
                                  </div>
                                </div>
                                <div class="modal fade" id="modal_documents" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        <form autocomplete="off" role="form" method="POST" action="employee_form_submit.php" enctype="multipart/form-data">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Add New Document</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Document Name: </label>
                                                            <input required class="form-control" name="txt_document_file_name" />
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Document File: </label>
                                                            <input required type="file" name="file_document" placeholder="Choose file" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Additional Notes: </label>
                                                            <input class="form-control" name="txt_document_notes" />
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                </div>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_add_document" class="btn green">Upload</button>
                                            </div>
                                        </div>
                                        </form>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>


                                  <div class="modal fade" id="modal_edit_document" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                    	<div class="modal-loading"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                                        <div class="modal-content">
                                            <form autocomplete="off" role="form" method="POST" action="employee_form_submit.php" enctype="multipart/form-data">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title">Update Document</h4>
                                                </div>
                                                <div class="modal-body">

                                                        <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">
                                                        <input type="hidden" name="txt_file_pervious_path">
                                                        <input type="hidden" name="txt_file_document_id">

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Document Name: </label>
                                                                <input class="form-control" name="txt_edited_document_file_name" />
                                                            </div>
                                                        </div>
                                                         <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Document File: <a class="url" href="#" target="_blank">Download File</a></label>
                                                                <input type="file" name="file_edited_document" placeholder="Choose file" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label>Additional Notes: </label>
                                                                <input class="form-control" name="txt_edited_document_notes" />
                                                            </div>
                                                        </div>
                                                        <div class="clearfix"></div>

                                                 </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                    <button type="submit" name="btn_edit_document" class="btn green">Update Document</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>




                                <div class="modal fade" id="modal_edit_advance_salary" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                    	<div class="modal-loading" id="advance_salary_ajax_loader"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                                        <div class="modal-content">
                                        	<form autocomplete="off" role="form" method="POST" action="employee_form_submit.php">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Update Advance Salary</h4>
                                            </div>
                                            <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label>Date:</label>
                                                            <input required="" class="form-control date-picker" type="text" name="txt_edit_advance_salary_date">
                                                        </div>
                                                        <div class="col-md-6">

                                                            <label>Amount:</label>
                                                            <input required="" class="form-control" type="number" name="txt_edit_advance_salary_amount">

                                                        </div>
                                                        <input type="hidden" name="txt_advance_salary_id">
                                                        <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">
                                                        <div class="clearfix"></div>
                                                    </div>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_edit_advance_salary" class="btn green">Update Compensation</button>
                                            </div>
                                        </div>
                                        </form>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>
                                 <div class="modal fade" id="modal_edit_compensation" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                    	<div class="modal-loading"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                                        <div class="modal-content">
                                        	<form autocomplete="off" role="form" method="POST" action="employee_form_submit.php">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Update Compensation</h4>
                                            </div>
                                            <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Compensation Titme:</label>
                                                            <input required="" class="form-control" type="text" name="txt_edit_compensation_full_name">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Amount: </label>
                                                            <input required="" class="form-control" type="number" name="txt_edit_compensation_amount">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Category:</label>
                                                            <select required class="form-control" name="txt_edit_compensation_category">
                                                                <option value="Travelling">Travelling</option>
                                                                <option value="Health">Health</option>
                                                                <option value="Extra Days">Extra Days</option>
                                                            </select>

                                                        </div>
                                                        <input type="hidden" name="txt_edit_compensation_id">
                                                        <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">
                                                    </div>
                                                    <div class="clearfix"></div>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_edit_compensation" class="btn green">Update Compensation</button>
                                            </div>
                                        </div>
                                        </form>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>

                                 <div class="modal fade" id="modal_advance_salary" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        	<form role="form" method="POST" action="employee_form_submit.php">
                                            <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Add Advance Salary</h4>
                                            </div>
                                            <div class="modal-body">
                                                  <div class="row">
                                                      <div class="col-md-6">
                                                          <label>Date:</label>
                                                          <input required="" class="form-control date-picker" type="text" name="txt_advance_salary_date">
                                                      </div>
                                                      <div class="col-md-6">
                                                          <label>Amount:</label>
                                                          <input required="" class="form-control" type="number" name="txt_advance_salary_amount">
                                                      </div>
                                                      <div class="clearfix"></div>
                                                  </div>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_add_advance_salary" class="btn green">Add Advance Salary</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>

                                 <div class="modal fade" id="modal_assets_history" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-loading"><i class="fa fa-circle-o-notch fa-spin"></i></div>
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Assets History</h4>
                                            </div>
                                            <div class="modal-body" id="tbl_assets_history">


                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                            </div>
                                            </form>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>

                                <!-- END CONTAINER -->
                                <div class="modal fade" id="modal_time_off" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                        	<form method="post" action="employee_form_submit.php">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Add Time Off</h4>
                                            </div>
                                            <div class="modal-body" >
                                              <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>">
                                              <div class="col-md-8">
                                                  <label>Form and To Date:</label>
                                                  <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-start-date="+0d" data-date-format="mm/dd/yyyy">
                                                      <input type="text"   class="form-control" name="txt_time_off_from_date">
                                                      <span class="input-group-addon"> to </span>
                                                      <input type="text"  class="form-control" name="txt_time_off_to_date"> </div>

                                              </div>
                                              <div class="col-md-4">
                                                  <label for="form_control">Policy:</label>
                                                  <select required class="form-control" name="txt_time_off_policy">
                                                      <option value="sick" selected>Sick</option>
                                                      <option value="vacation">Vacation</option>
                                                  </select>
                                              </div>

                                              <div class="col-md-12">
                                                <label>Comments:</label>
                                                <textarea required="" class="form-control" name="txt_time_off_comment"></textarea>
                                              </div>

                                              <div class="clearfix"></div>


                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" name="btn_add_time_off_admin" class="btn green">Add Time Off</button>
                                            </div>
                                            </form>
                                        </div>
                                        </form>
                                        <!-- /.modal-content -->
                                    </div>
                                    <!-- /.modal-dialog -->
                                </div>

                                <div class="modal fade" id="modal_asset_give" tabindex="-1" role="basic" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                       	<form id="asset_form" role="form" method="POST" action="employee_form_submit.php">

                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                <h4 class="modal-title">Assets</h4>
                                            </div>
                                            <div class="modal-body">
                                                  <div class="row">
                                                      <div class="col-md-6">
                                                          <label>Select User:</label>
                                                          <select name="asset_user_id"  class="form-control">

                                                          <optgroup label="Users">
                                                              <?php
                                                              $rows = sql($DBH, "SELECT id,fullname,access_level FROM tbl_login where company_id = ? AND status = ?",
                                                              array($SESS_COMPANY_ID,"active"), "rows");

                                                              foreach($rows as $row){
                                                                  $id					  = $row['id'];
                                                                  $fullname			= $row['fullname'];
                                                                  $access_level	= $row['access_level'];

                                                                  if($SESS_ID == $id){
                                                                    $access_level = "(You)";
                                                                  }else if($access_level == "admin"){
                                                                    $access_level = "(Admin)";
                                                                  }else if($access_level == "user"){
                                                                    $access_level = "(Employee)";
                                                                  }
                                                                  echo "<option value='$id'>$fullname $access_level</option>";
                                                              }

                                                              ?>
                                                          </optgroup>
                                                        </select>
                                                      </div>
                                                      <div class="col-md-6">
                                                          <label>Comments:</label>
                                                          <textarea placeholder="Comments" class="form-control" name="asset_comments"></textarea>
                                                      </div>
                                                      <div class="clearfix"></div>
                                                  </div>
                                             </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn green btn_submit_asset">Give Asset</button>
                                            </div>
                                        </div>
                                        </form>
                                    </div>
                                </div>

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
		<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
		<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
		<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
		<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- <script src="assets/pages/scripts/charts-morris.min.js" type="text/javascript"></script> -->
		<!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-date-time-pickers.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>


		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME LAYOUT SCRIPTS -->
		<script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
		<script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var map;
        var infowindow;
        var marker, i;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -34.397, lng: 150.644},
              zoom: 8
            });

            infowindow = new google.maps.InfoWindow();

            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function (position) {
                initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                map.setCenter(initialLocation);
              });
            }

            var locations = <?php echo json_encode($locations); ?>;

            try{
                map.setCenter(new google.maps.LatLng(locations[0]["latitude"], locations[0]["longitude"]))
            }catch(e){
            }

            for (i = 0; i < locations.length; i++) {
                var icon = {
                  url: locations[i].icon, // url
                  scaledSize: new google.maps.Size(20, 20), // scaled size
                  origin: new google.maps.Point(0,0), // origin
                  anchor: new google.maps.Point(0,0), // anchor
                  strokeColor: '#000',
		          strokeWeight: 3
                };


                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i]["latitude"], locations[i]["longitude"]),
                    map: map,
                    icon : icon

                    //icon: "https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(i+1)+"|ea4335|ffffff"
                });
                if(i > 0){
                    var flightPlanCoordinates = [
                      {lat: locations[i-1]["latitude"], lng: locations[i-1]["longitude"]},
                      {lat: locations[i]["latitude"], lng: locations[i]["longitude"]}
                    ];
                    var flightPath = new google.maps.Polyline({
                      path: flightPlanCoordinates,
                      geodesic: true,
                      strokeColor: '#ea4335',
                      strokeOpacity: 1.0,
                      strokeWeight: 2,
                      fillOpacity:0.35
                    });
                    flightPath.setMap(map);
                }
                google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {return function() {
                  infowindow.setContent(locations[i]["data"]);
                  infowindow.open(map, marker);
                }})(marker, i));
                google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {return function() {
                  infowindow.close();
                }})(marker, i));
            }
            //$polygons

            var my_polygons = '<?php echo json_encode($polygons); ?>';
            my_polygons 	= $.parseJSON(my_polygons);
             $.each(my_polygons,function(key, value){
               //key = index
               //value[0] = index
               //value[1] = name
               //value[2] = polygon
               //value[3] = date time

               var location_id		= value[0];
               var name 			= value[1];
               var polygon_points 	= value[2];
               var date_time		= value[3];

               var proper_polygon_json = [];
               $.each(polygon_points,function(point_index, point_lat_lng){
                 proper_polygon_json.push({"lat": +point_lat_lng.lat, "lng": +point_lat_lng.lng});
               });

               var office_locations = new google.maps.Polygon({
                 paths: proper_polygon_json,
                 strokeColor: '#FF0000',
                 strokeOpacity: 0.8,
                 strokeWeight: 3,
                 fillOpacity: 0.35
               });
               office_locations.setMap(map);


               office_locations.addListener('click', function(event) {
                 var vertices = this.getPath();
                 var contentString = '<b><?php echo $name_xml; ?> '+name+'</b>';
                   //'<br>' +
                   //'<?php echo $created_xml; ?> '+ date_time; +
                   //'<br>' +
                   //'<a class="btn btn-danger btn-xs del_fence" href="exe_del_fence.php?location_id='+location_id+'"><i class="fa fa-times"></i> <?php echo $delete_xml ; ?></a>'



                 infowindow.setContent(contentString);
                 infowindow.setPosition(event.latLng);
                 infowindow.open(map);
               });

               office_locations.addListener('mouseout', function(event) {
                 //infowindow.close(map);
               });

             });


            //$polygons end
        }


        $(document).ready(function(){

                setTimeout(function(){
                    $("#map").css('height',($(window).height()-200)+'px');
                    initMap();
                },250);

                $(".render_map").click(function(){
                    setTimeout(function(){
                        $("#map").css('height',($(window).height()-200)+'px');
                        initMap();
                    },250);
                });





                 $('#time_off_table').DataTable();
                 $('#tbl_documents').DataTable();
                 $('#compensation_table').DataTable();
                 $('#expense_table').DataTable();
                 $("#time_off_history_table").DataTable();

                 $('a.edit-document').on('click',function(){
                  	document_id = $(this).attr('data-document-id');
                    $('.modal-loading').show();
                  	$.ajax({
                  		method:'POST',
                  		url:'ajax/employee-ajax.php',
                  		data:{update_document:true,document_id:document_id},
                  		success:function(response){
                  			$('.modal-loading').hide();
                  			data = JSON.parse(response);
                  			file_path = data[0].file_path;
                  			notes = data[0].notes;
                  			file_name = data[0].filename;

                  			$('input[name=txt_edited_document_notes]').val(notes);
                  			$('input[name=txt_edited_document_file_name]').val(file_name);
                  			$('#modal_edit_document a.url').attr('href',file_path);
                  			$('input[name=txt_file_pervious_path]').val(file_path);
                  			$('input[name=txt_file_document_id]').val(document_id);
                  			// $('#txt_edited_document_notes').value(notes);
                  			// asd = JSON.parse(response);
                  			// console.log(asd.file_path);
                  			// console.log(asd['file_path']);
                  		}
                  	});
                });

                //document deleting
                $(".delete_document").click(function(e){
                    e.preventDefault();
                    //x = confirm("Are you sure you want to delete the document?");
                   	//if (x == true) {
                      var id = $(this).attr("document_id");

                      $(this).html("<i class='fa fa-refresh fa-spin'></i>");
                      $.ajax({
                       		method:'POST',
                       		url:'ajax/employee-ajax.php',
                       		data:{delete_document:true,document_id:id},
                       		success:function(response){
                            $('tr#rowDocument'+id).fadeOut();
                       		}
                   	    });
                   	//}
                });

                $(".delete_compensation").click(function(e){
                    e.preventDefault();
                    //x = confirm("Are you sure you want to delete this compensation?");
                    var id = $(this).attr("compensation_id");
                    $(this).html("<i class='fa fa-refresh fa-spin'></i>");
                   	//if (x == true) {
                          $('tr#rowCompensation'+id).fadeOut();
                       		$.ajax({
                       		method:'POST',
                       		url:'ajax/employee-ajax.php',
                       		data:{delete_compensation:true,compensation_id:id},
                       		success:function(response){
                       			  $('tr#rowCompensation'+id).fadeOut();
                           }
                   	    });
                   	//}
                });


                  $('a.assests-history-btn').on('click',function(){
                    $('.modal-loading').show();
                        $this = $(this);
                        $assets_id = $(this).attr('data-assets-id');
                        $.ajax({
                            method:'POST',
                            url:'ajax/employee-ajax.php',
                            data:{get_assets_history:true,assets_id:$assets_id},
                            success:function(response){
                                $('#tbl_assets_history').html(response);
                                $('.modal-loading').hide();
                                //$('#assets_table_history').DataTable();
                            }
                        })
                  });
                 $('button.btn-approve').on('click',function(){
                     $time_off_id = $(this).attr('data-time-off-id');
                     $this = $(this);
                     $('#tf-status-'+$time_off_id).html("<i class='fa fa-refresh fa-circle-o-notch'></i> Updating..");
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
                 $('#tf-status-'+$time_off_id).html("<i class='fa fa-refresh fa-circle-o-notch'></i> Updating..");
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
                 $('#tf-status-'+$time_off_id).html("<i class='fa fa-refresh fa-circle-o-notch'></i> Updating..");
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

                function change_status(expense_id,action,old_html,x){
						          $(".action_btn[expense_id='"+expense_id+"'][action='"+action+"']").html("<i class='fa fa-spinner fa-spin'></i> Loading..");
                        var reason = x; //rejection reason

                        $.get("ajax/expense_status.php?expense_id="+expense_id+"&action="+action+"&reason="+reason,function(data){
                            data = $.parseJSON(data);
                            var expense_id = data.expense_id;
                            //var old_status = data.old_status;
                            var new_status  = data.new_status;
                            var error       = data.error;
                            var date_time   = data.date_time;

                            if(error == "false"){
                                if(new_status == "Approved"){
                                    //status
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("title","Approved");
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("data-original-title","Approved");
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("data-content",date_time);
                                    $(".expense_status[expense_id='"+expense_id+"']").removeClass("badge-default badge-warning badge-danger badge-success");
                                    $(".expense_status[expense_id='"+expense_id+"']").addClass("badge-warning");
                                    $(".expense_status[expense_id='"+expense_id+"']").html('<i class="fa fa-check"></i> Approved');

                                    //action
                                    $(".action_opt[expense_id='"+expense_id+"'][action='approve']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='reject']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='paid']").fadeIn();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='no_action']").hide();

                                }else if(new_status == "Rejected"){
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("title","Rejected");
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("data-original-title","Rejected");
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("data-content",date_time);
                                    $(".expense_status[expense_id='"+expense_id+"']").removeClass("badge-default badge-warning badge-danger badge-success");
                                    $(".expense_status[expense_id='"+expense_id+"']").addClass("badge-danger");
                                    $(".expense_status[expense_id='"+expense_id+"']").html('<i class="fa fa-times"></i> Rejected');

                                    //action
                                    $(".action_opt[expense_id='"+expense_id+"'][action='approve']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='reject']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='paid']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='no_action']").html("Rejected: <b>"+data.reason+"</b>").fadeIn();

                                }else if(new_status == "Pay"){
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("title","Paid");
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("data-original-title","Paid");
                                    $(".expense_status[expense_id='"+expense_id+"']").attr("data-content",date_time);
                                    $(".expense_status[expense_id='"+expense_id+"']").removeClass("badge-default badge-warning badge-danger badge-success");
                                    $(".expense_status[expense_id='"+expense_id+"']").addClass("badge-success");
                                    $(".expense_status[expense_id='"+expense_id+"']").html('<i class="fa fa-usd"></i> Paid');

                                    //action
                                    $(".action_opt[expense_id='"+expense_id+"'][action='approve']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='reject']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='paid']").hide();
                                    $(".action_opt[expense_id='"+expense_id+"'][action='no_action']").html("Paid").fadeIn();
                                }
                            }

                            $(".action_btn[expense_id='"+expense_id+"'][action='"+action+"']").html(old_html);
                        });
                }


                var asset_action    = null;
                var assets_id       = null;

                $('a.assests-return-btn').on('click',function(){
                    $this = $(this);
                  	assets_id        = $(this).attr('data-assets-id');
                    asset_action     = "return";
                    var emp_id       = $(this).attr('data-emp-id');
                    $("select[name='asset_user_id']").val(emp_id);
                    $("#modal_asset_give").modal("show");
                    $(".btn_submit_asset").html("Return Asset");
                    $("select[name='asset_user_id']").parent().hide();
                    $("select[name='asset_user_id']").parent().siblings().removeClass("col-md-6").addClass("col-md-12");
                })

                $('a.assests-give-btn').on('click',function(){
                    $this           = $(this);
                    assets_id       = $(this).attr('data-assets-id');
                    asset_action    = "give";
                    var emp_id       = $(this).attr('data-emp-id');
                    $("select[name='asset_user_id']").val(emp_id);
                    $("#modal_asset_give").modal("show");
                    $(".btn_submit_asset").html("Give Asset");
                    $("select[name='asset_user_id']").parent().show();
                    $("select[name='asset_user_id']").parent().siblings().removeClass("col-md-12").addClass("col-md-6");
                });

                $("form#asset_form").submit(function(e){
                    e.preventDefault();
                    var asset_user_id   = $("select[name='asset_user_id']").val();
                    var asset_comments  = $("textarea[name='asset_comments']").val();

                    $(".status[data-assets-id='"+assets_id+"']").html("<i class='fa fa-circle-o-notch fa-spin'></i> Updating..");
                    $("#modal_asset_give").modal("hide");
                    $("a.assests-give-btn[data-assets-id='"+assets_id+"']").attr('data-emp-id',asset_user_id);
                    $.ajax({
                  		method:'GET',
                  		url:'ajax/employee-ajax.php',
                  		data:{asset_action:asset_action,assets_id:assets_id,comment:asset_comments,asset_user_id:asset_user_id},
                  		success:function(response){

                          $("textarea[name='asset_comments']").val("");

                          if(asset_action == "return"){
                            $(".status[data-assets-id='"+assets_id+"']").html("Returned");
                            $('a.assests-return-btn[data-assets-id="'+assets_id+'"]').hide();
                            $('a.assests-give-btn[data-assets-id="'+assets_id+'"]').show();
                          }else{
                            $(".status[data-assets-id='"+assets_id+"']").html("Given");
                            $('a.assests-give-btn[data-assets-id="'+assets_id+'"]').hide();
                            $('a.assests-return-btn[data-assets-id="'+assets_id+'"]').show();
                          }
                  		}
                  	});
                });


                $('a.btn-edit-compensation').on('click',function(){
                      $compensation_id = $(this).attr('data-compensation-id');
                      $('.modal-loading').show();
                      $.ajax({
                        method:'POST',
                        url:'ajax/employee-ajax.php',
                        data:{update_compensation:true,compensation_id:$compensation_id},
                        success:function(response){
                          $('.modal-loading').hide();
                          data = JSON.parse(response);
                          $('input[name=txt_edit_compensation_full_name]').val(data[0].full_name);
                          $('input[name=txt_edit_compensation_amount]').val(data[0].amount);
                          $('input[name=txt_edit_compensation_id]').val(data[0].id);
                          $('select[name=txt_edit_compensation_category] option').each(function() {
                            if ($(this).text() == data[0].category ) {
                              $(this).attr("selected","selected");
                            }
                          })
                        }
                      })
                })


                $('a.btn-advance-salary').on('click',function() {
                	$this =  $(this);
                	$advance_salary_id = $this.attr('data-salary-id');
                	 $('#advance_salary_ajax_loader').css('visibility','visible');
                	 $.ajax({
                           method:'POST',
                           url:'ajax/employee-ajax.php',
                           data:{get_advance_salary:true,salary_id:$advance_salary_id},
                           success:function(response){
                           	data = JSON.parse(response);
                               $('#advance_salary_ajax_loader').css('visibility','hidden');
                               $('input[name=txt_advance_salary_id]').val($advance_salary_id);
                               $('input[name=txt_edit_advance_salary_date]').val(data[0].date)
   							              $('input[name=txt_edit_advance_salary_amount]').val(data[0].amount);
                           }
                       })
                })







                setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);


                setTimeout(function(){
        					<?php
        						$date_from 	= $_GET['date_from'];
        						$date_to 	= $_GET['date_to'];
        						if(strlen($date_from) > 0 && strlen($date_to) > 0){
        							$show_date_from 	= date("M d, Y",strtotime($_GET['date_from']));
        							$show_date_to 		= date("M d, Y",strtotime($_GET['date_to']));
        						}else{
        						    $show_date_from 	= date("M d, Y",time());
        							$show_date_to 		= date("M d, Y",time());
        						}
        					?>


                  var date_from 	= "<?php echo $show_date_from; ?>";
        					var date_to		  = "<?php echo $show_date_to; ?>";

        					if(date_from == "" && date_to == ""){
                    $(".reportrange span").text("<?php echo $showing_last_hours_xml ?>");
        					}else if(date_from == date_to){
        						$(".reportrange span").text(date_from+" - "+date_from);
                  }else{
        						$(".reportrange span").text(date_from+" - "+date_to);
                  }
        					$(".reportrange span").show();

                },50);




        				$(".screenshot_filter").click(function(){
        					var date_from 	= $("input[name='daterangepicker_start']").val();
        					var date_to		= $("input[name='daterangepicker_end']").val();



                            if(date_from != "" && date_to != ""){
                                $("input[name='date_from']").val(date_from);
            					$("input[name='date_to']").val(date_to);
            					if(date_from == "" && date_to == ""){
            						$(".reportrange span").text("<?php echo $showing_last_hours_xml; ?>");
            					}
                            }

                            $(".form_screenshot_filter").submit();

        				});

                        $(".attendance_filter").click(function(){
        					var date_from 	= $("input[name='daterangepicker_start']").val();
        					var date_to		= $("input[name='daterangepicker_end']").val();

                            if(date_from != "" && date_to != ""){
                                $("input[name='date_from']").val(date_from);
            					$("input[name='date_to']").val(date_to);
            					if(date_from == "" && date_to == ""){
            						$(".reportrange span").text("<?php echo $showing_last_hours_xml; ?>");
            					}
                            }

                            $(".form_attendance_filter").submit();

        				});

                $('.attachment[data-toggle="popover"]').popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'right',
                    content: function(){return '<img style="width:100%;" src="'+$(this).data('img') + '" />';}
                });

                $('.expense_status[data-toggle="popover"]').popover({
                    html: true,
                    trigger: 'hover',
                    placement: 'left'
                });

                $(".ranges .active").removeClass("active");


              $("select[name='bulk_status_change']").change(function(){
                 var total_checked = $(".bulk_check").filter(':checked').length;
                 var new_status    = $(this).val();
                 if(new_status != ""){
                     if(total_checked == 0){
                          alert("Please select an expense!");
                          $("select[name='bulk_status_change']").val("");
                     }else{

                                        /*if(new_status == "reject"){
            								var x = prompt("Please specify a reason for rejection of "+total_checked+" selected expense(s)!");
            							}else{
            								var x = confirm("Are you sure you want to change the status of "+total_checked+" selected expense(s) to '"+new_status+"' ?");
            							}*/

                          //if(x){
                              $(".bulk_check:checked").each(function(){
                                  var expense_id  = $(this).val();
                                  var old_html    = $(".action_btn[expense_id='"+expense_id+"'][action='"+new_status+"']").html();
                                  change_status(expense_id,new_status,old_html,x);
                              });
                              $("select[name='bulk_status_change']").val("");
                          //}else{
                          //    $("select[name='bulk_status_change']").val("");
                          //}
                     }
                 }
              });

              $(".select_all").click(function(){
                  $(".bulk_check").each(function(){
                      if($(this).is(":visible")){
                          $(this).prop("checked",true);
                      }
                  });
              });

              $(".unselect_all").click(function(){
                  $(".bulk_check").each(function(){
                      if($(this).is(":visible")){
                          $(this).prop("checked",false);
                      }
                  });
              });

              $(".action_btn").click(function(){
                  var expense_id  = $(this).attr("expense_id");
                  var action      = $(this).attr("action");
                  var old_html    = $(this).html();

                  if(expense_id.length > 0 && action.length > 0){
                        if(action == "reject"){
                            var x = prompt("Please specify a reason for rejection of this expense!");
  						}else{
  							//var x = confirm("Are you sure you want to change the status of this expense to '"+action+"' ?");
  						    var x = true;
                        }
                      //if(x){
                          change_status(expense_id,action,old_html,x);
                      //}
                  }else{
                      alert("Whoops, please refresh your browser and try again!");
                  }
              });

               $("#single").change(function(){
                    var id = $(this).val();
                    window.location.href = "select_device.php?id="+id;
               });
               $('[data-toggle="tooltip"]').tooltip();


               $("#single").change(function(){
                    var id = $(this).val();
                    window.location.href = "select_device.php?id="+id;
               });
                $('[data-toggle="tooltip"]').tooltip();

                $(".screenshot").click(function() {
                    var src = $(this).attr("src");
                    $("#view_picture").modal("show");
                    $(".view_picture").attr("src",src);
                });


                setTimeout(function(){
        					<?php
        						$date_from 	= $_GET['date_from'];
        						$date_to 	= $_GET['date_to'];
        						if(strlen($date_from) > 0 && strlen($date_to) > 0){
        							$show_date_from 	= date("M d, Y",strtotime($_GET['date_from']));
        							$show_date_to 		= date("M d, Y",strtotime($_GET['date_to']));
        						}
        					?>

                  var date_from 	= "<?php echo $show_date_from; ?>";
        					var date_to		= "<?php echo $show_date_to; ?>";

        					if(date_from == "" && date_to == ""){
        						$(".reportrange span").text("<?php echo $showing_last_hours_xml ?>");
        					}else if(date_from == "" && date_to == ""){
        						$(".reportrange span").text(date_from+" - "+date_to);
        					}
        					$(".reportrange span").show();
                },50);

        				$("#filter").submit(function(){
        					var date_from 	= $("input[name='daterangepicker_start']").val();
        					var date_to		= $("input[name='daterangepicker_end']").val();
        					$("input[name='date_from']").val(date_from);
        					$("input[name='date_to']").val(date_to);
        					if(date_from == "" && date_to == ""){
        						$(".reportrange span").text("<?php echo $showing_last_hours_xml; ?>");
        					}
        				});

            $('[data-toggle="tooltip"]').tooltip();


            var right_height = $(".right-tab-content-data").height();
            if(right_height > 500){
              //alert(right_height);
            }

          });
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key; ?>&callback=initMap"
             async defer></script>

    </body>

</html>
