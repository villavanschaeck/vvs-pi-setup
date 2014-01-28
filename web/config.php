<?php
	$ip = $_SERVER['REMOTE_ADDR'];
	if(isset($_GET['ip'])) {
		$ip = $_GET['ip'];
	}

	if(!require('mysql.php')) {
		echo "# Database error\n";
		exit;
	}

	$res = mysql_query("SELECT * FROM raspberrypi WHERE ip='". addslashes($ip) ."'");
	if($row = mysql_fetch_assoc($res)) {
		echo 'HOSTNAME='. $row['hostname'] ."\n";
		echo "DOMAIN=vvs-nijmegen.nl\n";
		echo 'IP='. $row['ip'] ."\n";
	}
?>
