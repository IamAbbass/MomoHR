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
    
    
    $users_videos_xml      = $xml->videos->users_videos;
    $access_denied_xml     = $xml->videos->access_denied;
    $no_access_xml         = $xml->videos->no_access;
    $add_video_xml         = $xml->videos->add_video;
    $title_colon_xml       = $xml->videos->title_colon;
    $add_video_colon_xml   = $xml->videos->add_video_colon;
    $save_xml              = $xml->videos->save;
    $close_xml             = $xml->videos->close;

    
    
    
    
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_videos'] != "true"){
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
		
        <link href="assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        
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
            .my-videos{
                width:100%;
                max-width:300px;
                padding-bottom:5px;                
                height:200px;     
            }
            .hide{
                
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
											<i class="fa fa-video-camera"></i>
                                            
                                            
                                            <div data-toggle="tooltip" data-placement ="left" title="Click to select employee">
                                            <select id="single"  class="form-control select2">
                                                                                        
                                                <optgroup label="Employees">
                                                <?php
                                                  $rows = sql($DBH, "SELECT id,fullname,access_level FROM tbl_login where company_id = ? AND status = ?",
                                                  array($SESS_COMPANY_ID,"active"), "rows");

                                                  foreach($rows as $row){
                                                      $id					  = $row['id'];
                                                      $fullname			= $row['fullname'];
                                                      $access_level	= $row['access_level'];

                                                      if($SESS_ID == $id){
                                                        $access_level = "(You)";
                                                      }else if($access_level == "admin"){
                                                        $access_level = "(Admin)";
                                                      }else if($access_level == "user"){
                                                        $access_level = "(Employee)";
                                                      }

                                                      if($id == $SESS_DEVICE_ID){
                                                          echo "<option selected value='$id'>$fullname $access_level</option>";//selected
                                                      }else{
                                                          echo "<option value='$id'>$fullname $access_level</option>";
                                                      }
                                                  }

                                                  ?>
                                                </optgroup>
                                            </select>
                                            </div>
                                            
                                            
                                            <?php echo $users_videos_xml; ?>
                                            
                                        </div>	
                                            
                                        <div class="actions">
											<a href="#basic" class="btn btn-default btn-sm btn-circle" data-toggle="modal">
												<i class="fa fa-plus"></i> <?php echo $add_video_xml; ?>
											</a>
										</div>									
									</div>
                                    
                                    <div class="portlet-body">
                                   
                                       <div class="row">
                                            <?php $rows = sql($DBH,"SELECT * FROM tbl_videos where employee_id = ? ",array($SESS_DEVICE_ID),"rows"); 
                                            if(count($rows) > 0){
                                            foreach($rows as $row){ ?>
                                            <div class="col-md-3">
                                                <video class="my-videos" controls>
                                                  <source src="<?php echo $row['video']; ?>" type="video/mp4" />
                                            
                                                 
                                                </video>
                                            </div>
                                            <?php } 
                                             }
                                             else{
                                                ?>
                                                <div>
                                                    <h4 style="text-align:center;">No videos found</h4>
                                                </div>
                                             <?php
                                             }
                                             ?>
                                        </div>
								    </div>
                                    <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4 class="modal-title"><?php echo $add_video_xml; ?></h4>
                                                </div>
                                                <div class="modal-body">
                                                        <form action="exe_add_videos.php" method="POST" enctype="multipart/form-data">
                                                                <div class="row">
																	<div class="col-md-12">
																		<div class="form-group">
																			<label class="control-label"><?php echo $title_colon_xml; ?> *</label>
																			<input required type="text" name="title" class="form-control" />
																		</div>
																	</div>
                                                                 </div>   
																 
																   <div class="form-group mt-repeater">
                                                                      <div id="wrapper" >
                                                                         <label class="control-label"><?php echo $add_video_colon_xml; ?> *</label>
                                                                         <input id="file" name="file"  accept="video/*"  multiple="multiple" type="file" required /> 
                                                                      </div>
                                                                      
                                                                  </div>
									 							  <img id="image_upload_preview" class="my-videos" src="upload/video_holder.jpg" alt=""  />
                                                                   <video id="preview" class="my-videos hide"  controls  >
                                                                      <source src="" type="video/mp4"  />
                                                                      <source src="" type="video/ogg" />
                                                                    
                                                                   </video>
                                                                   <br />
																	
																
                                                                <button type="submit" class="btn blue btn-md btn-fill blue btn-square" ><i class='fa fa-check'></i><?php echo $save_xml; ?></button>
							                             </form>    
                                                
                                                
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo $close_xml; ?></button>
                                                   
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                     
                                    </div>
                                       <!-- /.modal-dialog -->
                                </div>
                            </div>
                        </div>
                    </div>
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
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script> 
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN THEME GLOBAL SCRIPTS -->
		<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
		<!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="assets/pages/scripts/table-datatables-fixedheader.min.js" type="text/javascript"></script>
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME LAYOUT SCRIPTS -->
		<script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
		<script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        
              <script>
        
        
            
     /*   $(document).ready(function() {
            
              $('#file').on('change', function(){
                
                 var file       = this.files[0];
                 var reader     = new FileReader();
                 reader.onload  = viewer.load;
                 reader.readAsDataUrl(file);
                 viewer.setProperties(file);
              });
              
              var viewer = {
                
                 load : function(e){
                   
                    $('#preview').attr('src', e.target.result);
                    
                 },
                 
                 setProperties : function(file){
                    
                    $('#filename').text(file.name);
                    $('#filetype').text(file.type);
                    $('#filesize').text(Math.round(file.size/1024); 
                 }
                
                
              }
         
         });**/
             function readURL(input) {
               if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  var icon   = "upload/loading_icon.gif";
                  reader.onload = function (e) {
                      	setTimeout(function(){
                      	 $("#image_upload_preview").hide();
                         $("#preview").removeClass("hide");
                         $("#preview").attr('src', e.target.result);
                        },1000);
                      
                         $('#image_upload_preview').attr('src',icon);
                   }

                      reader.readAsDataURL(input.files[0]);
                 }
              }

              $("#file").change(function () {
                   readURL(this);
               });
        	setTimeout(function(){
                    $(".remove_after_5").slideUp();
                },5000);
          
        
        </script>
        <script>
            $(document).ready(function(){
               $("#single").change(function(){
                    var id = $(this).val();
                    window.location.href = "select_device.php?id="+id;
               }); 
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>
		<!-- END THEME LAYOUT SCRIPTS -->        
    </body>

</html>