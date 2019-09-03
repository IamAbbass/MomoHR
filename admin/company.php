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


                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <form id="filter" action="" class="form-horizontal" method="GET">
										<div class="form-body">
											<input type="hidden" name="date_from" value="<?php echo $_GET['date_from']; ?>" />
                                            <input type="hidden" name="date_to" value="<?php echo $_GET['date_to']; ?>" />
                                            <div class="form-group">
												<div class="col-md-4">
													<div class="input-group">
														<span class="input-group-addon">
															<i class="fa fa-search"></i>
														</span>
														<input type="text" class="form-control" name="search" value="<?php echo $_GET['search']; ?>" placeholder="<?php echo $search_xml; ?>" />
                                                    </div>
												</div>
                                                <div class="col-md-4">
													<div id="reportrange" class="btn default">
														<i class="fa fa-calendar"></i> &nbsp;
														<span> </span>
														<b class="fa fa-angle-down"></b>
													</div>
												</div>
												<div class="col-md-4">
													<button type="submit" class="btn yellow"><i class="fa fa-filter"></i> <?php echo $filter_xml; ?></button>
                                                </div>
											</div>
										</div>
									</form>
						    </div>


                            <?php

								if($parent_id){
									$SESS_ID = $parent_id;
								}

								$search     = $_GET['search'];
								$date_from  = $_GET['date_from'];
								$date_to  	= $_GET['date_to'];

								if((strlen($date_from) > 0 && strlen($date_to) > 0) || strlen($search) > 0){
									$from_ts    = strtotime($date_from);
									$to_ts      = strtotime($date_to);
									$to_ts      = $to_ts+86400;
									$rows = sql($DBH, "SELECT * FROM tbl_login where date_time >= ? AND date_time <= ? AND (fullname like ? OR email like ? OR contact like ?) AND company_id = ? order by (access_level)",
									array($from_ts,$to_ts,"%$search%","%$search%","%$search%",$SESS_COMPANY_ID), "rows");
									if((strlen($date_from) > 0 && strlen($date_to) > 0)){
										$filter_text = " $from_xml ".$date_from." $to_xml ".$date_to;
									}
									if(strlen($search) > 0){
										$filter_text .= " $search_colon_xml '$search'";
									}
									$filter_text .= " <a href='".$_SERVER['PHP_SELF']."' class='btn btn-default btn-xs btn-circle'>Clear Filter</a>";
									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where date_time >= ? AND date_time <= ? AND (fullname like ? OR email like ? OR contact like ?) AND company_id = ?");
									$result  		= $STH->execute(array($from_ts,$to_ts,"%$search%","%$search%","%$search%",$SESS_COMPANY_ID));
									$count_total	= $STH->fetchColumn();
								}else{
									$date_from = date("m/d/Y");
									$date_to   = date("m/d/Y");
									$rows = sql($DBH, "SELECT * FROM tbl_login where company_id = ? order by (access_level)", array($SESS_COMPANY_ID), "rows");
									$filter_text = "$showing_all_records_xml";
									$STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_login where access_level = ? AND company_id = ?;");
									$result  		= $STH->execute(array("user",$SESS_COMPANY_ID));
									$count_total	= $STH->fetchColumn();
								}
							?>

                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <div class="portlet box green">
									<div class="portlet-title">
										<div class="caption">
											<i class='fa fa-briefcase'></i> <b><?php echo $SESS_COMPANY_NAME; ?></b>'s Company <span>(<?= count($rows) ?>)</span>
                                            <div class="actions pull-right">
   											<a href="add_user.php?pid=<?php echo $SESS_COMPANY_ID ?>" class="btn green btn-xs">
   												<i class="fa fa-plus"></i> Add New
   											</a>
   										</div>
                                      </div>
									</div>
									<div class="portlet-body">
                                     <?php if(count($rows) > 0){ ?>

                                     <div class="tabbable-line">
                                        <ul class="nav nav-tabs ">
                                            <li class="active">
                                                <a href="#" data-toggle="tab"> Directory </a>
                                            </li>
                                            <button class="button-view-list-grid" id="button_view_style"><i class="fa fa-th"></i></button>
                                        </ul>
                                      </div>
                                          <div class="row" id="company_grid">
                                                <?php foreach ($rows as $key => $value) {
                                                    $rows_profile =  sql($DBH, "SELECT * FROM tbl_employee_profile where employee_id = ? ", array($value['id']), "rows");
                                                	  foreach($rows_profile as $row_profile){
                                                        $job_description = $row_profile['job_description'];
                                                        if(strlen($job_description) == 0){
                                                            $job_description = "No Job Description";
                                                        }
                                                    }
                                                  ?>
                                                      <div user_id='<?= $value['id'] ?>' class="col-md-2 text-center" style="min-height:175px;">
                                                          <a href="employee-profile.php?id=<?= $value['id'] ?>" class="company-employee" data-toggle="tooltip" title="<?php echo $job_description; ?>">
                                                             <div class="company-employee-img"><img src="<?= video_to_photo($value['photo']) ?>" class="wait-for people-block-image lazy-load" /></div>
                                                             <div class="people-list-info">
                                                                <div class="name-text user-link-name">
                                                                  <?php
                                                                    echo my_substr($value['fullname'],5);
                                                                    if($value['id'] == $SESS_ID){
                                                                      echo " <kbd>(You)</kbd>";
                                                                    }else if($value['access_level'] == "admin"){
                                                                      echo " <kbd>(Admin)</kbd>";
                                                                    }
                                                                  ?>

                                                                </div>
                                                             </div>
                                                          </a>
                                                      </div>
                                                <?php } ?>
       		                               </div>
                                          <div class="row" id="company_list" style="display: none;margin-top: 30px">
                                            <div class="col-md-12 col-sm-12">
                                              <table class="table table-striped table-bordered table-hover" id="sample_2">

        											<thead>

        												<tr>

        													<th>#</th>

        													<th><?php echo $name_xml; ?></th>

        													<th><?php echo $email_xml; ?></th>

        													<th><?php echo $contact_xml; ?></th>

        													<th><?php echo $registration_date_xml; ?></th>

        													<th><?php echo $status_xml; ?></th>

                                                            <th>Mode</th>

        													<th style="width:100px;"><?php echo $actions_xml; ?></th>

        												</tr>

        											</thead>

        											<tfoot class="hidden">

        												<tr>

        													<th>#</th>

        													<th><?php echo $name_xml; ?></th>

        													<th><?php echo $email_xml; ?></th>

        													<th><?php echo $contact_xml; ?></th>

        													<th><?php echo $registration_date_xml; ?></th>

        													<th><?php echo $status_xml; ?></th>

                                                            <th>Mode</th>

        													<th style="width:100px;"><?php echo $actions_xml; ?></th>

        												</tr>

        											</tfoot>

        											<tbody id="adds_here">

        												<?php





        													$sno = 0;

        													foreach($rows as $row){

        														$sno++;

        														$id					= $row['id'];

        														$fullname			= $row['fullname'];

        														$email    	    	= $row['email'];

        														$contact			= $row['contact'];

        														$address			= $row['address'];

        														$city				= $row['city'];

        														$country			= $row['country'];

        														$photo				= get_employee_photo($id);

        														$registration_date	= my_simple_date($row['date_time']);



                                                                $update_at          = $row['update_at'];

                                                                if(strlen($update_at) > 0 && $update_at > 0){

                                                                    $update_at = "<Br>$updated_colon_xml ".my_simple_date($update_at);

                                                                }



        														$access_level		= ucfirst($row['access_level']);

        														$status				= $row['status'];

        														$mode				= $row['mode'];

                                                        
                                                                $btn_show 		= "<a action_id='$id' class='btn btn-success purple btn-xs btn-circle btn_user_mode' href='ajax/mode.php?id=$id&mode=basic'><i class='fa fa-mobile'></i> Advance/Basic</a>";
                                                        
                                                                $status_show 	= "<span mode_id='$id'  class='badge badge-info'>".ucfirst($mode)."</span>";

        														if($status == "active"){

        															$status_badge_class = "success";

        															$status_badge_text	= "$active_xml";



        															$action_btn_class	= "danger";

        															$action_btn_text	= "$block_unblock_xml";

        														}else{

        															$status_badge_class = "danger";

        															$status_badge_text	= "$inactive_xml";



        															$action_btn_class	= "success";

        															$action_btn_text	= "$block_unblock_xml";

        														}



        														$status_badge 	= "<span badge_id='$id' class='badge badge-".$status_badge_class."'>".$status_badge_text."</span>";

                                                                $status_mode 	= "<span badge_id='$id' class='add_status badge badge-".$status_badge_class."'>Ad</span>";

        														



        														//urls

        														$profile_url		= "employee-profile.php?id=$id";



                                                                if(strlen($email) == 0){

                                                                    $email = "<span class='badge badge-default'>$no_email_xml</span>";

                                                                }
                                                                
                                                                if($SESS_ID != $id){
                                                                    $action_btn 	= "<a href='ajax/active_inactive.php?id=$id&type=user' class='btn btn-danger btn-xs btn-circle active_inactive'>

        														      <i class='fa fa-lock'></i> ".$action_btn_text."

        														      </a>";
                                                                      
                                                                      $delete_btn = "<a user_id='$id' href='ajax/delete_user.php?id=$id&type=user' class='btn btn-danger btn-xs btn-circle btn_delete_user'>

                														      <i class='fa fa-times'></i> $delete_user_xml

                														</a>";
                                                                }else{
                                                                    $action_btn = "";
                                                                    $delete_btn = "";
                                                                }



        														echo "<tr>

        															<td>$sno</td>

        															<td><a href='$profile_url'><img src='$photo' alt='' class='user-dp' /> $fullname</a></td>

        															<td>$email</td>

        															<td>$contact</td>

        															<td>$registration_date $update_at</td>

        															<td>$status_badge</td>

                                                                    <td>
                                                                       $status_show
                                                                    </td>
                                                                    <td>



        																<a href='$profile_url' class='btn btn-warning btn-xs btn-circle'>

        																	<i class='fa fa-pencil'></i> $edit_profile_xml

        																</a>



        																$action_btn





                                                                        $delete_btn

                                                                        $btn_show


   <a  class='btn btn-success btn-xs btn-circle' href='setting.php?id=$id'><i class='fa fa-mobile'></i> Employee Schedule</a>
        

        															</td>

        														</tr>";

        													}



        												?>

        											</tbody>

        										</table>
                                              </div>
                                          </div>

                                          <?php }else{ ?>

                                          <p class="text-center text-muted">
                                                <br />
                                                <br />
                                                <i class="fa fa-users fa-2x"></i>
                                                <br />
                                                <br />
                                                You don't have any one in company. <a href="add_user.php">Add New</a>
                                                <br />
                                                <br />
                                          </p>

                                          <?php } ?>

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

		<!-- END THEME LAYOUT SCRIPTS -->
        <script type="text/javascript">
              $('#company_table').DataTable();
              $('#button_view_style').on('click',function() {
                  // $(this).html('<i class="fa fa-trash-o"></i>').toggle();
                    $(this).find('i').toggleClass('fa-list');
                    if ($(this).find('i').hasClass('fa fa-th fa-list')) {
                      $('#company_list').show();
                      $('#company_grid').hide();
                    }
                    else{
                      $('#company_grid').show();
                      $('#company_list').hide();
                    }
              });
              setTimeout(function(){

					<?php

						$date_from 	= $_GET['date_from'];

						$date_to 	= $_GET['date_to'];

						if(strlen($date_from) > 0 && strlen($date_to) > 0){

							$show_date_from 	= date("M d, Y",strtotime($_GET['date_from']));

							$show_date_to 		= date("M d, Y",strtotime($_GET['date_to']));

						}

					?>



                    var date_from 	= "<?php echo $show_date_from; ?>";

					var date_to		= "<?php echo $show_date_to; ?>";



					if(date_from == "" && date_to == ""){

						$("#reportrange span").text("<?php echo $showing_all_records_xml; ?>");

					}else if(date_from == "" && date_to == ""){

						$("#reportrange span").text(date_from+" - "+date_to);

					}

					$("#reportrange span").show();

                },50);
              $("#filter").submit(function(){

					var date_from 	= $("input[name='daterangepicker_start']").val();

					var date_to		= $("input[name='daterangepicker_end']").val();

					$("input[name='date_from']").val(date_from);

					$("input[name='date_to']").val(date_to);

					if(date_from == "" && date_to == ""){

						$("#reportrange span").text("<?php echo $showing_all_records_xml; ?>");

					}

				});

                $('[data-toggle="tooltip"]').tooltip();


                $(".btn_user_mode").click(function(e){
				    //var x = confirm("Are you sure you want to change the employee mode ?");
				    //if(!x){
					//   e.preventDefault();
				    //}else{
					   e.preventDefault();

					   var this_ = $(this);
    					$(this_).html("<i class='fa fa-refresh fa-spin'></i> Working..");

    					$.get($(this).attr("href"),function(data){
                            data = $.parseJSON(data);
                            var new_status  = data.new_status;
                            var new_class   = data.new_class;
                            var new_html    = data.new_html;
                            var id          = data.id;

                            if(new_status == "advance"){
                                $("#adds_here").find("span[mode_id='"+id+"']").html("Advance");
                            }else{
                                $("#adds_here").find("span[mode_id='"+id+"']").html("Basic");
                            }

                            $(this_).html("<i class='fa fa-mobile'></i> Basic/Advance");

    					});
                    //}
				});
                $(".btn_delete_user").click(function(e){
                    
                    var user_id = $(this).attr('user_id');



				var x = confirm("<?php echo $confirm_del_xml; ?>");

				if(!x){

					e.preventDefault();

				}

					else{

					e.preventDefault();

					var this_ = $(this);

					$(this_).html("<i class='fa fa-refresh fa-spin'></i> <?php echo $delete_user_xml; ?>");

					$.get($(this).attr("href"),function(data){

						$(this_).parent().parent().addClass("deleting");

                        $(this_).parent().parent().fadeOut();
                        
                        $("div[user_id='"+user_id+"']").remove();

					});



                    }

				});
                	$(".active_inactive").click(function(e){

					e.preventDefault();

					var this_ = $(this);

					$(this_).html("<i class='fa fa-refresh fa-spin'></i> <?php echo $block_unblock_xml; ?>");

					$.get($(this).attr("href"),function(data){

						data = $.parseJSON(data);

						if(data.error == ""){

							alert(data.error);

						}else{

							$("span[badge_id='"+data.id+"']").html(data.new_html);

							$("span[badge_id='"+data.id+"']").removeClass("badge-success badge-danger").addClass(data.new_class);

						}

						$(this_).html("<i class='fa fa-lock'></i> <?php echo $block_unblock_xml; ?>");

					});

				});
        </script>
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
