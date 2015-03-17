# wso2-wsf-php-2.1.0_rpm
Centos 6.5  , PHP , Apache , wsf,  wso2 , php , Web service Server, Web Service Client, php web service framework , axis2 , rampart , xml , wsdl ,sandesha2 , mtom

create axis2c-apache  rpm command line 
rpmbuild -bb -vv --buildroot "/tmp/axis2c-src-1.6.0-1.el6.x86_64" SPECS/axis2-apache.spec>axis2-apache.spec.log 2>&1

create wso2-php rpm command line for lower php 5.4 version
rpmbuild -bb -vv --buildroot "/tmp/wso2-wsf-php53-2.1.0-1.el6.x86_64" SPECS/wsf-apache-server.spec>wsf-apache-server.spec.log 2>&1

requirements..................................................
httpd => 2.2.15
Php =<5.4 (5.3.3)
