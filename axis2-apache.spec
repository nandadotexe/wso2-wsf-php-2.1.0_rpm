## 
# Apache Axis2/C is a Web services engine implemented in the C programming language.
##
%define version 1.6.0
%define _unpackaged_files_terminate_build 0
%define __find_requires %{nil}
Summary: Apache Axis2/C (1.6.0 Release)
Name: Axis2c
Vendor: Apache Axis2/C http://axis.apache.org/axis2/c/core/index.html
Version: %{version} 
Release: 1%{?dist}
Source: http://archive.apache.org/dist/ws/axis2/c/1_6_0/axis2c-src-1.6.0.tar.gz
URL: http://axis.apache.org/axis2/c/core/index.html
License: Apache License V2.0
Group: Development/Tools
Packager: Hasan UCAK <hasan.ucak@gmail.com>

Provides: axis2c = %{version}-%{release}
Buildroot: /tmp/apache-axis2c-%{version}-%{release}
AutoReqProv: no 

%description
Apache Axis2/C is a Web services engine implemented in the C programming language. It is based on the extensible and flexible Axis2 architecture. Apache Axis2/C can be used to provide and consume WebServices. It has been implemented with portability and ability to embed in mind, hence could be used as a Web services enabler in other software.

##
# Prepare
##
%prep

##
# Setup
##
%setup -q -n axis2c-src-%{version}

##
# Build
##
%build
export PATH=./bin/:$PATH
export CFLAGS="-O2"

./configure
make

##
# Install
##
%install
export QA_SKIP_BUILD_ROOT=1
rm -fr $RPM_BUILD_ROOT
make ROOT="$RPM_BUILD_ROOT" install
mkdir -p $RPM_BUILD_ROOT

##
# Clean
##

##
# Post
##
%post

# Running ldconfig 
echo "Running ldconfig..."
/sbin/ldconfig

# Verify WSF/PHP Module
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
%doc COPYING AUTHORS README NEWS

##
# Changelog
##
%changelog
* Tue Mar 16 2015 Hasan UCAK <hasan.ucak@gmail.com>
- First draft of the spec file
