<?php

		require_once('../class_function/session.php');
		require_once('../class_function/error.php');
		require_once('../class_function/dbconfig.php');
		require_once('../class_function/function.php');
		require_once('../class_function/validate.php');
		require_once('../class_function/language.php');

        echo "<table>";
  echo "<tr><th>#</th><th>Location</th><th>Accuracy (m)</th><th>Distance (m)</th><th>Time (sec)</th><th>Speed (km/h)</th><th>Date Time</th><tr>";
	$rows = sql($DBH,"select * from tbl_locations where employee_id = ? order by (date_time) asc",array("2"),"rows");

  $old_lat = null;
  $old_lon = null;
  $old_ts = null;
  $total_trip_distance = 0;

  $i=0;
	foreach($rows as $row){

    $id         = $row['id'];
    $movements  = $row['movements'];
    $data       = json_decode($row['data'], true);
    $time_stamp = round($data['timestamp']/1000);

    $date_time  = date("d-M, h:i:s a", $time_stamp);

    $latitude = $data['coords']['latitude'];
    $longitude = $data['coords']['longitude'];
    $accuracy = round($data['coords']['accuracy'],2);

    if($old_lat == null || $old_lon == null){
      $distance = "Starting Point";
    }else{
      $distance  = round(haversineGreatCircleDistance( $old_lat, $old_lon, $latitude, $longitude),2);
      $time      = round($time_stamp-$old_ts,2);
      $speed     = round((($distance/$time)*60*60)/1000,2);

      if($speed >= 80){ //too much speed
          continue;
      }
      if($time <= 0){ //old data
          continue;
      }
      if($distance <= 10){
          continue;
      }
    }


    $total_trip_distance += $distance;


    $i++;
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td><a target='_blank' href='https://www.google.com/maps/search/?api=1&query=$latitude,$longitude'>$latitude,$longitude</a></td>";
    echo "<td>$accuracy</td>";

    echo "<td>$distance Meter(s)</td>";
    echo "<td>$time Sec(s)</td>";
    echo "<td>$speed Km/h</td>";

    echo "<td>".$date_time."</td>";
    echo "<tr>";

    //remember the data

    $old_lat = $latitude;
    $old_lon = $longitude;
    $old_ts  = $time_stamp;

	}
  echo "</table>";

  echo "Total Trip Distance: ";
  echo round($total_trip_distance/1000,2)." Km";
?>

<style>
  table{
    width:100%;
    border:1px solid #000;
    border-collapse: collapse;
  }
  table td, table th{
    border:1px solid #000;
  }
</style>
