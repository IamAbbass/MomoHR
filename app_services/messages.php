<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');


    $timestamp      = $_GET['ts'];
    $timestamp_new  = $timestamp;//return

    $active_check_margin = 60*5;//5 mins

    //$STH 	 = $DBH->prepare("SELECT count(*) FROM tbl_login WHERE (email = ? OR contact = ?) AND status = ?");
    //$result  = $STH->execute(array($input,$input,"active"));
    //$count	 = $STH->fetchColumn();

    if($SESS_ACCESS_LEVEL == "root admin"){//dashboard
        //chatheads
        $rows = sql($DBH, "SELECT * FROM tbl_login where
        (access_level = ? && company_id = ?)
         AND
        (date_time > ? or update_at > ?) and id != ?",
        array("user",$SESS_COMPANY_ID,($timestamp-$active_check_margin),($timestamp-$active_check_margin),$SESS_ID), "rows");

        //groups
        $rows_groups = sql($DBH, "select * from tbl_groups where company_id = ? and update_date_time > ?",
        array($SESS_COMPANY_ID,$timestamp), "rows");

        //message
        $rows_messages = sql($DBH, "SELECT * FROM tbl_message where
    		msg_audience = ? AND
        (msg_from = ? or msg_to = ?)
	    	 AND
	    	(sent > ? or seen > ? or delivered > ? or edited_time > ? or deleted_time > ?)
	    	order by (sent) asc",
	    	array("individual",$SESS_ID,$SESS_ID,$timestamp,$timestamp,$timestamp,$timestamp,$timestamp), "rows");


        //group messages
        $rows_group_messages = sql($DBH, "SELECT * FROM tbl_message WHERE
        msg_audience = ? AND (sent > ? or seen > ? or delivered > ? or edited_time > ? or deleted_time > ?)  and
        msg_to IN(
        	SELECT id FROM tbl_groups WHERE company_id = ?)",
            array("group",$timestamp,$timestamp,$timestamp,$timestamp,$timestamp,$SESS_COMPANY_ID), "rows");


    }else if($SESS_ACCESS_LEVEL == "admin"){//dashboard
        //chatheads only user
        /*$rows = sql($DBH, "SELECT * FROM tbl_login where
        (access_level = ? && company_id = ?)
         AND
        (date_time > ? or update_at > ?)and id != ?",
        array("user",$SESS_COMPANY_ID,($timestamp-$active_check_margin),($timestamp-$active_check_margin),$SESS_ID), "rows");
        */

        //chatheads only user and admins
        $rows = sql($DBH, "SELECT * FROM tbl_login where
        (company_id = ?)
         AND
        (date_time > ? or update_at > ?)and id != ?",
        array($SESS_COMPANY_ID,($timestamp-$active_check_margin),($timestamp-$active_check_margin),$SESS_ID), "rows");
        //groups
        $rows_groups = sql($DBH, "select * from tbl_groups where company_id = ? and update_date_time > ?",
        array($SESS_COMPANY_ID,$timestamp), "rows");

        //message
        $rows_messages = sql($DBH, "SELECT * FROM tbl_message where
    	msg_audience = ? AND
        (msg_from = ? or msg_to = ?)
    	 AND
    	(sent > ? or seen > ? or delivered > ? or edited_time > ? or deleted_time > ?)
    	order by (sent) asc",
    	array("individual",$SESS_ID,$SESS_ID,$timestamp,$timestamp,$timestamp,$timestamp,$timestamp), "rows");

        //group messages
        $rows_group_messages = sql($DBH, "SELECT * FROM tbl_message WHERE
        msg_audience = ? AND (sent > ? or seen > ? or delivered > ? or edited_time > ? or deleted_time > ?) and
        msg_to IN(
        	SELECT id FROM tbl_groups WHERE company_id = ?)",
            array("group",$timestamp,$timestamp,$timestamp,$timestamp,$timestamp,$SESS_COMPANY_ID), "rows");

    }else{//mobile user
        //chatheads
        if(strlen($SESS_ID) == 0){
            $SESS_ID = $_GET['u_id'];//mobile
        }
				$SESS_COMPANY_ID = find_company_id($SESS_ID);

        if($SESS_ID == 4 || $SESS_ID == 5){
            //die("");
        }

        $rows = sql($DBH, "SELECT * FROM tbl_login where company_id = ?
      	AND
        (date_time > ? or update_at > ?) and id != ?",
        array($SESS_COMPANY_ID,($timestamp-$active_check_margin),($timestamp-$active_check_margin),$SESS_ID), "rows");

        //message
        $rows_messages = sql($DBH, "SELECT * FROM tbl_message where
        msg_audience = ? AND
        (msg_from = ? or msg_to = ?)
	 	AND
		(sent > ? or seen > ? or delivered > ? or edited_time > ? or deleted_time > ?)
		order by (sent) asc",
		array("individual",$SESS_ID,$SESS_ID,$timestamp,$timestamp,$timestamp,$timestamp,$timestamp), "rows");


        //groups chat heads
        $rows_groups = sql($DBH, "select * from tbl_groups where company_id = ? and update_date_time > ?",
        array($SESS_COMPANY_ID,$timestamp), "rows");

        //group messages
        $rows_group_messages = sql($DBH, "SELECT * FROM tbl_message WHERE
        msg_audience = ? AND (sent > ? or seen > ? or delivered > ? or edited_time > ? or deleted_time > ?)  and
        msg_to IN(
        SELECT id FROM tbl_groups WHERE company_id = ?)",
        array("group",$timestamp,$timestamp,$timestamp,$timestamp,$timestamp,$SESS_COMPANY_ID), "rows");
    }

    //update login status
    sql($DBH, "UPDATE tbl_login SET update_at = ? WHERE id = ?", array(time(),$SESS_ID), "rows");



    $arr = array();
    $i=0;
    //individual chat heads
    foreach($rows as $row){

        $arr["chatheads"][$i]["id"]          = $row['id'];
		$arr["chatheads"][$i]["fullname"]    = $row['fullname'];
        $arr["chatheads"][$i]["photo"]       = video_to_photo($row['photo']);

        $arr["chatheads"][$i]["type"]        = $row['access_level'];
        $arr["chatheads"][$i]["email"]       = $row['email'];
        $arr["chatheads"][$i]["phone"]       = $row['contact'];
        $arr["chatheads"][$i]["chat_type"]   = "individual";

        $count_signup = sql($DBH, "SELECT * FROM tbl_login_log WHERE login_id = ?", array($row['id']), "rows");

        if(count($count_signup) > 0){
            if(time()-$row['update_at'] > 60){
                $status = "inactive";
                if(ceil((time()-$row['update_at'])/60) <= 59){
                    $arr["chatheads"][$i]["last_active"] = "Active ".ceil((time()-$row['update_at'])/60)." mins ago";
                }else{
                    $arr["chatheads"][$i]["last_active"] = "Active ".my_simple_date($row['update_at']);
                }
            }else{
                $status = "active";
                $arr["chatheads"][$i]["last_active"] = "Active now";
            }
        }else{
            $status = "signout";
            $arr["chatheads"][$i]["last_active"] = "Signout";
        }



        $arr["chatheads"][$i]["login_status"]     = $status;

        if($row['update_at'] > $timestamp_new){ //becaue update time is always greater
            $timestamp_new = $row['update_at'];
        }
        if($row['date_time'] > $timestamp_new){
            $timestamp_new = $row['date_time'];
        }
        $i++;
    }

    //group chat heads
    foreach($rows_groups as $row){
        $arr["chatheads"][$i]["id"]           = "group_".$row['id'];
		$arr["chatheads"][$i]["fullname"]     = $row['name'];
        $arr["chatheads"][$i]["photo"]        = video_to_photo($row['picture']);
        $arr["chatheads"][$i]["participant"]  = $row['participant'];
        $arr["chatheads"][$i]["description"]  = $row['description'];

        $arr["chatheads"][$i]["type"]         = "";
        $arr["chatheads"][$i]["email"]        = "";
        $arr["chatheads"][$i]["phone"]        = "";

        $arr["chatheads"][$i]["chat_type"]    = "group";

        if($row['update_date_time'] > $timestamp_new){ //becaue update time is always greater
            $timestamp_new = $row['update_date_time'];
        }
        if($row['update_date_time'] > $timestamp_new){
            $timestamp_new = $row['update_date_time'];
        }
        $i++;
    }

    $i=0;
    //individual messages
    foreach($rows_messages as $row){
        $arr["messages"][$i]["id"]              = $row['id'];
        $arr["messages"][$i]["display_name"]    = $display_name;
        $arr["messages"][$i]["display_pic"]     = $display_pic;
        $arr["messages"][$i]["msg_audience"]    = $row['msg_audience'];
        if($row['msg_to'] == $SESS_ID){//I am the receiver
            $arr["messages"][$i]["received"]    = true;
            $arr["messages"][$i]["ch_id"]       = $row['msg_from'];
			$arr["messages"][$i]["mark_as_read"]= $row['mark_read_by_receiver'];
            $chat_head_id = $row['msg_from'];
        }else if($row['msg_from'] == $SESS_ID){//I am the sender
            $arr["messages"][$i]["received"]    = false;
            $arr["messages"][$i]["ch_id"]       = $row['msg_to'];
			$arr["messages"][$i]["mark_as_read"]= $row['mark_read_by_sender'];
            $chat_head_id = $row['msg_to'];
        }
        $rows_display_name_pic = sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($chat_head_id), "rows");
        foreach($rows_display_name_pic as $row_display_name_pic){
            $display_name    = $row_display_name_pic['fullname'];
            if(strpos($row_display_name_pic['photo'], "http") == false){
                $display_pic = $website_url."admin/".$row_display_name_pic['photo'];
            }else{
                $display_pic = $row_display_name_pic['photo'];
            }
        }

        $arr["messages"][$i]["display_name"]    = $display_name;
        $arr["messages"][$i]["display_pic"]     = $display_pic;
        $arr["messages"][$i]["msg_type"]        = $row['msg_type'];
        $arr["messages"][$i]["msg_text"]        = $row['msg_text'];
        $arr["messages"][$i]["msg_url"]         = $row['msg_url'];
        $arr["messages"][$i]["msg_url_size"]    = readableFileSize($row['msg_url_size']);

        if(strlen($row['msg_reply_to']) > 0){
            $reply_to = $row['msg_reply_to'];
            $arr["messages"][$i]["reply"]   = array();

            $rows_reply = sql($DBH, "SELECT msg_from,msg_text,msg_url,msg_type FROM tbl_message where id = ?", array($reply_to), "rows");
            foreach($rows_reply as $row_reply){

                $reply_from = $row_reply['msg_from'];
                if($SESS_ID == $reply_from){
                    $arr["messages"][$i]["reply"]["reply_name"] = "You:";
                }else{
                    $rows_reply_from = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($reply_from), "rows");
                    foreach($rows_reply_from as $row_reply_from){
                        $arr["messages"][$i]["reply"]["reply_name"] = $row_reply_from['fullname'].":";
                    }
                }

                $arr["messages"][$i]["reply"]["reply_id"] = $reply_to;
                $arr["messages"][$i]["reply"]["reply_text"] = $row_reply['msg_text'];

                if($row_reply['msg_type'] == "image"){
                    $arr["messages"][$i]["reply"]["reply_img"]  = $row_reply['msg_url'];
                }else{
                    $arr["messages"][$i]["reply"]["reply_img"]  = "null";
                }
            }

        }
        $arr["messages"][$i]["sent"]        			= $row['sent'];
		$arr["messages"][$i]["sent_date"]   			= date("M d, Y",$row['sent']);
		$arr["messages"][$i]["sent_time"]				= date("h:i A",$row['sent']);
		$arr["messages"][$i]["sent_datetime"]			= my_simple_date_for_ch($row['sent']);
        $arr["messages"][$i]["delivered"]   			= $row['delivered'];
		$arr["messages"][$i]["delivered_date"]  		= my_simple_date($row['delivered']);
        $arr["messages"][$i]["seen"]        			= $row['seen'];
		$arr["messages"][$i]["seen_date"]  				= my_simple_date($row['seen']);
        $arr["messages"][$i]["status"]      			= $row['status'];

        $arr["messages"][$i]["deleted"]                 = $row['deleted'];
        $arr["messages"][$i]["edited"]      			= $row['edited'];
        $arr["messages"][$i]["edited_time"]      		= my_simple_date_for_ch($row['edited_time']);

        if($row['sent'] > $timestamp_new){ //becaue update time is always greater
            $timestamp_new = $row['sent'];
        }
        if($row['delivered'] > $timestamp_new){
            $timestamp_new = $row['delivered'];
        }
        if($row['seen'] > $timestamp_new){
            $timestamp_new = $row['seen'];
        }
        if($row['edited_time'] > $timestamp_new){
            $timestamp_new = $row['edited_time'];
        }
        if($row['deleted_time'] > $timestamp_new){
            $timestamp_new = $row['deleted_time'];
        }

        $i++;
    }

    //group messages
    foreach($rows_group_messages as $row){
        $arr["messages"][$i]["id"]              = $row['id'];
        $arr["messages"][$i]["display_name"]    = $display_name;
        $arr["messages"][$i]["display_pic"]     = $display_pic;
        $arr["messages"][$i]["msg_audience"]    = $row['msg_audience'];

        if($row['msg_from'] == $SESS_ID){//I am the sender
            $arr["messages"][$i]["received"]    = false;
            $arr["messages"][$i]["ch_id"]       = "group_".$row['msg_to'];
	        $arr["messages"][$i]["mark_as_read"]= $row['mark_read_by_receiver'];
            $arr["messages"][$i]["group_message_sender"] = "You:";
            $chat_head_id = "group_".$row['msg_to'];
        }else{//I am the receiver
            $arr["messages"][$i]["received"]    = true;
            $arr["messages"][$i]["ch_id"]       = "group_".$row['msg_to'];
	        $arr["messages"][$i]["mark_as_read"]= $row['mark_read_by_receiver'];

            $rows_group_from = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($row['msg_from']), "rows");
            foreach($rows_group_from as $row_group_from){
                $arr["messages"][$i]["group_message_sender"] = $row_group_from['fullname'].":";
            }

            $chat_head_id = "group_".$row['msg_from'];
        }

        $rows_display_name_pic = sql($DBH, "SELECT * FROM tbl_login where id = ? ", array($chat_head_id), "rows");
        foreach($rows_display_name_pic as $row_display_name_pic){
            $display_name    = $row_display_name_pic['fullname'];
            if(strpos($row_display_name_pic['photo'], "http") == false){
                $display_pic = $website_url."admin/".$row_display_name_pic['photo'];
            }else{
                $display_pic = $row_display_name_pic['photo'];
            }
        }

        $arr["messages"][$i]["display_name"]    = $display_name;
        $arr["messages"][$i]["display_pic"]     = $display_pic;

        $arr["messages"][$i]["msg_type"]        = $row['msg_type'];
        $arr["messages"][$i]["msg_text"]        = $row['msg_text'];
        $arr["messages"][$i]["msg_url"]         = $row['msg_url'];
        $arr["messages"][$i]["msg_url_size"]    = readableFileSize($row['msg_url_size']);

        if(strlen($row['msg_reply_to']) > 0){
            $reply_to = $row['msg_reply_to'];
            $arr["messages"][$i]["reply"]   = array();

            $rows_reply = sql($DBH, "SELECT msg_from,msg_text,msg_url,msg_type FROM tbl_message where id = ?", array($reply_to), "rows");
            foreach($rows_reply as $row_reply){

                $reply_from = $row_reply['msg_from'];
                if($SESS_ID == $reply_from){
                    $arr["messages"][$i]["reply"]["reply_name"] = "You:";
                }else{
                    $rows_reply_from = sql($DBH, "SELECT * FROM tbl_login where id = ?", array($reply_from), "rows");
                    foreach($rows_reply_from as $row_reply_from){
                        $arr["messages"][$i]["reply"]["reply_name"] = $row_reply_from['fullname'].":";
                    }
                }

                $arr["messages"][$i]["reply"]["reply_id"] = $reply_to;
                $arr["messages"][$i]["reply"]["reply_text"] = $row_reply['msg_text'];

                if($row_reply['msg_type'] == "image"){
                    $arr["messages"][$i]["reply"]["reply_img"]  = $row_reply['msg_url'];
                }else{
                    $arr["messages"][$i]["reply"]["reply_img"]  = "null";
                }
            }

        }

		//if(strlen($row['msg_url']) > 0){
			//$arr["messages"][$i]["msg_url_size"] = curl_get_file_size($row['msg_url']);
		//}

        $arr["messages"][$i]["sent"]        			= $row['sent'];
		$arr["messages"][$i]["sent_date"]   			= date("M d, Y",$row['sent']);
		$arr["messages"][$i]["sent_time"]				= date("h:i A",$row['sent']);
		$arr["messages"][$i]["sent_datetime"]			= my_simple_date_for_ch($row['sent']);

        $arr["messages"][$i]["delivered"]   			= $row['delivered'];
		$arr["messages"][$i]["delivered_date"]  		= my_simple_date($row['delivered']);
        $arr["messages"][$i]["seen"]        			= $row['seen'];
		$arr["messages"][$i]["seen_date"]  				= my_simple_date($row['seen']);
        $arr["messages"][$i]["status"]      			= $row['status'];

        $arr["messages"][$i]["deleted"]                 = $row['deleted'];
        $arr["messages"][$i]["edited"]      			= $row['edited'];
        $arr["messages"][$i]["edited_time"]      		= my_simple_date_for_ch($row['edited_time']);

        $arr["messages"][$i]["group_delivered"]   		= $row['group_delivered'];
        $arr["messages"][$i]["group_seen"]   			= $row['group_seen'];

        if($row['sent'] > $timestamp_new){ //becaue update time is always greater
            $timestamp_new = $row['sent'];
        }
        if($row['delivered'] > $timestamp_new){
            $timestamp_new = $row['delivered'];
        }
        if($row['seen'] > $timestamp_new){
            $timestamp_new = $row['seen'];
        }
        if($row['edited_time'] > $timestamp_new){
            $timestamp_new = $row['edited_time'];
        }
        if($row['deleted_time'] > $timestamp_new){
            $timestamp_new = $row['deleted_time'];
        }

        $i++;
    }



    $arr['timestamp_new'] = $timestamp_new;

    die(json_encode($arr));

?>
