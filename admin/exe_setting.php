<?php
    require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
	require_once('../class_function/validate.php');
	require_once('../class_function/language.php');
    
                                   
                    $emp_id = $_POST['emp_id'];
                    $emp_company_id = $_POST['emp_company_id'];
                    $status= $_POST['status'];
                    $mon= $_POST['mon'];
                    $tues= $_POST['tues'];
                    $wed= $_POST['wed'];
                    $thur= $_POST['thur'];
                    $fri= $_POST['fri'];
                    $sat= $_POST['sat'];
                    $sun= $_POST['sun'];
                    $start_day= $_POST['start_day'];
                    $end_day= $_POST['end_day'];            
                                   
                                   
                                   
                                   
                        // die(json_encode($status));            
                                                  
                                     
                       if($emp_id>0){
                                 
                                 $check_emp = sql($DBH,"select * from tbl_emp_app_setting where emp_id=?",array($emp_id),"rows");
                                foreach($check_emp as $row){
                                    $ch_emp_id = $row[emp_id];
                                }
                              
                                if($ch_emp_id != 0 ){
                                     
                                      sql($DBH,"update tbl_emp_app_setting set status=?,monday=?,tuesday=?,wednesday=?,thursday=?,friday=?,saturday=?,sunday=?,start_day=?,end_day=? where emp_id=?",
                                      array($status,$mon,$tues,$wed,$thur,$fri,$sat,$sun,$start_day,$end_day,$ch_emp_id),"rows");
                                      
                                }          
                            else{        
                        sql($DBH, "insert into tbl_emp_app_setting (emp_id,company_id,status,monday,tuesday,wednesday,thursday,friday,saturday,sunday,start_day,end_day)
                        values (?,?,?,?,?,?,?,?,?,?,?,?)",
                           array($emp_id,$emp_company_id,$status,$mon,$tues,$wed,$thur,$fri,$sat,$sun,$start_day,$end_day), "rows");
                    
                                }
                                
                            $_SESSION['info'] = "Employee Schedule Has Been Updated";
                             redirect("company.php");
    
                      }
                                         
                                         
                                         
                                         
                   else{
                
                    $emp_company_id = $_SESSION['SESS_COMPANY_ID'];
                   
                        $check = sql($DBH,"select * from tbl_app_setting where company_id=?",array($emp_company_id),"rows");
                                foreach($check as $row){
                                    $ch_company_id = $row[company_id];
                                }
                     
                        if ($ch_company_id != 0){
                             sql($DBH,"update tbl_app_setting set status=?,monday=?,tuesday=?,wednesday=?,thursday=?,friday=?,saturday=?,sunday=?,day_start=?,day_end=? where company_id = ?",
                              array($status,$mon,$tues,$wed,$thur,$fri,$sat,$sun,$start_day,$end_day,$ch_company_id),"rows");
                        
            
                        
                        }
                   else{
                             sql($DBH, "insert into tbl_app_setting (company_id,status,monday,tuesday,wednesday,thursday,friday,saturday,sunday,day_start,day_end)
                              values (?,?,?,?,?,?,?,?,?,?,?)",
                             array($emp_company_id,$status,$mon,$tues,$wed,$thur,$fri,$sat,$sun,$start_day,$end_day), "rows");
                        
                         }   
                         
                         $_SESSION['info'] = "Company Schedule Has Been Updated";                    
                        redirect("setting.php");             
                             }
                                          
                                
                                          
                                     
                                  
                                       







?>