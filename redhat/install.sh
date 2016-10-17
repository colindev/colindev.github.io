#!/bin/bash

echo Input your redhat [user] [passwd]:
read RHEL_USER RHEL_PASSWD

subscription-manager register --username "${RHEL_USER}" --password "${RHEL_PASSWD}" --auto-attach

yum update -y

yum install  -y gcc

# 安裝 kernel source 給 VBOXADDITION 用
yum install -y kernel-devel-`uname -r`


