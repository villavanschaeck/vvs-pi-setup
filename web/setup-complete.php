<?php
	if(!require('mysql.php')) {
		echo "# Database error\n";
		exit;
	}

	$ip = $_SERVER['REMOTE_ADDR'];

	mysql_query("UPDATE raspberrypis SET last_install=NOW(), should_install=0 WHERE ip='". addslashes($ip) ."'");
	die("OK");
?>
