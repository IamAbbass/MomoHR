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



    $title_xml              = $xml->add_new_user->title;

    $name_xml               = $xml->add_new_user->name;

    $email_xml              = $xml->add_new_user->email;

    $phone_xml              = $xml->add_new_user->phone;

    $password_xml           = $xml->add_new_user->password;

    $cancel_xml             = $xml->add_new_user->cancel;

    $create_xml             = $xml->add_new_user->create;

    $access_denied_xml      = $xml->add_new_user->access_denied;

    $no_access_xml          = $xml->add_new_user->no_access;





	if($SESS_ACCESS_LEVEL == "root admin"){

		//allow

		if($_GET['pid']){

			$pid = $_GET['pid'];

			$rows 		= sql($DBH, "SELECT * FROM tbl_company where id = ? ", array($pid), "rows");

			foreach($rows as $row){

				$SESS_COMPANY_NAME	= $row['name'];

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

			.no_padding{

				padding:0;

			}

			select[name="country_code"]{

				border-right:none;

			}

			input[name="contact"]{

				border-left:none;

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



                        <h1 class="page-title hidden"> <i class="icon-user-follow"></i></h1>



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

                                <div class="portlet box yellow">

									<div class="portlet-title">

										<div class="caption">

											<i class='fa fa-plus'></i>

                                            <span>

                                                <?php echo "Add New Employee"; ?>

                    							<?php

                    								if($SESS_ACCESS_LEVEL == "root admin" && $pid){

                    									echo "<b> - ".$SESS_COMPANY_NAME."</b>";

                    								}

							                    ?>

                                            </span>

                                        </div>

									</div>

									<div class="portlet-body">



                                        <form role="form" method="post" action="exe_add_user.php">

                                            <div class="row">

												<?php

													if($pid){

														echo "<input required type='hidden' name='pid' value='$pid' />";

													}

												?>

												<div class="col-md-3">

													<div class="form-group">

														<label class="control-label"><?php echo $name_xml; ?> *</label>

														<input required type="text" name="fullname" class="form-control" />

													</div>

												</div>

												<div class="col-md-5">

													<div class="col-md-4 no_padding">

														<div class="form-group">

															<label class="control-label"><?php echo $phone_xml; ?> *</label>

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

													</div>

													<div class="col-md-8 no_padding">

														<div class="form-group">

															<label class="control-label">&nbsp;</label><br/>

															<input required type="text" name="contact" class="form-control" />

														</div>

													</div>

												</div>



												<div class="col-md-4">

													<div class="form-group">

														<label class="control-label"><?php echo $email_xml; ?> (for web access) </label>

														<input type="email" name="email" class="form-control" />

													</div>

												</div>



										        <div class="col-md-4">

													<div class="form-group">

														<label class="control-label"><?php echo $password_xml; ?> (for web access)</label>

														<input type="password" name="password" class="form-control" />

													</div>

												</div>



                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Birth Place: </label>

                                                        <input type="text" class="form-control" name="birth_place" />

                                                    </div>

                                                </div>



                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Date of birth:</label>

                                                        <input type="date" class="form-control date-picker" name="dob" />

                                                    </div>

                                                </div>



                                                <div class="row">

                                                    <div class="col-md-12">

                                                    <div class="col-md-4">

                                                        <div class="form-group form-md-radios">

                                                            <label>Gender: </label>

                                                            <div class="md-radio-inline">

                                                                <div class="md-radio">

                                                                    <input type="radio" id="checkbox2_8" name="gender" value="Male" class="md-radiobtn" />

                                                                    <label for="checkbox2_8">

                                                                        <span></span>

                                                                        <span class="check"></span>

                                                                        <span class="box"></span> Male</label>

                                                                </div>

                                                                <div class="md-radio">

                                                                    <input type="radio" id="checkbox2_9" name="gender" value="Female" class="md-radiobtn" />

                                                                    <label for="checkbox2_9">

                                                                        <span></span>

                                                                        <span class="check"></span>

                                                                        <span class="box"></span>Female</label>

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="form-group">

                                                            <label>Nationality: </label>

                                                            <input class="form-control" value="<?= $employee_profile_data['nationality'] ?>" name="nationality" />



                                                        </div>

                                                    </div>

                                                    <div class="col-md-4">

                                                        <div class="form-group">

                                                            <label>NRC: </label>

                                                            <input class="form-control" value="<?= $employee_profile_data['nrc'] ?>" name="nrc"  />

                                                        </div>

                                                    </div>

                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Religion: </label>

                                                        <input class="form-control"  name="religion" value="<?= $employee_profile_data['religion'] ?>"  />

                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Father's Name:</label>

                                                        <input class="form-control "  name="fathers_name" value="<?= $employee_profile_data['fathers_name'] ?>"  />

                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Mother's Name:</label>

                                                        <input class="form-control "  name="mothers_name"  value="<?= $employee_profile_data['mothers_name'] ?>"/>

                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Address:</label>

                                                        <input class="form-control"  value="<?= $employee_profile_data['address'] ?>"  name="address"  />





                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>City:</label>

                                                        <input class="form-control"  name="city" value="<?= $employee_profile_data['city'] ?>"  />





                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>State:</label>

                                                        <input class="form-control"  name="state" value="<?= $employee_profile_data['state'] ?>"  />





                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Work Phone: </label>

                                                        <input class="form-control"  name="work_phone"  value="<?= $employee_profile_data['work_phone'] ?>"/>





                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Personal Phone: </label>

                                                        <input class="form-control" name="personal_phone" value="<?= $employee_profile_data['personal_phone'] ?>"/>





                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Education:</label>

                                                        <input class="form-control" name='education' value="<?= $employee_profile_data['education'] ?>"/>





                                                    </div>

                                                </div>



                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Basic Salary:</label>

                                                        <input class="form-control" name="basic_salary" value="<?= $employee_profile_data['basic_salary'] ?>"/>





                                                    </div>

                                                </div>

                                                <div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Bonus:</label>

                                                        <input class="form-control "  name="bonus" value="<?= $employee_profile_data['bonus'] ?>"/>

                                                    </div>

                                                </div>

																								<div class="col-md-4">

                                                    <div class="form-group">

                                                        <label>Access Level:</label>

                                                        <select class="form-control "name="access_level">

																													<option value="user">Employee</option>

																													<option value="admin">Admin</option>

																												</select>

                                                    </div>

                                                </div>

                                                <div class="col-md-12">

                                                    <div class="form-group">

                                                        <label>Job Description:</label>

                                                        <textarea class="form-control" name="job_description"><?= $employee_profile_data['job_description'] ?></textarea>



                                                    </div>

                                                </div>





                                                <div class="col-md-12">

													<div class="form-group">

														<label>&nbsp;</label><Br>

														<button type="submit" class="btn green"><i class="fa fa-check"></i> <?php echo $create_xml; ?> </button>

														<a href="company.php" class="btn default"><i class="fa fa-arrow-left"></i> <?php echo $cancel_xml; ?> </a>

													</div>

												</div>

                                            </div>

                                        </form>







								    </div>

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

