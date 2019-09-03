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

        

	

	if($SESS_ACCESS_LEVEL == "root admin"){

		//allow

	}else if($SESS_ACCESS_LEVEL == "admin"){

		if($perm['perm_time_off'] != "true"){

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
                /*display: inline-block;*/
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
                                
                                <!-- time off start -->
                                
                                
                                check whatsapp design the time off page here
                                
                                                                
                                <!-- time off end -->
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

    </body>



</html>