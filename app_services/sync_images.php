<?php
	header("Access-Control-Allow-Origin: *");
 
	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
    //alibaba start
    require_once('../class_function/alibaba_cloud/autoload.php');
    use OSS\OssClient;
    use OSS\Core\OssException;
    //alibaba end
    
    
    $array = array();
    $arr   = array(); 
    $res = $_POST;
    
    
   if($_POST['image'] == 'true'){
    
        $id = $_POST['profile_id'];
                    
        $fileName = $_FILES['file']['name'];        
        
        $STH 	 		= $DBH->prepare("SELECT count(*) FROM tbl_photos where employee_id = ? and title = ?");
        $result  		= $STH->execute(array($id,$fileName));
        $count     	= $STH->fetchColumn();
    
        if($count == 0){
            
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
            $object = "attachments/".$_FILES['file']['name'];
            try{
                $result = $ossClient->uploadFile($bucket, $object, $_FILES['file']['tmp_name']);
    
                $date_time      = time($result['date']);
                $url            = $result['info']['url'];
                $size_upload    = $result['info']['size_upload'];
                $primary_ip     = $result['info']['primary_ip'];
                $content_type   = $result['oss-requestheaders']['Content-Type'];
    
                sql($DBH, "insert into tbl_alibaba
                (admin_id,url,content_type,size_upload,primary_ip,date_time)
                values (?,?,?,?,?,?);",
                array($SESS_ID,$url,$content_type,$size_upload,$primary_ip,$date_time), "rows");
    
                $arr['error']   = "";
                $arr['url']     = $url;
                $arr['size']    = $size_upload;
                $title          = $fileName; 
                
             
             $row = sql($DBH, "insert into tbl_photos
                (employee_id,title,image,time)
                values (?,?,?,?);", array($id,$title,$url,$date_time), "rows");
              
                if($row == ""){
                     $array['success'] = true;
                     $array['msg'] = "Picture successfully updated!";
                }
                else{
                    $array['error'] = false;
                    $array['msg'] = "Please try again!";
                }
            
                
               die(json_encode($array));
    
            } catch(OssException $e) {
                $arr['error']   = print_r($e->getMessage(),true);
                $arr['url']     = $url;
                die(json_encode($array));
            }
          }
          else{
                $arr['error'] = false;
                $arr['msg'] = "You already added ";
                die(json_encode($arr));
        
            } 
       
    }else{
        $u_id = $_GET['u_id'];
        
        $arr = array();
        $rows = sql($DBH, "select * from tbl_photos where employee_id = ?",array($u_id), "rows");
        foreach($rows as $row){
            $arr[] = $row['title'];
        }
        die(json_encode($arr));
    }
    
    
    
    
    
    
?>