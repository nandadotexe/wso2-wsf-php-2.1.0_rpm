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

try {

    // create client in WSDL mode
    $client = new WSClient(array ("wsdl" =>"http://192.168.110.103/fileUploadServer/FileUploadWSDL.php",
        "classmap" => $class_map));

    // get proxy object reference form client 
    $proxy = $client->getProxy();

    // create input object and set values
    $input = new FileLoad();
    //TODO: fill in the class fields of $input to match your business logic
    
    $base64Binary = new base64Binary();
    //1.file 
    $base64Binary->fileName = "axis2-apache.spec";
    $base64Binary->value = file_get_contents("../axis2-apache.spec");

    $input->fileList[] = $base64Binary;

    //2.file 
    $base64Binary->fileName = "README.md";
    $base64Binary->value = file_get_contents("../README.md");
    $input->fileList[] = $base64Binary;
   
// call the operation
    $response = $proxy->FileLoad($input);
    //TODO: Implement business logic to consume $response, which is of type FileLoadResponse
    print "Return : ".var_export($response,true);
} catch (Exception $e) {
    // in case of an error, process the fault
    if ($e instanceof WSFault) {
        printf("Soap Fault: %s\n", $e->Reason);
    } else {
        printf("Message = %s\n", $e->getMessage());
    }
}
?>
