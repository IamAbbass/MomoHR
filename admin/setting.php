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
        
        .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
} }
.switchbtn input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: green;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
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
                       
                      
                                
                            //die(json_encode($_SESSION));
                            
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
									

									<div class="portlet-title">
										<div class="caption">
											<i class="fa fa-gear"></i> Data Collecting Schedule

                                        </div>
									</div>
									<div class="portlet-body">
										
										
                                          <?php  
                                            
                                            
                                            
                                            $emp_id = $_GET['id'];
                                           
                                           
                                            if($emp_id>0){
                                                
                                           
                                           
                                            $rows = sql($DBH, "SELECT * FROM tbl_login where id = ?",
                                                   array($emp_id), "rows");
                                                   foreach($rows as $row){
														$emp_name		   = $row['fullname'];
                                                        $emp_photo         = $row['photo'];
                                                        $emp_status        = $row['status'];
                                                        $company_id    = $row['company_id'];
                                                        
													}
                                                $roww = sql($DBH, "SELECT * FROM tbl_company where id = ?",
                                                   array($company_id), "rows"); 
                                                  foreach($roww as $row){ 
                                                    $emp_company_id= $row['id'];
                                                   $emp_company_name = $row['name'];
                                                    
                                                    }
                                             
                                                echo "<div class='col-lg-12 col-md-12 col-xs-12 col-sm-12' style='text-align:center'> 
                                                
                                                    <img src=".$emp_photo." style='width:15%' class='img-circle'/>
                                                </div>
                                                <div class='col-lg-12 col-md-12 col-xs-12 col-sm-12' style='text-align:center'>
              
                                                            <h2 class='text-uppercase'><strong>".$emp_name."</strong></h2>
                   
                                                </div>";                                 
                                            
                                            }
                                  
      
												?>
                                        <form action="exe_setting.php" method="post" role="form" enctype="multipart/form-data">
                                        <table class="table table-bordered">
                                            
                                              <?php  
                                                
                                          
                                                                      
                                                if($emp_id>0){
                                                    
                                                      $set_app = sql($DBH,"select * from tbl_emp_app_setting where emp_id=?",array($emp_id),"rows");
                                                            foreach($set_app as $row){
                                                                $set_app_status =$row['status'];
                                                                $set_app_mon =$row['monday'];
                                                                $set_app_tues =$row['tuesday'];
                                                                $set_app_wed =$row['wednesday'];
                                                                $set_app_thur =$row['thursday'];
                                                                $set_app_fri =$row['friday'];
                                                                $set_app_sat =$row['saturday'];
                                                                $set_app_sun =$row['sunday'];
                                                                $set_app_start =$row['start_day'];
                                                                $set_app_end =$row['end_day'];  
                                      
                                                              }
                                                              
                                                               if ($set_app == null){
                                                               
                                                                
                                                                    // die(json_encode($set_app));
                                                $company_id = $_SESSION['SESS_COMPANY_ID'];
                                               $set_app = sql($DBH,"select * from tbl_app_setting where company_id=?",array($company_id),"rows");
                                                            foreach($set_app as $row){
                                                                $set_app_status =$row['status'];
                                                                $set_app_mon =$row['monday'];
                                                                $set_app_tues =$row['tuesday'];
                                                                $set_app_wed =$row['wednesday'];
                                                                $set_app_thur =$row['thursday'];
                                                                $set_app_fri =$row['friday'];
                                                                $set_app_sat =$row['saturday'];
                                                                $set_app_sun =$row['sunday'];
                                                                $set_app_start =$row['day_start'];
                                                                $set_app_end =$row['day_end'];
                                                                
                                                            }
                                                                     
                                                                     
                                                              }
                                                   }
                                               
                                               
                                                else{
                                                    
                                                         $company_id = $_SESSION['SESS_COMPANY_ID'];
                                               $set_app = sql($DBH,"select * from tbl_app_setting where company_id=?",array($company_id),"rows");
                                                            foreach($set_app as $row){
                                                                $set_app_status =$row['status'];
                                                                $set_app_mon =$row['monday'];
                                                                $set_app_tues =$row['tuesday'];
                                                                $set_app_wed =$row['wednesday'];
                                                                $set_app_thur =$row['thursday'];
                                                                $set_app_fri =$row['friday'];
                                                                $set_app_sat =$row['saturday'];
                                                                $set_app_sun =$row['sunday'];
                                                                $set_app_start =$row['day_start'];
                                                                $set_app_end =$row['day_end'];
                                                                
                                                            }
                                                }
                                                
                                                      
                                                  
                                                 
                                                              
                                                
                                           ?>
                                            
                                            <tr>
                                                <td>
                                                <input type="hidden" name="emp_id" value= " <?php echo $emp_id ;?> "/>
                                                <input type="hidden" name="emp_company_id" value=" <?php echo $emp_company_id; ?> " />
                                                <label> Data Collecting Schedule:</label>
                                                </td>
                                                
                                                <td> 
                                                    <label><input type="radio" name="status" value="true" <?php if($set_app_status=='true'){echo "checked";}?>/> Enable</label>
                                                    <label><input type="radio" name="status" value="false" <?php if($set_app_status=='false'){echo "checked";}?>/> Disable</label>
                                                      
                                                      
                                                                                                            
                                                </td>
                                            </tr>
                                         
                                            <tr>
                                                <td><label>Days:</label></td>
                                                <td>  
                                                    
                                                        <label class="checkbox-inline">
                                                          <input type="checkbox" name="mon" <?php if($set_app_mon=='on'){echo "checked='ture'";}?> />Monday
                                                        </label>
                                                        <label class="checkbox-inline">
                                                          <input type="checkbox" name="tues" <?php if($set_app_tues=='on'){echo "checked='ture'";}?> />Tuesday
                                                        </label>
                                                        <label class="checkbox-inline">
                                                          <input type="checkbox" name="wed" <?php if($set_app_wed=='on'){echo "checked='ture'";}?> />Wednesday
                                                        </label>
                                                         <label class="checkbox-inline">
                                                          <input type="checkbox" name="thur" <?php if($set_app_thur=='on'){echo "checked='ture'";}?>  />Thursday
                                                        </label>
                                                        <label class="checkbox-inline">
                                                          <input type="checkbox" name="fri" <?php if($set_app_fri=='on'){echo "checked='ture'";}?> />Friday
                                                        </label>
                                                        <label class="checkbox-inline">
                                                          <input type="checkbox" name="sat" <?php if($set_app_sat=='on'){echo "checked='ture'";}?> />Saturday
                                                        </label>
                                                        <label class="checkbox-inline">
                                                          <input type="checkbox" name="sun" <?php if($set_app_sun=='on'){echo "checked='ture'";}?> />Sunday
                                                        </label>
                                                      
                                                     
                                                </td>
                                            </tr>
                                           
                                            
                                            <tr>
                                                <td>
                                                 <label>Day Working Hours:</label>
                                                </td>
                                                <td>                                                 
                                                   <div>
                                                        <input type='time' name="start_day" <?php if($set_app_start!='on'){echo "value='".$set_app_start."'";}?> />
                                                             <label> To:</label> 
                                                         <input type='time' name='end_day' <?php if($set_app_end!='on'){echo "value='".$set_app_end."'";}?> />
                                                                                                                                                                                                                                          
                                                   </div>                                                                                                                                                                              
                                                
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                             <td colspan="2" class="text-right">
                                             
                                                <button value="submit" type="submit" name="set_timming" class="btn btn-primary">Update Schedule</button>
                                                
  
                                           </td>
                                            </tr>
                                     
                                        </table>
                                            </form>   
                                           
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
				$(".upload_helper").click(function(){
					$(this).siblings("input[name='pic']").click();
				});
				$("input[name='pic']").change(function(){
					$(this).parent().submit();
				});
				
				setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);
			});
		</script>
        
    </body>

</html>
