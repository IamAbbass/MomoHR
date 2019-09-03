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



    $geo_fencing             = $xml->geo_fencing->geo_fencing;

    $saved_locations         = $xml->geo_fencing->saved_locations;

    $name_this               = $xml->geo_fencing->name_this;

    $saving_location         = $xml->geo_fencing->saving_location;

    $location_saved          = $xml->geo_fencing->location_saved;

    $saving_location_failed  = $xml->geo_fencing->saving_location_failed;

    $name_xml                = $xml->geo_fencing->name;

    $confirm_delete_location = $xml->geo_fencing->confirm_delete_location;

    $created_xml             = $xml->geo_fencing->created;

    $delete_xml              = $xml->geo_fencing->delete;

    $access_denied_xml       = $xml->geo_fencing->access_denied;

	$no_access_xml           = $xml->geo_fencing->no_access;



	if($SESS_ACCESS_LEVEL == "root admin"){

		//allow

		if($_GET['id']){

			$SESS_COMPANY_ID = $_GET['id'];

			$rows 		= sql($DBH, "SELECT * FROM tbl_login where company_id = ? ", array($SESS_COMPANY_ID), "rows");

			foreach($rows as $row){

				$admin_name	= $row['fullname'];

			}

		}

	}else if($SESS_ACCESS_LEVEL == "admin"){

		if($perm['perm_geo_fencing'] != "true"){

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

	$rows 		= sql($DBH, "SELECT * FROM tbl_geo_fence where company_id = ? ", array($SESS_COMPANY_ID), "rows");

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



		<style>

		  /* Always set the map height explicitly to define the size of the div

		   * element that contains the map. */

		  #map {

			height: 100%;

		  }

		  /* Optional: Makes the sample page fill the window. */

		  html, body {

			height: 100%;

			margin: 0;

			padding: 0;

		  }

		  .geo-fence-status{

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

								<h1 class="page-title"> <i class="fa fa-map"></i> <?php echo $geo_fencing; ?>

								<?php

								if($SESS_ACCESS_LEVEL == "root admin"){

										if($_GET['id']){

											echo "<b> - $admin_name</b>";

										}

								}

								?>



								</h1>







								<div class='note note-info geo-fence-status'><p></p></div>

							</div>

						</div>



						<div class="row">



							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">

								<h4><?php echo $saved_locations; ?></h4>

								<ol id="list_locations_here">



								</ol>

							</div>







							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">

								<div id="map"></div>

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



		var map;

		var infoWindow;



		function initMap() {



				map = new google.maps.Map(document.getElementById('map'), {

				center: {lat: -34.397, lng: 150.644},

				zoom: 8

				});



				if (navigator.geolocation) {

				navigator.geolocation.getCurrentPosition(function (position) {

				initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

				map.setCenter(initialLocation);

				});

				}



				var drawingManager = new google.maps.drawing.DrawingManager({

    				//drawingMode: google.maps.drawing.OverlayType.MARKER,

    				// drawingControl: true,

    				drawingControlOptions: {

    				position: google.maps.ControlPosition.TOP_CENTER,

    				drawingModes: ['polygon'] /*['marker', 'circle', 'polygon', 'polyline', 'rectangle']*/

    				},

    				polygonOptions: {

                        icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png',

                        strokeColor: '#FF0000',

                        strokeOpacity: 0.8,

                        strokeWeight: 3,

                        fillColor: '#FF0000',

                        fillOpacity: 0.35

    				}

				});



				google.maps.event.addListener(drawingManager, 'polygoncomplete', function (polygon) {

    				var points = [];

    				for (var i = 0; i < polygon.getPath().getLength(); i++) {

    				    points.push(polygon.getPath().getAt(i).toUrlValue(6));

    				}



    				var name = prompt("<?php echo $name_this; ?>", "");



    				if (name != null) {

        				$(".geo-fence-status p").html("<i class='fa fa-spinner fa-spin'></i> <?php echo $saving_location; ?>..");

        				$(".geo-fence-status").slideDown();

        				$.get("ajax/save_geo_fence.php?pid=<?php echo $SESS_COMPANY_ID ?>&name="+name+"&ploygon="+encodeURI(JSON.stringify(points)),function(data){

            				if(data != "FAILED"){

            				    $(".geo-fence-status p").html("<i class='fa fa-check'></i> <?php echo $location_saved; ?>");

            				    $("#list_locations_here").load("ajax/get_locations.php?id=<?php echo $SESS_COMPANY_ID; ?>");

                            }else{

            				    $(".geo-fence-status p").html("<i class='fa fa-times'></i> <?php echo $saving_location_failed;?>");

            				}

            				$(".geo-fence-status").slideDown();

            				setTimeout(function(){

            				    $(".geo-fence-status").slideUp();

            				},3000);

        				});

    				}else{

    				    //remove polygon

    				    polygon.setMap(null);

    				}

				});



				drawingManager.setMap(map);





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

					  fillColor: '#FF0000',

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



				infoWindow = new google.maps.InfoWindow;

		}





		$(document).ready(function(){

			setTimeout(function(){

    		    $(".remove_after_5").slideUp();

    		},5000);





            $("#list_locations_here").load("ajax/get_locations.php?id=<?php echo $SESS_COMPANY_ID; ?>");



            $(document).delegate(".del_fence","click",function(e){

				var x = confirm("<?php echo $confirm_delete_location; ?>");

				if(!x){

					e.preventDefault();

				}

			});



            $(document).delegate(".map-re-center","click",function(e){

				var lat = +$(this).attr("lat");

				var lng = +$(this).attr("lng");

				map.setCenter({lat: lat, lng: lng});

				map.setZoom(17);



			});

		});



	</script>

    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api_key; ?>&libraries=drawing&callback=initMap"

         async defer></script>

    </body>



</html>

