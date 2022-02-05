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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdraw_methods.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_withdraw_methods object
$ha_withdraw_methods = new Ha_Withdraw_Methods($db);
 
// set ID property of record to read
$ha_withdraw_methods->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of ha_withdraw_methods to be edited
$ha_withdraw_methods->readOne();
 
if($ha_withdraw_methods->id!=null){
    // create array
    $ha_withdraw_methods_arr = array(
        
"id" => $ha_withdraw_methods->id,
"name" => $ha_withdraw_methods->name,
"image" => html_entity_decode($ha_withdraw_methods->image),
"min_limit" => $ha_withdraw_methods->min_limit,
"max_limit" => $ha_withdraw_methods->max_limit,
"delay" => $ha_withdraw_methods->delay,
"fixed_charge" => $ha_withdraw_methods->fixed_charge,
"rate" => $ha_withdraw_methods->rate,
"percent_charge" => $ha_withdraw_methods->percent_charge,
"currency" => $ha_withdraw_methods->currency,
"user_data" => $ha_withdraw_methods->user_data,
"description" => $ha_withdraw_methods->description,
"status" => $ha_withdraw_methods->status,
"created_at" => $ha_withdraw_methods->created_at,
"updated_at" => $ha_withdraw_methods->updated_at
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
   echo json_encode(array("status" => "success", "code" => 1,"message"=> "ha_withdraw_methods found","data"=> $ha_withdraw_methods_arr));
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user ha_withdraw_methods does not exist
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "ha_withdraw_methods does not exist.","data"=> ""));
}
?>
