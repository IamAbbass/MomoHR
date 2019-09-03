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
    
    
     $access_level_xml                = $xml->admin_access->access_level;
     $select_all_xml                  = $xml->admin_access->select_all;
     $de_select_all_xml               = $xml->admin_access->de_select_all;
     $schedule_access_level_xml       = $xml->admin_access->schedule_access_level;
     $save_xml                        = $xml->admin_access->save;
     $cancel_xml                      = $xml->admin_access->cancel;
     $schedule_access_level_for_xml   = $xml->admin_access->schedule_access_level_for;
     $new_trigger_xml                 = $xml->admin_access->new_trigger;
     $access_levels_xml               = $xml->admin_access->access_levels;
     $access_ctrl_xml                 = $xml->admin_access->access_ctrl;
     $select_action_xml               = $xml->admin_access->select_action;
     $allow_access_xml                = $xml->admin_access->allow_access;
     $block_access_xml                = $xml->admin_access->block_access;
     $trigger_date_time_colon_xml     = $xml->admin_access->trigger_date_time_colon;
     $trigger_date_time_xml           = $xml->admin_access->trigger_date_time;
     $create_xml                      = $xml->admin_access->create;
     $triggers_xml                    = $xml->admin_access->triggers;
     $action_xml                      = $xml->admin_access->action;
     $trigger_time_xml                = $xml->admin_access->trigger_time;
     $option_xml                      = $xml->admin_access->option;
     $allow_access_to_xml             = $xml->admin_access->allow_access_to;
     $block_access_to_xml             = $xml->admin_access->block_access_to;
     $back_xml                        = $xml->admin_access->back;
     $access_denied_xml               = $xml->admin_access->access_denied;
     $no_access_xml                   = $xml->admin_access->no_access;
     $colon_xml                       = $xml->admin_access->colon;
      
	
	
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
		redirect('index.php');
	}
	
	
    if($_GET['id'] && strlen($_GET['id']) > 0){        
        $id = $_GET['id'];
    }else{
        redirect("index.php");  
    }
    
    $STH        = $DBH->prepare("SELECT count(*) FROM tbl_login where access_level = ? AND id = ?");
    $result     = $STH->execute(array("admin",$id));
    $count      = $STH->fetchColumn();
    if($count == 0){
        redirect("index.php");  
    }
    
    $rows = sql($DBH, "SELECT * FROM tbl_login where access_level = ? AND id = ?", array("admin",$id), "rows");							
    foreach($rows as $row){	
        $admin_name       = $row['fullname'];
    }     
    
    //manage access
    $STH        = $DBH->prepare("SELECT count(*) FROM tbl_manage_access WHERE id = ?");
    $result     = $STH->execute(array($id));
    $count      = $STH->fetchColumn();
    if($count == 0){
        sql($DBH,"INSERT INTO tbl_manage_access (id) VALUES (?)",array($id));
		
		//set default
		$rows = sql($DBH, "SELECT * FROM tbl_manage_access_default", array(), "rows");	                                            
		$vendor_permissions = $rows[0];	
		foreach($vendor_permissions as $perm_code => $value){			
			sql($DBH,"UPDATE tbl_manage_access set $perm_code = ? where id = ?",array($value,$id));
		}
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
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/layouts/layout/css/themes/grey.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
		<!-- BEGIN PAGE LEVEL PLUGINS -->
		<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        
		
		
		
		
        <style>
            .profile_image{
                width:100%;
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
                        
                        
                        <h1 class="page-title"> <i class="fa fa-lock"></i>  <?php echo $access_level_xml; ?> <b><?php echo $admin_name; ?></b></h1>
                        
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
                            <form id="permission_form" role="form" name="access" method="post" action="exe_admin_access.php">
                                
                                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                                
                                <div class="col-md-12">                                
                                    <button type="button" class="btn btn-default btn-sm select_all"><i class='fa fa-check-square-o'></i> <?php echo $select_all_xml; ?></button>
                                    <button type="button" class="btn btn-default btn-sm unselect_all"><i class='fa fa-square-o'></i> <?php echo $de_select_all_xml; ?></button>
                                    
									<?php
                                        $STH        = $DBH->prepare("SELECT count(*) FROM tbl_schedule_access where admin_id = ?");
                                        $result     = $STH->execute(array($id));
                                        $count      = $STH->fetchColumn();
                                    ?>
									
									<a href='#schedule_perm_modal' 
									role='button' 
									data-toggle='modal'
									class='btn btn-primary btn-sm'>
									<i class='fa fa-clock-o'></i> <?php echo $schedule_access_level_xml; ?> <span class="s_count"><?php if($count > 0){echo "($count)";}else{echo "<i class='fa fa-plus'></i>";} ?></span>
									</a>
									
                                    <button type="submit" class="after_update btn btn-success btn-sm pull-right"><i class='fa fa-check'></i> <?php echo $save_xml; ?></button>
                                    <a href="javascript:history.go(-1)" style="margin-right: 5px;" type="button" class="after_update btn btn-default btn-sm pull-right"><i class='fa fa-undo'></i> <?php echo $cancel_xml; ?></a>
                                </div>
                                
                                <div class="col-md-12">                                            
                                        
                                    <table class="table table-striped table-bordered table-hover">
                                        <div class="md-checkbox-list">
                                        <?php
                                        $sno = 0;
                                        $rows = sql($DBH, "SELECT * FROM tbl_manage_access where id = ?", array($id), "rows");							
                                        
                                        
                                        $vendor_permissions = $rows[0];
                                        foreach($vendor_permissions as $perm_code => $value){
                                            $perm_name  = permission_name($array_perm,$array_perm_text,$perm_code);                                    
                                            if($perm_name != null){
                                                $perm_name = ucwords($perm_name);
                                                $sno++;
                                        
                                        
                                                if($sno%2 == 1){
                                                    echo "<tr>";
                                                }
                                        
                                        ?>
                                        
                                        
                                        
                                            <td class="middle">
                                                <div class="md-checkbox">
                                                    <input name="<?php echo $perm_code; ?>" value="true" <?php if($value == "true") echo "checked"; ?> type="checkbox" id="checkbox_<?php echo $sno; ?>" class="md-check access_check" />
                                                    <label for="checkbox_<?php echo $sno; ?>">
                                                        <span></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span> <?php echo $perm_name; ?> </label>
                                                </div>
                                            </td>                                       
                                        
                                        
                                        <?php
                                        
                                                if($sno%2 == 0){
                                                    echo "</tr>";
                                                }
                                            }                                    
                                        }
                                        ?>
                                        </div>
                                    </table>
                                            
                                        
                                    
                                </div> 
                            </form>                       
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


			<div id="schedule_perm_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div class="modal-dialog" style="width:90%">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title"><?php echo $schedule_access_level_for_xml; ?> <?php echo $admin_name ?></h4>
                        </div>
                        <div class="modal-body">                            
                            
							<div style="display: none;" class='note note-danger error_message'></div>
                            <div class="row">
							
                                <form id="exe_schdeule_trigger" action="exe_schdeule_trigger.php" method="post">
                                    <input name='id' value='<?php echo $id ?>' type='hidden' />
									<div class="form-group col-md-12">
									<h4 class='sbold' style='margin-top:0;'><?php echo $new_trigger_xml; ?></h4>
									</div>
									
									<div class="form-group col-md-9">
										<label><?php echo $access_levels_xml; ?> <small><?php echo $access_ctrl_xml; ?></small><?php echo $colon_xml; ?></a></label><Br>
										<select style="height: 200px;" required multiple class='form-control' name='perm[]'>
										<?php 										
											for($i=0; $i<count($array_perm); $i++){
												echo "<option value='".$array_perm[$i]."'>".ucwords($array_perm_text[$i])."</option>";
											}
										?>
										</select>
									</div>
									
									<div class="form-group col-md-3">
										<label><?php echo $select_action_xml; ?>:</label><Br>
										<label class='pointer'><input required name='new_value' type='radio' value='true'> <?php echo $allow_access_xml; ?> </label></Br>
										<label class='pointer'><input required name='new_value' type='radio' value='false'> <?php echo $block_access_xml; ?> </label>									
									   
                                       <br />
                                       <br />
                                       
                                       
       		                           <label><?php echo $trigger_date_time_colon_xml; ?></label><Br>
										<!--
										<div class='input-group date form_datetime' data-date='2012-12-21T15:25:00Z'>
										-->
										
											<input required="" name='trigger_time' placeholder="<?php echo $trigger_date_time_xml; ?>" class="form-control form-control-inline date-picker" size="16" type="text"  />
											
											
											<!--
											<input required name='trigger_time' placeholder="Trigger Date-Time" type='text' size='16' 
											class='form-control input-medium date-picker'>
											<span class='input-group-btn'>
												<button class='btn default date-reset btn-icon-only' type='button'>
													<i class='fa fa-times'></i>
												</button>
												<button class='btn default date-set btn-icon-only' type='button'>
													<i class='fa fa-calendar'></i>
												</button>
											</span>
											-->
										<!--
										</div>
										-->
									
										<label>&nbsp;</label><br />
										<button id="create" type='submit' class="btn blue"><i class="fa fa-check"></i> <?php echo $create_xml; ?></button>
									</div>
								</form>
								
									<div class="form-group col-md-12">
										<hr />
										<h4 class='sbold' style='margin-top:0;'><?php echo $triggers_xml; ?></h4>
									</div>
									
									<div class='form-group col-md-12'>
									<table class="table table-bordered table-striped table-hover">
										<tr>
											<th>#</th>
											<th><?php echo $action_xml; ?></th>
											<th><?php echo $trigger_time_xml; ?></th>
											<th><?php echo $option_xml; ?></th>
										</tr>
									<?php
										$rows = sql($DBH, "SELECT * FROM tbl_schedule_access where admin_id = ?", array($id), "rows");							
										$sno = 0;
										foreach($rows as $row)
										{	
											$sno++;
											$id             = $row['id'];
											$perm           = $row['perm'];
											$new_value      = $row['new_value'];
											$trigger_time   = date("D d M, Y",$row['trigger_time']);
                                            $perm_show      = explode(",",$perm);                                            
                                            
                                            $action = "";
                                            if($new_value == "true"){
                                                $action = "$allow_access_to_xml";
                                            }else{
                                                $action = "$block_access_to_xml";
                                            }
                                            
											echo "
												<tr>
													<td>$sno</td>
													<td>
                                                    $action
                                                    <ul>
                                                    ";                                                    
                                                    foreach($perm_show as $perm){
                                                        echo "<li>".ucwords((permission_name($array_perm,$array_perm_text,$perm)))."</li>";
                                                    }                                                    
                                                    echo "
                                                    </ul>
                                                    </td>
													<td>$trigger_time</td>
													<td><button s_id='$id' class='btn btn-danger btn-sm del-schedule'><i class='fa fa-times'></i></button></td>
												</tr>
											";
										}										
									?>
									</table>
									</div>

									
									
									
							</div>
						</div>                    
					
                        <div class="modal-footer">
                            <button class="btn default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i> <?php echo $back_xml; ?></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- END FOOTER -->
        </div>
        <!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<script src="assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <script src="assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
		
		<script src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/clockface/js/clockface.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
            $(document).ready(function(){
				
				$("#exe_schdeule_trigger").submit(function(){ 
					$("#create").html("<i class='fa fa-refresh fa-spin'></i> Creating.."); 
                });
                
                
                $(".del-schedule").click(function(){   
                    var this_   = $(this);
                    var id      = $(this_).attr("s_id");                    
                    
                    $(this_).parent().parent().addClass("tr-danger");
                    $(this_).parent().parent().delay(500).fadeOut();
                    
                    $.get("ajax/del_schedule.php?id="+id,function(data){
                        if($.trim(data) != "OK"){
                            alert("Can not delete schedule!");
                            $(this_).parent().parent().removeClass("tr-danger");
                            $(this_).parent().parent().show();              
                        }
                    });            
                }); 
                             
                setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);
                
                $(".select_all").click(function(){                   
                    $(".access_check").prop("checked",true);  
                    $(".after_update").slideDown();               
                });
               
                $(".unselect_all").click(function(){
                    $(".access_check").prop("checked",false);
                    $(".after_update").slideDown(); 
                });
               
               $(".access_check").click(function(){
                    $(".after_update").slideDown(); 
               });
               
            });
            
            
        </script>
    </body>

</html>