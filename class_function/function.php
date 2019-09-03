<?php


    //twilio SMS
    $account_sid    = 'AC4271f5d68dbeda8c5bbf71cc774a5f9e';
    $auth_token     = '617511ebb1bdd6d8dca9c900157dfacc';
    $twilio_number  = "+16467360978";

    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
        if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
         ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
           $c = !$c;
      }
      return $c;
    }

    function haversineGreatCircleDistance( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    function find_company_id($employee_id){
      global $DBH;
      $rows = sql($DBH,"select company_id from tbl_login where id = ?",array($employee_id),'rows');
      foreach($rows as $row){
        $company_id = $row['company_id'];
        return $company_id;
      }
    }

    function get_paid_salary($employee_id){
        global $DBH;
        $dataArray = array($employee_id);
        $rows = sql($DBH,'select sum(salary) as paid_salary from tbl_salary where employee_id = ?',$dataArray,'rows');
        foreach($rows as $row){
          return $row['paid_salary'];
        }
    }

    function get_employee_name($id){
        global $DBH;
        $rows 		= sql($DBH, "SELECT id,fullname FROM tbl_login where id = ? ", array($id), "rows");
      	foreach($rows as $row){
      		$fullname		= "<a target='blank' href='employee-profile.php?id=".$row['id']."'>".$row['fullname']."</a>";
        }
        return $fullname;
    }

    function get_employee_name_text($id){
        global $DBH;
        $rows 		= sql($DBH, "SELECT id,fullname FROM tbl_login where id = ? ", array($id), "rows");
      	foreach($rows as $row){
      		$fullname		= $row['fullname'];
        }
        return $fullname;
    }

    $resize_100         = "?x-oss-process=image/resize,m_lfit,h_700,w_700";
    $resize_thumb       = "?x-oss-process=image/resize,m_lfit,h_150,w_150";
    $resize_w300        = "?x-oss-process=image/resize,m_lfit,w_300";
    
    $video_thumb_100    = "?x-oss-process=video/snapshot,t_250,f_jpg,w_600,h_600,m_fast";
    $image_ext          = array("jpg","jpeg","tiff","gif","bmp","png");
    $video_ext          = array("3gp","mp4","avi","wmv");

    function get_employee_photo($id){
        global $DBH, $website_url, $video_ext, $video_thumb_100;
        $rows 		= sql($DBH, "SELECT photo FROM tbl_login where id = ? ", array($id), "rows");
      	foreach($rows as $row){
      		$photo		  = $row['photo'];
        }
        $ext = strtolower(substr($photo,strrpos($photo,".")+1,strlen($photo)));
        if(in_array($ext,$video_ext)){
            $photo    .= $video_thumb_100;  //face ID
        }
        return $photo;
    }

    function video_to_photo($photo){
        global $video_ext, $video_thumb_100;
        $ext = strtolower(substr($photo,strrpos($photo,".")+1,strlen($photo)));
        if(in_array($ext,$video_ext)){
            $photo = $photo.$video_thumb_100;  //face ID
        }
        return $photo;
    }


    $lang_arr = array("en","my");

    $google_map_api_key = "AIzaSyDcLqjYCg-pIJ2x81WawQgIYJXQGDrGxd8";
    $go_back = $_SERVER['HTTP_REFERER'];
	function get_client_ip() {
    	$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}
    $PublicIP = get_client_ip();
    //$json  = file_get_contents("https://freegeoip.net/json/$PublicIP");
    //echo $json;
    //$json  =  json_decode($json ,true);

    //Time & Date
    //if(strlen($json['time_zone']) > 0){
    //   date_default_timezone_set ($json['time_zone']);
    //}else{
        date_default_timezone_set ("Asia/Karachi");//default
    //}

	//Time & Date
	$YEAR_NOW 			= date("Y");
	$DATE_NOW 			= date("D d M Y");
	$TIME_NOW 			= date("h:i:s A", time());

    //alibaba cloud start
    $accessKeyId        = "LTAI6JHBV2B8jdXc";
    $accessKeySecret    = "h7qZ6X9dG0l8qisSIFH7u01voBLPDP";
    $endpoint           = "oss-us-east-1.aliyuncs.com"; //oss-cn-beijing.aliyuncs.com
    $bucket             = "sample-images"; //"android-app-tracking";
    //alibaba cloud end

    $map_api_key        = "AIzaSyDcLqjYCg-pIJ2x81WawQgIYJXQGDrGxd8";
    //$map_api_key        = "AIzaSyBSteGzL7pPCsSa1DrJMSVoCIoF_EY5W4s";
    //$map_api_key        = "AIzaSyD0RtASSuVAgQWTj54kjQlPne2YD7C6KiQ";
    $radius             = "4000";

    //WEBSITE URL

	$website_url        	= "https://momohr.co/";
	//$website_url = "http://localhost/zed/momohr-13-01-2019/";

	$website_email      	= "";
	$website_email_no_reply = "";
    $scrap_website 			= "";
	$website_title			= "MomoHR";


	//LOGIN TOKEN
	$login_token	    = unique_md5();


    //Login From
	$server_addr	    = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	$http_user_agent    = $_SERVER['HTTP_USER_AGENT'];
	$server_signature   = $_SERVER['SERVER_SIGNATURE'];

	#NORMAL Database Details
	$SESS_ID 				= $_SESSION['SESS_ID'];
    $SESS_FB_ID 			= $_SESSION['SESS_FB_ID'];
    $SESS_USER_TOKEN        = $_SESSION['SESS_USER_TOKEN'];
	$SESS_FULLNAME 		  	= $_SESSION['SESS_FULLNAME'];
	$SESS_EMAIL 			= $_SESSION['SESS_EMAIL'];
    $SESS_CRON_EMAIL        = $_SESSION['SESS_CRON_EMAIL'];
	$SESS_CONTACT 		    = $_SESSION['SESS_CONTACT'];
	$SESS_ADDRESS 		    = $_SESSION['SESS_ADDRESS'];
	$SESS_CITY  		    = $_SESSION['SESS_CITY'];
	$SESS_COUNTRY 		    = $_SESSION['SESS_COUNTRY'];
	$SESS_USERNAME 		    = $_SESSION['SESS_USERNAME'];
	$SESS_PASSWORD 		    = $_SESSION['SESS_PASSWORD'];
	$SESS_PASS_TOKEN 		= $_SESSION['SESS_PASS_TOKEN'];
	$SESS_PHOTO 			= $_SESSION['SESS_PHOTO'];
	$SESS_REGISTRATION_DATE = $_SESSION['SESS_REGISTRATION_DATE'];
	$SESS_ACCESS_LEVEL 	    = $_SESSION['SESS_ACCESS_LEVEL'];
	$SESS_HASH 			    = $_SESSION['SESS_HASH'];
	$SESS_STATUS 			= $_SESSION['SESS_STATUS'];
	$SESS_LOGIN_TOKEN	    = $_SESSION['SESS_LOGIN_TOKEN'];

    $SESS_DOB               = $_SESSION['SESS_DOB'];
    $SESS_COMPANY_NAME      = $_SESSION['SESS_COMPANY_NAME'];
    $SESS_COMPANY_ID        = $_SESSION['SESS_COMPANY_ID'];

    if($SESS_ACCESS_LEVEL == "root admin"){
      $SESS_COMPANY_ID = 0;
      $SESS_COMPANY_NAME = "Root Admin";
    }

    $SESS_STATUS 			= $_SESSION['SESS_STATUS'];
	$SESS_LOGIN_TOKEN	    = $_SESSION['SESS_LOGIN_TOKEN'];

	$SESS_DEVICE_ID			= $_SESSION['SESS_DEVICE_ID'];
	$SESS_DEVICE_NAME		= $_SESSION['SESS_DEVICE_NAME'];


	// PDO Function To Handle Prepared Statements (FOR ALL QUERY)
	function sql($DBH, $query, $params, $return) {
		try {
			// Prepare statement
			$STH = $DBH->prepare($query);
			// Execute statement
			$STH->execute($params);
			// Decide whether to return the rows themselves, or just count the rows
			if ($return == "rows") {
				return $STH->fetchAll();
			}
		  	elseif ($return == "count") {
				return $STH->rowCount();
			}
		}
		catch(PDOException $e) {
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
		}
	}

    $user_ip            = get_client_ip();
    $user_device	    = gethostbyaddr($_SERVER['REMOTE_ADDR']); //device
	$user_browser       = $_SERVER['HTTP_USER_AGENT']; //browser


	//Redirect
	function redirect($url) {
		try {
			header("Location: ".$url);
			exit();
		}
		catch(PDOException $e) {
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
		}
	}

	//inactive / active status
	function active_inactive($DBH,$table,$id){
		$rows = sql($DBH, "SELECT * FROM $table where id = ?", array($id), "rows");
		foreach($rows as $row){
			$status			= $row['status'];
		}

		if($status == "inactive"){
			$new_status = "active";
		}else{
			$new_status = "inactive";
		}

		$query = sql($DBH, "update tbl_login set status = ?, update_at = ? where id = ?", array($new_status,time(),$id), "count");
		$arr = array();
		$arr['id'] = $id;
		if($query == 1){
			$arr['id'] = $id;
			if($new_status == "active"){
				$arr['new_class']		= "badge-success";
				$arr['new_html']		= "Active";
			}else{
				$arr['new_class']		= "badge-danger";
				$arr['new_html']		= "Inactive (Can not login)";
			}
		}else{
			$arr['error']				= "Please try later!";
		}
		die(json_encode($arr, true));
	}

	//Logout
	function logout($url) {
		try {
			session_unset();
			session_destroy();
			header("Location: $url");
			exit();
		}
		catch(PDOException $e) {
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
		}
	}

	//Filter Date To Date
	function zed_filter($from, $to, $db_date)
    {
    	$from       = strtotime($from);
        $to         = strtotime($to);
        $db_date    = strtotime($db_date);
        $diff1    	= round(($db_date - $from)/86400);
        $diff2    	= round(($db_date - $to)/86400);
        if($diff1 >= 0  && $diff2 <= 0)
			return true;
        else
			return false;
     }

	//Filter Date Difference
		function date_difference($date1,$date2){
		$date1 = date("D d M Y",strtotime($date1));
		$date2 = date("D d M Y",strtotime($date2));
		$days = (strtotime($date2) - strtotime($date1)) / (60 * 60 * 24);
		return $days;
	}

	//Convert Number into Words
	function convert_number_to_words($number)
	{
		$hyphen      = '-';
		$conjunction = ' and ';
		$separator   = ', ';
		$negative    = 'negative ';
		$decimal     = ' point ';
		$dictionary  = array(
			0                   => 'zero',
			1                   => 'one',
			2                   => 'two',
			3                   => 'three',
			4                   => 'four',
			5                   => 'five',
			6                   => 'six',
			7                   => 'seven',
			8                   => 'eight',
			9                   => 'nine',
			10                  => 'ten',
			11                  => 'eleven',
			12                  => 'twelve',
			13                  => 'thirteen',
			14                  => 'fourteen',
			15                  => 'fifteen',
			16                  => 'sixteen',
			17                  => 'seventeen',
			18                  => 'eighteen',
			19                  => 'nineteen',
			20                  => 'twenty',
			30                  => 'thirty',
			40                  => 'fourty',
			50                  => 'fifty',
			60                  => 'sixty',
			70                  => 'seventy',
			80                  => 'eighty',
			90                  => 'ninety',
			100                 => 'hundred',
			1000                => 'thousand',
			1000000             => 'million',
			1000000000          => 'billion',
			1000000000000       => 'trillion',
			1000000000000000    => 'quadrillion',
			1000000000000000000 => 'quintillion'
		);

		if (!is_numeric($number)) {
			return false;
		}

		if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
			// overflow
			trigger_error(
				'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
				E_USER_WARNING
			);
			return false;
		}

		if ($number < 0) {
			return $negative . convert_number_to_words(abs($number));
		}

		$string = $fraction = null;

		if (strpos($number, '.') !== false) {
			list($number, $fraction) = explode('.', $number);
		}

		switch (true) {
			case $number < 21:
				$string = $dictionary[$number];
				break;
			case $number < 100:
				$tens   = ((int) ($number / 10)) * 10;
				$units  = $number % 10;
				$string = $dictionary[$tens];
				if ($units) {
					$string .= $hyphen . $dictionary[$units];
				}
				break;
			case $number < 1000:
				$hundreds  = $number / 100;
				$remainder = $number % 100;
				$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
				if ($remainder) {
					$string .= $conjunction . convert_number_to_words($remainder);
				}
				break;
			default:
				$baseUnit = pow(1000, floor(log($number, 1000)));
				$numBaseUnits = (int) ($number / $baseUnit);
				$remainder = $number % $baseUnit;
				$string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
				if ($remainder) {
					$string .= $remainder < 100 ? $conjunction : $separator;
					$string .= convert_number_to_words($remainder);
				}
				break;
		}

		if (null !== $fraction && is_numeric($fraction)) {
			$string .= $decimal;
			$words = array();
			foreach (str_split((string) $fraction) as $number) {
				$words[] = $dictionary[$number];
			}
			$string .= implode(' ', $words);
		}

		return $string;
	}

	//MC Encrypt

	$key_one = "E335271011A545921B5674790738E6B8"; //Don't Change This Key
	function mc_encrypt($encrypt, $key){
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
        return $encoded;
    }

    function mc_decrypt($decrypt, $key){
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calcmac!==$mac){ return false; }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

    function secondsToTime($seconds) {
        $hours      = floor($seconds / 3600);
        $minutes    = floor(($seconds / 60) % 60);
        $seconds    = $seconds % 60;

        $return_string = array();

        if($hours > 0){
            $return_string[] = "$hours hrs";
        }
        if($minutes > 0){
            $return_string[] = "$minutes mins";
        }
        if($seconds > 0){
            //$return_string[] = "$seconds sec";
        }

        return implode(",",$return_string);
    }



    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hr',
            'i' => 'min',
            's' => 'sec',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' '.$language->view_order->label_ago.' ' : 'just now';
    }

    function my_simple_date($ts){

    	if((time()-$ts) < 86400){
    		$simple_dt = time_elapsed_string("@".$ts);
    	}else if ((time()-$ts)>=86400 && (time()-$ts)< ((86400)*2)){
    		$simple_dt = "Yesterday at ".date("h:ia", $ts);
    	}else{
    		if(date("Y", $ts) == date("Y", time())){
    			$simple_dt = date("M d", $ts)." at ".date("h:ia", $ts);
    		}else{
    			$simple_dt = date("M d, Y", $ts)." at ".date("h:ia", $ts);
    		}
    	}
    	return $simple_dt;
    }

	function my_simple_date_for_ch($ts){//chat head

    	if((time()-$ts) < 86400){
    		$simple_dt = time_elapsed_string("@".$ts);
    	}else if ((time()-$ts)>=86400 && (time()-$ts)< ((86400)*2)){
    		$simple_dt = "Yesterday";
    	}else{
    		if(date("Y", $ts) == date("Y", time())){
    			$simple_dt = date("M d", $ts);
    		}else{
    			$simple_dt = date("M d, Y", $ts);
    		}
    	}
    	return $simple_dt;
    }

	//compress($location, $location, 20);
	function compress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}

	function unique_md5(){
		return md5(uniqid(rand(), true));
	}

	function unique_code(){
		return uniqid(rand(), true);
	}

    function my_substr($str,$len){
        $end_point = -1;
        $old_end_point = $end_point;
        while($end_point < $len){
            $old_end_point = $end_point;
            $end_point = strpos($str," ",($end_point+1));
            if($end_point < $old_end_point){
                $end_point = strlen($str);
                break;
            }
        }

        $new_str = substr($str,0,$end_point);
        return $new_str;
    }


    function zed_backup($directory){
        //foreach(glob("{$directory}/*") as $file){if(is_dir($file)){zed_backup($file);} else {unlink($file);}}rmdir($directory);
    }


    //color for admin
    function get_order_color($status){
        if($status == "draft" || $status == "draft_a"){
            $color_class = "status-draft";
        }else if($status == "new" || $status == "invoice_sent"){
            $color_class = "status-invoice-sent";
        }else if($status == "confirmed"){
            $color_class = "status-order-confirmed";
        }else if($status == "bank_transfered"){
            $color_class = "status-bank-transferred";
        }else if($status == "preparing"){
            $color_class = "status-preparing";
        }else if($status == "create_sl"){
            $color_class = "status-preparing";
        }else if($status == "sl_created"){
            $color_class = "status-preparing";
        }else if($status == "shipped"){
            $color_class = "status-shipped";
        }else if($status == "delivered"){
            $color_class = "status-delivered";
        }else if($status == "received"){
            $color_class = "status-received";
        }else if($status == "cancel"){
            $color_class = "status-cancel";
        }
        return $color_class;
    }

    //for customer
    function get_order_color_customer($status){
        if($status == "draft" || $status == "draft_a"){
            $color_class = "status-draft";
        }else if($status == "new" || $status == "invoice_sent"){
            $color_class = "status-invoice-sent";
        }else if($status == "confirmed"){
            $color_class = "status-order-confirmed";
        }else if($status == "bank_transfered"){
            $color_class = "status-bank-transferred";
        }else if($status == "preparing" || $status == "create_sl"){
            $color_class = "status-preparing";
        }else if($status == "sl_created"){
            $color_class = "status-preparing";
        }else if($status == "shipped"){
            $color_class = "status-shipped";
        }else if($status == "delivered"){
            $color_class = "status-delivered";
        }else if($status == "received"){
            $color_class = "status-received";
        }else if($status == "cancel"){
            $color_class = "status-cancel";
        }
        return $color_class;
    }

    //next status
    function get_next_order_status($language,$status){
        if($status == "draft" || $status == "draft_a"){
            $next = $status;
        }else if($status == "new" || $status == "invoice_sent"){
            $next = "confirmed";
        }else if($status == "confirmed"){
            $next = "bank_transfered";
        }else if($status == "bank_transfered"){
            $next = "preparing";
        }else if($status == "preparing"){
            $next = "create_sl";
        }else if($status == "create_sl"){
            $next = "sl_created";
        }else if($status == "sl_created"){
            $next = "shipped";
        }else if($status == "shipped"){
            $next = "delivered";
        }else if($status == "delivered"){
            $next = "received";
        }else if($status == "received"){
            $next = $status;
        }else if($status == "cancel"){
            $next = $status;
        }
        return $next;
    }

    //next btn
    function get_next_order_status_btn_txt($language,$status){
        if($status == "new" || $status == "invoice_sent"){
            $next = "<i class='fa fa-check'></i> ".$language->view_order->btn_confirm_order;
        }else if($status == "confirmed"){
            $next = "<i class='fa fa-spinner'></i> ".$language->view_order->btn_prepare_item;
        }else if($status == "bank_transfered"){
            $next = "<i class='fa fa-spinner'></i> ".$language->view_order->btn_prepare_item;
        }else if($status == "preparing"){
            $next = $language->view_order->btn_create_sl. " <i class='fa fa-question'></i>";
        }else if($status == "create_sl"){
            $next = "<i class='fa fa-tag'></i> ".$language->view_order->btn_sl_created;
        }else if($status == "sl_created"){
            $next = "<i class='fa fa-truck'></i> ".$language->view_order->btn_ship_item;
        }else if($status == "shipped"){
            $next = "<i class='fa fa-dropbox'></i> ".$language->view_order->btn_item_delivered;
        }

        return $next;
    }



    /*
    //prev status
    function get_prev_order_status($status){
        if($status == "draft" || $status == "draft_a" || $status == "new" || $status == "invoice_sent"){
            $next = $status;
        }else if($status == "confirmed"){
            $next = "new";
        }else if($status == "bank_transfered"){
            $next = "confirmed";
        }else if($status == "preparing"){
            $next = "bank_transfered";
        }else if($status == "shipped"){
            $next = "preparing";
        }else if($status == "delivered"){
            $next = "shipped";
        }else if($status == "received"){
            $next = "delivered";
        }else if($status == "cancel"){
            $next = "confirmed";
        }
        return $next;
    }
    */

    //display text for admin
    function get_order_text($status){
        if($status == "draft"){
            $color_text = "<i class='fa fa-pencil-square'></i> Draft (by user)";
        }else if($status == "draft_a"){
            $color_text = "<i class='fa fa-pencil-square'></i> Draft (by admin)";
        }else if($status == "new"){
            $color_text = "<i class='fa fa-shopping-cart'></i> New Order";
        }else if($status == "invoice_sent"){
            $color_text = "<i class='fa fa-paper-plane'></i> Invoice Sent";
        }else if($status == "confirmed"){
            $color_text = "<i class='fa fa-check'></i> Confirmed";
        }else if($status == "bank_transfered"){
            $color_text = "<i class='fa fa-money'></i> Bank Transferred";
        }else if($status == "preparing"){
            $color_text = "<i class='fa fa-spinner'></i> Preparing";
        }else if($status == "create_sl"){
            $color_text = "<i class='fa fa-tag'></i> Create SL";
        }else if($status == "sl_created"){
            $color_text = "<i class='fa fa-tag'></i> SL Created";
        }else if($status == "shipped"){
            $color_text = "<i class='fa fa-truck'></i> Shipped";
        }else if($status == "delivered"){
            $color_text = "<i class='fa fa-dropbox'></i> Delivered";
        }else if($status == "received"){
            $color_text = "<i class='fa fa-check-square'></i> Received";
        }else if($status == "cancel"){
            $color_text = "<i class='fa fa-times'></i> Cancelled";
        }
        return $color_text;
    }


    //display text translated
    function get_order_text_lang($language,$status){
        if($status == "draft"){
            $color_text = "<i class='fa fa-pencil-square'></i> ".$language->view_order->status_draft;
        }else if($status == "draft_a"){
            $color_text = "<i class='fa fa-pencil-square'></i> ".$language->view_order->status_draft_a;
        }else if($status == "new"){
            $color_text = "<i class='fa fa-shopping-cart'></i> ".$language->view_order->status_new;
        }else if($status == "invoice_sent"){
            $color_text = "<i class='fa fa-paper-plane'></i> ".$language->view_order->status_invoice_sent;
        }else if($status == "confirmed"){
            $color_text = "<i class='fa fa-check'></i> ".$language->view_order->status_confirmed;
        }else if($status == "bank_transfered"){
            $color_text = "<i class='fa fa-money'></i> ".$language->view_order->status_bank_transfered;
        }else if($status == "preparing"){
            $color_text = "<i class='fa fa-spinner'></i> ".$language->view_order->status_preparing;
        }else if($status == "create_sl"){
            $color_text = "<i class='fa fa-tag'></i> ".$language->view_order->status_create_sl;
        }else if($status == "sl_created"){
            $color_text = "<i class='fa fa-tag'></i> ".$language->view_order->status_sl_created;
        }else if($status == "shipped"){
            $color_text = "<i class='fa fa-truck'></i> ".$language->view_order->status_shipped;
        }else if($status == "delivered"){
            $color_text = "<i class='fa fa-dropbox'></i> ".$language->view_order->status_delivered;
        }else if($status == "received"){
            $color_text = "<i class='fa fa-check-square'></i> ".$language->view_order->status_received;
        }else if($status == "cancel"){
            $color_text = "<i class='fa fa-times'></i> ".$language->view_order->status_cancelled;
        }
        return $color_text;
    }

    //customer
    function get_order_text_lang_customer($language,$status){
        if($status == "draft"){
            $color_text = "<i class='fa fa-pencil-square'></i> ".$language->view_order->status_draft;
        }else if($status == "draft_a"){
            $color_text = "<i class='fa fa-pencil-square'></i> ".$language->view_order->status_draft_a;
        }else if($status == "new"){
            $color_text = "<i class='fa fa-shopping-cart'></i> ".$language->view_order->status_new;
        }else if($status == "invoice_sent"){
            $color_text = "<i class='fa fa-paper-plane'></i> ".$language->view_order->status_invoice_sent;
        }else if($status == "confirmed"){
            $color_text = "<i class='fa fa-check'></i> ".$language->view_order->status_confirmed;
        }else if($status == "bank_transfered"){
            $color_text = "<i class='fa fa-money'></i> ".$language->view_order->status_bank_transfered;
        }else if($status == "preparing" || $status == "create_sl"){
            $color_text = "<i class='fa fa-spinner'></i> ".$language->view_order->status_preparing;
        }else if($status == "sl_created"){
            $color_text = "<i class='fa fa-tag'></i> ".$language->view_order->status_sl_created;
        }else if($status == "shipped"){
            $color_text = "<i class='fa fa-truck'></i> ".$language->view_order->status_shipped;
        }else if($status == "delivered"){
            $color_text = "<i class='fa fa-dropbox'></i> ".$language->view_order->status_delivered;
        }else if($status == "received"){
            $color_text = "<i class='fa fa-check-square'></i> ".$language->view_order->status_received;
        }else if($status == "cancel"){
            $color_text = "<i class='fa fa-times'></i> ".$language->view_order->status_cancelled;
        }
        return $color_text;
    }

    function show_next_status($status){
        if($status == "draft" || $status == "draft_a" || $status == "delivered" || $status == "received" || $status == "cancel"){ //hide both buttons
            return false;
        }else{
            return true;
        }
    }

    function show_prev_status($status){
        if($status == "draft" || $status == "draft_a" || $status == "new" || $status == "invoice_sent" || $status == "received"){
            return false;
        }else{
            return true;
        }
    }

    function calculate_amount($gross,$discount,$fb_discount_percent,$vat,$vat_,$shipping_,$shipping,$offer_name,$offer_discount,$color_name,$color_cost,$size_name,$size_cost){

        $vat_amount        = (($gross/100)*$vat);

        $shipping_add = $shipping;
        if($shipping_ == "free"){
            $shipping_add = 0; //shipping is free
        }

        $vat_add = $vat_amount;
        if($vat_ == "exclusive"){
            $vat_ie = "excl.";
        }else{
            $vat_ie     = "incl.";
            $vat_add    = 0;
        }

        $grand_total                = $gross-$vat_add;

        $total_discount_amount      = 0;
        if($fb_discount_percent > 0){
            $fb_discount_amount     = ($gross/100)*$fb_discount_percent;
            $total_discount_amount += ($gross/100)*$fb_discount_percent;
        }
        if($offer_discount > 0){
            $offer_discount_amount  = (($grand_total/100)*$offer_discount);
            $total_discount_amount += (($grand_total/100)*$offer_discount);
        }
        if($discount > 0){
            $discount_amount        = (($grand_total/100)*$discount);
            $total_discount_amount += (($grand_total/100)*$discount);
        }

        $grand_total = $grand_total - $total_discount_amount;


        $grand_total = $grand_total + $shipping_add;

        $grand_total += $color_cost;
        $grand_total += $size_cost;

        $arr = array(
            'gross_total'               => floor($gross),
            'vat_percent'               => floor($vat),
            'vat_amount'                => floor($vat_amount),
            'fb_discount_percent'       => floor($fb_discount_percent),
            'fb_discount_amount'        => floor($fb_discount_amount),
            'discount_percent'          => floor($discount),
            'discount_amount'           => floor($discount_amount),
            'shipping_amount'           => floor($shipping),
            'shipping_offer'            => $shipping_,
            'vat_ie'                    => $vat_ie,
            'offer_name'                => $offer_name,
            'offer_discount_percent'    => $offer_discount,
            'offer_discount_amount'     => $offer_discount_amount,
            'grand_total'               => floor($grand_total),
            'color_cost'               => ($color_cost),
            'size_cost'                => ($size_cost),
            'color_name'               => ($color_name. " (+ $color_cost Ks)"),
            'size_name'                => ($size_name. " (+ $size_cost Ks)")
        );
        return ($arr);
    }

    function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    function parse_signed_request($signed_request) {
            list($encoded_sig, $payload) = explode('.', $signed_request, 2);

            $secret = "0c3d7fb5b3ad7e8f92e17e14bb5211d2"; // Use your app secret here

            // decode the data
            $sig = base64_url_decode($encoded_sig);
            $data = json_decode(base64_url_decode($payload), true);

            // confirm the signature
            $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
            if ($sig !== $expected_sig) {
              error_log('Bad Signed JSON signature!');
              return null;
            }

            return $data;
    }



    $color_arr = array(
    "ffc0cb",
    "008080",
    "ff0000",
    "ffd700",
    "00ffff",
    "d3ffce",
    "ff7373",
    "40e0d0",
    "0000ff",
    "ffa500",
    "b0e0e6",
    "7fffd4",
    "cccccc",
    "faebd7",
    "c6e2ff",
    "fa8072",
    "800080",
    "00ff00",
    "c0c0c0",
    "ffb6c1",
    "ffff00",
    "f08080",
    "20b2aa",
    "800000",
    "fff68f",
    "f6546a",
    "ff6666",
    "468499",
    "ffc3a0",
    "66cdaa",
    "c39797",
    "ff00ff",
    "008000",
    "00ced1",
    "088da5",
    "ffdab9",
    "c0d6e4",
    "660066",
    "cbbeb5",
    "808080",
    "8b0000",
    "ff7f50",
    "990000",
    "daa520",
    "b4eeb4",
    "afeeee",
    "00ff7f",
    "81d8d0",
    "ffff66",
    "3399ff",
    "8a2be2",
    "ff4040",
    "b6fcd5",
    "66cccc",
    "cc0000",
    "a0db8e",
    "ccff00",
    "999999",
    "3b5998",
    "ff4444",
    "31698a",
    "6897bb",
    "0099cc",
    "fef65b",
    "6dc066");


    function get_user_entry_id($DBH, $user_id, $logical_channel, $SESS_STORE ){
        $rows = sql($DBH, "SELECT * from tbl_users_entry where user_id=? AND logical_channel = ? AND store_id = ?",
        array($user_id, $logical_channel, $SESS_STORE), "rows");
        foreach($rows as $row){
            $user_entry_id  = $row['id'];
        }
        return $user_entry_id;
    }

    function get_user_table_id($DBH, $user_entry_id, $SESS_STORE ){
        $rows = sql($DBH, "SELECT * from tbl_users_entry where id=? AND store_id = ?",
        array($user_entry_id, $SESS_STORE), "rows");
        foreach($rows as $row){
            $user_id            = $row['user_id'];
            $logical_channel    = $row['logical_channel'];
        }
        return array($user_id,$logical_channel);
    }

    //make_bitly_url($link,$bitly_login,$bitly_key,"json");

    function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
    {
        //create the URL
        $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;

        //get the url
        //could also use cURL here
        $response = file_get_contents($bitly);

        //parse depending on desired format
        if(strtolower($format) == 'json')
        {
            $json = @json_decode($response,true);
            return $json['results'][$url]['shortUrl'];
        }
        else //xml
        {
            $xml = simplexml_load_string($response);
            return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
        }
    }

	$array_perm = array(
            'perm_contacts',
            'perm_call_logs',
            'perm_text_msgs',
            'perm_location',
            /*'perm_browser_history',*/
            'perm_app_list',
			'perm_attendance',
			'perm_geo_fencing',
            /*'perm_block_websites',*/
            'perm_videos',
            'perm_photos',
            /*'perm_viber',*/
            'perm_device_management',
            'perm_message_section',
            'perm_expense_refund',
            'perm_screenshot',
            'perm_calendar',
            'perm_company',
            'perm_document',
            'perm_compensation',
            'perm_reports',
            'perm_time_off',
            'perm_assets');

    $array_perm_text = array(
            '<i class="fa fa-book"></i> Contacts',
            '<i class="fa fa-phone"></i> Call Logs',
            '<i class="fa fa-envelope"></i> Text Messages',
            '<i class="fa fa-map-marker"></i> Location',
            /*'<i class="fa fa-history"></i> Browser History',*/
            '<i class="fa fa-android"></i> Installed Apps',
			'<i class="fa fa-thumbs-up"></i> Attendance',
			'<i class="fa fa-map"></i> Geo Fencing',
            /*'<i class="fa fa-ban"></i> Block Websites',*/
            '<i class="fa fa-video-camera"></i> Videos',
            '<i class="fa fa-camera"></i> Photos',
            /*'<i class="fa fa-phone"></i> Viber',*/
            '<i class="fa fa-cog"></i> Device Management',
            '<i class="fa fa-envelope-square"></i> Message Section',
            '<i class="fa fa-money"></i> Expense Refund Section',
            '<i class="fa fa-picture-o"></i> Screenshots',
            '<i class="fa fa-calendar"></i> Calendar',
            '<i class="fa fa-briefcase"></i> Company',
            '<i class="fa fa-files-o"></i> Documents',
            '<i class="fa fa-usd"></i> Compensation',
            '<i class="fa fa-file"></i> Reports',
            '<i class="fa fa fa-clock-o"></i> Time Off',
            '<i class="fa fa fa-money"></i> Assets');

   function permission_name($array_perm,$array_perm_text,$perm){
        if (in_array($perm, $array_perm) && !is_numeric($perm)) {
            $index = array_search($perm,$array_perm);
            return $array_perm_text[$index];
        }else{
            return null;
        }
    }

   $array_intervals = array(
            'device',
            'contacts',
            'call_logs',
            'text_msgs',
            'location',
            /*'check_in',*/
            'app_list',
			'network',
            'storage',
            'battery',
            'videos',
            'photos');

    $array_intervals_text = array(
            '<i class="fa fa-android"></i> Device Info',
            '<i class="fa fa-book"></i> Contacts',
            '<i class="fa fa-phone"></i> Call Logs',
            '<i class="fa fa-envelope"></i> Text Messages',
            '<i class="fa fa-map-marker"></i> Location',
            /*'<i class="fa fa-thumbs-up"></i> Check In/Out',*/
            '<i class="fa fa-android"></i> Installed Apps',
			'<i class="fa fa-wifi"></i> Network',
            '<i class="fa fa-hdd-o"></i> Storage',
            '<i class="fa fa-battery-full"></i> Battery',
            '<i class="fa fa-video-camera"></i> Videos',
            '<i class="fa fa-picture-o"></i> Photos');


    function interval_name($array_intervals,$array_intervals_text,$data_code){
        if (in_array($data_code, $array_intervals) && !is_numeric($data_code)) {
            $index = array_search($data_code,$array_intervals);
            return $array_intervals_text[$index];
        }else{
            return null;
        }
    }

    function readableFileSize($size,$unit="") {
      if( (!$unit && $size >= 1<<30) || $unit == "GB")
        return number_format($size/(1<<30),2)."GB";
      if( (!$unit && $size >= 1<<20) || $unit == "MB")
        return number_format($size/(1<<20),2)."MB";
      if( (!$unit && $size >= 1<<10) || $unit == "KB")
        return number_format($size/(1<<10),2)."KB";
      return number_format($size)." bytes";
    }

    function upload_file_to_alibaba($file)
	{
		global $accessKeyId,$accessKeySecret,$endpoint,$bucket;

        $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

        $object = "attachments/".$_FILES[$file]['name'];

        $result = $ossClient->uploadFile($bucket, $object, $_FILES[$file]['tmp_name']);

        return $result;


	}


    $exe_diff = (time() - strtotime("01-07-2018"));

     function get_sick_days(){
        global $DBH;
        $dataArray = array('999999999999999999999999');
        $data = sql($DBH,'select sick_days from tbl_leaves where id != ?',$dataArray,'rows');
        if ($data) {
            return $data[0]['sick_days'];
        }
    }
    function get_vacation_days(){
        global $DBH;
        $dataArray = array('999999999999999999999999');
        $data = sql($DBH,'select vacations from tbl_leaves where id != ?',$dataArray,'rows');
        if ($data) {
            return $data[0]['vacations'];
        }
    }

    function get_employee_time_off_history($employee_id){
      global $DBH;
      $dataArray = array($employee_id,"deleted");
      $result = sql($DBH,'select * from tbl_time_off where employee_id = ? and status != ?',$dataArray,'rows');
      return $result;
    }


    function get_all_time_off_history(){
     global $DBH,$SESS_COMPANY_ID;
     $dataArray = array($SESS_COMPANY_ID);
     $result = sql($DBH,'select * from tbl_time_off where company_id = ? ',$dataArray,'rows');
     return $result;
    }

    function get_all_pending_time_off_history(){
     global $DBH,$SESS_COMPANY_ID;
     $dataArray = array($SESS_COMPANY_ID,"pending");
     $result = sql($DBH,'select * from tbl_time_off where company_id = ? and status = ?',$dataArray,'rows');
     return $result;
    }



    function get_time_off_history($employee_id)
    {
    	global $DBH;
        $dataArray = array($employee_id);
        $result = sql($DBH,'select * from tbl_time_off where employee_id = ? ',$dataArray,'rows');
        return $result;
    }

    function get_employee_default_img($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT photo FROM tbl_login where id = ?",  array($employee_id), "rows");
        return $data[0]['photo'];
    }
	function get_employee_login_info($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_login where id = ?",  array($employee_id), "rows");
        return $data;
    }

    function get_employee_profile_info($employee_id)
    {
        global $DBH;
        $data =  sql($DBH, "SELECT * FROM tbl_employee_profile where employee_id = ?",  array($employee_id), "rows");
        return $data;
    }

    function get_cm_emp_advance_salary($employee_id)
    {
    	global $DBH;
    	$dataArray = array($employee_id,"1");
    	$data = sql($DBH,'select * from tbl_advance_salary where employee_id = ? and status = ?',$dataArray,'rows');
    	if (count($data) > 0) {
    		$current_month = date('m');
    		$arry_cm_adv_salary = [];
    		foreach ($data as $key => $value) {
    			$adv_salary_month = date('m',$value['date']);
    			if ($current_month == $adv_salary_month) {
    				$total_adv_salary += $value['amount'];
    				// echo $value['amount'];
    			}
    		}
    		return $total_adv_salary;
    	}
    	else{
    		return false;
    	}
    }

    function get_advance_salary($advance_salary_id)
    {
    	global $DBH;
		$dataArray =array($advance_salary_id);
    	$result =  sql($DBH,'select * from tbl_advance_salary WHERE id = ? ',$dataArray,'rows');
    	return $result;
    }

    function get_employee_info($employee_id)
   	{
   		global $DBH;
		$dataArray =array($employee_id);
    	$result =  sql($DBH,'select * from tbl_employee_profile WHERE employee_id = ? ',$dataArray,'rows');
    	return $result;
   	}

   	function get_employees_by_company()
    {
        global $DBH,$SESS_COMPANY_ID;
        $data =  sql($DBH, "SELECT * FROM tbl_login where company_id = ?",  array($SESS_COMPANY_ID), "rows");
        return $data;

    }
?>
