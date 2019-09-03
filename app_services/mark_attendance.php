<?php
	header("Access-Control-Allow-Origin: *");

	require_once('../class_function/session.php');
	require_once('../class_function/error.php');
	require_once('../class_function/dbconfig.php');
	require_once('../class_function/function.php');
    
    if($_GET['secret'] != "asd321asd654asd"){
        die("Access Denied!");
    }else{
        echo "Access Denied";
    }
    
    
    $STH 			= $DBH->prepare("select count(*) FROM tbl_attendance where employee_id = ? and id > ?");
    $result 	   	= $STH->execute(array(5,18));
    $count1	  	    = $STH->fetchColumn();    
    $attachment1    = "https://sample-images.oss-us-east-1.aliyuncs.com/Pi/5/5%20%28".($count1+1)."%29.jpg";
    
    
    echo "$count1";
    
    $STH 			= $DBH->prepare("select count(*) FROM tbl_attendance where employee_id = ? and id > ?");
    $result 	   	= $STH->execute(array(4,18));
    $count2	  	    = $STH->fetchColumn();
    $attachment2    = "https://sample-images.oss-us-east-1.aliyuncs.com/Pi/4/4%20%28".($count2+1)."%29.jpg";
    
        
    
    //s5
    $date_time1      = time()- rand(-1100,0);    
    $rows = sql($DBH, "insert into tbl_attendance (employee_id,date_time,attachment,company_id) values (?,?,?,?)",
    array(5,$date_time1,$attachment1,1), "rows");
    
    //m4
    $date_time2      = time()- rand(-1100,0);
    $rows = sql($DBH, "insert into tbl_attendance (employee_id,date_time,attachment,company_id) values (?,?,?,?)",
    array(4,$date_time2,$attachment2,1), "rows");
    
    
    $subject = 'MomoHR - Attendance';
    $message = '<html>
    				<head>
    				  <title>MomoHR - Attendance</title>
    				</head>
    				<body>
    					<p>5. Syed '.date("d-M-Y h:i:s a",$date_time1).' '.$attachment1.'</p>
                        <p>4. Mohammed '.date("d-M-Y h:i:s a",$date_time2).' '.$attachment2.'</p>
    				</body>
    			</html>';
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';
    $headers[] = 'To: <ghulamabbass.1995@gmail.com>';
    $headers[] = 'From: Website <attendance@momohr.com>';
    mail($email, $subject, $message, implode("\r\n", $headers));
    
    
    
    
    


?>