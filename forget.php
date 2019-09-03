<?php
	require_once('class_function/session.php');
	require_once('class_function/error.php');
	require_once('class_function/function.php');
	require_once('class_function/dbconfig.php');
	require_once('class_function/language.php');
	
	if(isset($_POST['email']))
	{
		try { 
			// GET RECORD
			$email		  = strip_tags($_POST['email']);
			$md5_token    = md5($token);
			$new_password = md5(md5(md5($md5_token) . md5($rand)) . md5($md5_token));

			$not_access   = "Super Administrator";
			$new_status   = "Blocked";
			
			//Get Duplicate Username OR Email
			$STH = $DBH->prepare("SELECT username, email, access_level FROM tbl_login WHERE email = :email");
			$STH->bindParam(':email',  $email);
			$STH->execute();
			$row = $STH->fetch(PDO::FETCH_ASSOC);
			$username 	 = $row['email'];
			$access_level = $row['access_level'];            
            
            // && $row['access_level'] != $not_access)
            
			if($row['email'] == $email) {
				// Update Old Record
				//sql($DBH, "UPDATE tbl_login SET password = ?, pass_token = ?, status = ? WHERE email = ? AND access_level != ? ",
				//array($new_password, $md5_token, $new_status, $email, $not_access));
                
                sql($DBH, "UPDATE tbl_login SET password = ?, pass_token = ? WHERE email = ?",
				array($new_password, $md5_token, $email));
                
                
				// Email New User Record
				$subject = "$website_title - New Password";
				$message = "
				<html>
					<head>
						<title>$website_title</title>
					</head>
					<body>
						<h1 style='text-align:center; width:600px;'>$website_title - New Password</h1>
						<table width='600px'>
							<tr>
								<th style='text-align: left; width: 50%;'>Username</th>
								<th style='text-align: left; width: 50%;'>Email Address</th>
								<th style='text-align: left; width: 50%;'>Password</th>
							</tr>
							<tr>
								<td>$username</td>
								<td>$email</td>
								<td>$rand</td>
							</tr>
						</table>
					</body>
				</html>
				";
				
				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				
				// More headers
				$headers .= "From: $website_title \r\n";
				mail($email,$subject,$message,$headers);
				redirect('index.php?error=forget');
			}
			else
			{
				redirect('index.php?error=not_access');
			}
		}
		catch(PDOException $e) { 
			file_put_contents('PDOErrors.txt', $e->getMessage(), FILE_APPEND); # Errors Log File
			redirect('index.php?error=failed');
		}
	}
	else
	{
        redirect('index.php?error=failed');
	}
?>