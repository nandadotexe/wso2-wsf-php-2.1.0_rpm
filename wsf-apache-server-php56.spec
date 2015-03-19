## 
# WSO2 WSF/PHP 2.1 Web Services Framework for => PHP 5.4
##
%define apiver 20131106
%define version 2.1.0
%define _unpackaged_files_terminate_build 0
%define __find_requires %{nil}
%define www_dir_content %(echo $APACHE_HTDOCS)
%define extension_dir %(echo `php-config --extension-dir`)
%define php_ini_file %(echo `php --ini|grep -e "Loaded Configuration File:         "|awk '{print $4}'`)
%define php_ini_dir %(echo `php --ini|grep -e "Configuration File (php.ini) Path:"|awk '{print $5}'`)
Summary: WSO2 WSF/PHP 2.1 Web Services Framework for PHP (Build for Apache 2.4)
Name: wso2-wsf-php
Vendor: WSO2 Inc. http://wso2.org
Version: %{version} 
Release: 1%{?dist}
Source: http://dist.wso2.org/products/wsf/php/2.1.0/wso2-wsf-php-src-%{version}.tar.gz
URL: http://wso2.com/products/web-services-framework/php/
License: Apache License V2.0
Group: Development/Tools
Packager: Hasan UCAK <hasan.ucak@gmail.com>

Requires: libxslt >= 1.1, zlib
Requires: libxml2 >= 2.6
Requires: php >= 5.4
Requires: axis2c >= 1.6

Provides: wso2-wsf-php = %{version}-%{release}
Buildroot: /tmp/wso2-wsf-php-%{version}-%{release}
AutoReqProv: no 

%description
WSO2 Web Services Framework for PHP (WSO2 WSF/PHP), a binding of WSO2 WSF/C into PHP is a PHP extension for providing and consuming Web Services in PHP. WSO2 WSF/PHP supports SOAP 1.1, SOAP 1.2, WSDL 1.1, WSDL 2.0, REST style invocation as well as some of the key WS-* stack specifications such as: SOAP MTOM, WS-Addressing, WS-Security, WS-SecurityPolicy and WS-ReliableMessaging.

##
# Prepare
##
%prep

PHP_CONFIG="php-config"
echo %{extension_dir}/wsf.so
# Safety check for API version change.
vapi=`phpize -v | sed -n '/PHP Api Version:/{s/.* //;p}'`

##
# Setup
##
%setup -q -n wso2-wsf-php-src-%{version}

##
# Build
##
%build
export PATH=./bin/:$PATH
export CFLAGS="-O2"
export PHP_CONFIG="php-config"

./configure --enable-trace --enable-tests --with-axis2=`pwd`/wsf_c/axis2c/include --prefix=$RPM_BUILD_ROOT/opt/wso2/wsf_c
make

##
# Install
##
%install
export QA_SKIP_BUILD_ROOT=1
rm -fr $RPM_BUILD_ROOT
make ROOT="$RPM_BUILD_ROOT" install

# Prepare and copy a WSO2 WSF Directory for scripts, samples and docs
mkdir -p $RPM_BUILD_ROOT/opt/wso2/wsf_php
cp -rf scripts $RPM_BUILD_ROOT/opt/wso2/wsf_php 
cp -rf samples $RPM_BUILD_ROOT/opt/wso2/wsf_php
cp -rf docs $RPM_BUILD_ROOT/opt/wso2/wsf_php

# Prepare and copy a WSO2 WSF Directory for Documents and WSF Samples
mkdir -p $RPM_BUILD_ROOT%{www_dir_content}
cp -rf samples $RPM_BUILD_ROOT%{www_dir_content} 
cp -rf docs $RPM_BUILD_ROOT%{www_dir_content}

mkdir $RPM_BUILD_ROOT/opt/wso2/wsf_php/sandesha2
cp -rf wsf_c/sandesha2c/config $RPM_BUILD_ROOT/opt/wso2/wsf_php/sandesha2

# Prepare a profile env
mkdir -p $RPM_BUILD_ROOT/etc/profile.d
cat > $RPM_BUILD_ROOT/etc/profile.d/wsf.sh <<EOF
LD_LIBRARY_PATH=\$LD_LIBRARY_PATH:/opt/wso2/wsf_c/lib:/opt/wso2/wsf_c/modules/rampart
export LD_LIBRARY_PATH
EOF

# Prepare a LD Config
mkdir -p $RPM_BUILD_ROOT/etc/ld.so.conf.d
cat > $RPM_BUILD_ROOT/etc/ld.so.conf.d/wsf.conf <<EOF
/opt/wso2/wsf_c/lib
/opt/wso2/wsf_c/modules/rampart
EOF

# Generate ini file for WSF/PHP
echo "php ini file : " %{php_ini_file}
cp --parents %{extension_dir}/wsf.so $RPM_BUILD_ROOT/.

##
# Clean
##
%clean
rm -rf $RPM_BUILD_ROOT

##
# Post
##
%post

# Running ldconfig 
echo "Running ldconfig..."
/sbin/ldconfig

# Verify WSF/PHP Module
echo "include wsf setting in php.ini" %{php_ini_file}
cat >> %{php_ini_file} <<EOF
include_path = ".:/opt/wso2/wsf_php/scripts/"

[wsf]
extension=wsf.so
wsf.home="/opt/wso2/wsf_c"
wsf.log_level=0
wsf.log_path="/tmp"
wsf.rm_db_dir="/tmp"
EOF

echo "Copied samples folders in /opt/wso2/wsf_php to your Web Root " %{www_dir_content}
echo "Added directory /opt/wso2/wsf_php/scripts/ to include_path directory"
echo "eg. include_path = \".:/opt/wso2/wsf_php/scripts/\" "

##
# Pre Uninstall
##
%preun

##
# Post Uninstall
##
%postun
# Running ldconfig
echo "Running ldconfig..."
/sbin/ldconfig

##
# Files
##
%files
%{extension_dir}/wsf.so
%attr(755,root,root)
/opt/wso2/wsf_php
/opt/wso2/wsf_c
/etc/profile.d/wsf.sh
/etc/ld.so.conf.d/wsf.conf
%{www_dir_content}
%doc COPYING AUTHORS README NEWS

##
# Changelog
##
%changelog
* Tue Mar 10 2015 Hasan UCAK <hasan.ucak@gmail.com>
- First draft of the spec file
