<?php
    header("location: index.php");
    exit;
    
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
    
    
    $block_websites_xml         = $xml->block_websites->users_block_websites;
    $add_url_xml                = $xml->block_websites->add_url;
    $last_synced_xml            = $xml->block_websites->last_synced;
    $never_synced_xml           = $xml->block_websites->never_synced;
    $entries_xml                = $xml->block_websites->entries;
    $search_colon_xml           = $xml->block_websites->search_colon;
    $url_xml                    = $xml->block_websites->url;
    $date_time_xml              = $xml->block_websites->date_time;
    $action_xml                 = $xml->block_websites->action;
    $delete_xml                 = $xml->block_websites->delete;
    $no_data_xml                = $xml->block_websites->no_data;
    $access_denied_xml          = $xml->block_websites->access_denied;
    $no_access_xml              = $xml->block_websites->no_access;
    $confirmation_xml           = $xml->block_websites->confirm;
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_block_websites'] != "true"){
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
    
    $STH           = $DBH->prepare("select count(*) FROM tbl_blocking_option where device_id =  ?");
	$result        = $STH->execute(array($SESS_DEVICE_ID));
	$count         = $STH->fetchColumn();    
    if($count == 0){
        sql($DBH,"INSERT INTO tbl_blocking_option (device_id) VALUES (?);",array($SESS_DEVICE_ID));
    }
    
    $rows = sql($DBH, "SELECT * FROM tbl_blocking_option where device_id = ?", array($SESS_DEVICE_ID), "rows");							
    foreach($rows as $row){
        $blocking_option = $row['blocking_option'];        
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
                                <div class="portlet box red">
								   <div class="portlet-title">
										<div class="caption">
											<i class="fa fa-ban"></i> <a style="color:#FFF;" href="profile.php?id=<?php echo $SESS_DEVICE_ID; ?>"><?php echo $SESS_DEVICE_NAME; ?></a><?php echo $block_websites_xml; ?>	
                                            <span id="blocking_update_time">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </span>                                            
                                        </div>
                                        <div class="actions">
											<a href="add_block_url.php" class="btn btn-default btn-sm btn-circle">
												<i class="fa fa-plus"></i> <?php echo $add_url_xml; ?>
											</a>
										</div>	
                                    </div>
									<div class="portlet-body">
										
                                        <label>Blocking Options: <span class="ajax_status"></span></label>
                                        
                                        <select class="form-control blocking_option" style="width: 400px;">
                                            <option <?php if($blocking_option == "disable"){echo "selected";} ?> value="disable">Disable Website Blocking (Allow all URLs)</option>
                                            <option <?php if($blocking_option == "whitelist"){echo "selected";} ?> value="whitelist">Whitelist (Block all except follow URLs)</option>
                                            <option <?php if($blocking_option == "blacklist"){echo "selected";} ?> value="blacklist">Blacklist (Allow all except follow URLs)</option>
                                        </select>
                                        <br />
                                        <div class="clearfix"></div>
                                        
                                        <table class="table table-striped table-bordered table-hover table-header-fixed" id="sample_2">
											<thead>
												<tr>
													<th>#</th>
													<th><?php echo $url_xml; ?></th>
													<th><?php echo $date_time_xml; ?></th>
													<th><?php echo $action_xml; ?></th>
												</tr>
											</thead>
											<tbody>
                                            
												<?php													
                                                    $rows = sql($DBH, "SELECT * FROM tbl_block_websites where device_id = ? order by(id) desc", 
                                                    array($SESS_DEVICE_ID), "rows");
                                                     
                                                    $sno = 0;												
													foreach($rows as $row){	
														$sno++;
														$id					= $row['id'];
                                                        $url                = $row['url'];
														$date_time			= my_simple_date($row['date_time']);													
														
														echo "<tr>
															<td>$sno</td>
															<td>$url</td>
                                                            <td>$date_time</td>
                                                            	<td>
															
																<a href='ajax/exe_del_block_url.php?id=$id' class='btn btn-danger btn-xs btn-circle del_url'>
																	<i class='fa fa-trash'></i> $delete_xml
																</a>
															
															
															
															</td>
															
														</tr>";
													}
												  
												?>
											</tbody>
										</table>
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
        
         <script>
            $(document).ready(function(){
                setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);
                
                $(".blocking_option").change(function(){
                    var blocking_option = $(this).val();
                    $(".ajax_status").html("<i class='fa fa-refresh fa-spin'></i> Saving..");
                    $.get("ajax/update_blocking_options.php?blocking_option="+blocking_option,function(data){
            			$(".ajax_status").html("<i class='fa fa-check'></i> Saved!");                        
            		});
                });
            
                $(".del_url").click(function(e){                   
                	var x = confirm("<?php echo $confirmation_xml; ?>");				
                	if(!x){
                		e.preventDefault();
                	}else{
                		e.preventDefault();   
                		var this_ = $(this);
                		$(this_).html("<i class='fa fa-refresh fa-spin'></i> <?php echo $delete_xml; ?>");
                		$.get($(this).attr("href"),function(data){
                			$(this_).parent().parent().addClass("deleting..");
                            $(this_).parent().parent().fadeOut();
                		});	
                    }				
                });
                
                
                function blocking_update_time(){
                    $.get("ajax/blocking_update_time.php",function(data){
                    $("#blocking_update_time").html(data);                        
                    setTimeout(function(){
                        blocking_update_time();
                    },3000);
                    });
                }   
                
                blocking_update_time();  
            
        
                        
               
            });
            
            
        </script>
		<!-- END THEME LAYOUT SCRIPTS -->        
    </body>

</html>