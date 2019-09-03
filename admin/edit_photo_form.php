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
    

     $id = $_GET['id'];
     $rows = sql($DBH, "SELECT * From tbl_photos WHERE id=$id", array(), "rows");
     foreach($rows as $row){
       $title  = $row['title'];
       $img    = $row['image'];
     
       
     } 
     


    

if(isset($_POST['save'])){
    
    if(file_exists($_FILES["file"]["tmp_name"])){

        $file_name      = $_FILES["file"]["name"];
        $file_tmp_name  = $_FILES["file"]["tmp_name"];
        $file_size      = $_FILES["file"]["size"];
        $file_type      = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
  
           
                if($file_type == "png" || $file_type == "jpg" || $file_type == "jpeg"){        
                            $rand_name    = md5(uniqid(rand(), true)).".".$file_type;
                            $location     = "photos/$rand_name";
                            move_uploaded_file($file_tmp_name,$location);
                            unlink("photos/$img");
                        
                        }
    
        // else{
        //    $_SESSION['danger'] = "Sorry, only Png & Jpeg File  is allowed";
        //  }
     }
     
     else{
    //    $_SESSION['danger'] = "Please select an Image";
        $rand_name = $img;
     }
     $title = $_POST['title'];
   
     $rows = sql($DBH, "UPDATE tbl_photos SET title = '$title',
     image = '$rand_name' WHERE id=$id", array(), "rows");
      $_SESSION['info'] = "<strong>Success: </strong> Photo Updated successfully!";
      redirect($go_back);
      	
   

}




    

?>

                                                   <form  method="POST" enctype="multipart/form-data">

                                                             <div class="row">
                                                               <div class="col-md-12">
	                                                              <div class="form-group">
	                                                              <label class="control-label">Title: *</label>
                                                                  <input required type="text" name="title" value="<?php echo $title; ?>" class="form-control" />
	                                                              </div>
                                                               </div>
                                                            </div>   
                                                           <div class="form-group mt-repeater">
                                                               <div id="wrapper" >
                                                                    <label class="control-label">Add Photos: *</label>
                                                                    <input id="fileUpload" name="file"  accept="image/*"  multiple="multiple" type="file"  /> 
                                                                         
                                                               </div>
                                                 
                                                          </div>
                                                          <img id="image_upload_preview" class="my-placeholder" src="photos/<?php echo $img; ?>" alt=""  /><br />										
                                                          <button type="submit"  name="save" class="btn blue btn-md btn-fill blue btn-square" ><i class='fa fa-check'></i> Save</button>
                      
                                                    </form>                                
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
               
               
               </script>