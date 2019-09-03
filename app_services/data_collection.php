<?php
	header("Access-Control-Allow-Origin: *");
    

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
    
    
    $id = $_GET['id'];
    
    //kia is employee ka alaga se time define hai ?
      $ch_emp_data = sql($DBH,"select * from tbl_emp_app_setting where emp_id = ?",array($id),"rows");
    
            if($ch_emp_data!=null){
            
              foreach($ch_emp_data as $row){
                       $ch_app_status = $row['status'];
                       $ch_app_mon =  $row['monday'];
                       $ch_app_tues =  $row['tuesday'];
                       $ch_app_wed =  $row['wednesday'];
                       $ch_app_thur =  $row['thursday'];
                       $ch_app_fri =  $row['friday'];
                       $ch_app_sat =  $row['saturday'];
                       $ch_app_sun =  $row['sunday'];
                       $ch_app_start =  $row['start_day'];
                       $ch_app_end =  $row['end_day'];
                }
                /* */
                
                        //die(json_encode($ch_emp_data));
              
                if($ch_app_status == 'true'){
                    
                    $day = date("l");
                    $time = date("H:i");
                    
                                      
    
                        if($day == 'Monday'){
                            if($ch_app_mon == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Tuesday'){
                            if($ch_app_tues == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Wednesday'){
                            if($ch_app_wed == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                        if($day == 'Thursday'){
                            if($ch_app_thur == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Friday'){
                            if($ch_app_fri == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Saturday'){
                            if($ch_app_sat == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Sunday'){
                            if($ch_app_sun == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         
                         
                    }
               else{
                          echo "<h2> Sorry !!! Your Empolyee is Disbale</h2>";
                    }
                
               
            }
            
             if($ch_emp_data == null){
                  $get_company_id = sql($DBH,"select * from tbl_login where id = ?",array($id),"rows");
                  foreach ($get_company_id as $row){
                     $company_id = $row['company_id'];
                  }
             
                  $ch_emp_data = sql($DBH,"select * from tbl_app_setting where company_id = ?",array($company_id),
                  "rows");
                  foreach($ch_emp_data as $row){
                       $ch_app_status = $row['status'];
                       $ch_app_mon =  $row['monday'];
                       $ch_app_tues =  $row['tuesday'];
                       $ch_app_wed =  $row['wednesday'];
                       $ch_app_thur =  $row['thursday'];
                       $ch_app_fri =  $row['friday'];
                       $ch_app_sat =  $row['saturday'];
                       $ch_app_sun =  $row['sunday'];
                       $ch_app_start =  $row['day_start'];
                       $ch_app_end =  $row['day_end'];
                    
                  }
                  
                //  showdata(array($ch_emp_data));
              
                        
                  if($ch_app_status == 'true'){
                    
                    $day = date("l");
                    $time = date("H:i");
                                        
    
                        if($day == 'Monday'){
                            if($ch_app_mon == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Tuesday'){
                            if($ch_app_tues == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Wednesday'){
                            if($ch_app_wed == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                        if($day == 'Thursday'){
                            if($ch_app_thur == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Friday'){
                            if($ch_app_fri == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Saturday'){
                            if($ch_app_sat == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         if($day == 'Sunday'){
                            if($ch_app_sun == 'on'){
                                
                              if($ch_app_start <= $time and $ch_app_end >= $time){
                                
                                echo "<h1>Collect Data of ".$day."</h1>";
                              }
                            }
                         }
                         
                         
                    }
                    else{
                          echo "<h2> Sorry !!! Your Company is Disbale</h2>";
                      }
            }
    

function showdata($ca){
    echo "pinasdadasdasdasdasdsf <br />";
     die(json_encode($ca));
     
     
     
     
     
}
        
        
     
   
    
    
?>