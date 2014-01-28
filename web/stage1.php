#<?php
#	Implement some mechanism that will execute update.sh only when needed
#	For example based on some flag that is cleared by setup-complete.php
#?>

export DHCP_NEXT_SERVER_PATH=${DHCP_BOOT_FILE%/stage1.php}
wget -O /update.txt http://$DHCP_NEXT_SERVER/$DHCP_NEXT_SERVER_PATH/update.txt
sh /update.txt
