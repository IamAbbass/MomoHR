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



	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_app_list'] != "true"){
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
                                    $rows = sql($DBH, "SELECT * FROM tbl_app_list where employee_id = ?", array($SESS_DEVICE_ID), "rows");
                                    $sync_date_time = "
			<small>$never_synced_xml</small>";
                                    foreach($rows as $row){
                                        $sync_data			= json_decode($row['data'],true);
                                        $sync_date_time		= my_simple_date($row['date_time']);
                                    }
                                    ?>
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-android"></i>
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
                                            <?php echo $installed_apps_xml; ?>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>App Name</th>
                                            </tr>
                                            </thead>
                                            <tfoot class="hidden">
                                            <tr>
                                                <th>#</th>
                                                <th>App Name</th>
                                            </tr>
                                            </tfoot>
                                            <tbody>
                                            <?php


                                            $sno = 0;
                                            foreach($sync_data as $key => $value){
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

                $(document).delegate(".active_inactive","click",function(e){
					e.preventDefault();

					var this_ = $(this);
					$(this_).children("i").removeClass("fa-lock fa-unlock").addClass("fa-refresh fa-spin");
					$.get($(this).attr("href"),function(data){
						data = $.parseJSON(data);
						if(data.error == ""){
							alert(data.error);
						}else{
							$(this_).html(data.new_html);
							$(this_).removeClass("btn-success btn-danger").addClass(data.new_class);
						}
					});

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
