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
    
    
    
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_attendance'] != "true"){
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
			#reportrange span{
				display:none;
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
                        
                        <div class="row">
                            
							<div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class=""> <!-- portlet box green -->
									
									<div class=""> <!-- portlet-body form -->
										<!-- BEGIN FORM-->
										<form id="filter" action="" class="form-horizontal" method="GET">
											<div class="form-body">
												<input type="hidden" name="date_from" value="<?php echo $_GET['date_from']; ?>" />
												<input type="hidden" name="date_to" value="<?php echo $_GET['date_to']; ?>" />												
												
												<div class="form-group">
													<div class="col-md-3">
														<div id="reportrange" class="btn default"> 
															<i class="fa fa-calendar"></i> &nbsp;
															<span> </span>
															<b class="fa fa-angle-down"></b>
														</div>
													</div>
													
													<div class="col-md-6">
														<button type="submit" class="btn green"><i class="fa fa-filter"></i><?php echo $filter_xml; ?></button>
													</div>
												</div>
											</div>
										</form>
										<!-- END FORM-->
									</div>
								</div>
							</div>
							
							<?php
								
								
								$date_from  = $_GET['date_from'];
								$date_to  	= $_GET['date_to'];
								
								if(strlen($date_from) > 0 && strlen($date_to) > 0){
									$from_ts    = strtotime($date_from);
									$to_ts      = strtotime($date_to);                                                   
									$to_ts      = $to_ts+86400;
                                    
									
									$rows = sql($DBH, "SELECT * FROM tbl_attendance where date_time >= ? AND date_time <= ? AND employee_id = ?  order by(id) desc", 
									array($from_ts,$to_ts,$SESS_DEVICE_ID), "rows");	
									if((strlen($date_from) > 0 && strlen($date_to) > 0)){
										$filter_text = " From ".$date_from." to ".$date_to;									
									}
									$filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_filter_xml</a>";									
									
									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_attendance where date_time >= ? AND date_time <= ? AND employee_id = ?");
									$result  		= $STH->execute(array($from_ts,$to_ts,$SESS_DEVICE_ID));
									$count_total	= $STH->fetchColumn();
									
								}else{
									$date_from = date("m/d/Y");
									$date_to   = date("m/d/Y");
									
									$rows = sql($DBH, "SELECT * FROM tbl_attendance where employee_id = ? order by(id) desc", array($SESS_DEVICE_ID), "rows");										
									$filter_text    = " $show_records_xml";									
									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_attendance where employee_id = ?;");
									$result  		= $STH->execute(array($SESS_DEVICE_ID));
									$count_total	= $STH->fetchColumn();
								}  
								
								
							?>
							
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet box red">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-thumbs-up"></i> 
                                            
                                            <div data-toggle="tooltip" data-placement ="left" title="Click to select employee">
                                            <select id="single"  class="form-control select2">
                                                                                        
                                                <optgroup label="Employees">
                                                <?php
                                                $rows = sql($DBH, "SELECT * FROM tbl_login where access_level = ? and parent_id = ? AND status = ?", 
                    							array("user",$SESS_ID,"active"), "rows");
                    							echo "<option value='$SESS_ID'>$SESS_FULLNAME</option>";
                                                foreach($rows as $row){	
                    								$id					= $row['id'];
                    								$fullname			= $row['fullname'];
                                                    
                                                    if($id == $SESS_DEVICE_ID){
                                                        echo "<option selected value='$id'>$fullname</option>";
                                                    }else{
                                                        echo "<option value='$id'>$fullname</option>";
                                                    }
                    							}  
                                                
                                                ?>
                                                </optgroup>
                                            </select>
                                            </div>
                                            
                                            <?php echo $title_xml; ?>
                                        
                                        
                                        </div>
									</div>
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
											<thead>
												<tr>
													<th>#</th>
													<th>Name</th>
													<th><?php echo $location_xml; ?></th>
                                                    <th><?php echo $date_time_xml; ?></th>
													<th><?php echo $date_time_xml; ?></th>
												</tr>
											</thead>
											<tfoot class="hidden">
												<tr>
													<th>#</th>
													<th>Action</th>
													<th>Location</th>
                                                    
													<th>Date Time</th>
												</tr>
											</tfoot>
											<tbody>
												<?php
												
															
													$sno = 0;												
													foreach($rows as $row){	
														$sno++;
														$id					= $row['id'];
														$admin_id			= $row['admin_id'];
														$employee_id    	= $row['employee_id'];
														$location_id		= $row['location_id'];
														$type				= $row['type'];
														$date_time			= my_simple_date($row['date_time']);
														
														//employee name
														$rows 		= sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($employee_id), "rows");	
														foreach($rows as $row){	
															$employee_name	= $row['fullname'];
														}
														
														$rows 		= sql($DBH, "SELECT * FROM tbl_geo_fence where id = ? ", array($location_id), "rows");	
														foreach($rows as $row){	
															$location_name	= $row['name'];

														}
														
														
														if($status == "active"){
															$status_badge_class = "success";
															$status_badge_text	= "$active_xml";
															
															$action_btn_class	= "danger";
															$action_btn_text	= "$block_unblock_xml";
														}else{
															$status_badge_class = "danger";
															$status_badge_text	= "$inactive_xml";
															
															$action_btn_class	= "success";
															$action_btn_text	= "$block_unblock_xml";
														}
														
														$status_badge 	= "<span badge_id='$id' class='badge badge-".$status_badge_class."'>".$status_badge_text."</span>";
														$action_btn 	= "<a href='ajax/active_inactive.php?id=$id&type=user' class='btn btn-".$action_btn_class." btn-xs btn-circle active_inactive'>
														<i class='fa fa-lock'></i> ".$action_btn_text."
														</a>";
														
														//urls
														$profile_url		= "profile.php?id=$id";
														
														echo "<tr>
															<td>$sno</td>
															<td>$employee_name</td>
															<td>".ucfirst(str_replace("_"," ",$type))."</td>
															<td>$location_name</td>
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
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
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
						$("#reportrange span").text("<?php echo $showing_last_hours_xml ?>");
					}else if(date_from == "" && date_to == ""){
						$("#reportrange span").text(date_from+" - "+date_to);
					}
					$("#reportrange span").show();
                },50);
				
				$("#filter").submit(function(){
					var date_from 	= $("input[name='daterangepicker_start']").val();
					var date_to		= $("input[name='daterangepicker_end']").val();
					$("input[name='date_from']").val(date_from);
					$("input[name='date_to']").val(date_to);
					if(date_from == "" && date_to == ""){
						$("#reportrange span").text("<?php echo $showing_last_hours_xml; ?>");
					}
				});                
            });
            
            
        </script>    
        <script>
            $(document).ready(function(){
               $("#single").change(function(){
                    var id = $(this).val();
                    window.location.href = "select_device.php?id="+id;
               }); 
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>   
    </body>

</html>