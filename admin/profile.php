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
    
    
    $your_profile               = $xml->profile->your_profile;
    $no_image                   = $xml->profile->no_image;
    $no_email                   = $xml->profile->no_emai;
    $click_on_image_to_change   = $xml->profile->click_on_image_to_change;
    $root_admin                 = $xml->profile->root_admin;
    $admin                      = $xml->profile->admin;
    $user                       = $xml->profile->user;
    $profile_account            = $xml->profile->profile_account;
    $personal_info              = $xml->profile->personal_info;
    $change_email               = $xml->profile->change_email;
    $change_pass                = $xml->profile->change_pass;
    $name_colon                 = $xml->profile->name_colon;
    $contact_colon              = $xml->profile->contact_colon;
    $address_colon              = $xml->profile->address_colon;
    $city_colon                 = $xml->profile->city_colon;
    $country_colon              = $xml->profile->country_colon;
    $save_changes               = $xml->profile->save_changes;
    $cancel                     = $xml->profile->cancel;
    $update_email               = $xml->profile->update_email;
    $current_pass_colon         = $xml->profile->current_pass_colon;
    $new_pass_colon             = $xml->profile->new_pass_colon;
    $current_pass_colon         = $xml->profile->current_pass_colon;
    $re_new_pass_colon          = $xml->profile->re_new_pass_colon;
    $update_password            = $xml->profile->update_password;
    $admins_profile             = $xml->profile->admins_profile;
    $access_denied              = $xml->profile->access_denied;
    $no_access                  = $xml->profile->no_access;
    
	
	$allow_view_3rd_profile = false;
	if($_GET['id']){		
		if($SESS_ACCESS_LEVEL == "root admin"){
			$allow_view_3rd_profile = true;
		}else if($SESS_ACCESS_LEVEL == "admin"){			
			$id = $_GET['id'];			
			$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ? and parent_id = ?;");
			$result  		= $STH->execute(array($id,$SESS_ID));
			$admins_user 	= $STH->fetchColumn();			
			if($admins_user == 1){
				$allow_view_3rd_profile = true;
			}else{
				$_SESSION['msg'] = "<strong>$access_denied </strong> $no_access";
				redirect('index.php');
			}
		}else if($SESS_ACCESS_LEVEL == "user"){
			$_SESSION['msg'] = "<strong>$access_denied </strong> $no_access";
			redirect('index.php');
		}else{
			$_SESSION['msg'] = "<strong>$access_denied </strong> $no_access";
			redirect('index.php');
		}		
	}else{
		//variables from session
	}
	
	if($allow_view_3rd_profile == true){
		$valid_3rd_id = $_GET['id'];
		$rows = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($valid_3rd_id), "rows");							
		foreach($rows as $row){
			$SESS_ID  					= $row['id'];
			$SESS_FULLNAME  		    = $row['fullname'];
			$SESS_EMAIL  	  	     	= $row['email'];
			$SESS_CRON_EMAIL            = $row['cron_email'];
			$SESS_CONTACT  		 	    = $row['contact'];
			$SESS_ADDRESS  		 	    = $row['address'];
			$SESS_USERNAME  		    = $row['username'];
			$SESS_PASSWORD  			= $row['password'];
			$SESS_PASS_TOKEN  			= $row['pass_token'];
			$SESS_PHOTO   	 			= $row['photo'];
			$SESS_CITY					= $row['city'];
			$SESS_COUNTRY   	 		= $row['country'];
			$SESS_REGISTRATION_DATE   	= $row['date_time'];
			$SESS_ACCESS_LEVEL   	 	= $row['access_level'];
			$SESS_HASH   	 	 		= $row['hash'];
			$SESS_STATUS   	 			= $row['status'];
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
                        
						<?php if($_GET['id'] != $SESS_ID){ ?>						
							<h1 class="page-title"> <i class="icon-user-following"></i> <?php $your_profile; ?>
								<small><?php echo $SESS_FULLNAME; ?></small>
							</h1>
						<?php }else{ ?>
							<h1 class="page-title"> <i class="icon-user-following"></i> <b><?php echo $SESS_FULLNAME ?></b><?php echo $admins_profile; ?></h1>							
						<?php } ?>
                        
                        
                        
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
                            <div class="col-md-3">
                                <!-- BEGIN PROFILE SIDEBAR -->
                                
                                <div class="profile-sidebar">
                                    <!-- PORTLET MAIN -->
                                    <div class="portlet light profile-sidebar-portlet ">
                                        
                                        <!-- SIDEBAR USER TITLE -->
                                        <div class="profile-usertitle">
                                            <form enctype='multipart/form-data' role="form" method="post" action="exe_profile.php?form=pic&id=<?php echo $valid_3rd_id; ?>">
												<img class="upload_helper profile_image" title="<?php echo $click_on_image_to_change; ?>" src="<?php echo $SESS_PHOTO; ?>" alt="<?php echo $no_image ?>" />
												<button type="button" class='upload_helper text-center btn btn-default btn-block'><small>(<?php echo $click_on_image_to_change; ?>)</small></button>
												<input type="file" name="pic" class="hidden" />
											</form>
											<h2 class="text-center"> <?php echo $SESS_FULLNAME; ?> <span class="badge badge-success"><i style="margin:0; width:none;" class="fa fa-tag"></i><?php echo ucfirst($SESS_ACCESS_LEVEL); ?></span></h2>
                                            
                                            <p class="text-center">
                                                <span><i class="fa fa-envelope"></i> 
                                                <?php
                                                    if(strlen($SESS_EMAIL) == 0){
                                                        echo "<span class='badge badge-default'>$no_email</span>";
                                                    }else{
                                                        echo $SESS_EMAIL;
                                                    }
                                                ?>
                                                </span><br />
                                                <span><i class="fa fa-phone"></i> <?php echo $SESS_CONTACT; ?></span>
                                            </p>
                                        </div>
										
                                        <!-- END MENU -->
                                    </div>
                                </div>
							</div>
                            <div class="col-md-9">
                            	
								
                            
								
                                <!-- END BEGIN PROFILE SIDEBAR -->
                                <!-- BEGIN PROFILE CONTENT -->
                                <div class="profile-content">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light ">
                                                <div class="portlet-title tabbable-line">
                                                    <div class="caption caption-md">
                                                        <i class="icon-globe theme-font hide"></i>
                                                        <span class="caption-subject font-blue-madison bold uppercase"><?php echo $profile_account; ?></span>
                                                    </div>
                                                    <ul class="nav nav-tabs">
                                                        <li class="active">
                                                            <a href="#tab_1_1" data-toggle="tab"><?php echo $personal_info;  ?></a>
                                                        </li>                                                        
                                                        <li>
                                                            <a href="#tab_1_2" data-toggle="tab"><?php echo $change_email ; ?></a>
                                                        </li>
                                                        <li>
                                                            <a href="#tab_1_3" data-toggle="tab"><?php echo $change_pass ; ?></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="tab-content">
                                                        <!-- PERSONAL INFO TAB -->
                                                        <div class="tab-pane active" id="tab_1_1">
                                                           <form role="form" method="post" action="exe_profile.php?form=profile&id=<?php echo $valid_3rd_id; ?>">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $name_colon; ?></label>
                                                                    <input required type="text" value="<?php echo $SESS_FULLNAME; ?>" name="name" class="form-control" /> </div>
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $contact_colon ?> </label>
                                                                    <input type="text" value="<?php echo $SESS_CONTACT; ?>" name="contact" class="form-control" /> </div>
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $address_colon; ?> </label>
                                                                    <textarea name="address" class="form-control"><?php echo $SESS_ADDRESS; ?></textarea> </div>     
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $city_colon; ?> </label>
                                                                    <input type="text" value="<?php echo $SESS_CITY; ?>" name="city" class="form-control" /> </div>
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $country_colon; ?> </label>
                                                                    <input type="text" value="<?php echo $SESS_COUNTRY; ?>" name="country" class="form-control" /> </div>
                                                                <div class="margiv-top-10">
                                                                    <button type="submit" class="btn btn-primary"><?php echo $save_changes; ?> </button>
                                                                    <a href="javascript:history.go(-1);" class="btn default"> <?php echo $cancel ?> </a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- END PERSONAL INFO TAB -->
                                                        <!-- CHANGE AVATAR TAB -->
                                                        
                                                        
                                                        
                                                        <div class="tab-pane" id="tab_1_2">
                                                            <form role="form" method="post" action="exe_profile.php?form=email&id=<?php echo $valid_3rd_id; ?>">
                                                                
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $email_colon; ?> </label>
                                                                    <input required type="email" value="<?php echo $SESS_EMAIL; ?>" name="email" class="form-control" /> </div>
                                                                
                                                                <div class="margiv-top-10">
                                                                    <button type="submit" class="btn btn-warning"> <?php echo $update_email ; ?> </button>
                                                                    <a href="javascript:history.go(-1);" class="btn default"> <?php echo $cancel; ?> </a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- END CHANGE AVATAR TAB -->
                                                        <!-- CHANGE PASSWORD TAB -->
                                                        <div class="tab-pane" id="tab_1_3">
                                                            <form role="form" method="post" action="exe_profile.php?form=password&id=<?php echo $valid_3rd_id; ?>">
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $current_pass_colon; ?></label>
                                                                    <input type="password" name="old_password" class="form-control" /> </div>
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $new_pass_colon; ?></label>
                                                                    <input type="password" name="new_password" class="form-control" /> </div>
                                                                <div class="form-group">
                                                                    <label class="control-label"><?php echo $re_new_pass_colon; ?></label>
                                                                    <input type="password" name="confirm_password" class="form-control" /> </div>
                                                                <div class="margin-top-10">
                                                                    <button type="submit" class="btn btn-success"> <?php echo $update_password; ?> </button>
                                                                    <a href="javascript:history.go(-1);" class="btn default"> <?php echo $cancel; ?> </a>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <!-- END CHANGE PASSWORD TAB -->
                                                    

												   </div>
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
