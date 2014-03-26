IMAGE=2014-01-07-wheezy-raspbian.img
PART1OFFSET=8192
PART1SIZE=57344
PART2OFFSET=122880
PART2SIZE=5662719

all: pi.img

pi.img: $(IMAGE) init.rd
	[ -f pi.img ] || cp $(IMAGE) pi.img
	dd if=$(IMAGE) of=boot.part skip=$(PART1OFFSET) count=$(PART1SIZE)
	if mountpoint -q mnt; then umount mnt; fi
	[ ! -d mnt ] || rmdir mnt
	mkdir mnt
	mount -o loop boot.part mnt
	cp init.rd mnt/init.rd
	[ "`grep -c initramfs mnt/config.txt`" -gt 0 ] || echo "initramfs init.rd" >> mnt/config.txt
	umount mnt
	rmdir mnt
	dd if=boot.part of=pi.img seek=$(PART1OFFSET) count=$(PART1SIZE) conv=notrunc
	rm boot.part

bootme.part:
	dd if=/dev/zero of=bootme.part bs=1M count=1
	mkfs.ext2 -F bootme.part
	if mountpoint -q mnt; then umount mnt; fi
	[ ! -d mnt ] || rmdir mnt
	mkdir mnt
	mount -o loop bootme.part mnt
	touch mnt/force_reinstall
	umount mnt
	rmdir mnt

installer.img: pi.img bootme.part
	dd if=pi.img of=installer.img count=$$(($(PART1OFFSET)+$(PART1SIZE)))
	dd if=bootme.part of=installer.img seek=$(PART2OFFSET) conv=notrunc

init.rd: target_fs udhcpc.script init.sh oid.armhf
	@[ -d target_fs ] || make target_fs
	rm -f target_fs/init
	rm -f sys/.dummy proc/.dummy dev/.dummy
	mkdir -p target_fs/usr/share/udhcpc
	install -m 555 udhcpc.script target_fs/usr/share/udhcpc/default.script
	install -m 555 init.sh target_fs/init
	install -m 555 oid.armhf target_fs/usr/local/bin/oid
	cd target_fs && find | cpio -o -H newc | gzip > ../init.rd

target_fs:
	git clone git://github.com/raspberrypi/target_fs
	cd target_fs && git reset --hard 61b4e3f7685f37a096f90edf32c02b6e6b22318b

oid.armhf:
	@echo "oid.armhf can be obtained by compiling https://github.com/Jille/oid with a crosscompiler or on a raspberry pi with"
	@echo "Be sure to give -static to the compiler because there are no libraries in the initramfs"
	@echo "I've provided a precompiled version in this git repository for your convenience"
	exit 1

$(IMAGE):
	@echo "Download $(IMAGE)"
	exit 1

clean:
	rm -f pi.img init.rd installer.img
	cd target_fs && git checkout -f
