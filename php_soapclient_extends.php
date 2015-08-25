<?php

/*
 * when method call from mtom web service get error "PHP Fatal error:  Uncaught SoapFault exception: [Client] looks like we got no XML document in"
 * cause : response data is mime format , is not xml format 
 * because get xml data in mime data ,we parse response data.
 * 
 * HASAN UCAK 
 * hasan.ucak@gmail.com
 */

define("CRLF","\r\n");

class __mtomparts {

    public $content_transfer_encoding = '';
    public $content_id = '';
    public $content_type = '';
    public $data = '';
    public $xml = false;

}

class HuSoapClient extends SoapClient
{
     
  public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        mb_internal_encoding("utf-8");
        $response = parent::__doRequest($request, $location, $action, $version, $one_way);
        /* parse $response, extract the multipart messages and so on
         * this part removes stuff
         * Message is multipart structure near as mtom message format.
         */
        if (substr($response, 0, 2) == '--') {
            $response_string = '';
            $parts = array();
            $mtompart = new __mtomparts();
            $boundry = '';
            $content_transfer_encoding = "";
            $response_arr = explode(CRLF, $response);
            $boundry = $response_arr[0];
            $endheader = false;
            //$index = 1 ...clear mime boundry line
            for ($index = 1; $index < count($response_arr) - 1; $index++) {
                if ($response_arr[$index] == $boundry) {
                    $mtompart->data = $response_string;
                    $response_string = '';
                    $parts[] = $mtompart;
                    $mtompart = new __mtomparts();
                    $endheader = false;
                    continue;
                }
                if ($endheader == false && $response_arr[$index] == "")
                    $endheader = true;
                if ($endheader == true)
                    $response_string .= $response_arr[$index] . CRLF;
                else {
                    $arr = explode(":", $response_arr[$index]);
                    switch (strtolower($arr[0])) {
                        case "content-transfer-encoding":
                            $mtompart->content_transfer_encoding = trim($arr[1]);
                            break;
                        case "content-id":
                            $mtompart->content_id = trim($arr[1]);
                            break;
                        case "content-type":
                            $mtompart->content_type = trim($arr[1]);
                            $types = explode(";", trim($mtompart->content_type));
                            if ($types[0] == 'application/xop+xml' || (isset($types[2]) && $types[2] == 'type="text/xml"'))
                                $mtompart->xml = true;
                            break;
                    }
                }
            }
            $mtompart->data = $response_string;
            $parts[] = $mtompart;
            //decode response data
            foreach ($parts as $key => $part) {
                if ($part->xml == true) {
                    $parts[$key]->data = mb_convert_encoding($part->data, "utf-8");
                    switch ($content_transfer_encoding) {
                        case "binary":
                            $parts[$key]->data = $part->data;
                            break;
                        case "base64":
                            $parts[$key]->data = base64_decode($part->data);
                            break;
                        case "quoted-printable":
                            $parts[$key]->data = imap_qprint($part->data);
                            break;
                    }
                }
            }

            if (count($parts) > 1)
                if (preg_match_all('/<xop:Include.*?([^<xop:Include]*?)<\/xop:Include>/si', trim($parts[0]->data), $matches, PREG_SET_ORDER)) {
                    foreach ($matches as $value) {
                        if (preg_match('/^<(.*|):Include href=\"cid:(.*)\"><\/(.*|):Include>/si', trim($value[0]), $match)) {
                            foreach ($parts as $part) {
                                if ($part->content_id == "<" . $match[2] . ">")
                                    $parts[0]->data = str_replace($match[0], base64_encode(trim($part->data)), $parts[0]->data);
//                                    $parts[0]->data = str_replace($match[0], "<ciddata>" . base64_encode(trim($part->data)) . "</ciddata>", $parts[0]->data);
                            }
                        }
                    }
                }
            return $parts[0]->data;
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
