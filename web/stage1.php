<?php
	header('Content-Type: text/plain');
	echo "date -us '". date('Y-m-d H:i:s', time() - date('Z')) ."'\n";

	$should_install = false;

	if(!include('mysql.php')) {
		echo "echo Database error\n";
		echo "sleep 60\n";
		echo "reboot -f\n";
		exit;
	}

	$ip = $_SERVER['REMOTE_ADDR'];
	if(isset($_GET['ip'])) {
		$ip = $_GET['ip'];
	}

	$res = mysql_query("SELECT should_install FROM raspberrypis WHERE ip='". addslashes($ip) ."'");
	if($row = mysql_fetch_assoc($res)) {
		if($row['should_install']) {
			$should_install = true;
		}
	}
	if(!$should_install) {
?>
if [ -n "$FORCE_REINSTALL" ]; then
<?php } ?>
	export PATH
	export DHCP_NEXT_SERVER_PATH=${DHCP_BOOT_FILE%/stage1.php}
	wget -O /update.txt http://$DHCP_NEXT_SERVER/$DHCP_NEXT_SERVER_PATH/update.txt
	sh /update.txt
<?php if(!$should_install) { ?>
fi

# Normal boot
<?php } ?>
