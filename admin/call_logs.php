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




	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_contacts'] != "true"){
			//$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			//redirect('index.php');
		}
	}else if($SESS_ACCESS_LEVEL == "user"){
		//$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		//redirect('index.php');
	}else{
		//$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
	//redirect('index.php');
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
      #map{
        width:100%;
      }
        </style>

		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


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
										$rows = sql($DBH, "SELECT * FROM tbl_call_logs where employee_id = ?", array($SESS_DEVICE_ID), "rows");
										$sync_date_time = "never";
										foreach($rows as $row){
											$sync_data			= json_decode($row['data'],true);
											$sync_date_time		= my_simple_date($row['date_time']);
										}
									?>

									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-phone"></i>

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

                                            <?php echo $users_call_log_xml; ?> ( <?php echo $last_synced_xml; ?> <?php echo $sync_date_time; ?> )


                                        </div>
									</div>
									<div class="portlet-body">
										<table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
											<thead>
												<tr>
													<th>#</th>
													<th><?php echo $display_name_xml; ?></th>
													<th><?php echo $phone_number_xml; ?></th>
													<th><?php echo $type_xml; ?></th>
                          <th>GPS Location</th>
													<th><?php echo $duration_xml; ?></th>
													<th><?php echo $date_xml; ?></th>
												</tr>
											</thead>
											<tbody>
                        <tr>
                          <td>#</td>
                          <td>Ghulam Abbass</td>
                          <td>+923323137489</td>
                          <td>Dailed</td>
                          <td>
                            <a class="show_map" lat="24.9195966" lon="67.1190969" data-toggle="modal" href="#modal_gps">Show Map</a>
                          </td>
                          <td>0</td>
                          <td>15-Jan-2019 8:17AM</td>
                        </tr>
												<?php

													foreach($sync_data as $row){
														$sno++;
														$date			     = my_simple_date(round($row['date']/1000));
														$number			   = $row['number'];
														$type    		   = $row['type'];
														$duration		   = $row['duration'];
														$cachedName		 = $row['cachedName'];
														$name			     = $row['name'];
														//$phoneAccountId = $row['phoneAccountId'];

														if(strlen($name) == 0){
															$name = $cachedName;
														}
														if(strlen($name) == 0){
															continue;
														}

														if ($type == 1) {
															$type = "<span class='text-primary'><i style='font-size:18px;' class='material-icons'>phone_in_talk</i> $received_xml</span>";
														} else if ($type == 2) {
															$type = "<span class='text-success'><i style='font-size:18px;' class='material-icons'>phone_forwarded</i> $dailed_xml</span>";
														} else if ($type == 3) {
															$type = "<span class='text-danger'><i style='font-size:18px;' class='material-icons'>phone_missed</i> $missed_xml</span>";
														}

														echo "<tr>
															<td>$sno</td>
															<td>$name</td>
															<td>$number</td>
															<td>$type</td>
                              <td></td>
															<td>$duration sec(s)</td>
															<td>$date</td
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


        <div class="modal fade" id="modal_gps" tabindex="-1" role="basic" aria-hidden="true">
           <div class="modal-dialog">
               <div class="modal-content">
                   <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                       <h4 class="modal-title">GPS Location</h4>
                   </div>
                   <div class="modal-body">
                      <div id="map"></div>
                   </div>
                   <div class="modal-footer">
                       <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    </div>
               </div>
           </div>
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
            var map;
            var markers = [];
            var infowindow;

            function initMap() {
        				map = new google.maps.Map(document.getElementById('map'), {
        				center: {lat: -34.397, lng: 150.644},
        				zoom: 8
        				});

                infowindow = new google.maps.InfoWindow({
                  content: "Call Location"
                });
        		}

            $(document).ready(function(){

                  function setMapOnAll(map) {
                      for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(map);
                      }
                  }

               $(".show_map").click(function(){
                
                var lat = +$(this).attr("lat");
                var lon = +$(this).attr("lon");
                
                $("#map").css('height',($(window).height()-200)+'px');               
                      
                 setTimeout(function(){
                     initMap();
                     setMapOnAll(null);
    
                      
                      var accuracy = 26;
    						
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
                 
                 
                  $('[data-toggle="tooltip"]').tooltip();
                   $("#single").change(function(){
                        var id = $(this).val();
                        window.location.href = "select_device.php?id="+id;
                   });
            });
            
            
              





          </script>
    </body>

</html>
