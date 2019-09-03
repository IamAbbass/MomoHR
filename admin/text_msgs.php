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






	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_contacts'] != "true"){
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
                                <div class="portlet box red">
									<?php
										$sync_data_contacts = null;
                                        $rows = sql($DBH, "SELECT * FROM tbl_contacts where employee_id = ?", array($SESS_DEVICE_ID), "rows");
										foreach($rows as $row){
											$sync_data_contacts			= json_decode($row['data'],true);
										}


                                        $rows = sql($DBH, "SELECT * FROM tbl_text_msgs where employee_id = ?", array($SESS_DEVICE_ID), "rows");
										$sync_date_time = " <small>$never_synced_xml</small>";
										foreach($rows as $row){
											$sync_data			= json_decode($row['data'],true);
											$sync_date_time		= my_simple_date($row['date_time']);
										}
									?>

									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-envelope"></i>

                                            <div data-toggle="tooltip" data-placement ="left" title="Click to select employee">
                                            <select id="single"  class="form-control select2">

                                              <optgroup label="Select">
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

                                                      if($id == $SESS_DEVICE_ID){
                                                          echo "<option selected value='$id'>$fullname $access_level</option>";//selected
                                                      }else{
                                                          echo "<option value='$id'>$fullname $access_level</option>";
                                                      }
                                                  }

                                                  ?>
                                              </optgroup>
                                            </select>
                                            </div>

                                            <?php echo $text_messages_xml; ?> ( <?php echo $last_synced_xml; ?> <?php echo $sync_date_time; ?> )


                                        </div>
									</div>
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
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
											<tfoot class="hidden">
												<tr>
													<th>#</th>
													<th>Name/Number</th>
													<th>Date</th>
													<th>Status</th>
													<th>Body</th>
													<th>Application</th>
												</tr>
											</tfoot>
											<tbody>
												<?php

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
               $("#single").change(function(){
                    var id = $(this).val();
                    window.location.href = "select_device.php?id="+id;
               });
                $('[data-toggle="tooltip"]').tooltip();
            });
        </script>
    </body>

</html>
