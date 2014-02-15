<?php
	$ip = $_SERVER['REMOTE_ADDR'];
	if(isset($_GET['ip'])) {
		$ip = $_GET['ip'];
	}

	if(!require('mysql.php')) {
		echo "echo Database error\n";
		echo "sleep 60\n";
		echo "reboot -f\n";
		echo "exit 1\n";
		exit;
	}

	$res = mysql_query("SELECT * FROM raspberrypis WHERE ip='". addslashes($ip) ."'");
	if($row = mysql_fetch_assoc($res)) {
		echo 'HOSTNAME='. $row['hostname'] ."\n";
		echo "DOMAIN=vvs-nijmegen.nl\n";
		echo 'IP='. $row['ip'] ."\n";
		echo 'EXTRA_SCRIPTS="'. str_replace(',', ' ', $row['functions']) ."\"\n";
		if(is_file('local-config.txt')) {
			readfile('local-config.txt');
		}
	}
?>
