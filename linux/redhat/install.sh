#!/bin/bash

echo Input your redhat [user] [passwd]:
read RHEL_USER RHEL_PASSWD

subscription-manager register --username "${RHEL_USER}" --password "${RHEL_PASSWD}" --auto-attach

yum update -y

# 安裝 kernel source 給 VBOXADDITION 用
yum install -y kernel-devel-`uname -r`

# 安裝開發工具群
yum groupinstall -y 'Development tools'

