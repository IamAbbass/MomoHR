<?php
    require_once('../class_function/session.php');
  	require_once('../class_function/error.php');
  	require_once('../class_function/dbconfig.php');
  	require_once('../class_function/function.php');
  	require_once('../class_function/validate.php');
  	require_once('../class_function/language.php');
    
      $employee_id = 5;
      $date_from  = $_GET['date_from'];
      $date_to  	= $_GET['date_to'];
    
      if((strlen($date_from) > 0 && strlen($date_to) > 0)){
        $from_ts    = strtotime($date_from);
        $to_ts      = strtotime($date_to);
        $to_ts      = $to_ts+86400;
    
        $rows = sql($DBH, "SELECT * FROM tbl_locations
        where employee_id = ? && (date_time >= ? AND date_time <= ? )",
        array($employee_id,$from_ts,$to_ts), "rows");
    
        if((strlen($date_from) > 0 && strlen($date_to) > 0)){
          $filter_text = " $from_xml ".$date_from." $to_xml ".$date_to;
        }
        $filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>$clear_xml</a>";
    
    
        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
        where employee_id = ? && (date_time >= ? AND date_time <= ? )");
        $result  		= $STH->execute(array($employee_id,$from_ts,$to_ts));
        $count_total	= $STH->fetchColumn();
      }else{
        $date_from  = date("m/d/Y");
        $date_to    = date("m/d/Y");
        $from_ts    = strtotime($date_from);
        $to_ts      = strtotime($date_to);
    
        $from_ts    = $from_ts-86400;  //18-aug to (today is 19th)
        $to_ts      = $to_ts+86400;    //20-aug
    
        $rows = sql($DBH, "SELECT * FROM tbl_locations
        where employee_id = ?",
        array($employee_id), "rows");
    
        $filter_text = "$showing_last_hours_xml"; //$showing_all_records_xml
    
        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_locations
        where employee_id = ? && (date_time >= ? AND date_time <= ? )");
        $result  		= $STH->execute(array($employee_id,$from_ts,$to_ts));
        $count_total	= $STH->fetchColumn();
      }
          
      $locations        = array();      
      $sync_date_time   = "$never_xml";
      $previous_place   = array();
      $time_count       = 0;
      $i                = 0;
      
      foreach($rows as $row){        
        $sync_data			    = json_decode($row['data'],true);         
        $movements			    = $row['movements'];         
        $location_timestamp     = $row['date_time'];
        $sync_date_time		    = date("d-M-Y h:i A",$location_timestamp);
        $latitude               = $sync_data['coords']['latitude'];
        $longitude              = $sync_data['coords']['longitude'];         
        $accuracy               = $sync_data['coords']['accuracy'];
        $altitude               = $sync_data['coords']['altitude'];          
        $heading                = $sync_data['coords']['heading'];         
        $speed                  = round($sync_data['coords']['speed'],2);
        $altitudeAccuracy       = $sync_data['coords']['altitudeAccuracy'];
        $gps_timestamp          = $sync_data['timestamp'];
        
        if($movements == 0){
            $movements_show= "<i class='fa'><img src='../img/sitting.png'/></i> Sitting</br>";
        }else if($movements > 0 && $movements <= 10){
            $movements_show= "<i class='fa'><img src='../img/walking.png'/></i> Walking</br>";
        }else if($movements > 10){
            $movements_show= "<i class='fa'><img src='../img/running.png'/></i> Running</br>";
        } 
        
        if($i > 0){
            $distance = haversineGreatCircleDistance($previous_place['latitude'],$previous_place['longitude'],$latitude,$longitude);
            
            if($distance < 26){ //consider same place
                $time_count = $time_count + ($location_timestamp-$previous_place['start_time']); //seconds;
                $i--;
                $locations[$i]['id']                    = $row['id'];
                $locations[$i]['latitude']              = $latitude;
                $locations[$i]['longitude']             = $longitude;
                $locations[$i]['accuracy']              = $accuracy;
                $locations[$i]['altitude']              = $altitude;
                $locations[$i]['heading']               = $heading;
                $locations[$i]['speed']                 = $speed;                
                $locations[$i]['altitudeAccuracy']      = $altitudeAccuracy;  
                $locations[$i]["date_time"]             = $sync_date_time; 
                $locations[$i]['here_since']            = $time_count;
                $locations[$i]["distance"]              = $distance;
                
                
                //for map
                $locations[$i]["data"]       .= "<i class='fa fa-clock-o'></i> Date Time: ".$sync_date_time."</br>";
                $locations[$i]["data"]       .= "<i class='fa fa-street-view'></i> Accuracy: ".round($accuracy)."m</br>";
                $locations[$i]["data"]       .= $movements_show;    
                $locations[$i]["data"]       .= "<i class='fa fa-angle-up'></i> Altitude: $altitude m</br>";
                $locations[$i]["data"]       .= "<i class='fa fa-toggle-up'></i> Heading: $heading Â°N Clockwise</br>";
                $locations[$i]["data"]       .= "<i class='fa fa-car'></i> Speed: $speed m/s";
                //for map                
                
                $i++;  
            }else{                    
                $locations[$i]['id']                    = $row['id'];
                $locations[$i]['latitude']              = $latitude;
                $locations[$i]['longitude']             = $longitude;
                $locations[$i]['accuracy']              = $accuracy;
                $locations[$i]['altitude']              = $altitude;
                $locations[$i]['heading']               = $heading;
                $locations[$i]['speed']                 = $speed;                
                $locations[$i]['altitudeAccuracy']      = $altitudeAccuracy;  
                $locations[$i]["date_time"]             = $sync_date_time; 
                $time_count = 0;//place time counter 
                
                
                //for map
                $locations[$i]["data"]       .= "<i class='fa fa-clock-o'></i> Date Time: ".$sync_date_time."</br>";
                $locations[$i]["data"]       .= "<i class='fa fa-street-view'></i> Accuracy: ".round($accuracy)."m</br>";
                $locations[$i]["data"]       .= $movements_show;    
                $locations[$i]["data"]       .= "<i class='fa fa-angle-up'></i> Altitude: $altitude m</br>";
                $locations[$i]["data"]       .= "<i class='fa fa-toggle-up'></i> Heading: $heading Â°N Clockwise</br>";
                $locations[$i]["data"]       .= "<i class='fa fa-car'></i> Speed: $speed m/s";
                //for map
            
            
                $i++;//first ++ 
             }             
        }else{            
            $locations[$i]['id']                    = $row['id'];
            $locations[$i]['latitude']              = $latitude;
            $locations[$i]['longitude']             = $longitude;
            $locations[$i]['accuracy']              = $accuracy;
            $locations[$i]['altitude']              = $altitude;
            $locations[$i]['heading']               = $heading;
            $locations[$i]['speed']                 = $speed;                
            $locations[$i]['altitudeAccuracy']      = $altitudeAccuracy;  
            $locations[$i]["date_time"]             = $sync_date_time; 
            $time_count = 0;//place time counter 
            
            //for map
            $locations[$i]["data"]       .= "<i class='fa fa-clock-o'></i> Date Time: ".$sync_date_time."</br>";
            $locations[$i]["data"]       .= "<i class='fa fa-street-view'></i> Accuracy: ".round($accuracy)."m</br>";
            $locations[$i]["data"]       .= $movements_show;    
            $locations[$i]["data"]       .= "<i class='fa fa-angle-up'></i> Altitude: $altitude m</br>";
            $locations[$i]["data"]       .= "<i class='fa fa-toggle-up'></i> Heading: $heading Â°N Clockwise</br>";
            $locations[$i]["data"]       .= "<i class='fa fa-car'></i> Speed: $speed m/s";
            //for map
            
            
            $i++;//first ++          
        }        
        
        //save for next iteration
        $previous_place['start_time']   = $location_timestamp;
        $previous_place['latitude']     = $latitude;
        $previous_place['longitude']    = $longitude;        
      }
      if(count($count_total) == 0){
        echo "<br /><br /><h1 class='page-title text-center text-danger'>$no_locations_xml</h1>";
      }
      
      
      echo json_encode($locations);
?>