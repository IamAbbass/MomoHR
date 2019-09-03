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
    
    
    $users_photos_xml         = $xml->photos->users_photos;
    $edit_xml                 = $xml->photos->edit;
    $access_denied_xml        = $xml->photos->access_denied;
    $no_access_xml            = $xml->photos->no_access;
    $add_photos_xml           = $xml->photos->add_photos;
    $title_colon_xml          = $xml->photos->title_colon;
    $add_photos_colon_xml     = $xml->photos->add_photos_colon;
    $save_xml                 = $xml->photos->save;
    $close_xml                = $xml->photos->close;
    
    
	
	if($SESS_ACCESS_LEVEL == "root admin"){
		//allow
	}else if($SESS_ACCESS_LEVEL == "admin"){
		if($perm['perm_photos'] != "true"){
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
        <link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
      
        
        
        <!-- END THEME LAYOUT STYLES -->
        <style>            
			
            .my-photos{
                width:100%;
                max-width:500px;
                padding-bottom:5px;                
                background-repeat: no-repeat;         
                background-position: center; 
                background-size:contain;
                height:200px;      
            }                        
            .thumb-image{
               float:left;
               width:50%;
               position:relative;
               padding:5px;
               height:200px;
               
            }
             .my-placeholder{
                width:100%;
                max-width:300px;
                padding-bottom:5px;                
                height:200px;     
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
											<i class="fa fa-picture-o"></i> 
                                            
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
                                            
                                            
                                            <?php echo $users_photos_xml; ?>
                                        </div>	
                                            
                                        <div class="actions">
											<a href="#basic" class="btn btn-default btn-sm btn-circle" data-toggle="modal">
												<i class="fa fa-plus"></i> <?php echo $add_photos_xml; ?>
											</a>
          	                                <a href="edit_photos.php" class="btn btn-default btn-sm btn-circle" >
												<i class="fa fa-pencil"></i> <?php echo $edit_xml; ?>
											</a>
										</div>									
									</div>
								    <div class="portlet-body">
                              
                                        <div class="row">
                                            <?php 
                                            
                                            $rows = sql($DBH,"SELECT * FROM tbl_photos where employee_id = ?",array($SESS_DEVICE_ID),"rows");
                                            if(count($rows) > 0){
                                            foreach($rows as $row){ ?>
                                            <div class="col-md-3 hvr">
                                               
                                                    <a rel="example_group"  href="<?php echo $row['image']; ?>">
                                                     <div id="myImg" class="my-photos" style="background-image: url('<?php echo $row['image']; ?>');"></div>
                                                    </a>
                                            </div>
                                           
                                          
                                            <?php 
                                                    }
                                                }
                                            else{
                                                ?>
                                                <div>
                                                    <h4 style="text-align:center;">No pictures found</h4>
                                                </div>
                                            <?php
                                            }
                                             ?>
                                        </div>
                                       
                                     
                                    </div>
								</div>
							</div>
                             <div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h4 class="modal-title"><?php echo $add_photos_xml; ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="exe_add_photos.php" method="POST" enctype="multipart/form-data">
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
                                                             <label class="control-label"><?php echo $add_photos_colon_xml; ?> *</label>
                                                             <input id="fileUpload" name="file"  accept="image/*"  multiple="multiple" type="file" required /> 
                                                             
                                                          </div>
                                                          
                                                          
                                                      </div>
				 							    	 <img id="image_upload_preview" class="my-placeholder" src="upload/placeholder.png" alt=""  /><br />
														
													
                                                    <button type="submit" class="btn blue btn-md btn-fill blue btn-square" ><i class='fa fa-check'></i> <?php echo $save_xml; ?></button>
				                             </form>    
                                        
                                        
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn dark btn-outline" data-dismiss="modal"><?php echo $close_xml; ?></button>
                                           
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
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
        <script src="assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script> 
		<!-- END PAGE LEVEL PLUGINS -->
		<!-- BEGIN THEME GLOBAL SCRIPTS -->
		<script src="assets/global/scripts/app.min.js" type="text/javascript"></script>
		<!-- END THEME GLOBAL SCRIPTS -->
		<!-- BEGIN PAGE LEVEL SCRIPTS -->
		<script src="assets/pages/scripts/components-date-time-pickers.min.js" type="text/javascript"></script>
        <script src="assets/pages/scripts/components-select2.min.js" type="text/javascript"></script>
		<!-- END PAGE LEVEL SCRIPTS -->
		<!-- BEGIN THEME LAYOUT SCRIPTS -->
		<script src="assets/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
		<script src="assets/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
		<script src="assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
		<!-- END THEME LAYOUT SCRIPTS --> 
        
          <!-- Fancy Box Script  Start
        <script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
        <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.pack.js"></script>
        <script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.js"></script>
	   -->
       
        <!-- Fancy Box Script  End-->
         <script>
        	
            /*
            $(document).ready(function(){
		   
                   
	           $("a[rel=example_group]").fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});     
                
                
         });
         
             function readURL(input) {
               if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  var icon   = "upload/loading_icon.gif";
                  reader.onload = function (e) {
                      	setTimeout(function(){
                         $("#image_upload_preview").attr('src', e.target.result);
                        },1000);
                      
                         $('#image_upload_preview').attr('src',icon);
                   }

                      reader.readAsDataURL(input.files[0]);
                 }
              }

              $("#fileUpload").change(function () {
                   readURL(this);
               });

     
		
 	          $(document).ready(function(){
		        	$(document).delegate(".del_photo","click",function(e){
			        	var x = confirm("Are you sure you want you delete this Photo ?");				
			          	if(!x){
				        	e.preventDefault();
			       	     }
		            	});
            
            	    setTimeout(function(){
                     $(".remove_after_5").slideUp();
                    },5000);
            
            
                  });
         
             
         
         
         */
               
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