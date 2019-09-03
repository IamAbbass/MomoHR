<?php 
	
	// define('HOST', 'localhost');
	// define('USER', 'zeddevel_hamza');
	// define('PASSWORD', 'zeddevel_hamza');
	// define('DATABASE', 'zeddevel_hamza_test');

	// $conn = mysqli_connect(HOST,USER,PASSWORD,DATABASE);
	// $conn = new PDO("mysql:host=HOST;dbname=DATABASE", USER, PASSWORD);

	// if (mysqli_connect_errno()) {
	// 	 echo "Failed to connect to MySQL: " . mysqli_connect_error();
	// 	die();
	// }
	$servername = "localhost";
	$username = "mmrsonli_momohr";
	$password = "mmrsonli_momohr";
	try {
	    $conn = new PDO("mysql:host=$servername;dbname=mmrsonli_momohr", $username, $password);
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
	catch(PDOException $e)
    {
    	echo "Connection failed: " . $e->getMessage();
    }
?>


?>