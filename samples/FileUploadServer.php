<?php

// PHP classes corresponding to the data types in defined in WSDL

class FileLoad {

    /**
     * @var array[0, unbounded] of (object)base64Binary
     */
    public $fileList;

}

class base64Binary {

    /**
     * @var string
     *     NOTE: contentType should follow the following restrictions
     *     Your length of the value should be 
     *     Greater than 3
     */
    public $contentType;

    /**
     * @var string
     *     NOTE: fileName should follow the following restrictions
     *     Your length of the value should be 
     *     Greater than 3
     */
    public $fileName;

    // The "value" represents the element 'fileList' value..
    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $value;

    /**
     * @var base64Binary
     */
    public $value_encoded;

}

class FileLoadResponse {

    /**
     * @var int
     */
    public $return;

}

// define the class map
$class_map = array(
    "FileLoad" => "FileLoad",
    "base64Binary" => "base64Binary",
    "FileLoadResponse" => "FileLoadResponse");

// define PHP functions that maps to WSDL operations 
/**
 * Service function FileLoad
 * @param object of FileLoad $input 
 * @return object of FileLoadResponse 
 */
function FileLoad($input) {
    // TODO: fill in the business logic
    // NOTE: $input is of type FileLoad
    // NOTE: should return an object of type FileLoadResponse
    error_log(var_export($input,true));
    foreach ($input->fileList as $key => $base64BinaryValue) {
        $ret = 1;
        $filename = $base64BinaryValue->fileName;
        if (empty($base64BinaryValue->fileName))
            $filename = uniqid("tmpfile");
        if (!empty($base64BinaryValue->value)) {
            if (file_put_contents($filename, $base64BinaryValue->value) === false)
                $ret = 0;
        }
    }
    $response = new FileLoadResponse();
    $response->return = $ret;
    return $response;
}

// define the operations map
$operations = array(
    "FileLoad" => "FileLoad");

// define the actions => operations map
$actions = array(
    "urn:FileLoad" => "FileLoad");

//Find to right wsdl links
$hostnameIpPort = ($_SERVER["SERVER_PORT"] == 443 ? "https://" : "http://") . $_SERVER["HTTP_HOST"] . ($_SERVER["SERVER_PORT"] == 80 ? '' : ($_SERVER["SERVER_PORT"] == 443 ? '' : $_SERVER["SERVER_PORT"]));
$wdlUrl = $hostnameIpPort . pathinfo($_SERVER['REQUEST_URI'], PATHINFO_DIRNAME) . "/FileUploadWSDL.php";

// create service in WSDL mode
$service = new WSService(array("wsdl" => $wdlUrl,
    "actions" => $actions,
    "classmap" => $class_map,
    "operations" => $operations,
    "cacheWSDL" => true,
    "useMTOM" => true));

// process client requests and reply 
$service->reply();
?>
