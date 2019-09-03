<?php 

	// die($_SERVER['PHP_SELF']);
    header("Access-Control-Allow-Origin: *");
   
		
	require_once('../../class_function/session.php');

	require_once('../../class_function/error.php');

	require_once('../../class_function/dbconfig.php');

	require_once('../../class_function/function.php');	 

	error_reporting(E_ALL);
	
  
    
    function generate_unique_id(){
		return md5(time().uniqid());
	}

 	function get_current_dir()
	{
		$url = $_SERVER['REQUEST_URI']; //returns the current URL
		$parts = explode('/',$url);
		$dir = $_SERVER['SERVER_NAME'];
		for ($i = 0; $i < count($parts) - 1; $i++) {
		 $dir .= $parts[$i] . "/";
		}
		return $dir;
	}

	function upload_file($fileName){  

	     // $errors= array();
	     $file_name = $_FILES[$fileName]['name'];
	     $file_size =$_FILES[$fileName]['size'];
	     $file_tmp =$_FILES[$fileName]['tmp_name'];
	     $file_type=$_FILES[$fileName]['type'];
	     $tmp = explode('.', $file_name);
	     $file_ext= end($tmp);  
	     if ($file_ext == 'zip') {
		     if($file_size > 15006176){
		     	$error ='Error: File size must be less than 15 MB';
		      return $error;
		     }
		     $unique_name=generate_unique_id();


		     $res = move_uploaded_file($file_tmp,"files/".$unique_name.".".$file_ext);
		     if ($res) {
		     	return get_current_dir()."/files/".$unique_name.".".$file_ext;
		     }
		     else{
		     	return false;
		     }
          }
          else{
          	return "Error: File extension should be zip";
          }                 
	}

	function get_version_name()
	{	
		global $DBH;
		$query = 'select * from tbl_client_version';
		$array = array();
		$result = sql($DBH,$query,$array,'rows');
		if (count($result) > 0) {
			$data = end($result);
			return $data['id'];
		}
		else{
			return '1';
		}
	}
	function insert_updates($data,$file_url)
	{	
		global $DBH;
		$date 	= strtotime(date('m/d/Y'));
		$query  = 'INSERT INTO `tbl_client_version`(`file_url`, `version_date`) VALUES (?,?)';
		$STH = $DBH->prepare("INSERT INTO `tbl_client_version`(`file_url`, `version_date`) VALUES (?,?)");
		$result = $STH->execute(array($file_url,$date));
		 if ($result) {
		 	return true;
		 }
		 else{
		 	return false;
		 }
	}

	
	$version_name = get_version_name();
	if (isset($_POST['btn_add_updates'])) {
		$file = upload_file('file');
		if ($file) {
			  $result = insert_updates($_POST,$file);
			  if ($result) {
			  	echo "success";
			  }
			 else{
			 	echo "data not inserted";
			 }
		}
		else{
			echo "Something went wrong";
		}
	}


 ?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1>Add Updates</h1>
				<form method="post" enctype="multipart/form-data">
					<div class="form-group">
						<label>Version</label>
						<input type="text" value="<?= $version_name?>" readonly name="txt_version" class="form-control">
					</div>
					<div class="form-group">
						<label>Upload zip file only</label>
						<input type="file" required="" class="form-control" name="file">
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" name="btn_add_updates">
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		
	</script>
</body>
</html>