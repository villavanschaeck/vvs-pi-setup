#!/bin/ash

/etc/init.d/rcS

sleep 3
ifconfig eth0 up
sleep 1
udhcpc -t 5

TRIES=0
while [ ! -e /dev/mmcblk0 -a "$TRIES" -lt 3 ]; do
	echo "/dev/mmcblk0 is missing. Will sleep 3 seconds and call mdev -s"
	sleep 3
	mdev -s
	TRIES=$(($TRIES+1))
done

if [ -f /stage0.sh ]; then
	sh /stage0.sh
else
	echo "/stage0.sh not found. dhcp failed?"
fi

if [ ! -d /target ]; then
	mkdir /target 2>/dev/null
fi
if ! mountpoint -q /target; then
	mount -o ro /dev/mmcblk0p2 /target
fi
if [ -f /target/sbin/init ]; then
	echo "Deconfiguring eth0"
	ip addr flush dev eth0
	exec switch_root -c /dev/console /target /sbin/init
fi

sleep 60
reboot -f
