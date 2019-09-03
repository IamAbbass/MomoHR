<?php    
	require_once('class_function/session.php');
	require_once('class_function/error.php');    
	require_once('class_function/function.php');    
	require_once('class_function/dbconfig.php'); 
	require_once('class_function/language.php');
	require_once('page/title.php');
	require_once('page/meta.php');
	require_once('page/footer.php');
    
    if($_SESSION['msg']){        
		$error = "<div class='note note-danger remove_after_5'><p>".$_SESSION['msg']."</p></div>"; 	
        unset($_SESSION['msg']);
    }else if($_SESSION['info']){        
		$error = "<div class='note note-success remove_after_5'><p>".$_SESSION['info']."</p></div>"; 	
        unset($_SESSION['info']);
    }else{
		$error = "";  
	}
    
?>
<!DOCTYPE html>
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>
<?php
	echo $title;
	echo $favicon;
	echo $meta;
?>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="admin/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="admin/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="admin/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="admin/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="admin/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="admin/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="admin/assets/global/css/components-rounded.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="admin/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="admin/assets/pages/css/login-3.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        
        <style>
            /*
			for login-4.min.css
			.white{
				color: #fff !important;
			}
			.bold{
				font-weight:bold !important;
			}			
			.has-error .checkbox, .has-error .checkbox-inline, .has-error .control-label, .has-error .form-control-feedback, .has-error .help-block, .has-error .radio, .has-error .radio-inline, .has-error.checkbox label, .has-error.checkbox-inline label, .has-error.radio label, .has-error.radio-inline label {
				color: #fff !important;
				background: #e73d4a !important;
				padding: 5px !important;
				border-radius: 5px !important;
			}*/
			
			.content{
				margin-top: 50px !important;
			}
        </style>
        
    </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN LOGO 
        <div class="logo">
            <a href="index.php">&nbsp;</a>
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
             <form action="fb_login.php" method="post" class="hidden"> 
                <input required type="hidden" value="" name="u_id" id="fb_id" />
            </form>
                
            
            <form class="login-form" action="signin.php" method="post">
                <img style="display: block; margin:0 auto; width:100px;" src="img/logo-lg.png" alt="" />
                <h3 style="margin: 0 0 10px 0;" class="form-title text-center"><?php echo $website_title; ?></h3>				
                <hr style="margin: 0 0 10px 0;"/>
				<br />                
				<?php echo $error ?>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span><?php echo $xml->empty_login->err_msg; ?></span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support php5 placeholder, so we just show field title for that-->
                    <label class="control-label"><?php echo $xml->login_screen->email_or_phone_colon; ?> </label>
                    <div class="input-icon">
                        <i class="fa fa-user"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo $xml->login_screen->email_or_phone; ?>" name="username" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo $xml->login_screen->password_colon; ?></label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="<?php echo $xml->login_screen->password; ?>" name="password" /> </div>
                </div>
                <div class=""> <!-- form-actions -->
                    <label class="rememberme mt-checkbox mt-checkbox-outline hidden">
                        <input type="checkbox" name="remember" value="1" /><?php echo $xml->login_screen->remember_me; ?>
                        <span></span>
                    </label>
					<a href="javascript:;" id="forget-password" class="white bold"><?php echo $xml->login_screen->forgot_password; ?> </a>
					
                    <input type="submit" name="signin" class="btn green pull-right" value="<?php echo $xml->login_screen->login; ?>" />
                </div>
                
				<hr />
				
                <div class="white"> <!-- forget-password -->
                    <p><?php echo $xml->login_screen->dont_have_an_account; ?>&nbsp;
                        <a href="javascript:;" id="register-btn" class="white bold"><?php echo $xml->login_screen->create_account; ?></a>
                    </p>
                </div>
                
				
            </form>
            <!-- END LOGIN FORM -->
            <!-- BEGIN FORGOT PASSWORD FORM -->
            <form class="forget-form" action="forget.php" method="post">
                <img style="display: block; margin:0 auto; width:100px;" src="img/logo-lg.png" alt="" />
                <h3 style="margin: 0 0 10px 0;" class="form-title text-center"><?php echo $xml->reset_password_screen->title; ?></h3>				
                <hr style="margin: 0 0 10px 0;"/>
				
                <p><?php echo $xml->reset_password_screen->help; ?> </p>
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo $xml->reset_password_screen->email; ?>" name="email" /> </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn grey-salsa btn-outline"> <?php echo $xml->reset_password_screen->back; ?> </button>
                    <input type="submit" name="forget" class="btn green pull-right" value="<?php echo $xml->reset_password_screen->reset; ?>" />
                </div>
            </form>
            <!-- END FORGOT PASSWORD FORM -->
            <!-- BEGIN REGISTRATION FORM -->
            <form class="register-form" action="signup.php" method="post">
                <img style="display: block; margin:0 auto; width:100px;" src="img/logo-lg.png" alt="" />
                <h3 style="margin: 0 0 10px 0;" class="form-title text-center"><?php echo $xml->signup_screen->title; ?> </h3>				
                <hr style="margin: 0 0 10px 0;"/>
				<br />  
				
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?php echo $xml->signup_screen->full_name; ?></label>
                    <div class="input-icon">
                        <i class="fa fa-font"></i>
                        <input required class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo $xml->signup_screen->full_name; ?>" name="fullname" /> </div>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support php5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9"><?php echo $xml->signup_screen->email; ?></label>
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input required class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo $xml->signup_screen->email; ?>" name="email" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?php echo $xml->signup_screen->phone; ?></label>
                    <div class="input-icon">
                        <i class="fa fa-globe"></i>
                        <select class="form-control" name="country_code">
							<option value="">Country Code</option>
							<?php
								$rows = sql($DBH, "SELECT * FROM country", array(), "rows");		
								foreach($rows as $row){	
									$name			= $row['nicename'];
									$phonecode		= $row['phonecode'];
									if($phonecode == "95"){ //default myanmar
										echo "<option selected value='$phonecode'>$name ($phonecode)</option>";
									}else{
										echo "<option value='$phonecode'>$name ($phonecode)</option>";
									}																							
								}	
							?>
						</select>                        
                    </div>
                    <div class="input-icon">
                        <i class="fa fa-phone"></i>
                        <i class="fa fa-phone"></i>                       
                        <input required class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="<?php echo $xml->signup_screen->phone; ?>" name="contact" />
                    </div>
                </div> 
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Date of birth</label>
                    <div class="input-icon">
                        <i class="fa fa-calendar"></i>
                        <input required class="form-control placeholder-no-fix" type="date" autocomplete="off" placeholder="Date of birth" name="dob" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Company Name</label>
                    <div class="input-icon">
                        <i class="fa fa-briefcase"></i>
                        <input required class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Company Name" name="company_name" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?php echo $xml->signup_screen->password; ?></label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input required class="form-control placeholder-no-fix" type="password" autocomplete="off" autocomplete="off" id="register_password" placeholder="<?php echo $xml->signup_screen->password; ?>" name="password" /> </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9"><?php echo $xml->signup_screen->re_password; ?></label>
                    <div class="controls">
                        <div class="input-icon">
                            <i class="fa fa-lock"></i>
                            <input required class="form-control placeholder-no-fix" type="password" autocomplete="off" autocomplete="off" placeholder="<?php echo $xml->signup_screen->re_password; ?>" name="rpassword" /> </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="mt-checkbox mt-checkbox-outline">
                        <input type="checkbox" name="tnc" /> <?php echo $xml->signup_screen->i_agree; ?>
                        <a href="javascript:;" class="white bold"><?php echo $xml->signup_screen->terms; ?> </a> <?php echo $xml->signup_screen->and; ?>
                        <a href="javascript:;" class="white bold"><?php echo $xml->signup_screen->policy; ?> </a>
                        <span></span>
                    </label>
                    <div id="register_tnc_error"> </div>
                </div>
                <div class="form-actions">
                    <button id="register-back-btn" type="button" class="btn grey-salsa btn-outline"> <?php echo $xml->signup_screen->back; ?> </button>
                    <input type="submit" name="signup" id="register-submit-btn" class="btn green pull-right" value="<?php echo $xml->signup_screen->signup; ?>" />
                </div>
            </form>
            <!-- END REGISTRATION FORM -->
        </div>
        <!-- END LOGIN -->
        <!--[if lt IE 9]>
<script src="admin/assets/global/plugins/respond.min.js"></script>
<script src="admin/assets/global/plugins/excanvas.min.js"></script> 
<script src="admin/assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="admin/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="admin/assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="admin/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="admin/assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="admin/assets/pages/scripts/login.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
        
        <script>
            $(document).ready(function(){
                
                
                setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);
            });
            
            
        </script>
    </body>
</html>



