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
    
    ini_set('precision',100);
    
    //play able
    $lat_lng = array();

    //alibaba start
    require_once('../class_function/alibaba_cloud/autoload.php');
    use OSS\OssClient;
    use OSS\Core\OssException;
    //alibaba end

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

    //imp
    $employee_id = $_GET['id'];
    
    

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


    //1. Employee Tab
    $employee_login    = get_employee_login_info($employee_id)[0];
    $employee_profile  = get_employee_profile_info($employee_id)[0];
    $employee_default_img   = get_employee_default_img($employee_id);
    $rows = sql($DBH, "SELECT * FROM tbl_employee_profile where employee_id = ?",  array($employee_id), "rows");
    if (empty($rows)) {
    	$employee_color =  "#".sprintf('%06x', mt_rand(0, 16777215))."c9";
        sql($DBH, "insert into tbl_employee_profile (employee_id,employee_color) values (?,?);",array($employee_id,$employee_color), "rows");
    }

    //2. DOCUMENTS
    function get_employee_documents($employee_id){
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_documents where employee_id = ? And status = '1' ",  array($employee_id), "rows");
        return $data;
    }
    $employee_documents = get_employee_documents($employee_id);

    //3. Assets
  	function get_employee_assets($employee_id){
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_assets where employee_id = ? ",  array($employee_id), "rows");
        return $data;
    }
    $employee_assets = get_employee_assets($employee_id);

    //count given assets
    $rows = sql($DBH,'select count(*) as total_assets_given from tbl_assets where employee_id = ? and status = ? ',array($employee_id,"1"),'rows');
    $total_assets_given = 0;
    foreach($rows as $row){
        $total_assets_given = $row['total_assets_given'];
    }

    //sum given / total
    $rows = sql($DBH,'select sum(worth) as total from tbl_assets where employee_id = ?',array($employee_id),'rows');
    foreach($rows as $row){
        $total_assets_worth_total = $row['total'];
    }
    if($total_assets_worth_total == ""){
      $total_assets_worth_total = 0;
    }

    $rows = sql($DBH,'select sum(worth) as total from tbl_assets where employee_id = ? and status = ? ',array($employee_id,"1"),'rows');
    foreach($rows as $row){
        $total_assets_worth_given = $row['total'];
    }
    if($total_assets_worth_given == ""){
      $total_assets_worth_given = 0;
    }



    //4. time off
    $data_time_off =  get_employee_time_off_history($employee_id,$SESS_ID);

    //count aproved days
    $rows = sql($DBH,'select * from tbl_time_off where employee_id = ? and status != ?',array($employee_id,"deleted"),'rows');

    $pending_leave_count    = 0;//pending requests
    $sick_leave_count       = 0;//sick (approved)
    $vacation_leave_count   = 0;//vacation (approved)
    $days_leave_count       = 0;//sick (approved) + vacation (approved)

    foreach($rows as $row){
        if($row['status'] == "approved"){
          if($row['time_off_policy'] == "sick"){
              $sick_leave_count += (($row['time_off_to_date']-$row['time_off_from_date'])/86400)+1;
          }else if($row['time_off_policy'] == "vacation"){
              $vacation_leave_count += (($row['time_off_to_date']-$row['time_off_from_date'])/86400)+1;
          }
        }else if($row['status'] == "pending"){
            $pending_leave_count += 1;
        }
    }
    $days_leave_count = $sick_leave_count+$vacation_leave_count;

    $rows = sql($DBH,"select * from tbl_vacation_settings where employee_id = ?",
    array($employee_id),"rows");
    $days_leave_allowed = 0;
    foreach($rows as $row){
        $allowed_sick = $row['allowed_sick'];
        $allowed_vacation = $row['allowed_vacation'];
    }
    $days_leave_allowed = $allowed_sick+$allowed_vacation;

    //5. Compensation
    function get_employee_compensation($employee_id)
    {
    	global $DBH;
    	$dataArray =  array($employee_id);
    	$data = sql($DBH,'select * from tbl_compensation where employee_id = ?',$dataArray,'rows');
    	if ($data) {
    		return $data;
    	}
    }
    $compensation_data = get_employee_compensation($employee_id);

    //count total
    $rows = sql($DBH,'select sum(amount) as total from tbl_compensation where employee_id = ? and date > ? and status = ?',
    array($employee_id,$current_month_start_ts,"1"),'rows');
    foreach($rows as $row){
        $compensation_count = $row['total'];
    }


    //6. advance salary

    function get_emp_advance_salary($employee_id){
    	global $DBH;
    	$dataArray = array($employee_id);
    	$data = sql($DBH,'select * from tbl_advance_salary where employee_id = ?',$dataArray,'rows');
    	return $data;
    }
    $advance_salary_data =  get_emp_advance_salary($employee_id);

    //count sum
    //count total
    $rows = sql($DBH,'select sum(amount) as total from tbl_advance_salary where employee_id = ? and date > ? and status = ?',
    array($employee_id,$current_month_start_ts,"1"),'rows');
    $advance_salary_count = 0;
    foreach($rows as $row){
        $advance_salary_count = $row['total'];
    }


    //7. expense
    $rows = sql($DBH,'select sum(amount) as total from tbl_expense_refund where employee_id = ? and status = ?',
    array($employee_id,"pending"),'rows');
    foreach($rows as $row){
        $expense_total = $row['total'];
    }    
    $paid_salary = get_paid_salary($employee_login['id']);
    
    
    //Contacts
    $rows = sql($DBH, "SELECT * FROM tbl_contacts where employee_id = ?", array($employee_login['id']), "rows");
    $sync_contacts_date_time = " <small>$never_synced_xml</small>";
    foreach($rows as $row){
    $sync_contacts_data			= json_decode($row['data'],true);
    $sync_contacts_date_time		= my_simple_date($row['date_time']);
    }
    
    //App list
    $rows = sql($DBH, "SELECT * FROM tbl_app_list where employee_id = ?", array($employee_login['id']), "rows");
    $sync_date_time = "<small>$never_synced_xml</small>";
    foreach($rows as $row){
    $sync_app_data			= json_decode($row['data'],true);
    $sync_app_date_time		= my_simple_date($row['date_time']);
    }

    //date filter
    $date_from  = $_GET['date_from'];
    $date_to    = $_GET['date_to'];
    
    if($date_from == ""){$date_from = date("Y-m-d", time());}
    if($date_to == ""){$date_to = date("Y-m-d", time());}
    


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
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
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
        
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/themes/grey.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="employee-profile.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <!--
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
          
          google.charts.load('current', {'packages':['corechart']});
          google.charts.setOnLoadCallback(drawChart);
          function drawChart() {
            var data1 = new google.visualization.DataTable();
            data1.addColumn('string', 'USED');
            data1.addColumn('number', 'FREE');
            data1.addRows([
              ['USED', 25],
              ['FREE', 75]
            ]);
            var options1 = {'title':'Internal Storage',
                           'width':400,
                           'height':300};
            var chart1 = new google.visualization.PieChart(document.getElementById('chart_div1'));
            chart1.draw(data1, options1);


            
    		var data2 = new google.visualization.DataTable();
            data2.addColumn('string', 'USED');
            data2.addColumn('number', 'FREE');
            data2.addRows([
              ['USED', 25],
              ['FREE', 75]
            ]);
            var options2 = {'title':'SD Card Storage',
                           'width':400,
                           'height':300};
            var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
            chart2.draw(data2, options2);
            
          }
          
        </script>
        -->
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
                                        
                                        <div class="col-md-3 col-sm-3 col-xs-3 left-tab-content-data">
                                            <div class="tab-employee-img">
                                                <img src="<?= video_to_photo($employee_login['photo']) ?>" />
                                                <span><?= my_substr($employee_login['fullname'],10) ?></span>
                                            </div>
                                            <ul style="border: none;" class="nav nav-tabs tabs-left">
                                                <li class="active">
                                                    <a href="#tab_employee" data-toggle="tab">
                                                    <i class="fa fa-user"></i> Profile </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_documents" data-toggle="tab"><i class="fa fa-folder-open-o"></i> Documents
                                                      <?php if(count($employee_documents) > 0){echo "<span class='badge badge-info pull-right'>".count($employee_documents)."</span>";}else{echo "<span class='badge badge-default pull-right'>Not added</span>";} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_assets" data-toggle="tab"><i class="fa fa-tags"></i> Assets
                                                      <?php if($total_assets_given > 0){echo "<span class='badge badge-info pull-right'>Given: ".$total_assets_given."</span>";}else{echo "<span class='badge badge-default pull-right'>Not given</span>";} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_time_off" data-toggle="tab"><i class="fa fa-clock-o"></i> Time Off
                                                      <?php if($pending_leave_count>0){echo "<span class='badge badge-info pull-right'>Pending: ".$pending_leave_count."</span>";}else{echo "<span class='badge badge-default pull-right'>No Pending</span>";} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_compensation" data-toggle="tab"><i class="fa fa-money"></i> Compensation
                                                      <?php if($compensation_count>0){echo "<span class='badge badge-info pull-right'>".ucfirst(date("M")).": $".$compensation_count."</span>";}else{echo "<span class='badge badge-default pull-right'>$0</span>";} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_advance_salary" data-toggle="tab"><i class="fa fa-money"></i> Advance Salary
                                                      <?php if($advance_salary_count>0){echo "<span class='badge badge-info pull-right'>".ucfirst(date("M")).": $".$advance_salary_count."</span>";}else{echo "<span class='badge badge-default pull-right'>$0</span>";} ?>
                                                    </a>
                                                </li>
                                                 <li>
                                                    <a href="#tab_expense" data-toggle="tab"><i class="fa fa-money"></i>  Expense
                                                      <?php if($expense_total>0){echo "<span class='badge badge-info pull-right'>Pending: $".$expense_total."</span>";}else{echo "<span class='badge badge-default pull-right'>No pending</span>";} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_contacts" data-toggle="tab"><i class="fa fa-book"></i> Contacts 
                                                      <?php if(count($sync_contacts_data)>0){echo "<span class='badge badge-info pull-right'>".count($sync_contacts_data)."</span>";} ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_screenshots" data-toggle="tab"><i class="fa fa-picture-o"></i>  Screenshots 
                                                    <?php
                                                        $STH 			= $DBH->prepare("select count(*) FROM tbl_screenshot where employee_id = ? ");
                                                        $result 	   	= $STH->execute(array($employee_login['id']));                                        
                                                        $count	  	    	= $STH->fetchColumn();
                                                        if($count>0){echo "<span class='badge badge-info pull-right'>$count</span>";}
                                                    ?>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#tab_attendance" data-toggle="tab"><i class="fa fa-clock-o"></i>  Attendance </a>
                                                </li>
                                                <li>
                                                    <a class="render_map" href="#tab_locations" data-toggle="tab"><i class="fa fa-map-marker"></i>  Locations </a>
                                                </li>                                      
                                                                                        
                                                <?php if($employee_login['mode'] == "advance"){ ?>
                                                    <li>
                                                        <a href="#tab_video" data-toggle="tab"><i class="fa fa-video-camera"></i>  Videos 
                                                            <?php
                                                                $STH 			= $DBH->prepare("select count(*) FROM tbl_videos where employee_id = ? ");
                                                                $result 	   	= $STH->execute(array($employee_login['id']));                                        
                                                                $count	  	    	= $STH->fetchColumn();
                                                                if($count>0){echo "<span class='badge badge-info pull-right'>$count</span>";}
                                                            ?>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#tab_photos" data-toggle="tab"><i class="fa fa-camera"></i>  Photos 
                                                        <?php
                                                            $STH 			= $DBH->prepare("select count(*) FROM tbl_photos where employee_id = ? ");
                                                            $result 	   	= $STH->execute(array($employee_login['id']));                                        
                                                            $count	  	    	= $STH->fetchColumn();
                                                            if($count>0){echo "<span class='badge badge-info pull-right'>$count</span>";}
                                                        ?>
                                                        </a>
                                                    </li>                                                                                                        
                                                    <li>
                                                        <a href="#tab_call_log" data-toggle="tab"><i class="fa fa-phone"></i>  Call Logs
                                                        <?php
                                                            $STH 			= $DBH->prepare("select count(*) FROM tbl_phone_state where employee_id = ? ");
                                                            $result 	   	= $STH->execute(array($employee_login['id']));                                        
                                                            $count	  	    	= $STH->fetchColumn();
                                                            if($count>0){echo "<span class='badge badge-info pull-right'>$count</span>";}
                                                        ?>
                                                        </a>
                                                    </li>
                                                    <!--
                                                    <li>                                                
                                                        <a href="#tab_text_msg" data-toggle="tab"><i class="fa fa-envelope"></i>  Test Messages </a>
                                                    </li>
                                                    -->
                                                    <li>
                                                        <a href="#tab_installed_apps" data-toggle="tab"><i class="fa fa-android"></i>  Installed Apps
                                                           <?php if(count($sync_app_data)>0){echo "<span class='badge badge-info pull-right'>".count($sync_app_data)."</span>";} ?>                                                        
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                <li>
                                                    <a href="#tab_device" data-toggle="tab"><i class="fa fa-cog"></i>  Device Management </a>
                                                </li>
                                            </ul>
                                            <?php if($employee_login['mode'] == "basic"){ ?>
                                                <p class="text-warning"><strong>Note:</strong> <a href="ajax/mode.php?id=<?php echo $employee_login['id']; ?>&mode=advance" class="btn_user_mode">Enable Advance Mode</a> for videos, photos and installed apps. </a>
                                                 
                                            
                                            <?php } ?>  
                                        </div>  <!-- left-tab-content-data -->
                                        
                                        <!-- data start -->
                                        <!-- start -->
                                        <div class="col-md-9 col-sm-9 col-xs-9 right-tab-content-data">                                        
                                            <div class="tab-content">
                                                
                                        
                                        
                                        <div class="tab-pane active" id="tab_employee">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-user"></i> <?= $employee_login['fullname'] ?></h2>
                                        </div>
                                        </div>
                                        <br><br>
                                        <div class="">
                                        <form action="employee_form_submit.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>" />
                                        
                                        <div class="col-md-12">
                                        <div class="portlet-body row">
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label class="control-label">Name: *</label>
                                            <input required type="text" value="<?php echo $employee_login['fullname']; ?>" name="fullname" class="form-control" />
                                            </div>
                                            </div>
                                            <div class="col-md-8">
                                            <div class="col-md-4 no_padding">
                                            <div class="form-group">
                                            <label class="control-label">Phone: *</label>
                                            <select class="form-control" name="country_code">
                                            <option value="">Country Code</option>
                                            <?php
                                            $rows = sql($DBH, "SELECT * FROM country", array(), "rows");
                                            $found_once = false;
                                            foreach($rows as $row){
                                            $name			= $row['nicename'];
                                            $phonecode		= $row['phonecode'];
                                            
                                            if($found_once == false && substr($employee_login['contact'],0,strlen($phonecode)) == $phonecode){
                                            echo "<option selected value='$phonecode'>$name ($phonecode) </option>";
                                            $employee_login['contact'] = substr($employee_login['contact'],strlen($phonecode),strlen($employee_login['contact']));
                                            $found_once = true;
                                            }else{
                                            echo "<option value='$phonecode'>$name ($phonecode) $t</option>";
                                            }
                                            }
                                            ?>
                                            </select>
                                            </div>
                                            </div>
                                            <div class="col-md-8 no_padding">
                                            <div class="form-group">
                                            <label class="control-label">&nbsp;</label><br/>
                                            <input required value="<?php echo $employee_login['contact']; ?>" type="text" name="contact" class="form-control" />
                                            </div>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label class="control-label">Email: (for web access) </label>
                                            <input autocomplete="off" type="email" value="<?php echo $employee_login['email']; ?>" name="email" class="form-control" />
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label class="control-label">Password: (for web access)</label>
                                            <input autocomplete="off" type="password" placeholder="To Change: Enter New" name="password" class="form-control" />
                                            </div>
                                            </div>
                                            
                                            <div class="col-md-4">
                                            <div class="">
                                            <label>Birth Place: </label>
                                            <input type="text" class="form-control" name="birth_place" value="<?php echo $employee_profile['birth_place']; ?>">
                                            </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                            <div class="">
                                            <label>Date of birth: </label> <!-- .date-picker -->
                                            <input autocomplete="off" type="date" class="form-control" name="dob" value="<?php echo date("Y-m-d",$employee_login['dob']); ?>"   >
                                            </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                            <div class="form-group form-md-radios">
                                            <label>Gender: </label>
                                            <div class="md-radio-inline">
                                            <div class="md-radio">
                                            <input type="radio" id="checkbox2_8" name="gender" value="Male" class="md-radiobtn" <?php if($employee_profile['gender'] == "Male"){echo "checked";} ?> />
                                            <label for="checkbox2_8">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> Male</label>
                                            </div>
                                            <div class="md-radio">
                                            <input type="radio" id="checkbox2_9" name="gender" value="Female" class="md-radiobtn" <?php if($employee_profile['gender'] == "Female"){echo "checked";} ?> />
                                            <label for="checkbox2_9">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span>Female</label>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                        
                                            <!--
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label class="control-label">App Pin Code: (4 digit)</label>
                                            <input autocomplete="off" type="number" placeholder="Empty: No App Pin Code" value="<?php echo $employee_login['app_pin_code'] ?>" name="app_pin_code" class="form-control" />
                                            </div>
                                            </div>                                        
                                            -->
                                            
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Nationality: </label>
                                            <input class="form-control" value="<?= $employee_profile['nationality'] ?>" name="nationality"  />
                                            </div>
                                            </div>
                                        </div>    
                                        <div class="row">                                        
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>NRC: </label>
                                            <input class="form-control" value="<?= $employee_profile['nrc'] ?>" name="nrc"  />
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Religion: </label>
                                            <input class="form-control"  name="religion" value="<?= $employee_profile['religion'] ?>"  >
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Father's Name:</label>
                                            <input class="form-control "  name="fathers_name" value="<?= $employee_profile['fathers_name'] ?>"  >
                                            </div>
                                            </div>
                                        </div>    
                                        <div class="row"> 
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Mother's Name:</label>
                                            <input class="form-control "  name="mothers_name"  value="<?= $employee_profile['mothers_name'] ?>">
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Address:</label>
                                            <input class="form-control"  value="<?= $employee_profile['address'] ?>"  name="address"  >
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>City:</label>
                                            <input class="form-control"  name="city" value="<?= $employee_profile['city'] ?>"  >
                                            </div>
                                            </div>
                                        </div>    
                                        <div class="row"> 
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>State:</label>
                                            <input class="form-control"  name="state" value="<?= $employee_profile['state'] ?>"  >
                                            
                                            
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Work Phone: </label>
                                            <input class="form-control"  name="work_phone"  value="<?= $employee_profile['work_phone'] ?>">
                                            
                                            
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Personal Phone: </label>
                                            <input class="form-control" name="personal_phone" value="<?= $employee_profile['personal_phone'] ?>">
                                            
                                            
                                            </div>
                                            </div>
                                        </div>    
                                        <div class="row"> 
                                        
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Education:</label>
                                            <input class="form-control" name='education' value="<?= $employee_profile['education'] ?>">
                                            
                                            
                                            </div>
                                            </div>
                                        
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Basic Salary:</label>
                                            <input class="form-control" name="basic_salary" value="<?= $employee_profile['basic_salary'] ?>">
                                            
                                            
                                            </div>
                                            </div>
                                            <div class="col-md-4">
                                            <div class="form-group">
                                            <label>Bonus:</label>
                                            <input class="form-control "  name="bonus" value="<?= $employee_profile['bonus'] ?>">
                                            </div>
                                            </div>
                                        </div>    
                                        <div class="row"> 
                                        
                                            <div class="col-md-12">
                                            <div class="form-group">
                                            <label>Job Description:</label>
                                            <textarea class="form-control" name="job_description"><?= $employee_profile['job_description'] ?></textarea>
                                            </div>
                                            </div>
                                        </div>    
                                        <div class="row">
                                         
                                            <div class="col-md-12">
                                            <div class="form-group">
                                            <label>&nbsp;</label><Br>
                                            <button name="btn_update_profile" type="submit" class="btn green"><i class="fa fa-check"></i> Update </button>
                                            </div>
                                            </div>
                                        </div>
                                        
                                        </div>
                                        </div>
                                        </form>
                                        </div>
                                        </div>



                                        
                                        <div class="tab-pane fade" id="tab_documents">
                                        <div class="row">
                                        <div class="col-md-6">
                                        <h2 class="page-title no_margin"> <i class="fa fa-files-o"></i> Documents</h2>
                                        </div>
                                        <div class="col-md-6">
                                        <a class="btn green btn-sm pull-right" data-toggle="modal" href="#modal_documents">
                                        <i class="fa fa-plus"></i> New Document
                                        </a>
                                        </div>
                                        </div>
                                        <br /><br />
                                        <?php if(count($employee_documents) > 0){?>
                                        <table class="table table-striped table-hover table-bordered table-documents" id="tbl_documents">
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>File Name</th>
                                        <th>Download</th>
                                        <th>Date Time</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i=0;
                                        foreach ($employee_documents as $key => $value) {
                                        $value['uploaded_at'] = my_simple_date($value['uploaded_at']);
                                        if (strlen($value['updated_at']) > 0) {
                                        $value['updated_at'] = '<br>Updated: '.my_simple_date($value['updated_at']);
                                        }
                                        $i++;
                                        ?>
                                        <tr id="rowDocument<?= $value['id'] ?>">
                                        <td><?= $i ?></a></td>
                                        <td><?= $value['filename'] ?></td>
                                        <td><a class="btn btn-primary btn-xs btn-circle" target="_blank" href="<?= $value['file_path'] ?>"><i class="fa fa-file-pdf-o"></i> Download</a></td>
                                        <td>
                                        <?= $value['uploaded_at'] ?>
                                        <?= $value['updated_at'] ?>
                                        </td>
                                        <td><?= $value['notes'] ?></td>
                                        <td>
                                        <div class="table-documents-actions">
                                        <a class="btn edit-document btn-success btn-circle btn-xs"  data-document-id="<?= $value['id'] ?>" data-toggle="modal" href="#modal_edit_document"><i class="fa fa-pencil " ></i> Edit</a>
                                        <button type="button" class="btn btn-danger btn-circle btn-xs delete_document" document_id="<?=$value['id']?>"> <i class="fa fa-trash"></i> Delete</button>
                                        </div>
                                        </td>
                                        </tr>
                                        <?php } ?>
                                        </tbody>
                                        </table>
                                        
                                        
                                        
                                        <?php }else{ ?>
                                        
                                        <p class="text-center text-muted">
                                        <i class="fa fa fa-folder-open-o fa-3x"></i>
                                        <br />
                                        <br />
                                        No documents added!
                                        <br />
                                        </p>
                                        
                                        <?php } ?>
                                        </div>
                                        
                                        
                                        
                                        

                                        
                                        <div class="tab-pane fade" id="tab_assets">
                                        <div class="row">
                                        <div class="col-md-8">
                                        <h2 class="page-title no_margin"> <i class="fa fa-tags"></i> Assets
                                        <small><?php if($total_assets_worth_total > 0)echo "$".$total_assets_worth_given." out of $".$total_assets_worth_total." assets given to ".$employee_login['fullname']; ?></small>
                                        </h2>
                                        </div>
                                        <div class="col-md-4">
                                        <a class="btn green btn-sm pull-right" data-toggle="modal" href="#modal_assets">
                                        <i class="fa fa-plus"></i> Add Assets
                                        </a>
                                        </div>
                                        </div>
                                        <br /><br />
                                        
                                        
                                        <?php
                                        
                                        if(count($employee_assets) > 0){
                                        ?>
                                        <table class="table table-striped table-hover table-bordered dataTable table-assets"  id="sample_2">
                                        <thead>
                                        <tr>
                                        <th>Sno</th>
                                        <th>Name</th>
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
                                        <div class="col-md-6">
                                        <h2 class="page-title no_margin"> <i class="fa fa-money"></i> Compensation</h2>
                                        </div>
                                        <div class="col-md-6">
                                        <a class="btn green btn-sm pull-right" data-toggle="modal" href="#modal_compensation">
                                        <i class="fa fa-plus"></i> Add Compensation
                                        </a>
                                        </div>
                                        </div>
                                        <br /><br />
                                        
                                        <?php if (count($compensation_data) > 0) { ?>
                                        
                                        
                                        <table class="table table-striped table-hover table-bordered table-documents" id="compensation_table">
                                        <thead>
                                        <tr>
                                        <th>Sno</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
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
                                        <td><?= $value['full_name'] ?></td>
                                        <td><?= $value['category'] ?></td>
                                        <td>$<?= $value['amount'] ?></td>
                                        <td><?= date('d-M-Y',$value['date']) ?></td>
                                        
                                        <td>
                                        <?php
                                        /*<!--<a data-toggle="modal" href="#modal_edit_compensation" class="btn btn-edit-compensation btn-success btn-xs" data-compensation-id="<?= $value['id'] ?>">
                                        <i class="fa fa-pencil"></i> Edit
                                        </a>
                                        -->
                                        */
                                        ?>
                                        <?php if($value['status'] == "1"){ ?>
                                        <a compensation_id="<?=$value['id']?>" class="delete_compensation btn btn-xs btn-danger"><i class="fa fa-times"></i> Cancel</a>
                                        
                                        <?php }else{
                                        echo "<span class='text-muted'>Cancelled</span>";
                                        } ?>
                                        
                                        </td>
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
                                        
                                        <div class="tab-pane fade" id="tab_advance_salary">
                                        <div class="row">
                                        <div class="col-md-6">
                                        <h2 class="page-title no_margin"> <i class="fa fa-money"></i> Advance Salary</h2>
                                        </div>
                                        <div class="col-md-6">
                                        <?php if($employee_profile['basic_salary'] > 0){ ?>
                                            <a class="btn green btn-sm pull-right" data-toggle="modal" href="#modal_advance_salary">
                                            <i class="fa fa-plus"></i> Add Advance Salary
                                            </a>
                                        <?php }else{ ?>
                                            <a class="text-danger pull-right" href="#tab_employee" data-toggle="tab">
                                            <i class="fa fa-exclamation"></i> Add Basic Salary First
                                            </a>
                                        <?php } ?>
                                        
                                        
                                        </div>
                                        </div>
                                        <br /><br />
                                        
                                        <?php if (count($advance_salary_data) > 0) {
                                        $advance_percentage = round(($advance_salary_count*100)/$employee_profile['basic_salary'],2);
                                        if($advance_percentage > 100){
                                        $advance_percentage = 100;
                                        }
                                        
                                        $salary_percentage = round(($paid_salary*100)/$employee_profile['basic_salary'],2);
                                        if($salary_percentage > 100){
                                        $salary_percentage = 100;
                                        }
                                        
                                        
                                        
                                        ?>
                                        <h4><span class="text-info">Advance</span> & <span class="text-danger">Paid</span> Salary Status: <kbd class="pull-right"><?php echo "$".($advance_salary_count+$paid_salary)."/$".$employee_profile['basic_salary'] ?></kbd><h4>
                                        
                                        <div class="leave_bar">
                                        <div style="float:left; width:<?php echo $advance_percentage; ?>%" class="progress_blue"></div>
                                        <div style="float:left; width:<?php echo $salary_percentage; ?>%" class="progress_red"></div>
                                        
                                        <span><?php echo ($advance_percentage+$salary_percentage)."%"; ?></span>
                                        
                                        </div>
                                        
                                        
                                        <table class="table table-striped table-hover table-bordered table-documents" id="compensation_table">
                                        <thead>
                                        <tr>
                                        <th>Sno</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 0;
                                        foreach ($advance_salary_data as $key => $value) {
                                        $i++;
                                        ?>
                                        
                                        <tr>
                                        <td><?= $i ?></td>
                                        <td>$<?= $value['amount'] ?></td>
                                        <td><span class=""><?= date('d-M-Y',$value['date']) ?></span></td>
                                        <td>
                                        
                                        
                                        <?php if($value['status'] == "1"){ ?>
                                        <a advance_id="<?=$value['id']?>" class="delete_avdanve_salary btn btn-xs btn-danger"><i class="fa fa-times"></i> Cancel</a>
                                        <?php }else{
                                        echo "<span class='text-muted'>Cancelled</span>";
                                        } ?>
                                        
                                        <!--
                                        <a href="#modal_edit_advance_salary" data-toggle="modal" class="btn btn-advance-salary btn-success btn-xs"  data-salary-id="<?= $value['id'] ?>"><i class="fa fa-pencil"></i> Edit</a>
                                        -->
                                        
                                        </td>
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
                                        No advance salary added!
                                        <br />
                                        </p>
                                        
                                        
                                        <?php } ?>
                                        </div>
                                        <div class="tab-pane fade" id="tab_expense">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-usd"></i> Expense</h2>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            	<br />
                                                <form action="" class="form-horizontal filter_form5" method="GET">
                                                    <input type="hidden" name="page5" value="tab_expense" />
                                                    <input type="hidden" name="id5" value="<?php echo $_GET['id'] ?>" />
                                                            
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_from5" value="<?php echo $date_from; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_to5" value="<?php echo $date_to; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn yellow"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                                        </div>
                                                    </div>                                                
                                                </form>                                                
                						    </div>
                                        </div>
                                        
                                        <div class="row">
                                        <div class="col-md-12 hidden">
                                        <form id="filter_todo" action="employee-profile.php#tab_expense" class="form-horizontal" method="GET">
                                        <!-- <input type="hidden" name="id" value="<?= $employee_login['id'] ?>" /> -->
                                        
                                        <div class="form-body ">
                                        <input type="hidden" name="date_from" value="<?php echo $date_from; ?>" />
                                        <input type="hidden" name="date_to" value="<?php echo $date_to; ?>" />
                                        
                                        <div class="form-group">
                                        <div class="col-md-2">
                                        <select class="form-control" name="status">
                                        <option value="">Filter</option>
                                        <option <?php if($_GET['status'] == "pending") echo "selected"; ?> value="pending">Pending</option>
                                        <option <?php if($_GET['status'] == "approved") echo "selected"; ?> value="approved">Approved</option>
                                        <option <?php if($_GET['status'] == "rejected") echo "selected"; ?> value="rejected">Rejected</option>
                                        <option <?php if($_GET['status'] == "paid") echo "selected"; ?> value="paid">Paid</option>
                                        </select>
                                        </div>
                                        <div class="col-md-2">
                                        <button type="submit" class="btn green"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                        </div>
                                        <div class="col-md-4">
                                        <button type="button" class="btn btn-default btn-sm select_all"><i class='fa fa-check-square-o'></i> Select All</button>
                                        <button type="button" class="btn btn-default btn-sm unselect_all"><i class='fa fa-square-o'></i> Deselect All</button>
                                        </div>
                                        <div class="col-md-4">
                                        <select class="form-control" name="bulk_status_change">
                                        <option value="">Bulk Status Update</option>
                                        <option value="approve">Approve</option>
                                        <option value="reject">Reject</option>
                                        <option value="paid">Paid</option>
                                        </select>
                                        </div>
                                        </div>
                                        </div>
                                        </form>
                                        </div>
                                        
                                        <?php
                                        $status     = "%".$_GET['status']."%";
                                        
                                        
                                        
                                        if(strlen($date_from) > 0 && strlen($date_to) > 0){
                                        $from_ts    = strtotime($date_from);
                                        $to_ts      = strtotime($date_to);
                                        $to_ts      = $to_ts+86400;
                                        
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where date_time >= ? AND date_time <= ? AND employee_id = ? AND status like ? order by(id) desc",
                                        array($from_ts,$to_ts,$employee_login['id'],$status), "rows");
                                        
                                        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_expense_refund where date_time >= ? AND date_time <= ? AND employee_id = ? AND status like ?");
                                        $result  		= $STH->execute(array($from_ts,$to_ts,$employee_login['id'],$status));
                                        $count_total	= $STH->fetchColumn();
                                        
                                        if((strlen($date_from) > 0 && strlen($date_to) > 0)){
                                        $filter_text = " - from ".$date_from." to ".$date_to;
                                        }
                                        
                                        //$filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_filter_xml</a>";
                                        
                                        
                                        }else{
                                            
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where employee_id = ? AND status like ? order by(id) desc",
                                        array($employee_login['id'],$status), "rows");
                                        $filter_text    = "";
                                        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_expense_refund where employee_id = ? AND status like ?");
                                        $result  		= $STH->execute(array($employee_login['id'],$status));
                                        $count_total	= $STH->fetchColumn();
                                        }
                                        
                                        
                                        $title = "Expense ($count_total)<small>$filter_text</small>";
                                        
                                        ?>
                                        <div class="portlet box">
                                        <div class="portlet-title">
                                        <div class="caption"><?= $title ?></div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        
                                        <?php if(count($rows) > 0){ ?>
                                        
                                        
                                        <table class="table table-striped table-hover table-bordered table-documents" id="expense_table">
                                        
                                        
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Thumbnail</th>
                                        <th>Expense</th>
                                        <th>Amount</th>
                                        <th>Date Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        
                                        $sno = 0;
                                        foreach($rows as $row){
                                        $sno++;
                                        $id					= $row['id'];
                                        $employee_id    	= $row['employee_id'];
                                        $title              = $row['title'];
                                        $amount             = $row['amount'];
                                        $attachment			= $row['attachment'];
                                        $date_time			= my_simple_date($row['date_time']);
                                        $status 			= $row['status'];
                                        $reason				= $row['reason'];
                                        $status_date_time   = my_simple_date($row['status_date_time']);
                                        
                                        //employee name
                                        $rows2 		= sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($employee_id), "rows");
                                        foreach($rows2 as $row2){
                                        $employee_name	= $row2['fullname'];
                                        }
                                        
                                        if($status == "pending" || $status == ""){
                                        $status_badge_text  = "<i class='fa fa-clock-o'></i> Pending";
                                        $status_badge_class = "default";
                                        $btn_approve    = "";
                                        $btn_reject     = "";
                                        $btn_paid       = "style='display:none'";
                                        $btn_no_action  = "style='display:none'";
                                        }else if($status == "approved"){
                                        $status_badge_text  = "<i class='fa fa-check'></i> Approved";
                                        $status_badge_class = "warning";
                                        $btn_approve    = "style='display:none'";
                                        $btn_reject     = "style='display:none'";
                                        $btn_paid       = "";
                                        $btn_no_action  = "style='display:none'";
                                        }else if($status == "rejected"){
                                        $status_badge_text  = "<i class='fa fa-times'></i> Rejected";
                                        $status_badge_class = "danger";
                                        $btn_approve    = "style='display:none'";
                                        $btn_reject     = "style='display:none'";
                                        $btn_paid       = "style='display:none'";
                                        $btn_no_action  = "";
                                        }else if($status == "paid"){
                                        $status_badge_text  = "<i class='fa fa-dollar'></i> Paid";
                                        $status_badge_class = "success";
                                        $btn_approve    = "style='display:none'";
                                        $btn_reject     = "style='display:none'";
                                        $btn_paid       = "style='display:none'";
                                        $btn_no_action  = "";
                                        }else if($status == "deleted"){
                                        $status_badge_text  = "<i class='fa fa-times'></i> Deleted: $status_date_time";
                                        $status_badge_class = "default";
                                        $btn_approve    = "style='display:none'";
                                        $btn_reject     = "style='display:none'";
                                        $btn_paid       = "style='display:none'";
                                        $btn_no_action  = "";
                                        }
                                        
                                        $action_btn 	= "<span $btn_approve expense_id='$id' action='approve' class='btn btn-warning btn-xs action_opt action_btn'>
                                        <i class='fa fa-check'></i> Approve
                                        </span>";
                                        
                                        $action_btn 	.= "<span $btn_reject expense_id='$id' action='reject' class='btn btn-danger btn-xs action_opt action_btn'>
                                        <i class='fa fa-times'></i> Reject
                                        </span>";
                                        
                                        $action_btn 	.= "<span $btn_paid expense_id='$id' action='paid' class='btn btn-success btn-xs action_opt action_btn'>
                                        <i class='fa fa-usd'></i> Pay
                                        </span>";
                                        
                                        if($status == "rejected"){
                                        $status .= ": <b>$reason</b>";
                                        }
                                        
                                        $action_btn 	.= "<span expense_id='$id' action='no_action' $btn_no_action class='action_opt'>".ucfirst($status)."</span>";
                                        
                                        $status_badge 	= "<span expense_id='$id' data-toggle='popover' title='".ucfirst($status)."' data-content='".$status_date_time."' expense_id='$id' class='expense_status badge badge-".$status_badge_class."'>".$status_badge_text."</span>";
                                        
                                        
                                        ?>
                                        
                                        <tr>
                                        <td>
                                        <div class="md-checkbox hidden">
                                        <input name="check_<?php echo $sno; ?>" value="<?php echo $id; ?>" type="checkbox" id="check_<?php echo $sno; ?>" class="md-check bulk_check" />
                                        <label for="check_<?php echo $sno; ?>">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> <?php echo $sno; ?>. </label>
                                        </div>
                                        <?php echo $sno."."; ?>
                                        </td>
                                        <td><a class="attachment" href="javascript:;" data-toggle="popover" title="<?php echo $title; ?>" data-img="<?php echo $attachment; ?>"><img class="thumbnail_img" src="<?php echo $attachment; ?>" alt="<?php echo $title; ?>" /></a></td>
                                        <td><?php echo $title; ?></td>
                                        <td>$<?php echo $amount; ?></td>
                                        <td><?php echo $date_time; ?></td>
                                        <td><?php echo $status_badge; ?></td>
                                        <td><?php echo $action_btn; ?></td>
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
                                        No expense from <?php echo $date_from ?> to <?php echo $date_to ?>!
                                        <br />
                                        </p>
                                        
                                        
                                        <?php } ?>
                                        </div>
                                        
                                        </div>
                                        
                                        </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        <!-- yahan -->
                                        <div class="tab-pane fade" id="tab_screenshots">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-picture-o"></i> Screenshots</h2>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            	<br />
                                                <form action="exe_emp_settings.php?type=tab_screenshots" method="POST">
                                                <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>" />
                                                
                                                <?php
                                                $rows = sql($DBH,"select * from tbl_screenshot_settings where employee_id = ?",
                                                array($employee_id),"rows");
                                                foreach($rows as $row){
                                                $ss_enable      = $row['ss_enable'];
                                                $ss_interval    = $row['ss_interval'];                                                      }
                                                
                                                ?>
                                                
                                                
                                                <h4>Screenshot Settings:</h4>
                                                <div class="row">
                                                <input type="hidden" value="<?= $employee_login['id'] ?>" name="employee_id" />
                                                <div class="col-md-4">
                                                <label>Enable Screenshot:</label>
                                                <div class="md-checkbox">
                                                <input name="ss_enable" value="true" <?php if($ss_enable == "true") echo "checked"; ?> type="checkbox" id="checkbox_1" class="md-check access_check" />
                                                <label for="checkbox_1">
                                                <span></span>
                                                <span class="check"></span>
                                                <span class="box"></span> Yes </label>
                                                </div>
                                                </div>
                                                <div class="col-md-4">
                                                <label>Screenshot Interval:</label>
                                                <select name="ss_interval" class="form-control">
                                                <option <?php if($ss_interval == "6"){echo "selected";} ?> value="6">6 Minutes</option>
                                                <option <?php if($ss_interval == "12"){echo "selected";} ?> value="12">12 Minutes</option>
                                                <option <?php if($ss_interval == "18"){echo "selected";} ?> value="18">18 Minutes</option>
                                                <option <?php if($ss_interval == "24"){echo "selected";} ?> value="24">24 Minutes</option>
                                                <option <?php if($ss_interval == "30"){echo "selected";} ?> value="30">30 Minutes</option>
                                                </select>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                <label>&nbsp;</label><br />
                                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save Settings</button>
                                                
                                                </div>
                                                </div>
                                                </form>
                                        
                                                                                       
                						    </div>
                                        </div>
                                        
                                        <hr />
                                        
                                        <form action="" class="form-horizontal filter_form4" method="GET">
                                            <input type="hidden" name="page4" value="tab_screenshots" />
                                            <input type="hidden" name="id4" value="<?php echo $_GET['id'] ?>" />
                                                    
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input class="form-control" type="date" name="date_from4" value="<?php echo $date_from; ?>" />
                                                </div>
                                                <div class="col-md-3">
                                                    <input class="form-control" type="date" name="date_to4" value="<?php echo $date_to; ?>" />
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn yellow"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                                </div>
                                            </div>                                                
                                        </form> 
                                        
                                        <hr />
                                        <h4>Screenshots:</h4>
                                        <div class="">
                                        
                                        <?php
                                        $from_ts    = strtotime($date_from);
                                        $to_ts      = strtotime($date_to);
                                        $to_ts      = $to_ts+86400;
                                        
                                        
                                        $rows = sql($DBH,"SELECT * FROM tbl_screenshot where employee_id = ? and date_time >= ? and date_time <= ? order by (date_time) desc",
                                        array($employee_id,$from_ts,$to_ts),"rows");
                                        
                                        $SEPERATE_hours_old = null;
                                        
                                        if(count($rows) > 0){
                                                
                                            $hrs_seperator_start = false;
                                            
                                            foreach($rows as $row){ 
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
                                                $thumb  = $row['image'].$resize_thumb;
                                                $hrs    = date("h:i A",$row['date_time']);                                                
                                                                                               
                                                echo '<div class="col-md-3">
                                                <img class="screenshot" src="'.$thumb.'" image="'.$image.'" alt="Screenshot at '.$hrs.'">
                                                <p class="my-placeholder">'.date("h:i A",$row['date_time']).'</p>
                                                </div>';   
                                                
                                                $SEPERATE_hours_old = $SEPERATE_hours;
                                            }
                                            
                                            if($hrs_seperator_start == true){
                                                echo "<div class='clearfix'></div>";
                                                echo "</div>";
                                                $hrs_seperator_start = false;
                                            }                             
                                        
                                        }else{ 
                                            ?>
                                        
                                        <p class="text-center text-muted">
                                        <i class="fa fa fa-picture-o fa-3x"></i>
                                        <br />
                                        <br />
                                        No screenshots from <?php echo $date_from ?> to <?php echo $date_to ?>!
                                        <br />
                                        </p>
                                        
                                        <?php } ?>
                                        
                                        
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
                                        
                                        
                                        </div>
                                        <div class="tab-pane fade" id="tab_attendance">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-clock-o"></i> Attendance </h2>
                                        </div>
                                        </div>
                                        <br /><br />
                                        <table class="table table-striped table-bordered table-hover table-bordered" id="sample_2">
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>Attachment</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        <?php
                                        $rows = sql($DBH, "SELECT * FROM tbl_attendance where employee_id = ?",
                                        array($employee_id), "rows");
                                        $i = 0;
                                        foreach($rows as $row){
                                            $e_id           = $row['employee_id'];
                                            $date_time      = date("d-M-Y",$row['date_time']);
                                            $attachment     = $row['attachment'];
                                            
                                            $i++;
                                            echo "<tr>
                                            <td>".$i."</td>
                                            <td><a class='attachment' href='javascript:;' data-toggle='popover' title='' data-img='".$attachment."' data-original-title=''><img class='thumbnail_img' src='".$attachment."'></a></td>
                                            <td>".date("d-M-Y",$row['date_time'])."</td>
                                            <td>".date("h:i:s A",$row['date_time'])."</td>
                                            </tr>" ;
                                        }
                                        ?>
                                        
                                        </tbody>
                                        </table>
                                        </div>
                                        
                                        
                                        
                                        <!-- start -->
                                        <div class="tab-pane fade" id="tab_locations">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-map-marker"></i> Locations</h2>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            	<br />
                                                <form action="" class="form-horizontal filter_form2" method="GET">
                                                    <input type="hidden" name="page2" value="tab_locations" />
                                                    <input type="hidden" name="id2" value="<?php echo $_GET['id'] ?>" />
                                                            
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_from2" value="<?php echo $date_from; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_to2" value="<?php echo $date_to; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn yellow"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                                        </div>
                                                    </div>                                                
                                                </form>                                                
                						    </div>
                                        </div>
                                        <br />
                                        
                                        
                                        <div class="row">
                                        <div class="col-md-12 hidden">
                                        <div class=""> <!-- portlet box green -->
                                        <div class=""> <!-- portlet-body form -->
                                        <!-- BEGIN FORM-->
                                        <form id="filter_todo3" action="" class="form-horizontal" method="GET">
                                        <div class="form-body">
                                        <input type="hidden" name="date_from" value="<?php echo $date_from; ?>" />
                                        <input type="hidden" name="date_to" value="<?php echo $date_to; ?>" />
                                        <div class="form-group">
                                        <div class="col-md-3">
                                            <!--
                                            <div id="reportrange" class="btn default">
                                            <i class="fa fa-calendar"></i> &nbsp;
                                            <span> </span>
                                            <b class="fa fa-angle-down"></b>
                                            </div>
                                            -->
                                        </div>
                                        <div class="col-md-6 pull-right">
                                        <button type="submit" class="btn green"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                        </div>
                                        </div>
                                        </div>
                                        </form>
                                        <!-- END FORM-->
                                        </div>
                                        </div>
                                        </div>
                                        
                                        <?php
                                            if((strlen($date_from) > 0 && strlen($date_to) > 0)){
                                        $from_ts    = strtotime($date_from);
                                        $to_ts      = strtotime($date_to);
                                        $to_ts      = $to_ts+86400;
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_locations
                                        where employee_id = ? && (date_time >= ? AND date_time <= ? ) order by (date_time) asc",
                                        array($employee_id,$from_ts,$to_ts), "rows");
                                        
                                        if((strlen($date_from) > 0 && strlen($date_to) > 0)){
                                        $filter_text = " $from_xml ".$date_from." $to_xml ".$date_to;
                                        }
                                        $filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_xml</a>";
                                        
                                        
                                        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
                                        where employee_id = ? && (date_time >= ? AND date_time <= ? ) order by (date_time) asc");
                                        $result  		= $STH->execute(array($employee_id,$from_ts,$to_ts));
                                        $count_total	= $STH->fetchColumn();
                                        }else{
                                            
                                        $from_ts    = strtotime(date("m/d/Y"));
                                        $to_ts      = strtotime(date("m/d/Y"));
                                        
                                        $from_ts    = $from_ts-86400;  //18-aug to (today is 19th)
                                        $to_ts      = $to_ts+86400;    //20-aug
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_locations
                                        where employee_id = ? order by (date_time) asc",
                                        array($employee_id), "rows");
                                        
                                        $filter_text = "$showing_last_hours_xml"; //$showing_all_records_xml
                                        
                                        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
                                        where employee_id = ? && (date_time >= ? AND date_time <= ? ) order by (date_time) asc");
                                        $result  		= $STH->execute(array($employee_id,$from_ts,$to_ts));
                                        $count_total	= $STH->fetchColumn();
                                        }
                                        
                                        $locations        = array();
                                        $sync_date_time   = "$never_xml";
                                        $time_count       = 0;
                                        $i                = 0;
                                        
                                        
                                          $old_lat = null;
                                          $old_lon = null;
                                          $old_ts = null;
                                          $total_trip_distance = 0;
                                        
                                        
                                        foreach($rows as $row){
                                            
                                            $sync_data			    = json_decode($row['data'],true);
                                            $movements			    = $row['movements'];
                                            $location_timestamp     = $row['date_time'];
                                            $sync_date_time		    = date("d-M-Y h:i A",$location_timestamp);
                                            $latitude               = $sync_data['coords']['latitude'];
                                            $longitude              = $sync_data['coords']['longitude'];
                                            $accuracy               = $sync_data['coords']['accuracy'];
                                            $altitude               = $sync_data['coords']['altitude'];
                                            $heading                = $sync_data['coords']['heading'];
                                            $speed                  = round($sync_data['coords']['speed'],2);
                                            $altitudeAccuracy       = $sync_data['coords']['altitudeAccuracy'];
                                            $gps_timestamp          = round($sync_data['timestamp']/1000);    
                                            
                                            //$lat_lng[]              = array($latitude,$longitude);                                                
                                            
                                            if($movements == 0){
                                            $movements_show= "<i class='fa'><img src='../img/sitting.png'/></i> Sitting";
                                            }else if($movements > 0 && $movements <= 10){
                                            $movements_show= "<i class='fa'><img src='../img/walking.png'/></i> Walking";
                                            }else if($movements > 10){
                                            $movements_show= "<i class='fa'><img src='../img/running.png'/></i> Running";
                                            }
                                            
                                            $locations[$i]['id']                    = $row['id'];
                                            $locations[$i]['latitude']              = $latitude;
                                            $locations[$i]['longitude']             = $longitude;
                                            $locations[$i]['accuracy']              = $accuracy;
                                            $locations[$i]['altitude']              = $altitude;
                                            $locations[$i]['heading']               = $heading;
                                            $locations[$i]['speed']                 = $speed;
                                            $locations[$i]['altitudeAccuracy']      = $altitudeAccuracy;
                                            $locations[$i]["date_time"]             = $sync_date_time;
                                            $time_count = 0;//place time counter
                                            
                                            
                                            if($old_lat == null || $old_lon == null){
                                              $distance = "Starting Point";
                                            }else{
                                              $distance  = haversineGreatCircleDistance( $old_lat, $old_lon, $latitude, $longitude);
                                              $time      = round($gps_timestamp-$old_ts,2);
                                              $speed     = round((($distance/$time)*60*60)/1000,2);
                                        
                                              if($speed >= 100){ //too much speed
                                                  continue;
                                              }
                                              if($time <= 0){ //old data
                                                  continue;
                                              }
                                              if($distance <= 10){
                                                  continue;
                                              }
                                            }
                                            
                                            //playable
                                            $lat_lng[]              = array($latitude,$longitude);
                                            
                                            
                                            //for map
                                            $locations[$i]["data"]       = "<i class='fa fa-clock-o'></i> Date Time: ".date("H:i:s a",$gps_timestamp)."</br>";
                                            $locations[$i]["data"]       .= "<i class='fa fa-street-view'></i> Accuracy: ".round($accuracy)."m</br>";
                                            //$locations[$i]["data"]       .= $movements_show."</br>";
                                            $locations[$i]["data"]       .= "<i class='fa fa-car'></i> Speed: $speed m/s";
                                            //for map  
                                            $i++; 
                                            
                                            
                                            
                                            
                                            
                                            
                                            //save for next iteration
                                            $total_trip_distance += $distance;
                                            $old_lat = $latitude;
                                            $old_lon = $longitude;
                                            $old_ts  = $gps_timestamp;
                                            
                                        }
                                        ?>
                                        
                                        <div class="col-md-4 hidden" style="max-height: 600px;overflow-y: auto;padding: 0 0 0 6px;">
                                            <?php
                                                foreach($locations as $location){
                                                    echo "<div style='margin:0 0 5px 0' class='note note-info'>".($location['data'])."</div>";
                                                }
                                                
                                                if(count($locations) == 0){
                                                echo "<div style='margin:0 0 5px 0' class='note note-danger'> No Location History</div>";
                                                }
                                            ?>
                                        </div>
                                        
                                        <div class="col-md-12">
                                        <div class="portlet-body">
                                        
                                        
                                            <div class="col-md-11">
                                                <input type="range" min="1" max="29" value="0" class="slider" id="myRange" />
                                            </div>
                                            
                                            <div class="col-md-1">
                                                <button class="btn purple btn-circle location_play" style="position: relative;
    top: -8px;">
                                                    <i class="fa fa-play"></i>
                                                </button>
                                            </div>    
                                                                                
                                        
                                        
                                        <div id="map" style="width: 100%;">
                                        <?php
                                            if(count($count_total) == 0){
                                                echo "<br /><br /><h1 class='page-title text-center text-danger'>$no_locations_xml</h1>";
                                            }
                                        ?>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        <!-- end -->
                                        
                                        
                                        <div class="tab-pane fade" id="tab_time_off">
                                        <div class="row">
                                        <div class="col-md-6">
                                        <h2 class="page-title no_margin"> <i class="fa fa-tags"></i> Time Off Settings & History</h2>
                                        </div>
                                        <div class="col-md-6">
                                        <a class="btn green btn-sm pull-right" data-toggle="modal" href="#modal_time_off">
                                        <i class="fa fa-plus"></i> Add Time Off
                                        </a>
                                        </div>
                                        </div>
                                        <hr />
                                        
                                        <form action="exe_emp_settings.php?type=vacation" method="POST">
                                        <input type="hidden" name="employee_id" value="<?= $employee_login['id'] ?>" />
                                        <h4>Vacation Settings</h4>
                                        <div class="row">
                                        <div class="col-md-4">
                                        <label>Allowed Sick Leaves:</label>
                                        <input type="number" name="allowed_sick" value="<?php echo $allowed_sick; ?>" class="form-control" />
                                        </div>
                                        <div class="col-md-4">
                                        <label>Allowed Vacation Leaves:</label>
                                        <input type="number" name="allowed_vacation" value="<?php echo $allowed_vacation; ?>" class="form-control" />
                                        </div>
                                        <div class="col-md-4">
                                        <label>&nbsp;</label><br />
                                        <button type="submit" class="btn btn-info btn-sm pull-right"><i class="fa fa-check"></i> Save Settings</button>
                                        </div>
                                        </div>
                                        </form>
                                        
                                        <hr />
                                        
                                        
                                        <?php
                                        $sick_percentage          = round(($sick_leave_count*100)/$allowed_sick,2);
                                        $vacation_percentage      = round(($vacation_leave_count*100)/$allowed_vacation,2);
                                        $total_leave_percentage   = round(($days_leave_count*100)/$days_leave_allowed,2);
                                        
                                        if($sick_percentage > 100){
                                        $sick_percentage = 100;
                                        }
                                        
                                        if($vacation_percentage > 100){
                                        $vacation_percentage = 100;
                                        }
                                        
                                        if($total_leave_percentage > 100){
                                        $total_leave_percentage = 100;
                                        }
                                        
                                        
                                        ?>
                                        
                                        <h4>Approved Sick Leaves: <kbd class="pull-right"><?php echo $sick_leave_count."/".$allowed_sick ?></kbd><h4>
                                        <div class="leave_bar">
                                        <div style="width:<?php echo $sick_percentage; ?>%" class="progress_red"></div>
                                        <span><?php echo $sick_percentage."%"; ?></span>
                                        </div>
                                        
                                        <h4>Approved Vacation Leaves: <kbd class="pull-right"><?php echo $vacation_leave_count."/".$allowed_vacation ?></kbd><h4>
                                        <div title="A" class="leave_bar">
                                        <div style="width:<?php echo $vacation_percentage; ?>%" class="progress_blue"></div>
                                        <span><?php echo $vacation_percentage."%"; ?></span>
                                        </div>
                                        
                                        <h4>Total Approved Leaves (Sick+Vacation): <kbd class="pull-right"><?php echo $days_leave_count."/".$days_leave_allowed ?></kbd><h4>
                                        <div title="A" class="leave_bar">
                                        <div style="width:<?php echo $total_leave_percentage; ?>%" class="progress_orange"></div>
                                        <span><?php echo $total_leave_percentage."%"; ?></span>
                                        </div>
                                        
                                        
                                        
                                        <hr />
                                        <h4>Time Off History</h4>
                                        <?php if(count($data_time_off) > 0){ ?>
                                        
                                        <table class="table table-hover table-bordered table-striped" id="time_off_history_table" style="margin-bottom: 0px">
                                        <thead>
                                        <tr>
                                        <th>Sno</th>
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
                                        
                                        
                                        <div class="tab-pane fade" id="tab_contacts">
                                        <?php
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
                                        ?>
                                        
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-book"></i> Contacts <small>Last Sync: <?php echo $sync_contacts_date_time; ?></small></h2>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="portlet box">
                                        <div class="portlet-title">
                                        <div class="caption">Contacts</div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <table class="table table-striped table-hover table-bordered" id="table-contacts">
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th><?php echo $display_name_xml; ?></th>
                                        <th><?php echo $phone_number_xml; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        <?php
                                        $sno = 0;
                                        foreach($sync_contacts_data as $row){
                                        $sno++;
                                        $displayName		= $row['displayName'];
                                        $phoneNumbers    	= $row['phoneNumbers'];
                                                                                                                       
                                        
                                        echo "<tr>
                                        <td>$sno</td>
                                        <td>$displayName</td>
                                        <td>";
                                        foreach($phoneNumbers as $numbers){
                                        $type 				= $numbers['type'];
                                        $normalizedNumber 	= $numbers['normalizedNumber'];
                                        if($normalizedNumber == ""){
                                        $normalizedNumber = "$no_number_xml";
                                        }
                                        if($type == "HOME"){
                                        echo "<i class='fa fa-home'></i> ".$normalizedNumber;
                                        }else if($type == "MOBILE"){
                                        echo "<i class='fa fa-mobile'></i> ".$normalizedNumber;
                                        }else{
                                        echo "$type: ".$normalizedNumber;
                                        }
                                        echo "<br/>";
                                        }
                                        echo "</td>
                                        </tr>";
                                        }
                                        ?>
                                        
                                        </tbody>
                                        </table>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        
                                        
                                        
                                        
                                        
                                        
                                        <div class="tab-pane fade" id="tab_call_log">
                                        <?php
                                        $users_call_log_xml          = $xml->call_logs->users_call_log;
                                        $last_synced_xml             = $xml->call_logs->last_synced;
                                        $never_synced_xml            = $xml->call_logs->never_synced;
                                        $entries_xml                 = $xml->call_logs->entries;
                                        $search_colon_xml            = $xml->call_logs->search_colon;
                                        $display_name_xml            = $xml->call_logs->display_name;
                                        $phone_number_xml            = $xml->call_logs->phone_number;
                                        $type_xml                    = $xml->call_logs->type;
                                        $duration_xml                = $xml->call_logs->duration;
                                        $date_xml                    = $xml->call_logs->date;
                                        $missed_xml                  = $xml->call_logs->missed;
                                        $dailed_xml                  = $xml->call_logs->dailed;
                                        $received_xml                = $xml->call_logs->received;
                                        $no_data_xml                 = $xml->call_logs->no_data;
                                        $access_denied_xml           = $xml->call_logs->access_denied;
                                        $no_access_xml               = $xml->call_logs->no_access;
                                        
                                        $sync_data = null;
                                        
                                        ?>
                                        
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-phone"></i> Call Logs / Phone State </h2>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="portlet box">
                                        <div class="portlet-title">
                                        <div class="caption">Call Logs / Phone State</div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <table class="table table-striped table-hover table-bordered table-documents" id="call_logs_table"> 
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>State</th>
                                        <th>GPS Location</th>
                                        <th>Date Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        <?php
                                        $rows = sql($DBH, "SELECT * FROM tbl_phone_state where employee_id = ?", array($employee_login['id']), "rows");
                                        $sync_date_time = "never";
                                        $sno = 0;
                                        foreach($rows as $row){
                                            $sno++;
                                            $state          = $row['state'];
                                            $gps			= json_decode($row['gps'],true);
                                            $date_time		= my_simple_date($row['date_time']);
                                        
                                        
                                            if ($state == "IDLE") {
                                                $type = "<span class='text-primary'><i style='font-size:18px;' class='fa fa-clock-o'></i> Idle</span>";
                                            } else if ($state == "RINGING") {
                                                $type = "<span class='text-success'><i style='font-size:18px;' class='fa fa-bell'></i> Ringing</span>";
                                            } else if ($state == "OFFHOOK") {
                                                $type = "<span class='text-danger'><i style='font-size:18px;' class='fa fa-phone'></i> Off-hook</span>";
                                            }
                                            //<a class='show_map' lat='24.9195966' lon='67.1190969' data-toggle='modal' href='#modal_gps'>Show Map</a></td>
                                            
                                            
                                            echo "<tr>
                                            <td>$sno</td>
                                            <td>$type</td>
                                            <td>".$gps['coords']['latitude'].",".$gps['coords']['longitude']." (Accuracy:".$gps['coords']['accuracy'].")</td>
                                            <td>$date_time</td>
                                            </tr>";
                                        }
                                        ?>
                                        
                                        </tbody>
                                        </table>
                                        </div>
                                        </div>
                                        </div>
                                        
                                        </div>
                                        
                                        <div class="tab-pane fade" id="tab_text_msg">
                                        <?php
                                        $text_messages_xml           = $xml->text_messages->users_text_messages;
                                        $last_synced_xml             = $xml->text_messages->last_synced;
                                        $never_synced_xml            = $xml->text_messages->never_synced;
                                        $entries_xml                 = $xml->text_messages->entries;
                                        $search_colon_xml            = $xml->text_messages->search_colon;
                                        $name_xml                    = $xml->text_messages->name;
                                        $number_xml                  = $xml->text_messages->number;
                                        $date_xml                    = $xml->text_messages->date;
                                        $status_xml                  = $xml->text_messages->status;
                                        $body_xml                    = $xml->text_messages->body;
                                        $application_xml             = $xml->text_messages->application;
                                        $no_data_xml                 = $xml->text_messages->no_data;
                                        $access_denied_xml           = $xml->text_messages->access_denied;
                                        $no_access_xml               = $xml->text_messages->no_access;
                                        $received_xml                = $xml->text_messages->received;
                                        $sent_xml                    = $xml->text_messages->sent;
                                        $draft_xml                   = $xml->text_messages->draft;
                                        $outbox_xml                  = $xml->text_messages->outbox;
                                        $failed_xml                  = $xml->text_messages->failed;
                                        $queued_xml                  = $xml->text_messages->queued;
                                        $read_xml                    = $xml->text_messages->read;
                                        $not_read_yet_xml            = $xml->text_messages->not_read_yet;
                                        
                                        $sync_data_contacts = null;
                                        $rows = sql($DBH, "SELECT * FROM tbl_contacts where employee_id = ?", array($employee_login['id']), "rows");
                                        foreach($rows as $row){
                                        $sync_data_contacts			= json_decode($row['data'],true);
                                        }
                                        
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_text_msgs where employee_id = ?", array($employee_login['id']), "rows");
                                        $sync_date_time = " <small>$never_synced_xml</small>";
                                        foreach($rows as $row){
                                        $sync_data			= json_decode($row['data'],true);
                                        $sync_date_time		= my_simple_date($row['date_time']);
                                        }
                                        ?>
                                        
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-envelope"></i> Text Messages <small>Last Sync: <?php echo $sync_date_time; ?></small></h2>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="portlet box">
                                        <div class="portlet-title">
                                        <div class="caption">Call Logs</div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <table class="table table-striped table-hover table-bordered table-documents" id="text_msgs_table">
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th><?php echo $name_xml; ?>/<?php echo $number_xml; ?></th>
                                        <th><?php echo $date_xml; ?></th>
                                        <th style="width:60px;"><?php echo $status_xml; ?></th>
                                        <th><?php echo $body_xml; ?></th>
                                        <th><?php echo $application_xml; ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        <?php
                                        $sno = 0;
                                        foreach($sync_data as $row){
                                        $sno++;
                                        $number			= $row['address'];
                                        $name           = "";
                                        
                                        $name_found = false;
                                        foreach($sync_data_contacts as $row1){
                                        $displayName		= $row1['displayName'];
                                        foreach($row1['phoneNumbers'] as $numbers){
                                        if($numbers['normalizedNumber'] == $number){
                                        $name_found = true;
                                        $name       = $displayName;
                                        break;
                                        }
                                        }
                                        if($name_found == true){
                                        break;
                                        }
                                        }
                                        
                                        $date			= my_simple_date(round($row['date']/1000));
                                        //$date_sent		= my_simple_date(round($row['date_sent']/1000));
                                        $type    		= $row['type'];
                                        if ($type == 1) {
                                        $type = "<i class='fa fa-envelope'></i> $received_xml";
                                        } else if ($type == 2) {
                                        $type = "<i class='fa fa-check'></i> $sent_xml";
                                        } else if ($type == 3) {
                                        $type = "<i class='fa fa-save'></i> $drft_xml";
                                        } else if ($type == 4) {
                                        $type = "<i class='fa fa-check'></i> $outbox_xml";
                                        } else if ($type == 5) {
                                        $type = "<i class='fa fa-times'></i> $failed_xml";
                                        } else if ($type == 6) {
                                        $type = "<i class='fa fa-clock-o'></i> $queued_xml";
                                        } else if ($type == 7) {
                                        $type = $type;
                                        }
                                        $body			= $row['body'];
                                        $seen			= $row['seen'];
                                        $application	= $row['creator'];
                                        
                                        if($seen == 1){
                                        $seen = "$red_xml";
                                        }else{
                                        $seen = "$not_read_yet_xml";
                                        }
                                        
                                        echo "<tr>
                                        <td>$sno</td>
                                        <td>$name $number</td>
                                        <td>$date</td>
                                        <td>$type</td>
                                        <td>$body</td>
                                        <td>$application</td>
                                        </tr>";
                                        }
                                        ?>
                                        
                                        </tbody>
                                        </table>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        
                                        <div class="tab-pane fade" id="tab_installed_apps">
                                        <?php
                                        $installed_apps_xml         = $xml->installed_apps->users_installed_apps;
                                        $last_synced_xml            = $xml->installed_apps->last_synced;
                                        $never_synced_xml           = $xml->installed_apps->never_synced;
                                        $entries_xml                = $xml->installed_apps->entries;
                                        $search_colon_xml           = $xml->installed_apps->search_colon;
                                        $app_name_xml               = $xml->installed_apps->app_name;
                                        $app_icon_xml               = $xml->installed_apps->app_icon;
                                        $no_data_xml                = $xml->installed_apps->no_data;
                                        $app_id_xml                 = $xml->installed_apps->app_id;
                                        $app_package_name_xml       = $xml->installed_apps->app_package_name;
                                        $action_xml                 = $xml->installed_apps->action;
                                        $blocked_xml                = $xml->installed_apps->blocked;
                                        $unblocked_xml              = $xml->installed_apps->unblocked;
                                        $access_denied_xml          = $xml->installed_apps->access_denied;
                                        $no_access_xml              = $xml->installed_apps->no_access;
                                        
                                        
                                        
                                        ?>
                                        
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-android"></i> Installed Apps <small>Last Sync: <?php echo $sync_app_date_time; ?></small></h2>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <div class="portlet box">
                                        <div class="portlet-title">
                                        <div class="caption">Installed Apps</div>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <table class="table table-striped table-hover table-bordered table-documents" id="expense_table">
                                        <thead>
                                        <tr>
                                        <th>#</th>
                                        <th>App Name</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        
                                        <?php
                                        $sno = 0;
                                        foreach($sync_app_data as $key => $value){
                                        $sno++;
                                        
                                        $value_explode = explode(";",$value);
                                        $package_name   = $value_explode[0];
                                        $app_name       = $value_explode[1];
                                        
                                        
                                        
                                        echo "
                                        <tr>
                                        <td>$sno</td>
                                        <td>
                                        <a data-toggle='tooltip' data-placement='right' title='$package_name' href='https://play.google.com/store/apps/details?id=$package_name' target='_blank'>$app_name</a>
                                        </td>
                                        </tr>";
                                        
                                        /*
                                        
                                        <td>
                                        <a href='ajax/active_inactive.php?package_name=$package_name&type=app' class='btn btn-".$action_btn_class." btn-xs btn-circle active_inactive'>
                                        $text
                                        </a>
                                        </td>
                                        */
                                        }
                                        ?>
                                        
                                        </tbody>
                                        </table>
                                        </div>
                                        </div>
                                        </div>
                                        
                                        </div>
                                        
                                        <div class="tab-pane fade" id="tab_video">
                                        <?php
                                        $users_videos_xml      = $xml->videos->users_videos;
                                        $access_denied_xml     = $xml->videos->access_denied;
                                        $no_access_xml         = $xml->videos->no_access;
                                        $add_video_xml         = $xml->videos->add_video;
                                        $title_colon_xml       = $xml->videos->title_colon;
                                        $add_video_colon_xml   = $xml->videos->add_video_colon;
                                        $save_xml              = $xml->videos->save;
                                        $close_xml             = $xml->videos->close;
                                        ?>
                                        
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-video-camera"></i> Videos</h2>
                                        </div>
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            	<br />
                                                <form action="" class="form-horizontal filter_form3" method="GET">
                                                    <input type="hidden" name="page3" value="tab_video" />
                                                    <input type="hidden" name="id3" value="<?php echo $_GET['id'] ?>" />
                                                            
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_from3" value="<?php echo $date_from; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_to3" value="<?php echo $date_to; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn yellow"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                                        </div>
                                                    </div>                                                
                                                </form>                                                
                						    </div>
                                        </div>
                                        <div class="row">
                                        <style>
                                        .my-videos{
                                        width:100%;
                                        max-width:300px;
                                        padding-bottom:5px;
                                        height:200px;
                                        }
                                        .hide{
                                        
                                        display:none;
                                        
                                        }
                                        </style>
                                        <?php 
                                        
                                        $from_ts    = strtotime($date_from);
                                        $to_ts      = strtotime($date_to);
                                        $to_ts      = $to_ts+86400;
                                        
                                        
                                        $rows = sql($DBH,"SELECT * FROM tbl_videos where employee_id = ? and time >= ? and time <= ?",
                                        array($employee_login['id'],$from_ts,$to_ts),"rows");
                                        if(count($rows) > 0){
                                        foreach($rows as $row){ ?>
                                        <div class="col-md-3">
                                        <video class="my-videos" controls>
                                        <source src="<?php echo $row['video']; ?>" type="video/mp4" />
                                        
                                        
                                        </video>
                                        </div>
                                        <?php }
                                        }
                                        else{
                                        ?>
                                        <div>
                                        <h4 style="text-align:center;">No videos from <?php echo $date_from ?> to <?php echo $date_to ?></h4>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                        </div>
                                        
                                        </div>
                                        
                                        <div class="tab-pane fade" id="tab_photos">
                                        <?php
                                        $users_photos_xml         = $xml->photos->users_photos;
                                        $edit_xml                 = $xml->photos->edit;
                                        $access_denied_xml        = $xml->photos->access_denied;
                                        $no_access_xml            = $xml->photos->no_access;
                                        $add_photos_xml           = $xml->photos->add_photos;
                                        $title_colon_xml          = $xml->photos->title_colon;
                                        $add_photos_colon_xml     = $xml->photos->add_photos_colon;
                                        $save_xml                 = $xml->photos->save;
                                        $close_xml                = $xml->photos->close;
                                        ?>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2 class="page-title no_margin"> <i class="fa fa-camera"></i> Photos</h2>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            	<br />
                                                <form action="" class="form-horizontal filter_form1" method="GET">
                                                    <input type="hidden" name="page1" value="tab_photos" />
                                                    <input type="hidden" name="id1" value="<?php echo $_GET['id'] ?>" />
                                                            
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_from1" value="<?php echo $date_from; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input class="form-control" type="date" name="date_to1" value="<?php echo $date_to; ?>" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="submit" class="btn yellow"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                                        </div>
                                                    </div>                                                
                                                </form>                                                
                						    </div>
                                        </div>
                                        
                                        <BR />
                                        <?php   
                                        $from_ts    = strtotime($date_from);
                                        $to_ts      = strtotime($date_to);
                                        $to_ts      = $to_ts+86400;
                                                                           
                                        $rows = sql($DBH,"SELECT * FROM tbl_photos where employee_id = ? and time >= ? and time <= ?",
                                        array($employee_login['id'],$from_ts,$to_ts),"rows");
                                        
                                        //yahan
                                        
                                        if(count($rows) > 0){
                                            foreach($rows as $row){
                                                $image  = $row['image'];
                                                $thumb  = $row['image'].$resize_thumb;   
                                                
                                                ?>
                                                <div class="col-md-3">                                        
                                                    <img class="my-photos" src="<?php echo $thumb; ?>" image="<?php echo $image; ?>" alt="" />
                                                    <p class="my-placeholder"><?php echo my_simple_date($row['time']); ?></p>
                                                </div>
                                            <?php
                                            }
                                        }
                                        else{
                                        ?>
                                            <div>
                                                <h4 style="text-align:center;">No pictures from <?php echo $date_from ?> to <?php echo $date_to ?></h4>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                        </div>
                                        
                                        <div class="tab-pane fade" id="tab_device">
                                        <?php
                                        $device_management_xml              = $xml->device_management->device_management;
                                        $device_information_xml             = $xml->device_management->device_information;
                                        $battery_xml                        = $xml->device_management->battery;
                                        $version_xml                        = $xml->device_management->version;
                                        $last_update_xml                    = $xml->device_management->last_update;
                                        $phone_name_xml                     = $xml->device_management->phone_name;
                                        $platform_xml                       = $xml->device_management->platform;
                                        $subscribed_untill_xml              = $xml->device_management->subscribed_untill;
                                        $internal_memory_xml                = $xml->device_management->internal_memory;
                                        $free_xml                           = $xml->device_management->free;
                                        $occupied_xml                       = $xml->device_management->occupied;
                                        $occupied_space_xml                 = $xml->device_management->occupied_space;
                                        $free_space_xml                     = $xml->device_management->free_space;
                                        $available_network_xml              = $xml->device_management->available_network;
                                        $operator_name_xml                  = $xml->device_management->operator_name;
                                        $device_id_xml                      = $xml->device_management->device_id;
                                        $phone_model_xml                    = $xml->device_management->phone_model;
                                        $sd_mempry_xml                      = $xml->device_management->sd_mempry;
                                        $device_settings_xml                = $xml->device_management->device_settings;
                                        $device_settings_help_xml           = $xml->device_management->device_settings_help;
                                        $update_interval_xml                = $xml->device_management->update_interval;
                                        $location_update_interval_xml       = $xml->device_management->location_update_interval;
                                        $call_logs_xml                      = $xml->device_management->call_logs;
                                        $browser_bookmarks_xml              = $xml->device_management->browser_bookmarks;
                                        $installed_apps_xml                 = $xml->device_management->installed_apps;
                                        $sms_xml                            = $xml->device_management->sms;
                                        $address_book_xml                   = $xml->device_management->address_book;
                                        $photos_xml                         = $xml->device_management->photos;
                                        $video_xml                          = $xml->device_management->video;
                                        $viber_xml                          = $xml->device_management->viber;
                                        
                                        $charging_xml                       = $xml->device_management->charging;
                                        $not_charging_xml                   = $xml->device_management->not_charging;
                                        $data_type_xml                      = $xml->device_management->data_type;
                                        $sync_in_xml                        = $xml->device_management->sync_in;
                                        $sync_time_xml                      = $xml->device_management->sync_time;
                                        $sync_over_xml                      = $xml->device_management->sync_over;
                                        $wifi_net_xml                       = $xml->device_management->wifi_net;
                                        $platform_xml                       = $xml->device_management->platform;
                                        $uuid_xml                           = $xml->device_management->uuid;
                                        $model_xml                          = $xml->device_management->model;
                                        $manufacturer_xml                   = $xml->device_management->manufacturer;
                                        $serial_xml                         = $xml->device_management->serial;
                                        $save_xml                           = $xml->device_management->save;
                                        $wifi_network_xml                   = $xml->device_management->wifi_network;
                                        $wi_fi_xml                          = $xml->device_management->wi_fi;
                                        $mob_data_xml                       = $xml->device_management->mob_data;
                                        $free_disk_space_xml                = $xml->device_management->free_disk_space;
                                        $network_con_xml                    = $xml->device_management->network_con;
                                        $access_denied_xml                  = $xml->device_management->access_denied;
                                        $no_access_xml                      = $xml->device_management->no_access;
                                        
                                        
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($employee_login['id']), "rows");
                                        $sim_serial = " <small>Not Logged In Yet</small>";
                                        foreach($rows as $row){
                                        $sim_serial		= $row['sim_serial'];
                                        }
                                        
                                        
                                        $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_options where employee_id =  ?");
                                        $result 	   	         = $STH->execute(array($employee_login['id']));
                                        $count_data_options	     = $STH->fetchColumn();
                                        if($count_data_options == 0){
                                        sql($DBH,"INSERT INTO tbl_data_interval_options (employee_id) VALUES (?);",array($employee_login['id']));
                                        }
                                        
                                        $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_time where employee_id =  ?");
                                        $result 	   	         = $STH->execute(array($employee_login['id']));
                                        $count_data_interval	 = $STH->fetchColumn();
                                        if($count_data_interval == 0){
                                        sql($DBH,"INSERT INTO tbl_data_interval_time (employee_id) VALUES (?);",array($employee_login['id']));
                                        }
                                        
                                        //device information
                                        $rows = sql($DBH, "SELECT * FROM tbl_device_info where employee_id = ?", array($employee_login['id']), "rows");
                                        foreach($rows as $row){
                                        $sync_device_data			= json_decode($row['data'],true);
                                        $sync_device_date_time		= my_simple_date($row['date_time']);
                                        }
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_battery where employee_id = ?", array($employee_login['id']), "rows");
                                        foreach($rows as $row){
                                        $sync_battery_data			= json_decode($row['data'],true);
                                        $sync_battery_date_time		= my_simple_date($row['date_time']);
                                        }
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_storage where employee_id = ?", array($employee_login['id']), "rows");
                                        foreach($rows as $row){
                                            $storage_data = json_decode($row['data']);                                        
                                            $free_disk_space            = round(($storage_data->free/(1024*1024*1024)),2);
                                            $total_disk_space           = round(($storage_data->total/(1024*1024*1024)),2);
                                            $total_used_space            = $total_disk_space-$free_disk_space;                                  
                                            
                                            $storage_percentage         = round(($total_used_space*100)/$total_disk_space,2);
                                            
                                            $sync_storage_date_time		= my_simple_date($row['date_time']);
                                        }
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_network where employee_id = ?", array($employee_login['id']), "rows");
                                        foreach($rows as $row){
                                        $sync_network_data			= $row['data'];
                                        $sync_network_date_time		= my_simple_date($row['date_time']);
                                        }
                                        ?>
                                        
                                        <div class="row">
                                        <div class="col-md-12">
                                        <h2 class="page-title no_margin"> <i class="fa fa-cog"></i> Device Management
                                        <span id="device_update_time">
                                        <i class="fa fa-spinner fa-spin"></i>
                                        </span>
                                        </h2>
                                        </div>
                                        </div>
                                        <div class="row">
                                        <Br /><Br />
                                        <div class="portlet box">
                                        
                                        
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 hidden">
                                        <div class="portlet box yellow">
                                        <div class="portlet-title">
                                        <div class="caption">
                                        <i class="fa fa-hdd-o"></i> Internal Storage
                                        </div>
                                        </div>
                                        <div class="portlet-body">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <div id="chart_div1"></div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        
                                        
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12 hidden">
                                        <div class="portlet box blue">
                                        <div class="portlet-title">
                                        <div class="caption">
                                        <i class="fa fa-hdd-o"></i> SD Card Storage
                                        </div>
                                        </div>
                                        <div class="portlet-body">
                                        <div class="row">
                                        <div class="col-md-12">
                                        <div id="chart_div2"></div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        
                                        
                                        <div class="">
                                        <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-header-fixed" id="sample_2">
                                        <thead>
                                        <tr>
                                        <th colspan="2"><h4><?php echo $device_information_xml; ?></h4></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <form>
                                        <tr>
                                        <td><?php echo $battery_xml; ?></td>
                                        <td>
                                        <?php
                                        $sync_battery_level = $sync_battery_data['level'];
                                        
                                        if($sync_battery_data['level'] < 20){
                                            //echo "<i class='fa fa-battery-0 text-danger'></i>";
                                            $BG_color = "#ed6b75";
                                        }else if($sync_battery_data['level'] >= 20 && $sync_battery_data['level'] < 50){
                                            //echo "<i class='fa fa-battery-1'></i>";
                                            $BG_color = "#F1C40F";
                                        }else if($sync_battery_data['level'] >= 50 && $sync_battery_data['level'] < 75){
                                            //echo "<i class='fa fa-battery-2'></i>";
                                            $BG_color = "#36c6d3";
                                        }else if($sync_battery_data['level'] >= 75 && $sync_battery_data['level'] < 90){
                                            //echo "<i class='fa fa-battery-3'></i>";
                                            $BG_color = "#36c6d3";
                                        }else if($sync_battery_data['level'] >= 90){
                                            //echo "<i class='fa fa-battery-4 text-success'></i>";
                                            $BG_color = "#36c6d3";
                                        }    
                                        
                                        echo '<div class="progress" style="margin: 0;">
                                          <div class="progress-bar" role="progressbar" aria-valuenow="'.$sync_battery_level.'"
                                          aria-valuemin="0" aria-valuemax="100" style="width:'.$sync_battery_level.'%; background:'.$BG_color.';">
                                            <span class="sr-only">'.$sync_battery_level.'% Complete</span>
                                          </div>
                                        </div>';                                        
                                        
                                        if($sync_battery_data['isPlugged'] == true){
                                        echo "<i class='fa fa-plug'></i> $charging_xml";
                                        }else{
                                        echo "<i class='fa fa-unlink'></i> $not_charging_xml";
                                        }
                                        
                                        echo " <kbd>$sync_battery_level %</kbd>";
                                        
                                        echo "<br>";
                                        echo " <small class='text-muted'><i class='fa fa-clock-o'></i> $sync_battery_date_time</small>";
                                        ?>
                                        </td>
                                        </tr>
                                        <tr>
                                        
                                        <td><?php echo $network_con_xml; ?></td>
                                        <td>
                                        <?php
                                        echo $sync_network_data;
                                        echo "<br>";
                                        echo " <small><i class='fa fa-clock-o'></i> $sync_network_date_time</small>";
                                        ?>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $platform_xml ; ?></td>
                                        <td><?php echo $sync_device_data['platform']; ?></td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $version_xml; ?></td>
                                        <td><?php echo $sync_device_data['version']; ?></td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $uuid_xml; ?></td>
                                        <td><?php echo $sync_device_data['uuid']; ?></td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $model_xml; ?></td>
                                        <td><?php echo $sync_device_data['model']; ?></td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $manufacturer_xml; ?></td>
                                        <td><?php echo $sync_device_data['manufacturer']; ?></td>
                                        </tr>
                                        <tr>
                                        <td>Device <?php echo $serial_xml; ?></td>
                                        <td><?php echo $sync_device_data['serial']; ?></td>
                                        </tr>
                                        <tr>
                                        <td>Sim <?php echo $serial_xml; ?></td>
                                        <td><?php echo $sim_serial; ?></td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $free_disk_space_xml; ?></td>
                                        <td>
                                        
                                        <div class="progress" style="margin: 0;">
                                          <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $storage_percentage; ?>"
                                          aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $storage_percentage; ?>%">
                                            <span class="sr-only"><?php echo $storage_percentage; ?>% Complete</span>
                                          </div>
                                        </div>

                                        
                                        <?php
                                        echo "$total_used_space GB / $total_disk_space GB <kbd>$storage_percentage %</kbd>";
                                        echo "<br><small class='text-muted'><i class='fa fa-clock-o'></i> $sync_storage_date_time</small>";
                                        ?>
                                        
                                        
                                        </td>
                                        </tr>
                                        <tr>
                                        <td><?php echo $last_update_xml; ?></td>
                                        <td><?php echo $sync_device_date_time; ?></td>
                                        </tr>
                                        </form>
                                        </tbody>
                                        </table>                                        
                                        
                                        </div>
                                        
                                        
                                        <div class="col-md-12">
                                        <form method="post" action="exe_device_manage.php">
                                        <input type="hidden" value="<?php echo $employee_login['id'] ?>" name="u_id" />
                                        <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                        <th colspan="4">
                                        <h4><?php echo $device_settings_xml; ?></h4>
                                        </td>
                                        </tr>
                                        <tr>
                                        <th>#</th>
                                        <th><?php echo $data_type_xml; ?></th>
                                        <th><?php echo $sync_in_xml;   ?> _ <?php echo $sync_time_xml; ?></th>
                                        <th><?php echo $sync_in_xml;   ?> <?php echo $wifi_net_xml; ?></th>
                                        </tr>
                                        </thead>
                                        <?php
                                        $sno = 0;
                                        $rows = sql($DBH, "SELECT * FROM tbl_data_interval_time where employee_id = ?", array($employee_login['id']), "rows");
                                        $data_interval_time = $rows[0];
                                        
                                        $rows = sql($DBH, "SELECT * FROM tbl_data_interval_options where employee_id = ?", array($employee_login['id']), "rows");
                                        $data_interval_opt = $rows[0];
                                        foreach($data_interval_opt as $data_code => $network_option){
                                        
                                        if($data_code == "call_logs" || $data_code == "text_msgs"){
                                            continue;//skip
                                        }
                                        
                                        $interval_name  = interval_name($array_intervals,$array_intervals_text,$data_code);
                                        if($interval_name != null){
                                        $interval_name = ucwords($interval_name);
                                        
                                        $interval_time = $data_interval_time[$data_code];
                                        
                                        $sno++;
                                        ?>
                                        
                                        
                                        <tr>
                                        <td><?php echo $sno; ?></td>
                                        <td><?php echo $interval_name; ?></td>
                                        <td><input name="interval_time[<?php echo $data_code; ?>]" type="number" class="form-control" value="<?php echo $interval_time; ?>" /></td>
                                        <td>
                                        <select name="network_option[<?php echo $data_code; ?>]" class="form-control">
                                        <option <?php if($network_option == "both") echo "selected"; ?> value="both"><?php echo $wifi_network_xml; ?></option>
                                        <option <?php if($network_option == "wifi") echo "selected"; ?> value="wifi"><?php echo $wi_fi_xml; ?></option>
                                        <option <?php if($network_option == "cellular") echo "selected"; ?> value="cellular"><?php echo $mob_data_xml; ?></option>
                                        </select>
                                        </td>
                                        </tr>
                                        <?php
                                        }
                                        }
                                        ?>
                                        <tfoot>
                                        <tr>
                                        <td colspan="4">
                                        <button class="btn btn-success"><i class="fa fa-check"></i> <?php echo $save_xml; ?></button>
                                        </td>
                                        </tr>
                                        </tfoot>
                                        </table>
                                        </form>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="portlet box purple">
                                            <div class="portlet-title">
                                            <div class="caption">
                                            <i class="fa fa-music"></i> Play Sound
                                            </div>
                                            </div>
                                            <div class="portlet-body">
                                            <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="portlet light bordered">
                                            <div class="portlet-body">
                                            <div class="row">
                                            <div class="col-md-12">
                                            <!--
                                            <span id="remote_update_time">
                                            <i class="fa fa-spinner fa-spin"></i>
                                            </span>
                                            -->
                                            <?php
                                            $rows = sql($DBH, "SELECT * FROM tbl_locations
                                            where employee_id = ? order by (id) limit 1",
                                            array($employee_login['id']), "rows");
                                            $sync_date_time		= "No Location";
                                            foreach($rows as $row){
                                            $sync_date_time		= my_simple_date($row['date_time']);
                                            }
                                            
                                            $rows = sql($DBH, "SELECT * FROM tbl_remove_device_lock where employee_id = ?", array($employee_login['id']), "rows");
                                            foreach($rows as $row){
                                            $send_message	= $row['send_message'];
                                            $message_title	= $row['message_title'];
                                            $message_body	= $row['message_body'];
                                            $play_sound  	= $row['play_sound'];
                                            $remote_lock	= $row['remote_lock'];
                                            $remote_wipe	= $row['remote_wipe'];
                                            }
                                            ?>
                                            <?php
                                            $send_message_html = '<i class="fa fa-envelope"></i> Send Message';
                                            
                                            $play_sound_html = '<i class="fa fa-play"></i> Play Sound';
                                            $stop_sound_html = '<i class="fa fa-stop"></i> Stop Sound';
                                            
                                            
                                            if($play_sound == "true"){//no command
                                                $play_sound_style = 'style="display:none"';
                                            }else if($play_sound == "false"){
                                                $stop_sound_style = 'style="display:none"';
                                            }
                                            
                                            if($remote_lock == "false"){//no command
                                            $remote_lock_html = '<i class="fa fa-power-off"></i> Remote Lock';
                                            }else if($remote_lock == "true"){
                                            $remote_lock_html = '<i class="fa fa-spinner"></i> Locking Device..';
                                            }
                                            
                                            if($remote_wipe == "false"){//no command
                                            $remote_wipe_html = '<i class="fa fa-eraser"></i> Remote Wipe';
                                            }else if($remote_wipe == "true"){
                                            $remote_wipe_html = '<i class="fa fa-spinner"></i> Wiping Phone..';
                                            }
                                            ?>
                                            <!--
                                            <a href='#message_modal' role='button' data-toggle='modal'class="btn btn-block btn-primary"><?php echo $send_message_html; ?></a>
                                            <a id="remote_lock" style="width: 49.5%;" class="btn btn-warning"><?php echo $remote_lock_html; ?></a>
                                            <a id="remote_wipe" style="width: 49.5%;" class="btn btn-danger"><?php echo $remote_wipe_html; ?></a>
                                            -->
                                            
                                            <a <?php echo $play_sound_style; ?> id="play_sound" href="ajax/remote_device_actions.php?action=play_sound&u_id=<?php echo $employee_login['id'] ?>" class="btn purple btn-block"><?php echo $play_sound_html; ?></a>
                                            <a <?php echo $stop_sound_style; ?> id="stop_sound" href="ajax/remote_device_actions.php?action=stop_sound&u_id=<?php echo $employee_login['id'] ?>" class="btn btn-danger btn-block"><?php echo $stop_sound_html; ?></a>
                                            
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                            </div>
                                        
                                        </div>
                                        </div>
                                        </div>
                                        </div>
                                        </div><!-- tab_device -->
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                        
                                            </div><!-- tab content -->
                                        </div><!-- col-9-->
                                        <!-- data end -->           
            
                                    </div> <!-- row -->
                                </div><!-- portlet body -->
                            </div><!-- col-md-12 -->
                        </div><!-- row -->
                    </div><!-- page content -->
                </div><!-- page content wrapper -->
            </div><!-- page container -->
            
            
            
            
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
            
            
            <div id="view_picture" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
            <!--
            <h4 class="modal-title"> 
            <button type="button" class="btn pull-right" data-dismiss="modal" aria-hidden="true">
            <i class="fa fa-times"></i>
            </button>
            </h4>
            -->
            </div>
            <div class="modal-body">
            <img src="" alt="" class="view_picture" />
            </div>
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
                                        <input autocomplete="off" required="" class="form-control date-picker" type="text" name="txt_edit_advance_salary_date">
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
                                      <input autocomplete="off" required="" class="form-control date-picker" type="text" name="txt_advance_salary_date">
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
                                  <input type="text" autocomplete="off"  class="form-control" name="txt_time_off_from_date">
                                  <span class="input-group-addon"> to </span>
                                  <input type="text" autocomplete="off" class="form-control" name="txt_time_off_to_date"> </div>

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
                            <textarea class="form-control" name="txt_time_off_comment"></textarea>
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
            
            <div class="modal fade" id="modal_gps" tabindex="-1" role="basic" aria-hidden="true">
               <div class="modal-dialog">
                   <div class="modal-content">
                       <div class="modal-header">
                           <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                           <h4 class="modal-title">GPS Location</h4>
                       </div>
                       <div class="modal-body">
                          <div id="map_2"></div>
                       </div>
                       <div class="modal-footer">
                           <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        </div>
                   </div>
               </div>
           </div>

            <!-- BEGIN FOOTER -->

<?php
	echo $footer;
?>

            <!-- END FOOTER -->

        </div><!-- page wrapper -->

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
		<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
		<!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
		
        <!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME LAYOUT SCRIPTS -->
		<script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
		<script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        
               
        <style>
        
            .slidecontainer {
                width: 100%;
            }
            
            .slider {
              -webkit-appearance: none;
              width: 100%;
              height: 15px;
              background: #d3d3d3;
              outline: none;
              opacity: 0.7;
              -webkit-transition: .2s;
              transition: opacity .2s;
              border-radius:5px;
              margin: 0 0 10px 0 ;
            }
            
            .slider:hover {
              opacity: 1;
            }
            
            .slider::-webkit-slider-thumb {
              -webkit-appearance: none;
              appearance: none;
              width: 30px;
              height: 30px;
              background: #31c7b2;
              cursor: pointer;
              border-radius: 100%;
            }
            
            .slider::-moz-range-thumb {
              width: 30px;
              height: 30px;
              background: #31c7b2;
              cursor: pointer;
              border-radius: 100%;
            }
        
        </style>
        
        
        
        
        
		<!-- END THEME LAYOUT SCRIPTS -->
        <script>
            $(document).ready(function(){
               $(".btn_user_mode").click(function(e){
                    e.preventDefault();                    
                    var x = confirm("Are you sure you want to enable advance mode ?");
				    if(x){
	                   $.get($(this).attr("href"),function(data){
                            window.location.reload();            
    				   });
                    }
				}); 
                
                /*
                setTimeout(function(){

					<?php

						if(strlen($date_from) > 0 && strlen($date_to) > 0){

							$show_date_from 	= date("M d, Y",strtotime($date_from));

							$show_date_to 		= date("M d, Y",strtotime($date_to));

						}

					?>


                    var date_from 	= "<?php echo $show_date_from; ?>";

					var date_to		= "<?php echo $show_date_to; ?>";



					if(date_from == "" && date_to == ""){

						$(".reportrange span").text("<?php echo $showing_all_records_xml; ?>");

					}else if(date_from == "" && date_to == ""){

						$(".reportrange span").text(date_from+" - "+date_to);

					}

					$(".reportrange span").show();

                },50);
                */
                
                $(".filter_form1").submit(function(e){
                    e.preventDefault();
                
					var date_from 	= $("input[name='date_from1']").val();
					var date_to		= $("input[name='date_to1']").val();
                    var tab 	    = $("input[name='page1']").val();
					var id		    = $("input[name='id1']").val();

					window.location.replace("employee-profile.php?id="+id+"&date_from="+date_from+"&date_to="+date_to+"#"+tab);

				});
                
                $(".filter_form2").submit(function(e){
                    e.preventDefault();
                
					var date_from 	= $("input[name='date_from2']").val();
					var date_to		= $("input[name='date_to2']").val();
                    var tab 	    = $("input[name='page2']").val();
					var id		    = $("input[name='id2']").val();

					window.location.replace("employee-profile.php?id="+id+"&date_from="+date_from+"&date_to="+date_to+"#"+tab);

				});
                
                
                $(".filter_form3").submit(function(e){
                    e.preventDefault();
                
					var date_from 	= $("input[name='date_from3']").val();
					var date_to		= $("input[name='date_to3']").val();
                    var tab 	    = $("input[name='page3']").val();
					var id		    = $("input[name='id3']").val();

					window.location.replace("employee-profile.php?id="+id+"&date_from="+date_from+"&date_to="+date_to+"#"+tab);

				});
                
                $(".filter_form4").submit(function(e){
                    e.preventDefault();
                
					var date_from 	= $("input[name='date_from4']").val();
					var date_to		= $("input[name='date_to4']").val();
                    var tab 	    = $("input[name='page4']").val();
					var id		    = $("input[name='id4']").val();

					window.location.replace("employee-profile.php?id="+id+"&date_from="+date_from+"&date_to="+date_to+"#"+tab);

				});
                
                $(".filter_form5").submit(function(e){
                    e.preventDefault();
                
					var date_from 	= $("input[name='date_from5']").val();
					var date_to		= $("input[name='date_to5']").val();
                    var tab 	    = $("input[name='page5']").val();
					var id		    = $("input[name='id5']").val();

					window.location.replace("employee-profile.php?id="+id+"&date_from="+date_from+"&date_to="+date_to+"#"+tab);

				});
                
                var map;
                var markers = [];
                var infowindow;
                
                function initMap_CALL_LOG() {
            		map = new google.maps.Map(document.getElementById('map'), {
            			center: {lat: -34.397, lng: 150.644},
            			zoom: 8
                    });
            
                    infowindow = new google.maps.InfoWindow({
                          content: "Call Location"
                    });
            	}
                
                function setMapOnAll(map) {
                  for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                  }
                }
                
                $(".show_map").click(function(){
                    
    
                    var lat = +$(this).attr("lat");
                    var lon = +$(this).attr("lon");
                    var accuracy = 26;    
                    $("#map_2").css('height',($(window).height()-200)+'px');               
                      
                    setTimeout(function(){
                        initMap_CALL_LOG();
                        setMapOnAll(null);             
                        						
                        var accuracy_circle = new google.maps.Circle({ //first time
                			map: map,
                			radius: accuracy,
                			fillColor: '#ADD8E6',
                			strokeColor: '#1191d4',
                			strokeOpacity: 0.9,
                			strokeWeight: 1,
                			center: new google.maps.LatLng(lat,lon),
                			clickable: false
                		});
                		accuracy_circle.setMap(map);
                        
                        var zoomLevel 	= parseInt(Math.log2(591657550.5 / (accuracy * 45))) + 1;
                
                		map.setZoom(zoomLevel);
                
                      //navigate
                			initialLocation = new google.maps.LatLng(lat, lon);
                			map.setCenter(initialLocation);
                
                      //add marker
                      var marker = new google.maps.Marker({
                        position: {lat: lat, lng: lon},
                        map: map,
                        title: 'Call Location (26m)'
                      });
                      markers.push(marker);
                
                      marker.addListener('mouseover', function() {
                        infowindow.open(map, marker);
                      });
                
                      marker.addListener('mouseout', function() {
                      infowindow.close(map);
                      });
                
                
                      /*
            			if (navigator.geolocation) {
            			navigator.geolocation.getCurrentPosition(function (position) {
            			initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            			map.setCenter(initialLocation);
            			});
            			}
                      */
                   },250);
                });
                
                
            });
        </script>
        <script>
            /*var map;
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
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(locations[i]["latitude"], locations[i]["longitude"]),
                        map: map,
                        icon: "https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(i+1)+"|ea4335|ffffff"
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
            }*/
            
            
                
                var map;
            function initMap() {
                var directionsDisplay = new google.maps.DirectionsRenderer;
                var directionsService = new google.maps.DirectionsService;
                map = new google.maps.Map(document.getElementById('map'), {
                  zoom: 17,
                  center: {lat: 24.91601, lng: 67.12536}
                });
                directionsDisplay.setMap(map);
        
                //calculateAndDisplayRoute(null,directionsService, directionsDisplay,null);
                
                var collection = [];
                
                google.maps.event.addListener(map, "click", function (e) {
    
                    var lat = e.latLng.lat();
                    var lng = e.latLng.lng();
                    
                    
                    console.log({lat,lng});
                    
                    collection.push([lat, lng]);
                    
                    console.log(JSON.stringify(collection));
                
                });
                
                var lat_lng = <?php echo json_encode($lat_lng); ?>;
                //alert("->"+lat_lng);
                //var lat_lng = [[24.91487789933761,67.12516184297147],[24.91482924756192,67.1251135632092],[24.914774514291313,67.12503980246129]];
                //alert("-->"+lat_lng);
                
                
                $("#myRange").attr("max",lat_lng.length-1);
                
                var take_me_to_center = false;
                
                $(".location_play").click(function(){
                    var end = lat_lng.length-1;
                    var i = 0;  
                    
                    $(".location_play").children("i").removeClass("fa-play").addClass("fa-pause");
                    $(".location_play").removeClass("purple").addClass("btn-default");
                    $(".location_play").hide();
                                      
                    var play_location = setInterval(function(){
                        i++;
                        
                        console.log(i);
                        
                        $("#myRange").val(i);
                        travell(i);
                        
                        if(i >= end){
                            clearInterval(play_location);
                            $(".location_play").children("i").removeClass("fa-pause").addClass("fa-play");
                            $(".location_play").removeClass("btn-default").addClass("purple");
                            $(".location_play").hide().fadeIn();
                        }
                        
                    },100); 
                    
                    take_me_to_center = true;                   
                });
                
                
                
                
                function travell(loop_i){                  
                   
                   var way_points = [];
                   var finish     = [];
                   
                   for(i=0; i< loop_i; i++){
                        var location = new google.maps.LatLng(lat_lng[i][0],lat_lng[i][1]);
                        
                        if(take_me_to_center == true){
                            map.setCenter(location);
                            take_me_to_center = false;
                        }
                        
                            
                            
                        //way_points.push({location: location, stopover: false});
                        
                        way_points.push({lat: lat_lng[i][0], lng: lat_lng[i][1]});
                        finish['lat'] = lat_lng[i][0];
                        finish['lng'] = lat_lng[i][1];   
                        var test = lat_lng[i];
                        console.log({test});
                   }                   
            
                   calculateAndDisplayRoute(way_points,directionsService, directionsDisplay,finish);
                }
                
                $("#myRange").change(function(){                   
                   var loop_i = +$(this).val();
                   
                   
                   travell(loop_i);
                });
                
              }
              
              
            var path;
          function calculateAndDisplayRoute(way_points,directionsService, directionsDisplay,finish) {
            var selectedMode = "DRIVING";
            
            if(way_points == null){
                directionsService.route({
                  origin:       {lat: 24.915712418171893, lng: 67.12559593138701}, 
                  destination:  {lat: 24.9158972474017, lng: 67.12520306134229},                  
                  optimizeWaypoints: true,
                  travelMode: google.maps.TravelMode[selectedMode]
                }, function(response, status) {
                  if (status == 'OK') {
                    directionsDisplay.setDirections(response);
                  } else {
                    window.alert('Directions request failed due to ' + status);
                  }
                });
            }else{
                
                //map.setCenter({lat: finish.lat, lng: finish.lng});



                /*directionsService.route({
                  origin:       {lat: 24.915712418171893, lng: 67.12559593138701},
                  destination:  {lat: finish.lat, lng: finish.lng},
                  waypoints: way_points,
                  optimizeWaypoints: true,
                  travelMode: google.maps.TravelMode[selectedMode]
                }, function(response, status) {
                  if (status == 'OK') {
                    directionsDisplay.setDirections(response);
                  } else {
                    window.alert('Directions request failed due to ' + status);
                  }
                });*/
                        
                        
                    try{path.setMap(null)}catch(e){}
                    
                    
                            
                    path = new google.maps.Polyline({
                      path: way_points,
                      geodesic: true,
                      strokeColor: '#31c7b2',
                      strokeOpacity: 0.6,
                      strokeWeight: 4
                    });
                    
                    console.log("yes");
                    
                    
                    path.setMap(map);
            }
            
            
            
            
            
                
            
          }
          
          
          
            
        </script>          
        <script src="employee-profile.js" type="text/javascript"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key; ?>&callback=initMap" async defer></script>
        
        <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>

    </body>

</html>