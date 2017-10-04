#!/bin/bash
#
# https://git-scm.com/book/id/v2/Getting-Started-Installing-Git
#
# curl https://colindev.github.io/linux/redhat/compile-git.sh | sudo bash

yum groupinstall -y 'development tools'

yum install -y epel-release \
    && yum update -y \
    && yum install -y \
        curl-devel \
        expat-devel \
        gettext-devel \
        openssl-devel \
        zlib-devel

git clone https://github.com/git/git /tmp/git \
    && cd /tmp/git \
    && git checkout v2.14.2

make configure
# 發布版本裝在 /usr
# default is YES --with-curl --with-expat
./configure --prefix=/usr/local

#!!! make doc info has Error
make all doc info
make install install-doc install-html install-info

# bash complation
cp contrib/completion/git-completion.bash /etc/bash_completion.d/

