<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');

    $input 	= json_encode($_REQUEST, true);
    sql($DBH, "INSERT into tbl_debug_app (data) values (?)", array($input), "rows");

    //validation required here: sending message to who ?

    $ch_id          = $_GET['ch_id'];
    $msg_i          = $_GET['msg_i'];
    $message        = htmlspecialchars($_GET['message']);
    $message_url    = $_GET['url'];
    $msg_url_size   = $_GET['size'];//msg url size (msg_url_size)
    $msg_reply_to   = $_GET['reply_to'];

    $u_id           = $_GET['u_id'];
    if(strlen($SESS_ID) == 0){
        $SESS_ID   = $u_id;
    }

    if($SESS_ID == 4 || $SESS_ID == 5){
            //die("");
        }

    $arr = array();

    $image_ext  = array("jpg","jpeg","tiff","gif","bmp","png");
	$audio_ext  = array("mp3","wav");
    $video_ext  = array("mp4","avi","wmv");
    $pdf_ext    = array("pdf");
    $zip_ext    = array("zip");

    if(strlen($message_url) > 0){
        $ext = strtolower(substr($message_url,strrpos($message_url,".")+1,strlen($message_url))) ;


        if(in_array($ext,$image_ext)){
            $message_type = "image";
            if(strlen($message) == 0){
                $message = " ";
            }
        }else if(in_array($ext,$video_ext)){
            $message_type = "video";
            if(strlen($message) == 0){
                $message = " ";
            }
        }else if(in_array($ext,$audio_ext)){
            $message_type = "audio";
            if(strlen($message) == 0){
                $message = " ";
            }
        }else if(in_array($ext,$zip_ext)){
            $message_type = "zip";
            if(strlen($message) == 0){
                $message = " ";
            }
        }else if(in_array($ext,$pdf_ext)){
            $message_type = "pdf";
            if(strlen($message) == 0){
                $message = " ";
            }
        }else{
            $message_type = "unknown";
            if(strlen($message) == 0){
                $message = " ";
            }
        }
    }else{
        $message_type = "text";
    }

    $msg_from   = $SESS_ID;
    $msg_to     = $ch_id;

    $date_time_stamp    = time();
    $date_time          = date("M d, Y h:i A",$date_time_stamp);


    if($ch_id == "_temp_new_group_"){ //create new group
        $users_split = $_GET['users_split'];

        $name = array();
        $users = explode(",",$users_split);
        for($i=0; $i<=count($users); $i++){
            $user_id = $users[$i];
            $rows_user = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($user_id), "rows");
            foreach($rows_user as $row_user){
                $name[] = $row_user['fullname'];
            }
        }

        $name           = implode(", ",$name);
        $description    = $SESS_FULLNAME ." created this group at ".date("d-M-Y h:i a");

        if($SESS_COMPANY_ID == ""){
            $rows_user = sql($DBH, "SELECT company_id FROM tbl_login where id = ?", array($SESS_ID), "rows");
            foreach($rows_user as $row_user){
                $SESS_COMPANY_ID = $row_user['company_id'];
            }
        }


        sql($DBH, "insert  into tbl_groups(name,description,participant,created_by_id,company_id,update_date_time) values
        (?,?,?,?,?,?)",array($name,$description,$users_split,$SESS_ID,$SESS_COMPANY_ID,time()), "rows");

        $group_id = $DBH->lastInsertId();

        if($message_type == "text" && strlen($message) == 0){
            //no
        }else{
            sql($DBH, "insert  into tbl_message(msg_from,msg_to,msg_audience,msg_type,msg_text,msg_url,msg_url_size,msg_reply_to,sent)
            values (?,?,?,?,?,?,?,?,?)",
            array($msg_from,$group_id,"group",$message_type,$message,$message_url,$msg_url_size,$msg_reply_to,$date_time_stamp), "rows");

            $message_id = $DBH->lastInsertId();
        }


        $arr['status']      = "SUCCESS";
        $arr['date_time']   = $date_time;//no really used
        $arr['group_id']    = $group_id;
        $arr['id']          = $message_id;
        $arr['msg_i']       = $msg_i;
    }else if (preg_match("/group_/i", "$ch_id")) {
        //group message
        $group_id =  substr($ch_id,6,strlen($ch_id));

        sql($DBH, "insert  into tbl_message(msg_from,msg_to,msg_audience,msg_type,msg_text,msg_url,msg_url_size,msg_reply_to,sent)
        values (?,?,?,?,?,?,?,?,?)",
        array($msg_from,$group_id,"group",$message_type,$message,$message_url,$msg_url_size,$msg_reply_to,$date_time_stamp), "rows");
        $message_id = $DBH->lastInsertId();

        $arr['status']      = "SUCCESS";
        $arr['date_time']   = $date_time;//no really used
        $arr['group_id']    = $group_id;
        $arr['id']          = $message_id;
        $arr['msg_i']       = $msg_i;

    }else{
        //individual message

        sql($DBH, "insert  into tbl_message(msg_from,msg_to,msg_type,msg_text,msg_url,msg_url_size,msg_reply_to,sent)
        values (?,?,?,?,?,?,?,?)",
        array($msg_from,$msg_to,$message_type,$message,$message_url,$msg_url_size,$msg_reply_to,$date_time_stamp), "rows");

        $arr['status']      = "SUCCESS";
        $arr['date_time']   = $date_time;//no really used
        $arr['id']          = $DBH->lastInsertId();
        $arr['msg_i']       = $msg_i;
    }
    die(json_encode($arr, true));




?>
