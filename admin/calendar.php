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

		if($perm['perm_calendar'] != "true"){

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



    function get_emp_time_off_by_admin()
    {
        global $DBH,$SESS_COMPANY_ID;
        $dataArray = array($SESS_COMPANY_ID);
        $data = sql($DBH,'select * from tbl_time_off where company_id = ?',$dataArray,'rows');
        return $data;

    }

    function get_employee_color($employee_id)
    {
        global $DBH;
        $dataArray = array($employee_id);
        $data = sql($DBH,'select * from tbl_employee_profile where employee_id = ?',$dataArray,'rows');
        return $data;
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

        <link href="assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css" />
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
        .emp_color{
          position: relative;
    width: 20px;
    height: 20px;
    display: block;
    top: -15px;
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
              margin:0 !important;


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
                        ;
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
                            <div class="page-content-inner" style="overflow-x: scroll;">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light portlet-fit  calendar">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class=" icon-layers font-green"></i>
                                                            <span class="caption-subject font-green sbold uppercase">Calendar</span>
                                                        </div>
                                                    </div>
                                                    <div  class="portlet-body">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-12">
                                                <div class="portlet light portlet-fit ">

                                                    <div>
                                                        <div class="mt-element-list">
                                                            <div class="mt-list-head list-default green-haze">
                                                                <div class="row">
                                                                    <div class="col-sm-12">
                                                                        <div class="list-head-title-container">
                                                                            <h3 class="list-title uppercase sbold">Staff</h3>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
        <div class="mt-list-container list-default" style="padding: 5px;">
            <ul>
                <?php
                    $data = sql($DBH, "SELECT * FROM tbl_login where company_id = ? order by (access_level)", array($SESS_COMPANY_ID), "rows");

                    $color_code_arr = [];
                    $emp_cc  = [];
                    foreach ($data as $key => $value) {
                        // $emp_data =  get_employee_login_info($value['employee_id']);
                       $employee_color =  get_employee_color($value['id'])[0]['employee_color'];

                 ?>
                <li class="mt-list-item" style="padding: 5px 0;">
                    <div class="pull-left">
                        <img src="<?= video_to_photo($value['photo']) ?>" width="43px" height="43px" style="border-radius: 100px !important">
                    </div>
                    <div class="list-item-content" style="    padding: 0px 0px 0px 50px;">
                        <h3 class="uppercase bold" style="position: relative;top: 9px;">
                            <a href="javascript:;"><?= $value['fullname'] ?></a>
                        </h3>
                      <span class="badge pull-right emp_color" style="background: <?= $employee_color ?>;"></span>

                    </div>
                    <div class="clearfix"></div>
                </li>
                <?php
                    }

                 ?>
            </ul>
        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                            </div>
                                                            <div class="col-md-9 col-sm-12">
                                                                <div id="calendar" class="has-toolbar"> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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

         <script src="assets/global/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>



        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>

        <!-- <script src="assets/apps/scripts/calendar.min.js" type="text/javascript"></script> -->

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
        <script type="text/javascript">
            var AppCalendar = function() {

    return {
        //main function to initiate the module
        init: function() {
            this.initCalendar();
        },

        initCalendar: function() {

            if (!jQuery().fullCalendar) {
                return;
            }

            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            var h = {};

            if (App.isRTL()) {
                if ($('#calendar').parents(".portlet").width() <= 720) {
                    $('#calendar').addClass("mobile");
                    h = {
                        right: 'title, prev, next',
                        center: '',
                        left: 'agendaDay, agendaWeek, month, today'
                    };
                } else {
                    $('#calendar').removeClass("mobile");
                    h = {
                        right: 'title',
                        center: '',
                        left: 'agendaDay, agendaWeek, month, today, prev,next'
                    };
                }
            } else {
                if ($('#calendar').parents(".portlet").width() <= 720) {
                    $('#calendar').addClass("mobile");
                    h = {
                        left: 'title, prev, next',
                        center: '',
                        right: 'today,month,agendaWeek,agendaDay'
                    };
                } else {
                    $('#calendar').removeClass("mobile");
                    h = {
                        left: 'title',
                        center: '',
                        right: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                }
            }



            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar({ //re-initialize the calendar
                header: h,
                defaultView: 'month', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/
                slotMinutes: 15,
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function(date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');
                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;
                    copiedEventObject.className = $(this).attr("data-class");

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                events: [
                <?php
                    $data = get_emp_time_off_by_admin();
                    if (!empty($data)) {
                        $i = -1;
                        foreach ($data as $key => $value) {

                            $i++;
                            $f_y = date('Y',$value['time_off_from_date']);
                            $f_m = date('m',$value['time_off_from_date']);
                            $f_d = date('d',$value['time_off_from_date']);

                            $t_y = date('Y',$value['time_off_to_date']);
                            $t_m = date('m',$value['time_off_to_date']);
                            $t_d = date('d',$value['time_off_to_date']);
                            $employee_color = get_employee_color($value['employee_id']);
                            $employee_name  = get_employee_login_info($value['employee_id'])[0]['fullname'];
                            

                            // echo "<pre>";
                            // print_r(date('F-d-Y',$value['time_off_from_date']));
                            // echo "</pre>";

                 ?>
                {
                    title: "<?= $employee_name.": ".$value['comment'] ?>",
                    start: new Date(<?= $f_y ?>, <?= $f_m ?> -1, <?= $f_d ?> ),
                    end: new Date(<?= $t_y ?>, <?= $t_m ?> -1, <?= $t_d ?> + 1),
                    backgroundColor:"<?=$employee_color[0]['employee_color']?>"
                },
                <?php
                        }
                    }
                 ?>
               ]
            });

        }

    };

}();

jQuery(document).ready(function() {
   AppCalendar.init();
});
        </script>
    </body>



</html>
