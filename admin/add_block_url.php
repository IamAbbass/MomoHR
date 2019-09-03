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
    
    
    
    $title_xml           = $xml->add_block_url->title;
    $url_xml             = $xml->add_block_url->url;
    $add_xml             = $xml->add_block_url->add;
    $cancel_xml          = $xml->add_block_url->cancel;
    $access_denied_xml   = $xml->add_block_url->access_denied;
    $no_access_xml       = $xml->add_block_url->no_access;
    
    
    
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
		if($_GET['pid']){
			$pid = $_GET['pid'];
			$rows 		= sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($pid), "rows");		
			foreach($rows as $row){	
				$admin_name	= $row['fullname'];
			}			
		}
	}else if($SESS_ACCESS_LEVEL == "admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "user"){
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
        redirect('index.php');
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
                        
                        
                        <h1 class="page-title"> <i class="icon-user-follow"></i> <?php echo $title_xml; ?>
						
							<?php
								if($SESS_ACCESS_LEVEL == "root admin" && $pid){
									echo "<b> - ".$admin_name."</b>";		
								}
							?>
						
						</h1>
                        
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
                            <div class="col-md-12">
                            	
								
                            
								
                                <!-- END BEGIN PROFILE SIDEBAR -->
                                <!-- BEGIN PROFILE CONTENT -->
                                <div class="profile-content">
                                    <div class="row">
                                            <div class="portlet light ">                                                
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- PERSONAL INFO TAB -->
                                                           <form role="form" method="post" action="exe_add_block_url.php">
                                                                <div class="row">
																	
																	<?php
																	
																		if($pid){
																			echo "<input required type='hidden' name='pid' value='$pid' />";
																		}
																	
																	?>
																	
																	<div class="col-md-9">
																		<div class="form-group">
																			<label class="control-label"><?php echo $url_xml; ?> *</label>
																			<input required type="text" name="url" class="form-control" />
																		</div>
																	</div>
																	
																
																	
																
																	
																	<div class="col-md-3">
																		<div class="form-group">
																			<label>&nbsp;</label><Br>
																			<button type="submit" class="btn btn-primary"> <i class='fa fa-check'></i> <?php echo $add_xml; ?> </button>
																			<a href="block_websites.php" class="btn default"> <?php echo $cancel_xml; ?> </a>
																		</div>
																	</div>
																</div>
															</form>                                               
                                                        

												   </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                
                                </div>
                                <!-- END PROFILE CONTENT -->
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
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
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
