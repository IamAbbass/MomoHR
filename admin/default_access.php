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
    
    $default_access      = $xml->default_access->default_access;
    $select_all          = $xml->default_access->select_all;
    $de_select_all       = $xml->default_access->de_select_all;
    $cancel              = $xml->default_access->cancel;
    $save                = $xml->default_access->save;
    $access_denied_xml   = $xml->default_access->access_denied_xml;
    $no_access_xml       = $xml->default_access->no_access_xml;
    
    
	
	
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
                        
                        
                        <h1 class="page-title"> <i class="fa fa-lock"></i> <?php echo $default_access; ?></h1>
                        
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
                            <form id="permission_form" role="form" name="access" method="post" action="exe_default_access.php">
                                
                                <input type="hidden" name="vdr_id" value="<?php echo $vendor_id; ?>" />
                                
                                <div class="col-md-12">                                
                                    <button type="button" class="btn btn-default btn-sm select_all"><i class='fa fa-check-square-o'></i><?php echo $select_all ?></button>
                                    <button type="button" class="btn btn-default btn-sm unselect_all"><i class='fa fa-square-o'></i> <?php echo $de_select_all ?></button>
                                    
                                    <button type="submit" class="after_update btn btn-success btn-sm pull-right"><i class='fa fa-check'></i> <?php echo $save ?></button>
                                    <a href="javascript:history.go(-1)" style="margin-right: 5px;" type="button" class="after_update btn btn-default btn-sm pull-right"><i class='fa fa-undo'></i> <?php echo $cancel ?></a>
                                </div>
                                
                                <div class="col-md-12">
                                    
                                            
                                        
                                            <table class="table table-striped table-bordered table-hover">
                                            <div class="md-checkbox-list">
                                            <?php
                                            $sno = 0;
                                            $rows = sql($DBH, "SELECT * FROM tbl_manage_access_default", array(), "rows");	
                                            
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



