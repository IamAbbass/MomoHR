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

    $showing_all_records_xml       = $xml->my_users->showing_all_records;
    $search_xml                    = $xml->my_users->search;
    $filter_xml                    = $xml->my_users->filter;
    $users_xml                     = $xml->my_users->users;
    $add_user_xml                  = $xml->my_users->add_user;
    $entries_xml                   = $xml->my_users->entries;
    $search_colon_xml              = $xml->my_users->search_colon;
    $name_xml                      = $xml->my_users->name;
    $email_xml                     = $xml->my_users->email;
    $contact_xml                   = $xml->my_users->contact;
    $registration_date_xml         = $xml->my_users->registration_date;
    $status_xml                    = $xml->my_users->status;
    $actions_xml                   = $xml->my_users->action;
    $active_xml                    = $xml->my_users->active;
    $inactive_xml                  = $xml->my_users->inactive;
    $edit_profile_xml              = $xml->my_users->edit_profile;
    $block_unblock_xml             = $xml->my_users->block_unblock;
    $invalid_id_xml                = $xml->my_users->invalid_id;
    $no_account_xml                = $xml->my_users->no_account;
    $from_xml                      = $xml->my_users->from;
    $to_xml                        = $xml->my_users->to;
    $clear_filter_xml              = $xml->my_users->clear_filter;
    $access_denied_xml             = $xml->my_users->access_denied;
    $no_access_xml                 = $xml->my_users->no_access;
    $search_colon_xml              = $xml->my_users->search_colon;
    $updated_colon_xml             = $xml->my_users->updated_colon;
    $no_email_xml                  = $xml->my_users->no_email;
    $delete_user_xml               = $xml->my_users->delete_user;
    $confirm_del_xml               = $xml->my_users->confirm_del;

	//if($_GET['pid']){

        /*
		if($SESS_ACCESS_LEVEL == "root admin"){
			$pid = $_GET['pid'];
			$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where id = ?");
			$result  		= $STH->execute(array($pid));
			$count_valid	= $STH->fetchColumn();
			if($count_valid == 1){
				$rows = sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($pid), "rows");
				foreach($rows as $row){
					$parent_id					= $row['id'];
					$parent_fullname			= $row['fullname'];
				}
			}else{
				$_SESSION['msg'] = "<strong>$invalid_id_xml </strong> $no_account_xml";
				redirect('list_admins.php');
			}
		}else{
			$_SESSION['msg'] = "<strong>$access_denied_xml </strong> $no_access_xml";
			redirect('index.php');
		}
        */

	//}else{

		if($SESS_ACCESS_LEVEL == "root admin"){
			//allow
      if($_GET['pid']){
        $SESS_COMPANY_ID = $_GET['pid'];
        $rows 		= sql($DBH, "SELECT * FROM tbl_login where company_id = ? ", array($SESS_COMPANY_ID), "rows");
  			foreach($rows as $row){
  				$SESS_COMPANY_NAME	= $row['fullname'];
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

	//}

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
           .company-employee{
               margin: auto;
               display: block;
               color:black;
               margin-top: 20px
           }
           .company-employee:hover{
              background-color: #f9f9f9 !important;
              text-decoration: none;
              color: black
               /*display: inline-block;*/
               /*padding: 15px;*/
           }
           .company-employee img{
               border-radius: 100% !important;
               width:130px;
               height: 130px;
           }
           .company-employee .name-text{
               font-size: 13px;
               font-weight: bold;
           }
           .company-employee .position-text{
               font-size: 13px
           }
           button.button-view-list-grid{
             float: right;
             background: transparent;
             border: 1px solid #32c5d2;
             color: #32c5d2;
             padding: 7px 15px;
             margin-top: 10px;
             font-size: 22px;
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

            <?php
            if (isset($success_msg)) {
                echo "<div class='note note-success remove_after_5'><p>$success_ms</p></div>";
            }else if (isset($error_msg)) {
                            echo "<div class='note note-success remove_after_5'><p>$error_msg</p></div>";
            }

            ?>


              <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                      <div class="row">
                        <div class="col-md-9">
                          <h1 style="margin:0px; padding:10px;"> <i class="fa fa-tasks"></i> Projects</h1>
                        </div>
                        <div class="col-md-3">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal_pro" style="margin-left:55px">Add New Project</button>
                          <div class="modal fade" id="myModal_pro" role="dialog">
                            <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">$times;</button>
                                <h4>Add New Project</h4>
                              </div>
                              <form  action="exe_add_project.php" method="post">

                              <div class="modal-body">
                                <div class="row form-group">
                                  <div class="col-md-4">
                                    <label> Project Name: </label>
                                    <input type="text" name="retrun" value="projects.php" class="hidden">
                                  </div>
                                  <div class="col-md-8">
                                    <input type="text" name="proj_name" autocomplete="off" class="form-control" required>
                                  </div>
                                </div>

                              </div>
                              <div class="modal-footer">
                                <div class="">
                                  <input type="submit" name="" value="Save" class="btn btn-primary">
                                </div>
                              </div>
                            </form>
                            </div>
                            </div>
                          </div>
                        </div>
                    </div>
                       <br><hr>
                      <table class="table table-striped table-hover table-bordered table-documents" id="project_table">

                        <tr>
                          <th>#</th>
                          <th>Project Title</th>
                          <th>Company Name</th>
                          <th>Created at</th>
                          <th>Action</th>
                        </tr>

                        <?php
                        $company_id = $_SESSION['SESS_COMPANY_ID'];
                        $company_name = $_SESSION['SESS_COMPANY_NAME'];


                        $rows = sql($DBH,"select * from tbl_add_project where company_id=? ",array($company_id),"rows");
                        $count=0;
                        foreach ($rows as $row) {
                          $count++;

                          $btn_update="<span class='btn btn-primary' data-toggle='modal' data-target='#updatemodal".$row['id']."'><i class='fa fa-refresh'></i> Update</span>";
                          $btn_delete="<a href='exe_project.php?id=".$row['id']."'class='btn btn-danger'><i class='fa fa-refresh'></i> Delete</a>";


                            echo "<tr>
                              <td>$count</td>
                              <td>".$row['project_name']."</td>
                              <td>$company_name</td>
                              <td>".my_simple_date($row['date_time'])."</td>
                              <td>$btn_update.$btn_delete</td>
                            </tr>";
                            echo'<div class="modal fade" id="updatemodal'.$row['id'].'" role="dialog">
                              <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">$times;</button>
                                  <h4>Update Row</h4>
                                </div>
                                <form  action="exe_project.php" method="post">

                                <div class="modal-body">
                                  <div class="row form-group">
                                    <div class="col-md-4">
                                      <label> Project Name:</label>
                                      <input type="text" name="id" value="'.$row['id'].'" class="hidden">
                                    </div>
                                    <div class="col-md-8">
                                      <input type="text" name="proj_name" autocomplete="off" class="form-control" placeholder="'.$row['project_name'].'" required>
                                    </div>
                                  </div>

                                </div>
                                <div class="modal-footer">
                                  <div class="">
                                    <input type="submit" name="" value="Update" class="btn btn-primary">
                                  </div>
                                </div>
                              </form>
                              </div>
                              </div>
                            </div>';


                          }


                            //die(json_encode($rows));

                         ?>

                      </table>


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

		<!-- END THEME LAYOUT SCRIPTS -->


               <script>

				setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);

		</script>
    </body>



</html>
