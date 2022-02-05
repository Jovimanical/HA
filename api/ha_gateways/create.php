<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
// get database connection
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
 
// instantiate ha_gateways object
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_gateways.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
$database = new Database();
$db = $database->getConnection();
 
$ha_gateways = new Ha_Gateways($db);
 
// get posted data
$data = json_decode(file_get_contents("php://input"));
 
// make sure data is not empty
if(!isEmpty($data->name)
&&!isEmpty($data->alias)
&&!isEmpty($data->status)
&&!isEmpty($data->crypto)){
 
    // set ha_gateways property values
	 
$ha_gateways->code = $data->code;
if(!isEmpty($data->name)) { 
$ha_gateways->name = $data->name;
} else { 
$ha_gateways->name = '';
}
if(!isEmpty($data->alias)) { 
$ha_gateways->alias = $data->alias;
} else { 
$ha_gateways->alias = 'NULL';
}
$ha_gateways->image = $data->image;
if(!isEmpty($data->status)) { 
$ha_gateways->status = $data->status;
} else { 
$ha_gateways->status = '1';
}
$ha_gateways->gateway_parameters = $data->gateway_parameters;
$ha_gateways->supported_currencies = $data->supported_currencies;
if(!isEmpty($data->crypto)) { 
$ha_gateways->crypto = $data->crypto;
} else { 
$ha_gateways->crypto = '0';
}
$ha_gateways->extra = $data->extra;
$ha_gateways->description = $data->description;
$ha_gateways->input_form = $data->input_form;
$ha_gateways->created_at = $data->created_at;
$ha_gateways->updated_at = $data->updated_at;
 	$lastInsertedId=$ha_gateways->create();
    // create the ha_gateways
    if($lastInsertedId!=0){
 
        // set response code - 201 created
        http_response_code(201);
 
        // tell the user
        echo json_encode(array("status" => "success", "code" => 1,"message"=> "Created Successfully","data"=> $lastInsertedId));
    }
 
    // if unable to create the ha_gateways, tell the user
    else{
 
        // set response code - 503 service unavailable
        http_response_code(503);
 
        // tell the user
		echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_gateways","data"=> ""));
    }
}
 
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to create ha_gateways. Data is incomplete.","data"=> ""));
}
?>
