<?php

	// define('HOST', 'localhost');
	// define('USER', 'root');
	// define('PASSWORD', '');
	// define('DATABASE', 'momohr');

	// $conn = mysqli_connect(HOST,USER,PASSWORD,DATABASE);
	// $conn = new PDO("mysql:host=HOST;dbname=DATABASE", USER, PASSWORD);

	// if (mysqli_connect_errno()) {
	// 	 echo "Failed to connect to MySQL: " . mysqli_connect_error();
	// 	die();
	// }
	$servername = "localhost";
	//$username = "mmrsonli_momohr";
	$username = "root";
//	$password = "mmrsonli_momohr";
	$password = "";
	try {
	    $conn = new PDO("mysql:host=$servername;dbname=momohr", $username, $password);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }
?>


?>
