<?php
    require_once('../class_function/session.php');
  	require_once('../class_function/error.php');
  	require_once('../class_function/dbconfig.php');
  	require_once('../class_function/function.php');
  	require_once('../class_function/validate.php');
  	require_once('../class_function/language.php');
    
            ini_set('precision',100);

            $employee_id = $_GET['id'];
            $rows = sql($DBH, "SELECT * FROM tbl_locations
            where employee_id = ?",
            array($employee_id), "rows");
            
            $locations        = array();
            $sync_date_time   = "$never_xml";
            $previous_place   = array();
            $time_count       = 0;
            $i                = 0;            
            
            foreach($rows as $row){
                //$sync_data			    = json_decode($row['data']);
                $sync_data              = json_decode($row['data'], true);  
                
                
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
                                
                //if($i > 0){
                    
                    //$distance = haversineGreatCircleDistance($previous_place['latitude'],$previous_place['longitude'],$latitude,$longitude);
                    
                    //echo "$distance < $accuracy <br/>";
                    
                    /*if($distance < $accuracy){ //consider same place
                    
                        $time_count = secondsToTime($time_count + ($location_timestamp-$previous_place['start_time'])); //seconds;
                        
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
                        $locations[$i]['here_since']            = $time_count; //time_elapsed_string
                        $locations[$i]["distance"]              = $distance;
                        
                        
                        //for map
                        $i++;
                    
                    }else{
                        */
                        $locations[$i]['id']                    = $row['id'];
                        $locations[$i]['latitude']              = $latitude;
                        $locations[$i]['longitude']             = $longitude;
                        $locations[$i]['accuracy']              = $accuracy;
                        $locations[$i]['altitude']              = $altitude;
                        $locations[$i]['heading']               = $heading;
                        $locations[$i]['speed']                 = $speed;
                        $locations[$i]['altitudeAccuracy']      = $altitudeAccuracy;
                        $locations[$i]["date_time"]             = $sync_date_time;
                        //$time_count = 0;//place time counter
                        
                        $i++;
                    
                    //}
                //}else{
                    
                  /*
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
                  */  
                    
                    //$i++;                                            
                    
                //}
                
                
                
                //save for next iteration
                $previous_place['start_time']   = $location_timestamp;
                $previous_place['latitude']     = $latitude;
                $previous_place['longitude']    = $longitude;
                
            }
            
            echo json_encode($locations);
            exit;

?>