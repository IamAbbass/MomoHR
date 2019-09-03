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


    $polygons 	= array();
	$rows 		= sql($DBH, "SELECT * FROM tbl_geo_fence where admin_id = ? ", array($SESS_ID), "rows");
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
            .deleting, .deleting:hover, .deleting:active{
                box-shadow:0 0 1000px #ff0000 inset !important;
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

								$date_from  = $_GET['date_from'];
								$date_to  	= $_GET['date_to'];

								if((strlen($date_from) > 0 && strlen($date_to) > 0)){
									$from_ts    = strtotime($date_from);
									$to_ts      = strtotime($date_to);
									$to_ts      = $to_ts+86400;

									$rows = sql($DBH, "SELECT * FROM tbl_locations
                                    where login_id = ? && (date_time >= ? AND date_time <= ? )",
									array($SESS_DEVICE_ID,$from_ts,$to_ts), "rows");

									if((strlen($date_from) > 0 && strlen($date_to) > 0)){
										$filter_text = " $from_xml ".$date_from." $to_xml ".$date_to;
									}
									$filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_xml</a>";


									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
                                    where login_id = ? && (date_time >= ? AND date_time <= ? )");
									$result  		= $STH->execute(array($SESS_DEVICE_ID,$from_ts,$to_ts));
									$count_total	= $STH->fetchColumn();

								}else{
									$date_from = date("m/d/Y");
									$date_to   = date("m/d/Y");
                                    $from_ts    = strtotime($date_from);
									$to_ts      = strtotime($date_to);

                                    $from_ts    = $from_ts-86400;  //18-aug to (today is 19th)
									$to_ts      = $to_ts+86400;    //20-aug

									$rows = sql($DBH, "SELECT * FROM tbl_locations
                                    where login_id = ? && (date_time >= ? AND date_time <= ? )", 
									array($SESS_DEVICE_ID,$from_ts,$to_ts), "rows");

									$filter_text = "$showing_last_hours_xml"; //$showing_all_records_xml

									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
                                    where login_id = ? && (date_time >= ? AND date_time <= ? )");
									$result  		= $STH->execute(array($SESS_DEVICE_ID,$from_ts,$to_ts));
									$count_total	= $STH->fetchColumn();
								}

                                $locations = array();
                                $i=0;
                                //$rows = sql($DBH, "SELECT * FROM tbl_locations where login_id = ? order by (date_time) desc", array($SESS_DEVICE_ID), "rows");
                            	$sync_date_time = "$never_xml";
                            	foreach($rows as $row){
                            		$sync_data			= json_decode($row['data'],true);
                                    $movements			= $row['movements'];
                            		$sync_date_time		= my_simple_date($row['date_time']);
                                    $accuracy           = $sync_data['coords']['accuracy'];

                                    if($accuracy <= 26){
                                        $locations[$i]["date_time"] = $sync_date_time;
                                        foreach($sync_data['coords'] as $key => $value){
                                            $locations[$i][$key]        = $value;
                                    	}

                                        $locations[$i]["data"]        = "<i class='fa fa-clock-o'></i> ".$sync_date_time;
                                        $locations[$i]["data"]       .= "</br><i class='fa fa-street-view'></i> ".round($accuracy)."m";
                                        $locations[$i]["data"]       .= "</br><i class='fa fa-mobile'></i> $movements";

                                        $i++;
                                    }
                            	}
                                //die(json_encode($locations));



							?>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet box red">
									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-map-marker"></i>


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

                                            's <?php echo $locations_xml; ?> <?php if(strlen($parent_fullname) > 0) echo "$of_xml $parent_fullname"; ?> <small> - <?php echo $filter_text; ?></small></div>
										<div class="actions hidden">
											<a href="#" class="btn btn-default btn-sm btn-circle">
												<i class="fa fa-plus"></i>
											</a>
										</div>
									</div>
									<div class="portlet-body">
                                        <div id="map" style="width: 100%; height: 100%;">
                                            <?php
                                                if($count_total == 0){
                                                    echo "<br /><br /><h1 class='page-title text-center text-danger'>$no_locations_xml</h1>";
                                                }
                                            ?>
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
          var locations = <?php echo json_encode($locations); ?>;
          function initMap() {
            var origin = new google.maps.LatLng(locations[0]["latitude"], locations[0]["longitude"]);
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: origin
            });
            var infowindow = new google.maps.InfoWindow();
			var marker, i;
			for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i]["latitude"], locations[i]["longitude"]),
                    map: map,
                    icon: "http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld="+(i+1)+"|ea4335|ffffff"
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

                google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
    				return function() {
    				  infowindow.setContent(locations[i]["data"]);
    				  infowindow.open(map, marker);
    				}
    			  })(marker, i));
                  google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
    				return function() {
    				  infowindow.close();
    				}
		         })(marker, i));

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
					office_locations.addListener('mouseover', function(event) {
						var vertices = this.getPath();
						var contentString = '<b><?php echo $name_xml; ?> '+name+'</b><br>' +
							/*'Clicked location: <br>' + event.latLng.lat() + ',' + event.latLng.lng() +*/
							'<?php echo $created_xml; ?> '+ date_time +
							'<br>' +
							'<a class="btn btn-danger btn-xs del_fence" href="exe_del_fence.php?location_id='+location_id+'"><i class="fa fa-times"></i> <?php echo $delete_xml ; ?></a>'


						/*
						for (var i =0; i < vertices.getLength(); i++) {
						  var xy = vertices.getAt(i);
						  contentString += '<br>' + 'Coordinate ' + i + ':<br>' + xy.lat() + ',' +
							  xy.lng();
						}*/
						infoWindow.setContent(contentString);
						infoWindow.setPosition(event.latLng);
						infoWindow.open(map);
					});

					office_locations.addListener('mouseout', function(event) {
						infoWindow.close(map);
					});
				});

			}
          }
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
        <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key; ?>&callback=initMap" async defer></script>
    </body>

</html>
