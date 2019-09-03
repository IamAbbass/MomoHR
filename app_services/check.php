<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

	sql($DBH, "insert into tbl_debug_app(data) values (?);", array(json_encode($_REQUEST)), "rows");

	$arr = array();

	$employee_id		= $_GET['id'];
	$action 			= $_GET['action'];

    $rows 		= sql($DBH, "SELECT * FROM tbl_login where id = ? and status = ?", array($employee_id,"active"), "rows");
	foreach($rows as $row){
		$parent_id	= $row['parent_id'];
	}

    $day            = strtotime(date("d-m-Y",time()));

    $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_attendance where
    employee_id = ? and day = ? and type = ?");
	$result  		= $STH->execute(array($employee_id,$day,$action));
	$count_att     	= $STH->fetchColumn();

    if($count_att == 0){

        sql($DBH, "insert into tbl_attendance
        (admin_id,employee_id,location_id,type,day,position_json,date_time)
        values (?,?,?,?,?,?,?);",
    	array($parent_id,$employee_id,$location_id,$action,$day,$position_json,time()), "rows");

        $arr['error'] 	= "Successfully marked ".ucfirst(str_replace("_"," ",$action))."!";
    	$arr['success'] = true;

    }else{

        $arr['error'] 	= "You already marked ".ucfirst(str_replace("_"," ",$action))."!";
    	$arr['success'] = true;

    }



    echo json_encode($arr);
	exit;




    /*
	sql($DBH, "insert into tbl_debug_app(data) values (?);", array(json_encode($_REQUEST)), "rows");


	$arr = array();

	function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
         ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
           $c = !$c;
        }
        return $c;
    }


	$employee_id		= $_GET['id']; //employee_id
	$action 			= $_GET['action'];	//check in out off
    $position_json      = urldecode($_GET['position']);
	$position			= json_decode($position_json);

	$latitude			= $position['coords']['latitude'];
	$longitude			= $position['coords']['longitude'];

	//finding admin
	$rows 		= sql($DBH, "SELECT * FROM tbl_login where id = ? and status = ?", array($employee_id,"active"), "rows");
	foreach($rows as $row){
		$parent_id	= $row['parent_id'];
	}
	//findind locations
	$STH 	 			= $DBH->prepare("SELECT count(*) FROM tbl_geo_fence where admin_id = ?");
	$result  			= $STH->execute(array($parent_id));
	$count_locations 	= $STH->fetchColumn();

	$location_id 		= null;

	$rows 		= sql($DBH, "SELECT * FROM tbl_geo_fence where admin_id = ? ", array($parent_id), "rows");
	foreach($rows as $row){
		$l_id				= $row['id'];
		$location_name		= $row['name'];
		$polygon_json 		= json_decode($row['polygon_json']);

		//algo
		$vertices_x = array(); //lon
		$vertices_y	= array(); //lat

		foreach($polygon_json as $point){
			$point = explode(",",$point);
			$vertices_y[] = $point[0];//lat
			$vertices_x[] = $point[1];//lon
		}

		$points_polygon = count($vertices_x) - 1;
		if(is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude, $latitude)){
			$location_id 	= $l_id;

			//$STH 	 			= $DBH->prepare("SELECT count(*) FROM tbl_geo_fence where admin_id = ?;");
			//$result  			= $STH->execute(array($parent_id));
			//$count_locations 	= $STH->fetchColumn();

			break;
		}else{
			continue;
		}
	}

    $day            = strtotime(date("d-m-Y",time()));

    $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_attendance where
    employee_id = ? and day = ? and type = ?");
	$result  		= $STH->execute(array($employee_id,$day,$action));
	$count_att     	= $STH->fetchColumn();

    if($count_att == 0){

        sql($DBH, "insert into tbl_attendance
        (admin_id,employee_id,location_id,type,day,position_json,date_time)
        values (?,?,?,?,?,?,?);",
    	array($parent_id,$employee_id,$location_id,$action,$day,$position_json,time()), "rows");

        $arr['error'] 	= "Successfully marked ".ucfirst(str_replace("_"," ",$action))."!";
    	$arr['success'] = true;

    }else{

        $arr['error'] 	= "You already marked ".ucfirst(str_replace("_"," ",$action))."!";
    	$arr['success'] = true;

    }



    echo json_encode($arr);
	exit;
    */
?>
