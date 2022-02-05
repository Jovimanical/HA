<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
require $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/helper.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_gateways.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_gateways object
$ha_gateways = new Ha_Gateways($db);
 
// set ID property of record to read
$ha_gateways->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_gateways to be edited
$ha_gateways->readOne();
 
if($ha_gateways->id!=null){
    // create array
    $ha_gateways_arr = array(
        
"id" => $ha_gateways->id,
"code" => $ha_gateways->code,
"name" => $ha_gateways->name,
"alias" => $ha_gateways->alias,
"image" => html_entity_decode($ha_gateways->image),
"status" => $ha_gateways->status,
"gateway_parameters" => $ha_gateways->gateway_parameters,
"supported_currencies" => $ha_gateways->supported_currencies,
"crypto" => $ha_gateways->crypto,
"extra" => $ha_gateways->extra,
"description" => $ha_gateways->description,
"input_form" => $ha_gateways->input_form,
"created_at" => $ha_gateways->created_at,
"updated_at" => $ha_gateways->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_gateways found","data"=> $ha_gateways_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_gateways does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_gateways does not exist.","data"=> ""));
}
?>
