<?php 
  	header("Access-Control-Allow-Origin: *");

	require_once('../../class_function/session.php');
	require_once('../../class_function/error.php');
	require_once('../../class_function/dbconfig.php');
	require_once('../../class_function/function.php');

	function cryptoJsAesDecrypt($passphrase, $jsonString){
	    $jsondata = json_decode($jsonString, true);
	    try {
	        $salt = hex2bin($jsondata["s"]);
	        $iv  = hex2bin($jsondata["iv"]);
	    } catch(Exception $e) { return null; }
	    $ct = base64_decode($jsondata["ct"]);
	    $concatedPassphrase = $passphrase.$salt;
	    $md5 = array();
	    $md5[0] = md5($concatedPassphrase, true);
	    $result = $md5[0];
	    for ($i = 1; $i < 3; $i++) {
	        $md5[$i] = md5($md5[$i - 1].$concatedPassphrase, true);
	        $result .= $md5[$i];
	    }
	    $key = substr($result, 0, 32);
	    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);
	    return json_decode($data, true);
	}



	
    function get_updates()
	{
	   
		global $DBH;
		$query = 'select * from tbl_client_version ORDER BY id DESC limit 1';
		$result = sql($DBH,$query,array(),'rows');
		if ($result) {
 
			$rows = end($result);
            //print_r($rows['id']);
	        $rows['version_date'] = date('m-d-Y',$rows['version_date']);
        	return $rows;
		}
        
        
	}
    
  


	// print_r(get_updates());

	if (isset($_POST['checkUpdates']) && isset($_POST['encrypted_text'])) {
		$status = cryptoJsAesDecrypt('key@zeddevelopers.com',$_POST['encrypted_text']);
		if (!empty($status) && $status == true) {
			echo json_encode(get_updates());
		}
	}


	
 ?>