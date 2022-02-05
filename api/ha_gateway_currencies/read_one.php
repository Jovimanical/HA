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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_gateway_currencies.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_gateway_currencies object
$ha_gateway_currencies = new Ha_Gateway_Currencies($db);
 
// set ID property of record to read
$ha_gateway_currencies->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_gateway_currencies to be edited
$ha_gateway_currencies->readOne();
 
if($ha_gateway_currencies->id!=null){
    // create array
    $ha_gateway_currencies_arr = array(
        
"id" => $ha_gateway_currencies->id,
"name" => $ha_gateway_currencies->name,
"currency" => $ha_gateway_currencies->currency,
"symbol" => $ha_gateway_currencies->symbol,
"method_code" => $ha_gateway_currencies->method_code,
"gateway_alias" => $ha_gateway_currencies->gateway_alias,
"min_amount" => $ha_gateway_currencies->min_amount,
"max_amount" => $ha_gateway_currencies->max_amount,
"percent_charge" => $ha_gateway_currencies->percent_charge,
"fixed_charge" => $ha_gateway_currencies->fixed_charge,
"rate" => $ha_gateway_currencies->rate,
"image" => html_entity_decode($ha_gateway_currencies->image),
"gateway_parameter" => $ha_gateway_currencies->gateway_parameter,
"created_at" => $ha_gateway_currencies->created_at,
"updated_at" => $ha_gateway_currencies->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_gateway_currencies found","data"=> $ha_gateway_currencies_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_gateway_currencies does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_gateway_currencies does not exist.","data"=> ""));
}
?>
