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
		if($perm['perm_expense_refund'] != "true"){
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
			#reportrange span{
				display:none;
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
													<div class="col-md-4">
														<div id="reportrange" class="btn default"> 
															<i class="fa fa-calendar"></i> &nbsp;
															<span> </span>
															<b class="fa fa-angle-down"></b>
														</div>
													</div>
                                                    
                                                    <div class="col-md-3">
                                                        <select class="form-control" name="emp_id">
                                                            <option value="all">All Employees</option>
                                                            <?php                                                            
                                                                $rows3 = sql($DBH, "SELECT * FROM tbl_login where parent_id = ? AND status = ?", array($SESS_ID,"active"), "rows");
                                    							foreach($rows3 as $row3){	
                                    								$id					= $row3['id'];
                                    								$fullname			= $row3['fullname'];
                                    								if($id == $_GET['emp_id']){
                                    								    echo "<option selected value='$id'>$fullname</option>";
                                    								}else{
                                    								    echo "<option value='$id'>$fullname</option>";
                                    								}
                                    							}                                                             
                                                            ?>                                                        
                                                        </select>                                                    
                                                    </div>
                                                    <div class="col-md-3">
                                                        <select class="form-control" name="status">
                                                            <option value="">Filter Status</option>
                                                            <option <?php if($_GET['status'] == "pending") echo "selected"; ?> value="pending">Pending</option>
                                                            <option <?php if($_GET['status'] == "approved") echo "selected"; ?> value="approved">Approved</option>
                                                            <option <?php if($_GET['status'] == "rejected") echo "selected"; ?> value="rejected">Rejected</option>
                                                            <option <?php if($_GET['status'] == "paid") echo "selected"; ?> value="paid">Paid</option>                  
                                                        </select>                                                 
                                                    </div>                                                       
                                                    
													<div class="col-md-2">
														<button type="submit" class="btn green"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
													</div>
                                                    
                                                    <div class="clearfix"></div>
                                                    
                                                    <div class="col-md-12">
                                                        <br />
                                                    </div>
                                                    
                                                    <div class="col-md-3">                                                        
                                                        <button type="button" class="btn btn-default btn-sm select_all"><i class='fa fa-check-square-o'></i> Select All</button>
                                                        <button type="button" class="btn btn-default btn-sm unselect_all"><i class='fa fa-square-o'></i> Deselect All</button>
                                                    </div>
                                                    
                                                    <div class="col-md-3">    
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
										<!-- END FORM-->
									</div>
								</div>
							</div>
							
							<?php
								
								
								$date_from  = $_GET['date_from'];
								$date_to  	= $_GET['date_to'];
								$emp_id     = $_GET['emp_id'];
                                $status     = "%".$_GET['status']."%";
                                
								if(strlen($date_from) > 0 && strlen($date_to) > 0){
									$from_ts    = strtotime($date_from);
									$to_ts      = strtotime($date_to);                                                   
									$to_ts      = $to_ts+86400;
                                    
                                    if($emp_id == "all" || $emp_id == ""){
                                        $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where date_time >= ? AND date_time <= ? AND parent_id = ? AND status like ?  order by(id) desc", 
									    array($from_ts,$to_ts,$SESS_DEVICE_ID,$status), "rows");
                                        
                                        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_expense_refund where date_time >= ? AND date_time <= ? AND parent_id = ? AND status like ?");
    									$result  		= $STH->execute(array($from_ts,$to_ts,$SESS_DEVICE_ID,$status));
    									$count_total	= $STH->fetchColumn();
                                        
                                    }else if(strlen($emp_id) > 0){
                                        $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where date_time >= ? AND date_time <= ? AND parent_id = ? AND device_id = ? AND status like ? order by(id) desc", 
									    array($from_ts,$to_ts,$SESS_DEVICE_ID,$emp_id,$status), "rows");
                                        
                                        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_expense_refund where date_time >= ? AND date_time <= ? AND parent_id = ? AND device_id = ? AND status like ?");
    									$result  		= $STH->execute(array($from_ts,$to_ts,$SESS_DEVICE_ID,$emp_id,$status));
    									$count_total	= $STH->fetchColumn();
                                    }
                                    
                                    if((strlen($date_from) > 0 && strlen($date_to) > 0)){
										$filter_text = " - from ".$date_from." to ".$date_to;									
									}
									$filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_filter_xml</a>";									
                                }else{
									$date_from = date("m/d/Y");
									$date_to   = date("m/d/Y");
									
                                    if($emp_id == "all" || $emp_id == ""){
                                        $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where parent_id = ? AND status like ? order by(id) desc", 
                                        array($SESS_ID,$status), "rows");										
    									$filter_text    = "";									
    									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_expense_refund where parent_id = ? AND status like ?");
    									$result  		= $STH->execute(array($SESS_ID,$status));
    									$count_total	= $STH->fetchColumn();
                                    }else if(strlen($emp_id) > 0){
                                        $rows = sql($DBH, "SELECT * FROM tbl_expense_refund where parent_id = ? and device_id = ? AND status like ? order by(id) desc", 
                                        array($SESS_ID,$emp_id,$status), "rows");										
    									$filter_text    = "";									
    									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_expense_refund where parent_id = ? and device_id = ? AND status like ?");
    									$result  		= $STH->execute(array($SESS_ID,$emp_id,$status));
    									$count_total	= $STH->fetchColumn();
                                    }
                                    
									
								}  
                                
                                $title = "Expense ($count_total)<small>$filter_text</small>";
                                /*
                                <a target="_blank" style="color:#FFF;" href="profile.php?id=<?php echo $SESS_DEVICE_ID; ?>"><?php echo $SESS_DEVICE_NAME; ?></a>
								*/
								
							?>
							
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet box red">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-money"></i> 
                                            
                                            
                                            <div data-toggle="tooltip" data-placement ="left" title="Click to select employee">
                                            <select id="single"  class="form-control select2">
                                                                                        
                                                <optgroup label="Employees">
                                                <?php
                                                $rows_3 = sql($DBH, "SELECT * FROM tbl_login where access_level = ? and parent_id = ? AND status = ?", 
                    							array("user",$SESS_ID,"active"), "rows");
                    							echo "<option value='$SESS_ID'>$SESS_FULLNAME</option>";
                                                foreach($rows_3 as $row){	
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
                                            
                                            
                                            
                                            's <?php echo $title; ?></div>
									</div>
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
											<thead>
												<tr>
													<th>#</th>
                                                    <th>Employee</th>
                                                    <th>Thumbnail</th>
													<th>Expense</th>
                                                    <th>Amount</th>
													<th>Date Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
												</tr>
											</thead>
											<tfoot class="hidden">
												<tr>
													<th>#</th>
                                                    <th>Employee</th>
                                                    <th>Thumbnail</th>
													<th>Expense</th>
                                                    <th>Amount</th>
													<th>Date Time</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
												</tr>
											</tfoot>
											<tbody>
												<?php
															
													$sno = 0;												
													foreach($rows as $row){	
														$sno++;
														$id					= $row['id'];                                                        												
														$device_id    	    = $row['device_id'];
                                                        $title              = $row['title'];	
                                                        $amount             = $row['amount'];	
                                                        $attachment			= $row['attachment'];
														$date_time			= my_simple_date($row['date_time']);
                                                        $status 			= $row['status'];
                                                        $reason				= $row['reason'];
														$status_date_time   = my_simple_date($row['status_date_time']);
														
														//employee name
														$rows2 		= sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($device_id), "rows");	
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

                                                        
														//urls
														//$profile_url		= "profile.php?id=$id";
														
														?>
                                                        
                                                        <tr>
															<td>                                                            
                                                                <div class="md-checkbox">
                                                                    <input name="check_<?php echo $sno; ?>" value="<?php echo $id; ?>" type="checkbox" id="check_<?php echo $sno; ?>" class="md-check bulk_check" />
                                                                    <label for="check_<?php echo $sno; ?>">
                                                                        <span></span>
                                                                        <span class="check"></span>
                                                                        <span class="box"></span> <?php echo $sno; ?>. </label>
                                                                </div>                                                            
                                                            </td>
															<td><?php echo $employee_name; ?></td>
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
		<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
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
                
                //$('[data-toggle="popover"]').popover();  
                //$('[data-toggle="popover"]').popover({ trigger: "hover" });   
                
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
						    if(new_status == "reject"){
								var x = prompt("Please specify a reason for rejection of "+total_checked+" selected expense(s)!");
							}else{
								var x = confirm("Are you sure you want to change the status of "+total_checked+" selected expense(s) to '"+new_status+"' ?");
							}					
                            
                            if(x){                                
                                $(".bulk_check:checked").each(function(){
                                    var expense_id  = $(this).val();
                                    var old_html    = $(".action_btn[expense_id='"+expense_id+"'][action='"+new_status+"']").html(); 
                                    change_status(expense_id,new_status,old_html,x);
                                }); 
                                $("select[name='bulk_status_change']").val("");
                            }else{
                                $("select[name='bulk_status_change']").val("");
                            }
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
							var x = confirm("Are you sure you want to change the status of this expense to '"+action+"' ?");
						}						
                        if(x){
                            change_status(expense_id,action,old_html,x);
                        }                            
                    }else{
                        alert("Whoops, please refresh your browser and try again!");
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