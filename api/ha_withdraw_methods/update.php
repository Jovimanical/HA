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
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/database.php';
include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/objects/ha_withdraw_methods.php';
 include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/token/validatetoken.php';
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// prepare ha_withdraw_methods object
$ha_withdraw_methods = new Ha_Withdraw_Methods($db);
 
// get id of ha_withdraw_methods to be edited
$data = json_decode(file_get_contents("php://input"));
 
// set ID property of ha_withdraw_methods to be edited
$ha_withdraw_methods->id = $data->id;

if(
!isEmpty($data->max_limit)
&&!isEmpty($data->status)
){
// set ha_withdraw_methods property values

$ha_withdraw_methods->name = $data->name;
$ha_withdraw_methods->image = $data->image;
$ha_withdraw_methods->min_limit = $data->min_limit;
if(!isEmpty($data->max_limit)) { 
$ha_withdraw_methods->max_limit = $data->max_limit;
} else { 
$ha_withdraw_methods->max_limit = '0.00000000';
}
$ha_withdraw_methods->delay = $data->delay;
$ha_withdraw_methods->fixed_charge = $data->fixed_charge;
$ha_withdraw_methods->rate = $data->rate;
$ha_withdraw_methods->percent_charge = $data->percent_charge;
$ha_withdraw_methods->currency = $data->currency;
$ha_withdraw_methods->user_data = $data->user_data;
$ha_withdraw_methods->description = $data->description;
if(!isEmpty($data->status)) { 
$ha_withdraw_methods->status = $data->status;
} else { 
$ha_withdraw_methods->status = '1';
}
$ha_withdraw_methods->created_at = $data->created_at;
$ha_withdraw_methods->updated_at = $data->updated_at;
 
// update the ha_withdraw_methods
if($ha_withdraw_methods->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
	echo json_encode(array("status" => "success", "code" => 1,"message"=> "Updated Successfully","data"=> ""));
}
 
// if unable to update the ha_withdraw_methods, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_withdraw_methods","data"=> ""));
    
}
}
// tell the user data is incomplete
else{
 
    // set response code - 400 bad request
    http_response_code(400);
 
    // tell the user
	echo json_encode(array("status" => "error", "code" => 0,"message"=> "Unable to update ha_withdraw_methods. Data is incomplete.","data"=> ""));
}
?>
