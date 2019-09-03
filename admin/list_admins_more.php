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
    
    $company_id = $_GET['id'];


    $showing_all_records    = $xml->administrators->showing_all_records;
    $search_xml             = $xml->administrators->search;
    $filter                 = $xml->administrators->filter;
    $administrators         = $xml->administrators->administrators;
    $entries                = $xml->administrators->entries;
    $search_colon           = $xml->administrators->search_colon;
    $name_xml               = $xml->administrators->name;
    $email_xml              = $xml->administrators->email;
    $contact_xml            = $xml->administrators->contact;
    $registration_xml       = $xml->administrators->registration_date;
    $sub_users_xml          = $xml->administrators->sub_users;
    $users_xml              = $xml->administrators->users;
    $status_xml             = $xml->administrators->status;
    $permission_xml         = $xml->administrators->perm_head;
    $action_xml             = $xml->administrators->action;
    $active_xml             = $xml->administrators->active;
    $inactive_xml           = $xml->administrators->inactive;
    $all_permissions        = $xml->administrators->all_permissions;
    $no_permissions         = $xml->administrators->no_permissions;
    $permission_s           = $xml->administrators->permission_s;
    $restrictions           = $xml->administrators->restrictions;
    $edit__xml       = $xml->administrators->edit_profile;
    $view_geo_fence         = $xml->administrators->view_geo_fence;
    $create_user            = $xml->administrators->create_user;
    $block_unblock          = $xml->administrators->block_unblock;
    $no_date                = $xml->administrators->no_date;


	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
        redirect('index.php');
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
        redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
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

							

							<?php
								$rows = sql($DBH, "SELECT * FROM tbl_login where company_id = ? ", 
                                array($company_id), "rows");

								

								$filter_text = "$showing_all_records";
                                
                                $count_total = count($rows);
							?>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet box red">
									<div class="portlet-title">
										<div style="float: left; width: 50% !important;" class="caption">
											<i class="icon-user-following"></i> <?php echo $administrators ; ?> <?php /*if($count_total > 0)*/ echo "($count_total)"; ?> <small> - <?php echo $filter_text; ?></small></div>
										<div style="float: right;" class="actions">
											<a href="add_admin.php" class="btn btn-default btn-sm btn-circle">
												<i class="fa fa-plus"></i> Add Administrator
											</a>
										</div>
									</div>
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
											<thead>
												<tr>
													<th>#</th>
													<th><?php echo $name_xml; ?></th>
													<th><?php echo $email_xml; ?></th>
													<th><?php echo $contact_xml; ?></th>
													<th><?php echo $registration_xml; ?></th>
													<!--<th><?php echo $sub_users_xml; ?></th>-->
                                                    <th>Alibaba Cloud</th>
													<th><?php echo $status_xml; ?></th>
													<th><?php echo $permission_xml ?></th>
													<th style="width:100px;"><?php echo $action_xml; ?></th>
												</tr>
											</thead>
											<tbody>
												<?php


													$sno = 0;
													foreach($rows as $row){
														$sno++;
                                                        
                                                        $id					= $row['id'];
														$fullname			= $row['fullname'];
														$email    	    	= $row['email'];
														$contact			= $row['contact'];
														$address			= $row['address'];
														$city				= $row['city'];
														$country			= $row['country'];
														$photo				= video_to_photo($row['photo']);
                                                        $company_id			= $row['company_id'];
														
                                                        $registration_date	= my_simple_date($row['date_time']);

                                                        $update_at          = $row['update_at'];
                                                        if(strlen($update_at) > 0 && $update_at > 0){
                                                            $update_at = "<Br>Updated: ".my_simple_date($update_at);
                                                        }

														$access_level		= ucfirst($row['access_level']);
														$status				= $row['status'];

														if($status == "active"){
															$status_badge_class = "success";
															$status_badge_text	= "$active_xml";

															$action_btn_class	= "danger";
															$action_btn_text	= "$block_unblock";
														}else{
															$status_badge_class = "danger";
															$status_badge_text	= "$inactive_xml";

															$action_btn_class	= "success";
															$action_btn_text	= "$block_unblock";
														}

														$status_badge 	= "<span badge_id='$id' class='badge badge-".$status_badge_class."'>".$status_badge_text."</span>";
														$action_btn 	= "<a href='ajax/active_inactive.php?id=$id&type=user' class='btn btn-".$action_btn_class." btn-xs btn-circle active_inactive'>
														<i class='fa fa-lock'></i> ".$action_btn_text."
														</a>";

														//urls
														$profile_url		= "employee-profile.php?id=$id";//for admin
														$geo_fence_url		= "geo_fencing.php?id=$company_id";
														$add_user_url		= "add_user.php?pid=$company_id";

														$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where company_id = ? and access_level = ?;");
														$result  		= $STH->execute(array($company_id,"user"));
														$count_users_in = $STH->fetchColumn();



														//perm
														$permissions    = "";
														$p_allow        = 0;
														$p_block        = 0;
														$p_total        = 0;

														$rows2 = sql($DBH, "SELECT * FROM tbl_manage_access where id = ?", array($id), "rows");
														$vendor_permissions = $rows2[0];
														foreach($vendor_permissions as $perm_code => $value){
															$perm_name  = permission_name($array_perm,$array_perm_text,$perm_code);
															if($perm_name != null){
																$p_total++;
																if($value == "true"){$p_allow++;}else{$p_block++;}
															}
														}

														if($p_allow == $p_total){
															$permissions = "<a href='admin_access.php?id=$id' class='badge badge-success'><i class='fa fa-check'></i> $all_permissions</a>";
														}else if($p_block == $p_total){
															$permissions = "<a href='admin_access.php?id=$id' class='badge badge-danger'><i class='fa fa-exclamation'></i> $no_permissions</a>";
														}else if($p_allow <= $p_block){
															$permissions = "<a href='admin_access.php?id=$id' class='badge badge-primary'><i class='fa fa-check'></i> <strong>$p_allow</strong> $permission_s</a>";
														}else if($p_block < $p_allow){
															$permissions = "<a href='admin_access.php?id=$id' class='badge badge-warning'><i class='fa fa-ban'></i> <strong>$p_block</strong> $restrictions</a>";
														}

                                                        if(strlen($email) == 0){
                                                            $email = "<span class='badge badge-default'>No Email</span>";
                                                        }

                                                        $rows2 = sql($DBH, "SELECT sum(size_upload) FROM tbl_alibaba where admin_id = ?", array($id), "rows");
                                                        foreach($rows2 as $row2){
                                                            $cloud_storage   = $row2[0];
                                                        }

                                                        $rows2 = sql($DBH, "SELECT limit_bytes FROM tbl_alibaba_limits where admin_id = ?", array($id), "rows");
                                                        foreach($rows2 as $row2){
                                                            $limit_bytes  = readableFileSize($row2[0]);
                                                        }

                                                        if($cloud_storage > 0){
                                                            $cloud_storage = readableFileSize($cloud_storage)." / ".$limit_bytes."<br /><a href='alibaba_cloud.php?id=$id' class='btn btn-info btn-xs btn-circle'>View Details</a>";
                                                        }else{
                                                            $cloud_storage = "<a href='javascript:;' class='badge badge-default'>not used yet</a>";
                                                        }
                                                        
                                                        
    													echo "<tr>
															<td>$sno</td>
															<td><a href='$profile_url'><img src='$photo' alt='' class='user-dp' /> $fullname</a></td>
															<td>$email</td>
															<td>$contact</td>
															<td>$registration_date $update_at</td>
															<!--<td><a href='company.php?pid=$company_id'>$count_users_in </a></td>-->
                                                            <td>$cloud_storage</td>
                                                            <td>$status_badge</td>
															<td>$permissions</td>
															<td>

																<a href='$profile_url' class='btn btn-warning btn-xs btn-circle'>
																	<i class='fa fa-pencil'></i> Edit
																</a>

																<a href='$geo_fence_url' class='btn purple btn-xs btn-circle'>
																	<i class='fa fa-map'></i> $view_geo_fence
																</a>

																<a href='$add_user_url' class='btn btn-info btn-xs btn-circle'>
																	<i class='icon-user-follow'></i> $create_user
																</a>

																$action_btn

                                                                <a href='admin_access.php?id=$id' class='btn btn-success btn-xs btn-circle'>
																	<i class='fa fa-lock'></i> Manage Access
																</a>

                                                                <a href='alibaba_limit.php?id=$id' class='btn btn-primary btn-xs btn-circle'>
																	<i class='fa fa-cloud'></i> Update Limit
																</a>

															</td>
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
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN THEME GLOBAL SCRIPTS -->
		<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
		<!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
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
						$("#reportrange span").text("Show all records");
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
						$("#reportrange span").text("Show all records");
					}
				});

				$(".active_inactive").click(function(e){
					e.preventDefault();

					var this_ = $(this);
					$(this_).html("<i class='fa fa-refresh fa-spin'></i> Block/Unblock");



					$.get($(this).attr("href"),function(data){
						data = $.parseJSON(data);
						if(data.error == ""){
							alert(data.error);
						}else{
							$("span[badge_id='"+data.id+"']").html(data.new_html);
							$("span[badge_id='"+data.id+"']").removeClass("badge-success badge-danger").addClass(data.new_class);
						}
						$(this_).html("<i class='fa fa-lock'></i> Block/Unblock");

					});

				});
                
            });


        </script>
    </body>

</html>
