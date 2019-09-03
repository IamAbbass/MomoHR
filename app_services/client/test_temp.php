<?php 
	
		header('Access-Control-Allow-Origin: *');


		if (isset($_POST['encryted_text']) && isset($_POST['checkUpdates'])) {
			$asd = cryptoJsAesDecrypt('key@zeddevelopers.com',$_POST['encryted_text']);
			if (!empty($asd) && $asd == true) {
				echo json_encode("success");
			}
		}

		
		
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
	

 ?>