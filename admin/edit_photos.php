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
    
    
        
    $users_photos_xml         = $xml->edit_photos->users_photos;
    $delete_xml               = $xml->edit_photos->delete;
    $edit_xml                 = $xml->edit_photos->edit;
    $close_xml                = $xml->edit_photos->close;
    $delete_all_xml           = $xml->edit_photos->delete_all;
    $access_denied_xml        = $xml->edit_photos->access_denied;
    $no_access_xml            = $xml->edit_photos->no_access;
    $confirm_delete_xml       = $xml->edit_photos->confirm_delete;
    $select_atleast_xml       = $xml->edit_photos->select_atleast;
    
	
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
            .middle {
               transition: .5s ease;
               opacity: 0;
               position: absolute;
               top: 50%;
               left: 50%;
               transform: translate(-50%, -50%);
               -ms-transform: translate(-50%, -50%);
               text-align: center;
               opacity:1;
             }
   
            
               .my-photos {
                 opacity: 0.6;
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
											<i class="fa fa-picture-o"></i> <?php echo $users_photos_xml; ?> </div>	
                                            
                                            <div class="actions">
										
                                             <button type="button" name="btn_delete" id="btn_delete" class="btn btn-default btn-sm btn-circle"><?php echo $delete_all_xml; ?></button>
										</div>									
									</div>
								    <div class="portlet-body">
                              
                                        <div class="row">
                                            <?php 
                                            $rows = sql($DBH,"SELECT * FROM tbl_photos",array(),"rows"); 
                                            foreach($rows as $row){ ?>
                                            <div class="col-md-3 hvr" id="<?php echo $row['id']; ?>">
                                                <a rel="example_group"  href="photos/<?php echo $row['image']; ?>">
                                                 <div id="myImg" class="my-photos" style="background-image: url('photos/<?php echo $row['image']; ?>');"></div>
                                                </a>
                                                <div class="middle">
                                                   <a class="btn btn-warning btn-xs btn-circle openPopup"  data-href="edit_photo_form.php?id=<?php echo $row['id']; ?>" >
									                    <i class="fa fa-pencil"></i> <?php echo $edit_xml ?>
									               </a>
                                                    <a href="exe_del_photos.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-xs btn-circle del_photo">
									                    <i class="fa fa-trash"></i> <?php echo $delete_xml; ?>
									               </a>
                                                    <input type="checkbox" name="photo_id[]" class="delete_photo" value="<?php echo $row['id']; ?>" />
                                                </div>
                                            </div>
                                               
                                          
                                            <?php } ?>
                                        </div>
                                       
                                     
                                    </div>
								</div>
							</div>
                             <!--Edit Form Modal Start-->
                                 
                            <div class="modal fade" id="basic" role="basic">
                                <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $edit_xml ?></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $close_xml; ?></button>
            </div>
        </div>
      
    </div>
</div>
                             
                             <!--Edit Form Modal End -->
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
        
          <!-- Fancy Box Script  Start-->
    
       
  

	
        <!-- Fancy Box Script  End-->
         <script>
      
         
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
			        	var x = confirm("<?php echo $confirm_delete_xml; ?>");				
			          	if(!x){
				        	e.preventDefault();
			       	     }
		            	});
            
            	    setTimeout(function(){
                     $(".remove_after_5").slideUp();
                    },5000);
            
            
                  });
                  
                  $(document).ready(function(){
                          
                            
                           $('#btn_delete').click(function(){
  
                             if(confirm("<?php echo $confirm_delete_xml; ?>"))
                                   {
                                       var id = [];
   
                                       $(':checkbox:checked').each(function(i){
                                       id[i] = $(this).val();
                                   });
   
                             if(id.length === 0) //tell you if the array is empty
                                   {
                                      alert("<?php echo $select_atleast_xml; ?>");
                                   }
                             else
                                   {
                                     $.ajax({
                                             url:'exe_multi_delete_photos.php',
                                             type:'POST',
                                             data:{
                                                  id: id
                                                  },
                                             success:function()
                                             {
                                                for(var i=0; i<id.length; i++)
                                                   {
                                                       $('div.hvr#'+id[i]+'').css('background-color', '#ccc');
                                                       $('div.hvr#'+id[i]+'').fadeOut('slow');
                                                   }
                                             }
     
                                           });
                                     }
   
                                   }
                              else
                                   {
                                     return false;
                                   }
                                });
 
                            });

                          $(document).ready(function(){
                              $('.openPopup').on('click', function(){
                                   var dataURL = $(this).attr('data-href');
                                   $('.modal-body').load(dataURL, function(){
                                        $('#basic').modal('show');
                                         });
                                       }); 
                                     });
                                       
               
        </script>
        
     
       
    
        
       
		<!-- END THEME LAYOUT SCRIPTS -->        
    </body>

</html>