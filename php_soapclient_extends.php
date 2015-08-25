<?php

/*
 * when method call from mtom web service get error "PHP Fatal error:  Uncaught SoapFault exception: [Client] looks like we got no XML document in"
 * cause : response data is mime format , is not xml format 
 * because get xml data in mime data ,we parse response data.
 * 
 */

define("CRLF","\r\n");
class HuSoapClient extends SoapClient
{
     
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        // parse $response, extract the multipart messages and so on
        
        //this part removes stuff
        $response_string='';
        //Message is multipart structure near as mtom message format. (RFC 1341)
        if (substr($response,0,2) == '--'){
            $response_arr = explode(CRLF, $response);
           // var_dump($response_arr);
            $endheader = false;
            //$index = 1 ...clear mime boundry line
            for ($index = 1; $index < count($response_arr)-1; $index++) {
                if ($endheader==false && $response_arr[$index] == "")
                    $endheader = true;
                if ($endheader == true)
                $response_string .= $response_arr[$index];
                    
            }
            return $response_string;
        }
        $start=strpos($response,'<?xml');
        $end=strrpos($response,'>');    
        $response_string=substr($response,$start,$end-$start+1);
        return($response_string);
    }
}


$client = new HuSoapClient($wsdl_uri);
//advice 
//$client->method_name($parameters)
