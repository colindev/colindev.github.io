#!/bin/bash
#
# curl https://colindev.github.io/linux/redhat/change-centos-repo.sh | sudo bash


releasever=7

cat > /etc/yum.repos.d/centos.repo <<EOF
[CentOSbase]
name=CentOS-$releasever-Base
mirrorlist=http://mirrorlist.centos.org/?release=$releasever&arch=\$basearch&repo=os
gpgcheck=1
enabled=1
gpgkey=http://mirror.centos.org/centos/RPM-GPG-KEY-CentOS-$releasever

[CentOSupdates]
name=CentOS-$releasever-Updates
mirrorlist=http://mirrorlist.centos.org/?release=$releasever&arch=\$basearch&repo=updates
gpgcheck=1
enabled=1
gpgkey=http://mirror.centos.org/centos/RPM-GPG-KEY-CentOS-$releasever

[CentOSplus]
name=CentOS-$releasever-Plus
mirrorlist=http://mirrorlist.centos.org/?release=$releasever&arch=\$basearch&repo=centosplus
gpgcheck=1
enabled=1
gpgkey=http://mirror.centos.org/centos/RPM-GPG-KEY-CentOS-$releasever
 
[centos]
name=CentOS $releasever - \$basearch
baseurl=http://ftp.heanet.ie/pub/centos/$releasever/os/\$basearch/
enabled=1
gpgcheck=0
EOF

cd /tmp
yum install pam.i686 -y --downloadonly --downloaddir=/tmp
rpm -ivh --force --nodeps `ls pam-*.rpm`
yum reinstall pam.i686 -y
yum distribution-synchronization -y
