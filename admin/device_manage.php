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



    $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($SESS_DEVICE_ID), "rows");
	$sim_serial = " <small>Not Logged In Yet</small>";
	foreach($rows as $row){
		$sim_serial		= $row['sim_serial'];
	}

	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_device_management'] != "true"){
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


    $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_options where employee_id =  ?");
	$result 	   	         = $STH->execute(array($SESS_DEVICE_ID));
	$count_data_options	     = $STH->fetchColumn();
    if($count_data_options == 0){
        sql($DBH,"INSERT INTO tbl_data_interval_options (employee_id) VALUES (?);",array($SESS_DEVICE_ID));
    }

    $STH 			         = $DBH->prepare("select count(*) FROM tbl_data_interval_time where employee_id =  ?");
	$result 	   	         = $STH->execute(array($SESS_DEVICE_ID));
	$count_data_interval	 = $STH->fetchColumn();
    if($count_data_interval == 0){
        sql($DBH,"INSERT INTO tbl_data_interval_time (employee_id) VALUES (?);",array($SESS_DEVICE_ID));
    }

    //device information
    $rows = sql($DBH, "SELECT * FROM tbl_device_info where employee_id = ?", array($SESS_DEVICE_ID), "rows");
	foreach($rows as $row){
		$sync_device_data			= json_decode($row['data'],true);
		$sync_device_date_time		= my_simple_date($row['date_time']);
	}

    $rows = sql($DBH, "SELECT * FROM tbl_battery where employee_id = ?", array($SESS_DEVICE_ID), "rows");
	foreach($rows as $row){
		$sync_battery_data			= json_decode($row['data'],true);
		$sync_battery_date_time		= my_simple_date($row['date_time']);
	}

    $rows = sql($DBH, "SELECT * FROM tbl_storage where employee_id = ?", array($SESS_DEVICE_ID), "rows");
	foreach($rows as $row){
        $free_space                 = json_decode($row['data'])->free;
		$total_space                = json_decode($row['data'])->total;
        $used_space                 = $total_space - $free_space ; 
        $free_disk_space            = round(($free_space/(1024*1024*1024)),2);
       
        
		$sync_storage_date_time		= my_simple_date($row['date_time']);
	}

    $rows = sql($DBH, "SELECT * FROM tbl_network where employee_id = ?", array($SESS_DEVICE_ID), "rows");
	foreach($rows as $row){
		$sync_network_data			= $row['data'];
		$sync_network_date_time		= my_simple_date($row['date_time']);
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

                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
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
                            <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
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


                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet box red">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-cog"></i>

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


                                            's <?php echo $device_management_xml; ?>

                                            <span id="device_update_time">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>

                                        </div>
									</div>

    			    	            <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-4">
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
                                                                        if($sync_battery_data['level'] < 20){
                                                                            echo "<i class='fa fa-battery-0 text-danger'></i>";
                                                                        }else if($sync_battery_data['level'] >= 20 && $sync_battery_data['level'] < 50){
                                                                            echo "<i class='fa fa-battery-1'></i>";
                                                                        }else if($sync_battery_data['level'] >= 50 && $sync_battery_data['level'] < 75){
                                                                            echo "<i class='fa fa-battery-2'></i>";
                                                                        }else if($sync_battery_data['level'] >= 75 && $sync_battery_data['level'] < 90){
                                                                            echo "<i class='fa fa-battery-3'></i>";
                                                                        }else if($sync_battery_data['level'] >= 90){
                                                                            echo "<i class='fa fa-battery-4 text-success'></i>";
                                                                        }
                                                                        echo " ".$sync_battery_data['level']."%";


                                                                        if($sync_battery_data['isPlugged'] == true){
                                                                            echo " &bull; <i class='fa fa-plug'></i> $charging_xml";
                                                                        }else{
                                                                            echo " &bull; <i class='fa fa-unlink'></i> $not_charging_xml";
                                                                        }
                                                                        echo "<br>";
                                                                        echo " <small><i class='fa fa-clock-o'></i> $sync_battery_date_time</small>";
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
                                                                <?php
                                                                    echo $free_disk_space.  "$nbsp GB";
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
                												            <span id="remote_update_time">
                                                                                <i class="fa fa-spinner fa-spin"></i>
                                                                            </span>
                                                                            <?php
                                                                                $rows = sql($DBH, "SELECT * FROM tbl_locations
                                                                                where employee_id = ? order by (id) limit 1",
                                            									array($SESS_DEVICE_ID), "rows");
                                                                                $sync_date_time		= "No Location";
                                                                                foreach($rows as $row){
                                                                                    $sync_date_time		= my_simple_date($row['date_time']);
                                                                                }

                                                                                $rows = sql($DBH, "SELECT * FROM tbl_remove_device_lock where employee_id = ?", array($SESS_DEVICE_ID), "rows");
                                                                                foreach($rows as $row){
                                                                                    $send_message	= $row['send_message'];
                                                                                    $message_title	= $row['message_title'];
                                                                                    $message_body	= $row['message_body'];
                                                                                    $play_sound  	= $row['play_sound'];
                                                                                    $remote_lock	= $row['remote_lock'];
                                                                                    $remote_wipe	= $row['remote_wipe'];
                                                                                }
                                                                            ?>
                                                                            <br />
                                                                            <span>Last Location: <?php echo $sync_date_time; ?></span>

                                                                            <?php
                                                                                $send_message_html = '<i class="fa fa-envelope"></i> Send Message';

                                                                                if($play_sound == "false"){//no command
                                                                                    $play_sound_html = '<i class="fa fa-play"></i> Play Sound';
                                                                                }else if($play_sound == "true"){
                                                                                    $play_sound_html = '<i class="fa fa-spinner"></i> Playing Sound..';
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
                                                                            <a id="play_sound" class="btn purple btn-block"><?php echo $play_sound_html; ?></a>
                                                                        </div>
                            										</div>
                            									</div>
                            								</div>
                            							</div>
                                                    </div>
                                                </div>
                							</div>


                                           </div>


                                           <div class="col-md-8">
                                                <form method="post" action="exe_device_manage.php">
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
                                                    $rows = sql($DBH, "SELECT * FROM tbl_data_interval_time where employee_id = ?", array($SESS_DEVICE_ID), "rows");
                                                    $data_interval_time = $rows[0];

                                                    $rows = sql($DBH, "SELECT * FROM tbl_data_interval_options where employee_id = ?", array($SESS_DEVICE_ID), "rows");
                                                    $data_interval_opt = $rows[0];
                                                    foreach($data_interval_opt as $data_code => $network_option){
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



            <div id="message_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Send Message</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <form id="send_message_form">
                                    <div class="form-group col-md-12">
										<label>Message Title:</label><Br>
										<input value="<?php echo $message_title; ?>" type="text" name="message_title" class="form-control" placeholder="Title here.." />
                                    </div>
                                    <div class="form-group col-md-12">
										<label>Message Title:</label><Br>
										<textarea name="message_body" class="form-control" placeholder="Enter message here.."><?php echo $message_body; ?></textarea>
									</div>
                                    <div class="form-group col-md-12">
										<label>&nbsp;</label><br />
										<button id="send_message_btn" type='submit' class="btn blue"><i class="fa fa-envelope"></i> Send Message</button>
									</div>
								</form>
							</div>
						</div>
                    </div>
                </div>
            </div>
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
       <!-- <script src="assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.stack.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.crosshair.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/flot/jquery.flot.axislabels.js" type="text/javascript"></script>-->


		<!-- BEGIN PAGE LEVEL PLUGINS
		<script src="assets/global/scripts/datatable.js" type="text/javascript"></script>
		<script src="assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
		-->

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

        <!--<script src="assets/pages/scripts/charts-flotcharts.js" type="text/javascript"></script>-->



		<!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS
		<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        -->
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>

		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME LAYOUT SCRIPTS -->
		<script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
		<script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>


        <script>
            $(document).ready(function(){

                setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);
                function device_update_time(){
                    $.get("ajax/device_update_time.php",function(data){
                    $("#device_update_time").html(data);
                    setTimeout(function(){
                        device_update_time();
                    },3000);
                    });
                }

                device_update_time();

            });

        </script>

        <script>
            $(document).ready(function(){
               setTimeout(function(){
                    $(".remove_after_5").slideUp();
               },5000);

               $("#send_message_form").submit(function(e){
                    e.preventDefault();
                    var message_title   = $("input[name='message_title']").val();
                    var message_body    = $("textarea[name='message_body']").val();
                    $("#send_message_btn").html('<i class="fa fa-refresh fa-spin"></i> Sending Message..');
                    $.get("ajax/remote_device_actions.php?action=send_message&title="+message_title+"&body="+message_body,function(){
                        $("#send_message_btn").html('<i class="fa fa-check"></i> Message Sent!');
                    });
               });
               $("#remote_lock").click(function(){
                    $("#remote_lock").html('<i class="fa fa-refresh fa-spin"></i> Sending Command..');
                    $.get("ajax/remote_device_actions.php?action=remote_lock",function(){
                        $("#remote_lock").html('<i class="fa fa-spinner"></i> Locking Device..');
                    });
               });
               $("#remote_wipe").click(function(){
                    $("#remote_wipe").html('<i class="fa fa-refresh fa-spin"></i> Sending Command..');
                    $.get("ajax/remote_device_actions.php?action=remote_wipe",function(){
                        $("#remote_wipe").html('<i class="fa fa-spinner"></i> Wiping Phone..');
                    });
               });
               $("#play_sound").click(function(){
                    $("#play_sound").html('<i class="fa fa-refresh fa-spin"></i> Sending Command..');
                    $.get("ajax/remote_device_actions.php?action=play_sound",function(){
                        $("#play_sound").html('<i class="fa fa-spinner"></i> Playing Sound..');
                    });
               });

               function remote_update_time(){
                    $.get("ajax/remote_update_time.php",function(data){
                    $("#remote_update_time").html(data);
                    setTimeout(function(){
                        remote_update_time();
                    },3000);
                    });
                }

                remote_update_time();
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
