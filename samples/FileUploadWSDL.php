<?php
$hostnameIpPort = ($_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . ($_SERVER["SERVER_PORT"] == 80 ? '' : ($_SERVER["SERVER_PORT"] == 443 ? '' : $_SERVER["SERVER_PORT"]));

$wdlUrl = $hostnameIpPort.pathinfo($_SERVER['REQUEST_URI'],PATHINFO_DIRNAME)."/FileUploadServer.php";
header("Content-type: text/xml");
?>
<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions
    xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/"
    xmlns:ns1="http://ws.apache.org/axis2/xsd"
    xmlns:wsaw="http://www.w3.org/2006/05/addressing/wsdl"
    xmlns:http="http://schemas.xmlsoap.org/wsdl/http/"
    xmlns:ns0="http://ws.apache.org/axis2"
    xmlns:xs="http://www.w3.org/2001/XMLSchema"
    xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/"
    xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/"
    xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/"
    targetNamespace="http://ws.apache.org/axis2"
    xmlns:xmime="http://www.w3.org/2005/05/xmlmime">
    <wsdl:types>
        <xs:schema xmlns:ns="http://ws.apache.org/axis2" attributeFormDefault="qualified" elementFormDefault="qualified" targetNamespace="http://ws.apache.org/axis2">
            <xs:import namespace="http://www.w3.org/2005/05/xmlmime"
                       schemaLocation="xmime.xsd"/>
            <xs:element name="FileLoad">
                <xs:complexType>
                    <xs:sequence>
                        <xs:element maxOccurs="unbounded" minOccurs="0" name="fileList" type="xmime:base64Binary"/>
                    </xs:sequence>
                </xs:complexType>
            </xs:element>
            <xs:element name="FileLoadResponse">
                <xs:complexType>
                    <xs:sequence>
                        <xs:element minOccurs="0" name="return" nillable="false" type="xs:int"/>
                    </xs:sequence>
                </xs:complexType>
            </xs:element>
    </xs:schema>        
    </wsdl:types>
    <wsdl:message name="FileLoadRequest">
        <wsdl:part name="parameters" element="ns0:FileLoad"/>
    </wsdl:message>
    <wsdl:message name="FileLoadResponse">
        <wsdl:part name="parameters" element="ns0:FileLoadResponse"/>
    </wsdl:message>
    <wsdl:portType name="flServicePortType">
        <wsdl:operation name="FileLoad">
            <wsdl:input message="ns0:FileLoadRequest" wsaw:Action="urn:FileLoad"/>
            <wsdl:output message="ns0:FileLoadResponse" wsaw:Action="urn:FileLoadResponse"/>
        </wsdl:operation>
 
    </wsdl:portType>
    <wsdl:binding name="flServiceSOAPBinding" type="ns0:flServicePortType">
        <soap:binding transport="http://schemas.xmlsoap.org/soap/http" style="document"/>
        <wsdl:operation name="FileLoad">
            <soap:operation soapAction="urn:FileLoad" style="document"/>
            <wsdl:input>
                <soap:body use="literal"/>
            </wsdl:input>
            <wsdl:output>
                <soap:body use="literal"/>
            </wsdl:output>
        </wsdl:operation>
    </wsdl:binding>
   <wsdl:service name="flService">
        <wsdl:port name="flServiceSOAPport_http" binding="ns0:flServiceSOAPBinding">
            <soap:address location="<?php print $wdlUrl;?>"/>
        </wsdl:port>
    </wsdl:service>
</wsdl:definitions>
