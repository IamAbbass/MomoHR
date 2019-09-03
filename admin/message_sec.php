<?php
    header("Access-Control-Allow-Origin: *");
    
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
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_message_section'] != "true"){
			$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
			redirect('index.php');
		}
	}else if($SESS_ACCESS_LEVEL == "user"){
		//$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
		//redirect('index.php');
	}else{
		$_SESSION['msg'] = "<strong>Access Denied: </strong> You have no access to view this content";
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
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet" type="text/css" />
		
		<link href="assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/clockface/css/clockface.css" rel="stylesheet" type="text/css" />
        
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
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
		<link href="assets/message_section/style.css" rel="stylesheet" type="text/css" />
        <style>
        .attachment_close{
            z-index: 10000;
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
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content" style="background: #fff;">
                        <!-- BEGIN PAGE HEADER-->
                        
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
                        
                        <!-- END PAGE HEADER-->
                        <div class="row">
                            <div class="col-md-12" id="main_frame">
                                <!-- BEGIN TODO SIDEBAR -->
                                <div class="todo-ui">                                    
                                    <!-- END TODO SIDEBAR -->
                                    <!-- BEGIN TODO CONTENT -->
                                    <div class="todo-content">
                        
                                        <div class="portlet light ">
                                            <!-- PROJECT HEAD -->
                                            <div class="portlet-title">
                                                <div class="caption" style="width: 100%; font-size: inherit;">                                                    
                                                    <div class="row">
                                                        
                                                        <div class="col-md-4">  
                                                            <div class="dropdown pull-left">
                                                              <button style="margin-right: 2px;" class="btn_tags_reload btn btn-circle green btn-sm btn-default btn-outline dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-filter"></i> <span class='ch_filter'><?php echo $xml->message_section_left->filter; ?></span> </span>
                                                              <span class="caret"></span></button>
                                                              <ul id="tags_reload" class="dropdown-menu">                                                                    
                                                                <li class="user_filter" filter="all"><a href="#"><i class="fa fa-list-alt"></i> <?php echo $xml->message_section_left->all; ?></a></li>
                                                                <li class="user_filter" filter="read"><a href="#"><i class="fa fa-circle"></i> <?php echo $xml->message_section_left->read; ?></a></li>
                                                                <li class="user_filter" filter="unread"><a href="#"><i class="fa fa-circle-o"></i> <?php echo $xml->message_section_left->unread; ?></a></li>                                                                
                                                                <?php
                                                                    $rows = sql($DBH,"select distinct(tag_name) as tag_name from tbl_tags where store_id = ?",
                                                            	    array($SESS_STORE),"rows");
                                                                    foreach($rows as $row){ 
                                                                        echo "<li class='user_filter tags_ajax_remove' filter='tag:".$row['tag_name']."'><a href='#'><i class='fa fa-tag'></i> ".$row['tag_name']."</a></li>";
                                                                    }
                                                                ?> 
                                                                
                                                              </ul>
                                                            </div>                                                              
                                                            &nbsp;                                                            
                                                            <div style="margin-right: 2px;" class="dropdown pull-left">
                                                              <button class="btn btn-circle btn-sm btn-default btn-outline select_all"> <i class="fa fa-check-square"></i> <?php echo $xml->message_section_left->select_all; ?> </button>
                                                            </div>
                                                            &nbsp;
                                                            <div id="bulk_action" class="dropdown pull-left">
                                                              <button class="btn btn-circle purple btn-sm btn-default btn-outline dropdown-toggle" type="button" data-toggle="dropdown"> <?php echo $xml->message_section_left->select_action; ?> </span>
                                                              <span class="caret"></span></button>
                                                              <ul class="dropdown-menu">
                                                                    <li class="bulk_delete hidden"><a href="#"><i class="fa fa-trash"></i> <?php echo $xml->message_section_left->clear_chat; ?></a></li>
                                                                    <li class="bulk_read"><a href="#"><i class="fa fa-circle-o"></i> <?php echo $xml->message_section_left->mark_as_read; ?></a></li>
                                                                    <li class="bulk_unread"><a href="#"><i class="fa fa-circle"></i> <?php echo $xml->message_section_left->mark_as_unread; ?></a></li>
                                                                    <li class="bulk_reset hidden"><a href="#">Cancel</a></li>
                                                              </ul>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-4">
                                                            <h5 class="page-title text-center" style="margin: 0;">
                                                                <i class="icon-bar-chart font-green-sharp hide"></i>
                                                                <span class="caption-subject font-green-sharp uppercase">
                                                                    <i class='fa fa-envelope-square'></i> Message Section <span id="noti_messages" class='badge badge-success'></span>
                                                                </span>
                                                            </h5>                                                        
                                                        </div>
                                                        
                                                        <!-- group functionality -->
                                                        <div class="col-md-4">  
                                                             <button class="btn btn-circle btn-sm purple btn-outline pull-right create_new_group"> <i class="fa fa-pencil-square-o big_i"></i> New Group</button> 
                                                        </div>                                                       
                                                        <!-- group functionality end -->  
                                                                 
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <!-- end PROJECT HEAD -->
											<div class="portlet-body">
                                                <div class="row">
                                                    
                                                    <div class="col-md-4 col-sm-4 user_container">
                                                        <input id="search_users" type="text" placeholder="<?php echo $xml->message_section_left->Search; ?>" class="form-control" />
                                                        
														<div id="users_here" class="todo-tasklist">												            
														</div>                                                        
                                                        <div id="no_users_found" class="todo-tasklist">
															<br />
															<i class="fa fa-2x fa-search"></i><br/><br/>No Users To Show
														</div>														
                                                    </div>
                                                                                                                                                   
                                                    <div class="col-md-8 col-sm-8 chat-box">
                                                            <!-- TASK HEAD -->
                                                             
                                                            <div class="form">                                                                
                                                                
                                                                <div class="new_group_input">
                                                                    <div class="form-group">
                                                                        <div class="input-group select2-bootstrap-append">
                                                                            <select id="multi-append" class="form-control select2" multiple>
                                                                                <option></option>
                                                                                <?php
                                                                                   $rows = sql($DBH, "SELECT * FROM tbl_login where access_level = ? and company_id = ? and status = ?", array("user",$SESS_COMPANY_ID,"active"), "rows");	
									                                               foreach($rows as $row){	
                                														$id					= $row['id'];
                                														$fullname			= $row['fullname'];
                                														$email    	    	= $row['email'];
                                														$contact			= $row['contact'];
                                														$photo				= $row['photo'];
                                                                                        echo "<option value='$id'>$fullname</option>";
                                                                                    }
                                                                                
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>  
                                                                
                                                                
                                                                <div class="admin_help">
                                                                    <div class="pre-text-help text-muted text-center">
                                                                        <i class="fa-2x fa fa-comments-o"></i>
                                                                        <p><?php echo $xml->message_section_middle->please_select_a_customer; ?></p>    
                                                                    </div>
                                                                </div>      
                                                                                                                              
                                                                <div class="form-group chat-heading">
                                                                    <div class="col-md-12">
                                                                        <div class="row">                                                                        
                                                                            <div class="col-md-9" style="padding: 0 0 0 5px;">
                                                                                <a href="javascript:;" target="_blank" class="user_href" title='View Profile'>
                                                                                    <img id="chat_profile_pic" class="todo-userpic pull-left" src="assets/load-sm.gif"  />
                                                                                    <span class="chat_name todo-username pull-left text-success"></span>
                                                                                </a>
                                                                            </div>  
                                                                            <div class="col-md-3">
                                                                                <a data-toggle="modal" href="#group_info" style="margin-top: 4px;" class="btn green btn-sm group_info pull-right"> <i class="fa fa-gear big_i"></i> Group Settings </a>
                                                                            </div>                                                                          
                                                                        </div>
                                                                    </div>
                                                                    <p class="group_desc"></p>
                                                                    <div class="clearfix"></div>
                                                                </div>
                                                                
                                                                <!-- END TASK HEAD -->
                                                                
                                                                <div class="chat-messages-container">                                                                    
                                                                    <!-- ajax | multiple chat-messages -->                                                                    
                                                                </div>
                                                                
																<div class="reply_container">
                                                                    <div class="col-md-12">
                                                                        <div class="reply_content">
                                                                            <div class="pull-left reply_content_text">
                                                                                <p class="reply_name reply_name_ui"></p>
                                                                                <p class="reply_text reply_text_ui"></p>
                                                                            </div>
                                                                            <div class="pull-right">
                                                                                <img class="reply_img reply_img_ui" src="" alt="Image" />
                                                                            </div>
                                                                            <div class="clearfix"></div>                                                                                
                                                                        </div>
                                                                        <button class="reply_close btn btn-xs pull-right"><i class="fa fa-times"></i></button>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                </div> 
																
                                                                <div class="form-group chat-footer">
                                                                    <form id="send_msg"> 														
																		<div class="col-md-10 no_padding">
																			<button type="button" class="attachment_close btn btn-danger btn-xs pull-left"><i class="fa fa-times"></i></button>
																			<img id='attachment_pic' src='' alt='' />             
                                                                            <textarea class="message_input" rows="2" placeholder="<?php echo $xml->message_section_middle->Type_message; ?>"></textarea>                                                                                                                                                   
                                                                        </div>
																		<div class="col-md-2 no_padding">
																			<div class="msg_buttons pull-right">
                                                                                <button type="submit" class="btn btn-sm btn-circle green"><i class="fa fa-paper-plane"></i></button>
                                                                                <button type="button" class="upload_attachment btn btn-sm btn-circle btn-primary"><i class="fa fa-upload"></i></button>   
                                                                            </div>                                                                            
                                                                            <div class="edit_buttons pull-right">
                                                                                <button type="submit" class="btn btn-sm btn-circle btn-warning"><i class="fa fa-check"></i></button>
                                                                                <button type="button" class="edit_cancel btn btn-sm btn-circle btn-default"><i class="fa fa-undo"></i></button>   
                                                                            </div> 																		
																		</div>
																		<div class="clearfix"></div>
                                                                    </form>
                                                                    
                                                                    <form style="display: none;" id="form_attachment" action="../app_services/upload.php" method="post" enctype="multipart/form-data">
                                                                        <input onchange="readURL(this,'#attachment_pic');" type="file" name="attachment" />
                                                                        <input name="attachment_info" type="hidden" />
                                                                        <button></button>
                                                                    </form>
                                                                    <div class="clearfix"></div>
                                                                </div>                                                                
                                                            </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- END TODO CONTENT -->
                                </div>
                            </div>
                            <!-- END PAGE CONTENT-->
                        </div>
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <!-- BEGIN FOOTER -->
            
            
            <div id="my_notification" class="note note-warning">
                <!-- ajax -->
            </div>
                        
           <!--
            id="pop_group"
           -->
            
            <div id="group_info" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div style="width:75%;" class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">Group Details</h4>
                            </div>
                            <div class="modal-body">  
                                <div class="group_info_loading text-center">
                                    <i class="fa fa-circle-o-notch fa-spin fa-2x"></i>
                                </div>
                                
                                <div class="group_info_loaded">
                                    <div class="col-md-5">
                                        <form style="display: none;" id="form_group_pic" action="../app_services/upload.php" method="post" enctype="multipart/form-data">
                                            <input type="file" name="upload_group_pic" />
                                            <button></button>
                                        </form>
                                        
                                        <img data-toggle='tooltip' data-placement='right' title='Click to change' class="group_picture" alt="" src="" />
                                        
                                        <div class="clearfix"></div>
                                        
                                        <div style="display: none;" class="note note-info group_pic_status"></div>
                                        
                                        <label>Group Name:</label>
                                        <input placeholder="Group Name" class="form-control group_name" type="text" />
                                        
                                        <label>Group Description:</label>
                                        <textarea placeholder="Group Description" class="form-control group_description"></textarea>
                                        <br />
                                        <button class="btn btn-sm btn-success save_group_info pull-right"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="list-group group_participants">   
                                          
                                        </ul>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="modal-footer">
                                <a class="btn default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i> <?php echo $xml->message_section_right->Back; ?></a>
                            </div>
                        </div>
                </div>
            </div>
            
            <div id="message_photo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <div class="modal-body">  
                                <img style="width:100%" src="" alt="" id="photo_pop" />          
                            </div>
                            <div class="modal-footer">
                                <a class="btn default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i> <?php echo $xml->message_section_right->Back; ?></a>
                            </div>
                        </div>
                </div>
            </div>
			
			<div id="share_pop" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
								<h4 class="modal-title">Forward Message</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <div class="modal-body" id="forward_pop_body">  
							
                            </div>
                            <div class="modal-footer">
                                <a class="btn default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i> <?php echo $xml->message_section_right->Back; ?></a>
                            </div>
                        </div>
                </div>
            </div>
            
            <div id="group_participants_pop" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
								<h4 class="modal-title">Forward Message</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <div class="modal-body" id="group_participants_pop_body">  
							
                            </div>
                            <div class="modal-footer">
                                <a class="btn default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i> <?php echo $xml->message_section_right->Back; ?></a>
                            </div>
                        </div>
                </div>
            </div>
			
			<div id="msg_info_pop" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
                <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
								<h4 class="modal-title">Message Info</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            </div>
                            <div class="modal-body" id="msg_info_data">  
							     <img class='load-sm' src='assets/load-sm.gif' alt='' />
                                 <table style="display: none;" class="table table-bordered table-striped">
                                    <tr>
                                        <th>Sent</th>
                                        <td class="sent"></td>
                                    </tr>
                                    <tr>
                                        <th>Delivered</th>
                                        <td class="delivered"></td>
                                    </tr>
                                    <tr>
                                        <th>Seen</th>
                                        <td class="seen"></td>
                                    </tr>
                                 </table>
                            </div>
                            <div class="modal-footer">
                                <a class="btn default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-arrow-left"></i> <?php echo $xml->message_section_right->Back; ?></a>
                            </div>
                        </div>
                </div>
            </div>
            
            
            
<?php
	echo $footer;
?>
            <!-- END FOOTER -->
        </div>
        <!-- BEGIN QUICK NAV -->
        
        <!-- END QUICK NAV -->
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
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
		<script src="assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
		
		<script src="assets/global/plugins/moment.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript"></script>
        <script src="assets/global/plugins/typeahead/typeahead.bundle.min.js" type="text/javascript"></script>
        <!--buttons laddal-->
        <script src="assets/global/plugins/ladda/spin.min.js" type="text/javascript"></script>
        
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/pages/scripts/components-bootstrap-select.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-bootstrap-tagsinput.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
        <!--buttons laddal-->
        <script src="assets/global/plugins/ladda/ladda.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/ui-buttons.min.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
                
        <script src="assets/message_section/script.js" type="text/javascript"></script>
		<script>
		
        var base_url            = "https://momohr.co/";
                
		var loading_img 		= base_url+"img/load-sm.gif";
        var loading_img_xs		= base_url+"img/load-xs.gif";
		var default_group_dp 	= base_url+"admin/assets/default/group.png"; 
        
		var file_audio			= base_url+"img/audio.png";
		var file_pdf			= base_url+"img/pdf.png";
        var file_zip            = base_url+"img/zip.png";
		var file_video			= base_url+"img/video.png";
		var file_file			= base_url+"img/file.png";
		
		var current_attachment_type = null;
		
		
		
function readURL(input,elem) {	
	$(elem).attr('src', loading_img);
    $(elem).show();
    
    if (input.files && input.files[0]) {
		var reader = new FileReader();			
		reader.onload = function (e) {
			var img = e.target.result;
			if(img.substr(5,5) == "image"){
				$(elem).attr('src', e.target.result);
				$(elem).hide().fadeIn();
			}
		};
		reader.readAsDataURL(input.files[0]);
	}else {			
		var img = input.value;
		$(elem).attr('src',img);
		$(elem).hide().fadeIn();
	} 
}
		
var new_height;

function sortUsingNestedText(parent, childSelector, keySelector) {
	var items = parent.children(childSelector).sort(function(a, b) {
		var vA = $(keySelector, a).text();
		var vB = $(keySelector, b).text();
		return (vA > vB) ? -1 : (vA < vB) ? 1 : 0;
	});
	parent.append(items);
}
sortUsingNestedText($('#users_here'), "div.ch", ".timestamp");
			
function sound(src){		
	var audioElement = document.createElement('audio');
	audioElement.setAttribute('src', src);
	audioElement.setAttribute('autoplay', 'autoplay');
	audioElement.addEventListener("load", function() {
		audioElement.play();
	}, true);
	audioElement.play();
}


function copyToClipboard(text) {
    var targetId = "_hiddenCopyText_";    
    
    target = document.getElementById(targetId);
    if (!target) {
        var target = document.createElement("input");
        target.style.position = "absolute";
        target.style.left = "-9999px";
        target.style.top = "0";
        target.id = targetId;
        document.body.appendChild(target);        
    }
    
    target.value = text;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    var succeed;
    try {
        succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    target.value = "";
    return succeed;
}	
			
$(document).ready(function(){                 
	setTimeout(function(){
		$(".remove_after_3_sec").slideUp();
	},3000);
	
	var current_ch_id           = null; 
	
	ajax_get_users              = [];
	
	var reply_intervals     = [];
	var reply_intervals_i   = 0;
			   
	var refresh_users_allow     = true; 
	var count_select            = [];
	
	//to get only updated chatheads
	var timestamp               = 0;
	var json_key                = 0;				
	var unread 					= []; //count of unread messages
	var message_callback 		= [];
	var ch_filter				= null;
	
    var reply_to                = null;
    var reply_msg_id            = null; 
    var reply_attachment        = null; 
    var reply_sender            = null;    
    var reply_message           = null;
    var wait_for_new_group      = false;
    var global_group_id         = null;
    
    var users_split             = "";
    
    var edit_id                 = null;
    var del_id                  = null;
    
	var apply_filter = function(ch_filter){
		if(ch_filter == "all"){
			$("#users_here .ch").show();
		}else if(ch_filter == "read"){
			$("#users_here .ch").hide();
			$("#users_here .ch").each(function(){
				if(!$(this).hasClass("ch_unread")){
					$(this).show();
				}
			});
		}else if(ch_filter == "unread"){
			$("#users_here .ch").hide();
			$("#users_here .ch").each(function(){
				if($(this).hasClass("ch_unread")){
					$(this).show();
				}
			});
		}
		
		if(!$("#users_here .ch").is(":visible")){
			$("#no_users_found").show();
		}else{
			$("#no_users_found").hide();
		}
		
		try{
		$(".ch_filter").text(ch_filter.charAt(0).toUpperCase() + ch_filter.slice(1));
		}catch(e){}
	}
	
	var total_noti = function(unread){
		var noti_messages = 0;
		unread.forEach(function(value, key){
			noti_messages += value;
		});
		if(noti_messages > 0){
			$("#noti_messages").text(noti_messages).slideDown();
		}else{
			$("#noti_messages").slideUp();
		}
	}
	
    
	var refresh_users = function(timestamp){                    
		if(refresh_users_allow == true){// && count_select.length == 0
									
			$.get("../app_services/messages.php?ts="+timestamp,function(data){                            
				if(refresh_users_allow == true){
					chat_json   = $.parseJSON(data); 
					timestamp   = chat_json.timestamp_new;
					
					$.each(chat_json.chatheads, function(key, value) {                       
                        
                        var ch_id         = value.id;
                        var ch_fullname   = value.fullname;
                        var ch_photo      = value.photo;
                        var ch_type       = value.type;
                        var ch_email      = value.email;
                        var ch_phone      = value.phone;
                        var chat_type     = value.chat_type;
                        var login_status  = value.login_status; //active/inactive/signout
                        var last_active   = value.last_active; //text
						
						if(chat_type == "group"){
                            if(wait_for_new_group == true){
								var tnp = "_temp_new_group_";
								
                                $(".ch[ch_id='"+tnp+"']").attr("ch_id",ch_id);
								$(".user_ready[ch_id='"+tnp+"']").attr("ch_id",ch_id);
								$(".chat-messages[ch_id='"+tnp+"']").attr("ch_id",ch_id);
								
								if(current_ch_id == tnp){//temp group is opened
									current_ch_id = ch_id;
									$(".group_info").show();
								}
								
								wait_for_new_group = false;
                            }
                        }
                        						 
                        
                        if(chat_type == "individual"){
        					/*if(ch_type == "root admin"){
        						var ch_badge = "<span class='hide-at-select inbox_type badge badge-primary'>&nbsp;</span>";
        					}else if(ch_type == "admin"){
        						var ch_badge = "<span class='hide-at-select inbox_type badge badge-info'>&nbsp;</span>";
        					}else if(ch_type == "user"){
        						var ch_badge = "<span class='hide-at-select inbox_type badge badge-danger'>&nbsp;</span>";
        					}*/
        
                            if(login_status == "active"){
        						var ch_badge = "<span title='"+last_active+"' class='hide-at-select inbox_type badge badge-success-green'>&nbsp;</span>";
        					}else if(login_status == "inactive"){
        						var ch_badge = "<span title='"+last_active+"' class='hide-at-select inbox_type badge badge-warning'>&nbsp;</span>";
        					}else if(login_status == "signout"){
        						var ch_badge = "<span title='"+last_active+"' class='hide-at-select inbox_type badge badge-danger'>&nbsp;</span>";
        					}
        
        				}else if(chat_type == "group"){
        					var ch_badge = "<span class='hide-at-select inbox_type badge'>&nbsp;</span>";
        				}
						
						//users						
						if($("#users_here").children("div[ch_id='"+ch_id+"']").length == 0){
							var append_2    = "<div class='user_dp' style='padding:0'><img class='hide-at-select todo-userpic pull-left ch_photo' alt='' />"+ch_badge+"<span class='select_del_bulk'><i class='fa fa-2x fa-square-o'></i></span></div>";
							var append_31   = "<div class='number_for_search' style='display: none;'>"+ch_email+" "+ch_phone+"</div>";
							var append_32   = "<div class='todo-tasklist-item-title'><span class='user_name ch_fullname'></span><span class='ch_date_time'></span></div>";
							var append_33   = "<div class='todo-tasklist-item-text'><span class='ch_msg'><span class='badge badge-default'>No Messages</span></span><span class='ch_status' style='display:none'></span></div>";
							var append_34   = "";                                        
							var append_3    = "<div class='user_ready' ch_id='"+ch_id+"'>"+append_31+append_32+append_33+append_34+"</div>";
							$("#users_here").append("<div ch_id='"+ch_id+"' class='ch user_search_box todo-tasklist-item'>"+"<div class='timestamp hidden'>"+0+"</div>"+append_2+append_3+"</div>");
						}	
                        
						
						//ID: forward pop users
						if($("#forward_pop_body").children("div[ch_id='"+ch_id+"']").length == 0){
							var append_2    = "<div class='user_dp' style='padding:0'><img class='hide-at-select todo-userpic pull-left ch_photo' alt='' />"+ch_badge+"<span class='select_del_bulk'><i class='fa fa-2x fa-square-o'></i></span></div>";
							var append_32   = "<div><span style='top: 13px !important;' class='user_name ch_fullname'></span><div><span ch_id='"+ch_id+"' style='margin-top: -8px;' class='btn_forward btn btn-sm btn-success pull-right'><i class='fa fa-share'></i> Send</span></div>";
							var append_3    = "<div class='user_ready' ch_id='"+ch_id+"'>"+append_32+"</div>";
							$("#forward_pop_body").append("<div ch_id='"+ch_id+"' class='ch user_search_box todo-tasklist-item'>"+append_2+append_3+"</div>");
						}	
                        
                        //ID: group pop users
						if($("#group_participants_pop_body").children("div[ch_id='"+ch_id+"']").length == 0){
							if(ch_id.substr(0,6) != "group_"){                            
                                var append_2    = "<div class='user_dp' style='padding:0'><img class='hide-at-select todo-userpic pull-left ch_photo' alt='' />"+ch_badge+"<span class='select_del_bulk'><i class='fa fa-2x fa-square-o'></i></span></div>";
    							var append_32   = "<div><span style='top: 13px !important;' class='user_name ch_fullname'></span><div><span ch_id='"+ch_id+"' style='margin-top: -8px;' class='btn_add_to_group btn btn-sm btn-success pull-right'><i class='fa fa-plus'></i> Add</span></div>";
    							var append_3    = "<div class='user_ready' ch_id='"+ch_id+"'>"+append_32+"</div>";
    							$("#group_participants_pop_body").append("<div ch_id='"+ch_id+"' class='ch user_search_box todo-tasklist-item'>"+append_2+append_3+"</div>");
	                        }
                        }

						$(".ch[ch_id='"+ch_id+"']").find(".ch_photo").attr("src",ch_photo);
						$(".ch[ch_id='"+ch_id+"']").find(".ch_fullname").text(ch_fullname);		
                                                
                        
                        if(current_ch_id == ch_id){
                            //dynamic update dp: if chat is opened
                            $("#chat_profile_pic").attr("src",ch_photo);
                            
                            //dynamic update name: if chat is opened
                            if(ch_fullname.length > 45){
                                ch_fullname = ch_fullname.substr(0,45)+"...";
                            }                            
                            $(".chat_name").html(ch_fullname);
                            
                            try{
                                $(".group_desc").html("<span data-toggle='tooltip' data-placement='bottom' title='Group Description'><i class='fa fa-info'></i> "+ch_description+"</span>").fadeIn();
                            }catch(e){
                                $(".group_desc").html("").fadeOut();
                            }
                            
                            
                        }
					});                                
					
					//messages
					$.each(chat_json.messages, function(key, value) {
						var m_id          	= value.id;
						var ch_id         	= value.ch_id;//done                          
						var m_received    	= value.received;//done
						var m_msg_type    	= value.msg_type;//done
						var m_msg_text    	= value.msg_text;//done
						var m_msg_url     	= value.msg_url;
                        var msg_url_size    = value.msg_url_size;
						var m_sent        	= value.sent;						
						var m_sent_date	  	= value.sent_date;					
						var m_sent_time	  	= value.sent_time;//for messages
						var m_sent_datetime	= value.sent_datetime;//for chat head: could be "date", "time" or "mins ago".						
						var m_delivered   	= value.delivered;//done
						var m_delivered_date= value.delivered_date;//done									
						var m_seen        	= value.seen;//done
						var m_seen_date		= value.seen_date;//done
						var m_status      	= value.status;//done
						var m_mark_as_read	= value.mark_as_read;//when I am the receiver
                        var msg_audience    = value.msg_audience;
                        var group_delivered = value.group_delivered;
                        var group_seen      = value.group_seen;
						var group_delivered_seen = "";
                        
						if($(".chat-messages[ch_id='"+ch_id+"']").length == 0){                        
							$(".chat-messages-container").append("<div class='chat-messages' ch_id='"+ch_id+"'><div class='col-md-12'><ul class='media-list current_chat'></ul></div></div>");
							//ui height
							$(".chat-messages").css("height",new_height+"px");
						}
														  
						var msg_in_ch   	     = "";	
						var message 		     = "";
						var bubble_class 	     = "";
                        var tooltip_placement    = "";                        
						var indication 		     = "";//for message									
						
						if(m_msg_type == "image"){
							if(m_msg_text.length == 0){m_msg_text = "Picture";}	
							msg_in_ch = "<i class='fa fa-camera'></i> "+m_msg_text;
							
                            message		= "<span class='attachment_preview'><a href='javascript:;' class='message_photo_box'>"+									    
							"<img role='button' data-toggle='modal' href='#message_photo' class='message_photo' src='"+m_msg_url+"' alt='Photo' />"+
							"</a></span>"+m_msg_text;										
						}else if(m_msg_type == "video"){
							if(m_msg_text.length == 0){m_msg_text = "Video";}	
							msg_in_ch = "<i class='fa fa-video-camera'></i> "+m_msg_text;
							
                            message		= "<span class='attachment_preview'><video class='message_photo' controls>"+									    
							"<source src='"+m_msg_url+"' type='video/mp4'>"+
							"Your browser does not support the video tag."+
							"</video></span>"+m_msg_text;										
						}else if(m_msg_type == "audio"){
							if(m_msg_text.length == 0){m_msg_text = "Audio";}	
							msg_in_ch = "<i class='fa fa-microphone'></i> "+m_msg_text;										
							
                            message		= "<span class='attachment_preview'><audio controls>"+									    
							"<source src='"+m_msg_url+"' type='audio/mp4'>"+
							"Your browser does not support the audio tag."+
							"</audio></span>"+m_msg_text;										
						}else if(m_msg_type == "zip"){
							if(m_msg_text.length == 0){m_msg_text = "ZIP";}
							msg_in_ch 	= "<i class='fa fa-file-archive-o'></i> "+m_msg_text;									
							var filename = m_msg_url.substring(m_msg_url.lastIndexOf('/')+1)+" <kbd>"+msg_url_size+"</kbd>";
                            
                            message		= "<span class='attachment_preview'><a class='msg_file' href='"+m_msg_url+"' target='_blank'><i class='fa fa-file-archive-o fa-2x'></i> "+filename+"</a></span>"+m_msg_text;
						}else if(m_msg_type == "pdf"){
							if(m_msg_text.length == 0){m_msg_text = "PDF";}
							msg_in_ch 	= "<i class='fa fa-file-pdf-o'></i> "+m_msg_text;									
							var filename = m_msg_url.substring(m_msg_url.lastIndexOf('/')+1)+" <kbd>"+msg_url_size+"</kbd>";
                            
                            message		= "<span class='attachment_preview'><a class='msg_file' href='"+m_msg_url+"' target='_blank'><i class='fa fa-file-pdf-o fa-2x'></i> "+filename+"</a></span>"+m_msg_text;
						}else if(m_msg_type == "unknown"){
							if(m_msg_text.length == 0){m_msg_text = "File";}
							msg_in_ch 	= "<i class='fa fa-file'></i> "+m_msg_text;									
							var filename = m_msg_url.substring(m_msg_url.lastIndexOf('/')+1)+" <kbd>"+msg_url_size+"</kbd>";
							
                            message		= "<span class='attachment_preview'><a class='msg_file' href='"+m_msg_url+"' target='_blank'><i class='fa fa-file-o fa-2x'></i> "+filename+"</a></span>"+m_msg_text;
						}else{
							message		= m_msg_text+"<br>";
							msg_in_ch	= m_msg_text;
						}
						
						if(m_received == false){
							msg_in_ch 			= "you: "+msg_in_ch;										
							bubble_class 		= "bubble_sent"; 
                            tooltip_placement   = "left";
                            
                            //for reply
                            var sender_name     = "You:";
										
							msg_opt_class		= "opt_left";
							
                            if(msg_audience == "individual"){                                
                                if(m_seen > 0){
    								var user_dp     = $("#users_here").children("div[ch_id='"+ch_id+"']").find(".ch_photo").attr("src");
                                    indication = "<span title='Seen: "+m_seen_date+"'><i class='fa fa-customised-img'><img src='"+user_dp+"' alt='' /></i> &bull; "+m_sent_time+"</span>";	
    								$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("<span title='Seen: "+m_seen_date+"'><i class='fa fa-customised-img'><img src='"+user_dp+"' alt='' /></i></span>").slideDown();
    							}else if(m_delivered > 0){
    								indication = "<span title='Delivered: "+m_delivered_date+"'><i class='fa fa-check-circle medium_i'></i> &bull; "+m_sent_time+"</span>";
    								$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("<span title='Delivered: "+m_delivered_date+"'><i class='fa fa-check-circle medium_i'></i></span>").slideDown();
    							}else{
    								indication = "<span title='Sent: "+m_sent_datetime+"'><i class='fa fa-check-circle-o medium_i'></i> &bull; "+m_sent_time+"</span>";
    								$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("<span title='Sent: "+m_sent_datetime+"'><i class='fa fa-check-circle-o medium_i'></i></span>").slideDown();
    							}
                            }else if(msg_audience == "group"){
                                indication = "<span title='Sent: "+m_sent_datetime+"'><i class='fa fa-check-circle-o medium_i'></i> &bull; "+m_sent_time+"</span>";
   								$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("<span title='Sent: "+m_sent_datetime+"'><i class='fa fa-check-circle-o medium_i'></i></span>").slideDown();
                            
                                //group seen delivered indication
                                
                                if(msg_audience == "group"){ 
                                    
                                    //list of delivered
                                    try{
                                        var group_delivered_split   = group_delivered.split(",");
                                    }catch(e){
                                    }
                                    
                                    //list of seen
                                    try{
                                        var group_seen_split        = group_seen.split(",");
                                    }catch(e){}
                                    //we will polupate after checking seen and delivered list
                                    var seen_users              = []; 
                                    var delivered_users         = []; 
                                    
                                    //loop over delivered array
                                    $.each(group_delivered_split, function( index, value ) {
                                        if($.inArray(value, group_seen_split) !== -1){//check if delivered + seen ?
                                            //delivered and seen
                                            if(value != ""){
                                                seen_users.push(value);
                                            }
                                        }else{
                                            //delivered
                                            if(value != ""){
                                                delivered_users.push(value);
                                            }
                                        }
                                    });
                                    
                                    if(seen_users.length > 0){
                                        group_delivered_seen += "Seen by "
                                        $.each(seen_users, function( index, value ) {                                             
                                            var user_dp     = $("#users_here").children("div[ch_id='"+value+"']").find(".ch_photo").attr("src");
	                                        var user_name   = $("#users_here").children("div[ch_id='"+value+"']").find(".user_name").text();
                                            group_delivered_seen += "<img data-toggle='tooltip' data-placement='left' title='"+user_name+"' src='"+user_dp+"' alt='' />";
                                        });
                                    }
                                    
                                    if(delivered_users.length > 0){                                        
                                        if(seen_users.length > 0){
                                            group_delivered_seen += "& ";
                                        }
                                        group_delivered_seen += "Delivered to "
                                        
                                        $.each(delivered_users, function( index, value ) {
                                            var user_dp     = $("#users_here").children("div[ch_id='"+value+"']").find(".ch_photo").attr("src");
                                            var user_name   = $("#users_here").children("div[ch_id='"+value+"']").find(".user_name").text();
                                            group_delivered_seen += "<img data-toggle='tooltip' data-placement='left' title='"+user_name+"' src='"+user_dp+"' alt='' />";
                                        });
                                    }
                                    
                                }
                            
                            }
							
							if(m_mark_as_read == "false"){											
								$(".ch[ch_id='"+ch_id+"']").addClass("ch_unread");
							}							
						}else{
							
                            //for reply
                            if(ch_id.substr(0,6) == "group_"){
                                var sender_name   = value.group_message_sender;
                            }else{
                                var sender_name   = $("#users_here").children("div[ch_id='"+ch_id+"']").find(".user_name").text()+":";
							}                            
                            
                            //message received
							if(current_ch_id != ch_id){//if chat is not opened
								if(m_mark_as_read == "false"){
									if($("#msg_i_"+m_id).length == 0){
                                        if(isNaN(unread[ch_id])){
    										unread[ch_id] = 1;													
    									}else{
    										unread[ch_id] = +unread[ch_id]+1;
    									}												
    									if(unread[ch_id] > 0){
    										$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html(unread[ch_id]+" unread").slideDown();
    									}else{
    									       //yahan1
                                            //$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("").slideUp();
    									}												
    									$(".ch[ch_id='"+ch_id+"']").addClass("ch_unread");	
    									if(m_delivered == 0){
    										message_callback.push(m_id+":delivered");
    									}
    									sound("sound/noti.mp3");
                                    }
								}else{
								    //yahan
									//$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("").slideUp();
								}												
							}else{
							     //yahan
								//$(".ch[ch_id='"+ch_id+"']").find(".ch_status").html("").slideUp();
                                if(m_seen == 0){
									message_callback.push(m_id+":seen");	
								}								
							}
							
							indication 			= m_sent_time;			
							bubble_class 		= "bubble_received";
                            tooltip_placement   = "right";
							msg_opt_class		= "opt_right";
						}
                        
                        //draft
                        var from_   = "<?php echo $SESS_ID; ?>";
                        var to_     = ch_id;
                        var key     = "draft_from_"+from_+"_to_"+to_;
                        var draft   = window.localStorage.getItem(key);
                        
                        //save old one
                        window.localStorage.setItem("old_from_"+from_+"_to_"+to_, msg_in_ch);
                        if(draft == null || draft == undefined || draft == ""){
                            //no need to show
                        }else{
                            msg_in_ch = "<span class='text-danger'>[Draft]</span> "+window.localStorage.getItem(key);  
                        }
                        
                        
                                       
						
						$(".ch[ch_id='"+ch_id+"']").find(".timestamp").html(m_sent);
						$(".ch[ch_id='"+ch_id+"']").find(".ch_msg").html(msg_in_ch).show();  									
						$(".ch[ch_id='"+ch_id+"']").find(".ch_date_time").html("<i class='fa fa-check-circle-o medium_i'></i> "+m_sent_datetime).show();
						
                        
        
						//message date
						var msg_date_exists = 	$(".chat-messages[ch_id='"+ch_id+"']").
												children("div").children("ul.current_chat").
												children(".seperator[date='"+m_sent_date+"']");
												
						if($(msg_date_exists).length == 0){ 
							var temp_message = 
							"<li date='"+m_sent_date+"' class='seperator'>"+
							"<span>"+m_sent_date+"</span>"+
							"</li>";                                    
							
                            if($("#msg_i_"+m_id).length == 0){   
							     $(".chat-messages[ch_id='"+ch_id+"']").children("div").children("ul.current_chat").append(temp_message);
	                        }else{
                                $(".chat-messages[ch_id='"+ch_id+"']").children("div").children("ul.current_chat").prepend(temp_message);
	                        }
                        }						
						//message date
						
                        var edited_deleted_icon = "";                     
                        if(value.edited == "true"){
                            edited_deleted_icon = "<i class='fa fa-pencil'></i> ";
                        }
                        if(value.deleted == "true"){
                            edited_deleted_icon = "<i class='fa fa-trash'></i> ";
                            message             = "This message was deleted";
                        }
                        
                        
						if($("#msg_i_"+m_id).length == 0){   
							
                            //message date
    						var msg_date_exists = 	$(".chat-messages[ch_id='"+ch_id+"']").
    												children("div").children("ul.current_chat").
    												children(".seperator[date='"+m_sent_date+"']");
    												
    						if($(msg_date_exists).length == 0){ 
    							var temp_message = 
    							"<li date='"+m_sent_date+"' class='seperator'>"+
    							"<span>"+m_sent_date+"</span>"+
    							"</li>";                                    
    							
    							$(".chat-messages[ch_id='"+ch_id+"']").children("div").children("ul.current_chat").append(temp_message);
    						}						
    						//message date
                            
                            if(m_received == true){
                                //no info
                                var show_msg_info = "";
                            }else{
                                //var show_msg_info = "<a msg_id='"+m_id+"' data-toggle='modal' href='#msg_info_pop' class='msg_info'><i class='fa fa-info-circle'></i></a>";
                                var show_msg_info = "";
                            }
                            var reply_ui = "";      
                            try{
                                
                                if(value.reply.reply_name != null){
                                    var reply_id    = value.reply.reply_id;
                                    var reply_name  = value.reply.reply_name;
                                    var reply_text  = value.reply.reply_text;
                                    var reply_img   = value.reply.reply_img;
                                    var reply_image_ui = "";
                                    if(reply_img != "null"){
                                        reply_image_ui = '<span class="pull-right">'+
                                                '<img class="in_msg_reply_img" src="'+reply_img+'" alt="Image" />'+
                                            '</span>';
                                    }                                    
                                    reply_ui = '<a href="#msg_i_'+reply_id+'" class="im_msg_reply_content">'+
                                                    '<span class="pull-left im_msg_reply_content_text">'+
                                                        '<span class="in_msg_reply_name">'+reply_name+'</span>'+
                                                        '<span class="in_msg_reply_text">'+reply_text+'</span>'+
                                                    '</span>'+
                                                    reply_image_ui+
                                                    '<span class="clearfix"></span>'+                                                                         
                                                '</a>';
                                                
                                                
                                }
                            }catch(e){}
                            
                            
                            
                            //group message sender name
                            var group_message_sender = "";
                            if(msg_audience == "group"){                                
                                if(value.group_message_sender != "You:"){
                                    group_message_sender = "<strong>"+(value.group_message_sender)+"</strong> ";    
                                }
                            }
                            
                            var temp_message = 
							"<li id='msg_i_"+m_id+"' class='media'>"+
    							"<div class='media-body todo-comment'>"+                            							
        							"<p data-toggle='tooltip' data-placement='"+tooltip_placement+"' title='"+m_sent_date+" "+m_sent_time+"' class='"+bubble_class+"'>"+
            							reply_ui+
                                        group_message_sender+
                                        '<span class="text_message">'+edited_deleted_icon+message+'</span>'+
            							"<span class='message_attr'>"+
            							 indication+
            							"</span>"+
        							"</p>"+ 
                                    "<p class='"+msg_opt_class+"' data-toggle='popover' data-time='"+m_sent_date+" "+m_sent_time+"' data-timestamp='"+m_sent+"' data-mid='"+m_id+"' class='"+bubble_class+"'>"+
                    					"<a class='popover_button' href='javascript:;'><i class='fa fa-ellipsis-v'></i></a><br />"+
                                     "</p>"+     							
                                "</div>"+
                                "<div class='group_delivered_seen'>"+group_delivered_seen+"</div>"+ 
							"</li>"; 
                            if(value.deleted == "false"){//only show if not deleted
                                $(".chat-messages[ch_id='"+ch_id+"']").children("div").children("ul.current_chat").append(temp_message);
                            }
                        }else{										
							$("#msg_i_"+m_id+" div p .message_attr").html(indication);
                            $("#msg_i_"+m_id+" .group_delivered_seen").hide().slideDown();
                            $("#msg_i_"+m_id+" .group_delivered_seen").html(group_delivered_seen);                            
                            $("#msg_i_"+m_id).find(".text_message").html(edited_deleted_icon+message);   
                            
                            
                            if(value.deleted == "true"){//delete
                                
                                var target = "#msg_i_"+m_id;
                                var bubble = "";
                                if($(target).children("div").children("p.bubble_received").length > 0){
                                    bubble = "bubble_received";
                                }else if($(target).children("div").children("p.bubble_sent").length > 0){
                                    bubble = "bubble_sent";
                                }
                                $(target).children("div").children("p."+bubble).css("background","#d64635");        
                                setTimeout(function(){
                                    $(target).fadeOut(function(){
                                        $(target).remove();
                                    });
                                },2000); 
                            }                         
						}
                        
                        //popover start
                        
                        //received message
                        $("#msg_i_"+m_id+" div p.opt_right a").popover({ 
                                trigger: "manual" , 
                                html: true, 
                                animation:false,
                                placement: 'right',
                                content: function(){                                   
                                    var forward     = "<li><a msg_id='"+m_id+"' href='#share_pop'  data-toggle='modal' class='msg_forward'><i class='fa fa-share-alt'></i> Forward</a></li>";
                                    if(m_msg_type == "image"){
                                        var reply_attachment    = m_msg_url;
                                    }else{
                                        var reply_attachment    = "null";
                                    }
                                    var reply       = "<li><a msg_id='"+m_id+"' attachment='"+reply_attachment+"' sender='"+sender_name+"' message='"+m_msg_text+"' href='javascript:;' class='msg_reply'><i class='fa fa-reply'></i> Reply</a></li>";
                                    if(m_msg_type == "text"){
                                        var copy        = "<li><a copy='"+m_msg_text+"' href='javascript:;' class='msg_copy'><i class='fa fa-clone'></i> Copy</a></li>";
                                    }else{
                                        var copy        = "";
                                    }
                                    var create_html = "<ul class='popover_ul'>"+forward+reply+copy+"</ul>";
                                    return create_html;
                                }
                            })
                            .on("mouseenter", function () {
                                var _this = this;
                                $(this).popover("show");
                                $(".popover").on("mouseleave", function () {
                                    $(_this).popover('hide');
                                });
                            }).on("mouseleave", function () {
                                var _this = this;
                                setTimeout(function () {
                                    if (!$(".popover:hover").length) {
                                        $(_this).popover("hide");
                                    }
                                }, 300);
                        });
                        
                        //sent message
                        $("#msg_i_"+m_id+" div p.opt_left a").popover({ 
                                trigger: "manual" , 
                                html: true, 
                                animation:false,
                                placement: 'left',
                                content: function(){                                    
                                    var diff            = (Math.round((new Date().getTime())/1000)-m_sent);
                                    var diff_readable   = 60-Math.floor(diff/60);
                                    
                                    var forward = "<li><a msg_id='"+m_id+"' href='#share_pop'  data-toggle='modal' class='msg_forward'><i class='fa fa-share-alt'></i> Forward</a></li>";
                                    
                                    if(m_msg_type == "image"){
                                        var reply_attachment    = m_msg_url;
                                    }else{
                                        var reply_attachment    = "null";
                                    }
                                    
                                    var reply   = "<li><a msg_id='"+m_id+"' attachment='"+reply_attachment+"' sender='"+sender_name+"' message='"+m_msg_text+"' href='javascript:;' class='msg_reply'><i class='fa fa-reply'></i> Reply</a></li>";
                                    
                                    if(m_msg_type == "text"){
                                        var copy        = "<li><a copy='"+m_msg_text+"' href='javascript:;' class='msg_copy'><i class='fa fa-clone'></i> Copy</a></li>";
                                        var msg_edit    = "<li><a msg_id='"+m_id+"' href='javascript:;' message='"+m_msg_text+"' class='msg_edit'><i class='fa fa-pencil'></i> Edit <small>("+diff_readable+" min)</small></a></li>";
                                    }else{
                                        var copy        = "";
                                    }      
                                    var msg_delete  = "<li><a msg_id='"+m_id+"' href='javascript:;' class='msg_delete'><i class='fa fa-trash'></i> Delete <small>("+diff_readable+" min)</small></a></li>";
                                    
                                    
                                    if(diff > 3600){ // can not delete or edit after 60 mins
                                        var msg_delete = "";
                                        var msg_edit = "";                                            
                                    }
                                                                            
                                    var create_html = "<ul class='popover_ul'>"+msg_edit+msg_delete+forward+reply+copy+"</ul>";
                                                                        
                                    return create_html;
                                }
                            })
                            .on("mouseenter", function () {
                                var _this = this;
                                $(this).popover("show");
                                $(".popover").on("mouseleave", function () {
                                    $(_this).popover('hide');
                                });
                            }).on("mouseleave", function () {
                                var _this = this;
                                setTimeout(function () {
                                    if (!$(".popover:hover").length) {
                                        $(_this).popover("hide");
                                    }
                                }, 300);
                        });
                        
                        //popover end
						
						if(current_ch_id == ch_id){ //if opened window is the same scroll down to see new message
							var chat_parent     = $(".chat-messages[ch_id='"+ch_id+"']");                    
							$(chat_parent).scrollTop($(chat_parent)[0].scrollHeight); 
						}
                        sortUsingNestedText($('#users_here'), ".ch", ".timestamp");
					});
					if(message_callback.length > 0){
						$.get("../app_services/message_callback.php?data="+JSON.stringify(message_callback)+"&type=message",function(){
							
						});
						message_callback = [];			
					}								
					total_noti(unread);					
					apply_filter(ch_filter);					
				}
				$( window ).resize();
			});
             
            
		}             
		setTimeout(function(){
			refresh_users(timestamp);
		},3000);
	}
	
	var default_type = null;	
	
   
   var search_users = function(){
		var txt = $("#search_users").val();
		if(txt.length > 0){
			$(".ch").hide();
			$(".ch").each(function(){
				if($(this).text().toUpperCase().indexOf(txt.toUpperCase()) != -1){
					$(this).show();
				}
			});                        
			if(!$("#users_here .ch").is(":visible")){
				$("#no_users_found").show();
			}else{
				$("#no_users_found").hide();
			}                        
			refresh_users_allow = false;
		}else{
			$("#no_users_found").hide();
			$(".ch").show();
			refresh_users_allow = true;
		}
   }
   refresh_users(timestamp);
   
								 
   //selection START
   $("#users_here").delegate(".ch","mouseover",function(){
		$(this).find(".hide-at-select").hide(); 
		$(this).find(".select_del_bulk").show(); 
   });               
   $("#users_here").delegate(".ch","mouseout",function(){
		if(count_select.length == 0){
			$(this).find(".hide-at-select").show(); 
			$(this).find(".select_del_bulk").hide();
		}
   });               
				  
   //select / deselect
   var selection_default = false;
   $(".select_all").click(function(){
		if(selection_default == false){
			$(this).html("<i class='fa fa-square-o'></i> Deselect All ");
			selection_default = true; 			
			count_select = [];
			$(".select_del_bulk").each(function(){
				$(this).children("i").removeClass("fa-check-square-o").addClass("fa-square-o");
				$(this).click(); 
			});			
		}else{
			$(this).html("<i class='fa fa-check-square'></i> Select All ");    
			selection_default = false;			
			$(".select_del_bulk").each(function(){
				$(this).children("i").removeClass("fa-square-o").addClass("fa-check-square-o");
				$(this).click(); 
			});
			count_select = [];   
			//unselect                     
		}		
		console.log(count_select);		
   });
					  
   $("#users_here").delegate(".select_del_bulk","click",function(){   
		var ch_id = $(this).parent().parent().attr("ch_id");
   
		if($(this).children("i").hasClass("fa-square-o")){
			$(this).children("i").removeClass("fa-square-o").addClass("fa-check-square-o");			
			count_select.push(ch_id);
		}else{
			$(this).children("i").removeClass("fa-check-square-o").addClass("fa-square-o");	
			count_select.splice(count_select.indexOf(ch_id),1);
		}                    
		if(count_select.length > 0){
			$("#users_here").find(".hide-at-select").hide(); 
			$("#users_here").find(".select_del_bulk").show();                         
			$("#bulk_action").fadeIn();
		}else{
			$("#users_here").find(".hide-at-select").show(); 
			$("#users_here").find(".select_del_bulk").hide();
			refresh_users_allow = true;
			$("#bulk_action").hide().val("");
		}    
		
		console.log(count_select);       
   }); 
   
   
   $(".bulk_delete").click(function(){
		$(".user_container_footer").hide();
		$("#del_bulk_btn, #del_bulk_reset").hide(); 
		$("#bulk_action").hide();  
		
		//loading sign
		for(var i=0; i<count_select.length; i++){
			//show done			
			$(".ch[ch_id='"+count_select[i]+"'] .select_del_bulk i").removeClass("fa-check-square-o").addClass("fa-square-o"); 
			
			//UI						
			$(".ch[ch_id='"+count_select[i]+"']").find(".ch_status").hide();
			$(".ch[ch_id='"+count_select[i]+"']").find(".timestamp").text(0);
			$(".ch[ch_id='"+count_select[i]+"']").find(".ch_msg").html("<span class='badge badge-default'>No Messages</span>");
			$(".ch[ch_id='"+count_select[i]+"']").find(".ch_date_time").hide();
			$(".ch[ch_id='"+count_select[i]+"']").removeClass("ch_unread");
			
			//empty message box			
			$(".chat-messages[ch_id='"+count_select[i]+"']").empty();			
		}
		
		refresh_users_allow = false;		
		$.get("../app_services/message_action.php?ch_ids="+count_select.toString()+"&action=delete",function(number){
			$("#users_here").find(".hide-at-select").show(); 
			$("#users_here").find(".select_del_bulk").hide(); 
			//refresh users
			refresh_users_allow = true;  
		});
		
		//reset
		count_select = [];
		$("#users_here").find(".hide-at-select").show(); 
		$("#users_here").find(".select_del_bulk").hide();
		$("#del_bulk_btn, #del_bulk_reset").hide();                    
		$(".select_del_bulk").children("i").removeClass("fa-check-square-o").addClass("fa-square-o"); 
		$("#bulk_action").hide(); 
   });
   
   $(".bulk_read").click(function(){
		for(var i=0; i<count_select.length; i++){                        
			$(".ch[ch_id='"+count_select[i]+"']").removeClass("ch_unread");			
		}
		
		$.get("../app_services/message_action.php?ch_ids="+count_select.toString()+"&action=read",function(number){				
		});
   });
   
   $(".bulk_unread").click(function(){
		for(var i=0; i<count_select.length; i++){                        
			$(".ch[ch_id='"+count_select[i]+"']").addClass("ch_unread");			
		}
		
		$.get("../app_services/message_action.php?ch_ids="+count_select.toString()+"&action=unread",function(number){				
		});
   });               
   
   $(".bulk_reset").click(function(){
		count_select = [];
		$("#users_here").find(".hide-at-select").show(); 
		$("#users_here").find(".select_del_bulk").hide();
		$("#del_bulk_btn, #del_bulk_reset").hide();                    
		$(".select_del_bulk").children("i")
		.removeClass("fa-check-square-o")
		.addClass("fa-square-o"); 
		$("#bulk_action").hide();  
   });  
   
	$(document).delegate("li.user_filter","click",function(){
		apply_filter($(this).attr("filter"));
	});
	
	var forward_msg_id = null;
	$(document).delegate(".msg_forward","click",function(){
		forward_msg_id = $(this).attr("msg_id");
		$(".btn_forward i").removeClass("fa-circle-o-notch fa-spin fa-check").addClass("fa-share");
	}); 
    
    $(document).delegate(".msg_copy","click",function(){
		var this_ = $(this);
        copyToClipboard($(this_).attr("copy"));                           
		$(this_).html("<i class='fa fa-check'></i> Copied");  
	});    
    
    
    $(document).delegate(".msg_reply","click",function(){
		reply_msg_id      = $(this).attr("msg_id"); 
        reply_attachment  = $(this).attr("attachment"); 
        reply_sender      = $(this).attr("sender");    
        reply_message     = $(this).attr("message"); 
        
        $(".reply_name").html(reply_sender);
        $(".reply_text").html(reply_message);
        $(".reply_img").attr("src",reply_attachment);
        
        if(reply_attachment == "null"){
            $(".reply_img").hide();
        }else{
            $(".reply_img").show();
        }        
               
        if(reply_to == null){
            $(".chat-messages").height($(".chat-messages").height()-54);
            $(".reply_container").show();
        }
        reply_to    = reply_msg_id;
        $(".message_input").focus();
		
		
		//close the edit		
		edit_id = null;
		$(".edit_buttons").hide();
        $(".msg_buttons").fadeIn();
		$(".message_input").val("");
		
		
		
	});  
    
    $(document).delegate(".msg_delete","click",function(){
		var msg_id      = $(this).attr("msg_id"); 
        var x = confirm("are you sure you want to delete this message?");
        if(x){
            del_id = msg_id;
                     
            $.get("../app_services/delete.php?del_id="+del_id,function(data){
				data = $.parseJSON(data);
                if(data.error == "false"){
                    var message = data.message;
                    //done in message receive function
                    /*
                    var target  = "#msg_i_"+data.del_id;                                            
                    $(target).children("div").children("p.bubble_sent").css("background","#d64635");        
                    
                    setTimeout(function(){
                        $(target).fadeOut(function(){
                            $(target).remove();
                        });
                    },2000); 
                    */
                                        
                }else{
                    alert(data.error);
                }
			}); 
            
            del_id = null
            
        }
	});  
    
    $(document).delegate(".msg_edit","click",function(){
		var msg_id      = $(this).attr("msg_id"); 
        edit_id = msg_id;//global
        var message     = $("#msg_i_"+msg_id).find(".text_message").text();        
        $(".message_input").val(message);
        $(".message_input").focus();          
        $(".msg_buttons").hide();
        $(".edit_buttons").fadeIn();
		
		//close attachment
		$('#form_attachment')[0].reset();						
		$("#attachment_pic, .attachment_close").hide();
		
		//close reply
		if(reply_to != null){
			reply_to = null;
			$(".chat-messages").height($(".chat-messages").height()+54);
			$(".reply_container").hide();
		}
	}); 
    
    $(document).delegate(".reply_close","click",function(){
		reply_to = null;
        $(".chat-messages").height($(".chat-messages").height()+54);
        $(".reply_container").hide();   
		//also done in edit start function		
	});

	$(document).delegate(".msg_info","click",function(){
		forward_msg_id = $(this).attr("msg_id");        
        
        $("#msg_info_data .load-sm").show();
        $("#msg_info_data .table").hide();        
        
        $.get("../app_services/msg_info.php?msg_id="+forward_msg_id,function(data){
            data = $.parseJSON(data);             
            $("#msg_info_data .load-sm").hide(0,function(){
                $("#msg_info_data .table").fadeIn();
            });           
            
            $("#msg_info_data .sent").html(data.sent);
            $("#msg_info_data .delivered").html(data.delivered);
            $("#msg_info_data .seen").html(data.seen);            
        });
	});	
	
	

	$(document).delegate(".btn_forward","click",function(){
		var this_	= $(this);		
		var ch_id 	= $(this_).attr("ch_id");
		$(this_).children("i").removeClass("fa-share");
		
		$(this_).children("i").addClass("fa-circle-o-notch fa-spin");
		$.get("../app_services/forward.php?ch_id="+ch_id+"&msg_id="+forward_msg_id,function(response){			
			response = $.parseJSON(response); //private
			//if(response.status == "SUCCESS"){                                    
			$(".btn_forward i").removeClass("fa-circle-o-notch fa-spin").addClass("fa-check");
			//}else{
			//	$(".btn_forward i").removeClass("fa-circle-o-notch fa-spin").addClass("fa-share");				
			//	alert(response.id);
			//} 
		});  
	});	
	
   
   //selection END
   
   $("#users_here").delegate(".user_ready","click",function(){    
		$(".admin_help").hide();  
		$(".new_group_input").hide();
        
		var ch_id = $(this).attr("ch_id"); 
		current_ch_id       = ch_id;//global
        
        $(".user_ready[ch_id='"+ch_id+"']").find(".ch_status").empty();
                
        if(ch_id.substr(0,6) == "group_"){
            //it is a group
            var group_id = ch_id.substr(6,ch_id.length);            
            $(".group_info").show();
            $(".group_info").attr("group_id",group_id);            
            $(".user_href").attr("href","javascript:;"); 
            refresh_group_info(group_id);
        }else{
            $(".group_info").hide();
            $(".user_href").attr("href","employee-profile.php?id="+ch_id);
            //$(".group_desc").hide();
        }
        
		if($(".chat-messages[ch_id='"+ch_id+"']").length == 0){                        
			$(".chat-messages-container").append("<div class='chat-messages' ch_id='"+ch_id+"'><div class='col-md-12'><ul class='media-list current_chat'></ul></div></div>");
			//ui height
			$(".chat-messages").css("height",new_height+"px");
		}			
		
		
		
		//highlight
		$(".chat-active").removeClass("chat-active");                    
		$(this).parent().addClass("chat-active");
		
		//if unread
		if($(this).parent().hasClass("ch_unread")){
			$(this).parent().removeClass("ch_unread");
			message_callback.push(ch_id+":seen");	
			$.get("../app_services/message_callback.php?data="+JSON.stringify(message_callback)+"&type=chat",function(){					
			});
			message_callback = [];
		}		
		unread[ch_id] = 0;
		total_noti(unread);		
		
		//show data
		$(".chat-heading, .chat-footer").fadeIn("fast");
		$(".chat-messages").hide();
		$(".chat-messages[ch_id='"+ch_id+"']").fadeIn("fast");
				
		var user_dp     = $("#users_here").children("div[ch_id='"+ch_id+"']").find(".ch_photo").attr("src");
		var user_name   = $("#users_here").children("div[ch_id='"+ch_id+"']").find(".user_name").text();
        var user_active = $("#users_here").children("div[ch_id='"+ch_id+"']").find(".inbox_type").attr("title");
        
        $(".group_desc").empty();
        setTimeout(function(){
          $(".group_desc").html(user_active).hide().slideDown();
        },250);

		
		$("#chat_profile_pic, .chat_name").hide(0,function(){			
            $("#chat_profile_pic").attr("src",user_dp);   
			
            if(user_name.length > 45){
                user_name = user_name.substr(0,45)+"...";
            }
            
            $(".chat_name").html(user_name);
			$("#chat_profile_pic, .chat_name").fadeIn();
		});	
        
        
        var chat_parent     = $(".chat-messages[ch_id='"+ch_id+"']");    
        $(chat_parent).scrollTop($(chat_parent)[0].scrollHeight); 
   });
   
   
   
   var refresh_group_info = function(group_id){
        $.get("../app_services/group_details.php?g_id="+group_id,function(data){
            data = $.parseJSON(data);
            $(".group_pic_status").slideUp();
            $(".group_name").val(data.name);            
            $(".group_picture").attr("src",data.picture);
            $(".group_description").val(data.description);
            if(data.description != ""){
                $(".group_desc").html("<span data-toggle='tooltip' data-placement='bottom' title='Group Description'><i class='fa fa-info'></i> "+data.description+"</span>").fadeIn();
            }else{
                $(".group_desc").html("");
            }            
            $(".group_participants").empty();
            $(".group_participants").append("<li class='list-group-item active'>Participants</li>");
            
            //show all users
            $("#group_participants_pop_body").children(".ch").show();
                        
            $(data.participant).each(function(key,value){
                $(".group_participants").append("<li class='list-group-item'><img class='group_participants_img' src='"+value.picture+"' />"+value.name+" <button class='btn btn-xs btn-danger btn-circle pull-right btn_remove_from_group' ch_id='"+value.id+"' style='margin-top:10px !important;' ><i class='fa fa-times'></i></button></li>");
                
                //hide already added users
                $("#group_participants_pop_body").children(".ch[ch_id='"+value.id+"']").hide();
            });
            $(".group_participants").append("<li class='list-group-item'><a data-toggle='modal' href='#group_participants_pop' class=btn btn-xs btn-block open_group_participants_pop'><i class='fa fa-plus'></i> Add Participants</a></li>");
            $(".group_info_loaded").fadeIn();
            $(".group_info_loading").hide();
        });
   }
   
   $(".group_info").click(function(){
        var group_id = $(this).attr("group_id");
        global_group_id = group_id;
        
        $(".group_info_loading").show();
        $(".group_info_loaded").hide();
        
        refresh_group_info(group_id);        
   });
   
   
   $(document).delegate(".btn_add_to_group","click",function(){
        var ch_id = $(this).attr("ch_id"); 
        $.get("../app_services/add_to_group.php?group_id="+global_group_id+"&ch_id="+ch_id,function(data){
            refresh_group_info(global_group_id);
        });
        var this_ = $(this);
        
        $(this_).html("<i class='fa fa-check'></i> Added!");
        setTimeout(function(){
            $(this_).parent().parent().parent().parent().slideUp();
        },1000);
   }); 
   
   $(document).delegate(".btn_remove_from_group","click",function(){
        var this_ = $(this);
        var ch_id = $(this_).attr("ch_id"); 
        $(this_).html("<i class='fa fa-circle-o-notch fa-spin'></i>");       
        $.get("../app_services/remove_from_group.php?group_id="+global_group_id+"&ch_id="+ch_id,function(data){
            refresh_group_info(global_group_id);
            $(this_).parent().slideUp();
        });        
   });
   
   $(".save_group_info").click(function(){
        var group_name          = $(".group_name").val();    
        var group_description   = $(".group_description").val();
        if(group_name.length == 0){
            alert("Please enter a group name!");
        }else{            
            
            $(".save_group_info").html("<i class='fa fa-circle-o-notch fa-spin'></i> Saving..");
            
            $.get("../app_services/update_group.php?name="+group_name+"&desc="+group_description+"&group_id="+global_group_id,function(data){
                $(".save_group_info").html("<i class='fa fa-check'></i> Saved!");
                setTimeout(function(){
                   $(".save_group_info").html("<i class='fa fa-save'></i> Save"); 
                },2000);
            });
        }
   });
   
   $("#search_users").keyup(function(){                    
		search_users();                    
   });               
   $("#search_users").blur(function(){  
		setTimeout(function(){
			$("#search_users").val("");
			search_users();     
		},1000);                     
   });
   
   $(".message_input").keyup(function(e){
		if (e.keyCode == 13 && !e.shiftKey) {
			e.preventDefault();
			$("#send_msg").submit();
		}
   });
   
   $(".attachment_close").click(function(){
		$('#form_attachment')[0].reset();						
		$("#attachment_pic, .attachment_close").hide();
   });
   
   $(".edit_cancel").click(function(){
		edit_id = null;
		$(".edit_buttons").hide();
        $(".msg_buttons").fadeIn();
		$(".message_input").val("");		
   });   
   
   function send_message(current_ch_id,message,url,size,msg_i,reply_to,users_split){
        $.get("../app_services/reply.php?ch_id="+current_ch_id+"&message="+message+"&msg_i="+msg_i+"&reply_to="+reply_to+"&users_split="+users_split+"&url="+url+"&size="+size,function(response){
            response = $.parseJSON(response); //private
            if(response.status == "SUCCESS"){  
               
            	$("#msg_i_temp"+response.msg_i).find(".message_attr").html("<i class='text-muted fa fa-check-circle-o medium_i'></i> just now");
            	$("#msg_i_temp"+response.msg_i).children("div").children(".opt_left").children("a").attr("msg_id",response.id);					
            	$("#msg_i_temp"+response.msg_i).children("div").children(".opt_left").fadeIn();
            	
            	$("#msg_i_temp"+response.msg_i).attr("id","msg_i_"+response.id);	
            	$("#msg_i_temp"+response.msg_i).find(".bubble_sent").attr("data-mid","msg_i_"+response.id);	
            	
            }else{
            	$("#my_notification").html("<p><strong>Error: </strong> "+response.id+"</p>").fadeIn();                                    
            	setTimeout(function(){
            		$("#my_notification").fadeOut();
            	},5000);
            }
        });
   }
   
   var msg_i = 0;
   $("#send_msg").submit(function(e){
		e.preventDefault(); 
  
        if(current_ch_id != null){        		              
			var message = $.trim($(".message_input").val());
            
			if(message.length > 0 || $("input[name='attachment']").val().length > 0){
				if(edit_id == null){
                    msg_i++; 
    				
                    if(current_ch_id == "_temp_new_group_"){
                        users_split       = $("#multi-append").val();                        
                        try{
                            if(users_split.length == 0){
                                alert("Please select a user!");
                                return false;
                            }else if(users_split.length == 1){
                                current_ch_id = users_split[0];
                                $(".user_ready[ch_id='"+current_ch_id+"']").click();
                            } 
                        }catch(e){
                            alert("Please select a user!");
                            return false;
                        } 
						
						wait_for_new_group	= true; 
                        $(".new_group_input").hide();
						
						var ch_id         	= current_ch_id;
						var ch_fullname   	= $("#multi-append option:selected").map(function(){ return this.text; }).get().join(", ");
						var ch_photo      	= default_group_dp;                             
						var ch_type       	= "";
						var ch_email      	= "";
						var ch_phone      	= "";
                        var chat_type     	= "group";
                        var ch_description	= "";
                        var ch_badge 		= "<span class='hide-at-select inbox_type badge badge-warning'><i class='fa fa-users'></i></span>";		
                        						
						var append_2    = "<div class='user_dp' style='padding:0'><img class='hide-at-select todo-userpic pull-left ch_photo' alt='' />"+ch_badge+"<span class='select_del_bulk'><i class='fa fa-2x fa-square-o'></i></span></div>";
						var append_31   = "<div class='number_for_search' style='display: none;'>"+ch_email+" "+ch_phone+"</div>";
						var append_32   = "<div class='todo-tasklist-item-title'><span class='user_name ch_fullname'></span><span class='ch_date_time'></span></div>";
						var append_33   = "<div class='todo-tasklist-item-text'><span class='ch_msg'><span class='badge badge-default'>No Messages</span></span><span class='ch_status' style='display:none'></span></div>";
						var append_34   = "";                                        
						var append_3    = "<div class='user_ready' ch_id='"+ch_id+"'>"+append_31+append_32+append_33+append_34+"</div>";
						$("#users_here").append("<div ch_id='"+ch_id+"' class='ch user_search_box todo-tasklist-item'>"+"<div class='timestamp hidden'>"+(new Date().getTime())+"</div>"+append_2+append_3+"</div>");
						$(".ch[ch_id='"+ch_id+"']").find(".ch_photo").attr("src",ch_photo);
						$(".ch[ch_id='"+ch_id+"']").find(".ch_fullname").text(ch_fullname);
						
						//messsage
						$(".ch[ch_id='"+ch_id+"']").find(".timestamp").html("Just now");
						$(".ch[ch_id='"+ch_id+"']").find(".ch_msg").html(message).show();  									
						$(".ch[ch_id='"+ch_id+"']").find(".ch_date_time").html("<i class='fa fa-check-circle-o medium_i'></i> Just now").show();
						
						//sort
						sortUsingNestedText($('#users_here'), ".ch", ".timestamp");
						
						//empty tags                                             
                        $("#multi-append").val();  
                        $("#multi-append option:selected").removeAttr("selected");
                        $("#multi-append option:selected").prop("selected", false);
						
						//append message window
						$(".chat-messages-container").append("<div class='chat-messages' ch_id='"+ch_id+"'><div class='col-md-12'><ul class='media-list current_chat'></ul></div></div>");
						
						//set height og window
						$(".chat-messages").css("height",new_height+"px");
						
						$(".user_ready[ch_id='"+ch_id+"']").click();						
                    }
                    
                    var d = new Date();
                    var months = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
                    if(d.getHours() <= 12){
                    	var am_pm = "AM";
                        var hours = d.getHours();
                    }else{
                    	var am_pm = "PM";
                        var hours = d.getHours()-12;
                    }
                    
                    var datetime_now = months[d.getMonth()]+" "+d.getDate()+", "+d.getFullYear()+" "+hours+":"+d.getMinutes()+" "+am_pm;
                    
                    var reply_ui    = "";
                    var edited      = "";
                    
                    if(reply_to != null){ 
                        var reply_id    = reply_msg_id;
                        var reply_name  = reply_sender;
                        var reply_text  = reply_message;
                        var reply_img   = reply_attachment;
                        var reply_image_ui = "";
                        if(reply_img != "null"){
                            reply_image_ui = '<span class="pull-right">'+
                                    '<img class="in_msg_reply_img" src="'+reply_img+'" alt="Image" />'+
                                '</span>';
                        }                                    
                        reply_ui = '<a href="#msg_i_'+reply_id+'" class="im_msg_reply_content">'+
                                        '<span class="pull-left im_msg_reply_content_text">'+
                                            '<span class="in_msg_reply_name">'+reply_name+'</span>'+
                                            '<span class="in_msg_reply_text">'+reply_text+'</span>'+
                                        '</span>'+
                                        reply_image_ui+
                                        '<span class="clearfix"></span>'+                                                                         
                                    '</a>';
                                    
                                    
                    }
                    
                    var group_message_sender = "";
                    if(current_ch_id.substr(0,6) == "group_"){//group						
                        //group_message_sender = "<strong>You:</strong> ";          
                    }
					
                    var attachment_preview  = "";
					if($("input[name='attachment']").val().length > 0){                        
				        
                        //gathering attachment_info start
                        var attachment_info             = {};                        
                        attachment_info['ch_id']        = current_ch_id;
                        attachment_info['message']      = message;
                        attachment_info['msg_i']        = msg_i;
                        attachment_info['reply_to']     = reply_to;
                        attachment_info['users_split']  = users_split;
                        attachment_info['type']         = current_attachment_type;
                        $("input[name='attachment_info']").val(JSON.stringify(attachment_info));
                        //gathering attachment_info end
                        
                        //now uploading attachment start
                        $('#form_attachment').submit();
                        $('#form_attachment')[0].reset();
                        //uploading attachment end
                                        
                        //now UI part start
						$("#attachment_pic, .attachment_close").hide(); 
                        var attachment_pic      = $("#attachment_pic").attr("src");                        
                        
                        
                        
                        if(current_attachment_type == "image"){
                            attachment_preview		= "<span class='attachment_preview'><a href='javascript:;' class='message_photo_box'>"+									    
							"<img role='button' data-toggle='modal' href='#message_photo' class='message_photo' src='"+attachment_pic+"' alt='Photo' />"+
							"</a></span>";
                        }else if(current_attachment_type == "video"){
                            attachment_preview		= "<span class='attachment_preview'><video class='message_photo' controls>"+									    
							"<source src='' type='video/mp4'>"+
							"Your browser does not support the video tag."+
							"</video></span>";	
                        }else if(current_attachment_type == "zip"){
                            attachment_preview		= "<span class='attachment_preview'><a class='msg_file' href='javascript:;'>"+
                            "<i class='fa fa-circle-o-notch fa-spin fa-2x'></i> uploading zip file.. </a></span>";
                        }else if(current_attachment_type == "pdf"){
                            attachment_preview		= "<span class='attachment_preview'><a class='msg_file' href='javascript:;'>"+
                            "<i class='fa fa-circle-o-notch fa-spin fa-2x'></i> uploading pdf.. </a></span>";
                        }else if(current_attachment_type == "audio"){
                            attachment_preview		= "<span class='attachment_preview'><audio controls>"+									    
							"<source src='' type='audio/mp4'>"+
							"Your browser does not support the audio tag."+
							"</audio></span>";	
                        }else if(current_attachment_type == "unknown"){                            
                            attachment_preview		= "<span class='attachment_preview'><a class='msg_file' href='javascript:;'>"+
                            "<i class='fa fa-circle-o-notch fa-spin fa-2x'></i> uploading file.. </a></span>";
                        }                        
                        //now UI part end                        						                  
					}else{ //send message
                        var url     = "";
                        var size    = "";
                        send_message(current_ch_id,message,url,size,msg_i,reply_to,users_split);
					}
                    
                    var temp_message = 
						"<li id='msg_i_temp"+msg_i+"' class='media'>"+
							"<div class='media-body todo-comment'>"+                            							
								"<p data-toggle='tooltip' data-placement='left' title='"+datetime_now+"' class='bubble_sent'>"+
									reply_ui+
									group_message_sender+
									'<span class="text_message">'+edited+attachment_preview+message+'</span>'+
									"<span class='message_attr'>"+
										 "<i class='text-muted fa fa-circle-o medium_i'></i> sending.."+
									"</span>"+
								"</p>"+  
								"<p class='opt_left' style='display:none' data-toggle='popover' data-time='"+datetime_now+"' data-timestamp='"+(new Date().getTime())+"' data-mid='' class='bubble_sent'>"+
									"<a data-message='"+message+"' class='popover_button' href='javascript:;'><i class='fa fa-ellipsis-v'></i></a><br />"+
								 "</p>"+     							
							"</div>"+
							"<div class='group_delivered_seen'></div>"+ 
						"</li>";
					   
                       
                       
					$(".chat-messages[ch_id='"+current_ch_id+"'] .current_chat").append(temp_message);                              
					$(".chat-messages[ch_id='"+current_ch_id+"']").scrollTop($(".chat-messages[ch_id='"+current_ch_id+"']")[0].scrollHeight);
			     
                    setTimeout(function(){
                        //yahan
                        $(".user_ready[ch_id='"+current_ch_id+"']").find(".ch_msg").html("you: "+message);
                        $(".user_ready[ch_id='"+current_ch_id+"']").find(".ch_status").html("<span><i class='fa fa-check-circle-o medium_i'></i></span>").show();

                    },100);
                    
                    
                 
                 }else{                
                    $(".edit_buttons").hide();
                    $(".msg_buttons").fadeIn();                     
                             
                    $.get("../app_services/edit.php?edit_id="+edit_id+"&message="+message,function(data){
    					data = $.parseJSON(data);
                        if(data.error == "false"){
                            var message = data.message;
                            var edit_id = data.edit_id;
                            
                            //$("#msg_i_"+edit_id+" div .bubble_sent .text_message").html("<i class='fa fa-pencil-square-o'></i> "+message); 
                            $("#msg_i_"+edit_id).find(".popover_button").attr("data-message",message);  
                            
                            var target = "#msg_i_"+edit_id;
                            var initial_background = $(target).children("div").children("p.bubble_sent").css("background");
                            $(target).children("div").children("p.bubble_sent").css("background","#F1C40F");
                            setTimeout(function(){
                                $(target).children("div").children("p.bubble_sent").css("background",initial_background);
                            },2000);          
                        }else{
                            alert(data.error);
                        }
    				}); 
                    edit_id = null;
                }
                
                //clear the reply
                if(reply_to != null){
                    reply_to = null;
                    $(".chat-messages").height($(".chat-messages").height()+54);
                    $(".reply_container").hide(); 
                }                  
				
				$(".message_input").val("");     
			}
		}                
   });
   
   $("textarea[name='e_user_address']").keyup(function(){
		$("select[name='address_list']").val("");
   });
	   
	$("#form input").keyup(function(e){
		if (e.keyCode == 13 && !e.shiftKey) {
			return false;
		}
	});
	$("#form").submit(function(e){
		e.preventDefault();
	});                
	//attachment
	$(".upload_attachment").click(function(){
		$("input[name='attachment']").click();
	});                                
	$("input[name='attachment']").change(function(){		
		var file 			= $(this).val().toLowerCase();		
		
		var extension = file.substr((file.lastIndexOf('.') +1));
		setTimeout(function(){
			switch(extension) {
				case 'jpg':
				case 'jpeg':
				case 'tiff':
				case 'png':
				case 'bmp':
				case 'gif':
					//showing actual picture: readURL(input,elem);
					current_attachment_type = "image";
				break; 
				case 'mp4':
				case 'mkv':
				case 'avi':
				case 'wmv': 
					$("#attachment_pic").attr("src",file_video);
					current_attachment_type = "video";
				break; 
				case 'zip':
					$("#attachment_pic").attr("src",file_zip);
					current_attachment_type = "zip";
				break;
				case 'pdf':
					$("#attachment_pic").attr("src",file_pdf);
					current_attachment_type = "pdf";
				break;
				case 'mp3':
				case 'wav':
					$("#attachment_pic").attr("src",file_audio);
					current_attachment_type = "audio";
				break;
				default:
					$("#attachment_pic").attr("src",file_file);
					current_attachment_type = "unknown";
			}
		},1000);
		
		$(".attachment_close").fadeIn();
		
		
		
		//close the edit		
		edit_id = null;
		$(".edit_buttons").hide();
        $(".msg_buttons").fadeIn();
		
	});
	$('#form_attachment').on('submit',(function(e) {
		e.preventDefault();
		if(current_ch_id != null){
            var formData = new FormData(this);            
            
    		$.ajax({
    			type:'POST',
    			url: base_url+"app_services/upload.php",//yahan
    			data:formData,
    			cache:false,
    			contentType: false,
    			processData: false,
    			success:function(data){    			    
    				console.log(data);
                    
                    data   = $.parseJSON(data); 
                    
                    //data.type                    
                    if(data.error != ""){ //error is not empty
    					alert("Upload Error:"+ data.error);
    				}else{
                        send_message(data.ch_id,data.message,data.url,data.size,data.msg_i,data.reply_to,data.users_split);
    				}                         
    			},
    			error: function(data){
    				console.log("error:"+data);
    			}
    		});            
        }        
	}));
    
    
    //group picture
	$(".group_picture").click(function(){
		$("input[name='upload_group_pic']").click();
	});                                
	$("input[name='upload_group_pic']").change(function(){
		$("#form_group_pic").submit();
		$('#form_group_pic')[0].reset();
	});
	$('#form_group_pic').on('submit',(function(e) {
		e.preventDefault();
		if(global_group_id != null){
            $(".group_pic_status").html("<i class='fa fa-refresh fa-spin'></i> Uploading..").hide().slideDown();
    		
    		var formData = new FormData(this);
    		$.ajax({
    			type:'POST',
    			url: $(this).attr('action'), 
    			data:formData,
    			cache:false,
    			contentType: false,
    			processData: false,
    			success:function(data){
    				
                    data   = $.parseJSON(data);                     
                    if(data.error != ""){ //error is not empty
    					$(".group_pic_status").html("<i class='fa fa-times'></i> "+(data.error)).slideDown();
    				}else{
    					var url = data.url; 
                        $(".group_pic_status").html("<i class='fa fa-refresh fa-spin'></i> Setting group picture..").slideDown();
                        $.get("../app_services/update_group_pic.php?group_dp="+url+"&group_id="+global_group_id,function(response){
                            $(".group_pic_status").html("<i class='fa fa-check'></i> Updated!").slideDown();
                            refresh_group_info(global_group_id);
                        });
    				}                            
    			},
    			error: function(data){
    				$(".group_pic_status").html("<i class='fa fa-times'></i> Please try later..").slideDown();
    			}
    		});
        }
	}));
    //group picture here..
	   
	$(document).delegate(".message_photo","click",function(){
		$("#photo_pop").attr("src",$(this).attr("src"));
	}); 
					
	var default_window_height = $(document).height();
	$( window ).resize(function() {
		/*if($(document).height() <= default_window_height){                        
			var window_height = $(document).height()-170;
			//var window_height = $("#main_frame").height();						
			
			$(".chat-box").css("height",window_height+"px");   
			$(".user_container").css("height",window_height+"px");
				
			var container   = $(".chat-box").height();
			var head        = $(".chat-heading").height();
			var footer      = $(".chat-footer").height();                    
			var new_height  = container-head-footer-18;                    
			//$(".chat-messages").css("height",new_height+"px");
		}*/
	});
	
	if($(document).height() <= default_window_height){                        
		//var window_height = $(document).height()-170;
		var window_height = $("#main_frame").height()-110;						
		
		$(".chat-box").css("height",window_height+"px");   
		$(".user_container").css("height",window_height+"px");
			
		var container   = $(".chat-box").height();
		var head        = $(".chat-heading").height();
		var footer      = $(".chat-footer").height();                    
		new_height  	= container-head-footer-18; 
		$(".chat-messages").css("height",new_height+"px");
	}
	
    
    /* group functionality */
        $(".create_new_group").click(function(){
            current_ch_id = "_temp_new_group_";
            
            $(".admin_help").hide(); 
    		$(".chat-active").removeClass("chat-active");	
    		$(".chat-heading").hide();       
    		$(".chat-messages").hide();
            
            if($(".chat-messages[ch_id='new_group']").length == 0){                        
    			
                var ui = '<div class="pre-text-help text-muted text-center new-group-help">'+
                            '<i class="fa-2x fa fa-users"></i>'+
                                '<p>Create new group</p>'+    
                                    '</div>';
                
                $(".chat-messages-container").append("<div class='chat-messages' ch_id='new_group'><div class='col-md-12'><ul class='media-list current_chat'><li>"+ui+"</li></ul></div></div>");
    			$(".chat-messages").css("height",new_height+"px");
    		}
    		var chat_parent     = $(".chat-messages[ch_id='new_group']");                    
    		$(chat_parent).scrollTop($(chat_parent)[0].scrollHeight);        
            $(".chat-messages[ch_id='new_group']").show();
            
            $(".chat-footer").show();    
            $(".new_group_input").show();
            $(".select2-search__field").focus();
            $(".select2-search__field").keydown();
            $(".select2-search__field").keyup();                        
        });
        
        
        $(".select2-search__field").css("width","100% !important");
        
        //show popover on hover        
        //$(document).delegate(".popover_button","mouseover",function(){
        //    if($(this).siblings(".popover").length == 0){
        //        $(".popover").hide();
        //        //$(".popover_button").removeAttr("aria-describedby")
        //        $(this).click();
        //    }
        //});
        
        //hide popover when mouse out
        /*$(document).delegate(".popover-content","mouseout",function(){
            $(this).parent().remove();
        });
        */
        
        
    /* group functionality end*/
    
    $(document).delegate('a.im_msg_reply_content',"click",function(event) {
        var target = $(this.getAttribute('href'));
        var bubble = "";
        if($(target).children("div").children("p.bubble_received").length > 0){
            bubble = "bubble_received";
        }else if($(target).children("div").children("p.bubble_sent").length > 0){
            bubble = "bubble_sent";
        }             
        var initial_background = $(target).children("div").children("p."+bubble).css("background");
        $(target).children("div").children("p."+bubble).css("background","#F1C40F");        
        setTimeout(function(){
            $(target).children("div").children("p."+bubble).css("background",initial_background);
        },2000);
    });
    
    
    //tooltip start
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]'
    });
    $('[data-toggle="tooltip"]').tooltip();
	//tooltip end
    
    $(window).resize();  
    
    
    setTimeout(function(){
        $(".create_new_group").show();
    },60000*60);
    
    
    
    //draft message    
    $(".message_input").keyup(function(){        
        //save draft
        var from_   = "<?php echo $SESS_ID; ?>";
        var to_     = current_ch_id;
        var draft   = $.trim($(this).val());
        var key     = "draft_from_"+from_+"_to_"+to_;        
        window.localStorage.setItem(key, draft);
        
        //old from is in loop
        
        //yahan
        if(draft.length > 0){
            $(".ch[ch_id='"+to_+"']").find(".ch_msg").html("<span class='text-danger'>[Draft]</span> "+draft).show();  
        }else{
            $(".ch[ch_id='"+to_+"']").find(".ch_msg").html(window.localStorage.getItem("old_from_"+from_+"_to_"+to_)).show(); 
        }
    });
    
    $(document).delegate(".user_ready","click",function(){
        $(".message_input").val("");      
        $(".attachment_close").click(); //type="button"
          
        //set
        var from_   = "<?php echo $SESS_ID; ?>";
        var to_     = $(this).attr("ch_id");
        var key     = "draft_from_"+from_+"_to_"+to_;
        $(".message_input").val(window.localStorage.getItem(key));
        
        
        setTimeout(function(){
           $(".message_input").focus(); 
        },200);
        //also set before showing new message
        //$(".ch[ch_id='"+ch_id+"']").find(".ch_msg").html(msg_in_ch).show();  
    });
    
    $("#send_msg").submit(function(e){
        e.preventDefault();
        var from_   = "<?php echo $SESS_ID; ?>";
        var to_     = current_ch_id;
        var key     = "draft_from_"+from_+"_to_"+to_;
        window.localStorage.setItem(key, "");
    });
    
    
			 
});
		
		</script>
        
    </body>
</html>