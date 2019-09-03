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

    sql($DBH, "insert into tbl_debug_app(data) values (?);", array(json_encode($_REQUEST)), "rows");



    $attachment_info    = $_POST['attachment_info'];
    $arr                = json_decode($attachment_info,true);

    $check_limit = null;
    if($SESS_ACCESS_LEVEL == "root admin"){
        //$SESS_ID from session
        $check_limit = true;
    }else if($SESS_ACCESS_LEVEL == "admin"){
        //$SESS_ID from secion
        $check_limit = true;
    }else if($SESS_ACCESS_LEVEL == "user"){
        $check_limit = true;
    }else{

        $check_limit = true;//mobile user

        $SESS_ID = $arr['ch_id'];//employee or admin
        $rows = sql($DBH, "SELECT parent_id FROM tbl_login where id = ?",  array($SESS_ID), "rows");
		foreach($rows as $row){
			$parent_id = $row['parent_id'];
		}
        if(strlen($parent_id) > 0 && $parent_id != "0"){//$SESS_ID is admin
            $SESS_ID = $parent_id;
        }else{
            //$SESS_ID = $attachment_info['ch_id'];//admin
        }
    }


    /*
    if($check_limit == true){
        $rows2 = sql($DBH, "SELECT sum(size_upload) FROM tbl_alibaba where admin_id = ?", array($SESS_ID), "rows");
        foreach($rows2 as $row2){
            $cloud_storage_used   = $row2[0];
        }
        $rows2 = sql($DBH, "SELECT limit_bytes FROM tbl_alibaba_limits where admin_id = ?", array($SESS_ID), "rows");
        foreach($rows2 as $row2){
            $cloud_storage_limit  = $row2[0];
        }

        if($cloud_storage_used >= $cloud_storage_limit){
            $arr['error'] = "You have exceed your upload limit i.e [$SESS_ID] ".readableFileSize($cloud_storage_limit);
            die(json_encode($arr));
        }
    }
    */

    //web attachment
    if($SESS_ID && $_FILES["attachment"]["tmp_name"]){
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $object = "attachments/".$_FILES['attachment']['name'];
        try{
            $result = $ossClient->uploadFile($bucket, $object, $_FILES['attachment']['tmp_name']);

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
            die(json_encode($arr));

        } catch(OssException $e) {
            $arr['error']   = print_r($e->getMessage(),true);
            $arr['url']     = $url;
             sql($DBH, "insert into tbl_debug_app(data) values (?);", array(json_encode($e->getMessage())), "rows");
            die(json_encode($arr));
        }
    }else if($_POST["type"] == "voice" || $_POST["type"] == "camera"){//from app
        $msg_from   = $_POST['u_id'];
        $ch_id      = $_POST['ch_id'];



        if(substr($ch_id,0,6) == "group_"){
            $msg_to         =  substr($ch_id,6,strlen($ch_id));
            $msg_audience   = "group";
        }else{
            $msg_to         = $ch_id;
            $msg_audience   = "individual";
        }

        if($_POST["type"] == "voice"){
            $file_key       = "voice";
            $message_type   = "audio";
            $message        = " ";
        }else if($_POST["type"] == "camera"){//camera picture
            $file_key       = "camera";
            $message_type   = "image";
            $message        = " ";
        }

        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $object = "attachments/$message_type/".$_FILES[$file_key]['name'];
        try{
            $result = $ossClient->uploadFile($bucket, $object, $_FILES[$file_key]['tmp_name']);

            $date_time      = time($result['date']);
            $url            = $result['info']['url'];
            $size_upload    = $result['info']['size_upload'];
            $primary_ip     = $result['info']['primary_ip'];
            $content_type   = $result['oss-requestheaders']['Content-Type'];
            sql($DBH, "insert into tbl_alibaba
            (admin_id,url,content_type,size_upload,primary_ip,date_time)
            values (?,?,?,?,?,?);", array($SESS_ID,$url,$content_type,$size_upload,$primary_ip,$date_time), "rows");

            $arr['error']   = "";
            $arr['url']     = $url;
            $arr['size']    = $size_upload;

            $msg_url_size   = $size_upload;
            $message_url    = $url;
            $date_time_stamp = time();
            $msg_reply_to = null;

            sql($DBH, "insert  into tbl_message(msg_from,msg_to,msg_audience,msg_type,msg_text,msg_url,msg_url_size,msg_reply_to,sent)
            values (?,?,?,?,?,?,?,?,?)",
                array($msg_from,$msg_to,$msg_audience,$message_type,$message,$message_url,$msg_url_size,$msg_reply_to,$date_time_stamp), "rows");

            die(json_encode($arr));

        } catch(OssException $e) {
            $arr['error']   = print_r($e->getMessage(),true);
            sql($DBH, "insert into tbl_debug_app(data) values (?);", array(json_encode($e->getMessage())), "rows");
            $arr['url']     = $url;
            die(json_encode($arr));
        }
    }else if($_POST['type'] == "screenshot"){
        
        
        $u_id           = $_POST['u_id'];
        $screenshot     = $_FILES['shot']['name'];
        $fileName       = time().".png";
        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $object = "attachments/".$fileName;
        try{
            $result = $ossClient->uploadFile($bucket, $object, $_FILES['shot']['tmp_name']);

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
           
      
      
        /*$output_file    =

        $ifp = fopen( $output_file, 'wb' );
        $data = explode( ',', $base64_string );
        fwrite( $ifp, base64_decode( $data[ 1 ] ) );
        fclose( $ifp );


        die("NO:".$output_file);
        */
        
		$company_id = find_company_id($u_id);

        sql($DBH, "insert into tbl_screenshot(company_id,employee_id,image,date_time) values (?,?,?,?)",
      	array($company_id,$u_id,$url,time()), "rows");
        die('send');
        
       } 
       catch(OssException $e) {
            $arr['error']   = print_r($e->getMessage(),true);
            die(json_encode($array));
       }


    }



    /*

    else if($_FILES["upload_group_pic"]["tmp_name"]){

        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

        $object = "attachments/".$_FILES['upload_group_pic']['name'];

        try{

            $result = $ossClient->uploadFile($bucket, $object, $_FILES['upload_group_pic']['tmp_name']);



            $date_time      = time($result['date']);

            $url            = $result['info']['url'];

            $size_upload    = $result['info']['size_upload'];

            $primary_ip     = $result['info']['primary_ip'];

            $content_type   = $result['oss-requestheaders']['Content-Type'];

            sql($DBH, "insert into tbl_alibaba

            (admin_id,url,content_type,size_upload,primary_ip,date_time)

            values

            (?,?,?,?,?,?);",

            array($SESS_ID,$url,$content_type,$size_upload,$primary_ip,$date_time), "rows");

            $arr['error']   = "";

            $arr['url']     = $url;

            die(json_encode($arr));

        } catch(OssException $e) {

            $arr['error']   = print_r($e->getMessage(),true);

            $arr['url']     = $url;

            die(json_encode($arr));

        }

    }else if($type == "display_pic"){



        if(strlen($file_name) > 0){



            //some times files is missing the extention

            if($_FILES["file"]["type"] == "image/jpeg"){

                $rand_name	= md5(uniqid(rand(), true)).".jpg";

            }else{

                $rand_name	= md5(uniqid(rand(), true)).".".pathinfo($file_name, PATHINFO_EXTENSION);

            }



            move_uploaded_file($_FILES["file"]["tmp_name"], "../admin/upload/dp/".$rand_name);

    		$file_path = $website_url."admin/upload/dp/$rand_name";

            compress($file_path, $file_path, 20);



            sql($DBH, "UPDATE tbl_login SET photo = ?, update_at = ? WHERE id = ?",

			array($file_path,time(),$SESS_ID));

        }else{

            die("failed");

        }

    }else if($type == "display_pic_default"){

        $file_path = $website_url."admin/assets/default/default.jpg";



        sql($DBH, "UPDATE tbl_login SET photo = ?, update_at = ? WHERE id = ?",

		array($file_path,time(),$SESS_ID));



        echo $file_path;

    }else if($type == "attachment"){ //chat files/images/videos

    	if(strlen($file_name) > 0){

            //some times files is missing the extention

            if($_FILES["file"]["type"] == "image/jpeg"){

                $rand_name	= md5(uniqid(rand(), true)).".jpg";

            }else{

                $rand_name	= md5(uniqid(rand(), true)).".".pathinfo($file_name, PATHINFO_EXTENSION);

            }



    		$tmp_name 	= $_FILES["file"]["tmp_name"];

    		$location 	= "../admin/upload/attachment/$rand_name";

    		move_uploaded_file($tmp_name,$location);

    		$file_path = $website_url."admin/upload/attachment/$rand_name";

            compress($file_path, $file_path, 20);



            //send start



            $message_url    = $file_path;



            $arr = array();



            $image_ext = array("jpg","jpeg","tiff","gif","bmp","png");

        	$audio_ext = array("mp3","wav");

            $video_ext = array("mp4","avi","wmv","mkv");

            //pdf



            $ext    = strtolower(substr($message_url,strrpos($message_url,".")+1,strlen($message_url)));

            if(in_array($ext,$video_ext)){

                $message_type   = "video";

                $message        = "Video";

            }else if(in_array($ext,$audio_ext)){

                $message_type   = "audio";

                $message        = "Audio";

            }else if(in_array($ext,$image_ext)){

                $message_type   = "image";

                $message        = "Picture";

            }else{

                $message_type   = "unknown";

                $message        = "File";

            }



            $date_time_stamp    = time();

            $date_time          = date("M d, Y h:i A",$date_time_stamp);



            sql($DBH, "insert  into tbl_message(msg_from,msg_to,msg_type,msg_text,msg_url,sent)

            values (?,?,?,?,?,?)",

            array($msg_from,$msg_to,$message_type,$message,$message_url,$date_time_stamp), "rows");



            $arr['status']      = "SUCCESS";

            $arr['date_time']   = $date_time;//no really used

            $arr['id']          = $DBH->lastInsertId();

            $arr['msg_i']       = $msg_i;



            die(json_encode($arr, true));

        }else{

            die("failed");

        }

    }

    */



    //$input 	= json_encode($_REQUEST, true);

    //$SESS_ID        = $_REQUEST['id'];

    //$type           = $_REQUEST['type'];

    //$msg_from       = $_REQUEST['u_id'];

    //$msg_to         = $_REQUEST['ch_id'];

    //$file_name  = urldecode($_FILES["file"]["name"]);

    //compress($file_path, $file_path, 20);



    /*

    //$file_name  = urldecode($_FILES["upload_group_pic"]["name"]);

    $rand_name	= md5(uniqid(rand(), true)).".".pathinfo($file_name, PATHINFO_EXTENSION);

    move_uploaded_file($_FILES["upload_group_pic"]["tmp_name"], "../admin/upload/attachment/".$rand_name);

	$file_path = $website_url."admin/upload/attachment/$rand_name";

    compress($file_path, $file_path, 20);

    */



?>
